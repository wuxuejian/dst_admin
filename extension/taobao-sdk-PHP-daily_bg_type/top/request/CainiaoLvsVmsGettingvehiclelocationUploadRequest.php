<?php
/**
 * TOP API: cainiao.lvs.vms.gettingvehiclelocation.upload request
 * 
 * @author auto create
 * @since 1.0, 2017.04.24
 */
class CainiaoLvsVmsGettingvehiclelocationUploadRequest
{
	/** 
	 * 提车点参数
	 **/
	private $gettingLocationParameters;
	
	private $apiParas = array();
	
	public function setGettingLocationParameters($gettingLocationParameters)
	{
		$this->gettingLocationParameters = $gettingLocationParameters;
		$this->apiParas["getting_location_parameters"] = $gettingLocationParameters;
	}

	public function getGettingLocationParameters()
	{
		return $this->gettingLocationParameters;
	}

	public function getApiMethodName()
	{
		return "cainiao.lvs.vms.gettingvehiclelocation.upload";
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
