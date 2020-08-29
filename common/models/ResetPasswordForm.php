<?php
namespace common\models;

use common\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $rePassword;
    private $_user;
    public function __construct($cellphone)
    {
        $this->_user = User::findOne(['cellphone' => $cellphone]);
        if (!$this->_user) {
            throw new InvalidParamException('没有该手机用户.');
        }
    }
    public function rules()
    {
        return [
            [['password', 'rePassword'], 'required'],
            [['password', 'rePassword'], 'string', 'min' => 6],
            ['rePassword', 'compare', 'compareAttribute' => 'password'],
        ];
    }
//重置密码
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        return $user->save(false);
    }
    public function attributeLabels()
    {
        return [
            'rePassword' => '确认密码',
            'password'   => '密码',
        ];
    }
}
