<?php

namespace app\modules\appcakethree\controllers;

use Yii;
use app\modules\appcakethree\models\CakeAdIdfa;
use app\modules\appcakethree\models\search\CakeAdIdfa as CakeAdIdfaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;
/**
 * ActiveController implements the CRUD actions for CakeAdIdfa model.
 */
class ActiveController extends IsloginController
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
        $searchModel = new CakeAdIdfaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


}
