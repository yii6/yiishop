<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "sms".
 *
 * @property int $id
 * @property string $cellphone 手机号
 * @property string $code 验证码
 * @property int $used 是否使用
 * @property int $updated_at 使用时间
 * @property int $created_at 发送时间
 */

class Sms extends ActiveRecord
{
    public static function tableName()
    {
        return 'sms';
    }
    public function rules()
    {
        return [
            // [['updated_at', 'created_at'], 'integer'],
            // [['cellphone'], 'string', 'max' => 15],
            // [['code'], 'string', 'max' => 6],
            // [['used'], 'string', 'max' => 1],
            [['userCode'], 'number', 'min' => 100000, 'max' => 999999],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'cellphone'  => '手机号',
            'code'       => '验证码',
            'userCode'   => '验证码',
            'used'       => '是否使用',
            'used_at' => '使用时间',
            'created_at' => '创建时间',
        ];
    }
}
