<?php
$this->title = $model->type_name . ' | ' . yii::$app->params['bandName'] . Yii::$app->params['suffix'];
?>
<script>
//标签页移到底部
$(function() {
    $("#tabs").tabs();
    $(".tabs-bottom .ui-tabs-nav, .tabs-bottom .ui-tabs-nav > *").removeClass("ui-corner-all ui-corner-top").addClass("ui-corner-bottom");
    // 移动导航到底部
    $(".tabs-bottom .ui-tabs-nav").appendTo(".tabs-bottom");
});
</script>
<div class="product">
    <div class="col-lg-4 col-md-4 col-sm-4 col-mx-4">
        <div id="tabs" class="tabs-bottom">
            <ul>
                <li>
                    <a href="#tabs-0">
                        <img src="http://<?php echo $model->label_img ?>-picsmall" alt="<?=$model->cate_name?>">
                    </a>
                </li>
<?php if (isset($model->pics)): ;
    foreach (json_decode($model->pics, true) as $k => $pic) {?>
                    <li>
                        <a href="#tabs-<?=$k?>">
                            <img src="http://<?php echo $pic ?>-picsmall">
                        </a>
                    </li>
    <?php }
    ;endif;?>
            </ul>
            <div id="tabs-0">
                <img src="http://<?php echo $model->label_img; ?>" alt="<?=$model->cate_name?>">
            </div>
<?php if (isset($model->pics)): ;
    foreach (json_decode($model->pics, true) as $k => $pic) {?>
                <div id="tabs-<?=$k?>">
                    <img src="http://<?php echo $pic ?>">
                </div>
    <?php }
    ;endif;?>
        </div>
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-mx-5">
        <h1><?=$model->band_name . " " . $model->cate_name . " " . $model->type_name?></h1>
        <form action="addtocart" method="get">
        <table>
            <tr>
                <td>价格：</td>
                <td><h2 class="red">￥<?=isset($model->price) ? $model->price : 0.00?></h2></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>品牌：</td>
                <td><?=$model->band_name?></td>
            </tr>
            <tr>
                <td>型号：</td>
                <td><?=isset($model->type_name) ? $model->type_name : ''?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>数量：</td>
                <td>
                    <input class="minus" type="button" value="-">
                    <input class="text_box"  type="text" value="1" name="amount">
                    <input class="add" type="button" value="+">
                </td>
            </tr>
        </table>
        <table>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td><input type="submit" value="立即购买" class="btn btn-danger" name="<?=$model->id?>"></td>
                <td>&nbsp;</td>
                <td><input type="submit" value="加入购物车" class="btn btn-danger" name="<?=$model->id?>"></td>
            </tr>
        </table>
         </form>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-mx-3 info-box">
        <table>
            <tr>
                <td>
                    <table class="border">
                        <tr>
                            <td><span class="glyphicon glyphicon-ok red"></span>原装正品</td>
                            <td><span class="glyphicon glyphicon-th red"></span>种类齐全</td>
                        </tr>
                        <tr>
                            <td><span class="glyphicon glyphicon-thumbs-up red"></span>库存充足</td>
                            <td><span class="glyphicon glyphicon-wrench red"></span>售后无忧</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>咨询热线：<span class="red"><b>0510-88230446</b></span></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table class="text-center">
                        <tr>
                            <td><img src="http://on3t83q3n.bkt.clouddn.com/knr_wx" alt="无锡凯恩瑞动力机械有限公司微信"></td>
                        </tr>
                        <tr>
                            <td>扫一扫添加<br>微信咨询</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="clear"></div>
    <h4>详细信息</h4>
    <hr>
    <?=$model->describe?>
</div>

