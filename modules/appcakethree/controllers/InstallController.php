<?php

namespace app\modules\appcakethree\controllers;

use Yii;
use app\modules\appcakethree\models\DownloadInstall;
use app\modules\appcakethree\models\search\DownloadInstall as DownloadInstallSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;
/**
 * InstallController implements the CRUD actions for DownloadInstall model.
 */
class InstallController extends IsloginController
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
     * Lists all DownloadInstall models.
     * @return mixed
     */
    public function actionIndex()
    {
        ini_set('memory_limit','1024M');
        $searchModel = new DownloadInstallSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
