<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "active_data".
 *
 * @property integer $id
 * @property integer $active_num
 * @property string $total_price
 * @property integer $source
 * @property string $date
 */
class ActiveData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'active_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active_num', 'source'], 'integer'],
            [['total_price'], 'number'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'active_num' => 'Active Num',
            'total_price' => 'Total Price',
            'source' => 'Source',
            'date' => 'Date',
        ];
    }
}
