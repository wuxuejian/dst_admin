<?php
namespace backend\models;
use yii\db\ActiveRecord;
class RbacMca extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%rbac_mca}}';
    }
    
    public function rules()
    {
        return [
            //过滤开始//
            [['name','note'],'filter','filter'=>'htmlspecialchars'],
            [['is_menu','list_order'],'filter','filter'=>'intval'],
            //过滤结束//
            ['name','required','message'=>'中文名称不能为空！'],
            ['is_menu','in','range'=>[0,1]],
            ['note','string','length'=>[0,255],'message'=>'备注长度超出！'],
            //添加修改模块时验证
            ['module_code','checkModuleCode','on'=>'controller'],
            //添加修改控制器验证
            ['controller_code','checkControllerCode','on'=>'controller'],
            //添加修改方法验证
            ['action_code','checkActionCode','on'=>'action']
        ];
    }
    
    public function scenarios(){
        return [
            'default'=>['*'],
            'module'=>['name','module_code','is_menu','list_order','note'],
            'controller'=>['name','controller_code','is_menu','list_order','note'],
            'action'=>['name','action_code','is_menu','list_order','note'],
        ];
    }

    public function checkModuleCode()
    {
        if(empty($this->module_code)){
            $this->addError('module_code','控制器代码不能为空！');
            return false;
        }
        if(!preg_match('/^[a-z]([a-z]|-)+$/', $this->module_code)){
            $this->addError('module_code','模块代码只能是"字母"或"-"，且只能以字母开头！');
            return false;
        }
        if($this->getOldAttribute('module_code') == $this->getAttribute('module_code')){
            return true;
        }
        if(self::find()->select(['id'])->where(['module_code'=>$this->module_code])->one()){
            $this->addError('module_code','模块代码已经存在！');
            return false;
        }
        return true;
    }
    
    public function checkControllerCode()
    {
        if(empty($this->controller_code)){
            $this->addError('controller_code','控制器代码不能为空！');
            return false;
        }
        if(!preg_match('/^[a-z]([a-z]|-)+$/', $this->controller_code)){
            $this->addError('controller_code','控制器代码只能是"字母"或"-"，且只能以字母开头！');
            return false;
        }
        if($this->getOldAttribute('controller_code') == $this->getAttribute('controller_code')){
            return true;
        }
        if(self::find()->select(['id'])->where(['controller_code'=>$this->controller_code,'module_code'=>$this->module_code])->one()){
            $this->addError('controller_code','控制器代码在模块下已经存在！');
            return false;
        }
        return true;
    }
    
    public function checkActionCode()
    {
        if(empty($this->action_code)){
            $this->addError('action_code','方法代码不能为空！');
            return false;
        }
        if(!preg_match('/^[a-z]([a-z]|-|[0-9])+$/', $this->action_code)){
            $this->addError('controller_code','方法代码只能是"字母"或"-"，且只能以字母开头！');
            return false;
        }
        if($this->getOldAttribute('action_code') == $this->getAttribute('action_code')){
            return true;
        }
        if(self::find()->select(['id'])->where(['controller_code'=>$this->controller_code,'module_code'=>$this->module_code,'action_code'=>$this->action_code])->one()){
            $this->addError('action_code','方法代码在本模块下本控制器已经存在！');
            return false;
        }
        return true;
    }
}