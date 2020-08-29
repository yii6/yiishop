<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
          ],
    ],
    'components' => [
        'authManager' => [
            'class' =>'yii\rbac\DbManager',
            'itemTable' => "{{%auth_item}}",
            'itemChildTable' => "{{%auth_item_child}}",
            'assignmentTable' => "{{%auth_assignment}}",
            'ruleTable' => "{{%auth_rule}}",
        ],
    ],
    'params' => $params,
];
