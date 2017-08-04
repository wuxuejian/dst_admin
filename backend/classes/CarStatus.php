<?php
/**
 * 车辆状态变更类
 */
namespace backend\classes;
use yii;
use backend\models\Car;
class CarStatus {
	
	private $_status = [
		'STOCK'		=> '库存',
		'LETING'	=> '租赁中',
		'INTRIAL'	=> '试用中',
		'DSTCAR'	=> '部门自用',
		'BACKUP'	=> '备用',
		'REPLACE'	=> '替换中',
		'NO_EXIST'	=> '不存在',
		'REPAIRING'	=> '维修中',
	]; 
	
	/**
	 * 维修登记变更车辆状态  维修中
	 */
	public function fault($car_id)
	{
		$db = \Yii::$app->db;
		$result = $db->createCommand()->update('cs_car',
				['car_status2'=> 'REPAIRING'],
				'id=:id AND is_del =0',[':id'=>$car_id]
		)->execute();
	}
	
	
	/**
	 * 故障修复 还原车辆状态
	 */
	public function restore($car_id)
	{
		/* $status = $this->_reduction_status($car_id);

		if($status == 'NO_EXIST')
		{
			return ;
		} */
		$db = \Yii::$app->db;
		$result = $db->createCommand()->update('cs_car',
				['car_status2'=> ''],
				'id=:id AND is_del =0',[':id'=>$car_id]
		)->execute();
		
	}
	
	
	/**
	 * 车辆故障时的车辆状态
	 * @param unknown_type $car_id	车辆ID
	 */
	private function _reduction_status($car_id)
	{
		
		//查询出车辆ID
/* 		$car_info = (new \yii\db\Query())->from('cs_car')->where('id=:id AND is_del=0',[':id'=>$car_id])->one();
		if(empty($car_info))
		{
			return 'NO_EXIST';
		} */
		//1.当前日期是否在出租中?
		$let_record = (new \yii\db\Query())->from('cs_car_let_record')
			->where('car_id=:car_id AND back_time=0 AND is_del=0',[':car_id'=>$car_id])->orderBy('let_time DESC')->one();
		if(!empty($let_record))
		{
			return 'LETING';
		}
		
		//2.是否在试用中?
		$trial_record = (new \yii\db\Query())->from('cs_car_trial_protocol_details')
			->where('ctpd_car_id=:ctpd_car_id AND ctpd_back_date is null',[':ctpd_car_id'=>$car_id])->orderBy('ctpd_deliver_date DESC')->one();
		
		if(!empty($trial_record))
		{
			return 'INTRIAL';
		}
		//3.是否是部门自用车、备用、替换?
	   $stock_record = (new \yii\db\Query())->from('cs_car_stock')
			->where('car_id=:car_id AND is_del=0',[':car_id'=>$car_id])->one();
	   
	   if(!empty($stock_record))
	   {
	   		//车辆已替换
	   		if($stock_record['car_status'] == 1)
	   		{
	   			return 'REPLACE';
	   		}else{
	   			
	   			if($stock_record['car_type'] == 1)
	   			{
	   				return 'DSTCAR';
	   			}else{
	   				return 'BACKUP';
	   			}
	   		}
	   }
	   
	   return 'STOCK';
	   
	}
	
