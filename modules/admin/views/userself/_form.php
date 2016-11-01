<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group field-admin-user_name required has-success">
        <label class="control-label" for="admin-user_name">用户名</label>
        <div class="form-control"><?php echo Yii::$app->session['user_name']; ?></div>
        <input id="admin-user_name" class="form-control" name="Admin[user_name]" value="<?php echo Yii::$app->session['user_name']; ?>" maxlength="50" type="hidden">

        <div class="help-block"></div>
    </div>

    <div class="form-group field-admin-passwd required">
        <label class="control-label" for="admin-passwd">密码</label>
        <input id="admin-passwd" class="form-control" name="Admin[passwd]" value="" type="password">

        <div class="help-block"><?php if(!empty($_POST)){ echo '密码不能为空';};?></div>
    </div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
