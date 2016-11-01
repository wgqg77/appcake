<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\appcake\models\AdCountry;
use app\modules\appcake\models\Bat;
/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\AdSortOne */
/* @var $form yii\widgets\ActiveForm */

$countryAll = AdCountry::find()->where(['status'=>1])->select('country_code,country_name')->asArray()->all();
$country_code = array_column($countryAll,'country_code');

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

<div class="ad-sort-one-form form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'camp_id')->textInput(['disabled' => 'disabled']) ?>

    <?= $form->field($model, 'app_id')->textInput(['disabled' => 'disabled']) ?>

    <?php if(!is_array($model->country)) echo  $form->field($model, 'country')->textInput(['disabled' => 'disabled']) ?>

    <?= $form->field($model, 'position')->textInput(['disabled' => 'disabled']) ?>

    <?= $form->field($model, 'source')->textInput(['disabled' => 'disabled']) ?>

    <?= $form->field($model, 'is_ad')->dropDownList(array(0=>'非广告',1=>'广告'),['disabled' => 'disabled']) ?>

    <?= $form->field($model, 'sort_method')->dropDownList(Yii::$app->params['ad_sort_method']) ?>

    <?= $form->field($model, 'current_sort')->textInput(['disabled' => 'disabled']) ?>

    <?= $form->field($model, 'next_sort')->textInput() ?>

    <?= $form->field($model, 'update_method')->dropDownList(Yii::$app->params['ad_sort_update_method']) ?>

    <?= $form->field($model, 'start_time')->textInput() ?>

    <?= $form->field($model, 'end_time')->textInput() ?>

    <?php
    if(!empty($model->camp_id) && (int)$model->camp_id > 0){
        $campId = $model->camp_id;
        $camp_id_country = Bat::find()->where(['origin_camp_id'=>$campId])->select("countries")->asArray()->one();

        if(!empty($camp_id_country['countries'])){
            $camp_id_country = explode(",",$camp_id_country['countries']);
            $country = array();
            foreach($camp_id_country as $k => $v){
//                if(in_array($v,$country_code)){'
                if(isset( Yii::$app->params['country_list_cn'][$v] )){
                    $country[$v] = $v .'_'.Yii::$app->params['country_list_cn'][$v];
                } else {
                    $country[$v] = $v;
                }
//                }
            }
        }else{
            $country = null;
        }

    }

    //非广告多个国家同步修改 查询排序中那些国家有此app 列出国家



    ?>


    <?php if(isset($country) && $country){?>
    <div class="field-adsortone-country" style="position: relative;margin: 10px auto;">
        <label class="control-label" for="authgroup-title" style="line-height: 45px;height: 45px;">国家:  <span style="font-size: 10px;position:absolute;bottom:20px;left: 200px;top: 15px;color: #666;">(当前排序列表存在可同步修改的国家)</span></label>
        <?= Html::Button('全选', ['class' => 'btn btn-success select-all' ]) ?> <?= Html::Button('反选', ['class' => 'btn btn-success select-no' ]) ?><hr/>
        <?= $form->field($model, 'country')->checkboxList($country)->label('') ?>
        <hr/>
    </div>
    <?php }else{ ?>

        <div class="field-adsortone-country" style="position: relative;margin: 10px auto;">
            <label class="control-label" for="authgroup-title" style="line-height: 45px;height: 45px;">国家:  <span style="font-size: 10px;position:absolute;bottom:20px;left: 200px;top: 15px;color: #666;">(当前排序列表存在可同步修改的国家)</span></label>
            <?= Html::Button('全选', ['class' => 'btn btn-success select-all' ]) ?> <?= Html::Button('反选', ['class' => 'btn btn-success select-no' ]) ?><hr/>
            <div class="form-group field-adsortone-country required" style="color: #666;font-size: 12px;">
                当前无可同步修改国家
            </div>
            <hr/>
        </div>

    <?php }?>








    <div class="container" style="margin: 10px auto;font-size: 12px;color: #666;">
        <p>
            注:
        </p>
        <p>
            1.添加国家/位置/排序重复,后续操作会替换修改之前操作
        </p>
        <p>
            2.自定义排期时间段,不能与现有修改操作任务冲突
        </p>
        <p>
            3.排期任务:开始时间请至少大于当前时间1小时(默认刷新排期时间1小时),或使用[下时段生效]模式;
        </p>
    </div>

    <div class="form-group form-btn">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
