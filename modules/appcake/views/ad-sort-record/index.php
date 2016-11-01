<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\appcake\models\search\AdSortRecord */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ad Sort Records';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-sort-record-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ad Sort Record', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'country',
            'camp_id',
            'app_id',
            'position',
            // 'ad_sort_id',
            // 'current_sort',
            // 'next_sort',
            // 'sort_method',
            // 'update_method',
            // 'is_updated',
            // 'current_table',
            // 'create_time',
            // 'start_time',
            // 'other_country',
            // 'other_camp_id',
            // 'other_app_id',
            // 'other_position',
            // 'other_ad_sort_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
