<?php

namespace app\modules\appcake\models;

use Yii;
use app\components\Common;
/**
 * This is the model class for table "data_summary".
 *
 * @property integer $id
 * @property string $date
 * @property integer $app_id
 * @property integer $camp_id
 * @property integer $ad_source
 * @property string $country
 * @property integer $click
 * @property integer $download
 * @property integer $install
 * @property integer $cake_active
 * @property integer $h_click
 * @property integer $h_active
 * @property integer $analog_click
 * @property string $acquisition_flow
 * @property double $payout_amount
 * @property string $payout_currency
 */
class DataSummary extends \yii\db\ActiveRecord
{


    public $isAd = 0 ; //0广告 1非广告
    public $adSource = 0 ; //广告渠道
    public $byCountry = 0 ;// 是否细分国家 0不细分 1 细分
    public $startTime = '';
    public $endTime = '';
    public $clickTotal = 0;
    public $downloadTotal = 0;
    public $installTotal = 0;
    public $cakeActiveTotal = 0;
    public $hClickTotal = 0;
    public $hActiveTotal = 0;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_summary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['app_id', 'camp_id', 'ad_source', 'click', 'download', 'install', 'cake_active', 'h_click', 'h_active', 'analog_click'], 'integer'],
            [['country'], 'required'],
            [['payout_amount'], 'number'],
            [['country'], 'string', 'max' => 2],
            [['name'], 'string', 'max' => 256],
            [['category'], 'string', 'max' => 255],
            [['countries'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => '日期',
            'app_id' => 'App ID',
            'camp_id' => 'Camp ID',
            'ad_source' => '广告主',
            'country' => '国家',
            'click' => '点击量',
            'download' => '下载量',
            'install' => '安装量',
            'cake_active' => 'Cake激活量',
            'h_click' => 'appSotre点击量',
            'h_active' => 'appSotre激活量',
            'analog_click' => '分配量',
            'payout_amount' => '单价',
            'name' => '名称',
            'category' => '分类',
            'countries' => '投放国家',
            'CP' => 'cake激活/点击',
            'AP' => 'appsotre 激活/点击比',
            'TA' => '总激活数',
            'TP' => '总激活/点击比',
            'income' => '收入',
        ];
    }


    public function InsertBysql($sql)
    {
        $connection = \Yii::$app->db;
        return $connection->createCommand($sql)->execute();
    }

    public function IsInserted($startTime,$isAd=0)
    {
        if($isAd == 0 ){
            return $this->find()->where("date='{$startTime}' and camp_id > 0 ")->asArray()->one();
        }else{
            return $this->find()->where("date='{$startTime}' and camp_id is null")->asArray()->one();
        }

    }

    /**
     * 根据下载量查询200条免费app
     * @param $countryArr
     * @param int $limit
     * @return array
     */
    public function getAppByCountry($countryArr,$limit =200)
    {
        if(!empty($countryArr)){
            $data = array();
            foreach($countryArr as $k => $v){
                $data[$v] = $this->find()->where("country = '{$v}' and camp_id > 0")->orderBy('country,app_id')->select('app_id,country')->asArray()->limit($limit)->all();
                echo count($data[$v]) ."\n\r";
            }
            if(!empty($data)){
                return $data;
            }else{
                return array();
            }
        }
        else
        {
            return array();
        }
    }

    /**
     * 查询当前投放广告的历史下载数据
     */
    public function getAdDownloadNum($ad){
        if(!empty($ad)){
            foreach($ad as $k => $v){
                $down = $this->find()->where("country = '{$v['country']}' and camp_id = {$v['camp_id']}")->orderBy('country,camp_id')->select('download')->orderBy("download desc")->asArray()->one();
                $down = $down == true ? $down : 0;
                $ad[$k]['download'] = $down;
            }
            return $ad;
        }else{
            return array();
        }
    }

    /**
     * 查询当前投放广告的历史下载数据
     */
    public function getNotAdDownloadNum($countryArr,$appId,$limit =200){
        if(!empty($countryArr)){
            $data = array();
            foreach($countryArr as $k => $v){
                if(isset($appId[$v]) && count($appId[$v]) > 0 ){
                    $temAppid = trim(implode(',',$appId[$v]));

                    $app_id = 'and app_id not in (' .$temAppid . ')';
                }else{
                    $app_id = '';
                }

                $temp = $this->find()->where("country = '{$v}' and camp_id > 0  $app_id")->orderBy('country,app_id')->select('app_id,country,download,ad_source,category')->orderBy("download desc")->groupBy("app_id")->asArray()->limit($limit)->all();
                $temp = $temp == true ? $temp : array();
                $data[$v] = $temp;
            }
            return $data;
        }else{
            return array();
        }
    }

    public function getCount($date,$isAd){
        if(!$isAd){
            $countSql = "select count(*) as count
                from (
                select count(*) as count from data_summary
                where date = '{$date}'
                and camp_id = 0
                group by app_id ) as a;";
        }else{
            $countSql = "select count(*) as count from data_summary
                where date = '{$date}'
                and camp_id > 0
                group by app_id ;";
        }
        $count = Common::dbQuery($countSql);
        $count = !empty($count) ? $count[0]['count'] : 0;
        return $count;
    }


    public function getNotAdData($date,$i, $limit){
        $start = $i * $limit;
        $countSql = "
                select app_id from data_summary
                where date = '{$date}'
                and camp_id = 0
                group by app_id
                limit $start,$limit ;";

        $data = Common::dbQuery($countSql);
        return $data;
    }

    public function getSumByDate($strartTime){
        $strartTime = date('Y-m-d',strtotime($strartTime));

        return $this->find()
            ->where("date = '{$strartTime}' ")
            ->select("sum(download) as cakeDownload, sum(cake_active) as cakeActive, sum(h_click) as hookDownload, sum(h_active) as hookActive, sum(income) as totalIncome")
            ->asArray()
            ->one();
    }

    //查询优选昨日收入广告
    public function getYesAdByAppId($appIdArr,$country){
        $yesterday = date("Y-m-d",strtotime('-1 day'));
        $res =$this->find()
            ->where(['date'=>$yesterday,'country'=>$country,'app_id'=>$appIdArr])
            ->andWhere("camp_id > 0 and income > 0")
            ->select("app_id,camp_id,ad_source,income")
            ->orderBy('income desc')
            ->asArray()
            ->all();
        if($res){
            return $res;
        }else{
            return array();
        }
    }

}
