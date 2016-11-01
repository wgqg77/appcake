<?php


$source = file_get_contents('http://mgmt.ad.app-cake.com/index.php/ad/source');
$source = json_decode($source,true);
$source['不限制'] = '';
$updateTime = time();
$source = json_encode($source);
$str = sprintf('<?php %s$updateTime=%s;%s$source=\'%s\';%s',"\n",$updateTime,"\n",$source,"\n");
file_put_contents( SITE_PATH .'config/paramsAdSource.php',$str);





