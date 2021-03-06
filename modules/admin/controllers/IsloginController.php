<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;
use app\modules\admin\models\AuthGroup;
use Yii;

class IsloginController extends Controller
{

    public function beforeAction($action)
    {

        $isLogin = $this->isLogin();
        //if(!$isLogin) return $this->redirect(array('admin/login/login'))->send();
        $currentMethod = '/' . $this->module->id .'/' . Yii::$app->controller->id .'/'. $this->action->id ;

        $this->hasPermissions($currentMethod);

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function isLogin(){

        $key = "_userpass";
        $cook = isset($_COOKIE[$key]) ? $_COOKIE[$key] : null ;
        if($cook){
            $memcached = Yii::$app->cache->get($cook);
            if($memcached){
                $temp = explode('|',$memcached);
                if(isset($temp[4])){
                    $cacheValue = '_' . md5($temp[0].$temp[4]."status");
                    if($cacheValue == $cook){
                        Yii::$app->session['user_name'] = $temp[0];
                        Yii::$app->session['user_id'] = $temp[1];
                        Yii::$app->session['user_group_id'] = $temp[2];
                        Yii::$app->session['group_name'] = $temp[3];
                        return true;
                    }
                }

            }
        }
        setcookie($key,null);
        return  $this->redirect(array('/admin/login/login'))->send();

    }

    public function hasPermissions($currentMethod){
        //无需验证的方法
        $noAuth = Yii::$app->params['notCheck'];
        if(in_array($currentMethod,$noAuth)){
            return true;
        }
        $userId = Yii::$app->session['user_id'];

        //不需要认证的用户id
//        if(in_array($userId,array(1))){
//            return true;
//        }
        if(YII_ENV){
            return true;
        }

        $authGropuModle = new AuthGroup();

        $userGroup = $authGropuModle->getUserRues();

        $userGroupMethod = array_column($userGroup,'name');

        if(in_array($currentMethod,$userGroupMethod)){
            return $userGroup;
        }else{
            return  $this->redirect(array('/admin/index/autherror','method'=>$currentMethod));
        }
    }

    /**
     * 成功跳转提示
     * 使用方法: return $this->success(['/admin/admin/welcome'],2);
     */
    public function success($url=null ,$sec = 3){

        if(!empty($url) && $url !== 1){
            $url= \yii\helpers\Url::toRoute($url);
        }else if($url == 1){
            $url = '/' . $this->module->id .'/' . Yii::$app->controller->id  .'/index' ;
            $url= \yii\helpers\Url::toRoute($url);
        }else{
            $url = null;
        }

        return $this->renderPartial('@app/modules/admin/views/tpl/jump',['gotoUrl'=>$url,'sec'=>$sec]);

    }

    /**
     * 失败跳转提示
     * 使用方法: return $this->error('错误提示语',2);
     */
    public function error($msg= 'error',$sec = 3){
        return $this->renderPartial('@app/modules/admin/views/tpl/jump',['errorMessage'=>$msg,'sec'=>$sec]);
    }



}
