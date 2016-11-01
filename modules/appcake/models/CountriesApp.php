<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "countries_app".
 *
 * @property string $country
 * @property integer $app_id
 * @property integer $camp_id
 * @property integer $source
 * @property string $click_url
 * @property double $payout_amount
 * @property double $hook_rate
 */
class CountriesApp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'countries_app';
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
            [['country', 'app_id', 'camp_id', 'source'], 'required'],
            [['app_id', 'camp_id', 'source'], 'integer'],
            [['payout_amount', 'hook_rate'], 'number'],
            [['country'], 'string', 'max' => 2],
            [['click_url'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country' => 'Country',
            'app_id' => 'App ID',
            'camp_id' => 'Camp ID',
            'source' => 'Source',
            'click_url' => 'Click Url',
            'payout_amount' => 'Payout Amount',
            'hook_rate' => 'Hook Rate',
        ];
    }
}
