<?php
/**
 * 广告排序控制器 1.2 上线初始化 3 定时脚本更新
 * step 1 ./yii ad-sort/init-ad-sort
 * step 2 ./yii ad-sort/init-update-status
 * step 3 ./yii ad-sort/update-sort
 *
 * //后续排序列表添加国家  ./yii ad-sort/init-add-ad-sort US,AD,AE
 *
 * //广告app是否在库中存在 app是否在列表中重复检测 替换操作
 * ./yii ad-sort/app-check
 */

namespace app\commands;

use app\modules\appcake\models\AdSortHistory;
use app\modules\appcake\models\AdSortOne;
use app\modules\appcake\models\AdSortTwo;
use app\modules\appcake\models\AppData;
use app\modules\appcake\models\CountryAd;
use app\modules\appcake\models\DownloadWeek;
use app\modules\appcake\models\search\AdSortRecord;
use app\modules\appcake\models\search\Bat;
use app\modules\appcake\models\search\DataSummary;
use app\modules\appcake\models\TaskStatus;
use yii\console\Controller;
use app\modules\appcake\models\CountriesApp;
use app\modules\appcake\models\AdCountry;
use Yii;
class AdSortController extends Controller
{
    //更新排序提前时间 默认提前5分钟更新
    const BEFOR_TIME = 5;
    //刷新排序间隔时间
    const INTERVAL_TIME = 60;
    //当前投放广告国家列表
    public $currentAdCountry = array();

    //国家->广告/非广告对应最大排序数
    public $adSortMaxNum = array();

    //非广告最大位置数
    public $notAdSortMaxNum = array();

    //每个国家列表已存在appid
    public $appId = array();

    //当前前任务表 初始化生成列表规则 基数1表 偶数2表
    public $currentTable = '';

    public $nextTable = '';


    /**
     * 初始化生成排序列表
     */
    public function actionInitAdSort()
    {

        //查询列表是否已经生成过
        $adSortStatus = $this->getTaskStatus();
        if(isset($adSortStatus['is_init_ad_sort']) && $adSortStatus['is_init_ad_sort'] == 1){
            $logstr = "初始化广告列表已生成过,无需再次生成.";
            echo $logstr;
            Yii::info(date("Y-m-d H:i:s",time()) ."$logstr \n\r",'initadsort');die;
        }

        //提取当前投放广告所有国家
        //$this->currentAdCountry = $this->getCurrentAdCountry($ad);
        //$this->currentAdCountry = array('US','AD','AE');
        $this->currentAdCountry = $this->getCountrylist();

        $ad = $this->getCurrentAdByCountry($this->currentAdCountry);

        if(!empty($ad)){

            $this->initCurrentTable();


            //查询广告应用栏目
            $ad = $this->getCategoryByAd($ad);

            //当前广告投排序写入操作
            $maxNum = $this->AdSort($ad);
            unset($ad);

            //当前非广告排序写入操作 根据历史下载量拉取200条数据
            $this->NotAdSort();

            //补充app  每个国家补满200位排序
            $this->AddFreeApp();

            //克隆表
            $this->cloneInitTable();

            //写入任务执行状态信息
            $this->recordInitStatus();

        }else{
            echo '当前投放广告为空';
        }
    }


    /*
     * 新添加国家到排序列表
     */
    public function actionInitAddAdSort()
    {
        $country = isset($_SERVER['argv'][2]) ? $_SERVER['argv'][2] : '';
        if(!$country){
            echo '国家为空 任务停止';exit();
        }
        $country = array_unique(explode(',',$country));

        //查询国家是否已经在列表中存在
        $country = $this->isExistInList($country);

        //提取当前投放广告所有国家
        $this->currentAdCountry = $country;

        $ad = $this->getCurrentAdByCountry($country);
        if(!empty($ad)){

            //查询列表是否已经生成过
            $adSortStatus = $this->getTaskStatus();
            $this->currentTable = $adSortStatus['ad_sort_current_table'];

            //查询广告应用栏目
            $ad = $this->getCategoryByAd($ad);


            //当前广告投排序写入操作
            $maxNum = $this->AdSort($ad);
            unset($ad);

            //当前非广告排序写入操作 根据历史下载量拉取200条数据
            $this->NotAdSort();

            //补充app  每个国家补满200位排序
            $this->AddFreeApp();

            echo '添加任务完成';
        }else{
            echo '当前投放广告为空';
        }
    }

    /**
     * 定时任务 更新排序
     * 2016-07-06 之前由定时任务负责更新排序 现修改为 定时任务只负责同步表和切换表数据功能
     * 更新排序操作转在发布排序是同步更新
     */
    public function actionUpdateSort()
    {
        //读取任务执行状态
        $adSortStatus = $this->getTaskStatus();
        //是否到执行更新任务时间
        $this->isUpdateTime($adSortStatus);
        //是否锁定
        $this->isLockUpdateSort($adSortStatus);

        //更新之前将当前排序更新为上个小时排序 并将当前排序写入历史表
        $this->updateLastSort($adSortStatus);

        //是否发布状态
        $this->isPost($adSortStatus);


        //查询未执行更新的任务 小于1小时内的
        //$updateData = $this->getUpdateRecord();

        //获取未更新的操作记录
        $recordModel = new AdSortRecord();
        $recordData = $recordModel->getrecordToUpdate();

        $nextUpdateTime = strtotime($adSortStatus['ad_sort_next_time']);

        $current_table = $adSortStatus['ad_sort_current_table'];

        if(!empty($recordData)){
            //遍历更新 排序
            foreach($recordData as $k => $v){

                if($v['update_method'] == 1 ) continue;     //即时生效跳过
                if($v['update_method'] == 2  && strtotime($v['start_time']) > $nextUpdateTime) continue;  //生效时间未到 跳过任务

                //查询任务实际位置 存在当前位置已发生变化问题
                if($k >= 1){
                    $vCurrent =  $this->getCurrentSortById($current_table,$v['sort_id']);
                    if($vCurrent == true) $v['next_sort'] = $vCurrent['next_sort'];
                }
                if(!$v['next_sort']){
                    //国家 下次排序空的 跳过
                    continue;
                }

                //根据排序方式执行排序
                if($v['sort_method'] == 1){         //自动排序
                    $this->autoSort($adSortStatus,$v);
                }else if($v['sort_method'] == 2){  //对换位置
                    $this->changeSort($adSortStatus,$v);
                }else if($v['sort_method'] == 3){   //新添加
                    $this->addSort($adSortStatus,$v);
                }
            }
        }
        else //不修改排序 同步数据
        {
            $logstr = "修改排序任务数0.";
            echo $logstr;
            Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'updateadsort');
        }

