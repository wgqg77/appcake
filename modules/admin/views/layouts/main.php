<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        form>.form-group{
            display: block;
        }
        h1{
            display: none;
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>
<nav class="breadcrumb">
    <i class="Hui-iconfont" >&#xe67f;</i>
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'tag'   => 'span',
        'class' => '',
        'options' => [
            'class' => ''
        ],
        'itemTemplate' => '<span class="c-gray en">&gt;</span> {link}',
        'activeItemTemplate' => '<span class="c-gray en">&gt;</span> {link}',
        'homeLink' => [
            'label' => '首页',
            'url'   => Url::toRoute('/admin/index/welcome',true),
            'template' => '{link}',
        ]
    ]) ?>
    <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
</nav>
<div class="wrap">


    <div class="container">
        <?= $content ?>
    </div>
</div>



<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
