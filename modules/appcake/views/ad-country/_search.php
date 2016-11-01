<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\search\AdCountry */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ad-country-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'country_name') ?>

    <?= $form->field($model, 'country_code') ?>

    <?= $form->field($model, 'create_time') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'day_active_number') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
