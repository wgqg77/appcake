<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\bt\models\BtIndexList */

$this->title = 'Create Bt Index List';
$this->params['breadcrumbs'][] = ['label' => 'Bt Index Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bt-index-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
