<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "idfa_campid_v3".
 *
 * @property integer $id
 * @property integer $app_id
 * @property string $date
 * @property integer $time
 * @property string $idfa
 * @property string $ip
 * @property integer $camp_id
 * @property string $country
 * @property string $status
 * @property string $click_url
 * @property integer $app_source
 * @property integer $channel
 * @property integer $success_number
 * @property integer $error_number
 * @property integer $nojump_number
 * @property string $urls
 */
class IdfaCampidV3 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'idfa_campid_v3';
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
            [['app_id', 'time', 'camp_id', 'app_source', 'channel', 'success_number', 'error_number', 'nojump_number'], 'integer'],
            [['date'], 'safe'],
            [['urls'], 'string'],
            [['idfa'], 'string', 'max' => 50],
            [['ip'], 'string', 'max' => 45],
            [['country', 'status'], 'string', 'max' => 2],
            [['click_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'date' => 'Date',
            'time' => 'Time',
            'idfa' => 'Idfa',
            'ip' => 'Ip',
            'camp_id' => 'Camp ID',
            'country' => 'Country',
            'status' => 'Status',
            'click_url' => 'Click Url',
            'app_source' => 'App Source',
            'channel' => 'Channel',
            'success_number' => 'Success Number',
            'error_number' => 'Error Number',
            'nojump_number' => 'Nojump Number',
            'urls' => 'Urls',
        ];
    }

    public function getByDate($date){
        return $this->find()->where("date = '{$date}' ")->asArray()->all();
    }

}
