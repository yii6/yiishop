<?php

use yii\helpers\Html;

$this->title = '更新产品: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '产品', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="product-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,'cates'=>$cates
    ]) ?>
</div>
