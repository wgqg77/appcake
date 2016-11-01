<?php

namespace app\modules\appcake\controllers;

use Yii;
use app\modules\appcake\models\Horizontalbar;
use app\modules\appcake\models\search\Horizontalbar as HorizontalbarSearch;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;
/**
 * HorizontalbarController implements the CRUD actions for Horizontalbar model.
 */
class HorizontalbarController extends IsloginController
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
     * Lists all Horizontalbar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HorizontalbarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Horizontalbar model.
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
     * Creates a new Horizontalbar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Horizontalbar();

        if ($model->load(Yii::$app->request->post()) ) {
            $appId = array_filter(array_unique(implode('/',$model->app_id)));
            $category = $model->category;
            $country = $model->country;
            if(count($appId) > 1){
                foreach($appId as $k => $v){
                    $model->category = $category;
                    var_dump($category);
                    var_dump($country);
                    $model->country = $country;
                    $model->app_id = $v;
                    $model->rank = $k;
                    $model->insert();
                }

            }else{
                $model->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreateHo()
    {
        $model = new Horizontalbar();
        $model->country = 'US';
        if ($model->load(Yii::$app->request->post())) {

            $appIds = array_unique(array_filter(explode('/',$model->app_id)));
            $country = $model->country;
            $category = $model->category;
            foreach($appIds as $k => $v){
                $model->setIsNewRecord(true);
                $model->id = 0;
                $model->app_id = $v;
                $model->rank = $k;
                $model->time = time();
                $model->insert();
            }
            return $this->success();
        } else {
            return $this->render('create-ho', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreateHo2()
    {
        $model = new Horizontalbar();
        $model->setScenario('ho2');
        if ($model->load(Yii::$app->request->post()) ) {
            if(empty($model->app_id)) $model->addError('app_id','app_id不能为空,且只能是数字');
            if(empty($model->special_id)) $model->addError('special_id','special_id不能为空,且只能是数字');
            if(empty($model->img)) $model->addError('img','img不能为空,且只能是数字');
            if(empty($model->img) || empty($model->special_id) || empty($model->app_id)){
                return $this->render('create-ho2', [
                    'model' => $model,
                ]);
            }else{
                if($model->save()){
                    return $this->success();
                }
            }

        } else {
            return $this->render('create-ho2', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Horizontalbar model.
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





    public function actionUpdateApp()
    {
        $category = isset($_GET['category']) ? $_GET['category'] : 'APP';
        $app = Horizontalbar::find()->where("category = '{$category}' ")->asArray()->orderBy('rank asc')->all();
        $app_id = implode('/',array_column($app,'app_id'));
        $model = new Horizontalbar();
        $model->country = 'US';
        $model->category = $category;
        $model->app_id = $app_id;
        $model->special_id = 0;
        $model->appstore = 1;
        $model->time = time();

        if ($model->load(Yii::$app->request->post()) ) {
            Horizontalbar::deleteAll("category = '{$category}'");
            $appArr = explode('/',$model->app_id);
            foreach($appArr as $k => $v){
                $model->setIsNewRecord(true);
                $model->id = null;
                $model->app_id = $v;
                $model->rank = $k;
                $model->time = time();
                $model->insert();
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('updateapp', [
                'model' => $model,
                'category' => $category
            ]);
        }
    }

    /**
     * Deletes an existing Horizontalbar model.
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
     * Finds the Horizontalbar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Horizontalbar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Horizontalbar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionRank($id){
        $model = $this->findModel($id);
        $rank = isset($_GET['rank']) ? $_GET['rank'] : null ;
        if($rank && $id){
            $model->rank = $rank;
            if($model->save()){
                return $this->success();
            }else{
                return $this->error('写入失败!');
            }
        }
    }
}
