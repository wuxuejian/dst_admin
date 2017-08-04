<?php
namespace backend\models;
use yii\db\ActiveRecord;
class RbacActionBtn extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%rbac_action_btn}}';
    }
    
    public function rules()
    {
        return [
            [['text','icon','on_click','note'],'filter','filter'=>'htmlspecialchars'],
            [['action_id','list_order'],'filter','filter'=>'intval'],
            ['action_id','checkActionId'],
            ['text','required','message'=>'文本内容不能为空！'],
            ['on_click','required','message'=>'点击执行JS不能为空！'],
            ['target_mca_code','match','pattern'=>'/^([a-z]|-)+\/([a-z]|-)+\/([a-z]|-)+$/','message'=>'目标mca格式错误！']
        ];
    }
    
    public function scenarios()
    {
        return [
            'default'=>['*'],
            'add'=>['text','icon','on_click','note','action_id','list_order','target_mca_code'],
            'edit'=>['text','icon','on_click','note','action_id','list_order','target_mca_code']
        ];
    }
    
    public function checkActionId()
    {
        if(empty($this->action_id)){
            $this->addError('action_id','action_id is error');
            return false;
        }
        if(RbacMca::find()->select(['id'])->where(['id'=>$this->action_id,'type'=>2])->one()){
            return true;
        }
        $this->addError('action_id','action_id is error');
    }
}