<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Bt Index Lists';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bt-index-list-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Bt Index List', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'url:url',
            'ico_url:url',
            'title',
            'describe',
            [
                'attribute'=>'显示状态',
                'value'=>function($model){
                    return Yii::$app->params['isShow'][$model->is_show];
                },
            ],
             'sort',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
