<?php
namespace common\models;

use common\models\User;
use yii\base\Model;

class PasswordResetRequestForm extends Model
{
    public $cellphone;
    public function rules()
    {
        return [
            ['cellphone', 'trim'],
            ['cellphone', 'required'],
            ['cellphone', 'match', 'pattern' => '/^[1][0-9]{10}$/'],
            ['cellphone', 'exist',
                'targetClass' => '\common\models\User',
                'filter'      => ['status' => User::STATUS_ACTIVE],
                'message'     => '没有使用这个手机号的用户.',
            ],
        ];
    }
    public function attributeLabels(){
        return [
            'cellphone'=>'手机号'
        ];
    }
}
