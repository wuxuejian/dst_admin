<?php

namespace frontend\modules\company\models;

use Yii;

/**
 * This is the model class for table "bus_role_priv".
 *
 * @property integer $id
 * @property integer $site_id
 * @property integer $boss_id
 * @property string $module
 * @property string $menu_ids
 * @property string $buttons
 * @property integer $deleted
 */
class BusRolePriv extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bus_role_priv';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id','deleted'], 'integer'],
            [['menu_ids', 'buttons'], 'string'],
            [['module'], 'string', 'max' => 20]
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'module' => 'Module',
            'menu_ids' => 'Menu Ids',
            'buttons' => 'Buttons',
            'deleted' => 'Deleted',
        ];
    }

    /** 根据角色id取其权限
     * @roledId int,string,array 角色id的数字、逗号分隔的多个id或id数组
     * @module string 限定取某个模块下的权限取
     * @return array BusRolePriv
     */
    public function getRolePrivModels($roleId, $moduleId=''){
        if (!$roleId){
            return null;
        }else if (is_array($roleId)){
            $roleId = implode(',', $roleId);
        }

        $query = $this->find()
            ->where("role_id IN ($roleId)");
        if ($moduleId){
            $query->andWhere('module_id=:module_id', [':module_id'=>$moduleId]);
        }
        $privs = $query->all();

        return $privs;
    }
/**
 * @name 根据角色id获取权限
 */
    public function getRolePriv($roleId, $module=''){
        $privs = [];
        $models = $this->getRolePrivModels($roleId, $module);
        if (count($models) == 0){
            return [];
        }

        foreach($models as $model){
            $mark   = explode(',', $model->menu_ids);
            $module   = explode(',', $model->module);
            $marks = array_unique(array_merge($mark, $module));

            $buttons = array();
            $buttons = unserialize($model->buttons);

            if (!isset($privs['menus'])){
                $privs['menus']   = $marks;
                $privs['buttons'] = $buttons;
            }else{
                // 把多个角色的功能按钮合并起来
                $privs['menus'] = array_unique(array_merge($privs['menus'], $marks));
                if(!empty($buttons))
                {

                    foreach($buttons as $m => $btnArr){

                        if(@array_key_exists($m, $privs['buttons'])){
                            $privs['buttons'][$m] = array_unique(array_merge($privs['buttons'][$m], $btnArr));
                        }else{
                            $privs['buttons'][$m] = $btnArr;
                        }
                    }
                }
            }
        }

        return $privs;
    }

    /**
     * @name 保存设置的权限
     * @param $data 提交的权限信息
     * @return bool
     */
    public function savePriv($data){

        $roleId  = $data['roleId'];
        $moduleId  = $data['moduleId'];
        $module  = $data['module'];
        $menuIds = substr($data['menuIds'], 0, -1);

        //处理buttons
        $buttonArr = explode('|',$data['buttons']);
        foreach($buttonArr as $b){
            $operate = explode('@',$b);
            $op = explode(',',$operate[1]);
            if($operate[1]){
            $arrButtons[$operate[0]]=$op;
            }
        }
            $btnIds = serialize($arrButtons);

        $priv = null;
        $privsModels = $this->getRolePrivModels($roleId, $moduleId);
        if ($privsModels){
            foreach($privsModels as $model){
                if ($moduleId == $model->module_id){
                    $priv = $model;
                }
            }
        }
        if (!$priv){
            $priv = new BusRolePriv();
        }
        if(empty($module))$module='';
        if(empty($menuIds))$menuIds='';
        $priv->setAttribute('role_id', $roleId);
        $priv->setAttribute('module', $module);
        $priv->setAttribute('module_id', $moduleId);
        $priv->setAttribute('menu_ids', $menuIds);
        $priv->setAttribute('buttons', $btnIds);
        $priv->setAttribute('deleted', 0);

        if ($priv->save()){
           return true;
        }else{
           return false;
        }
    }



}
