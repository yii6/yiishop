<?php

namespace frontend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;
use common\models\Base;
/**
 * This is the model class for table "cart".
 *
 * @property int $id
 * @property int $product_id
 * @property string $product_name
 * @property int $amount
 * @property double $price
 * @property string $label_img
 * @property int $created_at
 * @property int $user_id
 @property int $paid
 */
class Cart extends Base
{
    public static function tableName()
    {
        return 'cart';
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
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'attributes'         => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }
    public function rules()
    {
        return [
            [['product_id', 'product_name', 'amount', 'price', 'label_img'], 'required'],
            [['product_id', 'amount'], 'integer'],
            [['price'], 'number'],
            [['product_name'], 'string', 'max' => 60],
            [['label_img'], 'string', 'max' => 30],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'product_id'   => 'Product ID',
            'product_name' => '商品名称',
            'amount'       => '数量',
            'price'        => '单价',
            'label_img'    => '商品图片',
            'created_at'   => '创建时间',
            'user_id'      => '用户',
        ];
    }
}
