<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\search\BatAll */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bat-all-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'camp_id') ?>

    <?= $form->field($model, 'origin_camp_id') ?>

    <?= $form->field($model, 'source') ?>

    <?= $form->field($model, 'creatives') ?>

    <?= $form->field($model, 'imp_url') ?>

    <?php // echo $form->field($model, 'click_url') ?>

    <?php // echo $form->field($model, 'click_callback_url') ?>

    <?php // echo $form->field($model, 'mobile_app_id') ?>

    <?php // echo $form->field($model, 'payout_amount') ?>

    <?php // echo $form->field($model, 'payout_currency') ?>

    <?php // echo $form->field($model, 'acquisition_flow') ?>

    <?php // echo $form->field($model, 'icon_gp') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'rate') ?>

    <?php // echo $form->field($model, 'store_rating') ?>

    <?php // echo $form->field($model, 'installs') ?>

    <?php // echo $form->field($model, 'category') ?>

    <?php // echo $form->field($model, 'dl_type') ?>

    <?php // echo $form->field($model, 'preload') ?>

    <?php // echo $form->field($model, 'start_time') ?>

    <?php // echo $form->field($model, 'end_time') ?>

    <?php // echo $form->field($model, 'nocountries') ?>

    <?php // echo $form->field($model, 'countries') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>