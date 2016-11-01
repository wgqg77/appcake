<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "user_hook_new".
 *
 * @property string $mac_address
 * @property integer $number
 * @property string $time
 * @property string $last_time
 * @property integer $cake_channel
 * @property string $imei
 * @property string $udid
 * @property string $idfa
 * @property string $idfv
 * @property string $serial_number
 * @property string $os_version
 * @property string $product_type
 * @property string $ip
 * @property string $ua
 * @property string $platform
 * @property string $language
 * @property string $wifi_address
 * @property string $iccid
 * @property string $bluetoothAddress
 * @property string $dieid
 * @property string $carrierName
 * @property string $mobileNetworkCode
 * @property string $mobileCountryCode
 * @property string $isoCountryCode
 * @property string $HWModelStr
 * @property string $buildVersion
 */
class UserHookNew extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_hook_new';
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
            [['mac_address'], 'required'],
            [['number', 'cake_channel'], 'integer'],
            [['time', 'last_time'], 'safe'],
            [['mac_address', 'imei', 'serial_number', 'product_type', 'platform', 'language', 'wifi_address', 'isoCountryCode', 'HWModelStr', 'buildVersion'], 'string', 'max' => 20],
            [['udid', 'idfa', 'idfv', 'ip', 'carrierName'], 'string', 'max' => 50],
            [['os_version'], 'string', 'max' => 10],
            [['ua'], 'string', 'max' => 400],
            [['iccid', 'bluetoothAddress', 'dieid', 'mobileNetworkCode', 'mobileCountryCode'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mac_address' => 'Mac Address',
            'number' => 'Number',
            'time' => 'Time',
            'last_time' => 'Last Time',
            'cake_channel' => 'Cake Channel',
            'imei' => 'Imei',
            'udid' => 'Udid',
            'idfa' => 'Idfa',
            'idfv' => 'Idfv',
            'serial_number' => 'Serial Number',
            'os_version' => 'Os Version',
            'product_type' => 'Product Type',
            'ip' => 'Ip',
            'ua' => 'Ua',
            'platform' => 'Platform',
            'language' => 'Language',
            'wifi_address' => 'Wifi Address',
            'iccid' => 'Iccid',
            'bluetoothAddress' => 'Bluetooth Address',
            'dieid' => 'Dieid',
            'carrierName' => 'Carrier Name',
            'mobileNetworkCode' => 'Mobile Network Code',
            'mobileCountryCode' => 'Mobile Country Code',
            'isoCountryCode' => 'Iso Country Code',
            'HWModelStr' => 'Hwmodel Str',
            'buildVersion' => 'Build Version',
        ];
    }

    public function getNewUser($strartTime,$endTime){
        $res = $this->find()->where("time >= '{$strartTime}' and time < '{$endTime}' ")->select("count(*) as count")->asArray()->one();
        if($res){
            return $res['count'];
        }else{
            return 0;
        }
    }
}
