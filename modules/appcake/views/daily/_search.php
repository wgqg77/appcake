<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\search\DailyStatistics */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="data-summary-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


    <label class="control-label" for="downloadweek-app_id">开始时间:</label>

    <?= \kartik\widgets\DatePicker::widget([
        'value' => isset($_GET['startTime'])?$_GET['startTime']:'',
        'name'=>'startTime',
        "pluginOptions" => array(
            "format" => "yyyy-m-d",
        )


    ])?>


    <label class="control-label" for="datasummary-app_id">结束时间:</label>
    <?= \kartik\widgets\DatePicker::widget([
        'name'=>'endTime',
        'value'=>isset($_GET['endTime'])?$_GET['endTime']:'',
        "pluginOptions" => array(
            "format" => "yyyy-m-d",
        )


    ])?>


    <?php // echo $form->field($model, 'cake_download') ?>

    <?php // echo $form->field($model, 'cake_active_down') ?>

    <?php // echo $form->field($model, 'hook_new_user') ?>

    <?php // echo $form->field($model, 'hook_active_user') ?>

    <?php // echo $form->field($model, 'hook_download') ?>

    <?php // echo $form->field($model, 'hook_ad_no_repeat') ?>

    <?php // echo $form->field($model, 'hook_activation') ?>

    <?php // echo $form->field($model, 'all_download') ?>

    <?php // echo $form->field($model, 'all_activation') ?>

    <?php // echo $form->field($model, 'all_income') ?>

    <?php // echo $form->field($model, 'a_price') ?>

    <div class="form-group form">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
