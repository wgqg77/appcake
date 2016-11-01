<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\modules\appcake\models\DownloadWeek;
use app\modules\appcake\models\DownloadDone;
use app\modules\appcake\models\DownloadInstall;
use app\modules\appcake\models\CakeAdIdfa;
use app\modules\appcake\models\IdfaAppidV4;
use app\modules\appcake\models\AnalogClickRecord;
use app\modules\appcake\models\BatAll;
use app\modules\appcake\models\AppData;
use app\modules\appcake\models\DataSummary;
use app\components\Common;
use Yii;
class Data_mergeController extends Controller
{
    /**
     * 汇总数据合并到data_summary
     */
    public function actionAdData($strartTime=null,$endTime=null)
    {
        if($strartTime == null){
            $strartTime = isset($_SERVER["argv"][2]) ? $_SERVER["argv"][2] : date('Y-m-d',strtotime('-1 day'));
            $endTime = isset($_SERVER["argv"][3]) ? $_SERVER["argv"][3] : date('Y-m-d',time());
        }

        $mergeModel = new DataSummary();
        $isInserted = $mergeModel->IsInserted($strartTime);
        if($isInserted){
            $logStr = date("Y-m-d H:i:s",time()) ."执行开始时间: $strartTime 认定已经执行过,停止任务.";
            Yii::info($logStr . $endTime ,'merge');die;
        }
//        $strartTime = '2016-01-01';
//        $endTime = '2016-10-01';

        //查询广告点击数据
        $clickModel = new DownloadWeek();
        $clickData = $clickModel->getData($strartTime,$endTime);
        $clickPkey = $this->getPkey($clickData);
        $clickCampId = $this->getId($clickData);
        $clickData = $this->getCount($clickData);

        //下载
        $downModel = new DownloadDone();
        $downData = $downModel->getData($strartTime,$endTime);
        $downPkey = $this->getPkey($downData);
        $downCampId = $this->getId($downData);
        $downData = $this->getCount($downData);

        //安装
        $installModel = new DownloadInstall();
        $installData = $installModel->getData($strartTime,$endTime);
        $installPkey = $this->getPkey($installData);
        $installCampId = $this->getId($installData);
        $installData = $this->getCount($installData);

        //激活
        $activeModel = new CakeAdIdfa();
        $activeData = $activeModel->getData($strartTime,$endTime);
        $activePkey = $this->getPkey($activeData);
        $activeCampId = $this->getId($activeData);
        $activeData = $this->getCount($activeData);

        //模拟点击
        $analogclickModel = new AnalogClickRecord();
        $analogclickData = $analogclickModel->getData($strartTime,$endTime);
        $analogclickPkey = $this->getPkey($analogclickData);
        $analogclickCampId = $this->getId($analogclickData);
        $analogclickData = $this->getCount($analogclickData);

        //hook点击
        $clickModel = new IdfaAppidV4();
        $clickHookData = $clickModel->getData($strartTime,$endTime);
        $clickHookPkey = $this->getPkey($clickHookData);
        $clickHookCampId = $this->getId($clickHookData);
        $clickHookData = $this->getCount($clickHookData);

        //hook激活
        $hookActiveData = $activeModel->getHookData($strartTime,$endTime);
        $hookActivePkey = $this->getPkey($hookActiveData);
        $hookActiveCampId = $this->getId($hookActiveData);
        $hookActiveData = $this->getCount($hookActiveData);

        $mergeCampId = $this->toArray(array_unique(array_merge($clickCampId,$downCampId,$installCampId,$activeCampId,$analogclickCampId,$clickHookCampId,$hookActiveCampId)));
        $pKey = $this->toArray(array_unique(array_merge($clickPkey,$downPkey,$installPkey,$activePkey,$analogclickPkey,$clickHookPkey,$hookActivePkey)));

        $adModel = new BatAll();
        $adData = $adModel->getAdByCampId($mergeCampId);

        $ad = array();
        foreach($adData as $k => $v)
        {
            $ad[$v['camp_id']] = $v;
        }
        unset($adData);

        $sql_1 = "INSERT INTO `data_summary` (date,app_id,name,camp_id,country,countries,category,ad_source,click,download,install,cake_active,h_click,h_active,analog_click,CP,AP,TA,TP,payout_amount,income,origin_camp_id) VALUES ";
        $i = 0;
        $sql = '';
        $times = 0;
        $date = date("Y-m-d",strtotime($strartTime));


        foreach($pKey as $k => $v)
        {
            $i ++ ;
            $temp = explode('|',$v);
            $camp_id = $this->isEmpty_int($temp[0]);
            $country = $this->isEmpty_str($temp[1]);
            $app_id = $this->isEmpty_int( $ad[$camp_id]['mobile_app_id'] = isset($ad[$camp_id]['mobile_app_id']) ? $ad[$camp_id]['mobile_app_id'] : 0 );
            $ad_source = $this->isEmpty_int( $ad[$camp_id]['source'] = isset($ad[$camp_id]['source']) ? $ad[$camp_id]['source'] : 0  );
            $name = $this->isEmpty_str($ad[$camp_id]['name'] = isset($ad[$camp_id]['name']) ? $ad[$camp_id]['name'] : '-'   );
            $category = $this->isEmpty_str( $ad[$camp_id]['category'] = isset($ad[$camp_id]['category']) ? $ad[$camp_id]['category'] : '-'   );
            $originCampId = $this->isEmpty_str( $ad[$camp_id]['origin_camp_id'] = isset($ad[$camp_id]['origin_camp_id']) ? $ad[$camp_id]['origin_camp_id'] : '-'   );
            $countries = $this->isEmpty_str($ad[$camp_id]['countries'] = isset($ad[$camp_id]['countries']) ? $ad[$camp_id]['countries'] : '-'   );
            $acquisition_flow = $this->isEmpty_str( $ad[$camp_id]['acquisition_flow'] = isset($ad[$camp_id]['acquisition_flow']) ? $ad[$camp_id]['acquisition_flow'] : 0  );
            $payout_amount = $this->isEmpty_int( $ad[$camp_id]['payout_amount'] = isset($ad[$camp_id]['payout_amount']) ? $ad[$camp_id]['payout_amount'] : 0  );
            $payout_currency = $this->isEmpty_str(  $ad[$camp_id]['payout_currency'] = isset($ad[$camp_id]['payout_currency']) ? $ad[$camp_id]['payout_currency'] : 0  );

            $click = $this->isEmpty_int($clickData[$v] = isset($clickData[$v]) ? $clickData[$v] : 0);
            $down = $this->isEmpty_int($downData[$v] = isset($downData[$v]) ? $downData[$v] : 0 );
            $install = $this->isEmpty_int($installData[$v] = isset($installData[$v]) ? $installData[$v] : 0 );
            $active = $this->isEmpty_int($activeData[$v] = isset($activeData[$v]) ? $activeData[$v] : 0 );

            $cp = $active > 0 && $click > 0 ? round($active/$click , 2) : 0;

            $hookClick = $this->isEmpty_int($clickHookData[$v] = isset($clickHookData[$v]) ? $clickHookData[$v] : 0  );
            $hookActive = $this->isEmpty_int($hookActiveData[$v] = isset($hookActiveData[$v]) ? $hookActiveData[$v] : 0 );
            $ap = $hookActive > 0 && $hookClick > 0 ? round($hookActive / $hookClick,2) : 0 ;

            $analogClick = $this->isEmpty_int($analogclickData[$v] = isset($analogclickData[$v]) ? $analogclickData[$v] : 0 );

            $ta = $hookActive + $active;
            $tp = $ta > 0 && $click + $hookClick > 0  ?  round(($ta) / ($click + $hookClick) , 2 ) : 0 ;
            $income = $ta * $payout_amount ;

            //to string

            $camp_id = $camp_id > 0 ?  $camp_id . "_": 0;


            $ad_source = $this->getAdSource($ad_source);
            $sql .=  $sql_1 . "('{$date}',$app_id,'{$name}','{$camp_id}','{$country}','{$countries}','{$category}','{$ad_source}',$click,$down,$install,$active,$hookClick,$hookActive,$analogClick,$cp,$ap,$ta,$tp,$payout_amount,$income,$originCampId);";

            if($i >=1000){
                $mergeModel->InsertBysql($sql);
                $i = 0;
                $times ++ ;
                $sql = '';
            }

        }


        $mergeModel->InsertBysql($sql);


        $num = $times * 1000 + $i;

        $logStr = date("Y-m-d H:i:s",time()) ."执行总条数: $num" ;
        $logStr .=  '查询参数开始时间:' .$strartTime . '结束时间:' . $endTime  . '点击量合计:' . array_sum($clickData) .  ' 下载量合计: ' . array_sum($downData)  . ' 安装量合计:' . array_sum($installData) . ' 激活量合计:' . array_sum($activeData) . ' 模拟点击合计:' . array_sum($analogclickData) . ' hook点击合计:' . array_sum($clickHookData) . ' hook激活合计:  ' . array_sum($hookActiveData) ;
        echo $logStr. "\n\r";
        Yii::info($logStr . $endTime ,'merge');//die;
    }

