<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\AdSortRecord */

$this->title = 'Update Ad Sort Record: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ad Sort Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ad-sort-record-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
