<?php

namespace app\modules\appcake\controllers;

use Yii;
use app\modules\appcake\models\DataSummaryPosition;
use app\modules\appcake\models\search\DataSummaryPosition as DataSummaryPositionSearch;
use yii\db\ActiveQuery;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;
/**
 * ClickController implements the CRUD actions for DownloadWeek model.
 */
class PositionController extends IsloginController
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
     * Lists all DownloadWeek models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DataSummaryPositionSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $cloneQuery = clone $dataProvider->query;

        //$resquest = Yii::$app->request;
        $startTime = isset($_GET['startTime']) ? $_GET['startTime'] : date('Y-m-d',strtotime('-1 day'));
        $endTime = isset($_GET['endTime']) ?  $_GET['endTime'] :date('Y-m-d',time());
        $total =  $cloneQuery->select('sum(click) as click, sum(download) as download, sum(install) as install ')
            ->where("date >= '{$startTime}' and date < '{$endTime}' ")
            ->asArray()->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total' => $total
        ]);
    }

    /**
     * Displays a single DownloadWeek model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DownloadWeek model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DownloadWeek();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DownloadWeek model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DownloadWeek model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DownloadWeek model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DownloadWeek the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DownloadWeek::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
