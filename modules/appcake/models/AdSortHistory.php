<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "ad_sort_history".
 *
 * @property integer $id
 * @property integer $camp_id
 * @property integer $app_id
 * @property string $country
 * @property integer $position
 * @property integer $source
 * @property integer $current_sort
 * @property integer $is_ad
 * @property integer $click
 * @property integer $down
 * @property integer $install
 * @property double $price
 * @property integer $ad_type
 * @property string $creat_time
 * @property string $category
 * @property string $device
 * @property integer $ad_sort_id
 */
class AdSortHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_sort_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['camp_id'], 'required'],
            [['camp_id', 'app_id', 'position', 'source', 'current_sort', 'is_ad', 'click', 'down', 'install', 'ad_type', 'ad_sort_id'], 'integer'],
            [['price'], 'number'],
            [['creat_time'], 'safe'],
            [['country'], 'string', 'max' => 2],
            [['category'], 'string', 'max' => 160],
            [['device'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'camp_id' => 'Camp ID',
            'app_id' => 'App ID',
            'country' => 'Country',
            'position' => 'Position',
            'source' => 'Source',
            'current_sort' => 'Current Sort',
            'is_ad' => '是否广告',
            'click' => '点击量',
            'down' => '下载量',
            'install' => '安装量',
            'price' => '单价',
            'ad_type' => '广告形式',
            'creat_time' => 'Creat Time',
            'category' => '栏目',
            'device' => '包',
            'ad_sort_id' => 'Ad Sort ID',
        ];
    }
}
