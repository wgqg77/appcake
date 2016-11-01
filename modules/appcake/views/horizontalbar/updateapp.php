<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\Horizontalbar */

$this->title = 'Update Horizontalbar: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Horizontalbars', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update  ' . $category;
?>
<div class="horizontalbar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_appform', [
        'model' => $model,
    ]) ?>

</div>
