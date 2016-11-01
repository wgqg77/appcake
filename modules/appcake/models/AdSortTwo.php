<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "ad_sort_one".
 *
 * @property integer $id
 * @property integer $camp_id
 * @property integer $app_id
 * @property string $country
 * @property integer $position
 * @property integer $source
 * @property integer $current_sort
 * @property integer $is_ad
 * @property integer $next_sort
 */
class AdSortTwo extends \yii\db\ActiveRecord
{

    //修改记录字段
    public  $sort_method;
    public  $update_method;
    public  $start_time;
    public  $end_time;
    public  $app_name;
    //public  $countryList;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_sort_two';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country','next_sort'], 'required'],
            [['camp_id', 'app_id', 'position', 'source', 'current_sort', 'is_ad', 'next_sort'], 'integer'],
            [['is_ad'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'camp_id' => '广告ID',
            'app_id' => 'AppID',
            'country' => '国家',
            'position' => '位置',
            'source' => '渠道',
            'current_sort' => '当前排序位置',
            'is_ad' => '是否广告',
            'next_sort' => '修改排序位置',
            'sort_method' => '排序修改方式',
            'update_method'  => '生效时间',
            'start_time'  => '开始时间',
            'end_time' => '结束时间',
            'last_sort' => '前排序',


            'click' => '点击量',
            'down'  => '下载量',
            'install' => '安装量',

        ];
    }

    public function getAdSortHistory()
    {
        return $this->hasOne(AdSortHistory::className(), ['ad_sort_id' => 'id']);
    }

    public function getAppData()
    {
        return $this->hasOne(AppData::className(), ['app_id' => 'app_id']);
    }

    public function getBatAll()
    {
        return $this->hasOne(BatAll::className(), ['camp_id' => 'camp_id']);
    }
}
