<?php

namespace app\modules\appcake\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\appcake\models\CakeInfo;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;



class CakeInfoController extends IsloginController
{


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
     * Lists all CakeAdIdfa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CakeInfo::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($name)
    {

        $model = $this->findModel($name);


        if ($model->load(Yii::$app->request->post()) ) {
            $post = Yii::$app->request->post()['CakeInfo'];

            $sql = " update cake_info set name = '{$post['name']}',
                     version = '{$post['version']}',
                     md5 = '{$post['md5']}',
                     download_url = '{$post['download_url']}',
                     message = '{$post['message']}',
                     updateinfo = '{$post['updateinfo']}',
                     download_url2 = '{$post['download_url2']}'
                     where name = '{$name}' ;";
            $res = $this->InsertBysql($sql);

            // Clear the cache
            if (YII_ENV == 'prod' || YII_ENV == 'pro') {
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'https://api.app-cake.com/apiserver/index.php/home/?c=index&a=clearAppCakeInfoCache');
                curl_exec($curl);
                curl_close($curl);
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    protected function findModel($name)
    {
        if (($model = CakeInfo::findOne(['name' => $name])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function InsertBysql($sql)
    {
        $connection = \Yii::$app->cake;
        return $connection->createCommand($sql)->execute();
    }

}
