<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%motor_monitor}}".
 *
 * @property string $id
 * @property string $motor_monitor_model
 * @property string $apply_motor_type
 * @property string $cooling_type
 * @property string $motor_monitor_maker
 * @property double $input_voltage_range_s
 * @property double $input_voltage_range_e
 * @property double $rated_input_voltage
 * @property double $rated_capacity
 * @property double $peak_capacity
 * @property double $rated_input_current
 * @property double $rated_output_current
 * @property double $peak_output_current
 * @property string $peak_current_duration
 * @property double $output_frequency_range_s
 * @property double $output_frequency_range_e
 * @property double $max_effciency
 * @property string $protection_level
 * @property double $working_temp
 * @property string $create_time
 * @property string $creator_id
 * @property integer $is_del
 */
class MotorMonitor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%motor_monitor}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['input_voltage_range_s', 'input_voltage_range_e', 'rated_input_voltage', 'rated_capacity', 'peak_capacity', 'rated_input_current', 'rated_output_current', 'peak_output_current', 'output_frequency_range_s', 'output_frequency_range_e', 'max_effciency', 'working_temp'], 'number'],
            [['peak_current_duration', 'creator_id', 'is_del'], 'integer'],
            [['create_time'], 'safe'],
            [['motor_monitor_model', 'apply_motor_type', 'cooling_type', 'motor_monitor_maker', 'protection_level'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键，电机控制器表',
            'motor_monitor_model' => '电机型号',
            'apply_motor_type' => '适用电机：异步电机、永磁同步电机',
            'cooling_type' => '冷却方式：水冷',
            'motor_monitor_maker' => '电机控制器生产厂家',
            'input_voltage_range_s' => '输入电压范围-开始（VDC）',
            'input_voltage_range_e' => '输入电压范围-结束（VDC）',
            'rated_input_voltage' => '额定输入电压',
            'rated_capacity' => '额定容量（kVA）',
            'peak_capacity' => '峰值容量（kVA）',
            'rated_input_current' => '额定输入电流（A）',
            'rated_output_current' => '额定输出电流（A）',
            'peak_output_current' => '峰值输出电流（A）',
            'peak_current_duration' => '峰值电流持续时间（min）',
            'output_frequency_range_s' => '输出频率范围-开始（Hz）',
            'output_frequency_range_e' => '输出频率范围-结束（Hz）',
            'max_effciency' => '控制器最大效率（%）',
            'protection_level' => '防护等级',
            'working_temp' => '工作环境温度（℃）',
            'create_time' => '创建时间',
            'creator_id' => '创建人员id',
            'is_del' => '是否已删除',
        ];
    }
}
