<?php

namespace backend\controllers;

use backend\models\Rbac;
use backend\models\UserSearch;
use common\models\User;
use frontend\controllers\BaseController;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\models\ResetPassForm;

class UserController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'index', 'delete', 'update', 'view', 'assign', 'cpass', 'cavatar'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $searchModel  = new UserSearch();
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
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    public function actionAssign($id)
    {
        if (empty($id)) {
            throw new \Exception("参数错误");
        }
        $model = $this->findModel($id);
        if (empty($model)) {
            throw new \yii\web\NotFoundHttpException("没找到对应id的用户");
        }
        if (yii::$app->request->isPost) {
            $post     = yii::$app->request->post();
            $children = !empty($post['children']) ? $post['children'] : [];
            if (Rbac::grant($id, $children)) {
                yii::$app->session->setFlash('info', '授权成功');
            }
        }
        $auth        = yii::$app->authManager;
        $roles       = Rbac::getOptions($auth->getRoles(), null);
        $permissions = Rbac::getOptions($auth->getPermissions(), null);
        $children    = Rbac::getChildrenByUser($id);
        return $this->render('_assign', [
            'roles'       => $roles,
            'permissions' => $permissions,
            'admin'       => $model->username,
            'children'    => $children,
        ]);
    }
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionCpass()
    {
        $model = new ResetPassForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', '新密码已经保存.');
            return $this->goHome();
        }
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    public function actionCavatar()
    {
        $model = new ResetPassForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', '新密码已经保存.');
            return $this->goHome();
        }
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
