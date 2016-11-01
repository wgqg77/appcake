<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "ad_sort_preview".
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
 * @property integer $last_sort
 */
class AdSortPreview extends \yii\db\ActiveRecord
{

    public  $sort_method;
    public  $update_method;
    public  $start_time;
    public  $end_time;
    public  $app_name;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_sort_preview';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['camp_id'], 'required'],
            [['camp_id', 'app_id', 'position', 'source', 'current_sort', 'is_ad', 'next_sort', 'last_sort'], 'integer'],
            [['country'], 'string', 'max' => 2],
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
            'is_ad' => 'Is Ad',
            'next_sort' => 'Next Sort',
            'last_sort' => 'Last Sort',
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
