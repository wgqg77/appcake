<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\BatAll */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '广告查询';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bat-all-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'camp_id',
            'origin_camp_id',
            'name',
            //'source',
            [
                'attribute' => 'source',
                'value' => function($model){
                    return isset(Yii::$app->params['ad_source'][$model->source]) ? Yii::$app->params['ad_source'][$model->source] : $model->source;
                }
            ],
            //'creatives',
            //'imp_url:url',
            // 'click_url:url',
            // 'click_callback_url:url',
             'mobile_app_id',
             'payout_amount',
             'payout_currency',
             'acquisition_flow',
            // 'icon_gp',
            // 'description',
            // 'rate',
            // 'store_rating',
            // 'installs',
             'category',
            // 'dl_type',
            // 'preload',
            // 'start_time:datetime',
            // 'end_time:datetime',
            // 'nocountries',
             'countries',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>