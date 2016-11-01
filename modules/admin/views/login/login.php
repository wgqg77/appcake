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
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo Url::base();?>/h-ui/lib/html5.js"></script>
<script type="text/javascript" src="<?php echo Url::base();?>/h-ui/lib/respond.min.js"></script>
<script type="text/javascript" src="<?php echo Url::base();?>/h-ui/lib/PIE_IE678.js"></script>
<![endif]-->
<link href="<?php echo Url::base();?>/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo Url::base();?>/h-ui/css/H-ui.login.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Url::base();?>/h-ui/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Url::base();?>/h-ui/Hui-iconfont/1.0.7/iconfont.css" rel="stylesheet" type="text/css" />
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>后台登录 - 应用管理系统</title>
<meta name="keywords" content="">
<meta name="description" content="">
</head>
<body>
<div class="header"></div>
<div class="loginWraper">
  <div id="loginform" class="loginBox" style="padding-top: 50px;">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{input}",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60d;</i></label>
        <div class="formControls col-xs-8">
<!--          <input id="" name="user_name" type="text" placeholder="账户" class="input-text size-L">-->
          <?= $form->field($model, 'user_name')->textInput(['autofocus' => true,'class' => 'input-text size-L']) ?>
        </div>
      </div>
      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
        <div class="formControls col-xs-8">
<!--          <input id="" name="passwd" type="password" placeholder="密码" class="input-text size-L">-->
          <?= $form->field($model, 'passwd')->passwordInput(['class' => 'input-text size-L']) ?>
        </div>
      </div>
       <div class="formControls col-xs-8 col-xs-offset-3">
        <label for="online" style="color: red;font-size: 13px;padding-left: 20px;">
         <?php echo $error;?>
          </label>
      </div>
      <div class="row cl" style="margin-top: 20px;">
        <div class="formControls col-xs-8 col-xs-offset-3">
          <label for="online" style="color: #fff;font-size: 12px;">
            <?= $form->field($model, 'rememberMe')->checkbox(['template' => "{input}"]) ?>
            记住密码</label>
        </div>
      </div>
      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
          <?= Html::submitButton('&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;', ['class' => 'btn btn-success radius size-L', 'name' => 'login-button']) ?>
          <input name="" type="reset" class="btn btn-default radius size-L" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
        </div>
      </div>

    <?php ActiveForm::end(); ?>
  </div>
</div>
<div class="footer">Copyright @2016 </div>
<script type="text/javascript" src="<?php echo Url::base();?>/h-ui/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo Url::base();?>/h-ui/js/H-ui.js"></script>
<script type="text/javascript">
    if (top.location != self.location){
        top.location=self.location;
    }
</script>
</body>
</html>