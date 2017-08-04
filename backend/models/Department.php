<?php
namespace backend\models;
use yii\db\ActiveRecord;
class Department extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%department}}';
    }

    public function rules()
    {
        return [
            [['name','note'],'filter','filter'=>'htmlspecialchars'],
            ['name','required','message'=>'部门名称不能为空！'],
            ['name','unique','message'=>'部门名称已经存在！']
        ];
    }

}