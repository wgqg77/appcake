<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\BannerList */

$this->title = 'Update Banner List: ' . $model->app_id;
$this->params['breadcrumbs'][] = ['label' => 'Banner Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->app_id, 'url' => ['view', 'id' => $model->app_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="banner-list-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
