<?php

/**
 * 车型基础数据入参
 * @author auto create
 */
class VehicleModelBasicData
{
	
	/** 
	 * 库存数量
	 **/
	public $amount;
	
	/** 
	 * 运营商id
	 **/
	public $branch_id;
	
	/** 
	 * 运营商名称
	 **/
	public $branch_name;
	
	/** 
	 * 车辆品牌名称
	 **/
	public $brand;
	
	/** 
	 * 车型品牌id
	 **/
	public $brand_id;
	
	/** 
	 * 所属城市
	 **/
	public $city;
	
	/** 
	 * 车辆所在区县
	 **/
	public $district;
	
	/** 
	 * 租赁公司编码
	 **/
	public $lessor_company_code;
	
	/** 
	 * 租赁公司名称
	 **/
	public $lesssor_company;
	
	/** 
	 * 车型名称
	 **/
	public $model;
	
	/** 
	 * 车型id
	 **/
	public $model_id;
	
	/** 
	 * 提车点列表
	 **/
	public $picking_up_locations;
	
	/** 
	 * 车辆所在省
	 **/
	public $province;
	
	/** 
	 * 车辆所在乡镇
	 **/
	public $town;
	
	/** 
	 * 租金押金
	 **/
	public $vehicle_rent_and_deposit;	
}
?>