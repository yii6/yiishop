<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '创建角色';
$this->params['breadcrumbs'][] = ['label' => '权限管理', 'url' => ['rbac/createrole']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
	<div class="field-box span12">
		<?php echo Html::label('名称',null).Html::textInput('description','',['class'=>'span9']) ?>
	</div>
    <div class="field-box span12">
		<?php echo Html::label('标识',null).Html::textInput('name','',['class'=>'span9']) ?>
	</div>
	<div class="field-box span12">
		<?php echo Html::label('规则名称',null).Html::textInput('rule_name','',['class'=>'span9']) ?>
	</div>
	<div class="field-box span12">
		<?php echo Html::label('数据',null).Html::textarea('data','',['class'=>'span9']) ?>
	</div>
	<div class="form-group">
        <?= Html::submitButton('创建', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
