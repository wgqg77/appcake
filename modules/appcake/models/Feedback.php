<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property integer $id
 * @property string $uid
 * @property string $title
 * @property string $content
 * @property string $contact
 * @property integer $time
 * @property string $reply
 * @property integer $replytime
 * @property integer $cake_channel
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedback';
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
            [['content', 'reply'], 'string'],
            [['time', 'replytime', 'cake_channel'], 'integer'],
            [['uid'], 'string', 'max' => 100],
            [['title', 'contact'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '用户id',
            'title' => '版本',
            'content' => '内容',
            'contact' => 'IP',
            'time' => 'Time',
            'reply' => '回复内容',
            'replytime' => '回复时间',
            'cake_channel' => 'Cake Channel',
        ];
    }
}
