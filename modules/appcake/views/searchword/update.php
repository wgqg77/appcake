<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\Searchword */

$this->title = 'Update Searchword: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Searchwords', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="searchword-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
