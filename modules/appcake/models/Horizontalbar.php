<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "horizontalbar".
 *
 * @property integer $id
 * @property string $country
 * @property string $category
 * @property integer $app_id
 * @property integer $special_id
 * @property string $img
 * @property integer $appstore
 * @property integer $rank
 * @property integer $time
 */
class Horizontalbar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'horizontalbar';
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
            [['category'], 'string'],
            [['special_id', 'appstore', 'rank', 'time'], 'integer'],
            [['country'], 'string', 'max' => 2],
            [['img'], 'string', 'max' => 255],
            [['country', 'category', 'app_id', 'special_id'], 'unique', 'targetAttribute' => ['country', 'category', 'app_id', 'special_id'], 'message' => 'The combination of Country, Category, App ID and Special ID has already been taken.'],
            [['app_id','special_id', 'appstore', 'rank', 'time'], 'integer','on'=>['ho2']],
            [['country', 'category', 'app_id', 'special_id','img'], 'unique', 'targetAttribute' => ['country', 'category', 'app_id', 'special_id','img'], 'message' => 'The combination of Country, Category, App ID and Special ID has already been taken.','on'=>['ho2']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country' => 'Country',
            'category' => 'Category',
            'app_id' => 'App ID',
            'special_id' => 'Special ID',
            'img' => 'Img',
            'appstore' => 'Appstore',
            'rank' => 'Rank',
            'time' => 'Time',
        ];
    }
}
