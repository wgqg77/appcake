<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\bt\models\BtAppVersion */

$this->title = 'Create Bt App Version';
$this->params['breadcrumbs'][] = ['label' => 'Bt App Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bt-app-version-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
