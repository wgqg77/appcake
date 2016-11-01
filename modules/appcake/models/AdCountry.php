<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "ad_country".
 *
 * @property integer $id
 * @property string $country_name
 * @property string $country_code
 * @property string $create_time
 * @property integer $status
 * @property integer $day_active_number
 */
class AdCountry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time'], 'safe'],
            [['status', 'day_active_number'], 'integer'],
            [['country_name'], 'string', 'max' => 80],
            [['country_code'], 'string', 'max' => 3],
            [['country_code'], 'unique'],
            [['country_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country_name' => '国家名称',
            'country_code' => '国家代码',
            'create_time' => '添加时间',
            'status' => '状态',
            'day_active_number' => '日活量',
        ];
    }

    public function getOnlineCountries()
    {
        $country = $this->find()->where("status = 1")->select("country_code")->asArray()->all();
        return $country;
    }
}
