<?php

namespace backend\controllers;

use Yii;
use common\models\Category;
use backend\models\CategorySearch;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\AccessControl;
use frontend\controllers\BaseController;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['rename','tree','create','index','delete'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $page         = (int) yii::$app->request->get('page') ?: 1;
        $perpage      = (int) yii::$app->request->get('per-page') ?: 6;
        $model        = new Category();
        $data         = $model->getPrimaryCate($perpage);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'pager'   => $data['pages'],
            'page'    => $page,
            'perpage' => $perpage,
        ]);
    }
    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();
        $list         = $model->getOptions();
        if (yii::$app->request->isPost) {
            $post = yii::$app->request->post();
            if ($model->addcate($post)) {
                yii::$app->session->setFlash('info', "添加分类成功");
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', ['list' => $list,'model' => $model]);
    }
//修改分类名称
    public function actionRename()
    {
        yii::$app->response->format = Response::FORMAT_JSON;
        if (!yii::$app->request->isAjax) {
            throw new \yii\web\MethodNotAllowedHttpException("拒绝访问");
        }
        $post         = yii::$app->request->post();
        $newtext      = $post['new'];
        $old          = $post['old'];
        $id           = $post['id'];
        if (empty($newtext) || empty($id)) {
            return ['code' => -1, 'message' => '参数错误', 'data' => []];
        }
        if ($old == $newtext) {
            return ['code' => 0, 'message' => 'ok', 'data' => []];
        }
        $model           = Category::findOne($id);
        $model->cat_name = $newtext;
        if ($model->save()) {
            yii::$app->session->setFlash('info', '修改分类名称成功');
            return ['code' => 0, 'message' => 'ok', 'data' => []];
        }
        return ['code' => 1, 'message' => '修改失败', 'data' => []];
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        yii::$app->response->format = Response::FORMAT_JSON;
        if (!yii::$app->request->isAjax) {
            throw new \yii\web\MethodNotAllowedHttpException("拒绝访问");
        }
        $id           = yii::$app->request->get('id');
        if (empty($id)) {
            return ['code' => -1, 'message' => '参数错误', 'data' => []];
        } else {
            $model = new Category();
            $n     = $model->deleteAll('id=:id or pid=:id', [':id' => $id]);
            if ($n > 0) {
                yii::$app->session->setFlash('info', '删除' . $n . '个分类成功');
                return ['code' => 0, 'message' => 'ok', 'data' => []];
            } else {
                yii::$app->session->setFlash('info', '删除分类失败');
                return ['code' => 1, 'message' => '删除失败', 'data' => []];
            }
        }
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionTree()
    {
        yii::$app->response->format = Response::FORMAT_JSON;
        $model                      = new Category();
        $data                       = $model->getPrimaryCate();
        if (!empty($data)) {
            return $data['data'];
        }
        return [];
    }
}
