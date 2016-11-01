<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\appcake\models\search\AppData;



$app_id = $_GET['app_id'];
$appData = AppData::find()->where(['app_id' => $app_id])->select(' `app_id`,`app_name`,`category`,`compatible`,`download`,`add_date`,`description`,`size`,`price` ')->asArray()->one();


?>

<div class="required-form form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'app_id')->textInput(['value' => $appData['app_id']]) ?>

    <?= $form->field($model, 'app_name')->textInput(['value' => $appData['app_name']]) ?>

    <?= $form->field($model, 'description')->textInput(['value' => $appData['description']]) ?>



    <?= $form->field($model, 'category')->textInput(['value' => $appData['category']]) ?>



    <?= $form->field($model, 'size')->textInput(['value' => $appData['size']]) ?>

    <?= $form->field($model, 'price')->textInput(['value' => $appData['price']]) ?>

    <?= $form->field($model, 'frank')->dropDownList(Yii::$app->params['fank'])->label('售卖位置    (排序级别:1)') ?>

    <?= $form->field($model, 'rank')->dropDownList(Yii::$app->params['banner_rank'])->label('排序 (2默认)  (排序级别:1) ')  ?>

    <?= $form->field($model, 'compatible')->dropDownList(Yii::$app->params['banner_compatible'])->label('平台') ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
