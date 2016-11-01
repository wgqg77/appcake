<?php

namespace app\modules\appcake\controllers;

use Yii;
use app\modules\appcake\models\DownloadDone;
use app\modules\appcake\models\search\DownloadDone as DownloadDoneSearch;
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
        //设置页数
        //$dataProvider->pagination->defaultPageSize =10;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


}
