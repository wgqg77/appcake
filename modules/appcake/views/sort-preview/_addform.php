<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\modules\appcake\models\AdCountry;
/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\AdSortOne */
/* @var $form yii\widgets\ActiveForm */

$source = Yii::$app->params['ad_source'];
unset($source['']);
$countryAll = AdCountry::find()->where(['status'=>1])->select('country_code,country_name')->asArray()->all();

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
<style>
    #w0 >.field-adsortone-country{
        clear: left;
        width: 95%;
        text-align: left;
        margin: 10px auto;
    }
</style>
<div class="ad-sort-one-form form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'is_ad')->dropDownList(Yii::$app->params['is_ad']) ?>

    <?= $form->field($model, 'camp_id')->textInput() ?>

    <?= $form->field($model, 'app_id')->textInput() ?>



    <?= $form->field($model, 'position')->dropDownList(Yii::$app->params['ad_sort_position_name']) ?>

    <?= $form->field($model, 'source')->dropDownList($source) ?>



    <?= $form->field($model, 'sort_method')->dropDownList(array( 3 => '添加广告->自动排序')) ?>


    <?= $form->field($model, 'next_sort')->textInput()->label('排序位置') ?>

    <?= $form->field($model, 'update_method')->dropDownList(Yii::$app->params['ad_sort_update_method']) ?>

    <?= $form->field($model, 'start_time')->textInput() ?>

    <?= $form->field($model, 'end_time')->textInput() ?>


    <div class="field-adsortone-country">
        <label class="control-label" for="authgroup-title" style="line-height: 45px;height: 45px;">当前排序有效国家:  </label>
    <?= Html::Button('全选', ['class' => 'btn btn-success select-all' ]) ?> <?= Html::Button('反选', ['class' => 'btn btn-success select-no' ]) ?><hr/>
    <?= $form->field($model, 'country')->checkboxList(ArrayHelper::map($countryAll, 'country_code', 'country_name'))->label('') ?>
        <hr/>
    </div>







    <div class="container" style="margin: 10px auto;font-size: 12px;color: #666;">
        <p>
            注:
        </p>
        <p>
            1.修改国家/位置/排序app已存任务列表,后续操作会替换修改之前操作
        </p>
        <p>
            2.自定义排期时间段,不能与现有修改操作任务冲突
        </p>

    </div>

    <div class="form-group form-btn">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
