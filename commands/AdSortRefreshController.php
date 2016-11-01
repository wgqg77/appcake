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
class AdSortRefreshController extends Controller
{

    public $currentTable = '';
    public $AdCountry = array();
    public $listMaxNum = array();

    /**
     * 刷新排序列表 广告排前 刷新排序
     * 每周1-2次
     * 广告排序提前
     * 非广告按下载量追加
     * 注:在可编辑之前更新
     * ./yii ad-sort-refresh/index
     */
    public function actionIndex()
    {
        $task = $this->getTaskStatus();
        $this->currentTable = $task['ad_sort_current_table'];

        //当前列表广告国家
        $this->getSortCountry();


        //刷新排序列表
        $this->refreshAdList();


    }

    public function refreshAdList()
    {
       $refreshAdListSql = '';
       foreach($this->AdCountry as $k => $v){
            for($i=0;$i<=2;$i++){
                //广告 更新排序

                $list = $this->getSortList($v,$i);

                $this->updateAdSort($list);
                $NotAdStartNum = count($list);

                if($NotAdStartNum >= 200) continue;

                //非广告 接着广告最大位置开始插入非广告
                $appId = array_column($list,'app_id');
                $topApp = $this->getTopApp($appId,$v,$i);
                $notAdList = $this->getSortList($v,$i,0);
                $this->replaceNotAdApp($topApp,$NotAdStartNum,$notAdList);
            }
       }
    }

    public function replaceNotAdApp($topApp,$NotAdStartNum,$notAdList)
    {
        $sql = '';$sqlNum = 0;
        foreach($notAdList as $k => $v){
            if($NotAdStartNum > 200) break;

            $NotAdStartNum ++;
            $sqlNum ++;

            $app_id = isset($topApp[$k]['app_id']) ? $topApp[$k]['app_id'] : $v['app_id'];
            $sql .= "update {$this->currentTable} set app_id = {$app_id},camp_id=0,source=0,current_sort={$NotAdStartNum},is_ad=0 where id={$v['id']} ;";
        }
        $this->InsertBysql($sql);
        return true;
    }



    public function getTopApp($appId,$country,$position)
    {
        if($position == 1){
            $category = " and category != 'Games' ";
        }else if($position == 2){
            $category = " and category = 'Games' ";
        }else{
            $category = '';
        }
        $yesterday = date("Y-m-d",strtotime("-1 day"));
        $data = DataSummary::find()->where(['not in', 'app_id', $appId])->andWhere("country = '{$country}'  and date = '{$yesterday}' and camp_id = 0 $category ")->select('app_id')->orderBy("download desc")->asArray()->limit(200)->all();
        return $data;
    }

    public function updateAdSort($list)
    {
        $sql = '';$sqlNum = 0;
        foreach($list as $k => $v){
            $index = $k+1;
            if($v['current_sort'] != $index){
                $sqlNum ++;
                $sql .="update {$this->currentTable} set current_sort = {$index} where id = {$v['id']};";

            }
        }

        $this->InsertBysql($sql);
        return true;

    }


    public function getSortCountry()
    {
        $country = AdCountry::find()->where("status = 1")->select("country_code")->asArray()->all();
        $country = array_column($country,'country_code');
        $this->AdCountry = $country;
        return true;
    }

    public function getSortList($country,$position,$isAd=1)
    {
        $isAdSql = $isAd == 1 ? " and is_ad = 1 " : " and is_ad = 0  ";
        if($this->currentTable == 'ad_sort_one'){
            $list = AdSortOne::find()->where("country = '{$country}' and position = {$position}  $isAdSql ")->orderBy("current_sort asc")->asArray()->all();
        }else{
            $list = AdSortTwo::find()->where("country = '{$country}' and position = {$position}  $isAdSql ")->orderBy("current_sort asc")->asArray()->all();
        }
        return $list;
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