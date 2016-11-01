<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "userinfo_v2".
 *
 * @property string $date
 * @property string $mac_address
 * @property integer $number
 * @property string $imei
 * @property string $idfa
 * @property string $idfv
 * @property string $serial_number
 * @property string $os_version
 * @property string $product_type
 * @property string $ip
 * @property string $ua
 * @property string $time
 * @property string $last_time
 * @property integer $cake_channel
 */
class UserinfoV2 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userinfo_v2';
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
            [['date', 'mac_address'], 'required'],
            [['date', 'time', 'last_time'], 'safe'],
            [['number', 'cake_channel'], 'integer'],
            [['mac_address', 'imei', 'serial_number', 'product_type'], 'string', 'max' => 20],
            [['idfa', 'idfv', 'ip'], 'string', 'max' => 50],
            [['os_version'], 'string', 'max' => 10],
            [['ua'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'date' => 'Date',
            'mac_address' => 'Mac Address',
            'number' => 'Number',
            'imei' => 'Imei',
            'idfa' => 'Idfa',
            'idfv' => 'Idfv',
            'serial_number' => 'Serial Number',
            'os_version' => 'Os Version',
            'product_type' => 'Product Type',
            'ip' => 'Ip',
            'ua' => 'Ua',
            'time' => 'Time',
            'last_time' => 'Last Time',
            'cake_channel' => 'Cake Channel',
        ];
    }
}
