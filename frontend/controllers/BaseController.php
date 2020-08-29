<?php

namespace frontend\controllers;
use yii\filters\AccessControl;
use yii;

class BaseController extends \yii\web\Controller
{
    const AK     = 'cIrHD9OctIP5oAM0YYqoizCwxNR9p5LMhlVnTK5e';
    const SK     = 'OgZW9d08VmudI_aOZ9w3-vCOYpzUlJxwhi9fg7E5';
    const DOMAIN = 'yii6.com';
    const BUCKET = 'yuwuy';
    const ZONE   = 'east_china';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create','index','delete','update','view'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function beforeAction($action)
    {
        if(!parent::beforeAction($action)){
        	return false;
        }
        $controller=$action->controller->id;
        $actionName=$action->id;
        if(yii::$app->user->can($controller.'/*')||yii::$app->user->can($controller.'/'.$actionName)){
            return true;
        }
        throw new \yii\web\UnauthorizedHttpException("您没有访问".$controller.'/'.$actionName.'的权限.');
    }
    public function init(){
    }
}
