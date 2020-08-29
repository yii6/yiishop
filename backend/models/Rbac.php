<?php

namespace backend\models;
use Yii;
class Rbac extends \yii\db\ActiveRecord
{
    public static function getOptions($data,$parent){
        $return =[];
        foreach ($data as $obj) {
            if(!empty($parent)&&$obj->name!= $parent->name){
                yii::$app->authManager->canAddChild($parent,$obj);
            }
            $return[$obj->name]=$obj->description;
        }
        return $return;
    }
    public static function addChild($children,$name){
        $auth=yii::$app->authManager;
        $item=$auth->getRole($name);
        if(empty($item)){
            return false;
        }
        $trans=yii::$app->db->beginTransaction();
        try{
            $auth->removeChildren($item);
            foreach ($children as $key) {
                $obj=empty($auth->getRole($key))?$auth->getPermission($key):$auth->getRole($key);
                $auth->addChild($item,$obj);
            }
            $trans->commit();
        }catch(\Exception $e){
            $trans->rollback();
            return false;
        }
        return true;
    }
    public static function getChildrenByName($name){
        if(empty($name)){
            return false;
        }
        $return=[];
        $auth=Yii::$app->authManager;
        $children=$auth->getChildren($name);
        if(empty($children)){
            return [];
        }
        foreach ($children as $key) {
            if($key->type==1){
                $return['roles'][]=$key->name;
            }else{
                $return['permissions'][]=$key->name;
            }
        }
        return $return;
    }
    public static function _getItemByUser($id,$type){
        $func='getPermissionsByUser';
        if($type==1){
            $func='getRolesByUser';
        }
        $data=[];
        $auth=yii::$app->authManager;
        $items=$auth->$func($id);
        foreach ($items as $item) {
            $data[]=$item->name;
        }
        return $data;
    }
    public static function getChildrenByUser($id){
        $return =[];
        $return['roles']=self::_getItemByUser($id,1);
        $return['permissions']=self::_getItemByUser($id,2);
        return $return;
    }
    public static function grant($id,$children){
        $trans=yii::$app->db->beginTransaction();
        try{
            $auth=yii::$app->authManager;
            //清空所有授权
            $auth->revokeAll($id);
            foreach ($children as $key) {
                $obj=empty($auth->getRole($key))?$auth->getPermission($key):$auth->getRole($key);
                $auth->assign($obj,$id);
            }
            $trans->commit();
        }catch(\Exception $e){
            $trans->rollback();
            return false;
        }
        return true;
    }
}
