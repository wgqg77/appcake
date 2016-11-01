<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\AdCountry */

$this->title = 'Update Ad Country: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ad Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ad-country-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
