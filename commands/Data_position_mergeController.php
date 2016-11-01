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
use app\modules\appcake\models\BatAll;
use app\modules\appcake\models\AppData;
use app\modules\appcake\models\DataSummary;
use app\modules\appcake\models\DataSummaryPosition;
use app\components\Common;
use Yii;
class Data_position_mergeController extends Controller
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


        //$strartTime = '2016-01-01';

        $mergeModel = new DataSummaryPosition();
        $isInserted = $mergeModel->IsInserted($strartTime);
        if($isInserted){
            $logStr = date("Y-m-d H:i:s",time()) ."[positon广告任务]执行开始时间: $strartTime 认定已经执行过,停止任务.";
            echo $logStr;
            Yii::info($logStr . $endTime ,'merge');die;
        }

        //查询广告点击数据
        $clickModel = new DownloadWeek();
        $clickData = $clickModel->getDataWithPosition($strartTime,$endTime);
        $clickPkey = $this->getPkey($clickData);
        $clickCampId = $this->getId($clickData);
        $clickData = $this->getCount($clickData);

        //下载
        $downModel = new DownloadDone();
        $downData = $downModel->getDataWithPosition($strartTime,$endTime);
        $downPkey = $this->getPkey($downData);
        $downCampId = $this->getId($downData);
        $downData = $this->getCount($downData);

        //安装
        $installModel = new DownloadInstall();
        $installData = $installModel->getDataWithPosition($strartTime,$endTime);
        $installPkey = $this->getPkey($installData);
        $installCampId = $this->getId($installData);
        $installData = $this->getCount($installData);


        $mergeCampId = $this->toArray(array_unique(array_merge($clickCampId,$downCampId,$installCampId)));
        $pKey = $this->toArray(array_unique(array_merge($clickPkey,$downPkey,$installPkey)));

        $adModel = new BatAll();
        $adData = $adModel->getAdByCampId($mergeCampId);

        $ad = array();
        foreach($adData as $k => $v)
        {
            $ad[$v['camp_id']] = $v;
        }
        unset($adData);

        $sql_1 = "INSERT INTO `data_summary_position` (date,app_id,camp_id,ad_source,country,position,click,download,install,name,category,countries) VALUES ";
        $i = 0;
        $sql = '';
        $times = 0;
        $date = date("Y-m-d",strtotime($strartTime));
        $mergeModel = new DataSummary();

        foreach($pKey as $k => $v)
        {
            $i ++ ;
            $temp = explode('|',$v);

            $camp_id = $this->isEmpty_int($temp[0]);
            $country = $this->isEmpty_str($temp[1]);

            $position = $this->isEmpty_str($temp[2]);
            $app_id = $this->isEmpty_int($ad[$camp_id]['mobile_app_id'] = isset($ad[$camp_id]['mobile_app_id']) ? $ad[$camp_id]['mobile_app_id'] : 0 );
            $ad_source = $this->isEmpty_int($ad[$camp_id]['source']  = isset($ad[$camp_id]['source'] ) ? $ad[$camp_id]['source']  : 0 );
            $name = $this->isEmpty_str($ad[$camp_id]['name'] = isset($ad[$camp_id]['name']) ? $ad[$camp_id]['name'] : '' );
            $category = $this->isEmpty_str($ad[$camp_id]['category']  = isset($ad[$camp_id]['category']) ? $ad[$camp_id]['category'] : '' );
            $countries = $this->isEmpty_str($ad[$camp_id]['countries'] = isset($ad[$camp_id]['countries']) ? $ad[$camp_id]['countries'] : ''  );

            $click = $this->isEmpty_int($clickData[$v] = isset($clickData[$v]) ? $clickData[$v] : 0);
            $down = $this->isEmpty_int($downData[$v] = isset($downData[$v]) ? $downData[$v] : 0 );
            $install = $this->isEmpty_int($installData[$v] = isset($installData[$v]) ? $installData[$v] : 0 );


            $sql .=  $sql_1 . "('{$date}',$app_id,$camp_id,$ad_source,'{$country}','{$position}',$click,$down,$install,'{$name}','{$category}','{$countries}');";


            if($i >=1000){
                $mergeModel->InsertBysql($sql);
                $i = 0;
                $times ++ ;
                $sql = '';
            }

        }


        $mergeModel->InsertBysql($sql);


        $num = $times * 1000 + $i;

        $logStr = date("Y-m-d H:i:s",time()) ." 执行总条数: $num " ;
        $logStr .=  '查询参数开始时间:' .$strartTime . '结束时间:' . $endTime  . '点击量合计:' . array_sum($clickData) .  ' 下载量合计: ' . array_sum($downData)  . ' 安装量合计:' . array_sum($installData)  ;
        echo $logStr."\n\r";
        Yii::info($logStr . $endTime ,'merge');//die;
    }

    public $limit = 1000;
    /**
     * 遍历查询写入点击数据
     */
    public function writeClickData($strartTime,$endTime){
        $clickModel = new DownloadWeek();

        $count = $clickModel->getCountNumNotAd($strartTime,$endTime);

        $times = ceil($count / $this->limit);
        $sql = ''; $num = 0;
        $sql_1 = "INSERT INTO `data_summary_position` (date,app_id,country,position,click) VALUES ";

        for($i = 0 ;$i < $times;$i++) {

            $clickData = $clickModel->getDataNotAdWithPosition($strartTime, $endTime, $i, $this->limit);
            $clickPkey = $this->getPkey($clickData);
            $clickData = $this->getCount($clickData);
            foreach ($clickPkey as $k => $v) {
                $num ++;
                $temp = explode('|', $v);
                $date = $strartTime;
                $position = $this->isEmpty_str($temp[2]);
                $app_id = $this->isEmpty_int($temp[0]);
                $country = $this->isEmpty_str($temp[1]);

                $click = $this->isEmpty_int($clickData[$v] = isset($clickData[$v]) ? $clickData[$v] : 0);
                $sql .= "('{$date}',$app_id,'{$country}','{$position}',$click),";

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
        $count = $downModel->getCountNumNotAd($strartTime,$endTime);

        $times = ceil($count / $this->limit);
        $sql = ''; $num = 0;
        $sql_1 = "INSERT INTO `data_summary_position` (date,app_id,country,position,download) VALUES ";

        for($i = 0 ;$i < $times;$i++) {

            $downData = $downModel->getDataNotAdWithPosition($strartTime, $endTime, $i, $this->limit);
            $downPkey = $this->getPkey($downData);
            //$downCampId = $this->getId($downData,'app_id');
            $downData = $this->getCount($downData);
            foreach ($downPkey as $k => $v) {
                $num ++;
                $temp = explode('|', $v);
                $date = $strartTime;
                $position = $this->isEmpty_str($temp[2]);
                $app_id = $this->isEmpty_int($temp[0]);
                $country = $this->isEmpty_str($temp[1]);

                $down = $this->isEmpty_int($downData[$v] = isset($downData[$v]) ? $downData[$v] : 0);
                $sql .= "('{$date}',$app_id,'{$country}','{$position}',$down),";

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
        $count = $installModel->getCountNumNotAd($strartTime,$endTime);

        $times = ceil($count / $this->limit);
        $sql = ''; $num = 0;
        $sql_1 = "INSERT INTO `data_summary_position` (date,app_id,country,position,install) VALUES ";

        for($i = 0 ;$i < $times;$i++) {

            $installData = $installModel->getDataNotAdWithPosition($strartTime, $endTime, $i, $this->limit);
            $installPkey = $this->getPkey($installData);
            $installData = $this->getCount($installData);
            foreach ($installPkey as $k => $v) {
                $num ++;
                $temp = explode('|', $v);
                $date = $strartTime;
                $position = $this->isEmpty_str($temp[2]);
                $app_id = $this->isEmpty_int($temp[0]);
                $country = $this->isEmpty_str($temp[1]);

                $install = $this->isEmpty_int($installData[$v] = isset($installData[$v]) ? $installData[$v] : 0);
                $sql .= "('{$date}',$app_id,'{$country}','{$position}',$install),";

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

        $Model = new DataSummaryPosition();

        $count = $Model->getCount($date,$isAd);
        $appDataModle = new AppData();

        $times = ceil($count / $this->limit);
        $sql = ''; $num = 0;
        $sql_1 = "update `data_summary_position` set  ";
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

    public function actionNotAdData($strartTime=null,$endTime=null)
    {
        ini_set('memory_limit','800M');
        if($strartTime == null){
            $strartTime = isset($_SERVER["argv"][2]) ? $_SERVER["argv"][2] : date('Y-m-d',strtotime('-1 day'));
            $endTime = isset($_SERVER["argv"][3]) ? $_SERVER["argv"][3] : date('Y-m-d',time());
        }

        //if(YII_ENV) $strartTime = '2016-01-01';
        $mergeModel = new DataSummaryPosition();
        $isInserted = $mergeModel->IsInserted($strartTime,1);
        if($isInserted){
            $logStr = date("Y-m-d H:i:s",time()) ."[positon非广告任务]执行开始时间: $strartTime 认定已经执行过,停止任务.";
            echo $logStr;
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

        $logStr = date("Y-m-d H:i:s",time()) ;
        $logStr .=  '查询参数开始时间:' .$strartTime . '结束时间:' . $endTime  ;
        echo $logStr ."\n\r";
        Yii::info($logStr . $endTime ,'merge');
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

    // 广告   ./yii data_position_merge/index 1
    // 非广告 ./yii data_position_merge/index
    // 导入合并31天数据
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
