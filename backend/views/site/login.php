<?php
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;
$this->title = '登录-商城后台';
?>
<div class="site-login col-lg-4 col-md-4 col-sm-4">
<h3>欢迎登陆商城后台管理系统</h3>
<div class="input">
    <?php $form = ActiveForm::begin()?>
<?=$form->field($model, 'username', [
    'inputOptions' => [
        'placeholder' => '请输入管理员帐号']])
->textInput(['autofocus' => true])
->label(false)?>
<?=$form->field($model, 'password', [
    'inputOptions' => [
        'placeholder' => '请输入密码'],
])
->passwordInput()
->label(false)?>
<?=$form->field($model, 'verifyCode')->widget(Captcha::className())?>
<?=Html::submitButton('登录', ['class' => 'btn btn-primary'])?>
<?php ActiveForm::end();?>
<a href="http://www.zhouchengh.com/site/request-password-reset" class="pull-right">忘记密码</a>
</div>
</div>

