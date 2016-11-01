<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "bat".
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
 * @property string $appstore_version
 * @property integer $is_update
 * @property integer $start_time
 * @property integer $end_time
 * @property string $nocountries
 * @property string $countries
 */
class Bat extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bat';
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
            [['camp_id', 'source', 'mobile_app_id', 'installs', 'dl_type', 'preload', 'is_update', 'start_time', 'end_time'], 'integer'],
            [['payout_amount', 'rate', 'store_rating'], 'number'],
            [['origin_camp_id'], 'string', 'max' => 100],
            [['creatives'], 'string', 'max' => 1024],
            [['imp_url', 'click_url', 'click_callback_url', 'icon_gp', 'name'], 'string', 'max' => 256],
            [['payout_currency'], 'string', 'max' => 4],
            [['acquisition_flow'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 4096],
            [['category'], 'string', 'max' => 160],
            [['appstore_version'], 'string', 'max' => 30],
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
            'camp_id' => 'Camp ID',
            'origin_camp_id' => 'Origin Camp ID',
            'source' => 'Source',
            'creatives' => 'Creatives',
            'imp_url' => 'Imp Url',
            'click_url' => 'Click Url',
            'click_callback_url' => 'Click Callback Url',
            'mobile_app_id' => 'Mobile App ID',
            'payout_amount' => 'Payout Amount',
            'payout_currency' => 'Payout Currency',
            'acquisition_flow' => 'Acquisition Flow',
            'icon_gp' => 'Icon Gp',
            'description' => 'Description',
            'name' => 'Name',
            'rate' => 'Rate',
            'store_rating' => 'Store Rating',
            'installs' => 'Installs',
            'category' => 'Category',
            'dl_type' => 'Dl Type',
            'preload' => 'Preload',
            'appstore_version' => 'Appstore Version',
            'is_update' => 'Is Update',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'nocountries' => 'Nocountries',
            'countries' => 'Countries',
        ];
    }

    public function getAppData()
    {
        return $this->hasOne(AppData::className(), ['app_id' => 'mobile_app_id']);
    }
}
