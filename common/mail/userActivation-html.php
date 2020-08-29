<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/user-activate', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>你好, <?= Html::encode($user->username) ?>,</p>

    <p>请点击以下链接来激活帐号:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
