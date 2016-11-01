<?php

namespace app\modules\admin\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use app\modules\admin\models\Admin;
use yii\filters\VerbFilter;
use Yii;

class LoginController extends Controller
{

    public $layout = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    public function actionLogin()
    {
        $key = "_userpass";
        $cook = isset($_COOKIE[$key]) ? $_COOKIE[$key] : null ;
        if($cook){
            $memcached = Yii::$app->cache->get($cook);
            if($memcached){
                return $this->redirect(array('/admin/index/index'));
            }
        }
        $model = new Admin();
        if($model->load(Yii::$app->request->post())){

            if($model->login())
            {
                $this->redirect(array('/admin/index/index'));
            }
            else
            {
                $error = array_keys($model->errors)[0];
                return $this->renderPartial('login',[
                    'model' => $model,
                    'error' => $error
                ]);
            }
        }
        else
        {
            $error = '';
            return $this->renderPartial('login',[
                'model' => $model,
                'error' => $error
            ]);
        }

    }

    public function actionLoginout()
    {
        Yii::$app->getSession()->destroy();
        Yii::$app->session['user_name'] = null;
        Yii::$app->session['user_id'] = null;
        setcookie("PHPSESSID",null);
        setcookie("_csrf",null);
        $cook = isset($_COOKIE['_userpass']) ? $_COOKIE['_userpass'] : null ;
        if($cook){
            Yii::$app->cache->set($cook,null);
        }
        setcookie("_userpass",null);
        $this->redirect(array('login/login'));
    }

}
