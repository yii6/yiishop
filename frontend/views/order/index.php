<?php
use frontend\models\Cart;
use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title = '我的订单 | ' . yii::$app->params['bandName'] . Yii::$app->params['suffix'];
if (!empty($data['data'])) {
    ?>
<form action="order/settlement" method="get">
<table class="order-box">
    <tr>
        <th><input type="checkbox" class="selectall">全选</th>
        <th>
            <table>
                <tr>
                    <td class="goods">商品信息</td>
                    <td class="amount">数量</td>
                    <td class="sale">单价</td>
                </tr>
            </table>
        </th>
        <th>实付款</th>
        <th>交易状况</th>
        <th>操作</th>
    </tr>
<?php foreach ($data['data'] as $value): ?>
    <tr>
        <td class="check"><input type="checkbox" name="<?=$value['id']?>"></td>
        <td><b><?=date('Y-m-d H:i', $value['created_at'])?></b> &nbsp;订单号:<?=$value['order_no']?></td>
        <td></td>
        <td></td>
        <td>
<?php if ($value['paid'] == 0) {?>
            <a href="<?=Url::to(['order/delete', 'id' => $value['id']])?>">取消订单</a>
<?php }?>
        </td>
    </tr>
    <tr class="border">
        <td></td>
        <td>
<?php
$cart_ids = json_decode($value['cart_ids'], true);
    $select   = ['amount', 'price', 'product_name', 'label_img', 'created_at', 'user_id', 'id'];
    foreach ($cart_ids as $v) {
        $list = Cart::findOne($v);
        ?>
            <table>
                <tr>
                    <td class="goods">
                        <img src="http://<?php echo $list['label_img'] ?>" alt="<?=$list['product_name']?>">
                        <span><?=$list['product_name']?></span>
                    </td>
                    <td class="amount"><?=$list['amount']?></td>
                   <td class="red sale"><b>￥<span class="price"><?=$list['price']?></span></b></td>
                </tr>
            </table>
<?php }?>
        </td>
        <td class="red"><b>￥<span class="total"><?=$value['total']?></span></b></td>
        <td>
<?php if ($value['paid'] == 0) {
        echo "待付款";
    } else if ($value['paid'] == 1) {
        echo "未发货";
    } else if ($value['paid'] == 2) {
        echo "待收货";
    } else if ($value['paid'] == 3) {
        echo "已收货";
    }
    ?>
        </td>
        <td>
<?php if ($value['paid'] == 0) {?>
        <a href="<?=Url::to(['order/pay', 'id' => $value['id']])?>" class="btn-danger btn">付款</a>
<?php } else if ($value['paid'] == 2) {?>
        <a href="<?=Url::to(['order/confirmreceipt', 'id' => $value['id']])?>" class="btn-danger btn">确认收货</a>
<?php } else if ($value['paid'] == 3) {?>
        <a href="<?=Url::to(['order/delete', 'id' => $value['id']])?>" title="删除订单"><span class="glyphicon glyphicon-trash"></span></a>
<?php }?>
        </td>
    </tr>
<?php endforeach;?>
</table>
<div class="pull-right"><?=LinkPager::widget(['pagination' => $data['page']]);?></div>
<br>
<table class="cart-op">
    <tr>
        <td>
            <input type="checkbox" class="selectinvert"><span>反选</span>
        </td>
        <td>
            <input type="submit" value="取消选中订单" name="settle"> <input type="submit" value="批量确认收货" name="settle">
        </td>
        <td class="text-right">
            <span>合计：</span>
            <b class="red">￥<span class="finaltotal">0.00</span></b>&nbsp;
            <span>
                <input type="submit" value="合并付款" class="btn btn-danger" name="settle">
            </span>
        </td>
    </tr>
</table>
</form>
<?php } else {?>
<div class="text-center empty-cart">
    <img src="http://yii6.com/shop_cart.jpg" alt="空购物车">
    <h3>您还没有订单，去挑几件好东西吧~</h3>
</div>
<?php }?>



