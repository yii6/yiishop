<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "post_extends".
 *
 * @property int $id 自增ID
 * @property int $post_id 文章id
 * @property int $browser 浏览量
 * @property int $created_at
 * @property int $updated_at
 */
class PostExtends extends ActiveRecord
{
    public static function tableName()
    {
        return 'post_extends';
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
           [['post_id', 'browser', 'created_at', 'updated_at'], 'integer'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'browser' => 'Browser',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    //更新文章统计
    public function upCounter($condition,$browser,$num){
        $counter=$this->findOne($condition);
        if(!$counter){
            $this->setAttributes($condition);
            $this->browser=$num;
            $this->save();
        }else{
            $countData[$browser]=$num;
            $counter->updateCounters($countData);
        }
    }
}
