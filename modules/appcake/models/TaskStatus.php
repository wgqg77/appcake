<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "task_status".
 *
 * @property string $name
 * @property string $value
 */
class TaskStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'value'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'value' => 'Value',
        ];
    }

    public function getTaskValue($selectName)
    {
        $temp =  $this->find()->where(["name" => $selectName])->select('value')->asArray()->one();
        if(isset($temp['value'])){
            return $temp['value'];
        }else{
            return null;
        }
    }

    public function getAdSortStatus()
    {
        $temp =  $this->find()
            ->where("name = 'is_init_ad_sort'
            or name = 'ad_sort_current_table'
            or name = 'ad_sort_next_table'
            or name = 'ad_sort_interval'
            or name = 'ad_sort_update_lock'
            or name = 'ad_sort_is_post'
            or name = 'ad_sort_next_time'
            or name = 'is_preview'
            ")
            ->asArray()->all();
        if(!empty($temp)){
            $temp = array_column($temp,'value','name');
            return $temp;
        }else{
            return null;
        }
    }
}
