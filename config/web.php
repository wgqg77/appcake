<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');
$cake = require(__DIR__ . '/cake.php');
$ad = require(__DIR__ . '/ad.php');
$weike = require(__DIR__ . '/weike.php');
$config = [
    'id' => 'basic',
    'defaultRoute' => 'admin/login/login',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'JRGt-F501o2z4h-5lLhIid4jufHkO8F_',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'admin/index/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['test'],
                    'logFile' => '@app/runtime/logs/test.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 50,
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error','warning','info'],
                    'categories' => ['CheckSort'],
                    'logFile' => '@app/runtime/logs/CheckSort.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 50,
                ],
            ],
        ],
        'db'    => $db,
        'cake'  => $cake,
        'ad'    => $ad,
        'weike'    => $weike,
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'modules' => [
        'appcakethree' => [
            'class' => 'app\modules\appcakethree\appcakethree',
        ],
        'admin'=>[
            'class'=>'app\modules\admin\admin',
        ],
        'appcake' =>  [
            'class' => 'app\modules\appcake\appcake',
        ],
        'appstore' =>  [
            'class' => 'app\modules\appstore\appstore',
        ],
        'bt' =>  [
            'class' => 'app\modules\bt\bt',
        ],
        'api' =>  [
            'class' => 'app\modules\api\api',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
        ],
        'dynagrid'=> [
            'class'=>'\kartik\dynagrid\Module',
            // other module settings
        ],
    ],
    'params' => $params,
    'language'=>'zh-CN',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}


return $config;
