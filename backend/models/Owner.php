<?php
/*
 * 机动车所有人 模型	
 */
namespace backend\models;
class Owner extends \common\models\Owner{
    public function rules(){
        $rules = [
            [['name','code','addr','note'],'filter','filter'=>'htmlspecialchars'],
            ['name','unique','message'=>'所有人名称重复！'],
        ];
        return array_merge($rules,parent::rules());
    }
	
}