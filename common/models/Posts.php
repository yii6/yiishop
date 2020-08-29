<?php

namespace common\models;

use common\models\Category;
use common\models\RelationPostTags;
use common\models\Base;
use crazyfd\qiniu\Qiniu;
use frontend\controllers\BaseController;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "posts".
 *
 * @property int $id 自增ID
 * @property string $title 标题
 * @property string $summary 摘要
 * @property string $content 内容
 * @property string $label_img 标签图
 * @property int $cat_id 分类id
 * @property string $author_name 作者
 * @property int $created_id 创建者id
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Posts extends Base
{
    public $_lastError = "";
    public $tags;
    const EVENT_AFTER_CREATE = 'eventAfterCreate';
    public static function tableName()
    {
        return 'posts';
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
            [['content'], 'string'],
            [['cat_id', 'created_id', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 80],
            [['summary', 'label_img'], 'string', 'max' => 255],
            [['author_name'], 'string', 'max' => 30],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'title'       => '标题',
            'summary'     => '摘要',
            'content'     => '内容',
            'label_img'   => '标签图',
            'cat_id'      => '分类',
            'author_name' => '作者',
            'created_id'  => '创建者id',
            'created_at'  => '创建时间',
            'updated_at'  => '更新时间',
        ];
    }
    public function getRelates()
    {
        return $this->hasMany(RelationPostTags::className(), ['post_id' => 'id']);
    }
    public function getExtend()
    {
        return $this->hasOne(PostExtends::className(), ['post_id' => 'id']);
    }
    public function getCat()
    {
        return $this->hasOne(Category::className(), ['id' => 'cat_id']);
    }
    public function create($data)
    {
        $transaction = yii::$app->db->beginTransaction();
        try {
            $this->title       = $data['Posts']['title'];
            $this->cat_id      = $data['Posts']['cat_id'];
            $this->label_img   = $data['Posts']['label_img'];
            $this->content     = $data['Posts']['content'];
            $this->summary     = $this->_getSummary($data['Posts']['content']);
            if (!$this->save()) {
                throw new \Exception("文章保存失败");
            }
            $arr         = $this->getAttributes();
            $arr['tags'] = $data['Posts']['tags'];
            $this->_eventAfterCreate($arr);
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->_lastError = $e->getMessage();
            return false;
        }
    }
    public function modify($data, $id)
    {
        $transaction = yii::$app->db->beginTransaction();
        try {
            $model = self::findOne($id);
            $qiniu = new Qiniu(BaseController::AK, BaseController::SK, BaseController::DOMAIN, BaseController::BUCKET);
            if (isset($data['Posts']['label_img'])) {
                $key = basename($model->label_img);
                $qiniu->delete($key);
                $model->label_img = $data['Posts']['label_img'];
            }
            $model->title       = $data['Posts']['title'];
            $model->content     = $data['Posts']['content'];
            $model->cat_id      = $data['Posts']['cat_id'];
            $model->summary     = $this->_getSummary($data['Posts']['content']);
           // $model->author_name = $data['Posts']['author_name'];
            if (!$model->save()) {
                throw new \Exception("文章保存失败");
            }
            $arr         = $this->getAttributes();
            $arr['tags'] = $data['Posts']['tags'];
            $this->_eventAfterCreate($arr);
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->_lastError = $e->getMessage();
            return false;
        }
    }
    //截取文章摘要
    private function _getSummary($content, $start = 0, $length = 115, $char = 'utf-8')
    {
        if (empty($content)) {
            return null;
        }

        return (mb_substr(str_replace('&nbsp;', '', strip_tags($content)), $start, $length, $char));
    }
//文章发表后的事件
    public function _eventAfterCreate($data)
    {
        //on绑定事件,第二个参数为执行的函数,$data为传递的数据
        $this->on(self::EVENT_AFTER_CREATE, [$this, '_eventAddTag'], $data);
        $this->trigger(self::EVENT_AFTER_CREATE);
    }
//添加 标签 及 标签-文章关系
    public function _eventAddTag($event)
    {
        $tag       = new TagForm();
        $tag->tags = $event->data['tags']; //data可以取到数据
        $tagids    = $tag->saveTags();
        //删除原来的关联关系
        RelationPostTags::deleteAll(['post_id' => $event->data['id']]);
        //批量保存新的文章标签关联关系
        if (!empty($tagids)) {
            foreach ($tagids as $key => $id) {
                $row[$key]['post_id'] = $this->id;
                $row[$key]['tag_id']  = $id;
            }
            //批量插入
            $res = (new Query())->createCommand()
                ->batchInsert(RelationPostTags::tableName(), ['post_id', 'tag_id'], $row)
                ->execute();
            if (!$res) {
                throw new \Exception("关联关系保存失败");
            }

        }
    }
    public function getViewById($id)
    {
        //relates是Posts里面的 getRelates()函数,tag是RelationPostTags里面的 getTag()函数
        $select = ['title','created_at','label_img','content','id'];
        $res = self::find()->select($select)->with('relates.tag')->where(['id' => $id])->asArray()->one();
        if (!$res) {
            throw new NotFoundHttpException("文章不存在!");
        }
        $res['tags']=[];
        if (isset($res['relates']) && !empty($res['relates'])) {
                foreach ($res['relates'] as $value) {
                   $res['tags'][$value['tag']['id']] = $value['tag']['tag_name'];
                }
            }
        unset($res['relates']);
        return $res;
    }

    public static function getList($condition, $curPage = 1, $pageSize = 5, $orderBy = ['id' => SORT_DESC])
    {
        $select = ['id', 'title', 'summary', 'label_img','created_at','cat_id'];
        $query = self::find()->select($select)->where($condition)->with('relates.tag','cat')
            ->orderBy($orderBy);
        //获取分页数据
        $res         = self::getPages($query, $curPage, $pageSize);
        $res['data'] = self::_formatList($res['data']);
        return $res;
    }
//数据格式化
    public static function _formatList($data)
    {
        foreach ($data as $k=>$list) {
            $data[$k]['tags'] = [];
            if (isset($list['relates']) && !empty($list['relates'])) {
                foreach ($list['relates'] as $j=>$value) {
                    $data[$k]['tags'][$value['tag']['id']] = $value['tag']['tag_name'];
                }
            }
            unset($data[$k]['relates']);
        }
        return $data;
    }
}
