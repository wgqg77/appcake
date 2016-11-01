<?php

namespace app\modules\appcakethree\models;

use Yii;

/**
 * This is the model class for table "cake_ad_idfa".
 *
 * @property integer $id
 * @property integer $camp_id
 * @property integer $type
 * @property string $idfa
 * @property string $date
 * @property integer $number
 * @property integer $time
 * @property string $country_code
 * @property integer $app_id
 * @property integer $channel
 */
class CakeAdIdfa extends \yii\db\ActiveRecord
{


    public $startTime = '';

    public $endTime = '';

    public $app_name = '';

    public $count = 0;

    public $category = '';

    public $isAd = 0;

    public $aid = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cake_ad_idfa';
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
            [['camp_id'], 'required'],
            [['camp_id', 'type', 'number', 'time', 'app_id', 'channel'], 'integer'],
            [['date','channel'], 'safe'],
            [['idfa'], 'string', 'max' => 50],
            [['country_code'], 'string', 'max' => 2],
            [['camp_id', 'type', 'idfa', 'date'], 'unique', 'targetAttribute' => ['camp_id', 'type', 'idfa', 'date'], 'message' => 'The combination of Camp ID, Type, Idfa and Date has already been taken.'],
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
            'type' => 'Type',
            'idfa' => 'Idfa',
            'date' => 'Date',
            'number' => 'Number',
            'time' => 'Time',
            'country_code' => '国家',
            'app_id' => 'App ID',
            'channel' => 'Channel',
        ];
    }

    public function getData($startTime,$endTime)
    {
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        $data = $this->find()
            ->where("time >= '{$startTime}' and time < '{$endTime}' and camp_id > 0 and channel = 0")
            ->groupBy("date,camp_id,country_code,idfa")
            ->select(["CONCAT(camp_id, '|', country_code) AS pkey","app_id","camp_id","count( * ) AS count"])
            ->asArray()
            ->all();
        $data = $data == true ? $data : array();
        return $data;
    }

    public function getHookData($startTime,$endTime)
    {
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        $data = $this->find()
            ->where("time >= '{$startTime}' and time < '{$endTime}' and camp_id > 0 and channel = 80")
            ->groupBy("date,camp_id,country_code,idfa")
            ->select(["CONCAT(camp_id, '|', country_code) AS pkey","app_id","camp_id","count( * ) AS count"])
            ->asArray()
            ->all();
        $data = $data == true ? $data : array();
        return $data;
    }

}
