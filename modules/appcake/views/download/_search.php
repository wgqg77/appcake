<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\search\DownloadDone */
/* @var $form yii\widgets\ActiveForm */

$country = Yii::$app->params['country_list_cn'];
foreach($country as $k => $v){
    $country[$k] = $k.'_'.$v;
}

$country[''] = '全选';
ksort($country);

?>

<div class="download-done-search">

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

    <?= $form->field($model, 'isAd')->dropDownList(array(0=>'默认',1=>'广告',2=>'非广告'))->label('类型') ?>

    <?= $form->field($model, 'country')->dropDownList($country)->label('国家') ?>

    <?php // echo $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'device') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'camp_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
