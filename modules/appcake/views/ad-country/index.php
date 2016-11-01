<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\AdCountry */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ad Countries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-country-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>-->
<!--        --><?php //echo  Html::a('Create Ad Country', ['create'], ['class' => 'btn btn-success']) ?>
<!--    </p>-->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'country_name',
            'country_code',
            'create_time',
            //'status',
            [
                'attribute' => 'status',
                'value' => function($model){
                    if($model->status == 0){
                        return '未启用';
                    }else{
                        return '启用';
                    }
                },
                'filter' =>  array(0=>'未启用',1=>'启用')
            ],

             'day_active_number',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
