<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title                   = '用户信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?=Html::encode($this->title)?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?=Html::a('创建用户', ['create'], ['class' => 'btn btn-success'])?>
    </p>
    <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'username',
        //'auth_key',
        // 'password_hash',
        //'password_reset_token',
        // 'email_validate_token:email',
        //'email:email',
        // 'role_level',
        'cellphone',
        'status'   => [
            'label'     => '状态',
            'attribute' => 'status',
            'value'     => function ($model) {
                return ($model->status == 10) ? '激活' : '非激活';
            },
            'filter'    => ['0' => '非激活', '10' => '激活'],
        ],
        // 'avatar',
        // 'created_ip',
        'created_at:datetime',
        'updated_at:datetime',
        'login_ip' => [
            'label'     => '登录城市',
            'attribute' => 'login_ip',
            'value'     => function ($model) {
                $res      = file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=' . long2ip($model->login_ip));
                $citydata = json_decode($res, true);
                return $citydata['city'];
            },
        ],
        [
            'class'    => 'yii\grid\ActionColumn',
            'header'   => '操作',
            'template' => '{assign} {view} {update} {delete}',
            'buttons'  => [
                'assign' => function ($url, $model, $key) {
                    return Html::a('授权', ['assign', 'id' => $model['id']]);
                },
                'update' => function ($url, $model, $key) {
                    return Html::a('更新状态', ['update', 'id' => $model['id']]);
                },
                'view'   => function ($url, $model, $key) {
                    return Html::a('查看', ['view', 'id' => $model['id']]);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a('删除', ['delete', 'id' => $model['id']]);
                },
            ],
        ],
    ],
]);?>
</div>
