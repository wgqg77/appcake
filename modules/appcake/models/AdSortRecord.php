<?php

namespace app\modules\appcake\models;

use Yii;


class AdSortRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_sort_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort_id', 'position', 'current_sort', 'next_sort', 'sort_method', 'update_method'], 'integer'],
            [['create_time', 'start_time','end_time'], 'safe'],
            [['country'], 'string', 'max' => 2],
            [['camp_id'],'default'=> 0],
            [['is_updated'], 'string', 'max' => 45],
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
            'sort_id' => 'sort ID',
            'camp_id' => 'camp_id',
            'app_id' => 'app_id',
            'is_ad' => '是否广告',
            'position' => 'Position',
            'current_sort' => 'Current Sort',
            'next_sort' => 'Next Sort',
            'sort_method' => 'Sort Method',
            'update_method' => 'Update Method',
            'is_updated' => 'Is Updated',
            'create_time' => 'Create Time',
            'start_time' => 'Start Time',
            'end_time' => 'end_time'
        ];
    }

    public function getrecordToUpdate(){
        return $this->find()->where("is_updated = 0 or is_updated = 2")->asArray()->all();
    }

    public function getrecordBeforNextTime($nextTime){
        return $this->find()->where("is_updated = 0 or is_updated = 2 and  start_time <=  '{$nextTime}'  ")->asArray()->all();
    }
}
