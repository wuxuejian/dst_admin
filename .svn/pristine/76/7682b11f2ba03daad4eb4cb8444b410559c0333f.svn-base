<?php
/**
 * 电池表 模型
 */
namespace backend\models;
class Battery extends \common\models\Battery
{
    /**
     * 关联【人员】表
     */
    public function getAdmin(){
        return $this->hasOne(Admin::className(),[
            'id'=>'creator_id'
        ]);
    }

    
    public function rules()
    {
        $rules = [
            [['battery_model','battery_maker'],'trim'],
            [['battery_model','battery_maker'],'filter','filter'=>'htmlspecialchars'],
            [['system_voltage', 'system_capacity', 'system_power', 'single_voltage', 'single_capacity', 'module_capacity'],'default','value'=>0.00],
            [['system_nums', 'module_nums'],'default','value'=>0],
        ];
		return array_merge($rules,parent::rules());
    }


}