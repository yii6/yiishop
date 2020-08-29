<?php

namespace frontend\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "combine_order".
 *
 * @property int $id
 * @property string $order_ids
 * @property int $created_at
 * @property int $user_id
 */
class CombineOrder extends \yii\db\ActiveRecord
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
        return 'combine_order';
    }
    public function rules()
    {
        return [
            [['order_ids'], 'required'],
            [['order_ids'], 'string', 'max' => 256],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'order_ids'  => 'Order Ids',
            'created_at' => 'Created At',
            'user_id'    => 'User ID',
        ];
    }
}
