<?php
/**
 * TOP API: cainiao.lvs.vms.vehiclereserve.upload request
 * 
 * @author auto create
 * @since 1.0, 2017.04.24
 */
class CainiaoLvsVmsVehiclereserveUploadRequest
{
	/** 
	 * 库存入参
	 **/
	private $reserverParameters;
	
	private $apiParas = array();
	
	public function setReserverParameters($reserverParameters)
	{
		$this->reserverParameters = $reserverParameters;
		$this->apiParas["reserver_parameters"] = $reserverParameters;
	}

	public function getReserverParameters()
	{
		return $this->reserverParameters;
	}

	public function getApiMethodName()
	{
		return "cainiao.lvs.vms.vehiclereserve.upload";
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
