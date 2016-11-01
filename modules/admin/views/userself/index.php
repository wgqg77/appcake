<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Admins';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Admin', ['update'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'uid',
            'user_name',
            //'passwd',
            //'sex',
            //'email:email',
            // 'phone',
             'create_time:datetime',
             'create_ip',
             'login_time:datetime',
             'login_ip',
            // 'safe_question',
            // 'safe_answer',
            // 'face',
            // 'scores',
             'group',
            // 'coin',
             'is_lock',
            // 'is_admin',
            // 'nick_name',
            // 'collect',
             'login_count',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
