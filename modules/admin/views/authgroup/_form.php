<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\admin\models\AuthRule;
use app\modules\admin\models\AuthGroup;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AuthGroup */
/* @var $form yii\widgets\ActiveForm */

$groupStatus = Yii::$app->params['groupStatus'];

$rulesModel = new AuthRule();
$rules = $rulesModel->getTreeRules();

$userRulesId = array();
$authGropuModle = new AuthGroup();

$userRules = $authGropuModle->getGroupRues($_GET['id']);
$userRulesId = array_column($userRules,'id');

$this->registerJs(

    '$("document").ready(function(){
        $(\'.select-all\').click(function(){
            var pid = $(this).attr(\'data-pid\');
            if(pid){
                $(".pid-"+pid).prop("checked", true);
            }else{
                 $("input").prop("checked", true);
            }

        });

        $(\'.select-no\').click(function(){
             var pid = $(this).attr(\'data-pid\');
            if(pid){
                $(".pid-"+pid).prop("checked", false);
            }else{
                 $("input").prop("checked", false);
            }
        });

    });'

);



?>

<div class="auth-group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList($groupStatus) ?>






    <div class="form-group field-authgroup-title required">
        <label class="control-label" for="authgroup-title" style="line-height: 45px;height: 45px;">权限:  </label> <?= Html::Button('全选', ['class' => 'btn btn-success select-all' ]) ?> <?= Html::Button('反选', ['class' => 'btn btn-success select-no' ]) ?><hr/>
        <div class="">
        <?php
        foreach($rules as $k => $v){
            $selected = '';
            if(in_array($v['id'],$userRulesId)) $selected = 'checked="checked"';
            if($v['pid'] == 0){
                echo "<li style='line-height: 45px;height: 45px;list-style: none;clear: left;'><input name=\"AuthGroup[rules][]\" value=\"{$v['id']}\" type=\"checkbox\" {$selected} class='pid-{$v['id']}' >{$v['id']} {$v['_title']}";
                echo "<button type=\"button\" class=\"btn btn-success select-all\" data-pid=\"{$v['id']}\" style=\"margin-left:20px;\">全选</button> <button type=\"button\" class=\"btn btn-success select-no\" data-pid='{$v['id']}' >反选</button></li>";
            }else{
                echo "<li style='line-height: 45px;height: 45px;list-style: none;float: left;margin-left: 30px;width: 30%;'><input name=\"AuthGroup[rules][]\" value=\"{$v['id']}\" type=\"checkbox\" {$selected} class='pid-{$v['fid']}' >{$v['id']} {$v['_title']}</li>";
            }
        }
        ?>
        </div>
        <div class="help-block"></div>
    </div>

    <div class="form-group" style="clear: left;">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
