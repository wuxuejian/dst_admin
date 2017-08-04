<?php
/**
 *
 * @author
 */
namespace backend\modules\purchase\controllers;
use backend\controllers\BaseController;
use backend\models\PurchaseOrderMain;
use backend\models\PurchaseExpress;

use backend\models\Car;
use backend\models\ConfigCategory;
use backend\models\CarFault;
use backend\models\CarDrivingLicense;
use backend\models\CarRoadTransportCertificate;
use backend\models\CarSecondMaintenance;
use backend\models\CarInsuranceCompulsory;
use backend\models\CarInsuranceBusiness;
use backend\models\CarBrand;
use backend\models\Owner;
use backend\models\OperatingCompany;
use backend\models\Battery;
use backend\models\Motor;
use backend\models\MotorMonitor;
use backend\classes\UserLog;
use common\models\Excel;
use common\models\File;
use yii;
use yii\base\Object;
use yii\data\Pagination;
use backend\models\Admin;
use common\classes\Category;
use backend\models\CarType;

class PurchaseOrderController extends BaseController
{
	public function actionIndex()
	{
		$buttons = $this->getCurrentActionBtn();
		//获取配置数据
		$configItems = ['purchase_send_status','purchase_arrive_status','purchase_on_card_status','purchase_storage_status'];
		$config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
		//查询表单select选项
		$searchFormOptions = [];
		//各种状态查询字段
		if($config['purchase_send_status'])
		{
			$searchFormOptions['purchase_send_status'] = [];
			$searchFormOptions['purchase_send_status'][] = ['value'=>'','text'=>'不限'];
			foreach($config['purchase_send_status'] as $val){
				$searchFormOptions['purchase_send_status'][] = ['value'=>$val['value'],'text'=>$val['text']];
			}
		}
		if($config['purchase_arrive_status'])
		{
			$searchFormOptions['purchase_arrive_status'] = [];
			$searchFormOptions['purchase_arrive_status'][] = ['value'=>'','text'=>'不限'];
			foreach($config['purchase_arrive_status'] as $val){
				$searchFormOptions['purchase_arrive_status'][] = ['value'=>$val['value'],'text'=>$val['text']];
			}
		}
		if($config['purchase_on_card_status'])
		{
			$searchFormOptions['purchase_on_card_status'] = [];
			$searchFormOptions['purchase_on_card_status'][] = ['value'=>'','text'=>'不限'];
			foreach($config['purchase_on_card_status'] as $val){
				$searchFormOptions['purchase_on_card_status'][] = ['value'=>$val['value'],'text'=>$val['text']];
			}
		}
		if($config['purchase_storage_status'])
		{
			$searchFormOptions['purchase_storage_status'] = [];
			$searchFormOptions['purchase_storage_status'][] = ['value'=>'','text'=>'不限'];
			foreach($config['purchase_storage_status'] as $val){
				$searchFormOptions['purchase_storage_status'][] = ['value'=>$val['value'],'text'=>$val['text']];
			}
		}
		//echo '<pre>';


		//var_dump($config['purchase_send_status']);exit;
		return $this->render('index',[
				'buttons'=>$buttons,
				'config'=>$config,
				'searchFormOptions'=>$searchFormOptions,
				]);
	}
	/**
	 * 获取采购订单列表
	 */
	public function actionGetList(){

		$query = PurchaseOrderMain::find()
		->select(['{{%purchase_order_main}}.id',
				'{{%purchase_order_main}}.contract_number',
				'{{%purchase_order_main}}.order_number',
				'{{%purchase_order_main}}.distributor_name',
				'{{%purchase_order_main}}.sign_time',
				'{{%purchase_order_main}}.add_time',
				'{{%purchase_order_main}}.estimated_delivery_time',
				'{{%purchase_order_main}}.last_edit_time',
				'{{%operating_company}}.name operating_company_name',
				'{{%owner}}.name owner_name'
				]
		)
		->leftJoin('{{%operating_company}}','{{%purchase_order_main}}.operating_company_id = {{%operating_company}}.id and {{%operating_company}}.is_del=0')
		->leftJoin('{{%owner}}','{{%purchase_order_main}}.receiver_id = {{%owner}}.id and {{%owner}}.is_del=0')
		->andWhere(['{{%purchase_order_main}}.`is_del`'=>0]);
		//查询条件开始
		$query->andFilterWhere(['like','`order_number`',yii::$app->request->get('order_number')]);

		$start_date= yii::$app->request->get('start_date');
		if($start_date)
		{
			$start_date = strtotime($start_date);
			$query->andWhere('{{%purchase_order_main}}.add_time >=:start_date',[':start_date'=>$start_date]);
		}
		$end_date= yii::$app->request->get('end_date');
		if($end_date)
		{
			$end_date = strtotime($end_date." 23:59:59");
			$query->andWhere('{{%purchase_order_main}}.add_time <=:end_date',[':end_date'=>$end_date]);
		}
		//end
		$sortColumn = yii::$app->request->get('sort');
		$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
		$orderBy = '';
		if($sortColumn){
			switch ($sortColumn) {
				default:
					$orderBy = $sortColumn.' ';
				break;
			}
		}else{
			$orderBy = '{{%purchase_order_main}}.`id` ';
		}
		$orderBy .= $sortType;
		$total = $query->count();
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
		$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
		$data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)
		->asArray()->all();