	/**
	 *  提车申请 ，合同类型为租赁、虚拟，车辆状态变更为租赁中  合同类型 1 租赁合同 2 试用协议 3 虚拟合同
	 *  @param array $car_no	                       车牌号
	 *  @param int $extract_report_id   提车申请ID
	 */
	public function extract_car($extract_report_id)
	{
		$car_no = [];
		$stock_car = [];
		$prepare_car = (new \yii\db\Query())->select('car_no,is_delivery')->from('oa_prepare_car')->where('tc_receipts=:tc_receipts AND is_jiaoche=1',[':tc_receipts'=>$extract_report_id])->all();
		if($prepare_car){
			foreach ($prepare_car as $val){
				if($val['is_delivery'] == 1){
					$car_no[] = $val['car_no'];  //变更为租赁
				}else{
					$stock_car[] = $val['car_no']; //变更为库存
				}
				
			}
		}else{
			return true;
		}
		
		//未交付车辆
// 		if($stock_car){
// 			$car_str = '';
// 			foreach ($stock_car as $k=>$v)
// 			{
// 				if($k==0)
// 				{
// 					$car_str.="'".$v."'";
// 				}else{
// 					$car_str.=",'".$v."'";
// 				}
			
// 			}
// 			$cars = (new \yii\db\Query())->select('id,plate_number')->from('cs_car')->where("plate_number in ({$car_str}) AND is_del=0")->all();
// 			$db = \yii::$app->db;
// 			//变更车辆状态 为库存
// 			foreach ($cars as $car)
// 			{
// 				$db->createCommand()->update('cs_car',
// 						['car_status' =>'STOCK'],'id=:id',[':id'=>$car['id']]
// 				)->execute();
// 			}
// 		}
		
		//已交付车辆
		if($car_no){
			//查询出车辆ID
			$car_str = '';
			foreach ($car_no as $k=>$v)
			{
				if($k==0)
				{
					$car_str.="'".$v."'";
				}else{
					$car_str.=",'".$v."'";
				}
			
			}
			$cars = (new \yii\db\Query())->select('id,plate_number,car_status')->from('cs_car')->where("plate_number in ({$car_str}) AND is_del=0")->all();
			if(empty($cars)){
				return '-1';   //车辆不存在
			}
			//查询出客户类型、合同类型
			$extract_report = (new \yii\db\Query())->select('contract_type,contract_number,customer_type')
				->from('oa_extract_report')->where('id=:id',[':id'=>$extract_report_id])->one();
			if(empty($cars)){
				return '-2';  //此提车申请不存在
			}
			$contract_type = $extract_report['contract_type'];
			$contract_number = $extract_report['contract_number'];
			$customer_type = $extract_report['customer_type'];
			
			//试用,车辆状态变更为试用中
			if ($contract_type =='租赁' || $contract_type =='自运营'){//租赁,车辆状态变更为租赁
				//查询出合同ID，客户ID，所属运营ID
				$contract = (new \yii\db\Query())->select('id,cCustomer_id,pCustomer_id,operating_company_id')->from('cs_car_let_contract')->where('number=:number AND is_del=0',[':number'=>$contract_number])->one();
				$contract_id  = $contract['id'];
				$contract_oci = $contract['operating_company_id'];
				$cCustomer_id = $contract['cCustomer_id'];
				$pCustomer_id = $contract['pCustomer_id'];
			
				$db = \yii::$app->db;
				if($customer_type != 1 && $customer_type != 2)
				{
					return '-3';//客户类型错误
				}
				try {
					foreach ($cars as $car)
					{
						$prepare_car = (new \yii\db\Query())->select('money_fee,start_time')->from('oa_prepare_car')->where('car_no=:car_no AND tc_receipts=:tc_receipts',[':car_no'=>$car['plate_number'],':tc_receipts'=>$extract_report_id])->one();
						$row = (new \yii\db\Query())->from('cs_car_let_record')->where('contract_id=:contract_id AND car_id=:car_id',[':contract_id'=>$contract_id,':car_id'=>$car['id']])->one();
						if(empty($row)){
							$db->createCommand()->insert('cs_car_let_record',
									[
									'contract_id' =>$contract_id,
									'cCustomer_id'=>$customer_type ==1 ?$cCustomer_id:0,  //企业客户
									'pCustomer_id'=>$customer_type ==2 ?$pCustomer_id:0,   //个人客户
									'car_id'      =>$car['id'],
									'month_rent'  =>$prepare_car['money_fee']?$prepare_car['money_fee']:0,
									'let_time'    => strtotime($prepare_car['start_time']),
									'operating_company_id'=>$contract_oci,
									]
							)->execute();
						}else{
							$db->createCommand()->update('cs_car_let_record',
									[
									//'contract_id' =>$contract_id,
									'cCustomer_id'=>$customer_type ==1 ?$cCustomer_id:0,  //企业客户
									'pCustomer_id'=>$customer_type ==2 ?$pCustomer_id:0,   //个人客户
									//'car_id'      =>$car['id'],
									'month_rent'  =>$prepare_car['money_fee']?$prepare_car['money_fee']:0,
									'let_time'    => strtotime($prepare_car['start_time']),
									'back_time'    => 0,
									'operating_company_id'=>$contract_oci,
									],
									'contract_id=:contract_id AND car_id=:car_id',[':contract_id'=>$contract_id,':car_id'=>$car['id']]
							)->execute();
						}
			
					}
					//变更车辆状态 租赁
					$car_ids = array();
					foreach ($cars as $car)
					{
						if($car['car_status'] == 'PREPARE'){
							array_push($car_ids, $car['id']);
						}
					}
					if($car_ids){
						$statusRet = Car::changeCarStatusNew($car_ids, 'LETING', 'process/car/rent', '填写租金信息(完成提交)',['car_status'=>'PREPARE','is_del'=>0]);
						return $statusRet['status'];
					}
					return true;
				} catch (Exception $e) {
					return '-5';//关联失败
				}
			}else{
				return '-4';//合同类型错误
			}
		}
		
	}
}