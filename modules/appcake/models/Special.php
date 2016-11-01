<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "special".
 *
 * @property integer $sid
 * @property string $name
 * @property string $img
 * @property string $description
 * @property string $arr_appid
 * @property string $category
 * @property string $compatible
 * @property integer $addtime
 */
class Special extends \yii\db\ActiveRecord
{
    public $img_5500;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'special';
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
            [['description'], 'string'],
            [['name','arr_appid','img'],'required'],
            [['addtime'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['img', 'arr_appid'], 'string', 'max' => 256],
            [['category'], 'string', 'max' => 50],
            [['compatible'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sid' => 'Sid',
            'name' => 'Name',
            'img' => '图片',
            'description' => '描述',
            'arr_appid' => 'Appid列表',
            'category' => '分类',
            'compatible' => '平台',
            'addtime' => '排序',
        ];
    }
}
