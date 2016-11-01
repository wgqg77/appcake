<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AuthGroup */

$this->title = '添加权限';
$this->params['breadcrumbs'][] = ['label' => '权限管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-group-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>