<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\DataSummary */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="data-summary-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'app_id')->textInput() ?>

    <?= $form->field($model, 'camp_id')->textInput() ?>

    <?= $form->field($model, 'ad_source')->textInput() ?>

    <?= $form->field($model, 'country')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'click')->textInput() ?>

    <?= $form->field($model, 'download')->textInput() ?>

    <?= $form->field($model, 'install')->textInput() ?>

    <?= $form->field($model, 'cake_active')->textInput() ?>

    <?= $form->field($model, 'h_click')->textInput() ?>

    <?= $form->field($model, 'h_active')->textInput() ?>

    <?= $form->field($model, 'analog_click')->textInput() ?>

    <?= $form->field($model, 'acquisition_flow')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payout_amount')->textInput() ?>

    <?= $form->field($model, 'payout_currency')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
