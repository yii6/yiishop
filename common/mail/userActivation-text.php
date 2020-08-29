<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/user-activate', 'token' => $user->password_reset_token]);
?>
你好, <?= $user->username ?>,

请点击以下链接来激活帐号:

<?= $resetLink ?>
