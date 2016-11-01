<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\AdSortOne */

$this->title = 'Update Ad Sort One: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '广告排序管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ad-sort-one-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
