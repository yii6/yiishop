<?php

namespace console\controllers;
use yii;

class MailController extends \yii\console\Controller
{
    public function actionSend()
    {
        \yii::$app->mailer->process();
    }
}
