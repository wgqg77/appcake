<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\AdCountry */

$this->title = 'Create Ad Country';
$this->params['breadcrumbs'][] = ['label' => 'Ad Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-country-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
