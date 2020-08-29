<?php
namespace frontend\controllers;

use common\models\PostExtends;
use common\models\Posts;
use common\models\RelationPostTags;
use common\models\Tags;
use yii;
use yii\data\Pagination;

class PostController extends \yii\web\Controller
{
//资讯首页
    public function actionIndex()
    {
        $this->layout   = 'post';
        $curPage        = yii::$app->request->get('page', 1);
        $condition      = 1;
        $res            = Posts::getList($condition, $curPage);
        $result['body'] = $res['data'] ?: [];
        $cache          = yii::$app->cache;
        $key            = 'news-index';
        if (!$tech = $cache->get($key)) {
            $tech = $result;
            $dep  = new \yii\caching\DbDependency([
                'sql' => 'select max(updated_at) from {{%posts}}',
            ]);
            $cache->set($key, $tech, 3600, $dep);
        }
        $pages          = new Pagination(['totalCount' => $res['count'], 'pageSize' => $res['pageSize']]);
        $result['page'] = $pages;
        return $this->render('index', ['data' => $result]);
    }
//资讯关键词页面
    public function actionTag($id)
    {
        $this->layout = 'post';
        $curPage      = yii::$app->request->get('page', 1);
        $relation     = RelationPostTags::find()->where('tag_id=:id', [':id' => $id])->asArray()->all();
        $ids          = '(';
        foreach ($relation as $key => $value) {
            $ids .= $value['post_id'] . ',';
        }
        $ids            = substr_replace($ids, ")", -1);
        $condition      = 'id in ' . $ids;
        $res            = Posts::getList($condition, $curPage);
        $result['body'] = $res['data'] ?: [];
        $cache          = yii::$app->cache;
        $key            = 'news-tag' . $id;
        if (!$tech = $cache->get($key)) {
            $tech = $result;
            $dep  = new \yii\caching\DbDependency([
                'sql' => 'select max(updated_at) from {{%posts}}',
            ]);
            $cache->set($key, $tech, 3600, $dep);
        }
        $pages               = new Pagination(['totalCount' => $res['count'], 'pageSize' => $res['pageSize']]);
        $result['page']      = $pages;
        $result['breadword'] = Tags::find()->where('id=:id', [':id' => $id])->asArray()->one()['tag_name'];
        return $this->render('tag', ['data' => $result]);
    }
    //资讯分类页面
    public function actionCate($id)
    {
        $this->layout        = 'post';
        $curPage             = yii::$app->request->get('page', 1);
        $condition           = ['=', 'cat_id', $id];
        $res                 = Posts::getList($condition, $curPage);
        $result['body']      = $res['data'] ?: [];
        $pages               = new Pagination(['totalCount' => $res['count'], 'pageSize' => $res['pageSize']]);
        $result['page']      = $pages;
        $result['breadword'] = $result['body']['0']['cat']['cat_name'];
        return $this->render('cate', ['data' => $result]);
    }
//资讯浏览
    public function actionView($id)
    {
        $this->layout = 'post';
        $model        = new Posts();
        $data         = $model->getViewById($id);
        //浏览统计
        $review = new PostExtends();
        $review->upCounter(['post_id' => $id], 'browser', 1);
        return $this->render('view', ['data' => $data]);
    }
    protected function findModel($id)
    {
        if (($model = Posts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('请求访问的资讯页面不存在.');
        }
    }
}
