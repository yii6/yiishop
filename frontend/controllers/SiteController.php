<?php
namespace frontend\controllers;

use alisms\api_demo\SmsDemo;
use common\models\LoginForm;
use common\models\PasswordResetRequestForm;
use common\models\Product;
use common\models\ResetPasswordForm;
use common\models\SignupForm;
use common\models\Sms;
use common\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;

class SiteController extends \yii\web\Controller
{
    public function actions()
    {
        return [
            'error'   => [
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
        //可以测试这之间的代码的执行时间,然后进行优化
        // yii::beginProfile('test');
        // yii::endProfile('test');
        $orderBy                    = ['created_at' => SORT_DESC];
        $select                     = ['band', 'cate_name', 'price', 'label_img', 'type_name', 'id'];
        $result['band']             = ['SKF', 'NSK', 'FYH', 'FAG'];
        $result['SKF']              = Product::find()->where('band=5')->select($select)->orderBy($orderBy)->asArray()->limit(8)->all();
        $result['SKF']['label_img'] = 'yii6.com/skf_logo2';
        $result['SKF']['describe']  = '自1907年以来， SKF作为领先的技术供应商，具有不断研发新技术的强大实力，为客户提供具有竞争力的优势产品。SKF集团总部设立于瑞典哥德堡，是轴承科技与制造的领导者、世界最大的轴承生产商，SKF轴承产量占全球同类产品总产量的20%。。如今SKF技术研发的重点是在运营中减少资产生命周期对环境的影响。';
        $result['FAG']              = Product::find()->where('band=7')->select($select)->orderBy($orderBy)->asArray()->limit(8)->all();
        $result['FAG']['label_img'] = 'yii6.com/fag_logo';
        $result['FAG']['describe']  = '1883年，FAG在德国施魏因福特成立，是舍弗勒集团旗下品牌。一流的质量、优秀的技术和卓越的创新精神构成了舍弗勒持续成功的基础。通过提供广泛应用于工业领域的滚动轴承和滑动轴承解决方案，舍弗勒集团正积极实现“高效驱动，驰骋未来”（Mobility for tomorrow）的战略目标。';
        $result['NSK']              = Product::find()->where('band=6')->select($select)->orderBy($orderBy)->asArray()->limit(8)->all();
        $result['NSK']['label_img'] = 'yii6.com/nsk_logo_n';
        $result['NSK']['describe']  = 'NSK自1916年在日本率先开始生产轴承以来，作为日本的轴承先锋，开发与提供各类轴承，为产业的发展和机械的进步做出了巨大贡献。现在，NSK在轴承领域，稳居日本首位，同时在全世界也位居前列。今后，NSK还将一如既往地尽快将中国客户需求反映到产品上，并且以满足客户为己任，超越自己，超越未来。';
        $result['FYH']              = Product::find()->where('band=8')->select($select)->orderBy($orderBy)->asArray()->limit(8)->all();
        $result['FYH']['label_img'] = 'yii6.com/fyh_logo_n';
        $result['FYH']['describe']  = 'FYH株式会社自1950年在日本首次生产PILLOW BLOCK（带座滚动轴承）以来，已逐步成长为支撑世界产业发展的带座滚动轴承专业厂家。FYH生产的轴承的最后公差要比日本工业品规格标准还要严格，FYH轴承可以在高速旋转等各种要求严格的场所使用，得到了用户的好评。';
        return $this->render('index', [
            'data' => $result,
        ]);
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $this->layout = 'product';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    public function actionLogout()
    {
        Yii::$app->user->logout(true); //false只清除对应的cookie而不清除所有的cookie
        return $this->goHome();
    }
//用户注册
    public function actionSignup()
    {
        $model        = new SignupForm();
        $this->layout = 'product';
        if (Yii::$app->request->isPost) {
            $post = yii::$app->request->post();
            if ($model->load($post) && $model->validate() && $user = $model->signup($post)) {
                $sms             = new Sms();
                $sms->cellphone  = $user->cellphone;
                $sms->code       = rand(100000, 999999);
                $sms->created_at = time();
                $alisms          = new SmsDemo();
                $response        = $alisms->sendSms($sms->cellphone, $sms->code);
                if ($response->Code == 'OK') {
                    Yii::$app->session->setFlash('success', '验证码已发送。请查收。');
                    $sms->save();
                    return $this->redirect(['site/mobile_check', 'token' => $user->auth_key, 'created_at' => $sms->created_at, 'cellphone' => $sms->cellphone]);
                } else {
                    Yii::$app->session->setFlash('error', '验证码发送失败，'.$response->Message.'。请重试、更换手机号或者联系我们。');
                    $user->delete();
                    return $this->goBack();
                }
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }
//手机验证
    public function actionMobile_check($token, $created_at, $cellphone)
    {
        $this->layout = 'product';
        $model        = Sms::findOne(['created_at' => $created_at, 'cellphone' => $cellphone, 'used' => 0]);
        if (Yii::$app->request->isPost && $user = User::findOne(['cellphone' => $cellphone, 'status' => 0, 'auth_key' => $token])) {
            $post = yii::$app->request->post();
            if ($model->load($post) && $model->validate()) {
                if (time() - $model->created_at < 300) {
                    if ($model->code == $post['Sms']['userCode']) {
                        $model->used_at = time();
                        $model->used    = true;
                        $model->save();
                        $user->status = 10;
                        if ($user->save()) {
                            Yii::$app->session->setFlash('success', '验证成功，恭喜您注册成功。');
                            return $this->goHome();
                        } else {
                            Yii::$app->session->setFlash('error', '验证成功，状态更新失败，注册失败。');
                        }
                    } else {
                        Yii::$app->session->setFlash('error', '验证码错误，注册失败。');
                    }
                } else {
                    $model->used_at = time();
                    $model->used    = true;
                    $model->save();
                    Yii::$app->session->setFlash('error', '验证失败，验证码已经过期。');
                }
                $user->delete();
                return $this->goBack();
            }
        }
        return $this->render('mobileCode', [
            'model' => $model,
        ]);
    }
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $sms             = new Sms();
                $sms->cellphone  = $model->cellphone;
                $sms->code       = rand(100000, 999999);
                $sms->created_at = time();
                $alisms          = new SmsDemo();
                $response        = $alisms->sendSms($sms->cellphone, $sms->code);
                if ($response->Code == 'OK') {
                    Yii::$app->session->setFlash('success', '验证码已发送。请查收。');
                    $sms->save();
                } else {
                    Yii::$app->session->setFlash('error', '验证码发送失败，'.$response->Message.'。请重试、更换手机号或者联系我们。');
                    return $this->goBack();
                }
                return $this->redirect(['site/pass_mobile_check', 'created_at' => $sms->created_at, 'cellphone' => $sms->cellphone]);
            }
        }
        $this->layout = 'product';
        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }
    //手机验证
    public function actionPass_mobile_check($created_at, $cellphone)
    {
        $this->layout = 'product';
        $model        = Sms::findOne(['created_at' => $created_at, 'cellphone' => $cellphone, 'used' => 0]);
        if (Yii::$app->request->isPost) {
            $post = yii::$app->request->post();
            if ($model->load($post) && $model->validate()) {
                if (time() - $model->created_at < 300) {
                    if ($model->code == $post['Sms']['userCode']) {
                        $model->used_at = time();
                        $model->used    = true;
                        $model->save();
                        return $this->redirect(['site/resetpassword','cellphone'=>$cellphone]);
                    } else {
                        Yii::$app->session->setFlash('error', '验证码错误。');
                    }
                } else {
                    $model->used_at = time();
                    $model->used    = true;
                    $model->save();
                    Yii::$app->session->setFlash('error', '验证失败，验证码已经过期。');
                }
                return $this->goHome();
            }
        }
        return $this->render('mobileCode', [
            'model' => $model,
        ]);
    }
    public function actionResetpassword($cellphone)
    {
        try {
            $model = new ResetPasswordForm($cellphone);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', '新密码已经保存.');
            return $this->goHome();
        }
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
//联系我们
    public function actionContact()
    {
        return $this->render('contact');
    }
//企业简介
    public function actionAbout()
    {
        return $this->render('about');
    }
}
