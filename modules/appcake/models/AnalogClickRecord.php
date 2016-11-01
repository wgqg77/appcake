<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "analog_click_record".
 *
 * @property integer $id
 * @property string $idfa
 * @property string $country
 * @property integer $time
 * @property integer $camp_id
 * @property integer $type
 * @property integer $analog_click
 * @property integer $success_number
 * @property integer $error_number
 */
class AnalogClickRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'analog_click_record';
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
            [['country'], 'required'],
            [['time', 'camp_id', 'type', 'analog_click', 'success_number', 'error_number'], 'integer'],
            [['idfa'], 'string', 'max' => 50],
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
            'idfa' => 'Idfa',
            'country' => 'Country',
            'time' => 'Time',
            'camp_id' => 'Camp ID',
            'type' => 'Type',
            'analog_click' => 'Analog Click',
            'success_number' => 'Success Number',
            'error_number' => 'Error Number',
        ];
    }

    public function getData($startTime,$endTime)
    {
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        $data = $this->find()
            ->where("time >= {$startTime} and time < {$endTime} and camp_id > 0")
            ->groupBy("camp_id,country")
            ->select(["CONCAT(camp_id, '|', country) AS pkey","camp_id","sum(success_number)  AS count"])
            ->asArray()
            ->all();
        $data = $data == true ? $data : array();
        return $data;
    }
}
