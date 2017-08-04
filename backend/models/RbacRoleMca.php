<?php
namespace backend\models;
use backend\models\RbacRole;
use backend\models\RbacMca;
use yii\db\ActiveRecord;
class RbacRoleMca extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%rbac_role_mca}}';
    }

    public function rules()
    {
        return [
            ['role_id','checkRoleId'],
            ['mca_id','checkMcaId']
        ];
    }

    public function checkRoleId()
    {
        if($this->role_id){
            $role = RbacRole::find()->select(['id'])
                    ->where(['id'=>$this->role_id,'is_del'=>0])
                    ->one();
            if($role){
                return true;
            }
        }
        $this->addError('role_id','角色错误！');
        return false;
    }

    public function checkMcaId()
    {
        if($this->mca_id){
            $mca = RbacMca::find()->select(['id'])->where(['id'=>$this->mca_id])->one();
            if($mca){
                return true;
            }
        }
        $this->addError('mca_id','模块或控制器或方法错误！');
        return false;
    }
}