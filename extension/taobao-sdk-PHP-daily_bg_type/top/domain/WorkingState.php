<?php

/**
 * 车辆工作状态
 * @author auto create
 */
class WorkingState
{
	
	/** 
	 * 辅助蓄电池电压
	 **/
	public $assistant_battery_voltage;
	
	/** 
	 * 电池包就位数量
	 **/
	public $available_battery_pack;
	
	/** 
	 * 均衡状态
	 **/
	public $balanced_status;
	
	/** 
	 * 电池仓门状态
	 **/
	public $battery_door_status;
	
	/** 
	 * 制动踏板状态
	 **/
	public $brake_pedal;
	
	/** 
	 * 充电线连接状态
	 **/
	public $charging_cable;
	
	/** 
	 * 充电状态
	 **/
	public $charging_status;
	
	/** 
	 * 充放电状态
	 **/
	public $discharge_status;
	
	/** 
	 * 降功率状态
	 **/
	public $down_power_status;
	
	/** 
	 * 能量回馈开启状态
	 **/
	public $energy_feedback;
	
	/** 
	 * 档位信号
	 **/
	public $gear_signal;
	
	/** 
	 * 手刹开关状态
	 **/
	public $handbrake_status;
	
	/** 
	 * 钥匙状态
	 **/
	public $key_status;
	
	/** 
	 * 电池箱数量不足报警
	 **/
	public $number_of_battery_packs;
	
	/** 
	 * SOC
	 **/
	public $s_o_c;
	
	/** 
	 * SOC报警
	 **/
	public $s_o_c_warning;
	
	/** 
	 * 整车READY信号灯状态
	 **/
	public $signal_lights_status;
	
	/** 
	 * 降速度行驶状态
	 **/
	public $slowdown_status;
	
	/** 
	 * 电流大报警
	 **/
	public $warning_of_current_high;
	
	/** 
	 * 电池组单体电压均衡报警
	 **/
	public $warning_of_single_voltage_balance;
	
	/** 
	 * 电池组单体电压高报警
	 **/
	public $warning_of_single_voltage_high;
	
	/** 
	 * 单体电压低报警
	 **/
	public $warning_of_single_voltage_low;
	
	/** 
	 * 电池组温度高报警
	 **/
	public $warning_ofbattery_temperature_high;
	
	/** 
	 * 电池组温度低报警
	 **/
	public $warning_ofbattery_temperature_low;	
}
?>