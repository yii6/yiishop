<?php

namespace frontend\controllers;

use common\models\Category;
use common\models\Product;
use common\models\UserOrder;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\CombineOrder;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;

class OrderController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'delete', 'settlement', 'confirm', 'city', 'district', 'return_url', 'notify_url', 'pay', 'confirmreceipt'],
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
        $select         = ['cart_ids', 'id', 'created_at', 'paid', 'user_id', 'total', 'order_no'];
        $query          = UserOrder::find()->select($select)->where('user_id=:id', [':id' => Yii::$app->user->identity->id])->orderBy(['created_at' => SORT_DESC]);
        $result         = UserOrder::getPages($query, $curPage, 8);
        $pages          = new Pagination(['totalCount' => $result['count'], 'pageSize' => $result['pageSize']]);
        $result['page'] = $pages;
        return $this->render('index', ['data' => $result]);
    }
    public function actionDelete($id)
    {
        $model = UserOrder::findOne($id)->delete();
        return $this->redirect(['index']);
    }
    public function actionConfirmreceipt($id)
    {
        $user_order       = UserOrder::findOne($id);
        $user_order->paid = 3;
        $user_order->save();
        return $this->redirect(['index']);
    }
    public function actionPay($id)
    {
        $order    = UserOrder::findOne($id);
        $cart_ids = json_decode($order->cart_ids, true);
        $body     = [];
        foreach ($cart_ids as $key => $value) {
            $cart   = Cart::findOne($value);
            $body[] = $cart->product_name;
        }
        $subject = $cart->product_name . '等' . count($cart_ids) . '件商品';
        return $this->redirect(['pagepay/pagepay', 'subject' => $subject, 'total_amount' => $order->total, 'out_trade_no' => $order->order_no, 'body' => implode("、", $body)]);
    }
    public function actionSettlement()
    {
        $get = yii::$app->request->get();
        if ($get['settle'] == '取消选中订单') {
            foreach ($get as $key => $value) {
                if ($value == 'on') {
                    $user_order = UserOrder::findOne($key);
                    if ($user_order->paid == 0) {
                        $user_order->delete();
                    }
                }
            }
            return $this->redirect(['index']);
        }
        if ($get['settle'] == '批量确认收货') {
            foreach ($get as $key => $value) {
                if ($value == 'on') {
                    $user_order = UserOrder::findOne($key);
                    if ($user_order->paid == 1) {
                        $user_order->paid = 3;
                        $user_order->save();
                    }
                }
            }
            return $this->redirect(['index']);
        }
        if ($get['settle'] == '合并付款') {
            $out_trade_no = date('YmdHis', time()) . rand(100000, 999999);
            $total_amount = 0;
            $body         = [];
            $k            = 0;
            $model        = new CombineOrder();
            $orders       = [];
            foreach ($get as $key => $value) {
                if ($value == 'on') {
                    $order = UserOrder::findOne($key);
                    if ($order->paid == 0) {
                        $total_amount += $order->total;
                        ++$k;
                        $orders[] = $order->id;
                        $body[]   = '订单号为 ' . $order->order_no . ' 的订单';
                    }
                }
            }
            if ($k == 0) {
                return $this->redirect(['index']);
            }
            $model->order_ids    = json_encode($orders);
            $model->out_trade_no = $out_trade_no;
            $model->save();
            $subject = '商品汇-合并付款 | ' . $k . '笔订单';
            return $this->redirect(['pagepay/pagepay', 'subject' => $subject, 'total_amount' => $total_amount, 'out_trade_no' => $out_trade_no, 'body' => implode("、", $body)]);
        }
    }
    public function actionConfirm()
    {
        $this->layout = 'product';
        $get          = yii::$app->request->get();
        if (isset($get['cart_ids'])) {
            $total = 0;
            foreach ($get['cart_ids'] as $key) {
                $cart = Cart::findOne($key);
                $total += $cart->price * $cart->amount;
            }
            $result['total']    = $total;
            $result['cart_ids'] = $get['cart_ids'];
        } else if (isset($get['product_id'])) {
            $product            = Product::findOne($get['product_id']);
            $result['total']    = $product->price * $get['amount'];
            $cart               = new Cart();
            $cart->amount       = $get['amount'];
            $cart->product_id   = $get['product_id'];
            $cart->product_name = $product->band_name . " " . $product->cate_name . " " . $product->type_name;
            $cart->price        = $product->price;
            $cart->label_img    = $product->label_img;
            $cart->settled      = true;
            if (!$cart->save()) {
                Yii::$app->session->setFlash('failed', '操作失败，请重新操作。');
            } else {
                $result['cart_ids']['0'] = $cart->id;
            }
        } else {
            return $this->redirect(['index']);
        }
        //if address exists, use it and make it convenient for customers.
        if (!$model = Address::findOne(['user_id' => Yii::$app->user->identity->id])) {
            $model            = new Address();
            $model->cellphone = Yii::$app->user->identity->cellphone;
        } else {
            $model->province = 'a'; //make sure that it could trigger onchange event.
        }
        $result['province']['a'] = '————省————';
        foreach (Category::getCates(139) as $key => $value) {
            $result['province'][$key] = $value;
        }
        if (yii::$app->request->isPost) {
            $post                             = yii::$app->request->post();
            $post['Address']['province_name'] =
            Category::find()->where('id=:id', [':id' => $post['Address']['province']])->asArray()->one()['cat_name'];
            $post['Address']['city_name'] =
            Category::find()->where('id=:id', [':id' => $post['Address']['city']])->asArray()->one()['cat_name'];
            $post['Address']['district_name'] =
            Category::find()->where('id=:id', [':id' => $post['Address']['district']])->asArray()->one()['cat_name'];
            if ($model->load($post) && $model->save()) {
                $order             = new UserOrder();
                $order->cart_ids   = json_encode($result['cart_ids']);
                $order->total      = $result['total'];
                $order->cellphone  = $model->cellphone;
                $order->address_id = $model->id;
                $order->address    = $model->province_name . $model->city_name . $model->district_name . $model->detailed;
                $order->name       = $model->user_name;
                $order->order_no   = date('YmdHis', time()) . rand(100000, 999999);
                if ($order->save()) {
                    return $this->redirect(['index']);
                }
            }
            Yii::$app->session->setFlash('warning', $model->_lastError);
        }
        return $this->render('confirm', ['data' => $result, 'model' => $model]);
    }
    public function actionCity()
    {
        if (Yii::$app->request->isAjax) {
            $id          = Yii::$app->request->post()['id'];
            $series['a'] = '——市——';
            foreach (Category::getCates($id) as $key => $value) {
                $series[$key] = $value;
            }
            foreach ($series as $key => $value) {
                echo "<option value='" . $key . "'>" . $value . "</option>";
            }
        }
    }
    public function actionDistrict()
    {
        if (Yii::$app->request->isAjax) {
            $id     = Yii::$app->request->post()['id'];
            $series = Category::getCates($id);
            if (count($series)) {
                foreach ($series as $key => $value) {
                    echo "<option value='" . $key . "'>" . $value . "</option>";
                }
            } else {
                echo "<option value=700000> </option>";
            }
        }
    }
    public function actionReturn_url()
    {
        $get = yii::$app->request->get();
        if ($model = CombineOrder::findOne(['out_trade_no' => $get['out_trade_no']])) {
            $order_ids = json_decode($model->order_ids, true);
            foreach ($order_ids as $key => $value) {
                $user_order       = UserOrder::findOne($value);
                $user_order->paid = 1;
                $user_order->save();
                $cart_ids = json_decode($user_order->cart_ids, true);
                foreach ($cart_ids as $k => $v) {
                    $cart    = Cart::findOne($v);
                    $product = Product::findOne($cart->product_id);
                    $product->sale += $cart->amount;
                    $product->save();
                }
            }
        } else if ($user_order = UserOrder::findOne(['order_no' => $get['out_trade_no']])) {
            $user_order->paid = 1;
            $user_order->save();
            $cart_ids = json_decode($user_order->cart_ids, true);
            foreach ($cart_ids as $k => $v) {
                $cart    = Cart::findOne($v);
                $product = Product::findOne($cart->product_id);
                $product->sale += $cart->amount;
                $product->save();
            }
        }
        return $this->redirect(['index']);
    }
    public function actionNotify_url()
    {
        return $this->render('notify_url');
    }

}
