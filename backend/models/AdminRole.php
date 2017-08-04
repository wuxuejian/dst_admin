<?php
namespace backend\models;
use yii\db\ActiveRecord;
class AdminRole extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%admin_role}}';
    }

    public function rules()
    {
        return [
            ['admin_id','checkAdminId'],
            ['role_id','checkRoleId']
        ];
    }

    public function checkAdminId()
    {
        $admin = Admin::find()->select(['id'])
                ->where(['id'=>$this->admin_id,'super'=>0])
                ->one();
        if(!$admin){
            $this->addError('admin_id','用户信息错误！');
            return false;
        }
        return true;
    }

    public function checkRoleId()
    {
        $role = RbacRole::find()
                ->select(['id'])
                ->where(['id'=>$this->role_id])
                ->one();
        if(!$role){
            $this->addError('role_id','角色信息错误！');
            return false;
        }
        return true;
    }
}