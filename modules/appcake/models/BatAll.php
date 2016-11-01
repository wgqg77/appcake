<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "bat_all".
 *
 * @property integer $camp_id
 * @property string $origin_camp_id
 * @property integer $source
 * @property string $creatives
 * @property string $imp_url
 * @property string $click_url
 * @property string $click_callback_url
 * @property integer $mobile_app_id
 * @property double $payout_amount
 * @property string $payout_currency
 * @property string $acquisition_flow
 * @property string $icon_gp
 * @property string $description
 * @property string $name
 * @property double $rate
 * @property double $store_rating
 * @property integer $installs
 * @property string $category
 * @property integer $dl_type
 * @property integer $preload
 * @property integer $start_time
 * @property integer $end_time
 * @property string $nocountries
 * @property string $countries
 */
class BatAll extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bat_all';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('cake');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['camp_id', 'source'], 'required'],
            [['camp_id', 'source', 'mobile_app_id', 'installs', 'dl_type', 'preload', 'start_time', 'end_time'], 'integer'],
            [['payout_amount', 'rate', 'store_rating'], 'number'],
            [['origin_camp_id'], 'string', 'max' => 100],
            [['creatives'], 'string', 'max' => 1024],
            [['imp_url', 'click_url', 'click_callback_url', 'icon_gp', 'name'], 'string', 'max' => 256],
            [['payout_currency'], 'string', 'max' => 4],
            [['acquisition_flow'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 4096],
            [['category'], 'string', 'max' => 160],
            [['nocountries'], 'string', 'max' => 255],
            [['countries'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'camp_id' => '广告ID',
            'origin_camp_id' => '内部广告ID',
            'source' => '渠道',
            'creatives' => 'Creatives',
            'imp_url' => '图片地址',
            'click_url' => '链接地址',
            'click_callback_url' => '回调地址',
            'mobile_app_id' => 'appid',
            'payout_amount' => '价格',
            'payout_currency' => '支付方式',
            'acquisition_flow' => '广告形式',
            'icon_gp' => 'Icon Gp',
            'description' => 'Description',
            'name' => '名称',
            'rate' => 'Rate',
            'store_rating' => 'Store Rating',
            'installs' => 'Installs',
            'category' => '栏目',
            'dl_type' => 'Dl Type',
            'preload' => 'Preload',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'nocountries' => 'Nocountries',
            'countries' => '投放国家',
        ];
    }

    public function getAdByCampId($campId)
    {
        $field = ["camp_id","origin_camp_id","source","mobile_app_id","acquisition_flow","payout_amount","payout_currency","name","category","countries"];
        if(count($campId) < 100)
        {
            $res = $this->find()->where(['camp_id'=>$campId])->select($field)->all();
        }
        else
        {
            $res = $this->getAdByMoreCampIds($campId,$field);
        }
        return $res;
    }

    public function getAdByMoreCampIds($campId,$field){
        $times = ceil(count($campId) / 100);
        $res = array();
        $dataArr = array();

        for($i = 0 ; $i <= $times ; $i++ ){
            $start = $i*100;
            $tempArr =  array_slice($campId,$start,100);
            $res = $this->find()->where(['camp_id'=>$tempArr])->select($field)->all();
            $res = $res ? $res :array();
            $dataArr = array_merge($dataArr ,$res);

        }
        return $dataArr;
    }
}
