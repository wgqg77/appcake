<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\DailyStatistics */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="daily-statistics-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'cake_new_user')->textInput() ?>

    <?= $form->field($model, 'cake_active_user')->textInput() ?>

    <?= $form->field($model, 'cake_activation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cake_download')->textInput() ?>

    <?= $form->field($model, 'hook_new_user')->textInput() ?>

    <?= $form->field($model, 'hook_active_user')->textInput() ?>

    <?= $form->field($model, 'hook_download')->textInput() ?>

    <?= $form->field($model, 'hook_ad_no_repeat')->textInput() ?>

    <?= $form->field($model, 'hook_activation')->textInput() ?>

    <?= $form->field($model, 'all_download')->textInput() ?>

    <?= $form->field($model, 'all_activation')->textInput() ?>

    <?= $form->field($model, 'all_income')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'a_price')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
