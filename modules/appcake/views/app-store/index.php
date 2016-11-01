<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\DownloadDone */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'appStroe下载top';
$this->params['breadcrumbs'][] = $this->title;

$GLOBALS['appName'] = $appName;
$GLOBALS['appCategory'] = $appCategory;
$GLOBALS['adAppId'] = $adAppId;
?>
<div class="download-done-index">
    <div class="form-inline">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>

    <?= GridView::widget([
        //导出开始
        'pjax'=>true, // pjax is set to always true for this demo
        // set your toolbar
        'toolbar'=> [

            '{export}',
            '{toggleData}',
        ],
        'export'=>[
            'fontAwesome'=>true
        ],
        'toggleDataOptions'=>[
            'maxCount'=>10000
        ],



        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
        ],
        'persistResize'=>false,
        //导出结束
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' =>'id',
                'value' => function($model,$k){
                    return $k + 1;
                }
            ],
            'app_id',
            [
                'attribute' => 'app_name',
                'value'     => function($model){
                    $name = isset($GLOBALS['appName'][$model['app_id']]) ? $GLOBALS['appName'][$model['app_id']] : "<a href=\"itmss://itunes.apple.com/us/app/imovie/id{$model['app_id']}?mt=8&amp;ign-mpt=uo%3D4\" style='color: red;font-size: 16px;font-weight: 700;'>iTunes</a>";
                    return $name;
                },
                'format' => 'raw',
            ],

            [
                'attribute' =>'camp_id',
                'label' => '是否广告',
                'value' => function($model){
                    $isAd = isset($GLOBALS['adAppId'][$model['app_id']]) ? '广告' : '';
                    return $isAd;
                }
            ],
            [
                'attribute' => 'download',
                'label' => '下载量',
                'value' => function($model) {
                    return  $model['download'];
                }
            ],

            [
                'attribute' =>'category',
                'label' => 'category',
                'value' => function($model){
                    $name = isset($GLOBALS['appCategory'][$model['app_id']]) ? $GLOBALS['appCategory'][$model['app_id']] : '(库中暂无此app)';
                    return $name;
                }
            ],
        ],
    ]); ?>
</div>
