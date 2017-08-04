<?php
/**
 * TOP API: cainiao.lvs.vms.rentanddeposit.upload request
 * 
 * @author auto create
 * @since 1.0, 2017.04.24
 */
class CainiaoLvsVmsRentanddepositUploadRequest
{
	/** 
	 * 租金押金参数
	 **/
	private $rentDepositParameters;
	
	private $apiParas = array();
	
	public function setRentDepositParameters($rentDepositParameters)
	{
		$this->rentDepositParameters = $rentDepositParameters;
		$this->apiParas["rent_deposit_parameters"] = $rentDepositParameters;
	}

	public function getRentDepositParameters()
	{
		return $this->rentDepositParameters;
	}

	public function getApiMethodName()
	{
		return "cainiao.lvs.vms.rentanddeposit.upload";
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
