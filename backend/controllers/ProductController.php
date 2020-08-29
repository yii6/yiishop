<?php

namespace backend\controllers;

use backend\models\ProductSearch;
use common\models\Category;
use common\models\Product;
use crazyfd\qiniu\Qiniu;
use frontend\controllers\BaseController;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class ProductController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'index', 'delete', 'update', 'view', 'removeimg', 'ueditor', 'series', 'removepics', 'removeimg'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actions()
    {
        return [
            'ueditor' => [
                'class' => 'common\widgets\ueditor\UeditorAction', //编辑器插件
            ],
        ];
    }
    public function actionIndex()
    {
        $searchModel  = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
//添加产品介绍
    public function actionCreate()
    {
        $model = new Product();
        $dep   = new \yii\caching\DbDependency([
            'sql' => 'select max(updated_at) from {{%category}}',
        ]);
        $result['cates']  = Category::getCates(2);
        $result['bands']  = Category::getCates(3);
        $result['types']  = Category::getCates(1);
        $result['series'] = Category::getCates(9);//初始系列
        if (yii::$app->request->isPost) {
            $post = yii::$app->request->post();
            $pics = $this->upload();
            if ($pics['label_img']) {
                $post['Product']['label_img'] = $pics['label_img'];
            } else {
                unset($post['Product']['label_img']);
            }
            if (!empty($pics['pics'])) {
                $post['Product']['pics'] = json_encode($pics['pics']);
            } else {
                unset($post['Product']['pics']);
            }
            $post['Product']['cate_name'] =
            Category::find()->where('id=:id', [':id' => $post['Product']['cate']])->asArray()->one()['cat_name'];
            $post['Product']['band_name'] =
            Category::find()->where('id=:id', [':id' => $post['Product']['band']])->asArray()->one()['cat_name'];
            $post['Product']['type_name'] =
            Category::find()->where('id=:id', [':id' => $post['Product']['type']])->asArray()->one()['cat_name'];
            $post['Product']['series_name'] =
            Category::find()->where('id=:id', [':id' => $post['Product']['series']])->asArray()->one()['cat_name'];
            if ($model->load($post) && $model->save()) {
                return $this->redirect(['product/index']);
            } else {
                Yii::$app->session->setFlash('warning', $model->_lastError);
            }
        }
        return $this->render('create', ['model' => $model, 'cates' => $result]);
    }
    public function actionSeries()
    {
        if (Yii::$app->request->isAjax) {

            $id     = Yii::$app->request->post()['id'];
            $series = Category::getCates($id);
            foreach ($series as $key => $value) {
                echo "<option value='" . $key . "'>" . $value . "</option>";
            }
        }
    }
//图片上传函数
    public function upload()
    {
        $label_img = '';
        $qiniu     = new Qiniu(BaseController::AK, BaseController::SK, BaseController::DOMAIN, BaseController::BUCKET, BaseController::ZONE);
        if ($_FILES['Product']['error']['label_img'] == 0) {
            $key = time();
            $qiniu->uploadFile($_FILES['Product']['tmp_name']['label_img'], $key);
            //获得图片外链
            $label_img = $qiniu->getLink($key);
        }
        //获得多个图片的外链
        $pics = [];
        foreach ($_FILES['Product']['tmp_name']['pics'] as $k => $file) {
            if ($_FILES['Product']['error']['pics'][$k] > 0) {
                continue;
            }
            $keys = time() . rand(0, 100);
            $qiniu->uploadFile($file, $keys);
            $pics[$keys] = $qiniu->getLink($keys);
        }
        return ['label_img' => $label_img, 'pics' => $pics];
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $dep   = new \yii\caching\DbDependency([
            'sql' => 'select max(updated_at) from {{%category}}',
        ]);
        $result['cates']  = Category::getCates(2);
        $result['bands']  = Category::getCates(3);
        $result['types']  = Category::getCates(1);
        $result['series'] = Category::getCates($model->cate);
        if (yii::$app->request->isPost) {
            $post  = yii::$app->request->post();
            $pics  = $this->upload();
            $qiniu = new Qiniu(BaseController::AK, BaseController::SK, BaseController::DOMAIN, BaseController::BUCKET, BaseController::ZONE);
            if ($pics['label_img']) {
                if ($model->label_img) {
                    $key = basename($model->label_img);
                    $qiniu->delete($key);
                }
                $post['Product']['label_img'] = $pics['label_img'];
            } else {
                unset($post['Product']['label_img']);
            }
            if (!empty($pics['pics'])) {
                if ($model->pics) {
                    $post['Product']['pics'] = json_encode(array_merge(json_decode($model->pics, true), $pics['pics']));
                } else {
                    $post['Product']['pics'] = json_encode($pics['pics']);
                }
            } else {
                unset($post['Product']['pics']);
            }
            $post['Product']['cate_name'] =
            Category::find()->where('id=:id', [':id' => $post['Product']['cate']])->asArray()->one()['cat_name'];
            $post['Product']['band_name'] =
            Category::find()->where('id=:id', [':id' => $post['Product']['band']])->asArray()->one()['cat_name'];
            $post['Product']['type_name'] =
            Category::find()->where('id=:id', [':id' => $post['Product']['type']])->asArray()->one()['cat_name'];
            $post['Product']['series_name'] =
            Category::find()->where('id=:id', [':id' => $post['Product']['series']])->asArray()->one()['cat_name'];
            if ($model->load($post) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('warning', $model->_lastError);
            }
        }
        return $this->render('update', ['model' => $model, 'cates' => $result]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $qiniu = new Qiniu(BaseController::AK, BaseController::SK, BaseController::DOMAIN, BaseController::BUCKET, BaseController::ZONE);
        if ($model->label_img) {
            $key = basename($model->label_img);
            $qiniu->delete($key);
        }
        if ($model->pics) {
            $pics = json_decode($model->pics, true);
            foreach ($pics as $key => $file) {
                $qiniu->delete($key);
            }
        }
        $model->delete();
        return $this->redirect(['index']);
    }
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    // 删除封面图片
    public function actionRemoveimg($keyurl, $id)
    {
        $model = Product::findOne($id);
        $qiniu = new Qiniu(BaseController::AK, BaseController::SK, BaseController::DOMAIN, BaseController::BUCKET, BaseController::ZONE);
        $qiniu->delete(basename($keyurl));
        Product::updateAll(['label_img' => null], 'id = :id', [':id' => $id]);
        return $this->redirect(['product/update', 'id' => $id]);
    }
    // 删除详细图片
    public function actionRemovepics($key, $id) //只有一张图片删除会bug

    {
        $model = Product::findOne($id);
        $qiniu = new Qiniu(BaseController::AK, BaseController::SK, BaseController::DOMAIN, BaseController::BUCKET, BaseController::ZONE);
        $qiniu->delete($key);
        $pics = json_decode($model->pics, true);
        foreach ($pics as $k => $value) {
            if($value=='yii6.com/'.$key)
            unset($pics[$k]);
        }
        Product::updateAll(['pics' => json_encode($pics)], 'id = :id', [':id' => $id]);
        return $this->redirect(['product/update', 'id' => $id]);
    }
}
