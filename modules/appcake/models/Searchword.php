<?php

namespace app\modules\appcake\models;

use Yii;

/**
 * This is the model class for table "searchword".
 *
 * @property integer $id
 * @property string $name
 * @property integer $number
 * @property integer $addtime
 * @property string $category
 * @property string $compatible
 */
class Searchword extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'searchword';
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
            [['number', 'addtime'], 'integer'],
            [['name'], 'string', 'max' => 60],
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
            'id' => 'ID',
            'name' => 'Name',
            'number' => '初始number',
            'addtime' => '显示排序',
            'category' => '分类',
            'compatible' => '平台',
        ];
    }
}
