<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=appcake.cbhzsxlq4jlh.us-west-2.rds.amazonaws.com;dbname=iphonecake',
    'username' => 'iphonecake',
    'password' => 'iphonecakepass',
    'charset' => 'utf8',

//    // common configuration for slaves
//    'slaveConfig' => [
//        'username' => 'iphonecake',
//        'password' => 'iphonecakepass',
//        'charset' => 'utf8',
//        'attributes' => [
//            // use a smaller connection timeout
//            PDO::ATTR_TIMEOUT => 10,
//        ],
//    ],
//
//    // list of slave configurations
//    'slaves' => [
//        ['dsn' => 'mysql:host=appcake-read.cbhzsxlq4jlh.us-west-2.rds.amazonaws.com;dbname=iphonecake'],
//    ],
];
