<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '角色列表';
$this->params['breadcrumbs'][] = ['label' => '权限管理', 'url' => ['rbac/roles']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    	echo GridView::widget([
    		'dataProvider'=>$dataProvider,
    		'columns'=>[
    			[
    				'class'=>'yii\grid\SerialColumn',
    			],
    			'description:text:名称',
    			'name:text:标识',
    			'rule_name:text:规则名称',
    			'created_at:datetime:创建时间',
    			'updated_at:datetime:更新时间',
    			[
    				'class'=>'yii\grid\ActionColumn',
    				'header'=>'操作',
    				'template'=>'{assign} {update} {delete}',
    				'buttons'=>[
    					'assign'=>function($url,$model,$key){
    						return Html::a('分配权限',['assignitem','name'=>$model['name']]);
    					},
    					'update'=>function($url,$model,$key){
    						return Html::a('更新',['updateitem','name'=>$model['name']]);
    					},
    					'delete'=>function($url,$model,$key){
    						return Html::a('删除',['deleteitem','name'=>$model['name']]);
    					},
    				]
    			]
    		],
    		//items就是数据,summary就是统计,pager就是分页
    		'layout'=>"\n{items}\n{summary}<div class='pagination pull-right'>{pager}</div>"
    	]);
     ?>
</div>

