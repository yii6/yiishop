<?php
$params = array_merge(
    require (__DIR__ . '/../../common/config/params.php'),
    require (__DIR__ . '/../../common/config/params-local.php'),
    require (__DIR__ . '/params.php'),
    require (__DIR__ . '/params-local.php')
);

return [
    'id'                  => 'app-frontend',
    'basePath'            => dirname(__DIR__),
    'language'            => 'zh-CN',
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'homeUrl'             => '/',
    'components'          => [
        'request'      => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl'   => '',
        ],
        'user'         => [
            'identityClass'   => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie'  => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
                'signup'       => 'site/signup',
                'login'        => 'site/login',
                '<controller>' => '<controller>/index',
            ],
        ],
        'i18n'         => [
            'translations' => [
                '*' => [
                    'class'   => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'common' => 'common.php',
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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
    'params'              => $params,
];
