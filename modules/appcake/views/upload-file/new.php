<?php

$this->params['breadcrumbs'][] = ['label' => '大文件上传方式', 'url' => ['/appcake/upload-file/new']];
$this->params['breadcrumbs'][] = ['label' => '小文件上传方式', 'url' => ['/appcake/upload-file/index']];
$this->title = '文件上传';
$this->params['breadcrumbs'][] = $this->title;
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <title>文件上传</title>
</head>
<body>

<section class="Hui-article-box">

    <div id="iframe_box" class="Hui-article">
        <div class="show_iframe">
            <div style="display:none" class="loading"></div>
            <iframe scrolling="yes" frameborder="0" src="http://106.75.195.240/gmt2/Public/upload/serverPHP/uploadList.php" width="100% height=100%" style="min-height: 600px;"></iframe>
        </div>
    </div>
</section>


</body>
</html>