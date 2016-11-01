<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bt App Versions';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="bt-app-version-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Bt App Version', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'version',
            //'download_url:url',
            //'download_url2:url',
            // 'descript:ntext',
            // 'update_descript:ntext',
            // 'md5',
            [
                'attribute'=>'update_status',
                'value'=>function($model){
                    return Yii::$app->params['mustUpdate'][$model->update_status];
                },
            ],
             'create_time:datetime',
             'update_time:datetime',
            [
                'attribute'=>'file_type',
                'value'=>function($model){
                    return Yii::$app->params['appFileType'][$model->file_type];
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
