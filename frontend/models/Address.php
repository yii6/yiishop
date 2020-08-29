<?php

namespace frontend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property int $user_id
 * @property string $user_name
 * @property string $cellphone
 * @property int $province
 * @property string $province_name
 * @property int $city
 * @property string $city_name
 * @property int $district
 * @property string $district_name
 * @property string $detailed
 */
class Address extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'address';
    }
    public function behaviors()
    {
        return [
            [
                'class'              => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => null,
                'value'              => yii::$app->user->id,
            ],
        ];
    }
    public function rules()
    {
        return [
            [['user_name', 'cellphone', 'province', 'province_name', 'city', 'city_name', 'detailed'], 'required'],
            [['user_name','district_name'], 'string', 'max' => 30],
            ['cellphone', 'match', 'pattern' => '/^[1][0-9]{10}$/'],
            [['province', 'city','district'], 'integer', 'message' => '请选择您所在的城市。若列表中没有请选择一个离您较近的。'],
            [['detailed'], 'string', 'max' => 60],
            [['user_name', 'cellphone', 'detailed'], 'trim'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'user_id'       => 'User ID',
            'user_name'     => '收货人姓名',
            'cellphone'     => '手机',
            'province'      => 'Province',
            'province_name' => 'Province Name',
            'city'          => 'City',
            'city_name'     => 'City Name',
            'district'      => 'District',
            'district_name' => 'District Name',
            'detailed'      => '详细地址',
        ];
    }
}
