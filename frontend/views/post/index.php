<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title = '新闻资讯 | ' . yii::$app->params['bandName'] . Yii::$app->params['suffix'];
?>
<div class="col-lg-9 col-md-9 col-sm-9 col-mx-9">
<?php
if(!empty($data)):;
foreach ($data['body'] as $list): ?>
    <div class="news-box">
        <div class="col-lg-4 col-md-4 col-sm-4 col-mx-4">
        <img src="http://<?php echo $list['label_img']?>" alt="<?=$list['title']?>">
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-mx-8">
        <a href="<?=Url::to(['post/view','id'=>$list['id']])?>"><h4><?=$list['title']?></h4></a>
        <p><?=$list['summary']?>...</p>
        <span><?=date('Y-m-d',$list['created_at'])?>&nbsp;&nbsp;</span>
        <?php foreach ($list['tags'] as$k=>$tag):?>
         <span><a href="<?=Url::to(['post/tag','id'=>$k])?>"><?=$tag?></a></span>&nbsp;
         <?php endforeach;?>
    </div>
    <div class="clear"></div>
    </div>
    <br>
<?php
endforeach;
endif;?>
<div class="pull-right"><?=LinkPager::widget(['pagination' => $data['page']]);?></div>
</div>

