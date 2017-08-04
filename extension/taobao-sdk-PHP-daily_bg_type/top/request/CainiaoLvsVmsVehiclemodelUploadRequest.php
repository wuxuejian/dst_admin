<?php
/**
 * TOP API: cainiao.lvs.vms.vehiclemodel.upload request
 * 
 * @author auto create
 * @since 1.0, 2017.05.09
 */
class CainiaoLvsVmsVehiclemodelUploadRequest
{
	/** 
	 * 车型信息入参
	 **/
	private $vehicleModels;
	
	private $apiParas = array();
	
	public function setVehicleModels($vehicleModels)
	{
		$this->vehicleModels = $vehicleModels;
		$this->apiParas["vehicle_models"] = $vehicleModels;
	}

	public function getVehicleModels()
	{
		return $this->vehicleModels;
	}

	public function getApiMethodName()
	{
		return "cainiao.lvs.vms.vehiclemodel.upload";
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
