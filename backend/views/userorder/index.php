<?php

use frontend\models\Cart;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = '用户订单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-order-index">
    <h1><?=Html::encode($this->title)?></h1>
    <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],
        //'id',
        'order_no',
        'cart_ids'   => [
            'attribute' => 'cart_ids',
            'value'     => function ($model) {
                $cart_ids     = json_decode($model->cart_ids);
                $product_name = [];
                foreach ($cart_ids as $key => $value) {
                    $cart           = Cart::findOne($value);
                    $product_name[] = $cart->product_name . '*' . $cart->amount;
                }
                return implode('、', $product_name);
            },
        ],
        'total',
        'created_at' => [
            'attribute' => 'created_at',
            'value'     => function ($model) {
                return date('Y-m-d H:i', $model->created_at);
            },
        ],
        //'user_id',
        'paid'       => [
            'label'     => '订单状态',
            'attribute' => 'paid',
            'value'     => function ($model) {
                if ($model->paid == 0) {
                    return "未付款";
                } else if ($model->paid == 1) {
                    return "未发货";
                } else if ($model->paid == 2) {
                    return "已发货";
                } else if ($model->paid == 3) {
                    return "已签收";
                }
            },
            'filter'    => ['1' => '未发货', '2' => '已发货', '0' => '未付款', '3' => '已签收'],
        ],
        'cellphone',
        //'address_id',
        'address',
        'name',
        [
            'class'    => 'yii\grid\ActionColumn',
            'header'   => '操作',
            'template' => '{confirmsent}',
            'buttons'  => [
                'confirmsent' => function ($url, $model, $key) {
                    return Html::a('确认发货', ['confirmsent', 'id' => $model['id']]);
                },
            ],
        ],
    ],
]);?>
</div>
