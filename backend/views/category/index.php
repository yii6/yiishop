<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分类';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建分类', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="row-fluid table">
<?=\yiidreamteam\jstree\JsTree::widget([
    'containerOptions' => [
        'class' => 'data-tree',
    ],
    'jsOptions'        => [
        'core'    => [
            'check_callback' => true,
            'multiple'       => false,
            'data'           => [
                'url' => \yii\helpers\Url::to(['category/tree', 'page' => $page, 'per-page' => $perpage]),
            ],
            'themes'         => [
                'stripes' => true,
                'variant' => 'large',
            ],
        ],
        'plugins' => [
            'contextmenu', 'dnd', 'search', 'state', 'types', 'wholerow',
        ],
    ],
])?>
        </div>
        <div class="pagination pull-right">
<?php
echo yii\widgets\LinkPager::widget(['pagination' => $pager, 'prevPageLabel' => '&#8249;', 'nextPageLabel' => '&#8250;']);
?>
         </div>
        </div>
    </div>
<?php
$rename=yii\helpers\Url::to(['category/rename']);
$delete=yii\helpers\Url::to(['category/delete']);
$csrfvar=yii::$app->request->csrfParam;
$csrfval=yii::$app->request->getCsrfToken();
$js = <<<JS
$("#w0").on("rename_node.jstree",function(e,data){
    var newtext=data.text;
    var old=data.old;
    var id=data.node.id;
    var postData={
        '$csrfvar':'$csrfval',
        'new':newtext,
        'old':old,
        'id':id
    };
    $.post('$rename',postData,function(data){
        if(data.code!=0){
            alert(data.message);
            window.location.reload();
        }
    });
})
$("#w0").on("delete_node.jstree",function(e,data){
    var id=data.node.id;
    $.get('$delete',{id:id},function(data){
        if(data.code!=0){
            alert(data.message);
            window.location.reload();
        }
    });
})
JS;
$this->registerJs($js);
 ?>
</div>
