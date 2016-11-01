<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\BannerList */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('

    $(function(){
        var imgBaseUrl = "http://dmqxr0cwqie5r.cloudfront.net/banner/";
        var img = $("input[name =\'Bannerlist[img]\']").val();
        var img_5500 = $("input[name =\'Bannerlist[img_5500]\']").val();
        if(img){
            $(\'#img-img\').attr(\'src\',imgBaseUrl + img);
        }
        if(img_5500){
            $(\'#img-img_5500\').attr(\'src\',imgBaseUrl + img_5500);
        }



        $("input[name =\'Bannerlist[img]\']").blur(function(){
            $(\'#img-img\').attr(\'src\',imgBaseUrl + $(this).val());
        })

        $("input[name =\'Bannerlist[img_5500]\']").blur(function(){
            $(\'#img-img_5500\').attr(\'src\',imgBaseUrl + $(this).val());
        })

    })




');
?>

<div class="banner-list-form form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'app_id')->textInput() ?>

    <?= $form->field($model, 'app_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'appstore')->dropDownList(Yii::$app->params['banner_appsotre']) ?>

    <?= $form->field($model, 'rank')->dropDownList(Yii::$app->params['banner_rank']) ?>

    <?= $form->field($model, 'category')->dropDownList(Yii::$app->params['banner_category']) ?>

    <?= $form->field($model, 'compatible')->dropDownList(Yii::$app->params['banner_compatible']) ?>


    <?= $form->field($model, 'begintime')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'autoclose' => true,
            'todayHighlight' => true,
            'format' => 'yyyy-mm-dd HH:ii',
        ]
    ]); ?>


    <?= $form->field($model, 'endtime')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'autoclose' => true,
            'todayHighlight' => true,
            'format' => 'yyyy-mm-dd HH:ii',
        ]
    ]); ?>
    <style>
        .img{
            width: 90%;
            text-align:center;
            margin-left: 2%;
        }
        .img>img{
            width: 40%;
            margin:10px 15px 30px;
            float: left;
            border: 1px solid #ddd;
        }
    </style>
    <div class="img">
        <img src="http://dmqxr0cwqie5r.cloudfront.net/banner/voi750x303.jpg" alt="img预览图" id ="img-img" style="width: 40%;float: left;" alt="">
        <img src="http://dmqxr0cwqie5r.cloudfront.net/banner/voi750x303.jpg" alt="img_5500预览图" id = 'img-img_5500' style="width: 40%;float: left;" alt="">
    </div>

    <div class="clear" style="clear: left;"> </div>
    <?= $form->field($model, 'img')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'img_5500')->textInput(['maxlength' => true]) ?>

    <div class="clear" style="clear: left;"> </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>




