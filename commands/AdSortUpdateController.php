<?php
/**
 *
 */

namespace app\commands;


use app\modules\appcake\models\AdCountry;
use app\modules\appcake\models\CountriesApp;
use app\modules\appcake\models\DataSummary;
use yii\console\Controller;
use yii;
use app\modules\appcake\models\AppData;
use app\modules\appcake\models\TaskStatus;
use app\modules\appcake\models\Bat;
use app\modules\appcake\models\AdSortOne;
use app\modules\appcake\models\AdSortTwo;
class AdSortUpdateController extends Controller
{

    public $appId = array();

    public $currentTable = '';

    public $delNum = 0;

    public $addNum = 0;

    public $repeatNum = 0;

    public $replaceNum = 0;

    public $adMaxNum = array();

    public $AdCountry =array();

    public $needAddApp = array();

    public $delApp = array();

    public $noPostionCountry = array();

    public $addApp = array();
    /**
     * 定时刷新 排序列表上下架广告 增加下架广告查看同app如果有其他渠道广告 更换渠道不下线 无广告再下线
     * 2016-09-05 增加 广告列表广告排序刷新 广告按转换率排序
     *                 1-30 按1个广告2个非广告规则 后接广告 => 非广告
     *
     *
     * 每小时前5分钟 开始刷新任务
     * ./yii ad-sort-update/check-ad-sort
     */

    public $appIdArr = array();
    public $replaceAdSourceNum = 0;
    //前30名广告排序位置 16-09-19增加至40
    public $adSortMap = array(1,4,7,10,13,16,19,22,25,28,40);
    public $notAdSortMap = array(2,3,5,6,8,9,11,12,14,15,17,18,20,21,23,24,26,27,29,30,31,32,33,34,35,36,37,38,39);

