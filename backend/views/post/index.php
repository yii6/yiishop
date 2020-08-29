<?php

use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = '资讯管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-index">
    <p>
        <?= Html::a('插入资讯', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'title'    => [
            'attribute' => 'title',
            'format'    => 'raw',
            'value'     => function ($model) {
                return '<a href="http://fyh.yuwuy.cn' . Url::to(['post/view', 'id' => $model->id]) . '">' . $model->title . '</a>';
            },
        ],
        //'author_name',
        'created_at:datetime',
        'created_id',
        // 'updated_at',
        ['class' => 'yii\grid\ActionColumn'],
    ],
]);?>
</div>
