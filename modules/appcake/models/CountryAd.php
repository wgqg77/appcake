<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "country_ad".
 *
 * @property integer $ad_id
 * @property string $country
 */
class CountryAd extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country_ad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ad_id', 'country'], 'required'],
            [['ad_id'], 'integer'],
            [['country'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ad_id' => 'Ad ID',
            'country' => 'Country',
        ];
    }


}