    public function actionCheckAdSort()
    {
        $task = $this->getTaskStatus();
        $this->currentTable =  $task['ad_sort_current_table'];
        echo "当前操作表" . $this->currentTable ."\n\r";

        //当前列表广告国家
        $this->getSortCountry();

//        $this->AdCountry = array(
//           0 =>  'US'
//        );
        foreach($this->AdCountry as $k => $v){
            for($i=0;$i<=2;$i++){
                //查询当前国家位置广告列表
                $onLineAd = CountriesApp::find()->where("country = '{$v}' ")
                    ->select("camp_id,app_id,source")
                    //->groupBy("camp_id")
                    ->orderBy("payout_amount desc")
                    ->asArray()
                    ->all();
                //按价格去重app
                $ad = $this->filterAdByPrice($onLineAd);
                $campId = array_column($ad,'camp_id');
                $app_id = array_column($ad,'app_id','camp_id');
                $app_idArr = array_column($ad,'app_id');
                $this->appIdArr = $app_idArr;
                $source = array_column($ad,'source','camp_id');


                //查询当前排序列表投放广告及app
                $sortAd = $this->getSortAd($v,$i);
                $sorAdAppId = array_column($sortAd,'camp_id');
                $sortAppArr = array_column($sortAd,'app_id','camp_id');
                $sortIdArr = array_column($sortAd,'id','camp_id');
                $currentSortArr = array_column($sortAd,'current_sort','camp_id');
                $inListApp = array_column($sortAd,'app_id');
                //比对当前投放广告

                //库里新加有的
                $add = array_diff($campId,$sorAdAppId);
                //需要移除的
                $del = array_diff($sorAdAppId,$campId);

                //下架 改非广告app
                if(!empty($del)){
                    //echo "执行下架app操作{$v} {$i}-----------\n\r";
                    foreach($del as $delk => $delCampid){
                        //没有其他渠道广告 后移
                        $id_v = $sortIdArr[$delCampid];
                        $app_id_v = $sortAppArr[$delCampid];
                        $currentSort_v = $currentSortArr[$delCampid];
                        $this->delApp($delCampid,$v,$i,$id_v,$app_id_v,$currentSort_v);
                    }
                }

                //添加广告app
                if(!empty($add)){
                    //echo "执行添加广告app操作{$v} {$i}-----------\n\r";

                    //查询当前国家广告是否库中有app
                    $AppDataHasApps = $this->isInAppData($this->appIdArr);
                    $category = array_column($AppDataHasApps,'category','app_id');
                    $AppDataHasApps = array_column($AppDataHasApps,'app_id');
                    foreach($add as $addk => $addCampid){

                        //查询广告是否库中有应用
                        $appid = $app_id[$addCampid];

                        //要添加广告app是否在列表存在 存在跳过
                        $isInAdSortList = in_array($appid,$inListApp);
                        if($isInAdSortList) continue;

                        $appCategory = isset($category[$appid]) ? $category[$appid] : '';
                        $sourceid = $source[$addCampid];
                        $isInAppData = in_array($appid,$AppDataHasApps);
                        if($i == 1){
                            if(strtoupper($appCategory) == 'GAMES'){
                                continue;
                            }
                        }
                        if($i == 2){
                            if(strtoupper($appCategory) != 'GAMES'){
                                continue;
                            }
                        }
                        if($isInAppData){
                            //添加广告到排序列表
                            $this->addApp($v,$i,$addCampid,$appid,$sourceid);
                        }else{
                            $this->replaceNum += 1;
                            $this->needAddApp[] = $v;
                            //echo "添加新广告库中无app {$v} {$i} {$addCampid} 需要添加后操作\n\r";
                        }
                    }
                }

                //非广告 检查是否有广告
                $this->checkNotAdApp($v,$i);


                //检查广告app 按昨日收入优先投放
                $this->replaceAd($v,$i,$onLineAd);

                //增加排序列表 广告按前一天收入排序 解决新上广告后排问题
                $this->resetAdSort($v,$i);

                //非广告排序重排 解决非广有app变广告 替换广告后非广告位置空出问题
                $this->resetNotAdSort($v,$i);
            }
        }



        //入库检查了 此处暂省略检查
        //echo "app去重及库中是否存在检查-----------\n\r";
        $this->appCheck();

        //排序大于200移除
        $this->deleteOtherApp();


        //日志记录操作信息
        $delApp = json_encode($this->delApp);
        $neddAdd = json_encode($this->needAddApp);
        $addApp = json_encode($this->addApp);
        echo "删除广告数:{$this->delNum}\n\r 添加广告数:{$this->addNum}  重复:{$this->repeatNum}  不再库中替换的:{$this->replaceNum}  优选渠道替换数:{$this->replaceAdSourceNum}";
        Yii::info(date("Y-m-d H:i:s",time()) . "更新3个列表任务:\n\r删除广告数:{$this->delNum}\n\r
                    添加广告数:{$this->addNum} \n\r 重复:{$this->repeatNum} \n\r 不再库中替换的:{$this->replaceNum}
                    \n\r删除的app:\n\r{$delApp}\n\r添加的app:\n\r{$addApp}\n\r需要添加appdata的app:\n\r{$neddAdd}\n\r
                    优选渠道替换数:{$this->replaceAdSourceNum}",
            'addNewAd');

    }

    public function resetNotAdSort($country,$position){
        $notAdSort = $this->getNotAdSort($country,$position);
        $index = 200;
        $sql = '';
        foreach($notAdSort as $k => $v){
            if(isset($this->notAdSortMap[$k])) {
                $index = $this->notAdSortMap[$k];
            } else if(!isset($startNum)){
                $adNum = 200 - count($notAdSort); //广告数量
                $adNum = $adNum - count($this->adSortMap); //广告map以后广告数
                $startNum = $adNum + $this->adSortMap[count($this->adSortMap) - 1] + 1; //非广告开始位置
                $index = $startNum ;
            } else {
                $index ++;
            }
            if($v['current_sort'] != $index){
                $sql .= "update {$this->currentTable} set current_sort = {$index}
                     where country = '{$country}' and position = {$position} and current_sort = {$v['current_sort']};";
            }
        }
        $this->InsertBysql($sql);
        return true;
    }


