<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$updaStatus = Yii::$app->params['mustUpdate'];
$appFileType = Yii::$app->params['appFileType'];
/* @var $this yii\web\View */
/* @var $model app\modules\bt\models\BtAppVersion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bt-app-version-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'version')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'download_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'download_url2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descript')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'update_descript')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'md5')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'update_status')->dropDownList($updaStatus) ?>

    <?= $form->field($model, 'file_type')->dropDownList($appFileType) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
