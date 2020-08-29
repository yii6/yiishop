<?php

namespace frontend\controllers;

use common\models\Category;
use common\models\Product;
use frontend\models\Cart;
use frontend\models\PSF;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['addtocart', 'cate', 'search', 'view'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                    [
                        'actions' => ['cate', 'search', 'view'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                ],
            ],
        ];
    }
    public function actionView($id)
    {
        $this->layout = 'product';
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('请求展示的产品不存在.');
        }
    }
    public function actionCate($orderBy = ['id' => SORT_DESC])
    {
        $this->layout = 'product';
        $model        = new PSF();
        $get          = yii::$app->request->get();
        while (isset($get['condition'])) {
            $get = $get['condition'];
        }
        $cate        = $get['PSF']['cate'];
        $model->cate = $cate;
        $condition   = 'cate=' . $cate;
        if (isset($get['PSF']['band']) && $get['PSF']['band']) {
            $band        = $get['PSF']['band'];
            $model->band = $band;
            $band        = implode(',', $band);
            $condition .= ' and band in (' . $band . ')';
        }
        if (isset($get['PSF']['series']) && $get['PSF']['series']) {
            $serial = $get['PSF']['series'];
            $condition .= ' and series=' . $serial;
            $model->series = $serial;
        }
        $curPage = yii::$app->request->get('page', 1);
        if ($orderBy == 2) {
            $orderBy = ['sale' => SORT_DESC];
        } elseif ($orderBy == 3) {
            $orderBy = ['price' => SORT_ASC];
        } elseif ($orderBy == 1) {
            $orderBy = ['id' => SORT_DESC];
        }
        $result = $model->getCate($curPage, $condition, 18, $orderBy);
        if (!isset($result['body'])) {
            return $this->render('nomatch');
        }
        return $this->render('cate', ['model' => $model, 'data' => $result]);
    }
    public function actionSearch($keyword)
    {
        $this->layout = 'product';
        $keyword=htmlspecialchars($keyword, ENT_QUOTES);
        $types        = Category::find()->select('cat_name')->where('cat_name like \'%' . $keyword . '%\' and pid=1')->asArray()->all();
        if (!count($types)) {
            return $this->render('nomatch');
        }
        $arr = [];
        foreach ($types as $value) {
            $arr[] = "'" . $value['cat_name'] . "'";
        }
        $type      = implode(',', $arr);
        $condition = "type_name in (" . $type . ")";
        $cates     = [];
        $bands     = [];
        $series    = [];
        $products  = Product::find()->where($condition)->asArray()->all();
        foreach ($products as $value) {
            $cates[$value['cate']]    = $value['cate_name'];
            $bands[$value['band']]    = $value['band_name'];
            $series[$value['series']] = $value['series_name'];
        }
        $model   = new PSF();
        $curPage = yii::$app->request->get('page', 1);
        if (isset(yii::$app->request->get()['PSF']['cate']) && yii::$app->request->get()['PSF']['cate']) {
            $cate        = yii::$app->request->get()['PSF']['cate'];
            $model->cate = $cate;
            $condition .= ' and cate=' . $cate;
        }
        if (isset(yii::$app->request->get()['PSF']['band']) && yii::$app->request->get()['PSF']['band']) {
            $band        = yii::$app->request->get()['PSF']['band'];
            $model->band = $band;
            $band        = implode(',', $band);
            $condition .= ' and band in (' . $band . ')';
        }
        if (isset(yii::$app->request->get()['PSF']['series']) && yii::$app->request->get()['PSF']['series']) {
            $serial = yii::$app->request->get()['PSF']['series'];
            $condition .= ' and series=' . $serial;
            $model->series = $serial;
        }
        $result           = $model->getCate($curPage, $condition);
        $result['cate']   = $cates;
        $result['band']   = $bands;
        $result['series'] = $series;
        return $this->render('search', ['model' => $model, 'data' => $result]);
    }
    public function actionAddtocart()
    {
        $get = yii::$app->request->get();
        foreach ($get as $key => $value) {
            if ($value == '加入购物车') {
                $product = Product::findOne($key);
                if ($model = Cart::findOne(['product_id' => $key, 'user_id' => Yii::$app->user->identity->id, 'settled' => false])) {
                    $model->amount += $get['amount'];
                } else {
                    $model               = new Cart();
                    $model->amount       = $get['amount'];
                    $model->product_id   = $key;
                    $model->product_name = $product->band_name . " " . $product->cate_name . " " . $product->type_name;
                    $model->price        = $product->price;
                    $model->label_img    = $product->label_img;
                }
                if (!$model->save()) {
                    Yii::$app->session->setFlash('failed', '添加失败，请重新操作。');
                }
                return $this->redirect(['view', 'id' => $product->id]);
            }
            if ($value == '立即购买') {
                return $this->redirect(['order/confirm', 'product_id' => $key, 'amount' => $get['amount']]);
            }
        }
    }
}
