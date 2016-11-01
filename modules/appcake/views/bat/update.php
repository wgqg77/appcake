<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\BatAll */

$this->title = 'Update Bat All: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Bat Alls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'camp_id' => $model->camp_id, 'source' => $model->source]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bat-all-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>