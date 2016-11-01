<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\modules\appcake\models\AdSortOne;
use app\modules\appcake\models\IdfaCampidV3;
use app\modules\appcake\models\search\AdSortTwo;
use yii\console\Controller;
use app\modules\appcake\models\TaskStatus;
use yii;
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }

    public function actionTestCache(){
        $statusModel = new TaskStatus();
        $task = $statusModel->getAdSortStatus();

        if($task['ad_sort_current_table'] == 'ad_sort_one'){
            $data = AdSortOne::find()->asArray()->orderBy("current_sort asc")->all();
        }else{
            $data = AdSortTwo::find()->asArray()->orderBy("current_sort asc")->all();
        }
        echo '写入缓存  count' . count($data) . "\n";
        $data = json_encode($data);
        Yii::$app->cache->set('current_sort_table_test',$data);

        $this->actionTestWreite($task);
    }

    public function actionTestWreite($adSortStatus){
        $data = Yii::$app->cache->get('current_sort_table_test');

        $data = json_decode($data,true);
        echo '读取缓存 count' . count($data) . "\n";

        $logstr = "当前 从缓存读取前台同步排序表数据 写入\n";
        $sql = "truncate table ad_sort_two_copy;";
        $this->InsertBysql($sql);

        $i = 0;$sql = '';
        foreach($data as $k => $v){
            $i ++;
            $source = isset($v['source']) ? $v['source'] : 0 ;
            $isAd = isset($v['is_ad']) ? $v['is_ad'] : 0 ;
            $lastSort = isset($v['last_sort']) ? $v['last_sort'] : 0 ;
            $nextSort= isset($v['next_sort']) ? $v['next_sort'] : 0;
            $sql .= "insert into ad_sort_two_copy
                values({$v['id']},{$v['camp_id']},{$v['app_id']},'{$v['country']}',
                {$v['position']},{$source},{$v['current_sort']},{$isAd},
                {$nextSort},{$lastSort});";
            if($i >= 500){
                $this->InsertBysql($sql);
                $sql = '';
            }
        }
        echo '共写入条数' ."$i \n";
        $this->InsertBysql($sql);

    }

    public function InsertBysql($sql)
    {
        try{
            $connection = \Yii::$app->db;
            return $connection->createCommand($sql)->execute();
        }catch(\Exception $e){
            $trace = $e->getTraceAsString();
            Yii::info(date("Y-m-d H:i:s",time()) . $trace ,'adSortInserLog');
        }
    }

    public function actionTest($date=null){
        //ini_set('memory_limit', '800M');
        if($date == null){
            $date = isset($_SERVER["argv"][2]) ? $_SERVER["argv"][2] : date('Y-m-d',strtotime('-1 day'));
        }
        $model = new IdfaCampidV3();
        $data = $model->getByDate($date);

        $str = '';$title = '';
        foreach($data as $k => $v){
            $str1 = '';

            foreach($v as $kk => $vv){
                if($k == 0){
                    $title .= $kk .',';
                }
                $str1 .= $vv.'_' .',';
            }
            $reg = "/adjust/";
            $reg2 = "/appsflyer/";
            preg_match($reg,$v['urls'],$result);
            preg_match($reg2,$v['urls'], $result2);

            $isAdjust = $result ? 'adjust' : '-';
            $isAppsflyer =  $result2 ? 'appsflyer' : '-';
            $str2 = $isAdjust . ',' . $isAppsflyer .',';


            $str1 = $str2 . rtrim($str1,',') . "\n";
            $str .= $str1;
        }
        $title .= "adjust,appsflyer" ."\n";
        $str = $title . $str;
        //echo $str;
        $fileName = './runtime/csv/'. $date.'.csv';

        file_put_contents($fileName,$str);
        echo $fileName;
    }

}
