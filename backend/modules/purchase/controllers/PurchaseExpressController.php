<?php

/**
 * 采购运单控制器
 * @author pengyl
 *
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

class PurchaseExpressController extends BaseController
{
	public function actionIndex()
    {
		$buttons = $this->getCurrentActionBtn();
        //查询表单select选项
        $searchFormOptions = [];
		return $this->render('index',[
            'buttons'=>$buttons,
            'config'=>$config,
        ]);		
    }
    
	/**
	 * 获取运单列表
	 */
	public function actionGetList(){
		
        $query = PurchaseExpress::find()
	            ->select([
	            		'{{%purchase_express}}.*',
	            		'{{%purchase_order_main}}.contract_number',
	            		'{{%purchase_order_main}}.order_number',
	            		'{{%purchase_order_main}}.distributor_name',
	            		'{{%purchase_order_main}}.sign_time',
	            		'{{%operating_company}}.name operating_company_name',
	            		'{{%owner}}.name owner_name'
	            		])
	            ->leftJoin('{{%purchase_order_main}}', '{{%purchase_express}}.`main_id` = {{%purchase_order_main}}.`id` ')
	            ->leftJoin('{{%operating_company}}','{{%purchase_order_main}}.operating_company_id = {{%operating_company}}.id and {{%operating_company}}.is_del=0')
	            ->leftJoin('{{%owner}}','{{%purchase_order_main}}.receiver_id = {{%owner}}.id and {{%owner}}.is_del=0')
	            ->andWhere(['{{%purchase_express}}.`is_del`'=>0]);
        //查询条件开始	
        $query->andFilterWhere(['like','`order_number`',yii::$app->request->get('order_number')]);
        $purchase_arrive_status = yii::$app->request->get('purchase_arrive_status');
		if ($purchase_arrive_status){
			if($purchase_arrive_status == 1){	//已到车
				$query->andWhere("{{%purchase_express}}.arrive_num = {{%purchase_express}}.start_num");
			}else {	//未到车
				$query->andWhere("{{%purchase_express}}.arrive_num <> {{%purchase_express}}.start_num");
			}
		}
		$purchase_on_card_status = yii::$app->request->get('purchase_on_card_status');
		if ($purchase_on_card_status){
			if($purchase_on_card_status == 1){	//已上牌
				$query->andWhere("{{%purchase_express}}.on_card_num = {{%purchase_express}}.start_num");
			}else {	//未上牌
				$query->andWhere("{{%purchase_express}}.on_card_num <> {{%purchase_express}}.start_num");
			}
		}
		$purchase_storage_status = yii::$app->request->get('purchase_storage_status');
		if ($purchase_storage_status){
			if($purchase_storage_status == 1){	//已入库
				$query->andWhere("{{%purchase_express}}.storage_num = {{%purchase_express}}.start_num");
			}else {	//未入库
				$query->andWhere("{{%purchase_express}}.storage_num <> {{%purchase_express}}.start_num");
			}
		}
		if (yii::$app->request->get('purchase_person')){
			$query->andFilterWhere(['like','`purchase_person`',yii::$app->request->get('purchase_person')]);
		}
		if (yii::$app->request->get('area_vehicle_person')){
			$query->andFilterWhere(['like','`area_vehicle_person`',yii::$app->request->get('area_vehicle_person')]);
		}
		$start_date= yii::$app->request->get('start_date');		     
        if($start_date)
        {
			$start_date = strtotime($start_date);
            $query->andWhere('{{%purchase_express}}.add_time >=:start_date',[':start_date'=>$start_date]);
        }        
        $end_date= yii::$app->request->get('end_date');
        if($end_date)
        {
			$end_date = strtotime($end_date." 23:59:59");
            $query->andWhere('{{%purchase_express}}.add_time <=:end_date',[':end_date'=>$end_date]);
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
        	$orderBy = '{{%purchase_express}}.`id` ';
        }
        $orderBy .= $sortType;
        
//         echo $query->createCommand()->getRawSql();exit;
        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)
		->asArray()->all();
		//echo '<pre>';
