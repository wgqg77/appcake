<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\Horizontalbar */
/* @var $form yii\widgets\ActiveForm */
$country = Yii::$app->params['country_list_cn'];
foreach($country as $k => $v){
    $country[$k] = $k .'_'.$v;
}
?>

<div class="horizontalbar-form form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'country')->dropDownList($country) ?>



    <?= $form->field($model, 'app_id')->textInput()  ?>

    <?= $form->field($model, 'special_id')->textInput()->label("专题ID") ?>

    <?= $form->field($model, 'img')->textInput(['maxlength' => true])->label("专题图片") ?>

    <?= $form->field($model, 'appstore')->dropDownList(array(1=>1,2=>2)) ?>

    <?= $form->field($model, 'rank')->dropDownList(Yii::$app->params['ho_rank'])->label('排序') ?>


    <div class="form-group">
        <?= Html::submitButton( 'Create' , ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
