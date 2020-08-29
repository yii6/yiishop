<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\User;
use crazyfd\qiniu\Qiniu;
use frontend\controllers\BaseController;
use yii\web\BadRequestHttpException;
use yii\base\InvalidParamException;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'=>['logout','login','index'],
                'rules' => [
                    [
                        'actions' => ['login','error'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['logout','index'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionLogin()
    {
       $this->layout='login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    public function actionLogout()
    {
        Yii::$app->user->logout(true);

        return $this->goHome();
    }
//图片上传函数
    public function upload()
    {
        if ($_FILES['SignupForm']['error']['avatar'] > 0) {
            return false;
        }
        $qiniu = new Qiniu(BaseController::AK, BaseController::SK, BaseController::DOMAIN, BaseController::BUCKET);
        $key   = uniqid();
        $qiniu->uploadFile($_FILES['SignupForm']['tmp_name']['avatar'], $key);
        //获得图片外链
        $avatar = $qiniu->getLink($key);
        return ['avatar' => $avatar];
    }
}
