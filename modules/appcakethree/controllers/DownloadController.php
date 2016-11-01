<?php

namespace app\modules\appcakethree\controllers;

use Yii;
use app\modules\appcakethree\models\DownloadDone;
use app\modules\appcakethree\models\search\DownloadDone as DownloadDoneSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;
/**
 * DownloadController implements the CRUD actions for DownloadDone model.
 */
class DownloadController extends IsloginController
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
     * Lists all DownloadDone models.
     * @return mixed
     */
    public function actionIndex()
    {
        ini_set('memory_limit','1024M');
        $searchModel = new DownloadDoneSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


}
