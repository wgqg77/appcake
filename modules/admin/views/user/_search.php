<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\search\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'user_name') ?>

    <?= $form->field($model, 'passwd') ?>

    <?= $form->field($model, 'sex') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'create_ip') ?>

    <?php // echo $form->field($model, 'login_time') ?>

    <?php // echo $form->field($model, 'login_ip') ?>

    <?php // echo $form->field($model, 'safe_question') ?>

    <?php // echo $form->field($model, 'safe_answer') ?>

    <?php // echo $form->field($model, 'face') ?>

    <?php // echo $form->field($model, 'scores') ?>

    <?php // echo $form->field($model, 'leave') ?>

    <?php // echo $form->field($model, 'coin') ?>

    <?php // echo $form->field($model, 'is_lock') ?>

    <?php // echo $form->field($model, 'is_admin') ?>

    <?php // echo $form->field($model, 'nick_name') ?>

    <?php // echo $form->field($model, 'collect') ?>

    <?php // echo $form->field($model, 'login_count') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
