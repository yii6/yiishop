<?php

use yii\helpers\Html;
$this->title                   = '添加资讯';
$this->params['breadcrumbs'][] = ['label' => '资讯', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-create">
    <h1><?=Html::encode($this->title)?></h1>
    <?=$this->render('_form', [
    'model' => $model,
    'cates' => $cates,
])?>
</div>
