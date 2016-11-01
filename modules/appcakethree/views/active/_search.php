<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\search\CakeAdIdfa */
/* @var $form yii\widgets\ActiveForm */

$country = Yii::$app->params['country_list_cn'];
foreach($country as $k => $v){
    $country[$k] = $k.'_'.$v;
}
$country[''] = '全选';
$country[1] = '1_国家为空';
ksort($country);
?>

<div class="cake-ad-idfa-search">

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


    <label class="control-label" for="downloadweek-app_id">结束时间:</label>
    <?= \kartik\widgets\DatePicker::widget([
        'name'=>'endTime',
        'value'=>isset($_GET['endTime'])?$_GET['endTime']:'',
        "pluginOptions" => array(
            "format" => "yyyy-m-d",
        )
    ])?>







    <?= $form->field($model, 'country_code')->dropDownList($country) ?>


    <?php // echo $form->field($model, 'number') ?>

    <?php // echo $form->field($model, 'time') ?>

    <?php  //echo $form->field($model, 'country_code') ?>

    <?php // echo $form->field($model, 'app_id') ?>

    <?php // echo $form->field($model, 'channel') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
