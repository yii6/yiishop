<?php

namespace common\models;

use common\models\Base;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "user_order".
 *
 * @property int $id
 * @property int $product_id
 * @property string $product_name
 * @property int $amount
 * @property string $price
 * @property string $label_img
 * @property int $created_at
 * @property int $user_id
 * @property int $paid
 * @property int $address_id
 * @property string $address_name
 */
class UserOrder extends Base
{
    public function behaviors()
    {
        return [
            [
                'class'              => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => null,
                'value'              => yii::$app->user->id,
            ],
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'attributes'         => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }
    public static function tableName()
    {
        return 'user_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address_id','paid'], 'integer'],
            [['address'], 'string', 'max' => 120],
            [['total'], 'number'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'cart_ids'   => '商品',
            'created_at' => '创建时间',
            'user_id'    => 'User ID',
            'paid'       => '订单状态',
            'address_id' => 'Address ID',
            'address'    => '地址',
            'total'      => '实付款',
            'name'       => '收货人姓名',
            'cellphone'=>'手机',
            'order_no'=>'订单号',
        ];
    }
}
