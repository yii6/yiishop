<?php

namespace common\models;
use common\models\Tags;
use Yii;

/**
 *
 * @property integer $id
 * @property string $tags\
 */
class TagForm extends \yii\base\Model
{
    public $id;
    public $tags;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tags'],'each','rule'=>['string', 'max' => 255]],//each表示遍历
            [['tags'], 'required'],
        ];
    }
    public function saveTags(){
        $ids=[];
        if(!empty($this->tags)){
            $arr=explode(',', $this->tags);
            foreach ($arr as $tag) {
                $ids[]=$this->_saveTag($tag);
            }
        }
        return $ids;
    }
    private function _saveTag($tag){
        $model=new Tags();
        $res=$model->find()->where(['tag_name'=>$tag])->one();
        if(!$res){
            $model->tag_name=$tag;
            $model->post_num=1;
            if(!$model->save()){
                throw new \Exception("保存标签失败");
            }
            return $model->id;
        }else{
            $res->updateCounters(['post_num'=>1]);//post_num增加 1
            return $res->id;
        }
    }
}
