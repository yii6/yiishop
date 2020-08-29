<?php

namespace backend\controllers;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii;
use backend\models\Rbac;
use frontend\controllers\BaseController;

class RbacController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['createrole','roles','assignitem','createrule'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionCreaterole()
    {
        if(yii::$app->request->isPost){
        	$auth=yii::$app->authManager;
        	$role=$auth->createRole(null);
        	$post=yii::$app->request->post();
        	if(empty($post['name'])||empty($post['description'])){
        		throw new \Exception("参数错误");
        	}
        	$role->name=$post['name'];
        	$role->description=$post['description'];
        	$role->ruleName=empty($post['rule_name'])?null:$post['rule_name'];
        	$role->data=empty($post['data'])?null:$post['data'];
        	if($auth->add($role)){
        		yii::$app->session->setFlash('info','添加成功');
        	}
        }
        return $this->render('createrole');
    }
    public function actionRoles(){
    	$auth=yii::$app->authManager;
    	$data=new ActiveDataProvider([
    		'query'=>(new Query)->from($auth->itemTable)->where('type=1')->orderBy('created_at desc'),
    		'pagination'=>['pageSize'=>5],
    	]);
    	return $this->render('_roles',['dataProvider'=>$data]);
    }
    public function actionAssignitem($name){
        $name=htmlspecialchars($name);
        $auth=yii::$app->authManager;
        $parent=$auth->getRole($name);
        if(yii::$app->request->isPost){
            $post=yii::$app->request->post();
            if(Rbac::addChild($post['children'],$name)){
                yii::$app->session->setFlash('info','分配成功');
            }
        }
        $children=Rbac::getChildrenByName($name);
        $roles=Rbac::getOptions($auth->getRoles(),$parent);
        $permissions=Rbac::getOptions($auth->getPermissions(),$parent);
        return $this->render('_assignitem',['parent'=>$name,'roles'=>$roles,'permissions'=>$permissions,'children'=>$children]);
    }
    public function actionCreaterule(){
        if(yii::$app->request->isPost){
            $post=yii::$app->request->post();
            if(empty($post['class_name'])){
                throw new \Exception("参数错误");
            }
            $className="backend\\models\\".$post['class_name'];
            if(!class_exists($className)){
                throw new \Exception("规则类不存在.");
            }
            $rule=new $className;
            if(yii::$app->authManager->add($rule)){
                yii::$app->session->setFlash('info','添加成功');
            }
        }
        return $this->render('_createrule');
    }
}
