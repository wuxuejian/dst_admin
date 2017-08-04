<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            //'dsn' => 'mysql:host=localhost;dbname=car_system',
            'dsn' => 'mysql:host=rm-wz9k9ct8av553n1jt.mysql.rds.aliyuncs.com;dbname=car_system', 
            'username' => 'user_carsystem',
            'password' => 'CLY7dzc8WRUQ',
            'charset' => 'utf8',
            'tablePrefix' => 'cs_'
        ],
        'db1' => [
            'class' => 'yii\db\Connection',
            //'dsn' => 'mysql:host=localhost;dbname=car_system',
            'dsn' => 'mysql:host=localhost;dbname=car_monidata', 
            'username' => 'root',
            'password' => '4Z3uChwl',
            'charset' => 'utf8',
            'tablePrefix' => 'cs_'
        ],
       'db2' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=dstzc.wicp.net:13340;dbname=car_monidata', 
            'username' => 'root',
            'password' => 'Szdst20160328&',
            'charset' => 'utf8',
            'tablePrefix' => 'cs_'
        ],
        'db3' => [
        'class' => 'yii\db\Connection',
         'dsn' => 'mysql:host=rm-wz9k9ct8av553n1jt.mysql.rds.aliyuncs.com;dbname=car_system', 
        'username' => 'user_carsystem',
        'password' => 'CLY7dzc8WRUQ',
        'charset' => 'utf8',
        'tablePrefix' => 'cs_'
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
