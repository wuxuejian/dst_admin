<?php
/**
 * TOP API: cainiao.apanti.lease.order.vehicle.bind request
 * 
 * @author auto create
 * @since 1.0, 2017.04.24
 */
class CainiaoApantiLeaseOrderVehicleBindRequest
{
	/** 
	 * 租赁订单和车的绑定
	 **/
	private $vehicleOrders;
	
	private $apiParas = array();
	
	public function setVehicleOrders($vehicleOrders)
	{
		$this->vehicleOrders = $vehicleOrders;
		$this->apiParas["vehicle_orders"] = $vehicleOrders;
	}

	public function getVehicleOrders()
	{
		return $this->vehicleOrders;
	}

	public function getApiMethodName()
	{
		return "cainiao.apanti.lease.order.vehicle.bind";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
