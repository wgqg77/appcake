<?php

namespace app\modules\appcake\controllers;

use app\modules\appcake\models\AppData;
use Yii;
use app\modules\appcake\models\Required;
use app\modules\appcake\models\search\Required as RequiredSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;
/**
 * RequiredController implements the CRUD actions for Required model.
 */
class RequiredController extends IsloginController
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
     * Lists all Required models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequiredSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Required model.
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
     * Creates a new Required model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Required();

        if ($model->load(Yii::$app->request->post()) ) {

            $isExist = $this->is_exist_appid($model->app_id);

            if($isExist == null ){
                $model->rank = $model->rank .substr(time(),0,8);
                $model->save();
                return $this->redirect(['view', 'id' => $model->app_id]);
            }else{
                $model->addError('app_id','当前app已经存在');
                return $this->render('create', [
                    'model' => $model
                ]);
            }
        } else {

            $model->rank = 8;
            $model->frank = 0;
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    public function is_exist_appid($appid)
    {
        return Required::find()->where(['app_id'=> $appid])->asArray()->one();
    }

    /**
     * Updates an existing Required model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            $model->rank = $model->rank .substr(time(),0,8);
            if($model->save()){
                return $this->success();
            }else{
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

        } else {
            $model->rank = substr($model->rank,0,1);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Required model.
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
     * Finds the Required model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Required the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Required::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionRank($id){
        $model = $this->findModel($id);
        $rank = isset($_GET['rank']) ? $_GET['rank'] : null ;
        if($rank && $id){
            $model->frank = $rank;
            if($model->save()){
                return $this->success(1);
            }else{
                return $this->error('写入失败!');
            }
        }
    }
}