    /*
     * 广告列表 选择渠道广告 优先昨日收入
     */
    public function replaceAd($country,$position,$onLineAd){
        //获取当前国家位置排序列表
        $sortAd = $this->getSortAd($country,$position);
        $campIdArr = array_column($sortAd,'app_id','camp_id');
        $appIdArr = array_column($sortAd,'app_id');

        //当在线广告id
        $onLineCampId = array_column($onLineAd,'camp_id');

        //app对应广告昨日收入广告
        $model = new DataSummary();
        $adData =$model->getYesAdByAppId($appIdArr,$country);

        $top = array();
        foreach($adData as $k => $v){
            $temId = str_replace('_','',$v['camp_id']);
            if(!in_array($temId,$onLineCampId)) continue;
            if(isset($top[$v['app_id']]) && $v['income'] > $top[$v['app_id']]['income']){
                $top[$v['app_id']] = $v;
            }else if(!isset($top[$v['app_id']]) && $v['income'] > 0 ){
                $top[$v['app_id']] = $v;
            }
        }


        $sql = '';
        $source = array_flip(Yii::$app->params['ad_source']);
        foreach($sortAd as $k => $v){

            if(isset($top[$v['app_id']])){

                $campIdTemp = str_replace('_','',$top[$v['app_id']]['camp_id']);
                $sourceTemp = $top[$v['app_id']]['ad_source'];

                if($v['camp_id'] != $campIdTemp){
                    $adSource = isset($source[$sourceTemp]) ? $source[$sourceTemp] : 0;
                    if($adSource == 0 ) continue;
                    $this->replaceAdSourceNum = $this->replaceAdSourceNum + 1;
                    $sql .= "update {$this->currentTable} set camp_id = {$campIdTemp} , source = {$adSource} where id = {$v['id']};";
                }

            }

        }

        if($sql) $this->InsertBysql($sql);

    }

    /**
     * @param $country
     * @param $position
     * @return bool
     * 上下广告完成后 按收入刷新一遍广告排序
     */
    public function resetAdSort($country,$position){
        //获取当前国家位置排序列表
        $sortAd = $this->getSortAd($country,$position);
        $campIdArr = array_column($sortAd,'camp_id');
        //查询当前app收入
        $incomedata = $this->getincome($campIdArr,$country);

        $income = array_column($incomedata,'camp_id');

        //将当前广告列表按收入排序
        $newSortAd = $this->refresh($income,$sortAd);

        $index = 0;$sql = '';$i = -1;
        $adSortMapMax = $this->adSortMap[count($this->adSortMap)-1];
        //map最大值 + 广告列表广告数量 - map个数 = 广告排序值 即非广告开始值
        $adMaxIndex = count($newSortAd) - count($this->adSortMap) + $adSortMapMax ;
        $startNum = $adMaxIndex;
        ksort($newSortAd);
        foreach($newSortAd as $k => $v){
            $i ++;
            if (isset($this->adSortMap[$i])) {
                $index = $this->adSortMap[$i];
            }else{
                $index ++;
                $adMaxIndex ++;
            }

            //位置不同替换位置 位置相同替换最大广告排序位置
            if($v['current_sort'] == $index || in_array( $v['current_sort'],$this->adSortMap) || $v['current_sort'] < $startNum ){
                $sql .= "update {$this->currentTable} set current_sort = {$adMaxIndex}
                     where country = '{$country}' and position = {$position} and current_sort = {$index};
                         update {$this->currentTable} set current_sort = {$index}
                     where country = '{$country}' and position = {$position} and camp_id = {$v['camp_id']};";
            }else{
                $sql .= "update {$this->currentTable} set current_sort = {$v['current_sort']}
                 where country = '{$country}' and position = {$position} and current_sort = {$index};
                     update {$this->currentTable} set current_sort = {$index}
                 where country = '{$country}' and position = {$position} and camp_id = {$v['camp_id']};";
            }
        }
        $this->InsertBysql($sql);
        return true;
    }

    /**
     * @param $income
     * @param $sortAd
     * @return array
     * 将广告数据按昨日数据排序
     */
    public function refresh($income,$sortAd){
        $index = count($income);
        $tempArr = array();
        $income = array_flip($income);


        foreach($sortAd as $k => $v){
            if(isset($income[$v['camp_id'].'_'])){
                $tempArr[$income[$v['camp_id'].'_']] = $v;
            }else{
                $index ++ ;
                $tempArr[$index] = $v;
            }
        }

        return $tempArr;
    }

