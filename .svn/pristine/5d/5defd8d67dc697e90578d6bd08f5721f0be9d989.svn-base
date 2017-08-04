<?php
namespace backend\models;
use yii\db\ActiveRecord;
class ConfigItem extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%config_item}}';
    }
    
    
    public function rules()
    {
        return [
            ['belongs_id','checkBlongsId'],
            ['value','checkValue'],
            ['text','filter','filter'=>'htmlspecialchars'],
            ['text','required'],
            ['text','string','length'=>[1,100],'tooLong'=>'文本内容长度超出！'],
            ['note','filter','filter'=>'htmlspecialchars'],
            ['note','string','length'=>[0,255],'tooLong'=>'备注长度超出！'],
            ['list_order','filter','filter'=>'intval'],
        ];
    }

    public function scenarios()
    {
        return [
            'default'=>['*'],
            'add'=>['belongs_id','value','text','note','list_order'],
            'edit'=>['value','text','note','list_order'],
        ];
    }
    
    /**
     * 检测配置项的值是否合法
     */
    public function checkValue()
    {
        if(empty($this->value)){
            $this->addError('value','配置项对应值不能为空！');
            return false;
        }
        //$this->value = strtoupper($this->value);
        /*if(!preg_match('/^\w+$/',$this->value)){
            $this->addError('value','配置项对应值只能是数字、字母或下划线！');
            return false;
        }*/
        if(strlen($this->value) > 100){
            $this->addError('value','配置项对应值长度超出！');
            return false;
        }
        switch ($this->scenario) {
            case 'add':
                //添加场景
                $hasThisValue = self::find()->select('id')
                                ->where(['value'=>$this->value,'belongs_id'=>$this->belongs_id])
                                ->one();
                if($hasThisValue){
                    $this->addError('value','配置项对应值在本配置类别下已经存在！');
                    return false;
                }
                break;
            case 'edit':
                //修改场景
                if($this->getOldAttribute('value') != $this->value){
                    $hasThisValue = self::find()->select('id')
                                ->where(['value'=>$this->value,'belongs_id'=>$this->getOldAttribute('belongs_id')])
                                ->one();
                    if($hasThisValue){
                        $this->addError('value','配置项对应值在本配置类别下已经存在！');
                        return false;
                    }
                }
                break;
        }
        return true;
    }

    public function checkBlongsId()
    {
        $this->belongs_id = intval($this->belongs_id);
        if(empty($this->belongs_id)){
            $this->addError('belongs_id','所属分类非法！');
            return false;
        }
        if(!ConfigCategory::find()->select(['id'])->where(['id'=>$this->belongs_id])->one()){
            $this->addError('belongs_id','所属分类非法！');
            return false;   
        }
        return true;
    }
}