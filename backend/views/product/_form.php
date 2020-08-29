<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="product-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']])?>
    <?=$form->field($model, 'band')->dropDownList($cates['bands'])?>
    <?=$form->field($model, 'cate')->dropDownList(
    $cates['cates'], ['onchange' => '
        $.ajax({
            type:"post",
            url:"series",
            data:{id:$(this).val()},
            success:function(data){
                $("select#product-series").html(data);
            }
        });
    '])?>
    <?=$form->field($model, 'series')->dropDownList($cates['series'])?>
    <?=$form->field($model, 'type')->dropDownList($cates['types'])?>
    <?=$form->field($model, 'price')->textInput();?>
    <?=$form->field($model, 'label_img')->fileInput();?>
    <?php if (!empty($model->label_img)): ?>
    <img src="http://<?php echo $model->label_img; ?>-picmedium">
    <a href="<?php echo yii\helpers\Url::to(['product/removeimg', 'keyurl' => $model->label_img, 'id' => $model->id]) ?>">删除</a>
    <?php endif;
    echo $form->field($model, 'pics[]')->fileInput(['multiple' => true]);//ture的时候返回关联array
    if ($model->pics['0']!=''):;
    foreach ((array) json_decode($model->pics, true) as $pic) {
$keyurl=substr($pic, 9);
        ?>
    <img src="http://<?php echo $pic; ?>-picmedium">
    <a href="<?php echo yii\helpers\Url::to(['product/removepics', 'key' => $keyurl, 'id' => $model->id]) ?>">删除</a>
    <?php };endif; ?>
    <br>
    <input type='button' id='addpic' value='增加一个浏览文件框'>
    <script type="text/javascript">
        $("#addpic").click(function() {
            var pic = $("#product-pics").clone();//id是根据网页源代码发现的
            $("#product-pics").parent().append(pic);
        });
    </script>
    <br>
    <?=$form->field($model, 'describe')->widget('common\widgets\ueditor\Ueditor', [
    'options' => [
        'initialFrameHeight' => 2000,
        'initialFrameWidth'  => 1000,
    ],
])?>
    <div class="form-group">
        <?=Html::submitButton($model->isNewRecord ? '新建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
    </div>
    <?php ActiveForm::end();?>
</div>
