<?php
use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">
<head>
    <meta charset="<?=Yii::$app->charset?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" >
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <?=Html::csrfMetaTags()?>
    <title><?=Html::encode($this->title)?></title>
    <?php $this->head()?>
</head>
<body>
<?php $this->beginBody()?>
<div class="navbar-head text-center">
    <h2 class="inline">商城后台管理系统</h2>
    <div class="inline">
         <img src="http://<?=Yii::$app->user->identity->avatar?>-picsmall">
        <span><?=Yii::$app->user->identity->username?></span>
        <span><a href="<?=Url::to(['user/cpass'])?>">修改密码</a></span>
        <span><a href="<?=Url::to(['site/logout'])?>">退出</a></span>
    </div>
</div>
<ul class="navbar-body col-lg-2 col-md-2 col-sm-2">
<?php
if (yii::$app->user->can('post/*') || yii::$app->user->can('tech/*')) {
    ?>
    <li class="dropdown-list">内容管理
        <span class="caret"></span>
        <ul>
            <a href="<?=Url::to(['post/index'])?>"><li>资讯管理</li></a>
            <a href="<?=Url::to(['category/index'])?>"><li>分类管理</li></a>
        </ul>
    </li>
<?php }
if (yii::$app->user->can('product/*') || yii::$app->user->can('product/index')) {
    ?>
    <li class="dropdown-list">产品管理
        <span class="caret"></span>
        <ul>
            <a href="<?=Url::to(['product/index'])?>"><li>产品管理</li></a>
            <a href="<?=Url::to(['category/index'])?>"><li>分类管理</li></a>
        </ul>
    </li>
<?php }
if (yii::$app->user->can('user/*') || yii::$app->user->can('user/index')) {
    ?>
    <li><a href="<?=Url::to(['user/index'])?>">用户管理</a></li>
<?php
}
if (yii::$app->user->can('rbac/*') || yii::$app->user->can('rbac/index')) {
    ?>
    <li class="dropdown-list">权限管理
        <span class="caret"></span>
        <ul>
            <a href="<?=Url::to(['rbac/createrole'])?>"><li>创建角色</li></a>
            <a href="<?=Url::to(['rbac/roles'])?>"><li>角色列表</li></a>
            <a href="<?=Url::to(['rbac/createrule'])?>"><li>创建规则</li></a>
        </ul>
    </li>
<?php
}
if (yii::$app->user->can('userorder/*') || yii::$app->user->can('userorder/index')) {
    ?>
    <li><a href="<?=Url::to(['userorder/index'])?>">订单管理</a></li>
<?php
}
?>
</ul>
<script>
$(".navbar-body > li").click(function(){
    $(this).find("ul").toggle();
});
</script>
<div class="col-lg-9 col-md-9 col-sm-9">
    <?=Breadcrumbs::widget([
    'homeLink' => [
        'label' => Yii::t('yii', 'Home'),
        'url'   => '/',
    ],
    'links'    => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
])?>
<?=Alert::widget()?>
<?=$content?>
</div>
<?php $this->endBody()?>
</body>
</html>
<?php $this->endPage()?>
