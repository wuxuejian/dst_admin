<?php
/**
 * TOP API: cainiao.apanti.lease.order.query request
 * 
 * @author auto create
 * @since 1.0, 2017.04.24
 */
class CainiaoApantiLeaseOrderQueryRequest
{
	/** 
	 * 查询参数
	 **/
	private $query;
	
	private $apiParas = array();
	
	public function setQuery($query)
	{
		$this->query = $query;
		$this->apiParas["query"] = $query;
	}

	public function getQuery()
	{
		return $this->query;
	}

	public function getApiMethodName()
	{
		return "cainiao.apanti.lease.order.query";
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
