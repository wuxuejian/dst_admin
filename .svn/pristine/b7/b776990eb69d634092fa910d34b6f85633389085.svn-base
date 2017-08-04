<?php
/**
 * TOP API: cainiao.lvs.vms.vcondition.upload request
 * 
 * @author auto create
 * @since 1.0, 2017.04.17
 */
class CainiaoLvsVmsVconditionUploadRequest
{
	/** 
	 * 车辆实时工况数据
	 **/
	private $vehicleCondition;
	
	private $apiParas = array();
	
	public function setVehicleCondition($vehicleCondition)
	{
		$this->vehicleCondition = $vehicleCondition;
		$this->apiParas["vehicle_condition"] = $vehicleCondition;
	}

	public function getVehicleCondition()
	{
		return $this->vehicleCondition;
	}

	public function getApiMethodName()
	{
		return "cainiao.lvs.vms.vcondition.upload";
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
