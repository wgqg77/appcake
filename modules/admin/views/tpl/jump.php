<?php
use yii\helpers\Url;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo Url::base();?>/h-ui/lib/html5.js"></script>
<script type="text/javascript" src="<?php echo Url::base();?>/h-ui/lib/respond.min.js"></script>
<script type="text/javascript" src="<?php echo Url::base();?>/h-ui/lib/PIE_IE678.js"></script>
<![endif]-->
<link href="<?php echo Url::base();?>/h-ui/Hui-iconfont/1.0.7/iconfont.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Url::base();?>/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Url::base();?>/h-ui/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>提示页面</title>
</head>
<body>
<style>
  .va-m{
    font-size: 48px;
  }
</style>
<section class="container-fluid page-404 minWP text-c">
  <?php if(isset($errorMessage)):?>
    <p class="error-title"><i class="Hui-iconfont va-m">&#xe688;</i><span class="va-m">操作出错啦!</span></p>
    <p class="error-description"><?php echo '<p>'.$errorMessage.'</p>';?></p>
  <?php else:?>
    <p class="error-title"><i class="Hui-iconfont va-m" >&#xe6a8;</i><span class="va-m">操作完成!</span></p>
  <?php endif;?>

  <p class="text-muted">该页将在3秒后自动跳转!</p>

  <p>

    <?php if(isset($gotoUrl)):?>

      <a href="<?php echo $gotoUrl?>">立即跳转</a>

    <?php else:?>

      <a href="javascript:void(0)" onclick="history.go(-1)">返回上一页</a>

    <?php endif;?>

  </p>
</section>

<script>

  <?php if(!isset($gotoUrl) || !$gotoUrl ):?>

  setInterval("history.go(-1);",<?php echo $sec;?>000);

  <?php else:?>

  setInterval("window.location.href='<?php echo  $gotoUrl;?>'",<?php echo $sec;?>000);

  <?php endif;?>

</script>
</body>
</html>