<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户反馈管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'uid',

            'content:ntext',
            //'contact',
             //'time:datetime',
            [
                'attribute' => 'time',
                'value' => function($model){
                    return date('Y-m-d H:i:s',$model->time);
                }
            ],
             //'reply:ntext',
             //'replytime:datetime',
            'title',
             //'cake_channel',

            //['class' => 'yii\grid\ActionColumn'],

            [
                'class' => 'app\modules\appcake\components\ActionColumn',
                'template' => '{list:view} {update:update} {delete:delete}',
                'buttons' => [
                    // 自定义按钮
                    'view' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', '当前用户所有反馈'),
                            'aria-label' => Yii::t('yii', '当前用户所有反馈'),
                            'data-pjax' => '0',
                        ];
                        $url = $url .'&uid='.$model->uid;
                        return Html::a('<span class="glyphicon glyphicon-book"></span>', $url, $options);
                    },
                ]
            ],
        ],
    ]); ?>
</div>
