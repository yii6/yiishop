<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>
	<?=$form->field($model, 'pid')->dropDownList($list)?>
    <?= $form->field($model, 'cat_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('创建分类', ['class' =>  'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
