<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "app_version_error".
 *
 * @property integer $id
 * @property integer $app_id
 * @property string $version
 * @property integer $download
 * @property string $ext
 * @property string $top_version
 * @property integer $status
 */
class AppVersionError extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_version_error';
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
            [['app_id', 'version'], 'required'],
            [['app_id', 'download'], 'integer'],
            [['version'], 'string', 'max' => 30],
            [['app_id'], 'unique'],
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
            'version' => 'Version',
            'download' => 'Download',
        ];
    }

    public function getAppData()
    {
        return $this->hasOne(AppData::className(), ['app_id' => 'app_id']);
    }
}
