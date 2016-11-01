<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\search\AppData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'app_id') ?>

    <?= $form->field($model, 'app_name') ?>

    <?= $form->field($model, 'version') ?>

    <?= $form->field($model, 'category') ?>

    <?php // echo $form->field($model, 'vendor') ?>

    <?php // echo $form->field($model, 'release_date') ?>

    <?php // echo $form->field($model, 'add_date') ?>

    <?php // echo $form->field($model, 'size') ?>

    <?php // echo $form->field($model, 'icon') ?>

    <?php // echo $form->field($model, 'screenshot') ?>

    <?php // echo $form->field($model, 'screenshot2') ?>

    <?php // echo $form->field($model, 'screenshot3') ?>

    <?php // echo $form->field($model, 'screenshot4') ?>

    <?php // echo $form->field($model, 'screenshot5') ?>

    <?php // echo $form->field($model, 'ipadscreen1') ?>

    <?php // echo $form->field($model, 'ipadscreen2') ?>

    <?php // echo $form->field($model, 'ipadscreen3') ?>

    <?php // echo $form->field($model, 'ipadscreen4') ?>

    <?php // echo $form->field($model, 'ipadscreen5') ?>

    <?php // echo $form->field($model, 'support_watch') ?>

    <?php // echo $form->field($model, 'watch_icon') ?>

    <?php // echo $form->field($model, 'watch_screen1') ?>

    <?php // echo $form->field($model, 'watch_screen2') ?>

    <?php // echo $form->field($model, 'watch_screen3') ?>

    <?php // echo $form->field($model, 'watch_screen4') ?>

    <?php // echo $form->field($model, 'watch_screen5') ?>

    <?php // echo $form->field($model, 'requirements') ?>

    <?php // echo $form->field($model, 'whatsnew') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'download') ?>

    <?php // echo $form->field($model, 'week_download') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'compatible') ?>

    <?php // echo $form->field($model, 'need_backup') ?>

    <?php // echo $form->field($model, 'youtube_vid') ?>

    <?php // echo $form->field($model, 'v_poster') ?>

    <?php // echo $form->field($model, 'v_approved') ?>

    <?php // echo $form->field($model, 'bundle_id') ?>

    <?php // echo $form->field($model, 'stars') ?>

    <?php // echo $form->field($model, 'genres') ?>

    <?php // echo $form->field($model, 'min_os_version') ?>

    <?php // echo $form->field($model, 'ipa') ?>

    <?php // echo $form->field($model, 's3_key') ?>

    <?php // echo $form->field($model, 'bt_url') ?>

    <?php // echo $form->field($model, 'app_store_version') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