    public function filterAdByPrice($ad){
        $temp = array();
        foreach($ad as $k => $v){
            if(isset($temp[$v['app_id']])){
                unset($ad[$k]);
                continue;
            }
            $temp[$v['app_id']] = 1;
        }
        return $ad;
    }

    public function getincome($campIdArr,$country){
        $campIdArr = array_map('intval',$campIdArr);
        $yesterday = date('Y-m-d',strtotime('-1 day'));

        $income = DataSummary::find()
            ->where(['date'=>$yesterday,'country'=>$country,'camp_id'=>$campIdArr])
            ->andWhere("camp_id > 0 and income > 0 ")
            ->select('app_id,camp_id,income')
            ->orderBy('income desc')
            ->asArray()
            ->all();
        if(!$income) $income = array();
        return $income;
    }

    /**
     * 非广告app 检查是否有广告
     */
    public function checkNotAdApp($v,$i){
        if($this->currentTable == 'ad_sort_one'){
            $sort = AdSortOne::find()->where("country = '{$v}' and position = {$i} and is_ad = 0")
                ->orderBy("current_sort asc")
                ->select("camp_id,app_id,source,id")
                ->asArray()
                ->all();
        }else{
            $sort = AdSortTwo::find()->where("country = '{$v}' and position = {$i} and is_ad = 0")
                ->orderBy("current_sort asc")
                ->select("camp_id,app_id,source,id")
                ->asArray()
                ->all();
        }

        $appIdArr = array_column($sort,'app_id');
        $ad = CountriesApp::find()->where(['app_id'=>$appIdArr,'country'=> "{$v}"])->select("camp_id,app_id,source")->orderBy("payout_amount asc")->asArray()->all();

        $camp_id = array_column($ad,'camp_id','app_id');
        $source =array_column($ad,'source','app_id');
        $logStr = '';
        if(!empty($ad)){
            $sql = '';$num = 0;
            foreach($sort as $k => $vv){
                if(isset($camp_id[$vv['app_id']])){
                    $num ++ ;
                    $sql .= "update {$this->currentTable} set camp_id = {$camp_id[$vv['app_id']]} , source = {$source[$vv['app_id']]} ,is_ad = 1  where id = {$vv['id']};";
                }else{
                    continue;
                }
            }
            if($sql){
                $logStr .= "非广告替换广告 当前国家{$v} 位置{$i} 非广告替换广告总条数 {$num}\n";
                echo $logStr;
                $this->InsertBysql($sql);
            }
        }else{
            $logStr .= "当前国家{$v} 位置{$i} 非广告无广告替换\n";
        }
        Yii::info(date("Y-m-d H:i:s",time()) . $logStr ,'addNewAd');
        return true;
    }


    public function deleteOtherApp()
    {
        $sql = "delete from {$this->currentTable} where current_sort > 200;";
        $this->InsertBysql($sql);
        return true;
    }

    /**
     * 排序列表 app是否在库中存在判断
     * app重复判断
     * 重复使用其他替换
     * ./yii ad-sort/app-check
     */
    public $app_id = array();
    public function appCheck()
    {

        foreach($this->AdCountry as $k => $v){

            for($i= 0;$i<=2;$i++){
                //每个国家3个列表
                if($this->currentTable == 'ad_sort_one'){
                    $sort = AdSortOne::find()->where("country = '{$v}' and position = {$i}")
                        ->orderBy("current_sort asc")
                        ->asArray()
                        ->all();
                }else{
                    $sort = AdSortTwo::find()->where("country = '{$v}' and position = {$i}")
                        ->orderBy("current_sort asc")
                        ->asArray()
                        ->all();
                }

                $app_id = array_column($sort,'app_id');
                $this->app_id = $app_id;
                $temp =array();
                foreach($sort as $kk => $vv){

                    $app =  AppData::find()->where("app_id = {$vv['app_id']}")->select("app_id")->asArray()->one();

                    if($app){

                        $k = $v.'_'.$i.'_'.$vv['app_id'];
                        //echo $k ."\n\r";
                        $temp[$k] = isset($temp[$k]) ? $temp[$k] : 0 ;
                        $temp[$k] ++;
                        $appdata[$k][] = $vv;
                        //echo $temp[$k]  ."\n\r";

                        if($temp[$k] > 1){

                            $this->repeatNum ++;
                            //重复两个位置 前面如果是非广告就替换前面的位置app
                            if($appdata[$k][0]['is_ad'] == 0){
                                $id = $appdata[$k][0]['id'];
                                unset($appdata[$k][1]);
                            }else{
                                $id = $vv['id'];
                            }
                            //app重复 替换操作
                            $this->changeSortApp($id);
                            $temp[$k] = 1;
                        }
                    }else{
                        //app 不再库中替换
                        //echo "不再库中替换\n\r";
                        $this->replaceNum ++;
                        $this->changeSortApp($vv['id']);
                    }
                }
            }

        }
        return true;
    }



