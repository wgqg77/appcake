<?php
/**
 * 广告排序控制器
 * step 1 ./yii ad-sort/init-ad-sort
 * step 2 ./yii ad-sort/init-update-status
 * step 3 ./yii ad-sort/update-sort
 */

namespace app\modules\appcake\controllers;

use app\modules\appcake\models\search\AdSortRecord;
use app\modules\appcake\models\AdSortPreview;
use app\modules\appcake\models\search\AdSortPreview as AdSortPreviewSearch;
use app\modules\appcake\models\TaskStatus;
use app\modules\admin\controllers\IsloginController;
use Yii;

class SortPreviewController extends IsloginController
{

    //当前前任务表 初始化生成列表规则 基数1表 偶数2表
    public $currentTable = '';

    public $nextTable = '';

    public function actionIndex()
    {
        $taskStatus = $this->getTaskStaus();

        $searchModel = new AdSortPreviewSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskStatus' => $taskStatus
        ]);
    }

    /**
     * 更新排序
     *
     */
    public function actionUpdateSort()
    {
        ini_set("max_execution_time",1800);
        //读取任务执行状态
        $adSortStatus = $this->getTaskStatus();

        $this->updatePreviewStatus();

        $recordModel = new AdSortRecord();
        $recordData = $recordModel->getrecordToUpdate($adSortStatus['ad_sort_next_time']);

        $nextUpdateTime = strtotime($adSortStatus['ad_sort_next_time']);
        //初始化预览表
        $this->initTable($adSortStatus);

        if(!empty($recordData)){
            //遍历更新 排序
            foreach($recordData as $k => $v){

                if($v['update_method'] == 1 ) continue;     //即时生效跳过
                if($v['update_method'] == 2  && strtotime($v['start_time']) > $nextUpdateTime) continue;  //生效时间未到 跳过任务

                //查询任务实际位置 存在当前位置已发生变化问题
                if($k >= 1){
                    $vCurrent =  $this->getCurrentSortById($v['sort_id']);
                    if($vCurrent) $v['current_sort'] = $vCurrent['current_sort'];
                }

                //根据排序方式执行排序
                if($v['sort_method'] == 1){ //自动排序
                    $this->autoSort($v);
                }else if($v['sort_method'] == 2){  //对换位置
                    $this->changeSort($v);
                }else if($v['sort_method'] == 3){   //新添加
                    $this->addSort($v);
                }
            }
        }
        else //不修改排序 同步数据
        {
            $logstr = "修改排序任务数0.";
            echo $logstr;
            Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'updateadsort');
        }
        $logstr = "更新排序操作任务执行完毕\n\r";
        echo $logstr;
        return  $this->success(['/appcake/sort-preview/index']);

