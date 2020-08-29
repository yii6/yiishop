<?php

namespace common\models;

use common\models\Base;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $cate
 * @property string $cate_name
 * @property integer $band
 * @property string $band_name
 * @property integer $type
 * @property string $type_name
 * @property double $price
 * @property string $describe
 * @property string $label_img
 */
class Product extends Base
{
    public $_lastError = "";
    public static function tableName()
    {
        return 'product';
    }
    public function behaviors()
    {
        return [
            [
                'class'              => BlameableBehavior::className(),
                'createdByAttribute' => 'created_id',
                'updatedByAttribute' => null,
                'value'              => yii::$app->user->id,
            ],
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'attributes'         => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
    public function rules()
    {
        return [
            [['cate', 'band'], 'required'],
            [['cate', 'band', 'type', 'sale', 'series'], 'integer'],
            [['price'], 'number'],
            [['describe'], 'string'],
            [['cate_name', 'band_name', 'type_name', 'series_name'], 'string', 'max' => 50],
            [['label_img', 'pics'], 'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'cate'        => '类型',
            'band'        => '品牌',
            'type'        => '型号',
            'series'      => '系列',
            'cate_name'   => '类型',
            'band_name'   => '品牌',
            'type_name'   => '型号',
            'series_name' => '系列',
            'sale'        => '销量',
            'price'       => '价格',
            'describe'    => '详细描述',
            'label_img'   => '产品图片',
            'pics'        => '详细图片',
            'created_id'  => '创建者',
        ];
    }
    public static function getCate($condition, $curPage = 1, $pageSize = 16, $orderBy = ['sale' => SORT_DESC])
    {
        $select = ['cate_name', 'band_name', 'id', 'type_name', 'label_img', 'price'];
        $query  = self::find()->select($select)->where($condition)->orderBy($orderBy);
        //获取分页数据
        $res['data'] = self::getPages($query, $curPage, $pageSize);
        return $res;
    }
}
