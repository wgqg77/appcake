<?php

namespace app\modules\appcake\controllers;

use app\modules\appcake\models\AppData;
use Yii;
use app\modules\appcake\models\AppVersionError;
use app\modules\appcake\models\search\AppVersionError as AppVersionErrorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;
/**
 * AppVersionUpdateController implements the CRUD actions for AppVersionError model.
 */
class AppVersionUpdateController extends IsloginController
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
     * Lists all AppVersionError models.
     * @return mixed
     * @title 应用版本更新管理
     */
    public function actionIndex()
    {
        $searchModel = new AppVersionErrorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionClear(){
        $errorTotal = AppVersionError::find()->select("count(*) as count")->asArray()->one();
        $errorTotal = $errorTotal['count'];
        $num = 200;
        $times = ceil($errorTotal / $num );
        for($i = 0 ; $i < $times;$i++){
            $delAppId  = array();
            $startNum = $i * $num;
            //$endNum = ($i + 1) * $num;
            $appId = AppVersionError::find()->select('app_id')->asArray()->offset($startNum)->limit($num)->all();

            $appId = array_column($appId,'app_id');
            $appId = array_map('intval',$appId);
            $app = AppData::find()->where(['app_id'=>$appId])->select('app_id,version,app_store_version')->asArray()->all();
            foreach($app as $k => $v){
                if($v['version'] == $v['app_store_version']){
                    $delAppId[] = $v['app_id'];
                }
            }
            $delApp = array_map('intval',$delAppId);
            $res = AppVersionError::deleteAll(['app_id'=>$delApp]);
        }
        return  $this->success(['/appcake/app-version-update/index']);
    }

    /**
     * Deletes an existing AppVersionError model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @title 应用版本更新删除
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AppVersionError model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AppVersionError the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AppVersionError::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
