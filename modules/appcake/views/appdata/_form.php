<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\AppData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-data-form form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'app_id')->textInput() ?>

    <?= $form->field($model, 'app_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'version')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vendor')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'release_date')->textInput() ?>

    <?php // echo $form->field($model, 'add_date')->textInput() ?>

    <?= $form->field($model, 'size')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'screenshot')->textInput(['maxlength' => true])->label("截图地址:  (以http://开头绝对路径)") ?>

    <?= $form->field($model, 'screenshot2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'screenshot3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'screenshot4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'screenshot5')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ipadscreen1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ipadscreen2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ipadscreen3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ipadscreen4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ipadscreen5')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'support_watch')->textInput() ?>

    <?php // echo $form->field($model, 'watch_icon')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'watch_screen1')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'watch_screen2')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'watch_screen3')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'watch_screen4')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'watch_screen5')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'requirements')->textInput(['maxlength' => true]) ?>



    <?php  echo $form->field($model, 'download')->textInput() ?>

    <?php  echo $form->field($model, 'week_download')->textInput() ?>

    <?php // echo $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'compatible')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'need_backup')->textInput() ?>

    <?php // echo $form->field($model, 'youtube_vid')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'v_poster')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'v_approved')->textInput() ?>

    <?= $form->field($model, 'bundle_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stars')->dropDownList(array(1=>1,2=>2,3=>3,4=>4,5=>5)) ?>

    <?= $form->field($model, 'genres')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'min_os_version')->textInput() ?>

    <?= $form->field($model, 'ipa')->dropDownList(array(1=>"否",0=>'是')) ?>

    <?= $form->field($model, 's3_key')->textInput(['maxlength' => true])->label('下载地址: (格式:app_id-版本.包格式 例如:878658260-1.8.ipa)') ?>

    <?= $form->field($model, 'bt_url')->textInput(['maxlength' => true])->label("BT下载地址") ?>

    <?php // echo $form->field($model, 'app_store_version')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'whatsnew')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="" style="clear: left;"></div>
    <div class="form-group ">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>