<?php
/**
 * 车辆数据总览
 */
namespace backend\modules\car\controllers;
use yii;
use backend\controllers\BaseController;
use backend\models\Car;
use backend\models\ConfigCategory;
use backend\models\CarDrivingLicense;
use backend\models\CarRoadTransportCertificate;
use backend\models\CarSecondMaintenance;
use backend\models\CarInsuranceCompulsory;
use backend\models\CarInsuranceBusiness;
use backend\models\CarLetRecord;
use backend\models\CarFault;
use backend\models\CarTrialProtocolDetails;
use backend\models\TcpCarRealtimeData;
use backend\models\Owner;
use backend\models\CarBrand;
use backend\models\OperatingCompany;
use yii\data\Pagination;

class OverviewController extends BaseController{
	/**
	 * 车辆变更记录
	 */
	public function actionStatusChangeLog(){
		$car_id = yii::$app->request->get('car_id');
		if(!$car_id){
			return '参数错误！';
		}
		//获取配置数据
		$configItems = ['car_status'];
		$config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
		//查询表单select选项
		$searchFormOptions = [];
		if($config['car_status']){
			$searchFormOptions['car_status'] = [];
			$searchFormOptions['car_status'][] = ['value'=>'','text'=>'不限'];
			foreach($config['car_status'] as $val){
				$searchFormOptions['car_status'][] = ['value'=>$val['value'],'text'=>$val['text']];
			}
		}
		return $this->render('status-change-log',[
				'car_id'=>$car_id,
				'config'=>$config,
				'searchFormOptions'=>$searchFormOptions,
				]);
	}
	/**
	 * 获取车辆变更记录列表
	 */
	public function actionGetStatusChangeLogs()
	{
		$car_id = intval(yii::$app->request->get('car_id')) or die("Not pass 'car_id'.");

		$connection = yii::$app->db;
		//先查DEAL_TYPE=0（开始充电）
		$query = (new \yii\db\Query())
		->select(['*'])->from('cs_car_status_change_log')
		->where([
				'and',
				['car_id'=>$car_id]
				]);
		//查询条件
		if(yii::$app->request->get('add_time_start')){
			$query->andFilterWhere(['>=','add_time',yii::$app->request->get('add_time_start')]); //开始时间
		}
		if(yii::$app->request->get('add_time_end')){ //结束时间
			$query->andFilterWhere(['<=','add_time',yii::$app->request->get('add_time_end').' 23:59:59']);
		}
		if(yii::$app->request->get('car_status')){ //结束时间
			$query->andFilterWhere(['=','car_status',yii::$app->request->get('car_status')]);
		}


		//查总数
		$total = $query->count('id', $connection);
		//分页
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
		$pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
		//排序
		if(yii::$app->request->get('sort')){
			$field = yii::$app->request->get('sort');		//field
			$direction = yii::$app->request->get('order');  //asc or desc
			$orderStr = $field.' '.$direction;
		}else{
			$orderStr = 'id desc';
		}
		$query->orderBy($orderStr)->offset($pages->offset)->limit($pages->limit);
		//echo $query->createCommand()->sql;exit;
		$res = $query->all($connection);
		$returnArr = [];
		$returnArr['rows'] = $res;
		$returnArr['total'] = $total;
		return json_encode($returnArr);
	}

	/**
	 * 数据总览入口
	 */
	public function actionIndex(){
		return $this->render('index');
	}

