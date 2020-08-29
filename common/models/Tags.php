<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "tags".
 *
 * @property integer $id
 * @property string $tag_name
 * @property integer $post_num
 */
class Tags extends ActiveRecord
{
    public static function tableName()
    {
        return 'tags';
    }
    public function behaviors()
    {
        return [
            [
                'class'=>TimestampBehavior::className(),
                'createdAtAttribute'=>'created_at',
                'updatedAtAttribute'=>'updated_at',
                'attributes'=>[
                    ActiveRecord::EVENT_BEFORE_INSERT=>['created_at','updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE=>['updated_at'],
                ]
            ]
        ];
    }
    public function rules()
    {
        return [
            [['post_num'], 'integer'],
            [['tag_name'], 'string', 'max' => 255],
            [['tag_name'], 'unique'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tag_name' => '标签名',
            'post_num' => '文章数',
        ];
    }
}
