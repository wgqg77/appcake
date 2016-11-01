<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\modules\appcake\models\CakeAdIdfa;
use app\modules\appcake\models\search\BatAll;
use yii\console\Controller;
use app\modules\appcake\models\ActiveDataRecord;
use Yii;

class ActiveController extends Controller
{
    //汇总每日总激活量 按广告渠道算收入
    public function actionActiveData()
    {
        $date = date('Y-m-d',strtotime('-1 day'));
        $data = $this->getTotalActive($date);

        $sql = '';
        foreach($data as $k => $v){
            $source = $this->getSource($v['source']);
            $sql .= "insert into active_data VALUES (0,{$v['active_num']},{$v['total_price']},'{$source}','{$date}');";
        }
        $this->InsertBysql($sql);
    }

    /**
     * 激活数据记录 收入计算
     */
    public function actionActiveRecordData()
    {
        $date = date('Y-m-d',strtotime('-1 day'));
        $record = $this->getYesterdayActive();
        $camp_id = array_column($record,'camp_id');
        //查询广告信息
        $ad = $this->getAdByCampid($camp_id);
        $adName = array_column($ad,'name','camp_id');
        $adSource = array_column($ad,'source','camp_id');
        $price = array_column($ad,'payout_amount','camp_id');
        $sql = "";  $sqlNum = 0; $i = 0;
        foreach($record as $k => $v){
            $i ++ ;

            $totalPrice = $price[$v['camp_id']] * $v['count'];
            $name = str_replace('\'','',$adName[$v['camp_id']]);
            $source = $this->getSource($adSource[$v['camp_id']]);
            $sql .= "insert into active_data_record VALUES (0,{$v['app_id']},{$v['camp_id']},'{$name}',{$v['count']},{$price[$v['camp_id']]},{$totalPrice},'{$source}','{$date}');";


            $sqlNum ++;
            if($sqlNum >= 200){
                $this->InsertBysql($sql);
                $sqlNum = 0;
                $sql = "";
            }
        }

        $this->InsertBysql($sql);
        $logstr = date("Y-m-d",time()) . " 合并激活数据记录 本次任务共 {$i} 条 date:{$date} \n\r" ;
        echo $logstr;
        Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'active_data');
        $this->actionActiveData();
    }

    public function getSource($source)
    {
        $sourceData = Yii::$app->params['ad_source'];
        $source = isset($sourceData[$source]) ? $sourceData[$source] : $source;
        return $source;
    }


    public function getYesterdayActive()
    {
        $yesterday = date('Y-m-d',strtotime('-1 day'));
        $res = CakeAdIdfa::find()->where("date = '{$yesterday}'")->select("count(*) as count,camp_id,app_id")->groupBy("camp_id")->asArray()->all();
        return $res;
    }

    public function getAdByCampid($camp_id)
    {
        if(count($camp_id) > 200){
            $ad = $this->getAd($camp_id);
        }else{
            $count = ceil(count($camp_id)/200);
            $ad = array();
            for($i=0;$i<$count;$i++){
                $start = $i * 200;
                $camp_idtemp = array_slice($camp_id,$start,200);
                $adtemp =$this->getAd($camp_idtemp);
                $adtemp = is_array($adtemp) ? $adtemp : array();
                $ad = array_merge($ad,$adtemp);
            }
        }
        return $ad;
    }

    public function getAd($camp_id)
    {
        $ad = BatAll::find()->where(['camp_id' => $camp_id])->select("camp_id,name,source,payout_amount")->asArray()->all();
        return $ad;
    }

    public function getTotalActive($date)
    {
       return  ActiveDataRecord::find()->where("date = '{$date}'")->select(" sum(total_price) as total_price ,sum(active_num) as active_num,source ")->groupBy("source,date")->asArray()->all();
    }

    public function InsertBysql($sql)
    {
        try{
            $connection = \Yii::$app->db;
            return $connection->createCommand($sql)->execute();
        }catch(\Exception $e){
            $trace = $e->getTraceAsString();
            Yii::info(date("Y-m-d H:i:s",time()) . $trace ,'active_data');
        }
    }
}
