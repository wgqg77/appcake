<?php

namespace app\modules\appcake\controllers;

use Yii;
use app\modules\appcake\models\Searchword;
use app\modules\appcake\models\search\Searchword as SearchwordSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;
/**
 * SearchwordController implements the CRUD actions for Searchword model.
 */
class SearchwordController extends IsloginController
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
     * Lists all Searchword models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchwordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Searchword model.
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
     * Creates a new Searchword model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Searchword();

        if ($model->load(Yii::$app->request->post())) {
            $model->addtime = $model->addtime . substr(time(), -8);
            if( $model->save() ){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                $this->error('保存失败');
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Searchword model.
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
            $model->addtime = substr($model->addtime,0,1);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Searchword model.
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
     * Finds the Searchword model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Searchword the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Searchword::findOne($id)) !== null) {
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
