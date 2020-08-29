<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\captcha\Captcha;

$this->title = '登录';
?>
<div class="container">
    <h1><?=Html::encode('用户登录')?></h1>
<?php $form = ActiveForm::begin(['id' => 'login-form']);?>
    <div class="col-lg-5 col-md-6 col-sm-7 col-mx-9">
        <?=$form->field($model, 'username', [
    'inputOptions' => [
        'placeholder' => '请输入用户名/手机号'],
])
->textInput(['autofocus' => true])
->label(false)?>
        <?=$form->field($model, 'password', [
    'inputOptions' => [
        'placeholder' => '请输入密码'],
])
->passwordInput()
->label(false)?>
        <div>
            忘记密码?您可以申请 <a href="<?=Url::to(['site/request-password-reset'])?>">重置密码</a>。
        </div>
<?=$form->field($model, 'verifyCode')->widget(Captcha::className())?>
        <div class="form-group">
            <?=Html::submitButton('登录', ['class' => 'btn btn-primary'])?>
        </div>
    </div>
<?php ActiveForm::end();?>
</div>