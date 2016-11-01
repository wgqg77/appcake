<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\CakeAdIdfa */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cake Ad Idfas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cake-ad-idfa-index">

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
            'maxCount'=>100000
        ],
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
        ],
        'persistResize'=>false,
        //导出结束
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'app_id',
            'camp_id',
            //'type',

//            [
//                'attribute' => 'type',
//                'value' => function($model) {
//                    return  isset(Yii::$app->params['ad_source'][$model->type]) ? Yii::$app->params['ad_source'][$model->type] : $model->type;
//                }
//            ],
            'date',
             'number',
             'time:datetime',
             //'country_code',
            [
                'attribute' => 'country',
                'value' => function($model) {
                    return  isset(Yii::$app->params['country_list_cn'][$model->country_code]) ? $model->country_code .'-' . Yii::$app->params['country_list_cn'][$model->country_code]   : $model->country_code;
                }
            ],
            'idfa',
            // 'channel',
//            [
//                'attribute' => 'channel',
//                'value' => function($model){
//                    if($model->channel ==  0){
//                        return 'appcake';
//                    }else if($model->channel == 80){
//                        return 'appstore';
//                    }else if($model->channel == 81){
//                        return 'torrent box';
//                    }else{
//                        return $model->channel;
//                    }
//
//                }
//            ]

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
