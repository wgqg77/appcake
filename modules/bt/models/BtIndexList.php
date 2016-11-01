<?php

namespace app\modules\bt\models;

use Yii;

/**
 * This is the model class for table "bt_index_list".
 *
 * @property integer $id
 * @property string $url
 * @property string $ico_url
 * @property string $title
 * @property string $describe
 * @property integer $is_show
 * @property integer $sort
 */
class BtIndexList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bt_index_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_show', 'sort'], 'integer'],
            [['url', 'ico_url', 'describe'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 100],
            [['title','url','ico_url','describe','sort'],'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'ico_url' => 'Ico Url',
            'title' => '标题',
            'describe' => '简介',
            'is_show' => '是否显示',
            'sort' => '排序',
        ];
    }
}