    public function changeSortApp($id)
    {

        $appId = $this->app_id;
        $appData = AppData::find()->where(['not in', 'app_id', $appId])->select("app_id")->asArray()->one();

        if($appData){
            $appId[] = $appData['app_id'];
            $this->app_id = $appId;
            $sql = "update {$this->currentTable} set app_id ={$appData['app_id']},camp_id = 0,source = 0 ,is_ad = 0 where id = {$id};";
            $this->InsertBysql($sql);
        }else{
            echo $id . "对用位置为替换成功\n\r";
        }

    }


    public function getSortCountry()
    {
        $country = AdCountry::find()->where("status = 1")->select("country_code")->asArray()->all();
        $country = array_column($country,'country_code');
        $this->AdCountry = $country;
        return true;
    }

    public function isInAppData($appid)
    {
        $res = AppData::find()->where(['app_id' => $appid])->select("app_id,category")->asArray()->all();
        return $res;
    }

    public function addApp($country,$position,$addCampid,$appid,$source)
    {

        //读取一条60以后的非广告位置替换为广告
        $replacePosition = $this->getPosition($country,$position);

        if(empty($replacePosition)){
            //未找到替换位置
            $this->noPostionCountry[] = $country.'_'.$position;
            $str = "添加新广告: 60以后未查找到非广告位置 添加取消 国家:{$country} 位置:{$position} \n\r";
            //echo $str;
        }else{
            //将广告替换到查找到的位置
            $this->replaceNewAd($addCampid,$appid,$source,$replacePosition);
        }
        return true;
    }



    public function getPosition($country,$position){

        if($this->currentTable == 'ad_sort_one'){
            $adMaxCurrentSort =  AdSortOne::find()
                ->where("country = '{$country}' and position = {$position} and  is_ad = 0 and current_sort > 60 ")
                ->orderBy("current_sort asc")
                ->asArray()
                ->one();
        }else{
            $adMaxCurrentSort =  AdSortTwo::find()
                ->where("country = '{$country}' and position = {$position} and  is_ad = 0 and current_sort > 60 ")
                ->orderBy("current_sort asc")
                ->asArray()
                ->one();
        }

        if($adMaxCurrentSort){
            return $adMaxCurrentSort;
        }else{
            $str = "国家:{$country} 位置:{$position} 60以后未查找到非广告位置 添加取消\n\r";
            //echo $str;
            Yii::info(date("Y-m-d H:i:s",time()) . $str ,'addNewAd');
            return false;
        }

    }


    public function replaceNewAd($addCampid,$appid,$source,$v)
    {
        $sql = "update {$this->currentTable} set camp_id = {$addCampid} ,app_id = {$appid} ,source = {$source},is_ad = 1 where id = {$v['id']};";
        $this->addNum +=1;
        $this->InsertBysql($sql);
        $this->addApp[] = $v;
        return true;
    }


