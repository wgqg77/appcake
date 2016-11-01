<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE HTML>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <LINK rel="Bookmark" href="/favicon.ico" >
    <LINK rel="Shortcut Icon" href="/favicon.ico" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="lib/html5.js"></script>
    <script type="text/javascript" src="lib/respond.min.js"></script>
    <script type="text/javascript" src="lib/PIE_IE678.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="<?php echo Url::base();?>/h-ui/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Url::base();?>/h-ui/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Url::base();?>/h-ui/Hui-iconfont/1.0.7/iconfont.css" />

    <link rel="stylesheet" type="text/css" href="<?php echo Url::base();?>/h-ui/skin/default/skin.css" id="skin" />
    <link rel="stylesheet" type="text/css" href="<?php echo Url::base();?>/h-ui/css/style.css" />
    <!--[if IE 6]>
    <script type="text/javascript" src="lib/DD_belatedPNG_0.0.8a-min.js" ></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <title>管理员列表</title>
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
<div class="page-container">
    <div class="container">
        <?= $content ?>
    </div>
</div>
<script type="text/javascript" src="<?php echo Url::base();?>/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo Url::base();?>/lib/layer/2.1/layer.js"></script>

<script type="text/javascript" src="<?php echo Url::base();?>/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="<?php echo Url::base();?>/h-ui/js/H-ui.admin.js"></script>
<script>
    $(function(){
//        $('table').addClass('table table-border table-bordered table-bg');
        $('.summary').addClass('r').removeClass('summary');
        $('.container').removeClass('container');
    })
</script>
</body>
</html>