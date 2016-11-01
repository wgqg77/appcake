<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\Special */

$this->title = '编辑专题: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '专题管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->sid]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="special-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
