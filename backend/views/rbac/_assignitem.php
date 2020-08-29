<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '分配权限';
$this->params['breadcrumbs'][] = ['label' => '权限管理'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
	<div class="field-box span12">
		<?php echo Html::label('角色名称',null).Html::encode($parent) ?>
	</div>
    <div class="field-box span12">
		<?php echo Html::label('角色子节点',null).Html::checkboxList('children',isset($children['roles'])?$children['roles']:'',$roles) ?>
	</div>
	<div class="field-box span12">
		<?php echo Html::label('权限子节点',null).Html::checkboxList('children',isset($children['permissions'])?$children['permissions']:'',$permissions) ?>
	</div>
	<div class="form-group">
        <?= Html::submitButton('分配', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
