<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bt\models\BtIndexList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bt-index-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'version')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'download_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'md5')->textInput() ?>

    <?= $form->field($model, 'updateinfo')->dropDownList(array(0=>'不强制更新',1=>'强制更新')) ?>

    <?= $form->field($model, 'download_url2')->textInput() ?>

    <?= $form->field($model, 'message')->textarea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
