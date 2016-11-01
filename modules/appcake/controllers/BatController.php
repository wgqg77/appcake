<?php

namespace app\modules\appcake\controllers;

use Yii;
use app\modules\appcake\models\Bat;
use app\modules\appcake\models\search\Bat as BatSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;
/**
 * AdallController implements the CRUD actions for BatAll model.
 */
class BatController extends IsloginController
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
        $searchModel = new BatSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    protected function findModel($camp_id, $source)
    {
        if (($model = BatAll::findOne(['camp_id' => $camp_id, 'source' => $source])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionNeedAdd()
    {
        $sql = "select mobile_app_id from bat where mobile_app_id not in (select app_id from app_data where app_id in (select mobile_app_id from bat)); ";

        $appids = $this->InsertBysql($sql);


    }

    public function InsertBysql($sql)
    {
        $connection = \Yii::$app->cake;
        return $connection->createCommand($sql)->execute();
    }
}
