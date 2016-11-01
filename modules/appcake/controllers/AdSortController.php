<?php

namespace app\modules\appcake\controllers;

use app\modules\appcake\models\AdCountry;
use app\modules\appcake\models\AppData;
use app\modules\appcake\models\Bat;
use app\modules\appcake\models\CountriesApp;
use app\modules\appcake\models\search\AdSortPreview;
use app\modules\appcake\models\TaskStatus;
use Yii;
use app\modules\appcake\models\AdSortOne;
use app\modules\appcake\models\search\AdSortOne as AdSortOneSearch;
use app\modules\appcake\models\AdSortTwo;
use app\modules\appcake\models\search\AdSortTwo as AdSortTwoSearch;
use yii\base\ViewRenderer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\appcake\models\AdSortRecord;
use app\modules\admin\controllers\IsloginController;
/**
 * AdSortController implements the CRUD actions for AdSortOne model.
 */
class AdSortController extends IsloginController
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
     * Lists all AdSortOne models.
     * @return mixed
     */
    public function actionIndex()
    {
        $taskStatus = $this->getTaskStaus();

        if($taskStatus['ad_sort_current_table'] == "ad_sort_one"){
            $searchModel = new AdSortOneSearch();
        }else{
            $searchModel = new AdSortTwoSearch();
        }



        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taskStatus' => $taskStatus
        ]);
    }

    public function getTaskStaus()
    {
        $model = new TaskStatus();

        $taskStatus = $model->getAdSortStatus();

        return $taskStatus;
    }

    /**
     * Displays a single AdSortOne model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AdSortOne model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $isInUpdateTime = $this->isUpdateTime();
        if(!$isInUpdateTime){
            return $this->error('请在整点前后5分钟以外的时间操作');
        }

        $this->updatePreviewStatus();

        $currentTable = $this->getCurrentTable();

        $model = new AdSortOne();

        if ($model->load(Yii::$app->request->post())) {

            //广告id判断
            $post = Yii::$app->request->post()['AdSortOne'];
            if($post['is_ad'] == 0){
                $post['source'] = 0;
                $post['camp_id'] = 0;
            }else{
                if(empty($post['camp_id'])){
                    $model->addError('camp_id','选择了广告,广告id就不能为空');
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            }

            //排期时间判断
            if($post['update_method'] == 2){
                if(empty($post['start_time']) || empty($post['end_time'])){
                    $model->addError('start_time','排期模式,开始/结束时间不能为空');
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            }

            //即时生效模式
            if($post['update_method'] == 1){
                $this->updateRightNow($post);
            }

            //app是否存在
            $app_id = AppData::find()->where(['app_id'=> $post['app_id']])->asArray()->one();
            if(!$app_id){
                $model->addError('app_id','app_id在库中不存在');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }




            $start_time = !empty($post['start_time']) ? (string)"{$post['start_time']}" :  date("Y-m-d H:i:s",time());
            $end_time = !empty($post['end_time']) ?  (string)"{$post['end_time']}" : date("Y-m-d H:i:s",time());


            //遍历写入任务
            $sql = 'INSERT INTO `ad_sort_record` VALUES';
            $date = date('Y-m-d H:i:s',time());
            foreach($post['country'] as $k => $v){

                //排期查重
                if($post['sort_method'] == 2){
                    $is_exist = $this->isExistSortDate($post,$v);
                    if($is_exist){
                        $model->addError('country',"当前[{$v}]位置时间段在排期中已经存在");
                        return $this->render('create', [
                            'create' => $model,
                        ]);
                    }
                }

                //位置查重
                if($post['update_method'] == 0){
                    $is_exist = $this->isExistSortPosition($post,$v);
                    if($is_exist){
                        //已经存在任务记录的 修改任务记录
                        //$position = Yii::$app->params['ad_sort_position_name'][$post['position']];
                        //$model->addError('country',"当前应用在国家[{$v}]位置[{$position}]已经存在,请选择其他位置添加");
                        //return $this->render('create', [
                        //    'model' => $model,
                        //]);

                            $updateTaskSql = "update ad_sort_record
                            set
                            next_sort = {$post['next_sort']} ,
                            sort_method = {$post['sort_method']} ,
                            update_method = {$post['update_method']} ,
                            start_time = '{$start_time}',
                            end_time = '{$end_time}'
                            where
                            is_updated = 0
                            and country = '{$v}'
                            and position = '{$post['position']}'
                            and app_id = {$post['app_id']}
                            and camp_id = {$post['camp_id']}
                            and is_ad = {$post['is_ad']} ;
                            update task_status set value = 0 where name = 'ad_sort_is_post'; ";


                        $this->InsertBysql($updateTaskSql);
                        continue;
                    }
                }

                //查看app是否在列表中存在
                $current = $this->getCurrentSort($post,$v,$currentTable);
                //任务列表中查看是否有添加重复app
                $current_2 =$this->isExistAppInRecord($post,$v);

                if(!empty($current) || !empty($current_2)){
                    $position = isset(Yii::$app->params['ad_sort_position_name'][$post['position']]) ?
                        Yii::$app->params['ad_sort_position_name'][$post['position']]  : $post['position'];

                    $task =  !empty($current_2) ? "任务" : '';
                    $model->addError('app_id',"国家[$v]/列表[{$position}]在{$task}列表中已存在当前app 不能添加重复app");
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }


                if($start_time){
                    $sql .= " (0, '{$v}', {$post['camp_id']}, {$post['app_id']}, {$post['position']}, 0, 0,  {$post['next_sort']}, {$post['sort_method']}, {$post['update_method']}, 0,'{$date}', '{$start_time}', '{$end_time}', {$post['is_ad']}, {$post['source']}),";
                }else{
                    $sql .= " (0, '{$v}', {$post['camp_id']}, {$post['app_id']}, {$post['position']}, 0, 0,  {$post['next_sort']}, {$post['sort_method']}, {$post['update_method']}, 0,'{$date}', {$start_time}, {$end_time}, {$post['is_ad']}, {$post['source']}),";
                }
            }
            if(strlen($sql) > 40 ){
                $sql = trim($sql,',') .';';
                $sql .= "update task_status set value = 0 where name = 'ad_sort_is_post'; ";
                $this->InsertBysql($sql);
            }

            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    public function updatePreviewStatus()
    {
        $sql = "update task_status set value = 0 where name = 'is_preview' ;";
        $this->InsertBysql($sql);
        return true;
    }

    public function isUpdateTime()
    {
        $time = strtotime(date('Y-m-d H:00:00',time()));
        $t = time();
        if($t <= $time + 5*60  &&  $t >= $time - 5 * 60 ){
            return false;
        }else{
            return true;
        }
    }

    public function updateRightNow($post){

    }
    /**
     * Updates an existing AdSortOne model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $currentTable = $this->getCurrentTable();

        if ($model->load(Yii::$app->request->post()) ) {
            $isUpdateTime = $this->isUpdateTime();
            if(!$isUpdateTime){
                return $this->error('请在整点前后5分钟以外的时间操作');
            }

            $this->updatePreviewStatus();

            if($currentTable == 'ad_sort_one'){
                $post = Yii::$app->request->post()['AdSortOne'];
            }else{
                $post = Yii::$app->request->post()['AdSortTwo'];
            }



            if(empty($post['country'])) $post['country'][0] = $model->country;
            $post['is_ad'] = $model->is_ad;
            $post['camp_id'] = $model->camp_id;
            $post['app_id'] = $model->app_id;
            $post['position'] = $model->position;
            $post['source'] = $model->source;

            if($post['is_ad'] == 0){
                $post['source'] = 0;
                $post['camp_id'] = 0;
            }

            //修改排序不能和当前排序相同
            if($model->current_sort == $post['next_sort']){
                $model->addError('next_sort','修改排序不能和当前排序相同');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            //即时修改生效
            if($model->update_method == 1){
                //调取方法修改 表1/2 修改顺序
                echo "即时修改功能暂未添加";
                //$this->updateRightNow($post);
            }
            //记录修改操作

            //预排期 开始结束时间不能为空
            if($post['update_method'] == 2){
                if(empty($post['start_time']) || empty($post['end_time'])){
                    $model->addError('start_time','预排期模式开始/结束时间不能为空');
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            }

//            $Task = TaskStatus::find()->where('name = ad_sort_next_time or name = ad_sort_interval ')->asArray()->all();
//            $Task = array_column($Task,'name','value');

            $post['start_time'] = !empty($post['start_time']) ? (string)"{$post['start_time']}" : date("Y-m-d H:i:s",time());
            $post['end_time'] = !empty($post['end_time']) ? (string)"{$post['end_time']}" : date("Y-m-d H:i:s",time());
            //遍历写入任务
            $sql = 'INSERT INTO `ad_sort_record` VALUES';
            $date = date('Y-m-d H:i:s',time());

            foreach($post['country'] as $k => $v){

                //排期查重
                if($post['sort_method'] == 2){
                    $is_exist = $this->isExistSortDate($post,$v);
                    if($is_exist){
                        $model->addError('country',"当前[{$v}]位置时间段在排期中已经存在,不能重复编辑同一位置");
                        return $this->render('update', [
                            'model' => $model,
                        ]);
                    }
                }

                //查询当前位置 app查重
                $current = $this->getCurrentSort($post,$v,$currentTable);

                $current_sort = $current[0]['current_sort'];
                $sort_id = $current[0]['id'];

                if(count($current_sort) < 1){
                    $model->addError('country',"当前位置[{$v}]查询不到 请先添加再修改");
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }else if(count($current_sort) > 1){
                    $model->addError('country',"当前国家[{$v}]在列表{$v['position']}中出现相同app两个 请先处理异常");
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }

                //位置查重
                if($post['update_method'] == 0){
                    $is_exist = $this->isExistSortPosition($post,$v);
                    if($is_exist){
                        //已经存在任务记录的 修改任务记录
//                        $model->addError('country',"当前位置[{$v}]已经存在,不能重复编辑同一位置");
//                        return $this->render('update', [
//                            'model' => $model,
//                        ]);

                            $updateTaskSql = "update ad_sort_record
                            set
                            next_sort = {$post['next_sort']} ,
                            sort_method = {$post['sort_method']} ,
                            update_method = {$post['update_method']} ,
                            start_time = '{$post['start_time']}',
                            end_time = '{$post['end_time']}',
                            sort_id = {$sort_id}
                            where
                            is_updated = 0
                            and country = '{$v}'
                            and position = '{$post['position']}'
                            and app_id = {$post['app_id']}
                            and camp_id = {$post['camp_id']}
                            and is_ad = {$post['is_ad']} ;
                            update task_status set value = 0 where name = 'ad_sort_is_post'; ";

                        $this->InsertBysql($updateTaskSql);
                        continue;
                    }
                }


                if($post['start_time']){
                    $sql .= " (0, '{$v}', {$post['camp_id']}, {$post['app_id']}, {$post['position']}, {$sort_id}, {$current_sort},  {$post['next_sort']}, {$post['sort_method']}, {$post['update_method']}, 0,'{$date}', '{$post['start_time']}', '{$post['end_time']}', {$post['is_ad']}, {$post['source']}),";
                }else{
                    $sql .= " (0, '{$v}', {$post['camp_id']}, {$post['app_id']}, {$post['position']}, {$sort_id}, {$current_sort},  {$post['next_sort']}, {$post['sort_method']}, {$post['update_method']}, 0,'{$date}', null, null, {$post['is_ad']}, {$post['source']}),";
                }
            }

            if(strlen($sql) > 40){
                $sql = trim($sql,',') .';';
                $sql.= "update task_status set value = 0 where name = 'ad_sort_is_post'; ";
                $this->InsertBysql($sql);
            }



            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AdSortOne model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function getCurrentSort($post,$country,$currentTable)
    {
        if($currentTable == 'ad_sort_one'){
            $is_exist = AdSortOne::find()->where("
                    country = '{$country}'
                    and position = {$post['position']}
                    and app_id = {$post['app_id']}
                    ") // and camp_id = {$post['camp_id']} and is_ad = {$post['is_ad']}
//          ->select("current_sort")
            ->asArray()
            ->all();
        }else{
            $is_exist = AdSortTwo::find()->where("
                    country = '{$country}'
                    and position = {$post['position']}
                    and app_id = {$post['app_id']}
                    ") // and camp_id = {$post['camp_id']} and is_ad = {$post['is_ad']}
//          ->select("current_sort")
            ->asArray()
            ->all();
        }



        if($is_exist){
            return $is_exist;
        }else{
            return false;
        }
    }

    /**
     * 查看任务列表中是否已经添加过重复app
     */
    public function isExistAppInRecord($post,$country)
    {
        $is_exist = AdSortRecord::find()->where("
                    sort_method = 3
                    and is_updated = 0
                    and country = '{$country}'
                    and position = {$post['position']}
                    and app_id = {$post['app_id']}
                    ") // and camp_id = {$post['camp_id']} and is_ad = {$post['is_ad']}
//            ->select("current_sort")
            ->asArray()
            ->all();
        if($is_exist){
            return $is_exist;
        }else{
            return false;
        }
    }
    /**
     * 是否已有排期
     */
    public function isExistSortDate($post,$country)
    {
        $is_exist = AdSortRecord::find()->where("
                    is_updated = 0
                    and country = '{$country}'
                    and position = {$post['position']}
                    and app_id = {$post['app_id']}
                    and camp_id = {$post['camp_id']}
                    and is_ad = {$post['is_ad']}
                    and start_time >= '{$post['start_time']}'
                    and end_time < '{$post['start_time']}' ")
            ->asArray()
            ->one();
        if($is_exist) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * 是否时间段内位置被占
     */
    public function isExistSortPosition($post,$country)
    {
        $is_exist = AdSortRecord::find()->where("
                    is_updated = 0
                    and country = '{$country}'
                    and position = {$post['position']}
                    and app_id = {$post['app_id']}
                    and camp_id = {$post['camp_id']}
                    and is_ad = {$post['is_ad']} ")
            ->asArray()
            ->one();
        if($is_exist) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * Finds the AdSortOne model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdSortOne the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $currentTable = $this->getCurrentTable();
        if($currentTable == 'ad_sort_one'){
            $model = AdSortOne::findOne($id);
        }else{
            $model = AdSortTwo::findOne($id);
        }
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function getCurrentTable(){
        $taskStatus = TaskStatus::find()->where("name = 'ad_sort_current_table' ")->select('value')->asArray()->one();
        $currentTable = $taskStatus['value'];
        return $currentTable;
    }

    //排序预览
    public function actionCheck()
    {
        return $this->render('check');
    }

    public function InsertBysql($sql)
    {
        $connection = \Yii::$app->db;
        return $connection->createCommand($sql)->execute();
    }

    /**
     * @return string
     * 排序检查
     */
    public function actionCheckSort(){
        ini_set("max_execution_time", "1800");
        header("Content-Encoding: none\r\n");
        if (ob_get_level() == 0) ob_start();//打开缓冲区
        $logstr = '';
        $currentTable = $this->getCurrentTable();
        $logstr .= sprintf('%s%s-----开始任务:%s  开始->查询国家', "\n\n\n" , "\n<br />" , "\n<br />" . date('Y-m-d H:i:s',time()));
        $CountryModel = new AdCountry();
        $country = $CountryModel->getOnlineCountries();
        $logstr .= sprintf('%s  结束->查询国家',"\n<br />" . date('Y-m-d H:i:s',time()));

        echo $logstr;
        foreach($country as $k => $v){
            for($i=0;$i<=2;$i++){

                //排序校正
                $logstr = sprintf('%s  =====================\n<br />开始检查排序 国家【%s】位置【%s】',"\n<br />" . date('Y-m-d H:i:s',time()),$v['country_code'],$i);
                $sort = $this->getAdSortByPosition($currentTable,$v['country_code'],$i);
                //200个不足补充

                if(count($sort) < 200){
                    $num = 200 - count($sort);
                    $str = sprintf('国家[%s] 位置[%s] 缺少app[%s]个<hr />',$v['country_code'],$i,$num);
                    echo $str;
                    $appIdArr = array_column($sort,'app_id');

                    $this->insertApp($appIdArr,$v['country_code'],$i,$num);

                    $sort = $this->getAdSortByPosition($currentTable,$v['country_code'],$i);
                }

                $this->isSortOk($sort,$currentTable);
                $logstr .= sprintf('%s  结束->检查排序 国家[%s] 位置[%s]',"\n<br />" . date('Y-m-d H:i:s',time()),$v['country_code'],$i);
                echo $logstr;
                echo str_pad('',4096)."\n";
                ob_flush();
                flush();
                sleep(1);
            }

        }

        ob_end_flush();//输出并关闭缓冲

        return $this->success(['/appcake/sort-preview/index']);
    }

    public function actionPostSort()
    {
        ini_set("max_execution_time",1800);
        $isUpdateTime = $this->isUpdateTime();
        if(!$isUpdateTime){
            return $this->error('请在整点前/后5分钟以外的时间操作');
        }

        //是否已经生成预览
        $isPreview = $this->isPreview();
        if(!$isPreview){
            return $this->error('请先生成[预览列表]确认后再发布');
        }

        $currentTable = $this->getCurrentTable();

        $CountryModel = new AdCountry();
        $country = $CountryModel->getOnlineCountries();

        //更改发布状态
        $sql = "truncate table {$currentTable};insert into {$currentTable} (select * from ad_sort_preview);";
        $sql .= "update ad_sort_record set is_updated = 1 where is_updated = 2;";
        $sql .= "update task_status set value = 1 where name = 'ad_sort_is_post' ;";
        $res = $this->InsertBysql($sql);

        return $this->success(['/appcake/sort-preview/index']);
    }

    public function insertApp($appIdArr,$country,$position,$num)
    {
        $appData = AppData::find()->where(['not in', 'app_id', $appIdArr])->select("app_id")->asArray()->limit($num)->all();

        $sql = '';
        $sqlPrefix = "INSERT INTO `ad_sort_preview` (`id`, `camp_id`, `app_id`, `country`, `position`, `source`, `current_sort`, `is_ad`, `next_sort`, `last_sort`) VALUES ";
        foreach($appData as $k => $v){
            $sql .= "(0,0,{$v['app_id']},'{$country}',{$position},0,200,0,0,0),";
            echo sprintf('国家[%s]位置[%s]排序不足200位 补加app ->%s <br />',$country,$position,$v['app_id']);
        }
        $InsertSql = $sqlPrefix . rtrim($sql,',') . ';';

        $this->InsertBysql($InsertSql);
        return true;
    }

    public function isPreview()
    {
        $is_preview = TaskStatus::find()->where("name = 'is_preview' ")->asArray()->one();
        $is_preview = $is_preview['value'];
        return $is_preview;
    }

    public function isSortOk($sort,$currentTable)
    {
        $sql = '';
        $str = '';
        $app_id = array_column($sort,'app_id');
        $logstr = sprintf('%s  开始->检查排序 ->开始遍历',"\n" . date('Y-m-d H:i:s',time()));
        foreach($sort as $k => $v)
        {
            if($k <= 1){
                $country = $v['country'];
                $position = $v['position'];
            }
            $index = $k+1;
            //顺序是否完整

            if($v['current_sort'] != $index){
                $str .= "排序错乱修改 国家:[{$v['country']}] 位置:[{$v['position']}] 当前排序[{$v['current_sort']}] 修改为=> {$index} \n\r<br />";
                $sql .= "update ad_sort_preview set current_sort = {$index} where id = {$v['id']};";
            }
            $logstr .= sprintf('%s  开始->检查排序 ->app_id检查',"\n" . date('Y-m-d H:i:s',time()));
            //appid 是否异常
            if($v['app_id'] < 1 ){
                $app = AppData::find()->where(['not in', 'app_id', $app_id])->select('app_id')->asArray()->one();
                $sql .= "update ad_sort_preview set camp_id = 0 ,source = 0 ,is_ad = 0 ,app_id = {$app['app_id']} where id = {$v['id']};";
            }
            $logstr .= sprintf('%s  开始->检查排序 ->app_id检查结束',"\n" . date('Y-m-d H:i:s',time()));
        }
        $logstr .= sprintf('%s  开始->检查排序 ->结束遍历',"\n" . date('Y-m-d H:i:s',time()));
//        echo $str;
        if($sql){
            $this->InsertBysql($sql);
        }
        $logstr .= sprintf('%s  开始->检查排序 ->接入库操作',"\n" . date('Y-m-d H:i:s',time()));
        $delSql = "delete from ad_sort_preview where country = '{$country}'  and current_sort > 200;";
        $logstr .= sprintf('%s  开始->检查排序 ->接入库操作',"\n" . date('Y-m-d H:i:s',time()));
        //$str = sprintf('国家[%s]排序大于200删除操作 <br />',$country);
        //echo $str;
        $this->InsertBysql($delSql);
        Yii::info(date("Y-m-d H:i:s",time()) . $logstr ,'CheckSort');
        return true;

    }

    public function getAdSortByPosition($currentTable,$country,$position)
    {
        $res = AdSortPreview::find()->where("country = '{$country}' and position = {$position}")->orderBy("current_sort asc")->asArray()->all();
        return $res;
    }

    /**
     *  更新排期表 下一小时排序字段
     */
    public function actionUpdateSort()
    {

        //读取当前修改表
        $statusData = TaskStatus::find()
            ->where("name = 'ad_sort_current_table' or name = 'ad_sort_next_time' or name = 'ad_sort_interval' ")
            ->asArray()->all();

        $statusData = array_column($statusData,'value','name');

        $current_table = $statusData['ad_sort_current_table'];

        //同步当前排序到下次排序
        $sql = "update {$current_table} set next_sort = current_sort ;";
        $this->InsertBysql($sql);
        $nextUpdateTime = strtotime($statusData['ad_sort_next_time']);
        //读取任务


        $task = AdSortRecord::find()->where(" is_updated = 0  and start_time <=  '{$statusData['ad_sort_next_time']}'   ")->asArray()->all();

        //修改下次排序
        foreach($task as $k => $v){

            if($v['update_method'] == 1 ) continue;     //即时生效跳过
            if($v['update_method'] == 2  && strtotime($v['start_time']) > $nextUpdateTime) continue;  //生效时间未到 跳过任务


            //查询任务实际位置 存在当前位置已发生变化问题
            if($k >= 1){
                $vCurrent =  $this->getCurrentSortById($current_table,$v['sort_id']);
                if($vCurrent == true) $v['current_sort'] = $vCurrent['current_sort'];
            }
            if(!$v['next_sort']){
                //国家 下次排序空的 跳过
                continue;
            }
            //根据排序方式执行排序
            if($v['sort_method'] == 1){ //自动排序
                $this->autoSort($current_table,$v);
            }else if($v['sort_method'] == 2){  //对换位置
                $this->changeSort($current_table,$v);
            }else if($v['sort_method'] == 3){   //新添加
                $this->addSort($current_table,$v);
            }
        }
        $total = count($task);
        $str = "\n\r本次更新任务[{$total}]条";
        echo $str;
        return $this->success();
        //$str = "更新完成 2秒后跳转    <script language=\"javascript\"> setTimeout(function(){window.history.back(-1);},1500)</script>";


    }

    public function getCurrentSortById($current_table,$id){
        if($current_table == 'ad_sort_one'){
            $data = AdSortOne::find()->where(['id'=> $id])->asArray()->one();
        }else{
            $data = AdSortTwo::find()->where(['id'=> $id])->asArray()->one();
        }
        return $data;

    }
    /**
     * 自动排序
     */
    public function autoSort($current_table,$v)
    {
        $sql = "";
        //前换后 例 2 -> 5 :3到5减1  2调5  //前后相等 添加时过滤禁止
        if($v['current_sort'] < $v['next_sort']){
            //非自身位置修改 前
            $sql .= "UPDATE {$current_table} set next_sort = next_sort - 1
                      where country = '{$v['country']}'
                      and position = {$v['position']}
                      and next_sort <= {$v['next_sort']}
                      and next_sort > {$v['current_sort']} ; ";
            //非自身位置修改 后
            //$sql .= "UPDATE ad_sort_one set current_sort = current_sort + 1  where country = '{$v['country']}' and current_sort > {$v['next_sort']} ; ";
            //自身位置修改
            $sql .= "UPDATE {$current_table} set next_sort = {$v['next_sort']}
                      where country = '{$v['country']}'
                      and position = {$v['position']}
                      and id = {$v['sort_id']}; ";
        }
        //后换前  例子:6->3 : 3到5 + 1  6调3
        if($v['current_sort'] > $v['next_sort']){
            //非自身位置修改
            $sql .= "UPDATE {$current_table} set next_sort = next_sort + 1  where
                    country = '{$v['country']}'
                    and position = {$v['position']}
                    and next_sort >= {$v['next_sort']}
                    and next_sort < {$v['current_sort']} ; ";
            //自身位置修改
            $sql .= "UPDATE {$current_table} set next_sort = {$v['next_sort']}
                    where country = '{$v['country']}'
                    and position = {$v['position']}
                    and id = {$v['sort_id']}; "; //此处存在相同2条记录 需要辨别 每次修改是 sort_id 需要保存且唯一
        }

        if(!empty($sql)){
            $this->InsertBysql($sql);
        }
        return true;
    }

    /**
     * 对换位置排序
     */
    public function changeSort($current_table,$v)
    {
        //前换后  后换前
        //   例: 1->2 :
        //      set next = 2 where next = 1  // 2 2 两个2
        //      set next = 1 where id = _1   // 通过唯一标示 区别两条
        //
        $sql = "UPDATE {$current_table} set next_sort = {$v['current_sort']}
                    where country = '{$v['country']}'
                    and position = {$v['position']}
                    and next_sort = {$v['next_sort']} ;";  //兑换条目id不记录
        $sql .= "UPDATE {$current_table} set next_sort = {$v['next_sort']}
                    where id = {$v['sort_id']}; ";  //修改排序条目 sort_id 修改时记录
        $this->InsertBysql($sql);
        return true;
    }

    /**
     * 排序列表添加
     */
    public function addSort($current_table,$v)
    {
        //自动排序
        $sql = "UPDATE {$current_table} set next_sort = next_sort + 1
                    where country = '{$v['country']}'
                    and position = {$v['position']}
                    and next_sort >= {$v['next_sort']} ;
                DELETE from ad_sort_one where current_sort > 200;";
//        $sql = "INSERT INTO `{$current_table}` VALUES
//                                  (0, {$v['camp_id']}, {$v['app_id']}, '{$v['country']}', {$v['position']}, {$v['source']}, {$v['next_sort']}, {$v['is_ad']}, null,201);";

        $this->InsertBysql($sql);
        return true;
        //兑换 暂未添加
    }

    public function getCategoryByAd($ad)
    {
        $app_ids = array_column($ad,'app_id');
        $data = AppData::find()->where(['app_id'=>$app_ids])->select('app_id,category')->asArray()->all();
        $category = array_column($data,'category','app_id');
        foreach($ad as $k => $v){
            $categoryTemp = isset($category[$v['app_id']]) ? $category[$v['app_id']] : '';
            if(!$categoryTemp){
                Yii::info(date("Y-m-d H:i:s",time()) ." 未查询到app_id:". $v['app_id'] ."\n\r",'initadsort');
            }
            $ad[$k]['category'] = $categoryTemp;
        }
        unset($category);
        return $ad;
    }

    public function actionSortRecord()
    {
       $record = AdSortRecord::find()->where("is_updated = 0 or is_updated = 2 ")->orderBy("create_time desc")->asArray()->all();
        return $this->render('record', [
            'record' => $record
        ]);
    }

    public function actionDelSortRecord()
    {
        $record = AdSortRecord::deleteAll("is_updated = 0 or is_updated = 2 ");
        return $this->success();
    }

    public function actionChangeSource($id)
    {
        if(Yii::$app->request->post()){
            $request = Yii::$app->request->post();
            $camp_id = $request['camp_id'];
            $app_id = $request['app_id'];
            $country = $request['country'];
            $position = $request['position'];
            $source = $request['source'];
            $id = $request['id'];


            if($id != '' ){
                $sql = "update ad_sort_one set camp_id = {$camp_id},app_id ={$app_id},country='{$country}',position = {$position},source={$source},is_ad=1 where id = {$id} ;
                      update ad_sort_two set camp_id = {$camp_id},app_id ={$app_id},country='{$country}',position = {$position},source={$source},is_ad=1 where id = {$id};";
                $res = $this->InsertBysql($sql);
                if($res){
                    $return = array(
                        'code' => 10000,
                        'message' => 'success'
                    );
                }else{
                    $return = array(
                        'code' => 10002,
                        'message' => 'update failed'
                    );
                }
            }else{
                $return = array(
                    'code' => 1003,
                    'message' => 'parameter error'
                );
            }
            echo json_encode($return);exit();
        }
        $model = $this->findModel($id);

        $ad = CountriesApp::find()->where(" app_id = $model->app_id  and country = '{$model->country}'")->select("app_id,camp_id,source,payout_amount,country")->asArray()->all();

        $camp_id = array_column($ad,'camp_id');
        $ad = Bat::find()->where(['camp_id' => $camp_id])->select("mobile_app_id as app_id,camp_id,origin_camp_id,source,payout_amount,payout_currency,acquisition_flow,name,category,countries")->asArray()->all();

        return $this->render('source', [
            'model' => $model,
            'ad' => $ad
        ]);
    }

}
