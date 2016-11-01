<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\BannerList */

$this->title = 'Create Banner List';
$this->params['breadcrumbs'][] = ['label' => 'Banner Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