    public function getAdSource($source)
    {
        if(isset(Yii::$app->params['ad_source'][$source])){
            $sourceName = Yii::$app->params['ad_source'][$source];
        }else{
            $sourceName = "未定义渠道id" . $source;
        }
        return $sourceName;
    }

    /**
     * @param null $strartTime
     * @param null $endTime
     * @des 非广告数据合并汇总
     */
    public function actionNotAdData($strartTime=null,$endTime=null)
    {
        ini_set('memory_limit','800M');
        if($strartTime == null){
            $strartTime = isset($_SERVER["argv"][2]) ? $_SERVER["argv"][2] : date('Y-m-d',strtotime('-1 day'));
            $endTime = isset($_SERVER["argv"][3]) ? $_SERVER["argv"][3] : date('Y-m-d',time());
        }

        //if(YII_ENV) $strartTime = '2016-01-01';

        $mergeModel = new DataSummary();
        $isInserted = $mergeModel->IsInserted($strartTime,1);
        if($isInserted){
            $logStr = date("Y-m-d H:i:s",time()) ."执行开始时间: $strartTime 非广告任务,认定已经执行过,停止任务.";
            Yii::info($logStr . $endTime ,'merge');die;
        }

        //查询写入汇总非广告点击数据
        $this->writeClickData($strartTime,$endTime);

        //下载
        $this->writeDownData($strartTime,$endTime);

        //安装
        $this->writeInstallData($strartTime,$endTime);

        //查询遍历写入app信息
        $this->writeAppInfo($strartTime,false);

        $logStr = date("Y-m-d H:i:s",time()) ."执行非广告数据合并汇总完成";
        echo $logStr . "\n\r";
        Yii::info($logStr . $endTime ,'merge');//die;
    }

