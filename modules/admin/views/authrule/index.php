<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\AuthRule */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '权限管理';
$this->params['breadcrumbs'][] = $this->title;
//$menuPosition = Yii::$app->params['menuPostion'];


?>
<div class="auth-rule-index">



    <p>
        <?= Html::a('Create Auth Rule', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'title',
            [
                'attribute'=>'type',
                'value' => function($model,$menuPosition){
                    return Yii::$app->params['isShowAsMenu'][$model->status];
                },
            ],
            [
                'attribute'=>'status',
                'value' => function($model,$menuPosition){
                    return Yii::$app->params['menuPostion'][$model->type];
                },
            ],

            // 'condition',
            // 'pid',
            // 'sort',
            // 'create_time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>