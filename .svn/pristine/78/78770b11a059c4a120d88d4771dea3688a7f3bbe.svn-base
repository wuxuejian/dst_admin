<?php
/**
 * 电机表 模型
 */
namespace backend\models;
class Motor extends \common\models\Motor
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
            [['motor_model','motor_maker'],'trim'],
            [['motor_model','motor_maker'],'filter','filter'=>'htmlspecialchars'],
            [['rated_power', 'rated_speed', 'rated_frequency', 'rated_current', 'rated_torque', 'rated_voltage', 'peak_power', 'peak_speed', 'peak_frequency', 'peak_current', 'peak_torque', 'polar_logarithm'], 'default', 'value'=>0.00],
        ];
		return array_merge($rules,parent::rules());
    }


}