<?php

namespace app\modules\appcake\controllers;

use Yii;
use app\modules\appcake\models\DataSummary;
use app\modules\appcake\models\search\DataSummary as DataSummarySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;
/**
 * DataSummaryController implements the CRUD actions for DataSummary model.
 */
class DataSummaryController extends IsloginController
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
     * Lists all DataSummary models.
     * @return mixed
     */
    public function actionIndex()
    {
        ini_set('memory_limit','1024M');
        $searchModel = new DataSummarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $cloneQuery = clone $dataProvider->query;

        $total =  $cloneQuery
            ->select('sum(click) as click ,
              count(*) as count,
              sum(download) as download ,
              sum(install) as install ,
              sum(cake_active) as cActive ,
              sum(h_click) as hClick ,
              sum(h_active) as hActive ,
              sum(analog_click) as aClick ,
              sum(CP) as CP ,
              sum(AP) as AP,
              sum(TA) as TA,
              sum(TP) as TP,
              sum(income) as income,
              ')->asArray()->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total' => $total
        ]);
    }




}
