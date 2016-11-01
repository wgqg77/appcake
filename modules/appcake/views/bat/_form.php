<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\BatAll */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bat-all-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'camp_id')->textInput() ?>

    <?= $form->field($model, 'origin_camp_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'source')->textInput() ?>

    <?= $form->field($model, 'creatives')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'imp_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'click_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'click_callback_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile_app_id')->textInput() ?>

    <?= $form->field($model, 'payout_amount')->textInput() ?>

    <?= $form->field($model, 'payout_currency')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'acquisition_flow')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icon_gp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rate')->textInput() ?>

    <?= $form->field($model, 'store_rating')->textInput() ?>

    <?= $form->field($model, 'installs')->textInput() ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dl_type')->textInput() ?>

    <?= $form->field($model, 'preload')->textInput() ?>

    <?= $form->field($model, 'start_time')->textInput() ?>

    <?= $form->field($model, 'end_time')->textInput() ?>

    <?= $form->field($model, 'nocountries')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'countries')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>