    public $limit = 1000;
    /**
     * 遍历查询写入点击数据
     */
    public function writeClickData($strartTime,$endTime){
        $clickModel = new DownloadWeek();
        $count = $clickModel->getCountNumNotAd($strartTime,$endTime,false);


        $times = ceil($count / $this->limit);
        $sql = ''; $num = 0;
        $sql_1 = "INSERT INTO `data_summary` (date,app_id,country,click) VALUES ";

        for($i = 0 ;$i < $times;$i++) {

            $clickData = $clickModel->getDataNotAdWithPosition($strartTime, $endTime, $i, $this->limit,false);
            $clickPkey = $this->getPkey($clickData);
            $clickData = $this->getCount($clickData);
            foreach ($clickPkey as $k => $v) {
                $num ++;
                $temp = explode('|', $v);
                $date = $strartTime;
                $app_id = $this->isEmpty_int($temp[0]);
                $country = $this->isEmpty_str($temp[1]);

                $click = $this->isEmpty_int($clickData[$v] = isset($clickData[$v]) ? $clickData[$v] : 0);
                $sql .= "('{$date}',$app_id,'{$country}',$click),";

            }

            if($sql){
                $sql = $sql_1 . rtrim($sql,',') .';';
                Common::dbExecute($sql);
                $sql = '';
            }

        }
        $logStr = date("Y-m-d H:i:s",time()) ." 写入点击汇总数据 执行总条数: $num " ;
        $logStr .=  '查询参数开始时间:' .$strartTime . '结束时间:' . $endTime  ;
        echo $logStr ."\n\r";
        Yii::info($logStr . $endTime ,'merge');
        return true;
    }


    /**
     * 遍历查询写入下载数据
     */
    public function writeDownData($strartTime,$endTime){

        $downModel = new DownloadDone();
        $count = $downModel->getCountNumNotAd($strartTime,$endTime,false);

        $times = ceil($count / $this->limit);
        $sql = ''; $num = 0;
        $sql_1 = "INSERT INTO `data_summary` (date,app_id,country,download) VALUES ";

        for($i = 0 ;$i < $times;$i++) {

            $downData = $downModel->getDataNotAdWithPosition($strartTime, $endTime, $i, $this->limit,false);
            $downPkey = $this->getPkey($downData);
            //$downCampId = $this->getId($downData,'app_id');
            $downData = $this->getCount($downData);
            foreach ($downPkey as $k => $v) {
                $num ++;
                $temp = explode('|', $v);
                $date = $strartTime;
                $app_id = $this->isEmpty_int($temp[0]);
                $country = $this->isEmpty_str($temp[1]);

                $down = $this->isEmpty_int($downData[$v] = isset($downData[$v]) ? $downData[$v] : 0);
                $sql .= "('{$date}',$app_id,'{$country}',$down),";

            }

            if($sql){
                $sql = $sql_1 . rtrim($sql,',') ."ON DUPLICATE KEY UPDATE download=VALUES(download);";
                Common::dbExecute($sql);
                $sql = '';
            }

        }
        $logStr = date("Y-m-d H:i:s",time()) ." 写入下载汇总数据 执行总条数: $num " ;
        $logStr .=  '查询参数开始时间:' .$strartTime . '结束时间:' . $endTime  ;
        echo $logStr ."\n\r" ;
        Yii::info($logStr . $endTime ,'merge');
        return true;
    }

