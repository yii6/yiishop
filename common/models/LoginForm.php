<?php
namespace common\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    private $_user;
    public $verifyCode;
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
            ['verifyCode', 'captcha'],
        ];
    }
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser());
        }
        return false;
    }
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::find()->where('username=:username or cellphone=:username',[':username'=>$this->username])->one();
        }
        return $this->_user;
    }
    public function attributeLabels()
    {
        return [
            'username'   => '用户名',
            'password'   => '密码',
            'verifyCode' => '验证码',
        ];
    }
}
