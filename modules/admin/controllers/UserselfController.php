<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\Admin;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;

/**
 * UserselfController implements the CRUD actions for Admin model.
 */
class UserselfController extends IsloginController
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
     * Lists all Admin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $id =  Yii::$app->session['user_id'];
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }





    /**
     * Updates an existing Admin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //$model->setScenario('editPasswd');
        if ($model->load(Yii::$app->request->post())) {

            if(!empty($model->passwd)){
                $model->passwd = md5($model->passwd);
            }
            if(!$model->save()){
                $error = $model->errors;
                return $this->render('update', [
                    'model' => $model,
                ]);
            };
            return $this->redirect(['index', 'id' => $model->uid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }



    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
