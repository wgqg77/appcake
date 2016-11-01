<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\AdSortRecord */

$this->title = 'Create Ad Sort Record';
$this->params['breadcrumbs'][] = ['label' => 'Ad Sort Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-sort-record-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
