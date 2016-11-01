<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "data_summary_week".
 *
 * @property integer $id
 * @property string $date
 * @property integer $app_id
 * @property string $name
 * @property string $camp_id
 * @property string $country
 * @property string $countries
 * @property string $category
 * @property string $ad_source
 * @property integer $click
 * @property integer $download
 * @property integer $install
 * @property integer $cake_active
 * @property integer $h_click
 * @property integer $h_active
 * @property integer $analog_click
 * @property double $CP
 * @property double $AP
 * @property integer $TA
 * @property double $TP
 * @property double $payout_amount
 * @property double $income
 */
class DataSummaryWeek extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_summary_week';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['app_id', 'click', 'download', 'install', 'cake_active', 'h_click', 'h_active', 'analog_click', 'TA'], 'integer'],
            [['CP', 'AP', 'TP', 'payout_amount', 'income'], 'number'],
            [['name'], 'string', 'max' => 256],
            [['camp_id'], 'string', 'max' => 50],
            [['country'], 'string', 'max' => 2],
            [['countries'], 'string', 'max' => 512],
            [['category'], 'string', 'max' => 255],
            [['ad_source'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'app_id' => 'App ID',
            'name' => 'Name',
            'camp_id' => 'Camp ID',
            'country' => 'Country',
            'countries' => 'Countries',
            'category' => 'Category',
            'ad_source' => 'Ad Source',
            'click' => 'Click',
            'download' => 'Download',
            'install' => 'Install',
            'cake_active' => 'Cake Active',
            'h_click' => 'H Click',
            'h_active' => 'H Active',
            'analog_click' => 'Analog Click',
            'CP' => 'Cp',
            'AP' => 'Ap',
            'TA' => 'Ta',
            'TP' => 'Tp',
            'payout_amount' => 'Payout Amount',
            'income' => 'Income',
        ];
    }

    public function IsInserted($startTime,$isAd=0)
    {
        if($isAd == 0 ){
            return $this->find()->where("date='{$startTime}' and camp_id > 0 ")->asArray()->one();
        }else{
            return $this->find()->where("date='{$startTime}' and camp_id is null")->asArray()->one();
        }

    }

    public function InsertBysql($sql)
    {
        $connection = \Yii::$app->db;
        return $connection->createCommand($sql)->execute();
    }
}
