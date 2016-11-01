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
            //'iTunes',
            [
                'attribute' => 'mobile_app_id',
                'format' => 'raw',
                'value' => function($model){
                    return   '<a href="itmss://itunes.apple.com/us/app/imovie/id'.$model->mobile_app_id.'?mt=8&amp;ign-mpt=uo%3D4">iTunes</a>';
                },
                'label' => 'iTunes'
            ],
             'mobile_app_id',
             'payout_amount',
             //'payout_currency',
             //'acquisition_flow',
            // 'icon_gp',
            // 'description',
            // 'rate',
            // 'store_rating',
            // 'installs',
             'category',
            //'is_update',
            [
                'attribute' => 'is_update',
                'value' => function($model){
                    switch ($model->is_update){
                        case  0 :
                            return '已有';
                            break;
                        case  1 :
                            return '需更新';
                            break;
                        case  2 :
                            return '需添加';
                            break;
                        case 3  :
                            return '版本异常';
                            break;
                        default :
                            return 'unkonw';
                    }
                },
                'filter' => array(0=>'已有',1=>'需更新',2=>'需添加',3=>'版本异常')
            ],
            [
                'attribute' => 'appstore_version',
                'label' => '库中版本',
                'value' => function($model){
                    if(isset($model->appData->version)){
                        return $model->appData->version;
                    }else{
                        return '';
                    }

                }
            ],
            'appstore_version',
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