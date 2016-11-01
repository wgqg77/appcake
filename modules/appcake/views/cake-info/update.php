<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bt\models\BtIndexList */
$this->title = '编辑: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'AppCake版本管理', 'url' => ['index']];

$this->params['breadcrumbs'][] = '编辑';
?>
<div class="bt-index-list-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
