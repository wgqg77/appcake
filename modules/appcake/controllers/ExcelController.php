<?php

namespace app\modules\appcake\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
//use app\modules\admin\controllers\IsloginController;
use yii\web\Controller;
/**
 * AdallController implements the CRUD actions for BatAll model.
 */
class ExcelController extends Controller
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
        $rootPath = Yii::$app->basePath;
        $fileList=glob($rootPath.'/Excel/*');
        $file = array();
        foreach($fileList as $k => $v) {
            $file[$k]['name'] = basename($v);
            $file[$k]['size'] = round(filesize($v)/1024/1014,2)."Mb";
            $file[$k]['path'] = $v;
            $file[$k]['time'] = date('Y/m/d H:i:s',filemtime($v));
            $file[$k]['unixtime'] = filemtime($v);
        }

        $a_adddate = array_column($file, 'unixtime');
        array_multisort($a_adddate, SORT_DESC, $file);

        return $this->render('index', [
            'file' => $file,
        ]);
    }

    /**
     * 导出Excel
     */
    public function actionOutPutFile()
    {
        return $this->render('outputfile');
    }

    public function actionOutPutExcel()
    {
        $post = Yii::$app->request->post();
        $db = $post['db'];
        $name = isset($post['name']) && !empty($post['name']) ? $post['name'] : date("Y-m-d")."数据导出" . time() ;

        if(isset($post['startTime']) && !empty($post['startTime'])) {
            $startTime = $post['startTime'] ;
        }else{
            $startTime = date("Y-m-d",strtotime('-1 day')) ;
        }

        if(isset($post['endTime']) && !empty($post['endTime'])) {
            $endTime = $post['endTime'] ;
        }else{
            $endTime = date("Y-m-d",time()) ;
        }
        $source = $post['source'];
        $source = !empty($source) ? "and source = {$source}" : '';


        if($db == 1){   //汇总统计广告
            $sql = "select * from data_summary where date >= '{$startTime}' and date < '{$endTime}' and camp_id > 0 ;";
        }else if($db == 2){     //汇总统计非广告
            $sql = "select * from data_summary where date = '{$startTime}'  and camp_id = 0 ;";
        }else if($db == 3){     //汇总统计位置广告
            $sql = "select * from data_summary_position where date >= '{$startTime}' and date < '{$endTime}' and camp_id > 0 ;";
        }else if($db == 4){     //汇总统计位置all
            $sql = "select id,date,app_id,camp_id,ad_source,country,sum(click) as clickTotal,sum(download) as downloadTotal,sum(install) as installTotal,position,name,category,countries from data_summary_position where date = '{$startTime}'   group by country,app_id,position;";
        }else if($db == 5){  //最近7天汇总
            $sql = "select * from data_summary_week where date = '{$startTime}';";
        }else if($db == 6){
            $sql = "select id,app_id,camp_id,app_name,sum(active_num) as active_num,sum(total_price) as total_price,source,date from active_data_record where date >= '{$startTime}' and date < '{$endTime}' $source  group by source,camp_id;";
        }else if($db == 7){
            $sql = "select * from active_data where date >= '{$startTime}' and date < '{$endTime}' $source; ";
        }

        $sql = urlencode($sql);
        $url = Yii::$app->params['go_excel_url'] ."?sql=".$sql."&db=app_system&name=".$name;

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

    public function actionDel()
    {
        $fileName = $_GET['filename'];
        $rootPath = Yii::$app->basePath .'/Excel/';
        if(is_file($rootPath . $fileName)){
            unlink($rootPath.$fileName);
            return $this->success();
        }else{
            //清空目录 递归删除暂无
            $this->error("删除失败 清空目录 递归删除暂无");
        }
    }

    public function actionDownload($path=null)
    {
        if($path != null){
            $id = $path;
        }else{
            $id = $_GET['filename'];
        }
        $file= Yii::$app->basePath .'/Excel/'.$id;
        //二进制文件
        header("Content-type:application/octet-stream");
        //获得文件名
        $fileName = basename($file);
        //下载窗口中显 示的文件名
        header("Content-Disposition:attachment;filename={$fileName}");
        //文件尺寸单位
        header("Content-ranges:bytes");
        //文件大小
        header("Content-length:".filesize($file));
        //读出文件内容
        readfile($file);
    }

    public function actionLoading()
    {
        echo '<span style="color: #999;">请求已经提交,你可以在文件下载成功后关闭此对话.</span>';
    }

}
