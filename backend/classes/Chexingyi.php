<?php //if (!defined('BASEPATH')) exit('No direct script access allowed'); 
namespace backend\classes;
use yii;
/**
 * 车行易违章测试
 * @author try
 * 2017/3/20
 */
class Chexingyi {
	private $_userid = 'DST2017';
	private $_userpwd = '909FDA5C40434508941D28A931D0C1C3';
	
	//private $_ci;
	private $_car_city = array();
	private $_car_city_url = '../web/chexingyi_car_city.txt';
	//public function __construct()
	//{
		//$this->_ci = &get_instance();
		//$this->_ci->load->database();
	//}
	
	/**
	 * 城市 车辆查询 需要的条件
	 * 车架号、发动机号
	 */
	public function _cityCondition(){
		$filectime = date("Ymd",filectime($this->_car_city_url));
		//文件上次修改时间,每天第一次保存
		if($filectime < date('Ymd')){
			$url = "http://test.cx580.com:9000/InputsCondition.aspx?from={$this->_userid}";
			$json_data = file_get_contents($url);
			$arr = json_decode($json_data,true);
			foreach ($arr as $val){
				foreach ($val['Cities'] as $v){
					$this->_car_city[$v['CarNumberPrefix']] = array(
							'Name'           => $v['Name'],            //城市名称
							'CarNumberPrefix'=> $v['CarNumberPrefix'], //车牌前缀  京，沪，津，渝的代码为1位
							'CarCodeLen'     => $v['CarCodeLen'],      //查询所需车架号长度后N位   0 代表不需要   99 代表完整  具体数字代表 后多少位 （下同）
							'CarEngineLen'	 => $v['CarEngineLen'],    //查询所需发动机号长度后N位
							'CarOwnerLen'    => $v['CarOwnerLen']	   //查询所需车主驾驶证号码后N位，可暂时忽略此字段要求
					);
				}
			}
			$fp = fopen($this->_car_city_url, 'w');
			fwrite($fp, json_encode($this->_car_city));
			fclose($fp);
		}else{
			$fp = fopen($this->_car_city_url, 'r') or die("Unable to open file!");
			$this->_car_city = json_decode(fread($fp,filesize($this->_car_city_url)),true);
			fclose($fp);
		}
		
		
	}
	
	/**
	 * 违章查询
	 * @param unknown_type $car_no  车牌号
	 */
	public function query($car_no){
		set_time_limit(0);
		$this->_cityCondition();
		$params = $this->dealWith($car_no);
		if(is_numeric($params)){
			return $params;
		}
		$url = "http://chaxun.cx580.com:9008/queryindex.aspx?userid={$this->_userid}&userpwd={$this->_userpwd}&{$params}";
		$json_data = file_get_contents($url);
		$data = json_decode($json_data,true);
 		//echo '<pre>';var_dump($data);exit();
		return $data;		
	}
	
	/**
	 * 违章更新
	 * @param unknown_type $data	车行易返回数据
	 * @param unknown_type $car_no	车牌号
	 */
	public function update_car_wz($data,$car_no){
		$wz_num = 0;
		$dates = array();
		$codes = array();
		//违章记录状态0 末处理  1 己处理(绝大部分情况下，车行易只能返回未处理的违章)
		if($data['HasData'] ===true){
			foreach ($data['Records'] as $row){
				if($row['status'] == 0){ 		//有未处理违章，违章数+1
					$wz_num++;
				}
				//查询违章是否存在
				$sql = 'SELECT count(*) as cnt  FROM car_wz WHERE unix_timestamp(date)=? and plate_number=? and (code=? or code is null)';
				if($this->_ci->db->query($sql,array(strtotime($row['Time']),$car_no,$row['Code']))->row()->cnt == 0)
				{
					//1.获取违章客户
					$customer_company = $this->get_customer_by_wz($car_no,$row['Time']);
					$cCustomer_id = $customer_company->id?$customer_company->id:0;
					//2.插入
					$sql = 'INSERT INTO car_wz (fen,date,area,code,act,money,plate_number,handled,add_time,cCustomer_id) VALUES(?,?,?,?,?,?,?,?,?,?)';
					$this->_ci->db->query($sql,array(
							$row['Degree'],$row['Time'],$row['Location'],
							$row['Code'],$row['Reason'],$row['count'],
							$car_no,$row['status'],date("Y-m-d H:i:s"),
							$cCustomer_id
					));
				}else{
					/*更新已有的违章数据  违章记录状态0 末处理  1 己处理（对应数据库的已注销状态）
					 * handled 1处理，0未处理,2已注销
					*/
					if($row['status'] == 1){
						$this->_ci->db->query('update car_wz set handled=2 where unix_timestamp(date)=? and plate_number=? and code=?', array(strtotime($row['Time']), $car_no,$row['Code']));
					}
					if($row['status'] == 0){
						$this->_ci->db->query('update car_wz set handled=0 where unix_timestamp(date)=? and plate_number=? and code=?', array(strtotime($row['Time']), $car_no,$row['Code']));
					}
				}
				$dates[] = $row['Time'];
				$codes[] = $row['Code'];
			}
		}else{  //没有违章更新数据库所有违章 为已注销
			$this->_ci->db->query('UPDATE car_wz SET handled=2 WHERE plate_number=?',array($car_no));
		}
		//更新已注销的记录（数据库存在 ，接口无此条数据返回 表示已注销）
		if($dates){
			$sql = 'SELECT id FROM car_wz where';
			foreach ($dates as $key=>$date){
				if($key == 0){
					$sql.="( plate_number='{$car_no}' AND handled<>1 AND date ='{$date}' AND code ='{$codes[$key]}')";
				}else{
					$sql.=" OR  ( plate_number='{$car_no}' AND handled<>1 AND date ='{$date}' AND code ='{$codes[$key]}')";
				}
			}
			$not_handleds = $this->_ci->db->query($sql)->result();
			if($not_handleds){
				$not_id = '';
				foreach ($not_handleds as $key=>$not_handled){
					if($key == 0){
						$not_id = $not_handled->id;
					}else{
						$not_id .=','.$not_handled->id;
					}
				}
				$sql = "UPDATE car_wz SET handled=2 WHERE id NOT IN ({$not_id}) and plate_number='{$car_no}'";
				$this->_ci->db->query($sql);
			}
		}
		return $wz_num;
	}
	
	
	
