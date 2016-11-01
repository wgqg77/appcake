<?php

namespace app\modules\bt\models;

use Yii;

/**
 * This is the model class for table "bt_app_version".
 *
 * @property integer $id
 * @property string $name
 * @property string $version
 * @property string $download_url
 * @property string $download_url2
 * @property string $descript
 * @property string $update_descript
 * @property string $md5
 * @property integer $update_status
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $file_type
 */
class BtAppVersion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_app_version';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descript', 'update_descript'], 'string'],
            [['update_status', 'create_time', 'update_time', 'file_type'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['version'], 'string', 'max' => 30],
            [['download_url', 'download_url2'], 'string', 'max' => 255],
            [['md5'], 'string', 'max' => 32],
            [['name','version','download_url'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'version' => '版本',
            'download_url' => '下载地址',
            'download_url2' => '下载地址2',
            'descript' => '应用介绍',
            'update_descript' => '更新日志',
            'md5' => 'Md5',
            'update_status' => '强制更新',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'file_type' => '文件类型',
        ];
    }
}
