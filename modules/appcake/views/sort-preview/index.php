<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\AdSortOne */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '广告排序管理';
$this->params['breadcrumbs'][] = $this->title;

$countyrList = Yii::$app->params['country_list_cn'];
$position = Yii::$app->params['ad_sort_position_name'];


$country = array();
foreach($countyrList as $k => $v){
    $country[$k] = $k .'_' . $v ;
}
arsort($country);

$_GET['_data'] = $searchModel;


?>
<style>
    .glyphicon-trash{
        display: none;
    }
</style>
<div class="ad-sort-one-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('更新排序预览', ['/appcake/sort-preview/update-sort'], ['class' => 'btn btn-success']) ?>
        <!--
        <a class="btn btn-success" href="javascript:;" onclick="layerShow('发布排序','<?php echo  Url::to(['/appcake/ad-sort/post-sort']); ?>','800','500')" >发布排序</a>
        -->
        <?= Html::a('排序检查', ['/appcake/ad-sort/check-sort'], ['class' => 'btn btn-success']) ?>  </p>
        <?= Html::a('发布排序', ['/appcake/ad-sort/post-sort'], ['class' => 'btn btn-success']) ?>  </p>
    <style>
        .task-status li{
            float: left;
            margin-left: 15px;
            margin-right: 15px;
        }
        #w0{
            clear: left;
        }
        tr>td:nth-child(2){
            width: 50px;
        }
    </style>
    <div class="task-status">
        <li>当前编辑:<?php echo str_replace('ad_sort_','',$taskStatus['ad_sort_current_table']); ?>表</li>
        <li>前台显示:<?php echo str_replace('ad_sort_','',$taskStatus['ad_sort_next_table']); ?>表</li>
        <li>任务间隔:<?php echo $taskStatus['ad_sort_interval']; ?>分钟</li>
        <li>当前状态:<?php echo $task = $taskStatus['ad_sort_update_lock'] == 0 ? '<span style="color: #999;">[未锁定]</span>' : '<span style="color: red;">[锁定]</span>'; ?></li>
        <li>发布状态:<?php echo $task = $taskStatus['ad_sort_is_post'] == 0 ? '<span style="color: #999;">[未发布]</span>' : '<span style="color: green;">[已发布]</span>'; ?></li>
        <li>下次更新:<?php echo $task = $taskStatus['ad_sort_next_time']; ?></li>
        <li>当前时间:<?php echo date('Y-m-d H:i:s',time()); ?></li>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'id',
                'value' => function($model){
                    #return isset($model->appData->icon) ? $model->appData->icon : '';
                    if(isset($model->appData->icon)){
                        $str = '<img class="app-icon" src="'.$model->appData->icon.'" />';
                    }else{
                        $str = '';
                    }
                    return $str;
                },
                'format'=>'raw',
                'label' => 'icon',
            ],
            'app_id',
            'camp_id',
            [
                'attribute' => 'app_name',
                'value' => function($model){
                    return isset($model->appData->app_name) ? $model->appData->app_name : '';
                },
            ],
            //'country',
            [
                'attribute'=>'country',
                'value'=>function($model){
                    return $model->country;
//                    if(!empty($_GET['_country']->country)){
//                        return isset(Yii::$app->params['country_list_cn'][$_GET['_country']->country]) ? $_GET['_country']->country .'_'.Yii::$app->params['country_list_cn'][$_GET['_country']->country] : $_GET['_country']->country ;
//                    }else{
//                        $_country = '';
//                        foreach($model->countryAd as $k => $v){
//                            $_countryName = isset(Yii::$app->params['country_list_cn'][$v['country']]) ? Yii::$app->params['country_list_cn'][$v['country']] : '';
//                            $_country[] = $v['country'] .'_' .$_countryName;
//                        }
//                        $_country = implode('/',$_country);
//                        return $_country;
//                    }
                },
                'format'=>'html',
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=> $country
            ],
            //'position',
            [
                'attribute' =>'position',
                'value' =>function($model){
                    return Yii::$app->params['ad_sort_position_name'][$model->position];
                },
                'filterType'=>GridView::FILTER_SELECT2,
                'filter' =>$position
            ],
            // 'source',
            [
                'attribute' =>'source',
                'value' =>function($model){
                    return isset(Yii::$app->params['ad_source'][$model->source]) ? Yii::$app->params['ad_source'][$model->source] :  $model->source;
                },
                'filterType'=>GridView::FILTER_SELECT2,
                'filter' =>Yii::$app->params['ad_source']
            ],

             //'is_ad',
            [
                'attribute' =>'is_ad',
                'value' =>function($model){
                    if($model->is_ad == 0){
                        return '非广告';
                    }else{
                        return '广告';
                    }
                },
                'filterType'=>GridView::FILTER_SELECT2,
                'filter' =>array(''=> 'ALL',0 =>'非广告',1=>'广告')
            ],
            'last_sort',
             //'current_sort',
            [
                'attribute' => 'current_sort',
                'value' => function($model){
                    if($model->current_sort != $model->last_sort){
                        return "<span class='change-sort'>" .
                        $model->current_sort . ' </span>';
                    }else{
                        return $model->current_sort;
                    }
                },
                'format'=>'raw',
            ],
             //'next_sort',

//            //'click',
//            [
//                'attribute' => 'click',
//                'value' => function($model){
//                    return isset($model->adSortHistory->click) ? $model->adSortHistory->click : 0;
//                }
//            ],
//            //'down',
//            [
//                'attribute' => 'down',
//                'value' => function($model){
//                    return isset($model->adSortHistory->down) ? $model->adSortHistory->down : 0;
//                }
//            ],
//            //'install',
//            [
//                'attribute' => 'install',
//                'value' => function($model){
//                    return isset($model->adSortHistory->install) ? $model->adSortHistory->install : 0;
//                }
//            ],
            [
                'attribute' => 'price',
                'label' => '单价',
                'value' => function($model){
                    if($model->camp_id == 0) return 0;
                    return isset($model->batAll->payout_amount) ? $model->batAll->payout_amount : 0;
                }
            ],
            [
                'attribute' => 'ad_tpye',
                'label' => '广告形式',
                'value' => function($model){
                    if($model->camp_id == 0) return '';
                    return isset($model->batAll->acquisition_flow) ? $model->batAll->acquisition_flow :'';
                }
            ],
            [
                'attribute' => 'category',
                'label' => '栏目',
                'value' => function($model){
                    return isset($model->appData->category) ? $model->appData->category : '';
                },
            ],
            //['class' => 'yii\grid\ActionColumn'],

        ],
    ]); ?>
</div>

<div class="container" style="margin: 10px auto;font-size: 12px;color: #666;">
    <p>
        注:
    </p>
    <p>
        1.修改排序:兑换位置 影响条目2条
    </p>
    <p>
        2.修改排序:自动排序:
        <br />前换后: 例:2换5 影响位置3-5 位置前移1;
        <br />后换前: 例:5换2 影响位置2-4 位置后移1;
    </p>
    <p>
        3.添加新app自动排序:
        <br />例:位置2添加一条新app  之前2-200后移1
    </p>
    <p>
        4.依据以上规则,按照排序修改记录先后时间生成最终排序列表.
    </p>
</div>