<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\appcake\models\Horizontalbar */

$this->title = '添加(top/app/games)';
$this->params['breadcrumbs'][] = ['label' => '水平滚动栏管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="horizontalbar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form-ho', [
        'model' => $model,
    ]) ?>

</div>