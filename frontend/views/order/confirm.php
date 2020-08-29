<?php
use frontend\models\Cart;
use yii\widgets\ActiveForm;
$this->title = '确认订单 | ' . Yii::$app->params['bandName'] . Yii::$app->params['suffix'];
?>
<h3>确认收货地址</h3>
<?php $form = ActiveForm::begin()?>
<table>
    <tr>
        <td>
<?=$form->field($model, 'province')->dropDownList(
    $data['province'], ['onchange' => '
        $.ajax({
            type:"post",
            url:"city",
            data:{id:$(this).val()},
            success:function(data){
                $("select#address-city").html(data);
            }
        });
    ', ])->label(false)?>
        </td>
        <td>
<?=$form->field($model, 'city')->dropDownList(['a' => '——市——'], ['onchange' => '
        $.ajax({
            type:"post",
            url:"district",
            data:{id:$(this).val()},
            success:function(data){
                $("select#address-district").html(data);
            }
        });
    '])->label(false)?>
        </td>
        <td>
<?=$form->field($model, 'district')->dropDownList(['a' => '——区——'])->label(false)?>
        </td>
    </tr>
</table>
<?=$form->field($model, 'detailed')->textInput()?>
<table>
    <tr>
        <td><?=$form->field($model, 'user_name')->textInput()?></td>
    </tr>
    <tr>
        <td><?=$form->field($model, 'cellphone')->textInput()?></td>
    </tr>
</table>
<h3>确认订单信息</h3>
<table class="order-box">
    <tr>
        <th>商品信息</th>
        <th>数量</th>
        <th>单价</th>
        <th>小计</th>
    </tr>
<?php

$cart_ids = $data['cart_ids'];
$select   = ['amount', 'price', 'product_name', 'label_img', 'created_at', 'user_id', 'id'];
foreach ($cart_ids as $v) {
    $list = Cart::findOne($v);
    ?>
    <tr class="border">
        <td>

            <img src="http://<?php echo $list['label_img'] ?>" alt="<?=$list['product_name']?>">
                        <span><?=$list['product_name']?></span>

        </td>
        <td><?=$list['amount']?></td>
        <td>￥<?=$list['price']?></td>
        <td class="red"><b>￥<?=$list['amount'] * $list['price']?></b></td>
    </tr>
<?php }?>
</table>
<br>
<table class="cart-op">
    <tr>
        <td class="text-right">
            <span>合计：</span>
            <b class="red">￥<?=$data['total']?></b>&nbsp;
            <span>
                <input type="submit" value="提交订单" class="btn btn-danger">
            </span>
        </td>
    </tr>
</table>
<?php ActiveForm::end();?>



