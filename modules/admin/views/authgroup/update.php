<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AuthGroup */

$this->title = 'Update Auth Group: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Auth Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="auth-group-update">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
