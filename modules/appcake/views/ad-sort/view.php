<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\AdSortOne */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ad Sort Ones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-sort-one-view">

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
            'camp_id',
            'app_id',
            'country',
            'position',
            'source',
            'current_sort',
            'is_ad',
            'next_sort',
        ],
    ]) ?>

</div>
