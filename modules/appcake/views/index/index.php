<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/favicon.ico" >
<LINK rel="Shortcut Icon" href="/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo Url::base();?>/h-ui/lib/html5.js"></script>
<script type="text/javascript" src="<?php echo Url::base();?>/h-ui/lib/respond.min.js"></script>
<script type="text/javascript" src="<?php echo Url::base();?>/h-ui/lib/PIE_IE678.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo Url::base();?>/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Url::base();?>/h-ui/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Url::base();?>/h-ui/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Url::base();?>/h-ui/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Url::base();?>/h-ui/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="<?php echo Url::base();?>/h-ui/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>APP管理系统</title>
<meta name="keywords" content="">
<meta name="description" content="">
</head>
<body>

<?php echo $this->render('@app/modules/admin/views/index/head'); ?>
<?php echo $this->render('@app/modules/admin/views/index/default_cake_left.php'); ?>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<section class="Hui-article-box">
	<div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
		<div class="Hui-tabNav-wp">
			<ul id="min_title_list" class="acrossTab cl">
				<li class="active"><span title="appcake Home" data-href="welcome.html">appcake Home</span><em></em></li>
			</ul>
		</div>
		<div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a><a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d7;</i></a></div>
	</div>
	<div id="iframe_box" class="Hui-article">
		<div class="show_iframe">
			<div style="display:none" class="loading"></div>
			<iframe scrolling="yes" frameborder="0" name="main" src="<?php echo Url::toRoute('index/welcome',true); ?>"></iframe>
		</div>
	</div>
</section>
<script type="text/javascript" src="<?php echo Url::base();?>/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo Url::base();?>/lib/layer/2.1/layer.js"></script>
<script type="text/javascript" src="<?php echo Url::base();?>/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="<?php echo Url::base();?>/h-ui/js/H-ui.admin.js"></script>


</body>
</html>