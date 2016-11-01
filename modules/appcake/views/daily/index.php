<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\DailyStatistics */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '每日汇总数据';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="daily-statistics-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="form-inline">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'date',
            'cake_new_user',
            'cake_active_user',
            'cake_activation',
             'cake_download',
             'cake_active_down',
             'hook_new_user',
             'hook_active_user',
             'hook_download',
             'hook_ad_no_repeat',
             'hook_activation',
             'all_download',
             'all_activation',
             'all_income',
             'a_price',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
