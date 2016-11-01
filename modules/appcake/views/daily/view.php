<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\DailyStatistics */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Daily Statistics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="daily-statistics-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
        ],
    ]) ?>

</div>
