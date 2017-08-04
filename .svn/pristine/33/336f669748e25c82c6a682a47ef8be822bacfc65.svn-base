<?php

namespace frontend\modules\company\models;

use Yii;

/**
 * This is the model class for table "bus_user_priv".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $module
 * @property string $menu_ids
 * @property string $buttons
 * @property integer $deleted
 */
class BusUserPriv extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bus_user_priv';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'deleted','module_id'], 'integer'],
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
            'user_id' => 'User ID',
            'module' => 'Module',
            'menu_ids' => 'Menu Ids',
            'buttons' => 'Buttons',
            'module_id' => 'Module Id',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * @name 根据id 获取权限
     * @param $userId
     * @param string $module
     * @return array|null|\yii\db\ActiveRecord[]
     */
    public function getUserPrivModels($userId, $moduleId=''){
        if (!$userId){
            return null;
        }else if (is_array($userId)){
            $roleId = implode(',', $userId);
        }

        $query = $this->find()
            ->where("user_id IN ($userId)");
        if ($moduleId){
            $query->andWhere('module_id=:module_id', [':module_id'=>$moduleId]);
        }
        $privs = $query->all();

        return $privs;
    }
  /**
   * @name 根据角色id获取权限
   */
    public function getUserPriv($roleId, $module=''){
        $privs = [];
        $models = $this->getUserPrivModels($roleId, $module);
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
     * @name 保存权限
     * @param $data
     * @return bool
     */
    public function savePriv($data){

        $userId  = $data['userId'];
        $module  = $data['module'];
        $moduleId  = $data['moduleId'];
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
        $privsModels = $this->getUserPrivModels($userId, $moduleId);

        if ($privsModels){
            foreach($privsModels as $model){
                if ($moduleId == $model->module_id){
                    $priv = $model;
                }
            }
        }
        if (!$priv){
            $priv = new BusUserPriv();
        }
        if(empty($module))$module='';
        if(empty($menuIds))$menuIds='';
        $priv->setAttribute('user_id', $userId);
        $priv->setAttribute('module_id', $moduleId);
        $priv->setAttribute('module', $module);
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
