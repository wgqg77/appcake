<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\Required */

$this->title = $model->app_id;
$this->params['breadcrumbs'][] = ['label' => 'Requireds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="required-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->app_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->app_id], [
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
            'app_id',
            'app_name',
            'description',
            'rank',
            'category',
            'compatible',
            'frank',
            'size',
            'price',
        ],
    ]) ?>

</div>
