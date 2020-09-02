<?php
use common\models\Posts;
use yii\helpers\Url;
$this->title              = '商品汇 | 全球知名商品交易平台';
$data['SKF']['website'] = 'www.86skf.cn';
$data['FAG']['website'] = 'www.86fag.com.cn';
$data['NSK']['website'] = 'www.nskgo.com';
$data['FYH']['website'] = 'www.fyhgo.com';
?>
<h3>品牌介绍</h3>
<br>
<div class="product">
    <?php foreach ($data['band'] as $value) {
    ?>
<div class="col-lg-3 col-md-3 col-sm-3 col-mx-3">
<a href="http://<?=$data[$value]['website']?>" target="_blank"><img src="http://<?=$data[$value]['label_img']?>" alt="<?=$value?>"></a>
</div>
<?php }?>
<div class="clear"></div>
<br><br>
<h3>产品导航</h3>
<?php foreach ($data['band'] as $value) {?>
<div class="band-box row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-mx-3">
        <br>
        <img src="http://<?=$data[$value]['label_img']?>" alt="<?=$value?>">
        <h4 class="hide9"><?=$data[$value]['describe']?></h4>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-9 col-mx-9">
<?php for ($i = 0; $i < 8; ++$i) {?>
        <div class="col-lg-3 col-md-3 col-sm-3 col-mx-3 product-box">
            <a href="<?=Url::to(['product/view', 'id' => $data[$value][$i]['id']])?>">
                <img src="http://<?=$data[$value][$i]['label_img'];?>">
            </a>
            <table>
                <tr>
                    <td>
                        <a href="<?=Url::to(['product/view', 'id' => $data[$value][$i]['id']])?>"><?=$value?> <?=$data[$value][$i]['cate_name']?> <?=$data[$value][$i]['type_name']?></a>
                    </td>
                </tr>
                <tr>
                    <td class="red">￥<?=$data[$value][$i]['price']?></td>
                </tr>
            </table>
        </div>
<?php if ($i == 3) {?>
        <div class="clear"></div>
<?php }}?>
    </div>
</div>
<div class="clear"></div>
<br>
<?php }?>
<h3>焦点资讯</h3>
<?php
$orderBy = ['created_at' => SORT_DESC];
$res     = Posts::find()->orderBy($orderBy)->limit(6)->asArray()->all();
foreach ($res as $key => $list) {?>
<div class="col-lg-6 col-md-6 col-sm-6 col-mx-6">
    <div class="news-box">
        <div class="col-lg-4 col-md-4 col-sm-4 col-mx-4">
        <br>
        <img src="http://<?php echo $list['label_img'] ?>" alt="<?=$list['title']?>">
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-mx-8">
        <a href="<?=Url::to(['post/view', 'id' => $list['id']])?>"><h4><?=$list['title']?></h4></a>
        <p><?=$list['summary']?>...</p>
    </div>
    <div class="clear"></div>
    </div>
</div>
 <?php
if ($key % 2 == 1) {?>
<div class="clear"></div>
 <?php }}?>
<h3>合作伙伴</h3>
<h2 class="text-center"><b>合作伙伴 · </b>感谢有您一路同行</h2>
<img src="http://yii6.com/shop_co" alt="合作品牌">
</div>
