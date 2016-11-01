<?php

namespace app\modules\appcake\controllers;

use Yii;
use app\modules\appcake\models\Bannerlist;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;

/**
 * BannerController implements the CRUD actions for BannerList model.
 */
class BannerController extends IsloginController
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
     * Lists all BannerList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => BannerList::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BannerList model.
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
     * Creates a new BannerList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BannerList();

        if ($model->load(Yii::$app->request->post()) ) {

            $model->begintime = strtotime($model->begintime);
            $model->endtime = strtotime($model->endtime);
            $model->rank = $model->rank .  substr(time(), -8);
            $imgArray = array('0' => $model->img, '5.5.0.0' => $model->img_5500);
            $model->img = json_encode($imgArray);
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->app_id]);
            }else{
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BannerList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {


            $model->begintime = strtotime($model->begintime);
            $model->endtime = strtotime($model->endtime);
            $model->rank = $model->rank .  substr(time(), -8);
            $imgArray = array('0' => $model->img, '5.5.0.0' => $model->img_5500);
            $model->img = json_encode($imgArray);
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->app_id]);
            }else{
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

        } else {
            $model->begintime = date('Y-m-d H:i:s',$model->begintime);
            $model->endtime = date('Y-m-d H:i:s',$model->endtime);
            $img = json_decode($model->img,true);

            $model->img = isset($img[0]) ? $img[0] : '';
            $model->img_5500 = isset($img['5.5.0.0']) ? $img['5.5.0.0'] : '';
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BannerList model.
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
     * Finds the BannerList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BannerList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BannerList::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionRank($id){
        $model = $this->findModel($id);
        $rank = isset($_GET['rank']) ? $_GET['rank'] : null ;
        if($rank && $id){
            $model->rank = $rank .  substr(time(), -8);
            if($model->save()){
                return $this->success(1);
            }else{
                return $this->error('写入失败!');
            }
        }
    }
}
