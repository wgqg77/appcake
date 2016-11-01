<?php

namespace app\modules\admin\models;
use app\modules\admin\models\Admin;
use app\modules\admin\models\AuthRule;
use Yii;

/**
 * This is the model class for table "auth_group".
 *
 * @property integer $id
 * @property string $title
 * @property integer $status
 * @property string $rules
 */
class AuthGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', ], 'required'],
            [['status'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['rules'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '用户组',
            'status' => '状态',
            'rules' => '权限方法',
        ];
    }

    public function getUserGroup()
    {
        return $this->find()->all();
    }

    public function getUserRues(){
        $gid = Yii::$app->session['user_group_id'];
        if(!$gid) return array();
        $userRules = $this->find()->where("id={$gid}")->asArray()->one();//var_dump($userRules);die;
        $authModel = new AuthRule();
        $userRules = $authModel->getRulesInId($userRules['rules']);

        return $userRules;
    }

    public function getGroupRues($gid){
        if(!$gid) return array();
        $userRules = $this->find()->where("id={$gid}")->asArray()->one();//var_dump($userRules);die;
        $authModel = new AuthRule();

        if($userRules && !empty(str_replace(" ","",$userRules['rules'])) ){
            $userRules = $authModel->getRulesInId($userRules['rules']);
        }else{
            return array();
        }


        return $userRules;
    }

    public function getUserRulesId(){
        $gid = Yii::$app->session['user_group_id'];
        if(!$gid) return array();
        $userRules = $this->find()->where("id={$gid}")->asArray()->one();
        $userRules = explode(',',$userRules['rules']);
        return $userRules;
    }

    public function getGroupNameById($Id)
    {
        $group = $this->find()->where("id={$Id}")->one();
        return $group->title;
    }
}
