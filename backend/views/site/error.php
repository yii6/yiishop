<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        当处理你的请求的时候出现了上面的错误.
    </p>
    <p>
        如果你认为这是一个服务器端错误，请联系我们，谢谢.
    </p>

</div>
