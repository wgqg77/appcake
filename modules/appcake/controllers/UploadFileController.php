<?php

namespace app\modules\appcake\controllers;

use Yii;
use app\modules\appcake\models\CakeAdIdfa;
use app\modules\appcake\models\search\CakeAdIdfa as CakeAdIdfaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;
/**
 * ActiveController implements the CRUD actions for CakeAdIdfa model.
 */
class UploadFileController extends IsloginController
{


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CakeAdIdfa models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionNew()
    {
        return $this->render('new');
    }

}
