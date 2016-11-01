<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\Horizontalbar */

$this->title = 'Create Horizontalbar';
$this->params['breadcrumbs'][] = ['label' => 'Horizontalbars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="horizontalbar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form-ho2', [
        'model' => $model,
    ]) ?>

</div>
