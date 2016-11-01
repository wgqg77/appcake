<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\AdSortRecord */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ad Sort Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-sort-record-view">

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
            'country',
            'camp_id',
            'app_id',
            'position',
            'current_sort',
            'next_sort',
            'sort_method',
            'update_method',
            'is_updated',
            'create_time',
            'start_time',
            'end_time'
        ],
    ]) ?>

</div>
