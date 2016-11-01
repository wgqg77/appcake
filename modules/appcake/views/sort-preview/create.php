<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\AdSortOne */

$this->title = 'Create Ad Sort One';
$this->params['breadcrumbs'][] = ['label' => 'Ad Sort Ones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-sort-one-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_addform', [
        'model' => $model,
    ]) ?>

</div>
