<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\search\AdSortRecord */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ad-sort-record-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'country') ?>

    <?= $form->field($model, 'camp_id') ?>

    <?= $form->field($model, 'app_id') ?>

    <?= $form->field($model, 'position') ?>

    <?php // echo $form->field($model, 'ad_sort_id') ?>

    <?php // echo $form->field($model, 'current_sort') ?>

    <?php // echo $form->field($model, 'next_sort') ?>

    <?php // echo $form->field($model, 'sort_method') ?>

    <?php // echo $form->field($model, 'update_method') ?>

    <?php // echo $form->field($model, 'is_updated') ?>

    <?php // echo $form->field($model, 'current_table') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'start_time') ?>

    <?php // echo $form->field($model, 'other_country') ?>

    <?php // echo $form->field($model, 'other_camp_id') ?>

    <?php // echo $form->field($model, 'other_app_id') ?>

    <?php // echo $form->field($model, 'other_position') ?>

    <?php // echo $form->field($model, 'other_ad_sort_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
