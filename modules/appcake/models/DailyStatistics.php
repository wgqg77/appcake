<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "daily_statistics".
 *
 * @property integer $id
 * @property string $date
 * @property integer $cake_new_user
 * @property integer $cake_active_user
 * @property string $cake_activation
 * @property integer $cake_download
 * @property integer $hook_new_user
 * @property integer $hook_active_user
 * @property integer $hook_download
 * @property integer $hook_ad_no_repeat
 * @property integer $hook_activation
 * @property integer $all_download
 * @property integer $all_activation
 * @property string $all_income
 * @property string $a_price
 */
class DailyStatistics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'daily_statistics';
    }

    public $startTime;
    public $endTime;
    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['cake_new_user', 'cake_active_user', 'cake_download', 'hook_new_user', 'hook_active_user', 'hook_download', 'hook_ad_no_repeat', 'hook_activation', 'all_download', 'all_activation'], 'integer'],
            [['cake_activation', 'all_income', 'a_price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => '日期',
            'cake_new_user' => 'Cake新增',
            'cake_active_user' => 'Cake活跃',
            'cake_activation' => 'Cake激活',
            'cake_download' => 'Cake下载',
            'cake_active_down' => '单个活跃用户下载个数',
            'hook_new_user' => 'Hook新增',
            'hook_active_user' => 'Hook活跃',
            'hook_download' => 'Hook下载(点击)',
            'hook_ad_no_repeat' => 'Hook去重',
            'hook_activation' => 'Hook激活总数',
            'all_download' => '总下载',
            'all_activation' => '总激活',
            'all_income' => '总收入',
            'a_price' => 'A单价',
        ];
    }

    public function IsInserted($strartTime){
        $strartTime = date("Y-m-d",strtotime($strartTime));
        return $this->find()->where("date='{$strartTime}'")->asArray()->one();
    }
}
