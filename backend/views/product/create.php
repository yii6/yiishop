<?php
use yii\helpers\Html;
$this->title='添加新轴承';
$this->params['breadcrumbs'][]=['label'=>'产品','url'=>['product/index']];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="product-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<?= $this->render('_form', [
        'model' => $model,'cates'=>$cates
    ]) ?>
</div>