        //同步表数据 解锁更新任务状态
        $this->updateAdSortTable($adSortStatus);
        $logstr = "更新排序操作任务执行完毕\n\r";
        echo $logstr;
        Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'updateadsort');die;

    }


    public function getCurrentSortById($current_table,$id){
        if($current_table == 'ad_sort_one'){
            $data = AdSortOne::find()->where(['id'=> $id])->asArray()->one();
        }else{
            $data = AdSortTwo::find()->where(['id'=> $id])->asArray()->one();
        }
        return $data;

    }

    public function isExistInList($country)
    {
        foreach($country as $k => $v)
        {
            $res = AdSortOne::find()->where(" country = '{$v}' ")->asArray()->one();
            if($res){
                echo "国家{$v}在列表中存在不予添加\n\r";
                unset($country[$k]);
            }
        }
        return $country;
    }
    /**
     *  生成排序列表方法    ///////////////////////////////
     */

    /**
     * 当前投放广告排序入库
     */
    public function AdSort($ad)
    {
        $data = array();
        foreach($ad as $k => $v){
            $appId[$v['country']][] = (int)$v['app_id'];
            $data[$v['country']][] = $v;
        }
        unset($ad);
//        $position = Yii::$app->params['ad_sort_position'];
//        //查询现在投放广告历史下载量数据 做排序依据
//        $dataModel = new DataSummary();
//        $adDownloadData = $dataModel->getAdDownloadNum($ad);
//
//        //按下载量排序
//        $this->sortArrByField($adDownloadData);
//
//        //将数据按国家汇总
//        $data = array();
//        $appId = array();
//        foreach($adDownloadData as $k => $v){
//            $appId[$v['country']][] = (int)$v['app_id'];
//            $data[$v['country']][] = $v;
//        }
//        unset($adDownloadData);



        //生成现有投放广告国家 广告列表
        $adIndexMaxNum = array();
        $sql = '';  $sqlNum = 0;    $times = 0;
        foreach($this->currentAdCountry as $k => $v){

            $gameIndex = 0; $appIndex = 0;
            if(isset($data[$v]) && count($data[$v]) > 0 ){

                if(isset($adIndexMaxNum[$v]) && $adIndexMaxNum[$v] > 200
                    && isset($adAppMaxNum[$v]) && $adAppMaxNum[$v] > 200
                    && isset($adGameMaxNum[$v]) && $adGameMaxNum[$v] > 200
                )
                {
                    break;
                }

                foreach($data[$v] as $kk => $vv){
                    //首页排序列表
                    if(!isset($adIndexMaxNum[$v]) || $adIndexMaxNum[$v] <= 200 ){
                        $sort = $kk * 2 + 1;
                        $sqlNum ++ ;
                        $adIndexMaxNum[$v] = $sort;
                        $sql .= "INSERT INTO `{$this->currentTable}` VALUES
                                  (0, {$vv['camp_id']}, {$vv['app_id']}, '{$v}', 0, {$vv['source']}, {$sort}, 1, null,null);";
                    }
                    //应用 游戏列表
                    if(!empty($vv['category'])){
                        if(strtoupper($vv['category']) == "GAMES"){
                            if(!isset($adGameMaxNum[$v]) || $adGameMaxNum[$v] <= 200 ) {
                                $gameSort = $gameIndex * 2 + 1;
                                $gameIndex++;
                                $adGameMaxNum[$v] = $gameSort;
                                $sqlNum++;
                                $sql .= "INSERT INTO `{$this->currentTable}` VALUES
                                      (0, {$vv['camp_id']}, {$vv['app_id']}, '{$v}', 2, {$vv['source']}, {$gameSort}, 1, null,null);";
                            }
                        }else{
                            if(!isset($adAppMaxNum[$v]) || $adAppMaxNum[$v] <= 200 ) {
                                $appSort = $appIndex * 2 + 1;
                                $appIndex++;
                                $adAppMaxNum[$v] = $appSort;
                                $sqlNum++;
                                $sql .= "INSERT INTO `{$this->currentTable}` VALUES
                                      (0, {$vv['camp_id']}, {$vv['app_id']}, '{$v}', 1, {$vv['source']}, {$appSort}, 1, null,null);";
                            }
                        }
                    }
                    //1000条写入一次
                    if($sqlNum >=200){
                        $this->InsertBysql($sql);
                        $sqlNum = 0;
                        $sql = '';
                        $times ++ ;
                    }

                }

            }else{
                $adIndexMaxNum[$v] = 0;
            }
        }
        $this->InsertBysql($sql);
        $logstr = "广告列表排序写入完毕.共执行 {$k} 个国家,执行了{$times} * 200条 + {$sqlNum}条sql \n\r";
        echo $logstr;
        Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'initadsort');
        unset($sql);unset($sort);unset($gameSort);unset($appSort);
        $this->adSortMaxNum = array(
            0 => isset($adIndexMaxNum) ? $adIndexMaxNum : array(),
            1 => isset($adAppMaxNum) ? $adAppMaxNum : array(),
            2 => isset($adGameMaxNum) ? $adGameMaxNum : array(),
        );
        $this->appId = $appId;
        return true;

    }

    /**
     * 非广告排序入库
     */
    public function NotAdSort()
    {
        $appId = $this->appId;

        //查询非广告app广告历史下载量数据 做排序依据
        $dataModel = new DataSummary();
        $DownloadData = $dataModel->getNotAdDownloadNum($this->currentAdCountry,$appId);

        //生成现有投放广告国家 广告列表
        $sql = '';  $sqlNum = 0;    $times = 0; $sortMaxNum = array();
        foreach($this->currentAdCountry as $k => $v){
            $gameIndex = 0; $appIndex = 0;
            if(isset($DownloadData[$v]) && count($DownloadData[$v]) > 0){

                if(
                    isset($IndexMaxNum[$v]) && $IndexMaxNum[$v] > 200
                    && isset($appGameMaxNum[$v]) && $appGameMaxNum[$v] > 200
                    && isset($appMaxNum[$v]) && $appMaxNum[$v] > 200
                )
                {
                    break;
                }

                //按下载量排序
                $this->sortArrByField($DownloadData[$v]);
                foreach($DownloadData[$v] as $kk => $vv){
                    if(!isset($IndexMaxNum[$v]) || $IndexMaxNum[$v] < 200 ){
                        $appId[$v][] = (int)$vv['app_id'];
                        $sort = ($kk + 1) * 2;
                        $IndexMaxNum[$v] = $sort;
                        $sqlNum ++ ;
                        $sql .= "INSERT INTO `{$this->currentTable}` VALUES (0, 0, {$vv['app_id']}, '{$v}', 0, null, {$sort}, 0, null,null);";
                    }
                    //应用 游戏列表
                    if(!empty($vv['category'])){
                        if(strtoupper($vv['category']) == "GAMES"){
                            if(!isset($appGameMaxNum[$v]) || $appGameMaxNum[$v] < 200 ) {
                                $gameIndex++;
                                $gameSort = $gameIndex * 2;

                                $appGameMaxNum[$v] = $gameSort;
                                $sqlNum++;
                                $sql .= "INSERT INTO `{$this->currentTable}` VALUES (0, 0, {$vv['app_id']}, '{$v}', 2, null, {$gameSort}, 0, null,null);";
                            }
                        }else{
                            if(!isset($appMaxNum[$v]) || $appMaxNum[$v] < 200 ) {
                                $appIndex++;
                                $appSort = $appIndex * 2;

                                $appMaxNum[$v] = $appSort;
                                $sqlNum++;
                                $sql .= "INSERT INTO `{$this->currentTable}` VALUES (0, 0, {$vv['app_id']}, '{$v}', 1, null, {$appSort}, 0, null,null);";
                            }
                        }
                    }

                    //1000条写入一次
                    if($sqlNum >=200){
                        $this->InsertBysql($sql);
                        $sqlNum = 0;
                        $sql = '';
                        $times ++ ;
                    }

                }
            }else{
                $IndexMaxNum[$v] =  0 ;
            }
        }
        $logstr = "非广告列表排序写入完毕.共执行 {$k} 个国家,执行了{$times} * 200条 + {$sqlNum}条sql \n\r";
        echo $logstr;
        $this->InsertBysql($sql);
        Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'initadsort');
        unset($sql);unset($sort);unset($gameSort);unset($appSort);
        $this->notAdSortMaxNum = array(
            0 => isset($IndexMaxNum) ? $IndexMaxNum : array(),
            1 => isset($appMaxNum) ? $appMaxNum : array(),
            2 => isset($appGameMaxNum) ? $appGameMaxNum : array()
        );
        $this->appId = $appId;
        return true;
    }

    /**
     * 补满排序200位
     */
    public function AddFreeApp()
    {
        $appId = $this->appId;
        $country = $this->currentAdCountry;

        foreach($country as $k => $v){

            if(isset($this->adSortMaxNum[$v]) && isset($this->notAdSortMaxNum[$v])){

                if($this->adSortMaxNum[$v] >= 199 && $this->notAdSortMaxNum[$v] >= 200){
                    unset($country[$k]);
                    unset($appId[$k]);
                }
            }
        }

        $appModel = new AppData();
        $appData = $appModel->getAppNotInId($country,$appId);


        //生成现有投放广告国家 广告列表
        $sql = '';  $sqlNum = 0;    $times = 0;


        foreach($country as $k => $v){
            if(isset($appData[$v]) && count($appData[$v]) > 0){


                //广告/非广告3个列表位置 当前最大排序号
                $adStartSort_index = isset($this->adSortMaxNum[0][$v]) ? $this->adSortMaxNum[0][$v] : 0;
                $adStartSort_app = isset($this->adSortMaxNum[1][$v]) ? $this->adSortMaxNum[1][$v] : 0;
                $adStartSort_game = isset($this->adSortMaxNum[2][$v]) ? $this->adSortMaxNum[2][$v] : 0;



                $notAdStartSort_index = isset($this->notAdSortMaxNum[0][$v]) ? $this->notAdSortMaxNum[0][$v] : 0 ;
                $notAdStartSort_app = isset($this->notAdSortMaxNum[1][$v]) ? $this->notAdSortMaxNum[1][$v] : 0 ;
                $notAdStartSort_game = isset($this->notAdSortMaxNum[2][$v]) ? $this->notAdSortMaxNum[2][$v] : 0 ;

                //echo "当前国家  $v 广告最大值$adStartSort 非广告最大值 $notAdStartSort  \n\r";

                //非广告缺少数量
                $adNeed_index =  ceil(( 200 - $adStartSort_index ) / 2 ) ;
                $adNeed_app =  ceil(( 200 - $adStartSort_app ) / 2 ) ;
                $adNeed_game =  ceil(( 200 - $adStartSort_game ) / 2 ) ;

                $adSort_index = 0;  $adSort_app = 0;    $adSort_game = 0;
                $notAdSort_index = 0;   $notAdSort_app = 0;     $notAdSort_game = 0 ;
                $ad_index = 0; $ad_app = 0; $ad_games = 0;
                $notAd_index = 0; $notAd_app = 0; $notAd_games = 0;


                foreach($appData[$v] as $kk => $vv){



                    //排序号
                    $appId[$v][] = (int)$vv['app_id'];

                    $sqlNum ++ ;
                    $notAdStartKey = $adNeed_index + $kk ;


                    //广告写入 基数位置
                    if($adSort_index <= 198 &&  $ad_index < 200){
                        $ad_index ++;
                        $adSort_index = $adStartSort_index  + $ad_index * 2 ; // + 1 - 1; //基数
                        if($adSort_index <= 200){

                            $sql .= "INSERT INTO `{$this->currentTable}`
                                  VALUES (0, 0, {$vv['app_id']}, '{$v}', 0, null, {$adSort_index}, 0, null,null);";
                            $sqlNum ++;
                        }
                    }

                    if(!empty($vv['category']) && strtoupper($vv['category']) != 'GAMES'){
                        $ad_app ++;
                        if($adStartSort_app == 0){
                            $adSort_app = $adStartSort_app + 1  + ($ad_app - 1) * 2 ; // + 1 - 1; //基数
                        }else{
                            $adSort_app = $adStartSort_app + $ad_app * 2 ; // + 1 - 1; //基数
                        }

                        if($adSort_app <= 200){
                            $sql .= "INSERT INTO `{$this->currentTable}`
                                  VALUES (0, 0, {$vv['app_id']}, '{$v}', 1, null, {$adSort_app}, 0, null,null);";
                            $sqlNum ++;
                        }
                    }

                    if(!empty($vv['category']) && strtoupper($vv['category']) == 'GAMES'){
                        $ad_games ++;
                        if($adStartSort_game == 0){
                            $adSort_game = $adStartSort_game + 1  + ($ad_games -1) * 2 ; // + 1 - 1; //基数
                        }else{
                            $adSort_game = $adStartSort_game + $ad_games * 2 ; // + 1 - 1; //基数
                        }
                        if($adSort_game <= 200){
                            $sql .= "INSERT INTO `{$this->currentTable}`
                                  VALUES (0, 0, {$vv['app_id']}, '{$v}', 2, null, {$adSort_game}, 0, null,null);";
                            $sqlNum ++;
                        }
                    }


                    //非广告  偶数位置
                    if(!isset($notAdSort_index) || $notAdSort_index <= 198 ){
                        $notAd_index ++;
                        $notAdSort_index = $notAdStartSort_index  +  $notAd_index * 2 ;  //+ 1;
                        if($notAdSort_index <= 200){
                            $sql .= "INSERT INTO `{$this->currentTable}`
                                  VALUES (0, 0, {$appData[$v][$notAdStartKey]['app_id']}, '{$v}', 0, null, {$notAdSort_index}, 0, null,null);";
                            $sqlNum ++;
                        }
                    }

                    if(!empty($vv['category']) && strtoupper($vv['category']) != 'GAMES'){
                        $notAd_app ++;
                        $notAdSort_app = $notAdStartSort_app  +  $notAd_app * 2 ;  //+ 1;
                        if($notAdSort_app <= 200){
                            $sql .= "INSERT INTO `{$this->currentTable}`
                                  VALUES (0, 0, {$appData[$v][$notAdStartKey]['app_id']}, '{$v}', 1, null, {$notAdSort_app}, 0, null,null);";
                            $sqlNum ++;
                        }
                    }


                    if(!empty($vv['category']) && strtoupper($vv['category']) == 'GAMES'){
                        $notAd_games ++ ;
                        $notAdSort_game = $notAdStartSort_game  +  $notAd_games * 2 ;  //+ 1;
                        if($notAdSort_game <= 200){
                            $sql .= "INSERT INTO `{$this->currentTable}`
                                  VALUES (0, 0, {$appData[$v][$notAdStartKey]['app_id']}, '{$v}', 2, null, {$notAdSort_game}, 0, null,null);";
                            $sqlNum ++;
                        }
                    }


                    //1000条写入一次
                    if($sqlNum >=200){
                        $this->InsertBysql($sql);
                        $sqlNum = 0;
                        $sql = '';
                        $times ++ ;
                    }

                }

            }else{
                $countrSortMaxNum[$v] =  0 ;
            }

        }

        $logstr = "补充app写入完毕.当前写入{$this->currentTable}表;共执行 {$k} + 1  个国家,执写入了{$times} * 200 + 1 次sql \n\r";
        echo $logstr;
        Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'initadsort');
        $this->InsertBysql($sql);
        unset($sql);
        //$this->notAdSortMaxNum = $countrSortMaxNum;
        //$this->appId = $appId;
        return true;
    }

    public function getCurrentAdCountry($ad)
    {
        $currentAdCountry = array_unique(array_column($ad,'country'));
        if(!empty($currentAdCountry)){
            return $currentAdCountry;
        }else{
            return array();
        }
    }

    public function getCurrentSort()
    {
        $adData = AdSortOne::find()
            ->select(["CONCAT(country, '|', camp_id,'|',app_id,'|',
            position) as pkey,
            camp_id,
            app_id,
            country,
            position,
            current_sort,
            next_sort "])
            ->asArray()
            ->orderBy('current_sort asc')
            ->all();
        if(!empty($adData)){
            $data = array();
            foreach($adData as $k => $v){
                $data[$v['country']][] = $v;
            }
            return $data;
        }else{
            return array();
        }
    }

    /**
     * 读取任务状态
     */
    public function getTaskStatus()
    {
        $statusModel = new TaskStatus();
        return $statusModel->getAdSortStatus();
    }

    public function getTaskValue($selectName)
    {
        $statusModel = new TaskStatus();
        return $statusModel->getTaskValue($selectName);
    }

    /**
     * 初始化列表时 定义当前表
     */
    public function initCurrentTable()
    {
        $currentTable = date( 'H',time() ) % 2 ;
        if($currentTable == 0){
            $this->currentTable = "ad_sort_two";
            $this->nextTable = "ad_sort_one";
        }else{
            $this->currentTable = "ad_sort_one";
            $this->nextTable = "ad_sort_two";
        }
    }


    //克隆表
    public function cloneInitTable()
    {
        $sql = "insert into {$this->nextTable} (select * from {$this->currentTable}) ";
        $res = $this->InsertBysql($sql);
        return true;
    }

    //记录初始化列表状态
    public function recordInitStatus()
    {
        $sql = "insert into `task_status` VALUES ('ad_sort_current_table','{$this->currentTable}'),
                ('ad_sort_next_table', '{$this->nextTable}'),
                ('is_init_ad_sort', 1)";
        $this->InsertBysql($sql);
        return true;
    }



    //排序
    public function sortArrByField(&$array, $field='download', $desc = true){
        $fieldArr = array_column($array,$field);
        $sort = $desc == true ? SORT_DESC : SORT_ASC;
        array_multisort($fieldArr, $sort, $array);
    }



    public function InsertBysql($sql)
    {
        try{
            $connection = \Yii::$app->db;
            return $connection->createCommand($sql)->execute();
        }catch(\Exception $e){
            $trace = $e->getTraceAsString();
            Yii::info(date("Y-m-d H:i:s",time()) . $trace ,'adSortInserLog');
        }
    }

    //获取生效国家
    public function getCountries()
    {
        $adCountries = AdCountry::find()->where("status = 1")->select("country_code")->asArray()->all();
        return $adCountries;
    }

    /**
     * 获取当前投放广告
     */
    public function getCurrentAd(){
        $ad = CountriesApp::find()->groupBy("country")->asArray()->all();
        return $ad;
    }

    public function getCurrentAdByCountry($country)
    {
        if(empty($country)){
            return array();
        }
        $data = array();
        foreach($country as $k => $v){
            $temp = CountriesApp::find()->where(['country'=> $v])->groupBy('app_id')->asArray()->limit(200)->all();
            $temp = $temp == true ? $temp  : array();
            $data = array_merge($data,$temp);
        }
        return $data;
    }

    //查看国家列表初始化生成是否写入完整
    public function actionTestSort(){
        //$ad = $this->getCurrentAd();
        //$currentAdCountry = $this->getCurrentAdCountry($ad);
        //$currentAdCountry = array('US','AE','AD');
        $country = $this->getCountrylist();

        foreach($country as $k => $v){
            $data = AdSortOne::find()->where("country = '{$v}'")->select("count(*) as count")->asArray()->all();
            if(empty($data) || current($data)['count'] < 600){
                $num = 600 - current($data)['count'];
                echo "国家 $v 缺少 $num   \n\r";
            }
        }

    }

    public function getCountrylist()
    {
        $model = new AdCountry();
        $country = $model->getOnlineCountries();
        $country = array_column($country,'country_code');
        return $country;
    }



    /**
     *  修改排序列表方法    ///////////////////////////////
     */

    public function actionInitUpdateStatus()
    {
        $currentUnixTime = strtotime(date('Y-m-d H:00:00',time()));
        $nextUpdateTime = date('Y-m-d H:i:00',$currentUnixTime + self::INTERVAL_TIME * 60);
        $defaultTime = self::INTERVAL_TIME;
        $sql = "insert into `task_status` VALUES ('ad_sort_interval', {$defaultTime}),
                ('ad_sort_is_post', 0),
                ('ad_sort_update_lock', 0),
                ('ad_sort_next_time', '{$nextUpdateTime}')";
        $res = $this->InsertBysql($sql);
        $logStr = "初始化任务状态完成";
        if($res){
            echo $logStr;
        }else{
            $logStr = "初始化写入失败,请检查是否写入重复";
            echo $logStr;
        }
        Yii::info(date("Y-m-d H:i:s",time()) . $logStr ,'updateadsort');die;
    }

    /**
     * 是否到达任务更新时间
     */
    public function isUpdateTime($adSortStatus)
    {
        if(!isset($adSortStatus['ad_sort_next_time'])){
            $logStr = "更新时间未定义\n\r";
            echo $logStr;Yii::info(date("Y-m-d H:i:s",time()) . $logStr ,'updateadsort');die;
        }

        $updateTime = strtotime($adSortStatus['ad_sort_next_time']) ;

        if(time() >= $updateTime - self::BEFOR_TIME * 60){
            return true;
        }else{
            $logStr = "未到任务执行时间{$adSortStatus['ad_sort_next_time']}\n\r";
            echo $logStr;Yii::info(date("Y-m-d H:i:s",time()) . $logStr ,'updateadsort');die;
        }
    }

    public function isLockUpdateSort($adSortStatus)
    {

        if(isset($adSortStatus['ad_sort_update_lock']) && $adSortStatus['ad_sort_update_lock'] == 1){
            $logstr = "当前任务锁定状态.";
            echo $logstr;
            Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'updateadsort');die;
        }
        if(!isset($adSortStatus['ad_sort_update_lock'])){
            $sql = "insert into task_status VALUES ('ad_sort_update_lock',0);";
            $this->InsertBysql($sql);
            return true;
        }
        return true;
    }

    public function isPost($adSortStatus)
    {
        //2016-07-06
        // 排序更新修改为只同步复制表 发布更新排序时处理更新排序顺序操作
        $sql = '';
        $adSortStatus['ad_sort_is_post'] = 0;
        if(!isset($adSortStatus['ad_sort_is_post'])){
            $sql = "insert into task_status VALUES ('ad_sort_is_post',0);";
        }
        //锁表
        $sql .= "update task_status set value = 1 where name = 'ad_sort_update_lock' ;";
        $this->InsertBysql($sql);
        //已发布更新排序
        if(isset($adSortStatus['ad_sort_is_post']) && $adSortStatus['ad_sort_is_post'] == 1){
            return true;
        }
        //未发布 同步表和任务状态
        if(isset($adSortStatus['ad_sort_is_post']) && $adSortStatus['ad_sort_is_post'] == 0){
            $logstr = " /*任务未发布*/,执行同步数据和任务状态修改\n\r";
            echo $logstr;Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'updateadsort');
            $this->updateAdSortTable($adSortStatus);
            die;
        }
    }

    public function updateLastSort($adSortStatus)
    {
//        $sql ="update {$adSortStatus['ad_sort_current_table']} set last_sort = current_sort,next_sort=null where next_sort is not null;";
//
//        //复制数据到历史记录表
//        $sql.= "update ad_sort_history set ad_sort_id = null;
//                insert into ad_sort_history (camp_id,app_id,country,position,source,current_sort,is_ad,ad_sort_id)
//                select camp_id,app_id,country,position,source,current_sort,is_ad,id as ad_sort_id from {$adSortStatus['ad_sort_current_table']};";
//
//        $this->InsertBysql($sql);
//        $logstr = "同步历史排序完成\n\r";
//        echo $logstr;
//        Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'updateadsort');
        return true;
    }
    /**
     * 同步表数据 更新任务状态
     */
    public function updateAdSortTable($adSortStatus)
    {
        $nextUpdateTime = date('Y-m-d H:i:s',strtotime($adSortStatus['ad_sort_next_time']) + $adSortStatus['ad_sort_interval'] * 60);

        //切表前数据缓存
        echo "缓存 当前编辑表{$adSortStatus['ad_sort_current_table']}数据\n\n";
        $this->CacheCurrentTable($adSortStatus['ad_sort_current_table']);

        //切换表 更新任务状态
        $sql = "update task_status set value = '{$adSortStatus['ad_sort_next_table']}' where name = 'ad_sort_current_table' ;
                update task_status set value = '{$adSortStatus['ad_sort_current_table']}' where name = 'ad_sort_next_table' ;
                update task_status set value = 0 where name = 'ad_sort_update_lock' ;
                update task_status set value = '{$nextUpdateTime}' where name = 'ad_sort_next_time' ;
                update task_status set value = 0 where name = 'ad_sort_is_post' ;
                ";
        $this->InsertBysql($sql);

        //同步表数据  切表后改为读取缓存数据
        $data = Yii::$app->cache->get('current_sort_table');

        $data = json_decode($data,true);

        $logstr = '';$i = 0;$times = 0;
        if($data){
            $logstr .= sprintf('%s ------%s 当前 从缓存读取前台同步排序表数据 开始写入 总条数 %s %s ', "\n\n" , date('Y-m-d H:i:s',time()) , count($data) , "\n\n" );
            $logstr .= sprintf(' ------%s 当前 已切换表 清空 当前编辑表[ %s ] 表数据 写入数据来源 上小时编辑表[ %s ] ',
                date('Y-m-d H:i:s',time()) , $adSortStatus['ad_sort_next_table'] , $adSortStatus['ad_sort_current_table'] , "\n\n\n" );
            echo $logstr;
            $sql = "truncate table {$adSortStatus['ad_sort_next_table']};";
            $this->InsertBysql($sql);
            $sql = '';
            $sqlPrefix = "INSERT INTO `{$adSortStatus['ad_sort_next_table']}` (`id`, `camp_id`, `app_id`, `country`, `position`, `source`, `current_sort`, `is_ad`, `next_sort`, `last_sort`) VALUES";
            foreach($data as $k => $v){
                $i ++;
                $source = isset($v['source']) ? $v['source'] : 0 ;
                $isAd = isset($v['is_ad']) ? $v['is_ad'] : 0 ;
                $lastSort = isset($v['last_sort']) ? $v['last_sort'] : 0 ;
                $nextSort= isset($v['next_sort']) ? $v['next_sort'] : 0;
                $sql .= "({$v['id']},{$v['camp_id']},{$v['app_id']},'{$v['country']}',
                    {$v['position']},{$source},{$v['current_sort']},{$isAd},
                    {$nextSort},{$lastSort}),";

                if($i >= 500){
                    $sqlInsertStr = $sqlPrefix .rtrim($sql,',').';';
                    $res = $this->InsertBysql($sqlInsertStr);
                    $sql = '';
                    $times ++;
                    $i = 0;
                }
            }
            if($sql){
                $sqlInsertStr = $sqlPrefix .rtrim($sql,',').';';
                $this->InsertBysql($sqlInsertStr);
            }

        }else{
            $logstr .=  date('Y-m-d H:i:s',time()) .  "当前 从前台表读取前台同步排序表数据 写入";
            $sql = "truncate table {$adSortStatus['ad_sort_next_table']};
                    insert into {$adSortStatus['ad_sort_next_table']} (select * from {$adSortStatus['ad_sort_current_table']});";
            $this->InsertBysql($sql);
        }
        $logstr .= sprintf('%s ------%s 缓存读取写入次数 %s; 条数 %s %s', "\n\n" , date('Y-m-d H:i:s',time()) , $times,$times* 500 + $i , "\n\n");
        $logstr .= sprintf('当前时间: %s 执行同步表操作 更新任务状态完成 下次任务时间:%s %s', date('Y-m-d H:i:s',time()) , $nextUpdateTime , "\n\n" );
        $logstr .= sprintf('当前时间: %s 本次任务更新排序 操作表 %s 排序更新完成并设为前台访问表 下次编辑表已修改为 :  %s 数据已同步 %s',
            date('Y-m-d H:i:s',time()) , $adSortStatus['ad_sort_current_table'] , $adSortStatus['ad_sort_next_table'] , "\n\n" );
        echo $logstr;
        Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'updateadsort');
        return true;
    }

    /**
     * 查询当前编辑排序列表数据
     */
    public function CacheCurrentTable($currentTable){
        if($currentTable == "ad_sort_one"){
            $sortData = AdSortOne::find()->asArray()->All();
        }else{
            $sortData = AdSortTwo::find()->asArray()->All();
        }
        $logstr  = date('Y-m-d H:i:s',time()) . "写入缓存数据 表:" .$currentTable . "总条数 " . count($sortData) . "\n";
        echo $logstr;
        Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'updateadsort');
        $sortData = json_encode($sortData);

        Yii::$app->cache->delete('current_sort_table');

        Yii::$app->cache->set('current_sort_table',$sortData);
        return true;
    }




    /**
     * 自动排序
     */
    public function autoSort($adSortStatus,$v)
    {
        $sql = "";
        //前换后
        if($v['current_sort'] < $v['next_sort']){
            //非自身位置修改 前
            $sql .= "UPDATE {$adSortStatus['ad_sort_current_table']} set current_sort = current_sort - 1
                      where country = '{$v['country']}'
                      and position = {$v['position']}
                      and current_sort <= {$v['next_sort']}
                      and current_sort > {$v['current_sort']} ; ";
            //非自身位置修改 后
            //$sql .= "UPDATE ad_sort_one set current_sort = current_sort + 1  where country = '{$v['country']}' and current_sort > {$v['next_sort']} ; ";
            //自身位置修改
            $sql .= "UPDATE {$adSortStatus['ad_sort_current_table']} set current_sort = {$v['next_sort']} ,next_sort = null
                      where country = '{$v['country']}'
                      and position = {$v['position']}
                      and current_sort = {$v['current_sort']}
                      and id = {$v['sort_id']}; ";
            $sql .= "UPDATE ad_sort_record set is_updated = 1 where id = {$v['id']};";
        }
        //后换前
        if($v['current_sort'] > $v['next_sort']){
            //非自身位置修改
            $sql .= "UPDATE {$adSortStatus['ad_sort_current_table']} set current_sort = current_sort + 1  where
                    country = '{$v['country']}'
                    and position = {$v['position']}
                    and current_sort >= {$v['next_sort']}
                    and current_sort < {$v['current_sort']} ; ";
            //自身位置修改
            $sql .= "UPDATE {$adSortStatus['ad_sort_current_table']} set current_sort = {$v['next_sort']} ,next_sort = null
                    where country = '{$v['country']}'
                    and position = {$v['position']}
                    and current_sort = {$v['current_sort']}
                    and id = {$v['sort_id']}; ";
            $sql .= "UPDATE ad_sort_record set is_updated = 1 where id = {$v['id']};";
        }
        if(!empty($sql)){
            $this->InsertBysql($sql);
        }
        return true;
    }

    /**
     * 对换位置排序
     */
    public function changeSort($adSortStatus,$v)
    {
        //前换后  后换前
        $sql = "UPDATE {$adSortStatus['ad_sort_current_table']} set current_sort = {$v['current_sort']}
                    where country = '{$v['country']}'
                    and position = {$v['position']}
                    and current_sort = {$v['next_sort']} ;";
        $sql .= "UPDATE {$adSortStatus['ad_sort_current_table']} set current_sort = {$v['next_sort']} ,next_sort = null
                    where id = {$v['sort_id']}; ";

        $sql .= "UPDATE ad_sort_record set is_updated = 1 where id = {$v['id']};";

        $this->InsertBysql($sql);
        return true;
    }

    /**
     * 排序列表添加
     */
    public function addSort($adSortStatus,$v)
    {
        //自动排序
        $sql = "UPDATE {$adSortStatus['ad_sort_current_table']} set current_sort = current_sort + 1
                    where country = '{$v['country']}'
                    and position = {$v['position']}
                    and current_sort >= {$v['next_sort']} ;
                DELETE from ad_sort_one where current_sort > 200;";
        $sql .= "INSERT INTO {$adSortStatus['ad_sort_current_table']} VALUES
            (0, {$v['camp_id']}, {$v['app_id']}, '{$v['country']}', {$v['position']}, {$v['source']}, {$v['next_sort']}, {$v['is_ad']}, null,201);";

        $sql .= "UPDATE ad_sort_record set is_updated = 1 where id = {$v['id']};";
        $this->InsertBysql($sql);
        return true;
        //兑换 暂未添加
    }

    public function getCategoryByAd($ad)
    {
        $app_ids = array_column($ad,'app_id');
        $data = AppData::find()->where(['app_id'=>$app_ids])->select('app_id,category')->asArray()->all();
        $category = array_column($data,'category','app_id');
        foreach($ad as $k => $v){
            $categoryTemp = isset($category[$v['app_id']]) ? $category[$v['app_id']] : '';
            if(!$categoryTemp){
                Yii::info(date("Y-m-d H:i:s",time()) ." 未查询到app_id:". $v['app_id'] ."\n\r",'initadsort');
            }
            $ad[$k]['category'] = $categoryTemp;
        }
        unset($category);
        return $ad;
    }

    public function actionResetSort()
    {

    }

    /**
     * 填补预定app
     * ./yii ad-sort/add-app
     */
    public function actionAddApp()
    {
        $status = $this->getTaskStatus();
        $currentTabl = $status['ad_sort_current_table'];
        echo $currentTabl . "   <== 当前表\n\r";
        $country['US'] = array(667728512,986339882,297606951,719525810,945274928,586634331,577586159,1089836344,934596429,372648912,295646461,485126024,771990977,1047246341,577333970);
        $country['GB'] = array(897894318,667728512,382030091,552024276,543186831,850417475,1040083067,334235181,305343404,385266473,368677368,934596429,1055672607,1001250333,376888389);
        $country['DE'] = array(1040083067,1004381470,945274928,376888389,1047246341,372648912,1055672607,599502670,543186831,440677948,577491499,378563358,445338486,727296976,885661130);
        $country['FR'] = array(579373363,1040083067,934596429,898160912,341329033,1047246341,1035263816,603097018,850417475,436672029,625257520,439643474,543186831,426414466,1010677881);
        $country['CA'] = array(667728512,934596429,305343404,469231420,1040083067,1047246341,771990977,502658998,859204347,850417475,804379658,376888389,973482525,937718942,911793120);
        foreach($country as $k => $v){

            foreach($v as $kk => $vv){
                $res = $this->isInSort($currentTabl,$k,$vv);
                if($res){
                    continue;
                }else{
                    $res = $this->addApp($currentTabl,$k,$kk,$vv);
                    if($res){
                        echo "写入成功\n\r";
                    }else{
                        echo "写入失败\n]r";
                    }
                }
            }

        }
    }

    public function isInSort($currentTabl,$country,$app_id)
    {
        if($currentTabl == 'ad_sort_one'){
            $res = AdSortOne::find()->where("country = '{$country}' and position = 0 and  app_id = {$app_id}")->asArray()->one();
        }else{
            $res = AdSortTwo::find()->where("country = '{$country}' and position = 0 and app_id = {$app_id}")->asArray()->one();
        }
        if($res){
            echo "存在列表的  国家: " . $country ." app_id " . $app_id . "\n\r" ;
            return $res;
        }else{
            return null;
        }
    }

    public function addApp($currentTabl,$country,$currentSort,$app_id)
    {
        $adData = CountriesApp::find()->where("app_id = {$app_id} and country = '{$country}' ")->select("source,camp_id")->asArray()->one();
        if($adData){
            $camp_id = $adData['camp_id'];
            $source = $adData['source'];
        }else{
            $camp_id = 0;
            $source = 0;
        }
        $currentSort = $currentSort + 1;
        $sql = "update {$currentTabl} set app_id = {$app_id},camp_id = {$camp_id},source={$source} where country = '{$country}' and position = 0 and current_sort = {$currentSort};";

        echo "不存在写入数据  国家: " . $country ." app_id " . $app_id .' source '. $source .' camp_id ' . $camp_id . "\n\r" ;
        $res = $this->InsertBysql($sql);
        return $res;
    }

    /**
     *
     *
     * 更新排序列表上个小时每个位置 点击/下载/安装数据
     *
     *
     */
    public function actionHistoryData()
    {
        //查询上个小时点击/下载/安装数据
        $click = $this->getClickData();
        //$histryData = AdSortHistory::find()->where("ad_sort_id is not null")->asArray()->all();
        // var_dump($histryData);
    }

    public function getClickData()
    {
        $endTime = strtotime(date('Y-m-d H:00:00',time()));
        $startTime = $endTime - 60 * 60;
        $click = DownloadWeek::find()->where("time >= {$startTime} and time < {$endTime}")->groupBy("country,position,app_id")->asArray()->all();
        // var_dump($click);
        // 未完
    }

    /**
     * 排序列表 app是否在库中存在判断
     * app重复判断
     * 重复使用其他替换
     * ./yii ad-sort/app-check
     */
    public $app_id = array();
    public function actionAppCheck()
    {
        return true;
        $task = $this->getTaskStatus();
        $currentTable = $task['ad_sort_current_table'];
        echo '当前操作表 ' . $currentTable ."\n\r";
        $country = $this->getCountries();
        $country = array_column($country,'country_code');

        foreach($country as $k => $v){

            for($i= 0;$i<3;$i++){
                //每个国家3个列表
                if($currentTable == 'ad_sort_one'){
                    $sort = AdSortOne::find()->where("country = '{$v}' and position = {$i}")->asArray()->all();
                }else{
                    $sort = AdSortTwo::find()->where("country = '{$v}' and position = {$i}")->asArray()->all();
                }

                $app_id = array_column($sort,'app_id');
                $this->app_id = $app_id;
                $temp =array();
                foreach($sort as $kk => $vv){
                    $app =  AppData::find()->where("app_id = {$vv['app_id']}")->asArray()->one();

                    if($app){
                        //var_dump($vv);
                        $k = $v.'_'.$i.'_'.$vv['app_id'];
                        //echo $k ."\n\r";
                        $temp[$k] = isset($temp[$k]) ? $temp[$k] : 0 ;
                        $temp[$k] ++;
                        //echo $temp[$k]  ."\n\r";

                        if($temp[$k] > 1){
                            echo "app重复\n\r";
                            //app重复 替换操作
                            $this->changeSortApp($currentTable,$vv['id']);
                        }
                    }else{
                        //app 不再库中替换
                        echo "不再库中替换\n\r";
                        $this->changeSortApp($currentTable,$vv['id']);
                    }
                }
            }

        }
        $this->updateAdSortOneTwo($task);
        echo "同步完成\n\r";
    }

    public function updateAdSortOneTwo($adSortStatus)
    {

        $sql = "truncate table {$adSortStatus['ad_sort_next_table']};
            insert into {$adSortStatus['ad_sort_next_table']} (select * from {$adSortStatus['ad_sort_current_table']});";
        $res = $this->InsertBysql($sql);
        return true;

    }

    public function changeSortApp($currentTable,$id)
    {

        $appId = $this->app_id;
        $appData = AppData::find()->where(['not in', 'app_id', $appId])->select("app_id")->asArray()->one();

        if($appData){
            $appId[] = $appData['app_id'];
            $this->app_id = $appId;
            $sql = "update {$currentTable} set app_id ={$appData['app_id']},camp_id = 0,source = 0 ,is_ad = 0 where id = {$id};";
            $this->InsertBysql($sql);
        }else{
            echo $id . "对用位置为替换成功\n\r";
        }

    }
}