	public function dealWith($car_no){
		$connection = yii::$app->db;
    	//查询退车流程中的合同内容
		$sql = "SELECT vehicle_dentification_number as vin,engine_number,car_type FROM cs_car WHERE plate_number='".$car_no."' AND is_del=0";
    	$row = $connection->createCommand($sql)->queryOne();    	
		if(empty($row)){
			return -1; //车辆不存在
		}
		$number_prefix = array('京','沪','津','渝');
		$car_number_prefix = mb_substr($car_no,0,1,'utf-8');
		
		/*  //车辆类型：默认为02小车（除广东地区外，其他地区只支持02小车）
		 *  “除广东地区外，其他地区只支持02小车” ??? 车行易文档有问题   by 2017/5/3 
		$cartype = '02';
		if($car_number_prefix !='粤'){
			$cartype ='02';
		}else{
			if($row->car_type=='HUOCHE' || $row->car_type=='ZXXSHC'){
	    		$cartype = '01';
	    	}
		} */
		
		/*
		 * 新能源小车  cartype=52  除货车、中厢型式货车之外 
		 * 新能源大车  cartype=51  货车 中型厢式货车
		 */
		if(mb_strlen($car_no) ==8){
			if($row['car_type'] =='HUOCHE' || $row['car_type'] =='ZXXSHC'){
				$cartype = '51';
			}else{
				$cartype = '52';
			}
		}else{
			$cartype = '02';
			if($row['car_type'] =='HUOCHE' || $row['car_type'] =='ZXXSHC'){
				$cartype = '01';
			}
		}
		if(!in_array($car_number_prefix,$number_prefix)){
			$car_number_prefix = mb_substr($car_no,0,2,'utf-8');
		}
		if(array_key_exists($car_number_prefix, $this->_car_city)){
			//车架号
			if($this->_car_city[$car_number_prefix]['CarCodeLen'] ==99){
				$carcode = $row['vin'];
			}elseif ($this->_car_city[$car_number_prefix]['CarCodeLen'] ==0){
				$carcode = 0;
			}else{
				$carcode = substr($row['vin'],-$this->_car_city[$car_number_prefix]['CarCodeLen']);
			}
			
			//发动机号
			if($this->_car_city[$car_number_prefix]['CarEngineLen'] ==99){
				$cardrivenumber = $row['engine_number'];
			}elseif ($this->_car_city[$car_number_prefix]['CarEngineLen'] ==0){
				$cardrivenumber =0;
			}else{
				$cardrivenumber = substr($row['engine_number'], -$this->_car_city[$car_number_prefix]['CarEngineLen']);
			}
			
			$params['cartype'] = $cartype;
			$params['carnumber'] = $car_no;
			if($carcode !==0 ){
				$params['carcode'] = $carcode;
			}
			if($cardrivenumber !==0){
				$params['cardrivenumber'] = $cardrivenumber;
			}
			//参数拼接
			$data = http_build_query($params);
			//echo $data;exit();
			return $data;
		}else{
			return -2;//不支持该城市车辆查询
		}
	}
	
	/**
	 * 根据违章获取客户信息  by copy /models/m_carwz.php
	 */
	public function get_customer_by_wz($plate_number,$wz_date){
		$wz_time = strtotime($wz_date);
		$sql = "select cCustomer_id from cs_car_let_record
		where car_id=(select id from cs_car where plate_number=? limit 1)
		and (({$wz_time} >= let_time and {$wz_time} <= back_time) or ({$wz_time} >= let_time and back_time=0)) limit 1";
	
		$car_let_record = $this->_ci->db->query($sql, $plate_number)->row();
		if($car_let_record){
			return $this->_ci->db->query('select id,company_name,keeper_mobile from cs_customer_company where id=?', $car_let_record->cCustomer_id)->row();
		}
		//试用客户
		$sql = "select ctpd_cCustomer_id from cs_car_trial_protocol_details
		where ctpd_car_id=(select id from cs_car where plate_number=? limit 1)
		and (('{$wz_date}' >= ctpd_deliver_date and '{$wz_date}' <= ctpd_back_date) or ('{$wz_date}' >= ctpd_deliver_date and ctpd_back_date is null)) limit 1";
		
		$car_let_record = $this->_ci->db->query($sql, $plate_number)->row();
		if($car_let_record){
		return $this->_ci->db->query('select id,company_name,keeper_mobile from cs_customer_company where id=?', $car_let_record->ctpd_cCustomer_id)->row();
		}
		return false;
	}
}