<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "idfa_appid_v4".
 *
 * @property integer $app_id
 * @property string $idfa
 * @property string $date
 * @property string $time
 * @property string $last_time
 * @property integer $camp_id
 * @property string $country
 * @property integer $number
 * @property string $ip
 */
class IdfaAppidV4 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'idfa_appid_v4';
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
            [['app_id', 'idfa', 'date'], 'required'],
            [['app_id', 'camp_id', 'number'], 'integer'],
            [['date', 'time', 'last_time'], 'safe'],
            [['idfa', 'ip'], 'string', 'max' => 50],
            [['country'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'app_id' => 'App ID',
            'idfa' => 'Idfa',
            'date' => 'Date',
            'time' => 'Time',
            'last_time' => 'Last Time',
            'camp_id' => 'Camp ID',
            'country' => 'Country',
            'number' => 'Number',
            'ip' => 'Ip',
        ];
    }

    public function getData($startTime,$endTime)
    {
        $data = $this->find()
            ->where("date >= '{$startTime}' and date < '{$endTime}' and camp_id > 0")
            ->groupBy("camp_id,country")
            ->select(["CONCAT(camp_id, '|', country) AS pkey","app_id","camp_id","count(distinct camp_id,idfa) as count"])
            ->asArray()
            ->all();
        $data = $data == true ? $data : array();
        return $data;
    }

    public function getHookAdNum($strartTime,$endTime){
        $data = $this->find()
            ->where("date >= '{$strartTime}' and date < '{$endTime}' and camp_id > 0")
            ->select(["count(distinct app_id,idfa) count"])
            ->asArray()
            ->one();
        $data = $data == true ? $data : array();
        return $data;
    }
}
