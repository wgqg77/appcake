<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "bannerlist".
 *
 * @property integer $app_id
 * @property string $app_name
 * @property integer $appstore
 * @property integer $rank
 * @property string $category
 * @property string $compatible
 * @property integer $begintime
 * @property integer $endtime
 * @property string $img
 */
class Bannerlist extends \yii\db\ActiveRecord
{
    public $img_5500;
    public $rank_sort;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bannerlist';
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
            [['app_id', 'appstore', 'rank'], 'integer'],
            [['endtime','begintime','img_5500'],'safe'],
            [['app_name'], 'string', 'max' => 128],
            [['category'], 'string', 'max' => 50],
            [['compatible'], 'string', 'max' => 10],
            [['img'], 'string', 'max' => 255],
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
            'appstore' => 'Appstore',
            'rank' => '排序',
            'category' => '分类',
            'compatible' => '平台',
            'begintime' => 'Begintime',
            'endtime' => 'Endtime',
            'img' => '图片',
            'img_5500' => '5.5.0.0版本以上图片'
        ];
    }
}
