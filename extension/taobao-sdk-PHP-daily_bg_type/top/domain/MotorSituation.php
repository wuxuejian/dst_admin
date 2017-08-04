<?php

/**
 * 电机工况信息
 * @author auto create
 */
class MotorSituation
{
	
	/** 
	 * 加速踏板行程
	 **/
	public $accelerator_pedal_travel;
	
	/** 
	 * 制动踏板行程
	 **/
	public $brake_pedal_stroke;
	
	/** 
	 * 接触器后端电压
	 **/
	public $contactor_back_voltage;
	
	/** 
	 * 接触器前端电压
	 **/
	public $contactor_front_voltage;
	
	/** 
	 * 驱动电机温度
	 **/
	public $drive_motor_temperature;
	
	/** 
	 * 驱动电机扭矩电压
	 **/
	public $drive_motor_torque_voltage;
	
	/** 
	 * 电机交流电流有效值
	 **/
	public $motor_a_c_current;
	
	/** 
	 * 电机控制器温度
	 **/
	public $motor_controller_temperature;
	
	/** 
	 * 续航里程
	 **/
	public $recharge_mileage;
	
	/** 
	 * 整车控制器LIFE
	 **/
	public $vehicle_controller_of_l_i_f_e;	
}
?>