<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title = '我的购物车 | ' . yii::$app->params['bandName'] . Yii::$app->params['suffix'];
if (!empty($data['data'])){?>
<form action="cart/settlement" method="get">
<table class="cart-box">
    <tr>
        <th><input type="checkbox" class="selectall">全选</th>
        <th>商品信息</th>
        <th>数量</th>
        <th>单价</th>
        <th>金额</th>
        <th>操作</th>
    </tr>
<?php foreach ($data['data'] as $list): ?>
    <tr class="border">
        <td class="check"><input type="checkbox" name="<?=$list['id']?>"></td>
        <td>
            <img src="http://<?php echo $list['label_img'] ?>" alt="<?=$list['product_name']?>">
            <span><?=$list['product_name']?></span>
        </td>
        <td>
            <input class="minus" type="button" value="-">
            <input class="text_box" type="text" value="<?=$list['amount']?>" name="no<?=$list['id']?>">
            <input class="add" type="button" value="+">
        </td>
        <td class="red"><b>￥<span class="price"><?=isset($list['price']) ? $list['price'] : 0.00?></span></b></td>
        <td class="red"><b>￥<span class="total"><?=isset($list['price']) ? $list['price'] * $list['amount'] : 0.00?></span></b></td>
        <td>
            <a href="<?=Url::to(['cart/delete', 'id' => $list['id']])?>">删除</a>
        </td>
    </tr>
<?php endforeach;?>
</table>
<div class="pull-right"><?=LinkPager::widget(['pagination' => $data['page']]);?></div>
<br>
<table class="cart-op">
    <tr>
        <td>
            <input type="checkbox" class="selectinvert"><span>反选</span>&nbsp;&nbsp;
            <span>
                <input type="submit" value="删除选中商品" name="settle">
            </span>
        </td>
        <td class="text-right">
            <span>合计：</span>
            <b class="red">￥<span class="finaltotal">0.00</span></b>&nbsp;
            <span>
                <input type="submit" value="结算" class="btn btn-danger" name="settle">
            </span>
        </td>
    </tr>
</table>
</form>
<?php }else{?>
<div class="text-center empty-cart">
    <img src="http://yii6.com/shop_cart.jpg" alt="空购物车">
    <h3>您的购物车还是空的，去挑几件好东西吧~</h3>
</div>
<?php }?>



