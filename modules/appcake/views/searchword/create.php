<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\Searchword */

$this->title = 'Create Searchword';
$this->params['breadcrumbs'][] = ['label' => 'Searchwords', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="searchword-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
