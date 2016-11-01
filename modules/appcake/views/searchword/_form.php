<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\Searchword */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="searchword-form form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'number')->textInput() ?>

    <?= $form->field($model, 'addtime')->dropDownList(Yii::$app->params['banner_rank']) ?>

    <?= $form->field($model, 'category')->dropDownList(Yii::$app->params['banner_category']) ?>

    <?= $form->field($model, 'compatible')->dropDownList(Yii::$app->params['banner_compatible']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
