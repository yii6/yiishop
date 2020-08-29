<?php
use yii\helpers\Url;
$this->title = $data['title'] . ' | ' . yii::$app->params['bandName'] . Yii::$app->params['suffix'];
?>
<div class="col-lg-10 col-md-10 col-sm-10 col-mx-10 news-detail">
	<h2 class="text-center"><b><?=$data['title']?></b></h2>
	<span><?=date('Y-m-d', $data['created_at'])?></span>
<hr>
<img src="http://<?=$data['label_img']?>" alt="<?=$data['title']?>">
<?=$data['content']?>
<span><b>关键词：</b>
<?php
foreach ($data['tags'] as $k => $value) {?>
	<span><a href="<?=Url::to(['post/tag', 'id' => $k])?>"><?=$value?></a>&nbsp;&nbsp;</span>
<?php }?>
</span>
<hr>
<div class="text-center"><a href="#" onClick="javascript:history.go(-1);"><h4>返回</h4></a></div>
</div>