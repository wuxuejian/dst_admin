<?php

/**
 * 车辆实时工况数据
 * @author auto create
 */
class VehicleConditionData
{
	
	/** 
	 * 电池温度
	 **/
	public $battery_temperature;
	
	/** 
	 * 电池电压
	 **/
	public $battery_voltage;
	
	/** 
	 * 运营商名称
	 **/
	public $branch_name;
	
	/** 
	 * 能量工况信息
	 **/
	public $energy_situation;
	
	/** 
	 * 租赁公司code
	 **/
	public $lessor_code;
	
	/** 
	 * 租赁公司名称
	 **/
	public $lessor_name;
	
	/** 
	 * 里程信息
	 **/
	public $mileage_information;
	
	/** 
	 * 电机功率
	 **/
	public $motor_power;
	
	/** 
	 * 电机工况信息
	 **/
	public $motor_situation;
	
	/** 
	 * 车辆唯一标识号
	 **/
	public $vin;
	
	/** 
	 * 车辆工作状态
	 **/
	public $working_state;
	
	/** 
	 * 车辆工作状态报警 数
	 **/
	public $working_state_warning;	
}
?>