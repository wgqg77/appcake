<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "active_data_record".
 *
 * @property integer $id
 * @property integer $app_id
 * @property integer $camp_id
 * @property string $app_name
 * @property integer $active_num
 * @property string $price
 * @property string $total_price
 * @property integer $source
 * @property string $date
 */
class ActiveDataRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'active_data_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'camp_id', 'active_num', 'source'], 'integer'],
            [['price', 'total_price'], 'number'],
            [['date'], 'safe'],
            [['app_name'], 'string', 'max' => 160],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'camp_id' => 'Camp ID',
            'app_name' => 'App Name',
            'active_num' => 'Active Num',
            'price' => 'Price',
            'total_price' => 'Total Price',
            'source' => 'Source',
            'date' => 'Date',
        ];
    }
}
