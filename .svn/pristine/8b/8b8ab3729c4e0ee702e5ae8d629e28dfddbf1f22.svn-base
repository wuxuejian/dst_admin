<?php
namespace backend\models;
use yii\db\ActiveRecord;
class RbacRole extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%rbac_role}}';
    }
    
    public function rules()
    {
        return [
            [['name','note'],'filter','filter'=>'htmlspecialchars'],
            ['name','required','message'=>'角色名称不能为空！'],
            ['name','unique','message'=>'角色名称已经存在！']
        ];
    }

}