		$returnArr = [];
		$returnArr['rows'] = $data;
		$returnArr['total'] = $total;
		return json_encode($returnArr);
	}



	//采购添加
	public function actionAdd(){
		$connection = yii::$app->db;
		if(yii::$app->request->isPost){
			$contract_number = yii::$app->request->post('contract_number');//合同编号
			$distributor_name = yii::$app->request->post('distributor_name');//经销商名称
			$order_number = yii::$app->request->post('order_number');//订单编号
			$operating_company_id = yii::$app->request->post('operating_company_id');//
			//接受方
			$sign_time = yii::$app->request->post('sign_time');//合同签署时间
			$receiver_id = yii::$app->request->post('receiver_id');//所有人
			$estimated_delivery_time = yii::$app->request->post('estimated_delivery_time');//预计发货时间
			//$sign_time2 = yii::$app->request->post('sign_time2');//合同签署时间
			$note = yii::$app->request->post('note');//其他消息
			//接受的车辆信息
			$car_brand = [];
			$car_brand = yii::$app->request->post('car_brand');//车辆品牌
			$car_type = [];
			$car_type = yii::$app->request->post('car_type');//车辆类型
			$car_number = [];
			$car_number = yii::$app->request->post('car_number');//车辆数量
			$car_gary = [];
			$car_gary = yii::$app->request->post('car_gary');//类别编码
			$c_number = [];
			$c_number = yii::$app->request->post('c_number');//其他|配件
			
			$arr = [];
			foreach($car_brand as $key => $val1) {
				$arr[$key]['brand_id'] = $val1;
			}
			foreach($car_type as $key => $val2) {
				$arr[$key]['car_type'] = $val2;
			}
			foreach($car_number as $key => $val3) {
				if(!is_numeric($val3)){
					$returnArr['status'] = false;
					$returnArr['info'] = '车辆数量填写错误！';
					return json_encode($returnArr);
				}
				$arr[$key]['car_number'] = $val3;
			}
			foreach($car_gary as $key => $val4) {
				$arr[$key]['car_gary'] = $val4;
			}
			//var_dump($car_number);
			//var_dump($c_number);exit;
			foreach($c_number as $key => $val5) {
				$arr[$key]['c_number'] = $val5;
			}
			//查询车辆所有人编码
			$owner_code = Owner::find()->select(['code'])->andWhere(['`id`'=>$receiver_id])->asArray()->one();
			//var_dump($owner_code['code'].$contract_number);exit;
			$order_number = $owner_code['code'].$contract_number;

			$reg_record = $connection->createCommand()->insert('cs_purchase_order_main', [
					'contract_number' => $contract_number,
					'distributor_name' => $distributor_name,
					'order_number' => $order_number,
					//'order_number' => '123',
					'operating_company_id' => $operating_company_id?$operating_company_id:0,
					'sign_time' => strtotime($sign_time),
					'receiver_id' => $receiver_id?$receiver_id:0,
					'estimated_delivery_time' => strtotime($estimated_delivery_time),
					'add_time' => time(),
					'note' => $note,
					])->execute();

			//获取当前插入数据的id
			$main_id = yii::$app->db->getLastInsertID();

			//var_dump($_id);exit;
			foreach($arr as $key1 => $value1) {
				if(!$value1['car_number']){
					continue;
				}
				$reg_record2 = $connection->createCommand()->insert('cs_purchase_order_details', [
						'brand_id' =>$value1['brand_id'],
						'car_type_id' =>$value1['car_type'],
						'quantity' =>$value1['car_number'],
						'item_type' =>$value1['car_gary'],
						'main_id'=>$main_id,
						'parts'=>$value1['c_number']
						])->execute();
			}

			//var_dump($reg_record2);exit;
			if($reg_record && $reg_record2){
				$returnArr['status'] = true;
				$returnArr['info'] = '添加成功!';
			} else {
				$returnArr['status'] = false;
				$returnArr['info'] = '添加失败!';
			}
			return json_encode($returnArr);
		}

		//自动生成订单编号
		//$order_n = 'DST-CGB'.date("Ymd");
		//var_dump($main_id);exit;

		//查询所有的车型
		$arr2 = CarType::find()->select(['id','car_model','car_model_name'])->andWhere(['`is_del`'=>0])->asArray()->all()/*->groupBy('car_model')*/;
		//         $config = (new ConfigCategory)->getCategoryConfig(['car_model_name'],'value');
		//         foreach($cartype_a as $key2 =>$val2) {
		//             $obje = [];
		//             $obje['text'] = $config['car_model_name'][$val2['car_model']]['text'];
		//             $obje['id'] = $val2['id'];
		//             $arr2[] = $obje;
		//         }
		$searchFormOptions = [];
		//车辆运营公司
		$ret_data = $this->_oc();
		//echo '<pre>';
		//var_dump($ret_data['operating_company_id']);exit;
		$searchFormOptions['operating_company_id'] = !empty($ret_data['operating_company_id']) ? $ret_data['operating_company_id']:array();

		//车辆类型
		if($config['car_model_name']){
			$searchFormOptions['car_model_name'] = [];
			$searchFormOptions['car_model_name'][] = ['value'=>'','text'=>'不限'];
			foreach($config['car_model_name'] as $val){
				$isexist = false;
				foreach ($searchFormOptions['car_model_name'] as $obj){ //去重
					if($obj['value'] == $val['text']){
						$isexist = true;
						break;
					}
				}
				if(!$isexist){
					$searchFormOptions['car_model_name'][] = ['value'=>$val['value'],'text'=>$val['text']];
				}
			}
		}
		//品牌
		$query1 = CarBrand::find()
		->select(['id','pid','text'=>'name'])
		->andWhere(['`is_del`'=>0]);
		$rows = $query1->asArray()->all();
		$nodes = [];
		if(!empty($rows)){
			$nodes = Category::unlimitedForLayer($rows,'pid');
		}
		$data = [['id'=>0,'text'=>'顶级','iconCls'=>'icon-filter','children'=>$nodes]];
		$data1 = array();
		foreach($data[0]['children'] as $key => $value3){
			$obj = array();
			if(count($value3['children']) != 0){
				$obj['id'] = $value3['children'][0]['id'];
				$obj['text'] = $value3['children'][0]['text'];
				$data1[] = $obj;
			} else {
				$obj['id'] = $value3['id'];
				$obj['text'] = $value3['text'];
				$data1[] = $obj;
			}
		}
		return $this->render('add',['cars'=>$cars,'searchFormOptions'=>$searchFormOptions,'data1'=>$data1,'arr2'=>$arr2,'order_n'=>$order_n]);
	}

	/**
	 * 车辆运营公司
	 * @return array()
	 */
	private function _oc()
	{
		$searchFormOptions = [];
		//车辆运营公司
		$oc = (new \yii\db\Query())->from('cs_operating_company')->where('is_del=0')->all();
		$searchFormOptions['operating_company_id'] = $oc;

		return $searchFormOptions;
	}

	/**
	 * 发车，产生运单
	 */
	public function actionStart() {
		$user_id = $_SESSION['backend']['adminInfo']['id'];
		$user_name = Admin::find()->select(['name'])->where(['id'=>$user_id])->asArray()->one();
		$time = date('Y-m-d H:i:s',time());
		
		$connection = yii::$app->db;

		if(yii::$app->request->isPost){
			$id = yii::$app->request->post('id');
			$order_main  = (new \yii\db\Query())->from('cs_purchase_order_main')->andWhere(['`id`'=>$id])->one();
			$row_n = Owner::find()->select(['name'])->andWhere(['`id`'=>$row['receiver_id']])->asArray()->one();
			$order_main['row_n'] = $row_n['name'];
			
			$true_delivery_time = yii::$app->request->post('true_delivery_time');//实际发货时间
			$express_company = yii::$app->request->post('express_company');//承运公司
			$express_number = yii::$app->request->post('express_number');//运单编号
			$express_phone = yii::$app->request->post('express_phone');//联系电话
			$estimated_arrive_time = yii::$app->request->post('estimated_arrive_time');//预计到达时间
			$start_num = yii::$app->request->post('start_num');	//发车数量
			$order_details_ids = yii::$app->request->post('order_details_id');	//订单详情ids
			$details = [];
			foreach ($order_details_ids as $index=>$row){
				array_push($details, ['order_details_id'=>$row,'start_num'=>$start_num[$index]]);
			}
			//增加运单及运单详情
			$start_num = 0;
			foreach ($details as $row){
				$start_num += $row['start_num'];
			}
			if(strtotime($estimated_arrive_time) <= strtotime($true_delivery_time)){
				$returnArr['status'] = false;
				$returnArr['info'] = '预计到达时间要在实际发货时间之后!';
				return json_encode($returnArr);
			}
			if(!$start_num){
				$returnArr['status'] = false;
				$returnArr['info'] = '本次发送数量不能为0!';
				return json_encode($returnArr);
			}
			$transaction = $connection->beginTransaction();
			$purchase_express_record = $connection->createCommand()->insert('cs_purchase_express', [
					'express_company' => $express_company,
					'express_number' => $express_number,
					'express_phone' => $express_phone,
					'estimated_arrive_time' => strtotime($estimated_arrive_time),
					'true_delivery_time' => strtotime($true_delivery_time),
					'main_id' => $order_main['id'],
					'is_send' => 'IS_SEND',
					'start_num' => $start_num,
					'add_time' => time(),
					'details_text' => json_encode($details)
					])->execute();
			$express_id = yii::$app->db->getLastInsertID();
			
			$purchase_express_details_record = true;
			foreach ($details as $row){
				for($i=0; $i<$row['start_num']; $i++){
					$purchase_express_details_record = $connection->createCommand()->insert('cs_purchase_express_details', [
							'express_id' => $express_id,
							'order_details_id' => $row['order_details_id']
							])->execute();
					if(!$purchase_express_details_record){
						break;
					}
				}
				if(!$purchase_express_details_record){
					break;
				}
			}
			
			//var_dump($ed_record);exit;
			if($purchase_express_record && $purchase_express_details_record){
				$transaction->commit();  //提交事务
				$returnArr['status'] = true;
				$returnArr['info'] = '物流状态添加成功!';
			} else {
				$transaction->rollback(); //回滚事务
				$returnArr['status'] = false;
				$returnArr['info'] = '物流状态添加失败!';
			}
			return json_encode($returnArr);
		}

		$id = yii::$app->request->get('id');
		$order_main  = (new \yii\db\Query())->from('cs_purchase_order_main')->andWhere(['`id`'=>$id])->one();
		$row_n = Owner::find()->select(['name'])->andWhere(['`id`'=>$order_main['receiver_id']])->asArray()->one();
		$order_main['row_n'] = $row_n['name'];
		//采购的车辆查询
		$row_c  = (new \yii\db\Query())->select(['id','item_type','brand_id','car_type_id','quantity','parts'])->from('cs_purchase_order_details')->andWhere(['`main_id`'=>$id])->all();
		//计算订单已发车数量
		$already_num_arr = [];
		$purchase_express_list  = (new \yii\db\Query())->select(['details_text'])->from('cs_purchase_express')->andWhere(['`main_id`'=>$id])->all();
		foreach ($purchase_express_list as $purchase_express){
			$details_list = $purchase_express['details_text']?json_decode($purchase_express['details_text']):[];
			foreach ($details_list as $details){
				$already_num_arr[$details->order_details_id] = $already_num_arr[$details->order_details_id]+$details->start_num;
			}
		}
		foreach ($row_c as $index=>$row){
			$row_c[$index]['already_num'] = @$already_num_arr[$row['id']];
		}
		
		return $this->render('start',['row'=>$order_main,'row_c'=>$row_c]);
	}
	//发车状态查看
	/*public function actionStart2() {
	 echo 'm1';exit;
	$id = 4;
	$data  = (new \yii\db\Query())->from('cs_purchase_express')->andWhere(['`id`'=>$id])->one();

	return $this->render('start2',['data'=>$data]);
	}*/
	//查看详情
	public function actionInfo() {
		//echo 'mm';
		$id = yii::$app->request->get('id');
		/*$result_main = (new \yii\db\Query())->from('cs_purchase_order_main a')
					->leftjoin('cs_purchase_order_details b','a.id=b.main_id')
					->where(['is_del'=>0,'a.id'=>$id])->all()
					;*/
		$result_main = (new \yii\db\Query())->from('cs_purchase_order_main a')
					->select(['a.*','b.name as company_name','c.name as receiver_name','b.area'])
					->leftjoin('cs_operating_company b','b.id = a.operating_company_id')
					->leftjoin('cs_owner c','c.id = a.receiver_id')
					->where(['a.is_del'=>0,'a.id'=>$id])->one()
					;

		/*a.order_details_id*/
		$result_details = (new \yii\db\Query())->from('cs_purchase_order_details a')
					->select(['a.*','b.name as brand_name','c.car_model_name'])
					->leftjoin('cs_car_brand b','a.brand_id = b.id')
					->leftjoin('cs_car_type c','c.id = a.car_type_id')
					->where(['main_id'=>$result_main['id']])->all()
					;
		//查找发货批次 cs_purchase_express
		//order_number 预计发货时间 订单编号在主表；
		$result_express = (new \yii\db\Query())->from('cs_purchase_express a')
					->select(['a.details_text','c.name as company_name','b.order_number','b.estimated_delivery_time','b.operating_company_id','a.true_delivery_time','a.express_company','a.express_number','a.express_phone','a.estimated_arrive_time'])
					->leftjoin('cs_purchase_order_main b','a.main_id = b.id')
					->leftjoin('cs_operating_company c','c.id = b.operating_company_id')
					->where(['main_id'=>$result_main['id']])->all()
					;
		/*
		*查找批次发货的车辆详情。1、
		*
		*/
		/*echo '<pre>';
		var_dump($result_express);exit;*/
		foreach($result_express as $key => $value) {
			$val = json_decode($value['details_text']);
			$result_express2=[];
			foreach ($val as $key1 => $value1) {

				$order_id = $value1->order_details_id;//订单id
				$order_num = $value1->start_num;//每批发车数量
				$aa = (new \yii\db\Query())->from('cs_purchase_order_details a')
					->select(['a.*','b.name as brand_name','c.car_model_name'])
					->leftjoin('cs_car_brand b','a.brand_id = b.id')
					->leftjoin('cs_car_type c','c.id = a.car_type_id')
					->where(['a.id'=>$order_id])->one()
					;
				$result_express2[$key]['order'][] = $aa;
				$result_express[$key]['order_num'][] = $order_num;	
			}
			
			/*$result_express[$key]['order'] = $aa;
			$result_express[$key]['order_num'] = $order_num;*/
			/*echo '<pre>';
			var_dump($aa);exit;*/			
		}
		/*echo '<pre>';
		var_dump($result_express2);exit;*/
		//var_dump($result_express);exit;
		return $this->render('info',['result_main'=>$result_main,'result_details'=>$result_details,'result_express'=>$result_express,'result_express2'=>$result_express2]);

		}



}