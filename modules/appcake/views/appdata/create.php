<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\AppData */

$this->title = 'Create App Data';
$this->params['breadcrumbs'][] = ['label' => 'App Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
