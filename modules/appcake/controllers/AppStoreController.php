<?php

namespace app\modules\appcake\controllers;

use app\modules\appcake\models\AppData;
use app\modules\appcake\models\CountriesApp;
use app\modules\appcake\models\IdfaAppidV4;
use Yii;
use app\modules\appcake\models\DownloadDone;
use app\modules\appcake\models\search\DownloadDone as DownloadDoneSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use app\modules\admin\controllers\IsloginController;
/**
 * DownloadController implements the CRUD actions for DownloadDone model.
 */
class AppStoreController extends IsloginController
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
     * Lists all DownloadDone models.
     * @return mixed
     */
    public function actionIndex()
    {
        ini_set('memory_limit','1024M');

        $startTime = isset($_GET['startTime']) ? $_GET['startTime'] : date("Y-m-d",strtotime('-1 day'));
        $endTime = isset($_GET['ednTime']) ? $_GET['ednTime'] : date("Y-m-d",time());
        $country = isset($_GET['IdfaAppidV4']['country']) ? $_GET['IdfaAppidV4']['country'] : '';

        if($country){
            $countryStr = " and country = '{$country}'";
        }else{
            $countryStr = '';
        }
        $AdData = CountriesApp::find()
            ->select("app_id")
            ->groupBy("app_id")
            ->asArray()
            ->all();

        $adAppId = array_column($AdData,'app_id','app_id');
        $hook = IdfaAppidV4::find()
            ->where(" time >= '{$startTime}' AND time <= '{$endTime}' $countryStr ")
            ->select("app_id,sum(number) as download")
            ->groupBy("app_id")
            ->orderBy("download desc")
            ->asArray()
            ->limit(1000)
            ->all();
        $appId = array_column($hook,'app_id');

        $appData = AppData::find()
            ->where(['app_id'=>$appId])
            ->select("app_id,app_name,category")
            ->asArray()
            ->all();

        $appCategory = array_column($appData,'category','app_id');

        $appName = array_column($appData,'app_name','app_id');
        $provider = new ArrayDataProvider([
            'allModels' => $hook,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'attributes' => ['download','desc'],
            ],
        ]);

        $searchModel = new IdfaAppidV4();
        $searchModel->country = $country;

        return $this->render('index', [
            'appCategory' => $appCategory,
            'searchModel' => $searchModel,
            'appName' => $appName,
            'adAppId' => $adAppId,
            'dataProvider' => $provider,
        ]);
    }


}
