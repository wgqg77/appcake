<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\bt\models\BtIndexList */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Bt Index Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$isShow = Yii::$app->params['isShow'];
?>
<div class="bt-index-list-view">

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
            'url:url',
            'ico_url:url',
            'title',
            'describe',
            [
                'label' => '显示状态',
                'value' => $isShow[$model->is_show]
            ],
            'sort',
        ],
    ]) ?>

</div>
