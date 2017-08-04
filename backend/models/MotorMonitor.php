<?php
/**
 * 电机控制器表 模型
 */
namespace backend\models;
class MotorMonitor extends \common\models\MotorMonitor
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
            [['motor_monitor_model','motor_monitor_maker','protection_level'],'trim'],
            [['motor_monitor_model','motor_monitor_maker','protection_level'],'filter','filter'=>'htmlspecialchars'],
            [['input_voltage_range_s', 'input_voltage_range_e', 'rated_input_voltage', 'rated_capacity', 'peak_capacity', 'rated_input_current', 'rated_output_current', 'peak_output_current', 'output_frequency_range_s', 'output_frequency_range_e', 'max_effciency', 'working_temp'],'default','value'=>0.00],
            [['peak_current_duration'],'default','value'=>0],
        ];
		return array_merge($rules,parent::rules());
    }


}