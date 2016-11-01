<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\search\AdSortOne */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ad-sort-one-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'camp_id') ?>

    <?= $form->field($model, 'app_id') ?>

    <?= $form->field($model, 'country') ?>

    <?= $form->field($model, 'position') ?>

    <?php // echo $form->field($model, 'source') ?>

    <?php // echo $form->field($model, 'current_sort') ?>

    <?php // echo $form->field($model, 'is_ad') ?>

    <?php // echo $form->field($model, 'next_sort') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
