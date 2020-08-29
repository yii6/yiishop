<?php

namespace backend\controllers;

use backend\models\UserorderSearch;
use common\models\UserOrder;
use frontend\controllers\BaseController;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserorderController implements the CRUD actions for UserOrder model.
 */
class UserorderController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'confirmsent'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $searchModel  = new UserorderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionConfirmsent($id)
    {
        $user_order = UserOrder::findOne($id);
        if ($user_order->paid == 1) {
            $user_order->paid = 2;
            $user_order->save();
        }
        return $this->redirect(['index']);
    }
    protected function findModel($id)
    {
        if (($model = UserOrder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
