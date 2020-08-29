<?php

namespace frontend\controllers;

use frontend\models\Cart;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;

class CartController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'delete', 'settlement'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $this->layout   = 'product';
        $curPage        = yii::$app->request->get('page', 1);
        $select         = ['amount', 'price', 'product_name', 'label_img', 'created_at', 'user_id', 'id'];
        $query          = Cart::find()->where('user_id=:id and settled=false', [':id' => Yii::$app->user->identity->id])->orderBy(['created_at' => SORT_DESC]);
        $result         = Cart::getPages($query, $curPage, 8);
        $pages          = new Pagination(['totalCount' => $result['count'], 'pageSize' => $result['pageSize']]);
        $result['page'] = $pages;
        return $this->render('index', ['data' => $result]);
    }
    public function actionDelete($id)
    {
        $model = Cart::findOne($id)->delete();
        return $this->redirect(['index']);
    }
    public function actionSettlement()
    {
        $get = yii::$app->request->get();
        if ($get['settle'] == '删除选中商品') {
            foreach ($get as $key => $value) {
                if ($value == 'on') {
                    Cart::findOne($key)->delete();
                }
            }
            return $this->redirect(['index']);
        }
        if ($get['settle'] == '结算') {
            $cart_ids=[];
            foreach ($get as $key => $value) {
                if ($value == 'on') {
                    $cart=Cart::findOne($key);
                    $cart->settled=true;
                    $cart->amount=$get['no'.$key];
                    $cart->save();
                    $cart_ids[]=$key;
                }
            }
            return $this->redirect(['order/confirm','cart_ids'=>$cart_ids]);
        }
    }
}
