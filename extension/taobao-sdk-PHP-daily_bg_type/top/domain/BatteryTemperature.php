<?php

/**
 * 电池温度
 * @author auto create
 */
class BatteryTemperature
{
	
	/** 
	 * 最高温度
	 **/
	public $highest_value;
	
	/** 
	 * 最高温度所在电池序列号最高温度所在电池序列号
	 **/
	public $index_of_highest_value;
	
	/** 
	 * 最高温度所在电池内测温点序列号
	 **/
	public $index_of_highest_value_for_measuring_point;
	
	/** 
	 * 最低温度所在电池序列号
	 **/
	public $index_of_minimum_value;
	
	/** 
	 * 最低温度所在电池内测温点序列号
	 **/
	public $index_of_minimum_value_for_measuring_point;
	
	/** 
	 * 最低温度
	 **/
	public $minimum_value;	
}
?>