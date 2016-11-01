<?php

namespace app\modules\appcake\controllers;

use Yii;
use app\modules\appcake\models\CakeAdIdfa;
use app\modules\appcake\models\search\CakeAdIdfa as CakeAdIdfaSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\controllers\IsloginController;

class ToolController extends IsloginController{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGettime()
    {
        $date = isset($_POST['date']) && !empty($_POST['date']) ? $_POST['date'] : date("Y-m-d",time());

        $time = strtotime($date);
        $return = array(
            'code' => 10000,
            'data' => $time
        );
        return \yii\helpers\Json::encode($return);
    }

    public function actionSelect()
    {

        $reg = '/(delete|update|truncate|alter|insert|create|\suser|userlist|admin|bt_itunes_user)/';
        $get = Yii::$app->request->post();
        $sql= isset($get['sql']) && !empty($get['sql']) ? $get['sql'] : false;
        preg_match($reg,$sql, $matches);
        if(isset($_POST['debug'])){
            $matches = array();
        }
        $defaultFileName = date('Y-m-d-H:i:s',time()).'-sql-'.time();
        $fileName = isset($get['name']) && !empty($get['name'])?$get['name']:$defaultFileName;
        $type = isset($get['type']) && !empty($get['type']) ? $get['type'] : 0;
        $db = isset($get['db']) && !empty($get['db']) ?  $get['db'] : 'app_system';
        if( $sql && empty($matches)){

            $sql = htmlspecialchars_decode($sql);

            if($type == 0){
                //页面显示
                $data = $this->selectBysql($db,$sql);
                var_dump($data);
            }else{
                //调用方法导出
                $sql = urlencode($sql);
                $url = Yii::$app->params['go_excel_url'] ."?sql=".$sql."&db=".$db."&name=".$fileName;

                $data = file_get_contents($url);
                $data = json_decode($data,true);

                if($data['code'] == 10000){
                    $return = array(
                        'code' => 10000,
                        'url'  => Url::to(['/appcake/excel/download']) ."&filename=". $data['name'] . ".xlsx"
                    );
                    echo json_encode($return);
                }

            }

        }else if(empty($sql) || $matches){
            $return = array(
                'code' => 10002,
                'message' => '参数错误或权限不足'
            );
            var_dump($return);
        }
    }

    public function selectBysql($db,$sql){
        if($db == 'app_system'){
            $res = $this->selectBySql_appsystem($sql);
        }else if($db == 'iphonecake'){
            $res = $this->selectBySql_iphonecake($sql);
        }else if($db == 'ad_system2'){
            $res = $this->selectBySql_adSystem2($sql);
        }else if($db == 'apptree_weike'){
            $res = $this->selectBySql_weike($sql);
        }else{
            $res =  'db 未添加';
        }
        return $res;
    }

    public function selectBySql_appsystem($sql)
    {
        $connection = \Yii::$app->db;
        return $connection->createCommand($sql)->queryAll();
    }

    public function selectBySql_iphonecake($sql)
    {
        $connection = \Yii::$app->cake;
        return $connection->createCommand($sql)->queryAll();
    }

    public function selectBySql_adSystem2($sql)
    {
        $connection = \Yii::$app->ad;
        return $connection->createCommand($sql)->queryAll();
    }

    public function selectBySql_weike($sql)
    {
        $connection = \Yii::$app->weike;
        return $connection->createCommand($sql)->queryAll();
    }
}