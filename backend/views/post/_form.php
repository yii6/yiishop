<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="posts-form col-lg-9">
    <?php $form=ActiveForm::begin(['options'     => ['enctype'=> 'multipart/form-data']]) ?>
    <?=$form->field($model,'cat_id')->dropDownList($cates) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?=$form->field($model, 'label_img')->fileInput();?>
        <?php if (!empty($model->label_img)): ?>
        <img src="http://<?php echo $model->label_img; ?>-picmedium">
        <?php endif?>
    <?= $form->field($model, 'content')->widget('common\widgets\ueditor\Ueditor',[
        'options'=>[
        'initialFrameHeight' => 450,
        ]
        ]) ?>
     <?=$form->field($model,'tags')->widget('common\widgets\bootstrapTags\BootstrapTags')?>
    <div class="form-group">
    <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="col-lg-3">
    <span>注意事项</span>
    <div>
        <p>1.上传的图片最大不超过2M</p>
        <p>2.文章字数不能超过1万 </p>
        <p>3.敲击回车确认一个标签 </p>
    </div>
</div>
