<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\AdSortRecord */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ad-sort-record-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'country')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'camp_id')->textInput() ?>

    <?= $form->field($model, 'app_id')->textInput() ?>

    <?= $form->field($model, 'position')->textInput() ?>

    <?= $form->field($model, 'current_sort')->textInput() ?>

    <?= $form->field($model, 'next_sort')->textInput() ?>

    <?= $form->field($model, 'sort_method')->textInput() ?>

    <?= $form->field($model, 'update_method')->textInput() ?>

    <?= $form->field($model, 'is_updated')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'start_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
