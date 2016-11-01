<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\Required */

$this->title = 'Create Required';
$this->params['breadcrumbs'][] = ['label' => 'Requireds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="required-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_createform', [
        'model' => $model,
    ]) ?>

</div>
