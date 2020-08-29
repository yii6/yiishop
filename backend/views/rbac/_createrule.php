<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '创建规则';
$this->params['breadcrumbs'][] = ['label' => '规则管理', 'url' => ['rbac/rules']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
	<div class="field-box span12">
		<?php echo Html::label('类名称',null).Html::textInput('class_name','',['class'=>'span9']) ?>
	</div>
	<div class="form-group">
        <?= Html::submitButton('创建', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
