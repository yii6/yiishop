<?php

namespace common\models;

use \yii\db\ActiveRecord;
/**
 * This is the model class for table "posts".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $created_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Base extends ActiveRecord
{
    public static function getPages($query,$curPage=1,$pageSize=5){
        $data['count']=$query->count();
        if(!$data['count']){
            return ['count'=>0,'curPage'=>$curPage,'pageSize'=>$pageSize,'start'=>0,'end'=>0,
            'data'=>[]];
        }
        //超过实际页数,不取 curPage为当前页
        $curPage=(ceil($data['count']/$pageSize)<$curPage)?ceil($data['count']/$pageSize):$curPage;
        $data['curPage']=$curPage;
        $data['pageSize']=$pageSize;
        //起始条数,如 11条数据,每页5条,第一页是1-5,第三页是11-11,$data['count']是总条数11
        $data['start']=($curPage-1)*$pageSize+1;
        $data['end']=(ceil($data['count']/$pageSize)==$curPage)?$data['count']:$curPage*$pageSize;
        //取出的数据
        $data['data']=$query->offset(($curPage-1)*$pageSize)
        ->limit($pageSize)->asArray()->all();
        return $data;
    }
}
