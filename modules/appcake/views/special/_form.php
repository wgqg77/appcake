<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\Special */
/* @var $form yii\widgets\ActiveForm */



$this->registerJs('

    $(function(){
        var imgBaseUrl = "http://dmqxr0cwqie5r.cloudfront.net/banner/";
        var img = $("input[name =\'Special[img]\']").val();
        var img_5500 = $("input[name =\'Special[img_5500]\']").val();
        if(img){
            $(\'#img-img\').attr(\'src\',imgBaseUrl + img);
        }
        if(img_5500){
            $(\'#img-img_5500\').attr(\'src\',imgBaseUrl + img_5500);
        }



        $("input[name =\'Special[img]\']").blur(function(){
            $(\'#img-img\').attr(\'src\',imgBaseUrl + $(this).val());
        })

        $("input[name =\'Special[img_5500]\']").blur(function(){
            $(\'#img-img_5500\').attr(\'src\',imgBaseUrl + $(this).val());
        })

    })




');

?>

<div class="special-form form">



    <?php $form = ActiveForm::begin(); ?>

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

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>



    <?= $form->field($model, 'arr_appid')->textInput(['maxlength' => true])->label('APP列表(..id/..)') ?>

    <?= $form->field($model, 'category')->dropDownList(array('Games'=>'Games','other'=>'应用')) ?>

    <?= $form->field($model, 'compatible')->dropDownList(Yii::$app->params['banner_compatible']) ?>



    <?= $form->field($model, 'img')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'img_5500')->textInput(['maxlength' => true])->label('5.5.0.0版本以上图片') ?>

    <?= $form->field($model, 'addtime')->dropDownList(Yii::$app->params['banner_rank'])->label('排序') ?>

    <div class="clear" style="clear: left;"> </div>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="clear" style="clear: left;"> </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
