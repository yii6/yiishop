<?php

use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

$this->title = '注册';
?>
<div class="site-signup container">
    <h1><?=Html::encode($this->title)?></h1>
    <p>请填写以下信息来完成注册:</p>
<?php $form = ActiveForm::begin(['id' => 'form-signup']);?>
    <div class="col-lg-5 col-md-6 col-sm-7 col-mx-9">
        <?=$form->field($model, 'username')->textInput(['autofocus' => true])?>
        <?=$form->field($model, 'cellphone')->textInput()?>
        <?=$form->field($model, 'password')->passwordInput()?>
        <?=$form->field($model, 'rePassword')->passwordInput()?>
        <?=$form->field($model, 'verifyCode')->widget(Captcha::className())?>
        <div class="form-group">
            <?=Html::submitButton('确定', ['class' => 'btn btn-primary'])?>
        </div>
    </div>
<?php ActiveForm::end();?>
</div>

