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

<div class="horizontalbar-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'country')->dropDownList($country) ?>

    <?= $form->field($model, 'category')->dropDownList([ 'APP' => 'APP', 'Games' => 'Games',  ]) ?>

    <?= $form->field($model, 'app_id')->textInput()->label('APP列表(..id/..)') ?>

    <div class="form-group">
        <?= Html::submitButton( 'Create' , ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
