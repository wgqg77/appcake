<?php

namespace app\modules\appcakethree\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use app\modules\admin\models\Admin;
use yii\filters\VerbFilter;
use Yii;
use app\modules\admin\controllers\IsloginController;

class IndexController extends IsloginController
{

    public $layout = false;

    public function actionError()
    {
        echo 'Error index/error';
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionWelcome()
    {
        echo 'welcome';die;
        return $this->render('welcome');
    }

    public function actionAutherror()
    {
        return $this->render('autherror');
    }
}
