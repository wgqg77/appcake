<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "userinfo".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $info
 * @property string $device_id
 * @property integer $compatible
 * @property string $language
 * @property string $idfa
 * @property string $mac
 * @property string $os_version
 * @property string $device
 * @property string $totalMemeory
 * @property string $freeMemeory
 * @property string $ip
 * @property integer $time
 * @property integer $cake_channel
 */
class Userinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userinfo';
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
            [['uid', 'compatible', 'time', 'cake_channel'], 'integer'],
            [['info'], 'required'],
            [['info'], 'string'],
            [['device_id', 'idfa'], 'string', 'max' => 50],
            [['language'], 'string', 'max' => 30],
            [['mac', 'device', 'totalMemeory', 'freeMemeory', 'ip'], 'string', 'max' => 20],
            [['os_version'], 'string', 'max' => 10],
            [['idfa'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'info' => 'Info',
            'device_id' => 'Device ID',
            'compatible' => 'Compatible',
            'language' => 'Language',
            'idfa' => 'Idfa',
            'mac' => 'Mac',
            'os_version' => 'Os Version',
            'device' => 'Device',
            'totalMemeory' => 'Total Memeory',
            'freeMemeory' => 'Free Memeory',
            'ip' => 'Ip',
            'time' => 'Time',
            'cake_channel' => 'Cake Channel',
        ];
    }

    public function getNewUserTotal($strartTime,$endTime){
        $strartTime = strtotime($strartTime);
        $endTime = strtotime($endTime);
        return $this->find()->where("time >= $strartTime and time <$endTime")->select("count(*) as count")->asArray()->one();
    }
}