//var_dump($data);exit;
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
	}
    
	/**
	 * 到车登记
	 */
	public function actionArrive()
	{
		$connection = yii::$app->db;
		$purchase_express_id = yii::$app->request->get('id');
		//点击提交
		$data=yii::$app->request->post();
		if($data){
			$transaction = $connection->beginTransaction();
			$arrive_num = 0;
			for($i=0;$i<count($data['id']);$i++){
				$vehicle_dentification_number=$data['vehicle_dentification_number'][$i];
				$invoice_number=$data['invoice_number'][$i];
				$id=$data['id'][$i];
				$storage_location = $data['storage_location'][$i];
				if($vehicle_dentification_number && $invoice_number && $storage_location){
					$arrive_num++;
				}
				//更新运单详情
				$connection->createCommand()->update('cs_purchase_express_details', [
					'vehicle_dentification_number' => $vehicle_dentification_number,
					'invoice_number' => $invoice_number,
					'storage_location' => $storage_location
					],"id=$id")->execute();
			}
			//更新运单已到车数量
			$record = $connection->createCommand()->update('cs_purchase_express', [
					'arrive_num' => $arrive_num
					],"id=".$data['purchase_express_id'])->execute();
			
			if(true){
				$transaction->commit();  //提交事务
				$returnArr['status'] = true;
				$returnArr['info'] = '更新成功!';
			} else {
				$transaction->rollback(); //回滚事务
				$returnArr['status'] = false;
				$returnArr['info'] = '更新失败!';
			}
			
			return json_encode($returnArr);
		}
		
		//运单
		$purchase_express = (new \yii\db\Query())
		->select('a.id,a.express_number,a.estimated_arrive_time,a.is_storage,b.order_number,c.name as operating_company_name')
		->from('cs_purchase_express a')
		->leftJoin('cs_purchase_order_main as b','a.main_id = b.id')
		->leftJoin('cs_operating_company as c','b.operating_company_id=c.id and c.is_del=0')
		->where(['a.id'=>$purchase_express_id])
		->one();
		//运单详情
		$purchase_express_detials=Yii::$app->db->createCommand(
				"SELECT a.*,b.item_type,c.name as brand_name,d.car_model_name as type_name
				FROM cs_purchase_express_details a
				left join cs_purchase_order_details b on a.order_details_id=b.id
				left join cs_car_brand c on c.id=b.brand_id and c.is_del=0
				left join cs_car_type d on d.id=b.car_type_id and d.is_del=0
				where a.express_id=".$purchase_express_id)->queryAll();
		return $this->render('arrive',['purchase_express'=>$purchase_express,'order_detials'=>$purchase_express_detials]);
	}
	
	/**
	 * 获取入库提示
	 */
	public function actionGetStorageMsg()
	{
		$data=yii::$app->request->post();
		$on_card_num = 0;
		$no_card_num = 0;
		
		for($i=0;$i<count($data['id']);$i++){
			$engine_number=$data['engine_number'][$i];
			$plate_number=$data['plate_number'][$i];
			$is_storage=$data['is_storage'][$i];
			
			if(!$is_storage){
				if($engine_number && $plate_number){
					$on_card_num++;
				}else {
					$no_card_num++;
				}
			}
		}
		$returnArr['status'] = true;
		$returnArr['info'] = '你现在提交'.$on_card_num.'台车辆，还有'.$no_card_num.'台信息不完整，确定提交车辆数据将到车辆管理->车辆基本信息！';
		return json_encode($returnArr);
	}
	/**
	 * 上牌登记
	 */
	public function actionOnCard()
	{
		$connection = yii::$app->db;
		$purchase_express_id = yii::$app->request->get('id');
		$data=yii::$app->request->post();
		if($data){
			$purchase_express_id = $data['purchase_express_id'];
			try {
				$transaction = $connection->beginTransaction();
				//运单已上牌数量计算开始...
				$on_card_num = 0;
				for($i=0;$i<count($data['id']);$i++){
					$engine_number=$data['engine_number'][$i];
					$plate_number=$data['plate_number'][$i];
					$is_storage=$data['is_storage'][$i];
					$id=$data['id'][$i];
					if($engine_number && $plate_number){
						$on_card_num++;
					}
					//更新运单详情
					if(!$is_storage){
						$connection->createCommand()->update('cs_purchase_express_details', [
								'engine_number' => $engine_number,
								'plate_number' => $plate_number
								],"id=$id")->execute();
					}
				}
				$connection->createCommand()->update('cs_purchase_express', [
						'on_card_num' => $on_card_num
						],"id=".$purchase_express_id)->execute();
				//运单已上牌数量计算end
				
				//提交车辆（入库）
				if($data['is_submit']){
					$purchase_express_detials = $connection->createCommand(
							"SELECT
								 a.id,a.vehicle_dentification_number,a.plate_number,a.engine_number,a.is_storage,
								 b.brand_id,b.car_type_id,c.receiver_id,c.operating_company_id
								 FROM cs_purchase_express_details a
								left join cs_purchase_order_details b on a.order_details_id=b.id
								left join cs_purchase_order_main c on b.main_id=c.id
								where a.vehicle_dentification_number!='' and a.invoice_number!='' and a.engine_number!=''
									 and a.plate_number!='' and a.express_id=".$data['purchase_express_id'])->queryAll();
					
					//更新运单已入库数量
					$connection->createCommand()->update('cs_purchase_express', [
							'storage_num' => count($purchase_express_detials)
							],"id=".$purchase_express_id)->execute();
					$no_storage_num = 0;
					$storage_num = 0;
					foreach ($purchase_express_detials as $row){
						if($row['is_storage']){
							continue;
						}
						$car = $connection->createCommand(
								"SELECT id FROM cs_car
								where (vehicle_dentification_number=(:vin) or plate_number=(:plate_number)) and is_del=0"
								)->bindValues([':vin'=>$row['vehicle_dentification_number'],':plate_number'=>$row['plate_number']])
								->queryOne();
						if(!$car){
							$connection->createCommand()->insert('cs_car', [
									'vehicle_dentification_number' => $row['vehicle_dentification_number'],
									'plate_number' => $row['plate_number'],
									'engine_number' => $row['engine_number'],
									'brand_id' => $row['brand_id'],
									'car_type_id' => $row['car_type_id'],
									'owner_id' => $row['receiver_id'],
									'car_status' => 'NAKED',
									'add_time' => time(),
									'add_aid' => $_SESSION['backend']['adminInfo']['id'],
									'operating_company_id' => $row['operating_company_id']
								])->execute();
							//标识已入库
							$connection->createCommand()->update('cs_purchase_express_details', [
									'is_storage' => 1
									],"id=".$row['id'])->execute();
							$storage_num++;
						}else {
							$no_storage_num++;
						}
					}
				}
				$transaction->commit();  //提交事务
				$returnArr['status'] = true;
				if(@$no_storage_num){
					$returnArr['info'] = "提交成功，本次操作{$no_storage_num}辆车入库失败，成功{$storage_num}辆。失败原因：车架号/车牌已存在！";
				}else {
					$returnArr['info'] = '更新成功!';
				}
			} catch (Exception $e) {
				$transaction->rollback(); //回滚事务
				$returnArr['status'] = false;
				$returnArr['info'] = '更新失败!';
			}
	
			return json_encode($returnArr);
		}
	
		//运单详情
		$purchase_express_detials=Yii::$app->db->createCommand(
				"SELECT a.*,b.item_type,c.name as brand_name,d.car_model_name as type_name
				FROM cs_purchase_express_details a
				left join cs_purchase_order_details b on a.order_details_id=b.id
				left join cs_car_brand c on c.id=b.brand_id and c.is_del=0
				left join cs_car_type d on d.id=b.car_type_id and d.is_del=0
				where a.express_id=".$purchase_express_id)->queryAll();
		return $this->render('on-card',['purchase_express_id'=>$purchase_express_id,'order_detials'=>$purchase_express_detials]);
	}

	public function actionInfo() {

		$id = yii::$app->request->get('id');

		/*$result_express = (new \yii\db\Query())->from('cs_purchase_express_details a')
					->select(['a.*','b.express_number','c.order_number','c.operating_company_id','b.estimated_arrive_time'])
					->leftjoin('cs_purchase_express b','b.id = a.express_id')
					->leftjoin('cs_purchase_order_main c','c.id = a.order_details_id')
					->where(['a.express_id'=>$id])->all()
					;*/
		$result_express = (new \yii\db\Query())->from('cs_purchase_express_details a')
					->select(['a.*','b.name as brand_name','c.car_model_name'])
					//->leftjoin('cs_purchase_express b','b.id = a.express_id')
					->leftjoin('cs_purchase_order_details d','d.id = a.order_details_id')
					->leftjoin('cs_car_brand b','b.id = d.brand_id')
					->leftjoin('cs_car_type c','c.id = d.car_type_id')

					->where(['a.express_id'=>$id])->all()
					;
		$result_express_r = (new \yii\db\Query())->from('cs_purchase_express a')
					->select(['a.express_number','c.order_number','c.operating_company_id','a.estimated_arrive_time','b.name as company_name'])
					//->leftjoin('cs_purchase_express b','b.id = a.express_id')
					->leftjoin('cs_purchase_order_main c','c.id = a.main_id')
					->leftjoin('cs_operating_company b','b.id = c.operating_company_id')
					
					->where(['a.id'=>$id])->one()
					;

		//echo '<pre>';
		//var_dump($result_express);exit;
		return $this->render('info',['result_express'=>$result_express,'result_express_r'=>$result_express_r]);
	}
}