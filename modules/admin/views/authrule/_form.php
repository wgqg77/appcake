<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\admin\models\AuthRule;
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AuthRule */
/* @var $form yii\widgets\ActiveForm */
$father = AuthRule::find()->select(array('id','name','title','pid'))->asArray()->all();

$isShow = Yii::$app->params['isShowAsMenu'];

$data = array();
if(!empty($father)){
    foreach($father as $k => $v){
        $data[$v['id']] = $v['title'];
    }
}
$data[0]='请选择';
ksort($data);

$menuPosition = Yii::$app->params['menuPostion'];

?>

<div class="auth-rule-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->listBox($isShow) ?>

    <?= $form->field($model, 'type')->listBox($menuPosition) ?>

    <?= $form->field($model, 'pid')->listBox($data) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
