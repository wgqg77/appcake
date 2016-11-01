<?php

namespace app\modules\appcake\models;

use Yii;
use app\components\Common;
/**
 * This is the model class for table "data_summary_position".
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
 * @property string $position
 * @property string $acquisition_flow
 * @property double $payout_amount
 * @property string $payout_currency
 * @property string $name
 * @property string $category
 * @property string $countries
 */
class DataSummaryPosition extends \yii\db\ActiveRecord
{

    public $isAd = 0 ; //0广告 1非广告
    public $adSource = 0 ; //广告渠道
    public $byCountry = 0 ;// 是否细分国家 0不细分 1 细分
    public $startTime = '';
    public $endTime = '';
    public $clickTotal = 0;
    public $downloadTotal = 0;
    public $installTotal = 0;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_summary_position';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['app_id', 'camp_id', 'ad_source', 'click', 'download', 'install'], 'integer'],
            [['country', 'payout_currency'], 'required'],
            [['country'], 'string', 'max' => 2],
            [['position'], 'string', 'max' => 10],
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
            'date' => 'Date',
            'app_id' => 'App ID',
            'camp_id' => 'Camp ID',
            'ad_source' => 'Ad Source',
            'country' => 'Country',
            'click' => 'Click',
            'download' => 'Download',
            'install' => 'Install',
            'position' => 'Position',
            'name' => 'Name',
            'category' => 'Category',
            'countries' => 'Countries',
        ];
    }

    public function IsInserted($startTime,$isAd=0)
    {
        if($isAd == 0 ){
            return $this->find()->where("date='{$startTime}' and camp_id > 0 ")->asArray()->one();
        }else{
            return $this->find()->where("date='{$startTime}' and camp_id is null")->asArray()->one();
        }

    }

    public function getCount($date,$isAd){
        if(!$isAd){
            $countSql = "select count(*) as count
                from (
                select count(*) as count from data_summary_position
                where date = '{$date}'
                and camp_id = 0
                group by app_id ) as a;";
        }else{
            $countSql = "select count(*) as count from data_summary_position
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
                select app_id from data_summary_position
                where date = '{$date}'
                and camp_id = 0
                group by app_id
                limit $start,$limit ;";

        $data = Common::dbQuery($countSql);
        return $data;
    }


}
