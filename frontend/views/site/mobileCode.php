<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '手机验证';
?>
<div class="container">
    <h1><?=Html::encode($this->title)?></h1>
    <p>请填写收到的验证码完成验证:</p>
<?php $form = ActiveForm::begin(['id' => 'form-signup']);?>
    <div class="col-lg-5 col-md-6 col-sm-7 col-mx-9">
        <?=$form->field($model, 'cellphone')->textInput(['readonly'=>true])?>
        <?=$form->field($model, 'userCode')->textInput(['autofocus' => true])?>
        <div class="form-group">
            <?=Html::submitButton('确定', ['class' => 'btn btn-primary'])?>
        </div>
    </div>
<?php ActiveForm::end();?>
</div>

