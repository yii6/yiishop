<?php
namespace common\models;

use common\models\User;
use Yii;
use yii\base\Model;
/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $cellphone;
    public $password;
    public $rePassword;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'cellphone'], 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 16],
            //正则表达式/......./u表示匹配unicode字符,不加 u不能匹配汉字
            ['username', 'match', 'pattern' => '/^[(\x{4E00}-\x{9FA5})a-zA-Z]+[(\x{4E00}-\x{9FA5})a-zA-Z_\d]*$/u', 'message' => '用户名由字母，汉字，数字，下划线组成，且不能以数字和下划线开头。'],
            ['cellphone', 'required'],
            ['cellphone','match','pattern'=>'/^[1][0-9]{10}$/'],
            [['password', 'rePassword'], 'required'],
            [['password', 'rePassword'], 'string', 'min' => 6],
            ['rePassword', 'compare', 'compareAttribute' => 'password'],
            ['verifyCode', 'captcha'],
            ['cellphone', 'unique', 'targetClass' => '\common\models\User', 'message' => '手机号已经注册.'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => '此用户名已经被使用.'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'username'   => '用户名',
            'password'   => '密码',
            'cellphone'      => '手机',
            'rePassword' => '确认密码',
            'verifyCode' => '验证码',
        ];
    }
//用户注册
    public function signup($data)
    {
        $user           = new User();
        $user->username = $data['SignupForm']['username'];
        $user->cellphone    = $data['SignupForm']['cellphone'];
        $user->setPassword($data['SignupForm']['password']);
        $user->generateAuthKey();
        $user->created_ip = ip2long(yii::$app->request->userIP);
        $user->login_ip   = ip2long(yii::$app->request->userIP);
        return $user->save()? $user : null;
    }
}