    /**
     * 遍历查询写入安装数据
     */
    public function writeInstallData($strartTime,$endTime){

        $installModel = new DownloadInstall();
        $count = $installModel->getCountNumNotAd($strartTime,$endTime,false);

        $times = ceil($count / $this->limit);
        $sql = ''; $num = 0;
        $sql_1 = "INSERT INTO `data_summary` (date,app_id,country,install) VALUES ";

        for($i = 0 ;$i < $times;$i++) {

            $installData = $installModel->getDataNotAdWithPosition($strartTime, $endTime, $i, $this->limit,false);
            $installPkey = $this->getPkey($installData);
            $installData = $this->getCount($installData);
            foreach ($installPkey as $k => $v) {
                $num ++;
                $temp = explode('|', $v);
                $date = $strartTime;
                $app_id = $this->isEmpty_int($temp[0]);
                $country = $this->isEmpty_str($temp[1]);

                $install = $this->isEmpty_int($installData[$v] = isset($installData[$v]) ? $installData[$v] : 0);
                $sql .= "('{$date}',$app_id,'{$country}',$install),";

            }

            if($sql){
                $sql = $sql_1 . rtrim($sql,',') ."ON DUPLICATE KEY UPDATE install=VALUES(install);";
                Common::dbExecute($sql);
                $sql = '';
            }

        }
        $logStr = date("Y-m-d H:i:s",time()) ." 写入安装汇总数据 执行总条数: $num " ;
        $logStr .=  '查询参数开始时间:' .$strartTime . '结束时间:' . $endTime  ;
        echo $logStr ."\n\r" ;
        Yii::info($logStr . $endTime ,'merge');
        return true;
    }

    /**
     * 写入app信息
     */
    public function writeAppInfo($date,$isAd = true){

        $Model = new DataSummary();

        $count = $Model->getCount($date,$isAd);
        $appDataModle = new AppData();

        $times = ceil($count / $this->limit);
        $sql = ''; $num = 0;
        $sql_1 = "update `data_summary` set  ";
        echo "------count => {$count} times=>{$times} \n";
        for($i = 0 ;$i < $times;$i++) {
            $data = $Model->getNotAdData($date,$i, $this->limit);

            $appIdArr= array_column($data,'app_id');
            $fields = "app_id,app_name,category";
            $appData = $appDataModle->getAppInfoInAppId($appIdArr,$fields);
            foreach ($appData as $k => $v) {
                $num ++;
                $name = str_replace(array("'","\'","\"",'"'),' ',$v['app_name']);

                $sql .= $sql_1 ." name = '{$name}' , category = '{$v['category']}'  where date = '{$date}'  and app_id = {$v['app_id']};";

            }

            if($sql){
                echo "------------num => {$num}\n";
                Common::dbExecute($sql);
                $sql = '';
            }

        }
        $logStr = date("Y-m-d H:i:s",time()) ." 写入安app信息数据 执行总条数: $num " ;
        $logStr .=  '查询参数开始时间:' .$date  ;
        echo $logStr ."\n\r" ;
        Yii::info($logStr,'merge');
        return true;
    }

    public function toArray($data)
    {
        return $data = is_array($data) ? $data : array();
    }

    public function getPkey($data,$pkey="pkey")
    {
        return $this->toArray(array_column($data,$pkey));
    }

    public function getCount($data,$count='count',$pkey='pkey')
    {
        return $this->toArray(array_column($data,$count,$pkey));
    }

    public function getId($data,$id='camp_id')
    {
        return $this->toArray(array_column($data,$id));
    }

    public function isEmpty_str($data)
    {
        //return $data; // = empty($data) ? 'null' : $data;
        $rule = '/(\')/';
        preg_match($rule,$data,$result);
        if($result){
            $data = str_replace("'","\'",$data);
            return $data;
        }else{
            return $data;
        }
    }

    public function isEmpty_int($data)
    {
        return $data = isset($data) && !empty($data) ? $data : 0 ;
    }

    //1 广告 0 非广告 导入合并31天数据
    public function actionIndex()
    {
        $isAd = isset($_SERVER["argv"][2]) ? 1 : 0;
        for($i = 1 ;$i <= 31; $i ++)
        {
            echo "当前任务第{$i}条\n\r";
            $strTime = date('Y-m-d',strtotime('-'.$i.' day'));
            $endTime = date('Y-m-d',strtotime('-'.$i + 1 .' day'));
            if($isAd){
                $this->actionAdData($strTime,$endTime);
            }else{
                $this->actionNotAdData($strTime,$endTime);
            }

        }
    }

}
