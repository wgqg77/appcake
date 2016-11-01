<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\search\Required */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="required-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'app_id') ?>

    <?= $form->field($model, 'app_name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'rank') ?>

    <?= $form->field($model, 'category') ?>

    <?php // echo $form->field($model, 'compatible') ?>

    <?php // echo $form->field($model, 'frank') ?>

    <?php // echo $form->field($model, 'size') ?>

    <?php // echo $form->field($model, 'price') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
