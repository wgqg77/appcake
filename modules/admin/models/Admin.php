<?php

namespace app\modules\admin\models;

use Yii;
use app\modules\admin\models\AuthGroup;
/**
 * This is the model class for table "admin".
 *
 * @property integer $uid
 * @property string $user_name
 * @property string $passwd
 * @property string $sex
 * @property string $email
 * @property string $phone
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $login_time
 * @property string $login_ip
 * @property integer $safe_question
 * @property string $safe_answer
 * @property string $face
 * @property integer $scores
 * @property integer $leave
 * @property integer $coin
 * @property integer $is_lock
 * @property integer $is_admin
 * @property string $nick_name
 * @property string $collect
 * @property integer $login_count
 */
class Admin extends \yii\db\ActiveRecord
{

    public $rememberMe = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_name','passwd'],'required'],
            ['user_name', 'unique','message'=> '用户名重复','on'=>'create'],
            [['user_name'], 'string', 'max' => 50 , 'min' => 2 ],
            [['passwd'], 'string', 'max' => 32 , 'min' => 2 ],
            [['group'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => '用户ID',
            'user_name' => '用户名',
            'passwd' => '密码',
            'sex' => 'Sex',
            'email' => 'Email',
            'phone' => 'Phone',
            'create_time' => '创建时间',
            'create_ip' => '创建IP',
            'login_time' => '最近登录时间',
            'login_ip' => '最近登录IP',
            'safe_question' => 'Safe Question',
            'safe_answer' => 'Safe Answer',
            'face' => 'Face',
            'scores' => 'Scores',
            'group' => '用户组',
            'coin' => 'Coin',
            'is_lock' => '锁定',
            'is_admin' => 'Is Admin',
            'nick_name' => 'Nick Name',
            'collect' => 'Collect',
            'login_count' => '登录次数',
            'rememberMe'  => '记住密码',
            'group_id'    => '用户组ID',
        ];
    }

    public function login()
    {
        if($this->validate()){
            $user = $this->getUserByUserName($this->user_name);
            if($user){
                if($user->passwd == md5($this->passwd)){
                    //查看用户所属组状态是否停用
                    $group = $this->getUserGroup($user->group);
                    if($group == false || $group->status == 1){
                        return $this->addError('所属用户组已被禁止使用');
                    }
                    else
                    {
                        Yii::$app->session['group_name'] = $group->title;
                    }

                    Yii::$app->session['user_name'] = $user->user_name;
                    Yii::$app->session['user_id'] = $user->uid;
                    Yii::$app->session['user_group_id'] = $user->group;
                    $user->login_time = time();
                    $user->login_ip = $_SERVER["REMOTE_ADDR"];
                    $user->login_count ++;
                    $user->save();

                    $Mstatus = '_'.md5($user->user_name . $user->passwd ."status");
                    $mValue = $user->user_name ."|".$user->uid ."|". $user->group ."|". $group->title ."|" . $user->passwd;
                    $key = "_userpass";
                    if($this->rememberMe){
                        $weekTime = time()+3600*24*7;
                        setcookie($key,$Mstatus, $weekTime);
                        Yii::$app->cache->set($Mstatus,$mValue,$weekTime);
                    }else{
                        setcookie($key,$Mstatus);
                        Yii::$app->cache->set($Mstatus,$mValue);
                    }
                    return true;
                }
                else
                {
                    return $this->addError('用户名或密码错误'); //密码错误
                }
            }
            else
            {
                return $this->addError('用户名或密码错误'); //用户名不存在
            }
        }
    }

    public function getUserByUserName($userName)
    {
        return $this->find()->where(['user_name' => $userName])->one();
    }

    public function getUserGroup($id)
    {
        return AuthGroup::find()->where("id=$id")->one();
    }

}
