<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii;

class PagepayController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['pagepay'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionPagepay()
    {
        $get = yii::$app->request->get();
        return $this->renderPartial('pagepay',['subject'=>$get['subject'],'total_amount'=>$get['total_amount'],'out_trade_no'=>$get['out_trade_no'],'body'=>$get['body']]);
    }
}
