<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '授权';
$this->params['breadcrumbs'][] = ['label' => '用户管理'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
	<div class="field-box span12">
		<?php echo Html::label('管理员',null).Html::encode($admin) ?>
	</div>
    <div class="field-box span12">
		<?php echo Html::label('角色',null).Html::checkboxList('children',isset($children['roles'])?$children['roles']:[],$roles) ?>
	</div>
	<div class="field-box span12">
		<?php echo Html::label('权限',null).Html::checkboxList('children',isset($children['permissions'])?$children['permissions']:[],$permissions) ?>
	</div>
	<div class="form-group">
        <?= Html::submitButton('授权', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
