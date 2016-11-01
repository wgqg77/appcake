<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\Required */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="required-form form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'app_id')->textInput() ?>

    <?= $form->field($model, 'app_name')->textInput() ?>

    <?= $form->field($model, 'description')->textInput() ?>



    <?= $form->field($model, 'category')->textInput() ?>



    <?= $form->field($model, 'size')->textInput() ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'frank')->dropDownList(Yii::$app->params['fank'])->label('售卖位置    (排序级别:1)') ?>

    <?= $form->field($model, 'rank')->dropDownList(Yii::$app->params['banner_rank'])->label('排序 (2默认)  (排序级别:1) ') ?>

    <?= $form->field($model, 'compatible')->dropDownList(Yii::$app->params['banner_compatible'])->label('平台') ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
