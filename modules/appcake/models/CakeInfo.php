<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "cake_info".
 *
 * @property string $name
 * @property string $version
 * @property string $download_url
 * @property string $message
 * @property integer $updateinfo
 * @property string $download_url2
 */
class CakeInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cake_info';
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
            [['message'], 'required'],
            [['message'], 'string'],
            [['updateinfo'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['version'], 'string', 'max' => 30],
            [['download_url', 'download_url2'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '包类型',
            'version' => 'Version',
            'download_url' => 'Download Url',
            'message' => '版本描述',
            'updateinfo' => '是否强制更新',
            'download_url2' => 'Download Url2',
            'md5' => 'MD5值',
        ];
    }
}