	/**
	 * 检索车辆数据
	 */
	public function actionSearch(){
		$vin_or_platenumber = yii::$app->request->get('vin_or_platenumber');
		if(!$vin_or_platenumber){
			return false;
		}
		$columns = [
		'plate_number','vehicle_dentification_number','car_status',
		'car_type','car_color','add_time','id'
		];
		//优先按车牌号查询
		$query = Car::find()
		->select($columns)
		->where(['like','plate_number',$vin_or_platenumber])
		->andWhere(['is_del'=>0]);
		//检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
		$isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
		if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
			$query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
		}
		$carInfo = $query->limit(1)->asArray()->one();
		if(!$carInfo){
			$query = Car::find()
			->select($columns)
			->where(['like','vehicle_dentification_number',$vin_or_platenumber])
			->andWhere(['is_del'=>0]);
			//检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
			$isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
			if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
				$query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
			}
			$carInfo = $query->asArray()->one();
		}
		
		if($carInfo){
			//获取要显示的btn
			$buttons = $this->getCurrentActionBtn();
			foreach($buttons as &$controllerBtnItem){
				$controllerBtnItem['href'] = '?r='.$controllerBtnItem['target_mca_code'].'&car_id='.$carInfo['id'];
			}
			/*echo '<pre>';
			 var_dump($buttons);
			echo '</pre>';*/
			//获取配置
			$config = (new ConfigCategory)->getCategoryConfig(['car_status','car_type','car_color'],'value');
			//检测行驶证
			if(CarDrivingLicense::find()->select(['id'])->where(['car_id'=>$carInfo['id']])->one()){
				$carInfo['drivingLicense'] = '已办理';
			}else{
				$carInfo['drivingLicense'] = '未办理';
			}
			//检测道路运输证
			if(CarRoadTransportCertificate::find()->select(['id'])->where(['car_id'=>$carInfo['id']])->one()){
				$carInfo['roadTransportCertificate'] = '已办理';
			}else{
				$carInfo['roadTransportCertificate'] = '未办理';
			}
			//检测二级维护卡
			if(CarSecondMaintenance::find()->select(['id'])->where(['car_id'=>$carInfo['id']])->one()){
				$carInfo['secondMaintenance'] = '已办理';
			}else{
				$carInfo['secondMaintenance'] = '未办理';
			}
			//检测交强险
			if(CarInsuranceCompulsory::find()->select(['id'])->where(['car_id'=>$carInfo['id']])->one()){
				$carInfo['insuranceCompulsory'] = '已办理';
			}else{
				$carInfo['insuranceCompulsory'] = '未办理';
			}
			//检测商业险
			if(CarInsuranceBusiness::find()->select(['id'])->where(['car_id'=>$carInfo['id']])->one()){
				$carInfo['insuranceBusiness'] = '已办理';
			}else{
				$carInfo['insuranceBusiness'] = '未办理';
			}
			$carInfo['car_status'] = $config['car_status'][$carInfo['car_status']]['text'];
			$carInfo['car_type'] = $config['car_type'][$carInfo['car_type']]['text'];
			$carInfo['car_color'] = $config['car_color'][$carInfo['car_color']]['text'];
			$carInfo['add_time'] = date('Y-m-d H:i:s',$carInfo['add_time']);
		}else{
			$buttons = [];
		}
		return $this->render('search',[
				'carInfo'=>$carInfo,
				'buttons'=>$buttons,
				]);
	}

	/**
	 * 显示车辆基本信息
	 */
	public function actionBaseInfo(){
		$carId = yii::$app->request->get('car_id');
		if(!$carId){
			return 'param car_id is required';
		}
		//查询车辆基本信息
		$car = Car::find()
		->select([
				'{{%car}}.*',
				'car_brand_name'=>'{{%car_brand}}.`name`'
				])->joinWith('carBrand',false)
				->where(['{{%car}}.`id`'=>$carId])
				->limit(1)->asArray()->one();
		if(!$car){
			return '无车辆信息！';
		}
		unset($car['brand_id']);
		//查询车辆行驶证信息
		$drivingLicense = CarDrivingLicense::findOne(['car_id'=>$carId]);
		//查询车辆道路运输证信息
		$roadTransportCertificate = CarRoadTransportCertificate::findOne(['car_id'=>$carId]);
		$configItems = [
		'car_status','car_type','use_nature',
		'car_color','import_domestic','fuel_type','turn_type','gain_way','car_status2','DL_REG_ADDR','TC_ISSUED_BY'
		];
		$config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
		//处理数据车辆基本数据
		$carConfigItem = ['car_status','car_type','use_nature',
		'car_color','import_domestic','fuel_type','turn_type','gain_way','car_status2'];
		foreach($carConfigItem as $val){
			$car[$val] = @$config[$val][$car[$val]]['text'];
		}
		$car['add_time'] = $car['add_time'] ? date('Y-m-d H:i:s',$car['add_time']) : '';
		$car['leave_factory_date'] = $car['leave_factory_date'] ? date('Y-m-d',$car['leave_factory_date']) : '';
		$car['issuing_date'] = $car['issuing_date'] ? date('Y-m-d',$car['issuing_date']) : '';
		//查机动车所有人
		$ownerModel = Owner::findOne($car['owner_id']);
		if($ownerModel){
			$car['owner_id'] = $ownerModel->name;
		}
		//查车辆运营公司
		$operatingCompanyModel = OperatingCompany::findOne($car['operating_company_id']);
		if($operatingCompanyModel){
			$car['operating_company_id'] = $operatingCompanyModel->name;
		}
		unset($car['id']);
		unset($car['add_aid']);
		unset($car['is_del']);
		$carAttributeLabels = (new Car)->attributeLabels();
		$carAttributeLabels['car_brand_name'] = '车辆品牌';
		//处理行驶证信息
		if($drivingLicense){
			$drivingLicense['addr'] = $config['DL_REG_ADDR'][$drivingLicense['addr']]['text'];
			$drivingLicense['register_date'] = $drivingLicense['register_date'] ? date('Y-m-d',$drivingLicense['register_date']) : '';
			$drivingLicense['issue_date'] = $drivingLicense['issue_date'] ? date('Y-m-d',$drivingLicense['issue_date']) : '';
		}
		//处理道路运输证信息
		if($roadTransportCertificate){
			$roadTransportCertificate['issuing_date'] = $roadTransportCertificate['issuing_date'] ? date('Y-m-d',$roadTransportCertificate['issuing_date']) : '';
			$roadTransportCertificate['last_annual_verification_date'] = $roadTransportCertificate['last_annual_verification_date'] ? date('Y-m-d H:i:s',$roadTransportCertificate['last_annual_verification_date']) : '';
			$roadTransportCertificate['issuing_organ'] = $config['TC_ISSUED_BY'][$roadTransportCertificate['issuing_organ']]['text'];
		}
		return $this->render('base-info',[
				'car'=>$car,
				'carAttributeLabels'=>$carAttributeLabels,
				'drivingLicense'=>$drivingLicense,
				'roadTransportCertificate'=>$roadTransportCertificate,
				]);
	}

	/**
	 * 显示车辆证件办理详细
	 */
	public function actionLicense(){
		$carId = yii::$app->request->get('car_id');
		if(!$carId){
			echo 'param car_id is required';
			return;
		}
		$configItems = [
		'DL_REG_ADDR','TC_ISSUED_BY','INSURANCE_COMPANY'
		];
		$config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
		//获取年审预警
		$drivingLicense = CarDrivingLicense::find()
		->select([
				'{{%car_driving_license}}.`addr`',
				'{{%car_driving_license}}.`register_date`',
				'{{%car_driving_license}}.`issue_date`',
				'{{%car_driving_license}}.`archives_number`',
				'{{%car_driving_license}}.`total_mass`',
				'{{%car_driving_license}}.`next_valid_date`',
				'{{%car_driving_license}}.`add_datetime`',
				'{{%admin}}.`username`'
				])->joinwith('admin',false)
				->where(['{{%car_driving_license}}.`car_id`'=>$carId])
				->asArray()->One();
		if($drivingLicense){
			$timeStamp = time();
			if($drivingLicense['next_valid_date'] < $timeStamp){
				$drivingLicense['leftDay'] = 0;
			}else{
				$drivingLicense['leftDay'] = ceil(($drivingLicense['next_valid_date'] - $timeStamp) / 86400);
			}
			$drivingLicense['addr'] = $config['DL_REG_ADDR'][$drivingLicense['addr']]['text'];
			$drivingLicense['register_date'] = date('Y-m-d',$drivingLicense['register_date']);
			$drivingLicense['issue_date'] = date('Y-m-d',$drivingLicense['issue_date']);
			$drivingLicense['add_datetime'] = date('Y-m-d',$drivingLicense['add_datetime']);
			unset($drivingLicense['next_valid_date']);
		}
		//获取道路运输证预警
		$transportCertificate = CarRoadTransportCertificate::find()
		->select([
				'{{%car_road_transport_certificate}}.`ton_or_seat`',
				'{{%car_road_transport_certificate}}.`issuing_organ`',
				'{{%car_road_transport_certificate}}.`rtc_province`',
				'{{%car_road_transport_certificate}}.`rtc_city`',
				'{{%car_road_transport_certificate}}.`rtc_number`',
				'{{%car_road_transport_certificate}}.`issuing_date`',
				'{{%car_road_transport_certificate}}.`last_annual_verification_date`',
				'{{%car_road_transport_certificate}}.`next_annual_verification_date`',
				'{{%car_road_transport_certificate}}.`add_datetime`',
				'{{%admin}}.`username`'
				])->joinwith('admin',false)
				->where(['{{%car_road_transport_certificate}}.`car_id`'=>$carId])
				->asArray()->one();
		if($transportCertificate){
			$timeStamp = time();
			if($transportCertificate['next_annual_verification_date'] < $timeStamp){
				$transportCertificate['leftDay'] = 0;
			}else{
				$transportCertificate['leftDay'] = ceil(($transportCertificate['next_annual_verification_date'] - $timeStamp) / 86400);
			}
			unset($transportCertificate['next_annual_verification_date']);
			$transportCertificate['issuing_organ'] = @$config['TC_ISSUED_BY'][$transportCertificate['issuing_organ']]['text'];
			$transportCertificate['issuing_date'] = date('Y-m-d',$transportCertificate['issuing_date']);
			$transportCertificate['last_annual_verification_date'] = date('Y-m-d',$transportCertificate['last_annual_verification_date']);
			$transportCertificate['add_datetime'] = date('Y-m-d',$transportCertificate['add_datetime']);
		}
		//获取交强险预警
		$insuranceCompulsory = CarInsuranceCompulsory::find()
		->select([
				'{{%car_insurance_compulsory}}.`insurer_company`',
				'{{%car_insurance_compulsory}}.`money_amount`',
				'{{%car_insurance_compulsory}}.`start_date`',
				'{{%car_insurance_compulsory}}.`end_date`',
				'{{%car_insurance_compulsory}}.`add_datetime`',
				'{{%admin}}.`username`',
				])->joinWith('admin',false)
				->where(['{{%car_insurance_compulsory}}.`car_id`'=>$carId])
				->andWhere(['{{%car_insurance_compulsory}}.`is_del`'=>0])
				->orderby('{{%car_insurance_compulsory}}.`add_datetime` desc')
				->asArray()->one();
		if($insuranceCompulsory){
			$timeStamp = time();
			if($insuranceCompulsory['end_date'] < $timeStamp){
				$insuranceCompulsory['leftDay'] = 0;
			}else{
				$insuranceCompulsory['leftDay'] = ceil(($insuranceCompulsory['end_date'] - $timeStamp) / 86400);
			}
			$insuranceCompulsory['insurer_company'] = $config['INSURANCE_COMPANY'][$insuranceCompulsory['insurer_company']]['text'];
			$insuranceCompulsory['start_date'] = date('Y-m-d',$insuranceCompulsory['start_date']);
			$insuranceCompulsory['end_date'] = date('Y-m-d',$insuranceCompulsory['end_date']);
			$insuranceCompulsory['add_datetime'] = date('Y-m-d',$insuranceCompulsory['add_datetime']);
		}
		//获取商业险预警
		$insuranceBusiness = CarInsuranceBusiness::find()
		->select([
				'{{%car_insurance_business}}.`insurer_company`',
				'{{%car_insurance_business}}.`money_amount`',
				'{{%car_insurance_business}}.`start_date`',
				'{{%car_insurance_business}}.`end_date`',
				'{{%car_insurance_business}}.`add_datetime`',
				'{{%admin}}.`username`',
				])->joinWith('admin',false)
				->where(['{{%car_insurance_business}}.`car_id`'=>$carId])
				->andWhere(['{{%car_insurance_business}}.`is_del`'=>0])
				->orderby('{{%car_insurance_business}}.`add_datetime` desc')
				->asArray()->one();
		if($insuranceBusiness){
			$timeStamp = time();
			if($insuranceBusiness['end_date'] < $timeStamp){
				$insuranceBusiness['leftDay'] = 0;
			}else{
				$insuranceBusiness['leftDay'] = ceil(($insuranceBusiness['end_date'] - $timeStamp) / 86400);
			}
			$insuranceBusiness['insurer_company'] = $config['INSURANCE_COMPANY'][$insuranceBusiness['insurer_company']]['text'];
			$insuranceBusiness['start_date'] = date('Y-m-d',$insuranceBusiness['start_date']);
			$insuranceBusiness['end_date'] = date('Y-m-d',$insuranceBusiness['end_date']);
			$insuranceBusiness['add_datetime'] = date('Y-m-d',$insuranceBusiness['add_datetime']);
		}
		//获取二级维护预警
		$secondMaintenance = CarSecondMaintenance::find()
		->select([
				'{{%car_second_maintenance}}.`number`',
				'{{%car_second_maintenance}}.`current_date`',
				'{{%car_second_maintenance}}.`next_date`',
				'{{%car_second_maintenance}}.`add_datetime`',
				'{{%admin}}.`username`',
				])->joinWith('admin',false)
				->where(['{{%car_second_maintenance}}.`car_id`'=>$carId])
				->andWhere(['{{%car_second_maintenance}}.`is_del`'=>0])
				->orderby('{{%car_second_maintenance}}.`add_datetime` desc')
				->asArray()->one();
		if($secondMaintenance){
			$timeStamp = time();
			if($secondMaintenance['next_date'] < $timeStamp){
				$secondMaintenance['leftDay'] = 0;
			}else{
				$secondMaintenance['leftDay'] = ceil(($secondMaintenance['next_date'] - $timeStamp) / 86400);
			}
			$secondMaintenance['current_date'] = date('Y-m-d',$secondMaintenance['current_date']);
			$secondMaintenance['next_date'] = date('Y-m-d',$secondMaintenance['next_date']);
			$secondMaintenance['add_datetime'] = date('Y-m-d',$secondMaintenance['add_datetime']);
		}
		/*echo '<pre>';
		 var_dump($drivingLicense);
		var_dump($transportCertificate);
		var_dump($insuranceCompulsory);
		var_dump($insuranceBusiness);
		var_dump($secondMaintenance);
		echo '</pre>';*/
		return $this->render('license',[
				'drivingLicense'=>$drivingLicense,
				'transportCertificate'=>$transportCertificate,
				'insuranceCompulsory'=>$insuranceCompulsory,
				'insuranceBusiness'=>$insuranceBusiness,
				'secondMaintenance'=>$secondMaintenance,
				]);
	}

	/**
	 * 显示车辆使用状态
	 */
	public function actionUseStatus(){
		$carId = yii::$app->request->get('car_id');
		if(!$carId){
			echo 'param car_id is required';
			return;
		}
		//获取当前车辆状态信息
		$carInfo = Car::find()
		->select(['car_status'])
		->where(['id'=>$carId,'is_del'=>0])->asArray()->one();
		if(!$carInfo){
			return;
		}
		switch ($carInfo['car_status']) {
			case 'NAKED':
			case 'STOCK':
				//裸车或库存
				$config = (new ConfigCategory)->getCategoryConfig(['car_status'],'value');
				$data = [];
				break;
			case 'REPAIRING':
			case 'FAULT':
				//已受理/已送修/维修中
				$config = (new ConfigCategory)->getCategoryConfig(['fault_status','car_status'],'value');
				$data = CarFault::find()
				->select([
						'{{%car_fault}}.`f_desc`',
						'{{%car_fault}}.`reg_datetime`',
						'{{%car_fault}}.`fault_status`',
						'{{%admin}}.`username`',
						])
						->joinWith('admin',false)
						->where(['car_id'=>$carId])
						->andWhere('{{%car_fault}}.`fault_status` in("RECEIVED","SENT","REPAIRING") and {{%car_fault}}.`is_del` = 0')
						->orderby('{{%car_fault}}.`id` desc')
						->asArray()->one();
				$data['fault_status'] = $config['fault_status'][$data['fault_status']]['text'];
				break;
			case 'LETING':
				//出租中
				$config = (new ConfigCategory)->getCategoryConfig(['car_status','customer_type'],'value');
				$data = CarLetRecord::find()
				->select([
						'{{%car_let_record}}.`month_rent`',
						'{{%car_let_record}}.`let_time`',
						'{{%car_let_record}}.`back_time`',
						'{{%car_let_record}}.`note`',
						'contract_number'=>'{{%car_let_contract}}.`number`',
						'{{%car_let_contract}}.`customer_type`',
						'cCustomer_name'=>'{{%customer_company}}.`company_name`',
						'pCustomer_name'=>'{{%customer_personal}}.`id_name`',
						])
						->where([
								'{{%car_let_record}}.`is_del`'=>0,
								'{{%car_let_record}}.`back_time`'=>0,
								'{{%car_let_record}}.`car_id`'=>$carId,
								])
								->joinWith('letContract',false)
								->joinWith('customerCompany',false,'LEFT JOIN')  //查企业客户
								->joinWith('customerPersonal',false,'LEFT JOIN') //查个人客户
								->asArray()->one();
				$data['let_time'] = date('Y-m-d H:i:s',$data['let_time']);
				$data['back_time'] = '';
				$data['customer_type'] = $config['customer_type'][$data['customer_type']]['text'];
				break;
			case 'INTRIAL':
				//试用中
				$config = (new ConfigCategory)->getCategoryConfig(['car_status'],'value');
				$data = CarTrialProtocolDetails::find()
				->select([
						'{{%car_trial_protocol_details}}.`ctpd_deliver_date`',
						'{{%car_trial_protocol_details}}.`ctpd_back_date`',
						'{{%car_trial_protocol_details}}.`ctpd_note`',
						'{{%car_trial_protocol}}.`ctp_number`',
						'{{%car_trial_protocol}}.`ctp_sign_date`',
						'{{%car_trial_protocol}}.`ctp_start_date`',
						'{{%car_trial_protocol}}.`ctp_end_date`',
						'cc_name'=>'{{%customer_company}}.`company_name`',
						])->joinWith('carTrialProtocol',false)
						->joinWith('customerCompany',false)
						->where('{{%car_trial_protocol_details}}.`ctpd_is_del` = 0 and {{%car_trial_protocol_details}}.`ctpd_back_date` IS NULL')
						->orderby('{{%car_trial_protocol_details}}.`ctpd_id` desc')
						->asArray()->one();
				break;
			default:
				return;
		}
		$data['car_status'] = $config['car_status'][$carInfo['car_status']]['text'];
		return $this->render('use-status',[
				'carStatus'=>$carInfo['car_status'],
				'data'=>json_encode($data),
				]);
	}

	/**
	 * 查看车辆监控数据
	 */
	public function actionMonitorInfo(){
		$carId = yii::$app->request->get('car_id');
		if(!$carId){
			echo 'param car_id is required';
			return;
		}
		//获取车辆监控数据
		$carInfo = Car::find()
		->select([
				'car_vin'=>'vehicle_dentification_number',
				'car_type'
				])->where(['id'=>$carId])->asArray()->one();
		if(!$carInfo){
			return '没找到车辆信息！';
		}
		$realtimeData = TcpCarRealtimeData::find()
		->where(['car_vin'=>$carInfo['car_vin']])
		->asArray()->one();
		if(!$realtimeData){
			return '无数据！';
		}
		if($realtimeData){
			$config = (new ConfigCategory)->getCategoryConfig(['car_type'],'value');
			$realtimeData['car_type'] = $config['car_type'][$carInfo['car_type']]['text'];
			$buttons = $this->getCurrentActionBtn();
		}else{
			$buttons = [];
		}
		return $this->render('monitor-info',[
				'buttons'=>$buttons,
				'realtimeData'=>$realtimeData,
				]);
	}

}