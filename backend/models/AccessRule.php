<?php
namespace backend\models;

use common\models\Category;
use common\models\Posts;
use common\models\Product;

class AccessRule extends \yii\rbac\Rule
{
    public $name = "isAuthor";
    public function execute($user, $item, $params)
    {
        $action     = \yii::$app->controller->action->id;
        $controller = \yii::$app->controller->action->controller->id;
        if ($action == 'delete' || $action == 'update') {
            $id = \yii::$app->request->get('id');
            if ($controller == 'category') {
                $model = Category::findOne($id);
            } elseif ($controller == 'post') {
                $model = Posts::findOne($id);
            } elseif ($controller == 'product') {
                $model = Product::findOne($id);
            } else {
                return false;
            }
            return $model->created_id == $user;
        }
        return true;
    }
}
