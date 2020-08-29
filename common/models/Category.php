<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $cat_name
 */
class Category extends ActiveRecord
{
    public static function tableName()
    {
        return 'category';
    }
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'attributes'         => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            [
                'class'              => BlameableBehavior::className(),
                'createdByAttribute' => 'created_id',
                'updatedByAttribute' => null,
                'value'              => yii::$app->user->id,
            ],
        ];
    }
    public function rules()
    {
        return [
            [['pid'], 'integer'],
            [['pid'], 'required', 'on' => ['addcate']],
            [['cat_name'], 'required', 'on' => ['addcate']],
            [['cat_name'], 'string', 'max' => 40],
            [['cat_name'], 'unique', 'message' => '已经存在同名分类', 'on' => ['addcate']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'cat_name'   => '分类名称',
            'pid'        => '上级分类',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
    public static function getAllCates()
    {
        $res = self::find()->asArray()->all();
        if ($res) {
            foreach ($res as $key => $value) {
                $cates[$value['id']] = $value['cat_name'];
            }
        }
        return $cates;
    }
//获得相应父类下的所有子类
    public static function getCates($pid)
    {
        $res = self::find()->where('pid=:pid', [':pid' => $pid])->asArray()->all();
        if ($res) {
            foreach ($res as $key => $value) {
                $cates[$value['id']] = $value['cat_name'];
            }
        } else {
            return [];
        }
        return $cates;
    }
    public function getPrimaryCate($perpage = 6)
    {
        $data = self::find()->where('pid=:pid', [':pid' => 0]);
        if (empty($data)) {
            return [];
        }
        $pages = new \yii\data\Pagination(['totalCount' => $data->count(), 'pageSize' => $perpage]);
        $data  = $data->offset($pages->offset)->limit($pages->limit)->all();
        if (empty($data)) {
            return [];
        }
        $primary = [];
        foreach ($data as $key) {
            $primary[] = [
                'id'       => $key->id,
                'text'     => $key->cat_name,
                'children' => $this->getChild($key->id),
            ];
        }
        $cache = yii::$app->cache;
        $key   = 'pcate';
        if (!$pcate = $cache->get($key)) {
            $pcate = $primary;
            $dep   = new \yii\caching\DbDependency([
                'sql' => 'select max(updated_at) from {{%category}}',
            ]);
            $cache->set($key, $pcate, 3600, $dep);
        }
        return ['data' => $primary, 'pages' => $pages];
    }
    public function getChild($pid)
    {
        $data = self::find()->where('pid=:pid', [':pid' => $pid])->all();
        if (empty($data)) {
            return [];
        }
        $children = [];
        foreach ($data as $key) {
            $children[] = [
                'id'       => $key->id,
                'text'     => $key->cat_name,
                'children' => $this->getChild($key->id),
            ];
        }
        return $children;
    }
    //将数据库中的分类数据存入数组
    public function getdata()
    {
        $cates = self::find()->all();
        $cates = ArrayHelper::toArray($cates);
        return $cates;
    }
    //获得没有前缀的分类列表
    public function gettree($cates, $pid = 0, $level = 0, $prefix = '——')
    {
        static $tree = [];
        foreach ($cates as $cate) {
            if ($cate['pid'] == $pid) {
                $cate['prefix'] = str_repeat($prefix, $level);
                $tree[]         = $cate;
                $tree += $this->gettree($cates, $cate['id'], $level + 1);
            }
        }
        return $tree;
    }
    public function getOptions()
    {
        $tree    = $this->gettree($this->getdata());
        $options = ['顶级分类'];
        foreach ($tree as $cate) {
            $options[$cate['id']] = $cate['prefix'] . $cate['cat_name'];
        }
        return $options;
    }
    public function getTopcates()
    {
        $select = ['cat_name', 'id'];
        $query  = self::find()->select($select)->where('pid=0')->asArray()->all();
        $cache  = yii::$app->cache;
        $key    = 'top';
        if (!$hot = $cache->get($key)) {
            $hot = $query;
            $dep = new \yii\caching\DbDependency([
                'sql' => 'select max(updated_at) from {{%category}}',
            ]);
            $cache->set($key, $hot, 3600, $dep);
        }
        $options = ['顶级分类'];
        foreach ($query as $cate) {
            $options[$cate['id']] = $cate['cat_name'];
        }
        return $options;
    }
    //添加分类
    public function addcate($data)
    {
        $this->scenario = "addcate";
        $this->pid      = $data['Category']['pid'];
        $this->cat_name = $data['Category']['cat_name'];
        if ($this->save()) {
            return true;
        }
        return false;
    }
}
