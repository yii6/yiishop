<?php

use common\models\Category;
use common\models\PostExtends;
use common\models\Posts;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Cart;
AppAsset::register($this);
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">
<head>
    <meta charset="<?=Yii::$app->charset?>">
   <?php
$this->registerMetaTag(['http-equiv' => "X-UA-Compatible", 'content' => "IE=edge"]);
$this->registerMetaTag(['name' => "viewport", 'content' => "width=device-width, initial-scale=1"]);
$this->registerMetaTag(['name' => "keywords", 'content' => "NSK商品商城；FAG商品商城；SKF商品商城"]);
$this->registerMetaTag(['name' => "description", 'content' => "买商品，到商品汇！汇聚全球知名商品品牌的交易平台，打造国内专业的商品采购平台。"]);?>
    <?=Html::csrfMetaTags()?>
    <link type="image/x-icon" href="http://yii6.com/skf_ico" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" >
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.bootcss.com/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link href="https://cdn.bootcss.com/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet">
    <title><?=Html::encode($this->title)?></title>
    <?php $this->head()?>
</head>
<body>
<?php include_once "baidu_js_push.php";
$this->beginBody()?>
<div class="top-menu">
    <div class="container">
        <span class="inline">欢迎光临商品汇商城！</span>
    <div class="pull-right inline">
<?php if (Yii::$app->user->isGuest) {?>
        <span><a href="<?=Url::to(['site/login'])?>">请登录</a></span>
        <span><a href="<?=Url::to(['site/signup'])?>">免费注册</a></span>
<?php } else {?>
        <span>欢迎，<?=Yii::$app->user->identity->username?></span>
        <span><a href="<?=Url::to(['site/logout'])?>">退出</a></span>
        <span><a href="<?=Url::to(['order/index'])?>">我的订单</a></span>
<?php }?>
        <div class="red inline"><span class="glyphicon glyphicon-earphone"></span><span>8888-12345678</span></div>
    </div>
    </div>
</div>
<div class="container">
    <div class="col-lg-3 col-md-3 col-sm-3 col-mx-3">
        <img src="http://yii6.com/shop_logo" alt="商品汇">
    </div>
    <div class="col-lg-5 col-md-5 col-sm-5 col-mx-5 search-box">
        <form action="product/search" method="get" class="form-inline">
            <input type="text" name="keyword" placeholder="请输入产品型号" class="input-box">
            <input type="submit" value="搜索" class="btn btn-danger">
        </form>
    </div>
<?php if (!Yii::$app->user->isGuest) {;?>
    <div class="col-lg-2 col-md-2 col-sm-4 col-mx-4 text-center shopping-cart">
<?php $carts=Cart::find()->where('user_id=:id and settled=false',[':id'=>Yii::$app->user->identity->id])->asArray()->all();
$amount=count($carts);?>
        <a href="<?=Url::to(['cart/index'])?>">
            <span class="red glyphicon glyphicon-shopping-cart"></span>
            我的购物车
            <span class="bubble btn-danger"><?=$amount?></span>
        </a>
    </div>
<?php }?>
</div>
<nav class="navbar" role="navigation">
    <div class="container">
        <ul class="nav navbar-nav text-center">
            <li class="dropdown left-menu btn-danger">
                <span class="dropdown-toggle" data-toggle="dropdown">商品分类</span>
                <ul class="dropdown-menu" role="menu">
<?php
$select = ['cat_name', 'id'];
$list   = Category::find()->select($select)->where('pid=2')->asArray()->all();
foreach ($list as $key => $value) {?>
                    <li>
                        <a href="<?=Url::to(['product/cate', 'PSF[cate]' => $value['id']])?>" class="text-center">
                            <span><?=$value['cat_name']?></span>
                        </a>
                    </li>
<?php }?>
                </ul>
            </li>
            <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="<?=Url::to(['site/index'])?>"><span>首页</span></a>
            </li>
            <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="<?=Url::to(['post/index'])?>"><span>资讯</span></a>
            </li>
             <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="<?=Url::to(['site/about'])?>"><span>企业简介</span></a>
            </li>
            <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="<?=Url::to(['site/contact'])?>"><span>联系我们</span></a>
            </li>
        </ul>
    </div>
</nav>
<?=Alert::widget()?>
<div class="container product">
    <br>
<?php
        $select=['cat_name','id'];
        $list=Category::find()->select($select)->where('pid=4')->asArray()->all();
     ?>
<div class="col-lg-2 col-md-2 col-sm-2 col-mx-2">
    <ul class="post-nav-list">
<?php
foreach ($list as $key => $value) {?>
        <a href="<?=Url::to(['post/cate', 'id' => $value['id']])?>">
            <li><?=$value['cat_name']?></li>
        </a>
<?php }?>
    </ul>
</div>
    <?=$content?>
</div>
<footer class="footer">
<table class="bottom-box text-center">
    <tr><td>&nbsp;</td>
        <td><img src="http://yii6.com/shop_z.jpg" alt="正"><span> &nbsp;正品保障</span></td>
        <td><img src="http://yii6.com/shop_p.jpg" alt="种类"><span> &nbsp;品类齐全</span></td>
        <td><img src="http://yii6.com/shop_d.jpg" alt="定"><span> &nbsp;非标定制</span></td>
        <td><img src="http://yii6.com/shop_k.jpg" alt="库存"><span> &nbsp;库存充足</span></td>
        <td>&nbsp;</td>
    </tr>
</table>
<div class="container">
<table class="nav navbar-nav text-center">
    <tr>
    <td class="dropdown">
        &copy; <?=Html::encode('银河吕布貂蝉无限公司')?> <?=date('Y')?>
    </td>
    <td class="dropdown">
        <a href="http://www.miitbeian.gov.cn" target="_blank">鄂ICP备18003564号</a>
    </td>
    <td class="dropdown hide9">
        地址：银河A区地球村中国路
    </td>
    <td class="dropdown hide9">
        联系人：吕经理
    </td>
    <td class="dropdown">
        手机：15827167113
    </td>
    <td class="dropdown dropup">
        <img src="http://yii6.com/wx.jpg2" alt="微信图标">
        <ul class="dropdown-menu" role="menu">
        <li><img src="http://yii6.com/knr_wx" alt="银河吕布貂蝉无限公司微信"></li>
        </ul>
    </td>
    <td class="dropdown dropup">
        <a href="http://www.yii6.com" target="_blank">友情链接</a>
        <ul class="dropdown-menu" role="menu">
            <li>
                <a href="http://www.fyhgo.com" target="_blank">
                <img src="http://yii6.com/fyh_logo" alt="FYH图标">
                </a>
            </li>
            <li>
                <a href="http://www.86skf.cn" target="_blank">
                <img src="http://yii6.com/skf_logo2" alt="SKF图标">
                </a>
            </li>
            <li>
                <a href="http://www.86fag.com.cn" target="_blank">
                <img src="http://yii6.com/fag_logo1" alt="FAG图标">
                </a>
            </li>
            <li>
                <a href="http://www.nskgo.com" target="_blank">
                <img src="http://yii6.com/nsk-logo4" alt="NSK图标">
                </a>
            </li>
        </ul>
    </td>
    </tr>
</table>
</div>
</footer>
<?php $this->endBody()?>
</body>
</html>
<?php $this->endPage()?>