    public function delApp($campId,$country,$position,$id,$app_id,$currentSort)
    {

        $ad = CountriesApp::find()->where("country = '{$country}' and app_id = {$app_id}")->orderBy('payout_amount desc')->asArray()->one();

        if(!empty($ad)){
            //替换广告
            $sql = "update {$this->currentTable} set camp_id = {$ad['camp_id']} ,source = {$ad['source']} where id = {$id}  ;";
            $logstr = "下架广告有其他渠道更换渠道:广告{$campId} 当前国家{$country}位置{$position}排序 {$currentSort} 更换渠道: {$ad['source']} 广告id: {$ad['camp_id']} \n\r";
            echo $logstr;
            $this->replaceNum  += 1;
            Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'adSortInserLog');
            $this->InsertBysql($sql);
        }else{
            //下架广告 后边位置递加 广告app移至200
            $sql = "update {$this->currentTable} set current_sort = current_sort - 1 where country='{$country}' and position = {$position} and current_sort > {$currentSort};
                update {$this->currentTable} set current_sort = 200, is_ad = 0,camp_id = 0,source = 0 where id = {$id} ;";
            $logstr = "下架广告{$campId} 当前国家{$country}位置{$position}排序{$currentSort} 后移至200\n\r";
            //echo $logstr;
            $this->delNum += 1;
            Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'adSortInserLog');
            $this->InsertBysql($sql);
        }


        return true;
    }

    public function getAppIdByPosition($v)
    {
        if($this->currentTable == 'ad_sort_one'){
            $appId = AdSortOne::find()->where("country = '{$v['country']}' and position = {$v['position']} ")->select('app_id')->asArray()->all();
        }else{
            $appId = AdSortTwo::find()->where("country = '{$v['country']}' and position = {$v['position']} ")->select('app_id')->asArray()->all();
        }
        if($appId){
            $appId = array_column($appId,'app_id');
            $this->appId[$v['country']][$v['position']] = $appId;
        }
        return true;
    }

//    public function getSort($country,$position)
//    {
//        if($this->currentTable == 'ad_sort_one'){
//            $sortAd = AdSortOne::find()->where("country ='{$country}' and position = {$position}")
//                ->select('camp_id,id,app_id,current_sort,source')
//                ->orderBy("current_sort asc")
//                ->asArray()
//                ->all();
//        }else{
//            $sortAd = AdSortTwo::find()->where("country ='{$country}' and position = {$position}")
//                ->select('camp_id,id,app_id,current_sort,source')
//                ->orderBy("current_sort asc")
//                ->asArray()
//                ->all();
//        }
//        return $sortAd;
//    }


    public function getNotAdSort($country,$position)
    {
        if($this->currentTable == 'ad_sort_one'){
            $sortAd = AdSortOne::find()->where("country ='{$country}' and position = {$position} and is_ad = 0")
                ->select('camp_id,id,app_id,current_sort')
                ->orderBy("current_sort asc")
                ->asArray()
                ->all();
        }else{
            $sortAd = AdSortTwo::find()->where("country ='{$country}' and position = {$position} and is_ad = 0")
                ->select('camp_id,id,app_id,current_sort')
                ->orderBy("current_sort asc")
                ->asArray()
                ->all();
        }
        return $sortAd;
    }

    public function getSortAd($country,$position)
    {
        if($this->currentTable == 'ad_sort_one'){
            $sortAd = AdSortOne::find()->where("country ='{$country}' and position = {$position} and is_ad = 1")
                ->select('camp_id,id,app_id,current_sort')
                ->orderBy("current_sort asc")
                ->asArray()
                ->all();
        }else{
            $sortAd = AdSortTwo::find()->where("country ='{$country}' and position = {$position} and is_ad = 1")
                ->select('camp_id,id,app_id,current_sort')
                ->orderBy("current_sort asc")
                ->asArray()
                ->all();
        }
        return $sortAd;
    }

    public function getSortAd_bak()
    {
        if($this->currentTable == 'ad_sort_one'){
            $sortAd = AdSortOne::find()->where("is_ad = 1")->select('camp_id')->groupBy("camp_id")->asArray()->all();
        }else{
            $sortAd = AdSortTwo::find()->where("is_ad = 1")->select('camp_id')->groupBy("camp_id")->asArray()->all();
        }
        return $sortAd;
    }







    public function getTaskStatus()
    {
        $statusModel = new TaskStatus();
        return $statusModel->getAdSortStatus();
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
}