<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'AppCake版本管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bt-index-list-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'version',
            'download_url:url',
            'message',
            'md5',
            [
                'attribute' => 'updateinfo',
                'value' => function($model){
                    if($model->updateinfo == 0){
                        return '否';
                    }else{
                        return '是';
                    }
                }
            ],
            'download_url2',


            //['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'app\modules\appcake\components\ActionColumn',
                'template' => ' {update:update}',
                'buttons' => [
                    // 自定义按钮
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', '编辑'),
                            'aria-label' => Yii::t('yii', '编辑'),
                            'data-pjax' => '0',
                        ];
                        $url = $url.'&name='. $model->name;
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                    },
                ]
            ],
        ],
    ]); ?>
</div>
