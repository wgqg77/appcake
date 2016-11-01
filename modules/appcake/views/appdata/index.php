<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\AppData */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'App Datas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-data-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create App Data', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'app_id',
            'app_name',
            'version',
            'category',
            // 'vendor',
            // 'release_date',
             //'add_date',
            [
                'attribute' => 'add_date',
                'value' => function($model){
                    return $date = $model->add_date ?  date('Y-m-d H:i:s',$model->add_date) :  " ";
                }
            ],
             'size',
            // 'icon',
            // 'screenshot',
            // 'screenshot2',
            // 'screenshot3',
            // 'screenshot4',
            // 'screenshot5',
            // 'ipadscreen1',
            // 'ipadscreen2',
            // 'ipadscreen3',
            // 'ipadscreen4',
            // 'ipadscreen5',
            // 'support_watch',
            // 'watch_icon',
            // 'watch_screen1',
            // 'watch_screen2',
            // 'watch_screen3',
            // 'watch_screen4',
            // 'watch_screen5',
            // 'requirements',
            // 'whatsnew:ntext',
            // 'description:ntext',
            // 'download',
            // 'week_download',
            // 'price',
            // 'compatible',
            // 'need_backup',
            // 'youtube_vid',
            // 'v_poster',
            // 'v_approved',
            // 'bundle_id',
             'stars',
            // 'genres',
            // 'min_os_version',
            // 'ipa',
            // 's3_key',
            // 'bt_url:url',
             'app_store_version',

            ['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'app\modules\appcake\components\ActionColumn',
                'template' => '{/appcake/required/create:view}',
                'buttons' => [
                    // 自定义按钮
                    'view' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', '发布'),
                            'aria-label' => Yii::t('yii', '发布'),
                            'data-pjax' => '0',
                        ];
                        $url = $url . "&app_id=". $model->app_id;
                        return Html::a('<span class="glyphicon glyphicon-send"></span>', $url, $options);
                    },
                ]
            ],
        ],
    ]); ?>
</div>