//        $str = "<script language=\"javascript\"> setTimeout(function(){window.history.back(-1);},1500)</script>";
//        echo $logstr;
//        echo $str;

        Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'updateadsort');die;

    }

    public function getCurrentSortById($id)
    {

        $data = AdSortPreview::find()->where(['id'=> $id])->asArray()->one();

        return $data;
    }

    public function getTaskStaus()
    {
        $model = new TaskStatus();

        $taskStatus = $model->getAdSortStatus();

        return $taskStatus;
    }

    /**
     * 初始化预览表
     */
    public function initTable($adSortStatus)
    {
        $sql = "truncate table ad_sort_preview;
                insert into ad_sort_preview (select * from {$adSortStatus['ad_sort_current_table']}) ;
                update ad_sort_preview set last_sort = current_sort;";
        $res = $this->InsertBysql($sql);
        return $res;
    }

    /**
     * 读取任务状态
     */
    public function getTaskStatus()
    {
        $statusModel = new TaskStatus();
        return $statusModel->getAdSortStatus();
    }

    public function getTaskValue($selectName)
    {
        $statusModel = new TaskStatus();
        return $statusModel->getTaskValue($selectName);
    }

    //克隆表
    public function cloneInitTable()
    {
        $sql = "insert into {$this->nextTable} (select * from {$this->currentTable}) ";
        $res = $this->InsertBysql($sql);
        return true;
    }

    public function InsertBysql($sql)
    {
        $connection = \Yii::$app->db;
        return $connection->createCommand($sql)->execute();
    }


    /**
     *  修改排序列表方法    ///////////////////////////////
     */

    public function actionInitUpdateStatus()
    {
        $nextUpdateTime = date('Y-m-d H:i:s',time() + 5 * 60);
        $sql = "insert into `task_status` VALUES ('ad_sort_interval',5),
                ('ad_sort_is_post', 0),
                ('ad_sort_update_lock', 0),
                ('ad_sort_next_time', '{$nextUpdateTime}')";
        $res = $this->InsertBysql($sql);
        $logStr = "初始化任务状态完成";
        if($res){
            echo $logStr;
        }else{
            $logStr = "初始化写入失败,请检查是否写入重复";
            echo $logStr;
        }
        Yii::info(date("Y-m-d H:i:s",time()) . $logStr ,'updateadsort');die;
    }

    /**
     * 同步表数据 更新任务状态
     */
    public function updateAdSortTable($adSortStatus)
    {
        $nextUpdateTime = date('Y-m-d H:i:s',strtotime($adSortStatus['ad_sort_next_time']) + $adSortStatus['ad_sort_interval'] * 60);
        $sql = "truncate table {$adSortStatus['ad_sort_next_table']};
                insert into {$adSortStatus['ad_sort_next_table']} (select * from {$adSortStatus['ad_sort_current_table']});
                update task_status set value = '{$adSortStatus['ad_sort_next_table']}' where name = 'ad_sort_current_table' ;
                update task_status set value = '{$adSortStatus['ad_sort_current_table']}' where name = 'ad_sort_next_table' ;
                update task_status set value = 0 where name = 'ad_sort_update_lock' ;
                update task_status set value = '{$nextUpdateTime}' where name = 'ad_sort_next_time' ;
                update task_status set value = 0 where name = 'ad_sort_is_post' ;
                ";
        $this->InsertBysql($sql);

        $logstr = "当前时间:" .date('Y-m-d H:i:s',time()) ."执行同步表操作 更新任务状态完成 下次任务时间:{$nextUpdateTime}\n\r";
        echo $logstr;
        Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'updateadsort');
        return true;
    }



    /**
     * 自动排序
     */
    public function autoSort($v)
    {
        $sql = "";
        //前换后
        if($v['current_sort'] < $v['next_sort']){
            //非自身位置修改 前
            $sql .= "UPDATE ad_sort_preview set current_sort = current_sort - 1
                      where country = '{$v['country']}'
                      and position = {$v['position']}
                      and current_sort <= {$v['next_sort']}
                      and current_sort > {$v['current_sort']} ; ";
            //非自身位置修改 后
            //$sql .= "UPDATE ad_sort_one set current_sort = current_sort + 1  where country = '{$v['country']}' and current_sort > {$v['next_sort']} ; ";
            //自身位置修改
            $sql .= "UPDATE ad_sort_preview set current_sort = {$v['next_sort']} ,next_sort = null
                      where country = '{$v['country']}'
                      and position = {$v['position']}
                      and current_sort = {$v['current_sort']}
                      and id = {$v['sort_id']}; ";
            $sql .= "UPDATE ad_sort_record set is_updated = 2 where id = {$v['id']};";
        }
        //后换前
        if($v['current_sort'] > $v['next_sort']){
            //非自身位置修改
            $sql .= "UPDATE ad_sort_preview set current_sort = current_sort + 1  where
                    country = '{$v['country']}'
                    and position = {$v['position']}
                    and current_sort >= {$v['next_sort']}
                    and current_sort < {$v['current_sort']} ; ";
            //自身位置修改
            $sql .= "UPDATE ad_sort_preview set current_sort = {$v['next_sort']} ,next_sort = null
                    where country = '{$v['country']}'
                    and position = {$v['position']}
                    and current_sort = {$v['current_sort']}
                    and id = {$v['sort_id']}; ";
            $sql .= "UPDATE ad_sort_record set is_updated = 2 where id = {$v['id']};";
        }
        if(!empty($sql)){
            $this->InsertBysql($sql);
        }
        return true;
    }

    /**
     * 对换位置排序
     */
    public function changeSort($v)
    {
        //前换后  后换前
        $sql = "UPDATE ad_sort_preview set current_sort = {$v['current_sort']}
                    where country = '{$v['country']}'
                    and position = {$v['position']}
                    and current_sort = {$v['next_sort']} ;";
        $sql .= "UPDATE ad_sort_preview set current_sort = {$v['next_sort']} ,next_sort = null
                    where id = {$v['sort_id']}; ";

        $sql .= "UPDATE ad_sort_record set is_updated = 2 where id = {$v['id']};";

        $this->InsertBysql($sql);
        return true;
    }

    /**
     * 排序列表添加
     */
    public function addSort($v)
    {
        //自动排序
        $sql = "UPDATE ad_sort_preview set current_sort = current_sort + 1
                    where country = '{$v['country']}'
                    and position = {$v['position']}
                    and current_sort >= {$v['next_sort']} ;
                DELETE from ad_sort_one where current_sort > 200;
                INSERT INTO ad_sort_preview VALUES
            (0, {$v['camp_id']}, {$v['app_id']}, '{$v['country']}', {$v['position']}, {$v['source']}, {$v['next_sort']}, {$v['is_ad']}, null,201);";

        $sql .= "UPDATE ad_sort_record set is_updated = 2 where id = {$v['id']};";

        $this->InsertBysql($sql);
        return true;
        //兑换 暂未添加
    }

    public function updatePreviewStatus()
    {
        $sql = "update task_status set value = 1 where name = 'is_preview' ;";
        $this->InsertBysql($sql);
        return true;
    }


}