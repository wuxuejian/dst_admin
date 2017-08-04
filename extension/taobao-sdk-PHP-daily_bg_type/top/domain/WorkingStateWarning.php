<?php

/**
 * 车辆工作状态报警 数
 * @author auto create
 */
class WorkingStateWarning
{
	
	/** 
	 * 加速踏板1故障
	 **/
	public $acceleration_pedal1_fault;
	
	/** 
	 * 加速踏板2故障
	 **/
	public $acceleration_pedal2_fault;
	
	/** 
	 * 电池组充电温度高
	 **/
	public $battery_charging_temperature_high;
	
	/** 
	 * 电池充电温度低报警
	 **/
	public $battery_charging_temperature_low;
	
	/** 
	 * 电池组放电温度高
	 **/
	public $battery_discharge_temperature_high;
	
	/** 
	 * 电池组放电温度低
	 **/
	public $battery_discharge_temperature_low;
	
	/** 
	 * 电池组间组SOC值差异故障
	 **/
	public $battery_pack_s_o_c_difference_fault;
	
	/** 
	 * 电池组间组电压压差故障
	 **/
	public $battery_pack_voltage_difference_fault;
	
	/** 
	 * 电池组温度不均衡报警
	 **/
	public $battery_temperature_not_balanced;
	
	/** 
	 * 制动系统故障
	 **/
	public $braking_system_fault;
	
	/** 
	 * 充电机故障
	 **/
	public $charger_fault;
	
	/** 
	 * 碰撞信号状态
	 **/
	public $collision_signal;
	
	/** 
	 * 电流环1故障
	 **/
	public $current1_fault;
	
	/** 
	 * DCDC故障
	 **/
	public $d_c_d_c_fault;
	
	/** 
	 * DCDC状态报警
	 **/
	public $d_c_d_c_state;
	
	/** 
	 * 驱动电机温度
	 **/
	public $drive_motor_temperature;
	
	/** 
	 * EPS故障
	 **/
	public $e_p_s_fault;
	
	/** 
	 * EVCU故障
	 **/
	public $e_v_c_u_fault;
	
	/** 
	 * 储能系统故障指示
	 **/
	public $energy_storage_fault;
	
	/** 
	 * 高压绝缘漏电报警
	 **/
	public $high_voltage_insulation_leakage;
	
	/** 
	 * 高压互锁状态
	 **/
	public $high_voltage_interlock;
	
	/** 
	 * 绝缘检测故障
	 **/
	public $insulation_detection_fault;
	
	/** 
	 * 电机控制故障
	 **/
	public $motor_control_fault;
	
	/** 
	 * 电机控制器通信超时故障
	 **/
	public $motor_controller_communicationtimeout;
	
	/** 
	 * 电机控制器温度
	 **/
	public $motor_controller_temperature;
	
	/** 
	 * 电机驱动系统故障
	 **/
	public $motor_drive_system;
	
	/** 
	 * 预充故障
	 **/
	public $precharge_fault;
	
	/** 
	 * 慢充CC异常
	 **/
	public $slow_charge_c_c_fault;
	
	/** 
	 * 慢充CP异常
	 **/
	public $slow_charge_c_p_fault;
	
	/** 
	 * DCDC温度报警
	 **/
	public $warning_of_d_c_d_c_temperature;
	
	/** 
	 * 电池总电压报警
	 **/
	public $warning_of_total_battery_voltage;	
}
?>