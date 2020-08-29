<?php

use yii\helpers\Html;
$this->title                   = '更新资讯: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '资讯', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新资讯';
?>
<div class="posts-update">
    <h1><?=Html::encode($this->title)?></h1>
    <?=$this->render('_form', [
    'model' => $model,
    'cates' => $cates,
])?>
</div>
