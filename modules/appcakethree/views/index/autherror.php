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
<title>404页面</title>
</head>
<body>
<section class="container-fluid page-404 minWP text-c">
  <p class="error-title"><i class="Hui-iconfont va-m" style="font-size:80px">&#xe688;</i><span class="va-m"> 权限不足</span></p>
  <p class="error-description">不好意思，您的权限不足,不能访问~</p>
  <p class="error-info">请联系管理员添加权限 当前方法:
    <?php
      if(isset($_GET['method'])){
        echo $_GET['method'];
      }
    ?>
  </p>
</section>
</body>
</html>