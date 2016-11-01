<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\AppVersionError */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'App Version Errors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-version-error-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= Html::a('过滤删除', ['/appcake/app-version-update/clear'], ['class' => 'btn btn-success']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'app_id',
            [
                'attribute'=>'app_name',
                'label' => 'app_name',
                'value' => function($model){
                    return isset($model->appData->app_name) ? $model->appData->app_name : '';
                }
            ],
            //'version',
            [
                'attribute'=>'price',
                'label' => 'price',
                'value' => function($model){
                    return isset($model->appData->price) ? $model->appData->price : '';
                }
            ],
            [
                'attribute'=>'appStore_version',
                'label' => 'version',
                'value' => function($model){
                    return $model->version;
                }
            ],
            [
                'attribute'=>'appStore_version',
                'label' => 'ipa_version',
                'value' => function($model){
                    return isset($model->appData->version) ? $model->appData->version : '';
                }
            ],
            [
                'attribute'=>'appStore_version',
                'label' => 'appStore_version',
                'value' => function($model){
                    return isset($model->appData->app_store_version) ? $model->appData->app_store_version : '';
                }
            ],
            'download',
            //'ext',
             //'top_version',
             //'status',
            [
                'attribute'=>'itunes',
                'label' => 'itunes',
                'value' => function($model){
                    $url = "itmss://itunes.apple.com/us/app/imovie/id{$model->app_id}?mt=8&ign-mpt=uo%3D4";
                    $a = "<a href=\"{$url}\">itunes</a>";
                    return $a;
                },
                'format'=>'raw',
            ],
            //['class' => 'yii\grid\ActionColumn'],

            [
                'class' => 'app\modules\appcake\components\ActionColumn',
                'template' => '{delete:delete}',
            ],


        ],
    ]); ?>
</div>
