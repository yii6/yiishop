<?php
return [
    'language'   => 'zh-CN',
    'timeZone'   => 'Asia/Shanghai',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases'=>[
        '@yuwuy/mailqueue'=>'@vendor/yuwuy/mailqueue/src',
        '@alisms/api_demo'=>'@vendor/alisms/api_demo',
        '@alipay'=>'@vendor/alipay',
    ],
    'components' => [
        'redis'        => [
            'class'    => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port'     => 6379,
            'database' => 0,
        ],
        'cache'        => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => 'localhost',
                'port'     => 6379,
                'database' => 4, //多个网址时更换缓存数据库，否则共用缓存造成混乱
            ],
        ],
        'session'      => [
            'class'     => 'yii\redis\Session',
            'redis'     => [
                'hostname' => 'localhost',
                'port'     => 6379,
                'database' => 3,
            ],
            'keyPrefix' => 'yii6_',
        ],
        'db'           => [
            'class'    => 'yii\db\Connection',
            'dsn'      => 'mysql:host=localhost;dbname=shop',
            'username' => 'shop',
            'password' => '#bsdfU9.',
            'charset'  => 'utf8',
        ],
        'mailer'       => [
            // 'class' => 'yii\swiftmailer\Mailer',
            'class'            => 'yuwuy\mailqueue\MailerQueue',
            'viewPath'         => '@common/mail',
            'db'               => '5',
            'key'              => 'mails',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport'        => [
                'class'    => 'Swift_SmtpTransport',
                'host'     => 'www.yuwuy.com',
                'username' => 'sp@yuwuy.com',
                'password' => 'eW.RfF0!X5oP',
                'port'     => '25',
            ],
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'   => 'yii\log\FileTarget',
                    'levels'  => ['error'],
                    'logFile' => '@app/runtime/logs/error.log',
                ],
                [
                    'class'   => 'yii\log\FileTarget',
                    'levels'  => ['warning'],
                    'logFile' => '@app/runtime/logs/warning.log',
                ],
            ],
        ],
        'authManager'  => [
            'class'           => 'yii\rbac\DbManager',
            'itemTable'       => '{{%auth_item}}',
            'itemChildTable'  => '{{%auth_item_child}}',
            'assignmentTable' => '{{%auth_assignment}}',
            'ruleTable'       => '{{%auth_rule}}',
            'defaultRoles'    => ['default'],
        ],
        'assetManager' => [
            'class'   => 'yii\web\AssetManager',
            'bundles' => [
                'yii\web\JqueryAsset'                => [
                    'js'         => [],
                    'sourcePath' => null,
                ],
                'yii\bootstrap\BootstrapAsset'       => [
                    'css'        => [],
                    'sourcePath' => null,
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js'         => [],
                    'sourcePath' => null,
                ],
            ],
        ],
    ],
];
