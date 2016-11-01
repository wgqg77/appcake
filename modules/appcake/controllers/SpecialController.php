<?php

namespace app\modules\appcake\controllers;

use Yii;
use app\modules\appcake\models\Special;
use app\modules\appcake\models\search\Special as SpecialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;
/**
 * SpecialController implements the CRUD actions for Special model.
 */
class SpecialController extends IsloginController
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
     * Lists all Special models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SpecialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Special model.
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
     * Creates a new Special model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Special();

        if ($model->load(Yii::$app->request->post()) ) {
            $imgArray = array('0' => $model->img, '5.5.0.0' => $model->img_5500 ?:"");
            $model->img = json_encode($imgArray);
            $model->arr_appid = json_encode(explode('/',$model->arr_appid));
            $model->addtime = $model->addtime . substr(time(), -8);
            if($model->save()){
                return $this->success(1);
            }else{
                return $this->error('数据写入失败');
            }
        } else {

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Special model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            $imgArray = array('0' => $model->img, '5.5.0.0' => $model->img_5500 ?:"");
            $model->img = json_encode($imgArray);
            $model->arr_appid = json_encode(explode('/',$model->arr_appid));
            $model->addtime = $model->addtime . substr(time(), -8);
            if($model->save()){
                return $this->success(1);
            }else{
                return $this->error('数据写入失败');
            }
        } else {
            $model->arr_appid = json_decode($model->arr_appid,true);
            $model->arr_appid = implode('/',$model->arr_appid);
            $img = json_decode($model->img,true);

            $model->img = $img[0];
            $model->img_5500 = $img['5.5.0.0'];
            $model->addtime = substr($model->addtime, 0,1);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Special model.
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
     * Finds the Special model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Special the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Special::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionRank($id){
        $model = $this->findModel($id);
        $rank = isset($_GET['rank']) ? $_GET['rank'] : null ;
        if($rank && $id){
            $model->addtime = $rank .  substr(time(), -8);
            if($model->save()){
                return $this->success(1);
            }else{
                return $this->error('写入失败!');
            }
        }
    }
}
