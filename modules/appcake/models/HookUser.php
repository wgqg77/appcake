<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "hookUser".
 *
 * @property string $date
 * @property integer $imei
 * @property string $idfa
 * @property string $idfv
 * @property string $serial_number
 * @property string $wifi_address
 * @property string $os_version
 * @property string $product_type
 * @property string $hook_from
 * @property string $ip
 * @property string $user_agent
 * @property string $country
 * @property integer $cake_channel
 */
class HookUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hookUser';
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
            [['date', 'imei', 'idfa', 'idfv', 'serial_number', 'wifi_address', 'os_version', 'product_type', 'hook_from', 'ip', 'user_agent', 'country'], 'required'],
            [['date'], 'safe'],
            [['imei', 'cake_channel'], 'integer'],
            [['idfa', 'idfv', 'serial_number', 'wifi_address', 'os_version', 'product_type', 'hook_from', 'ip', 'user_agent'], 'string', 'max' => 255],
            [['country'], 'string', 'max' => 2],
            [['date', 'wifi_address', 'hook_from'], 'unique', 'targetAttribute' => ['date', 'wifi_address', 'hook_from'], 'message' => 'The combination of Date, Wifi Address and Hook From has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'date' => 'Date',
            'imei' => 'Imei',
            'idfa' => 'Idfa',
            'idfv' => 'Idfv',
            'serial_number' => 'Serial Number',
            'wifi_address' => 'Wifi Address',
            'os_version' => 'Os Version',
            'product_type' => 'Product Type',
            'hook_from' => 'Hook From',
            'ip' => 'Ip',
            'user_agent' => 'User Agent',
            'country' => 'Country',
            'cake_channel' => 'Cake Channel',
        ];
    }

    public function getActiveuser($strartTime){
        $strartTime = date('Y-m-d',strtotime($strartTime));
        return $this->find()->where("date = $strartTime ")->select("count(*) as count")->asArray()->one();
    }
}
