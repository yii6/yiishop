<?php

namespace console\controllers;
use yii;

class RbacController extends \yii\console\Controller
{
    public function actionInit()
    {
        $trans=yii::$app->db->beginTransaction();
        try{
            $dir=dirname(dirname(dirname(__FILE__))).'/backend/controllers';
            //glob是匹配符合表达式的文件或目录
            $controllers=glob($dir.'/*');
            $permissions=[];
            foreach ($controllers as $controller) {
                $content=file_get_contents($controller);
                preg_match('/class ([a-zA-Z]+)Controller/', $content,$match);
                //$match[0]=> class CategoryController,[1] => Category
                $ctrlName=$match[1];
                $permissions[]=strtolower($ctrlName.'/*');
                preg_match_all('/public function action([a-zA-Z_]+)/',$content, $matches);
                foreach ($matches[1] as $actionName) {
                    $permissions[]=strtolower($ctrlName.'/'.$actionName);
                }
            }
            $auth=yii::$app->authManager;
            foreach ($permissions as $permission) {
                if(!$auth->getPermission($permission)){
                    $obj=$auth->createPermission($permission);
                    $obj->description=$permission;
                    $auth->add($obj);
                }
            }
            $trans->commit();
            echo "导入成功\n";
        }catch(\Exception $e){
            $trans->rollback();
            echo "导入失败\n";
        }
    }
}
