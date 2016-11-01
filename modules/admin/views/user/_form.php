<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\admin\models\AuthGroup;
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Admin */
/* @var $form yii\widgets\ActiveForm */

$group = AuthGroup::find()->select(array('id','title'))->asArray()->all();

$data = array();
if(!empty($group)){
    foreach($group as $k => $v){
        $data[$v['id']] = $v['title'];
    }
}
?>

<div class="admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>


    <div class="form-group field-admin-passwd required">
        <label class="control-label" for="admin-passwd">密码</label>
        <input id="admin-passwd" class="form-control" name="Admin[passwd]" value="" type="password">

        <div class="help-block"></div>
    </div>


    <?= $form->field($model, 'group')->listBox($data) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
