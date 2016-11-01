<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$isShow = Yii::$app->params['isShow'];
/* @var $this yii\web\View */
/* @var $model app\modules\bt\models\BtIndexList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bt-index-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ico_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'describe')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_show')->dropDownList($isShow) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
