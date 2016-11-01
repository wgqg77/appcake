<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\BatAll */

$this->title = 'Create Bat All';
$this->params['breadcrumbs'][] = ['label' => 'Bat Alls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bat-all-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>