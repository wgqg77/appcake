<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "required".
 *
 * @property integer $app_id
 * @property string $app_name
 * @property string $description
 * @property integer $rank
 * @property string $category
 * @property string $compatible
 * @property integer $frank
 * @property string $size
 * @property string $price
 */
class Required extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'required';
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
            [['app_id'], 'required'],
            [['app_id', 'rank', 'frank'], 'integer'],
            [['app_name', 'description'], 'string', 'max' => 128],
            [['category', 'size'], 'string', 'max' => 50],
            [['compatible'], 'string', 'max' => 10],
            [['price'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_id' => 'App ID',
            'app_name' => 'App Name',
            'description' => 'Description',
            'rank' => '次排序',
            'category' => 'Category',
            'compatible' => 'Compatible',
            'frank' => '售卖位置 主排序',
            'size' => 'Size',
            'price' => 'Price',
        ];
    }
}
