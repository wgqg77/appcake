<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\AuthGroup */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户组管理';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="auth-group-index">



    <p>
        <?= Html::a('创建用户组', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            [
                'attribute'=>'status',
                'value' => function($model){
                    if($model->status == 0){
                        return '启用';
                    }else{
                        return '禁用';
                    }
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
