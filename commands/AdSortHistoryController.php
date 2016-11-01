<?php
/**
 *
 */

namespace app\commands;


use app\modules\appcake\models\AdSortHistory;
use app\modules\appcake\models\DownloadInstall;
use app\modules\appcakethree\models\DownloadDone;
use yii\console\Controller;
use yii;
use app\modules\appcake\models\AppData;
use app\modules\appcake\models\TaskStatus;
use app\modules\appcake\models\Bat;
use app\modules\appcake\models\AdSortOne;
use app\modules\appcake\models\AdSortTwo;
use app\modules\appcake\models\DownloadWeek;
use app\modules\appcake\models\AdCountry;
class AdSortHistoryController extends Controller
{

    public $startTime = '';
    public $endTime = '';
    public $click = array();
    public $down = array();
    public $install = array();



    /**  ---------------------------------------------------------------------------------------------------------------
     *
     *
     * 更新排序列表上个小时每个位置 点击/下载/安装数据
     * 0_0_% 首页
     * 1_0_% 应用
     * 2_0_% 游戏 (位置默认+1)
     *  ./yii ad-sort-history/history-data
     *
     */
    public function actionHistoryData()
    {
        $this->endTime = strtotime(date("Y-m-d H:00:00",time()));
        $this->startTime = $this->endTime - 60 * 60;
        $clickData = $this->getClickData();
        $downData = $this->getDownloadData();
        $installData = $this->getInstallData();

        $country = $this->getSortCountry();

        foreach($country as $k => $v){
            for($i=0;$i<=2;$i++){
                echo "当前更新国家[{$v}] 位置[{$i}] \n\r";
                $this->updateHistoryData($v,$i);
            }
        }
        $logStr = " 更新排序列表上小时数据完成.";
        echo $logStr;
        Yii::info(date("Y-m-d H:i:s",time()) . $logStr ,'adSortInserLog');
    }



    public function updateHistoryData($country,$position)
    {

        $historyData = AdSortHistory::find()->where("ad_sort_id is not null and country = '{$country}' and position = {$position} ")->select("id")->orderBy("current_sort asc")->asArray()->all();
        $sql = "";
        foreach($historyData as $k => $v){
            $index = $k + 1;
            $positionIndex = $position . "_0_" . $index;
            $pKey = $country.'|'.$positionIndex;

            $click = isset($this->click[$pKey]) ? $this->click[$pKey] : 0 ;
            $down =  isset($this->down[$pKey]) ? $this->down[$pKey] : 0 ;
            $install =  isset($this->install[$pKey]) ? $this->install[$pKey] : 0 ;
            $sql .= "update ad_sort_history set click ={$click},down={$down},install={$install} where id = {$v['id']};";
        }

        $this->InsertBysql($sql);
        return true;
    }

    public function getClickData()
    {
        $click = DownloadWeek::find()
            ->where("time >= '{$this->startTime}' and time < '{$this->endTime}'  ")
            ->select(["CONCAT(country, '|', position) AS pkey","count( * ) AS count"])
            ->groupBy("country,position")
            ->asArray()
            ->all();
        $click = array_column($click,'count','pkey');
        $this->click = $click;

        return true;
    }

    public function getSortCountry()
    {
        $country = AdCountry::find()->where("status = 1")->select("country_code")->asArray()->all();
        $country = array_column($country,'country_code');
        return $country;
    }


    public function getDownloadData()
    {
        $download = DownloadDone::find()
            ->where("time >= '{$this->startTime}' and time < '{$this->endTime}' ")
            ->select(["CONCAT(country, '|', position) AS pkey","count( * ) AS count"])
            ->groupBy("country,position")
            ->asArray()
            ->all();
        $download = array_column($download,'count','pkey');
        $this->down = $download;
        return $download;
    }

    public function getInstallData()
    {
        $install = DownloadInstall::find()
            ->where("time >= '{$this->startTime}' and time < '{$this->endTime}'  ")
            ->select(["CONCAT(country, '|', position) AS pkey","count( * ) AS count"])
            ->groupBy("country,position")
            ->asArray()
            ->all();
        $install = array_column($install,'count','pkey');
        $this->install = $install;
        return $install;
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