<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'h-ui/css/H-ui.min.css',
        'h-ui/css/H-ui.login.css',
        'h-ui/css/style.css',
        'h-ui/Hui-iconfont/1.0.7/iconfont.css',
    ];
    public $js = [
        'js/common.js',
        'lib/layer/2.1/layer.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
