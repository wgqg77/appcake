<?php

namespace app\modules\appcake\controllers;

use Yii;
use app\modules\appcake\models\BatAll;
use app\modules\appcake\models\search\BatAll as BatAllSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;
/**
 * AdallController implements the CRUD actions for BatAll model.
 */
class AdallController extends IsloginController
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
     * Lists all BatAll models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BatAllSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BatAll model.
     * @param integer $camp_id
     * @param integer $source
     * @return mixed
     */
    public function actionView($camp_id, $source)
    {
        return $this->render('view', [
            'model' => $this->findModel($camp_id, $source),
        ]);
    }

    /**
     * Creates a new BatAll model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BatAll();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'camp_id' => $model->camp_id, 'source' => $model->source]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BatAll model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $camp_id
     * @param integer $source
     * @return mixed
     */
    public function actionUpdate($camp_id, $source)
    {
        $model = $this->findModel($camp_id, $source);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'camp_id' => $model->camp_id, 'source' => $model->source]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BatAll model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $camp_id
     * @param integer $source
     * @return mixed
     */
    public function actionDelete($camp_id, $source)
    {
        $this->findModel($camp_id, $source)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BatAll model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $camp_id
     * @param integer $source
     * @return BatAll the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($camp_id, $source)
    {
        if (($model = BatAll::findOne(['camp_id' => $camp_id, 'source' => $source])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
