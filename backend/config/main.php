<?php
$params = array_merge(
    require (__DIR__ . '/../../common/config/params.php'),
    require (__DIR__ . '/../../common/config/params-local.php'),
    require (__DIR__ . '/params.php'),
    require (__DIR__ . '/params-local.php')
);

return [
    'id'                  => 'app-backend',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'language'            => 'zh-CN',
    'bootstrap'           => ['log'],
    'homeUrl'             => '/',
    'modules'             => [],
    'components'          => [
        'request'      => [
            'csrfParam' => '_csrf-backend',
        ],
        'user'         => [
            'identityClass'   => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie'  => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        //url美化还需要在web目录上传.htaccess文件
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
                'user/view/<id:\d+>'                     => 'user/view',
                'login'                                  => 'site/login',
                '<controller:(post|category|user|rbac)>' => '<controller>/index',
            ],
        ],
    ],
    'params'              => $params,
];
