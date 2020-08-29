<?php
namespace backend\controllers;

use common\models\Category;
use common\models\Posts;
use backend\models\PostSearch;
use common\models\RelationPostTags;
use common\models\Tags;
use crazyfd\qiniu\Qiniu;
use frontend\controllers\BaseController;
use yii;
use yii\web\NotFoundHttpException;

class PostController extends BaseController
{
    public function actions()
    {
        return [
            'ueditor' => [
                'class' => 'common\widgets\ueditor\UeditorAction', //编辑器插件
            ],
        ];
    }
//文章列表
    public function actionIndex()
    {
        $searchModel  = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
//写文章
    public function actionCreate()
    {
        $model  = new Posts();
        $result = Category::getCates(4);
        if (yii::$app->request->isPost) {
            $post = yii::$app->request->post();
            $pics = $this->upload();
            if (!$pics) {
                $model->addError('label_img', '标签图片不能为空');
            } else {
                $post['Posts']['label_img'] = $pics['label_img'];
            }
            if (!$model->create($post)) {
                Yii::$app->session->setFlash('warning', $model->_lastError);
            } else {
                return $this->redirect(['post/index']);
            }
        }
        return $this->render('create', ['model' => $model, 'cates' => $result]);
    }
//图片上传函数
    public function upload()
    {
        $label_img = '';
        $qiniu     = new Qiniu(BaseController::AK, BaseController::SK, BaseController::DOMAIN, BaseController::BUCKET, BaseController::ZONE);
        if ($_FILES['Posts']['error']['label_img'] == 0) {
            $key = time();
            $qiniu->uploadFile($_FILES['Posts']['tmp_name']['label_img'], $key);
            $label_img = $qiniu->getLink($key);
        }
        return ['label_img' => $label_img];
    }
//文章浏览
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionUpdate($id)
    {
        $model       = Posts::findOne($id);
        $result = Category::getCates(4);
        $res         = Posts::find()->with('relates.tag')->where(['id' => $id])->asArray()->one();
        $res['tags'] = '';
        if (isset($res['relates']) && !empty($res['relates'])) {
            foreach ($res['relates'] as $key => $list) {
                $res['tags'] = $res['tags'] . ',' . $list['tag']['tag_name'];
                Tags::findOne($list['tag_id'])->updateCounters(['post_num' => -1]);
            }
        }
        RelationPostTags::deleteAll(['post_id' => $id]);
        $model->tags = substr($res['tags'], 1);
        if (yii::$app->request->isPost) {
            $post = yii::$app->request->post();
            $pics = $this->upload();
            if ($pics['label_img']) {
                $post['Posts']['label_img'] = $pics['label_img'];
            } else {
                unset($post['Posts']['label_img']);
            }
            if (!$model->modify($post, $id)) {
                Yii::$app->session->setFlash('warning', $model->_lastError);
            } else {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model, 'cates' => $result,
        ]);
    }
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }
    protected function findModel($id)
    {
        if (($model = Posts::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('请求的页面不存在.');
    }
}
