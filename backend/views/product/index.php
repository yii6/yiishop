<?php

use common\models\User;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title                   = '产品';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?=Html::encode($this->title)?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?=Html::a('添加轴承', ['create'], ['class' => 'btn btn-success'])?>
    </p>
    <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        //'cate',
        'cate_name',
        //'band',
        'band_name',
        // 'type',
        'type_name',
        'series_name',
        'created_id' => [
            'attribute' => 'created_id',
            'value'     => function ($model) {
                $user = User::findOne($model->created_id);
                return $user->username;
            },
        ],

        ['class' => 'yii\grid\ActionColumn'],
    ],
]);?>
</div>
