<?php
/**
 * 出险理赔控制器
 * time    2016/08/22 17:15
 * @author pengyl
 */
namespace backend\modules\car\controllers;
use backend\models\CarInsuranceClaim;

use backend\models\CarInsuranceOther;

use backend\models\CarDrivingLicense;

use backend\models\CarInsuranceCompulsory;

use backend\models\ConfigItem;

use backend\controllers\BaseController;
use backend\models\Car;
use backend\models\CustomerCompany;
use backend\models\CarInsuranceBusiness;
use backend\models\ConfigCategory;
use common\models\Excel;
use common\models\File;
use yii;
use yii\data\Pagination;
class InsuranceClaimController extends BaseController
{
    public function actionIndex()
    {
    	$carId = yii::$app->request->get('carId') or die('param carId is required');
    	$buttons = $this->getCurrentActionBtn();
    	$config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY'],'value');
    	$insurerCompany = [
    	['value'=>'','text'=>'不限']
    	];
    	if($config['INSURANCE_COMPANY']){
    		foreach($config['INSURANCE_COMPANY'] as $val){
    			$insurerCompany[] = ['value'=>$val['value'],'text'=>$val['text']];
    		}
    	}
    	return $this->render('index',[
    			'carId'=>$carId,
    			'buttons'=>$buttons,
    			'config'=>$config,
    			'insurerCompany'=>$insurerCompany,
    			]);
    }
    
    //报案出险
    public function actionAdd1(){
    	$id = yii::$app->request->post('id');
    	$car_num = yii::$app->request->post('car_num');
    	$danger_date = yii::$app->request->post('danger_date');
    	$people = yii::$app->request->post('people');
    	$tel = yii::$app->request->post('tel');
    	$province_id = yii::$app->request->post('province_id');
    	$city_id = yii::$app->request->post('city_id');
    	$area_id = yii::$app->request->post('area_id');
    	$area_detail = yii::$app->request->post('area-detail');
//     	$car_num = '粤BCG557';
//     	$area_detail = '广东省椅套';
    	
    	$connection = yii::$app->db;
    	$sql = 'select id from cs_car where is_del=0 and plate_number="'.$car_num.'"';
    	$data = $connection->createCommand($sql)->queryOne();
    	$car_id = $data['id'];
    	
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	
    	if(!$car_num || !$car_id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	
    	if($id){
    		$r = $connection->createCommand()->update('cs_car_insurance_claim', [
    				'car_id' => $car_id,
    				'province_id' => $province_id,
    				'city_id' => $city_id,
    				'area_id' => $area_id,
    				'danger_date' => $danger_date,
    				'people' => $people,
    				'tel' => $tel,
    				'area_detail' => $area_detail,
    				'oper_user1' => $_SESSION['backend']['adminInfo']['name'],
    				'last_update_time' => date('Y-m-d H:i:s'),
    				'last_update_user' => $_SESSION['backend']['adminInfo']['name']
    				],
    				'id=:id', 
    				array(':id'=>$id)
    				)->execute();
//     		print_r($r);
    		$returnArr['status'] = true;
    	}else{
    		//理赔编号，格式：LP+日期+3位数（即该故障是系统当天登记的第几个，第一个是001，第二个是002…）
            $sql = 'select count(*) count from cs_car_insurance_claim 
            	where add_time>="'.date('Y-m-d').' 00:00:00" and add_time<="'.date('Y-m-d').' 23:59:59"';
            $data = $connection->createCommand($sql)->queryOne();
            $todayCount = $data['count'];
            $currentNo = str_pad($todayCount+1,3,0,STR_PAD_LEFT);
            $number = 'LP' . date('Ymd') . $currentNo;  
    		$connection->createCommand()->insert('cs_car_insurance_claim', [
    				'car_id' => $car_id,
    				'province_id' => $province_id,
    				'city_id' => $city_id,
    				'area_id' => $area_id,
    				'danger_date' => $danger_date,
    				'people' => $people,
    				'tel' => $tel,
    				'area_detail' => $area_detail,
    				'number' => $number,
    				'add_time' => date('Y-m-d H:i:s'),
    				'oper_user1' => $_SESSION['backend']['adminInfo']['name'],
    				'last_update_time' => date('Y-m-d H:i:s'),
    				'last_update_user' => $_SESSION['backend']['adminInfo']['name']
    				])->execute();
    		$id = $connection->getLastInsertID();
    		if($id){
    			$returnArr['status'] = true;
    			$returnArr['id'] = $id;
    		}
    	}
    	$returnArr['status'] = true;
    	$returnArr['info'] = '';
    	echo json_encode($returnArr);
    }
    
    public function actionGet1(){
    	$id = yii::$app->request->get('id');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$connection = yii::$app->db;
    	$sql = 'select a.car_id,a.province_id,a.city_id,a.area_id,a.danger_date,a.people,a.tel,a.area_detail,a.oper_user1,b.plate_number car_num from cs_car_insurance_claim a left join cs_car b on a.car_id=b.id where a.id='.$id;
    	$data = $connection->createCommand($sql)->queryOne();
    	$returnArr['status'] = true;
    	$returnArr['data'] = $data;
    	echo json_encode($returnArr);
    }
    
    //查勘结论
    public function actionAdd2(){
    	$id = yii::$app->request->post('id');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$type_of_survey = yii::$app->request->post('type_of_survey');
    	$type_detail = yii::$app->request->post('type_detail');
    	
    	$responsibility_object = yii::$app->request->post('responsibility_object');	//责任对象
    	$plate_number = yii::$app->request->post('plate_number');	//车牌号
    	$full_name = yii::$app->request->post('full_name');			//姓名
    	$object_name = yii::$app->request->post('object_name');		//物体名称
    	$medical_treatment = yii::$app->request->post('medical_treatment');	//就诊类型
    	$disability_rating = yii::$app->request->post('disability_rating');	//伤残等级
    	$specific_gravity = yii::$app->request->post('specific_gravity');	//责任比重
    	$damage_condition = yii::$app->request->post('damage_condition');	//受损情况
    	
    	$responsibilitys = array();
    	foreach ($responsibility_object as $index=>$value){
    		array_push($responsibilitys,
    				array(
    						'responsibility_object'=>$responsibility_object[$index],
    						'plate_number'=>$plate_number[$index],
    						'full_name'=>$full_name[$index],
    						'object_name'=>$object_name[$index],
    						'medical_treatment'=>$medical_treatment[$index],
    						'disability_rating'=>$disability_rating[$index],
    						'specific_gravity'=>$specific_gravity[$index],
    						'damage_condition'=>$damage_condition[$index]
    					) 
    				);
    	}
    	$responsibility_text = json_encode($responsibilitys);	//责任text
    	$insurance_company = yii::$app->request->post('insurance_company');	//保险公司
    	$insurances = array();
    	foreach ($insurance_company as $index=>$value){
    		array_push($insurances,
    			array(
    					'insurance_company'=>$value,
    					'insurance'=>yii::$app->request->post('insurance'.($index+1))
    			) 
    		);
    		//yii::$app->request->post('insurance'.$index+1);	//险种
    	}
    	$insurance_text = json_encode($insurances);	//险种text
    	//上传出险资料
    	if(yii::$app->request->post('append_url')){
    		$append_urls_arr = yii::$app->request->post('append_url');
    	}else {
    		$append_urls_arr = array();
    	}
    	
    	for ($i=0;$i<10;$i++){
    		$_FILES['append'][$i] = @$_FILES['append'.($i+1)];
    	}
    	if(@$_FILES['append']){
    		$file_path="uploads/claim/";
    		if(!is_dir($file_path)){
    			mkdir($file_path);
    		}
    		$file_path .= date("Ymd").'/';
    		if(!is_dir($file_path)){
    			mkdir($file_path);
    		}
    		for($i=0;$i<count($_FILES['append']);$i++){
    			if(!@$_FILES['append'][$i]){
    				continue;
    			}
    			$_FILES['append'][$i]['name'] = date("YmdHis").'_'.$_FILES['append'][$i]['name']; //加个时间戳防止重复文件上传后被覆盖
    	
    			move_uploaded_file($_FILES['append'][$i]['tmp_name'],$file_path.iconv("UTF-8","gb2312", $_FILES['append'][$i]['name']));
    			if(@$append_urls_arr[$i]){	//替换原URL
    				$append_urls_arr[$i] = $file_path.$_FILES['append'][$i]['name'];
    			}else {
    				array_push($append_urls_arr, $file_path.$_FILES['append'][$i]['name']);
    			}
    		}
    	}
    	$append_urls = json_encode($append_urls_arr);
    	
    	$connection = yii::$app->db;
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	
//     	echo $responsibility_text;exit;
    	$r = $connection->createCommand()->update('cs_car_insurance_claim', [
    				'type_of_survey' => $type_of_survey,
    				'type_detail' => $type_detail,
    				'responsibility_text' => $responsibility_text,
    				'insurance_text' => $insurance_text,
    				'append1_urls' => $append_urls,
    				'oper_user2' => $_SESSION['backend']['adminInfo']['name'],
    				'last_update_time' => date('Y-m-d H:i:s'),
    				'last_update_user' => $_SESSION['backend']['adminInfo']['name']
    				],
    				'id=:id',
    				array(':id'=>$id)
    		)->execute();
    		//     		print_r($r);
    	$returnArr['status'] = true;
    	echo json_encode($returnArr);
    }
    
    public function actionGet2(){
    	$id = yii::$app->request->get('id');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$connection = yii::$app->db;
    	$sql = 'select a.type_of_survey,a.type_detail,a.responsibility_text,a.insurance_text,a.append1_urls,a.oper_user2 from cs_car_insurance_claim a where a.id='.$id;
    	$data = $connection->createCommand($sql)->queryOne();
    	$data['responsibility_text'] = json_decode($data['responsibility_text']);
    	$data['insurance_text'] = json_decode($data['insurance_text']);
    	$data['append1_urls'] = json_decode($data['append1_urls']);
    	
    	$returnArr['status'] = true;
    	$returnArr['data'] = $data;
    	echo json_encode($returnArr);
    }
    
    //保险定损 MARK
    public function actionAdd3(){
    	$id = yii::$app->request->post('id');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$damaged_money = yii::$app->request->post('damaged_money');	//定损金额
    	//$damaged_money2 = yii::$app->request->post('damaged_money2');	//肇事司机自付金额
    	$damaged_date = yii::$app->request->post('damaged_date');	//定损时间
    
    	
    	$damageds = array();
    	foreach ($damaged_money as $index=>$value){
    		array_push($damageds,
    				array(
    						'damaged_money'=>$damaged_money[$index],
    						//'damaged_money2'=>$damaged_money2[$index],
    						'damaged_date'=>$damaged_date[$index]
    				)
    		);
    	}
    	$damaged_text = json_encode($damageds);	//定损text
    	$connection = yii::$app->db;
    	$r = $connection->createCommand()->update('cs_car_insurance_claim', [
    			'damaged_text' => $damaged_text,
    			'oper_user3' => $_SESSION['backend']['adminInfo']['name'],
    			'last_update_time' => date('Y-m-d H:i:s'),
    			'last_update_user' => $_SESSION['backend']['adminInfo']['name']
    			],
    			'id=:id',
    			array(':id'=>$id)
    	)->execute();
    	$returnArr['status'] = true;
    	echo json_encode($returnArr);
    }
    //MARK
    public function actionGet3(){
    	$id = yii::$app->request->get('id');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$connection = yii::$app->db;
    	$sql = 'select responsibility_text,damaged_text,oper_user3 from cs_car_insurance_claim a where id='.$id;
    	$data = $connection->createCommand($sql)->queryOne();
    	$data['responsibility_text'] = json_decode($data['responsibility_text']);
    	$data['damaged_text'] = json_decode($data['damaged_text']);
    	
    	$returnArr['status'] = true;
    	$returnArr['data'] = $data;
    	echo json_encode($returnArr);
    }
    
    //车辆维修
    public function actionAdd4(){
    	$id = yii::$app->request->post('id');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$is_maintenance = yii::$app->request->post('is_maintenance');	//是否维修，1是
    	$maintenance_shop = yii::$app->request->post('maintenance_shop');	//维修厂
    	$maintenance_shop_details = yii::$app->request->post('maintenance_shop_details');	//其它维修厂
    	$maintenance_time = yii::$app->request->post('maintenance_time');	//维修时间
    	$contacts = yii::$app->request->post('contacts');	//联系人
    	$contact_number = yii::$app->request->post('contact_number');	//联系电话
    	$maintenance_condition = yii::$app->request->post('maintenance_condition');	//维修情况
    	$img_url = yii::$app->request->post('img_url');	//标的车图片URL
    	
    	//上传标的车图片
    	$_file = @$_FILES['img'];
    	if($_file){
    		$file_path="uploads/claim/";
    		if(!is_dir($file_path)){
    			mkdir($file_path);
    		}
    		$file_path .= date("Ymd").'/';
    		if(!is_dir($file_path)){
    			mkdir($file_path);
    		}
    		$file_name = date("YmdHis").'_'.$_file['name']; //加个时间戳防止重复文件上传后被覆盖
    		move_uploaded_file($_file['tmp_name'],$file_path.$file_name);
    		
    		$img_url = $file_path.$file_name;
    	}
    	//end
    	$maintenances = array();
    	foreach ($maintenance_shop as $index=>$value){
    		array_push($maintenances,
    				array(
    						'is_maintenance'=>$is_maintenance[$index],
    						'maintenance_shop'=>$maintenance_shop[$index],
    						'maintenance_shop_details'=>$maintenance_shop_details[$index],
    						'maintenance_time'=>$maintenance_time[$index],
    						'contacts'=>$contacts[$index],
    						'contact_number'=>$contact_number[$index],
    						'maintenance_condition'=>$maintenance_condition[$index],
    						'img_url'=>$index==0?$img_url:''
    				)
    		);
    	}
    	$maintenance_text = json_encode($maintenances);	//车辆维修text
    	$connection = yii::$app->db;
    	$r = $connection->createCommand()->update('cs_car_insurance_claim', [
    			'maintenance_text' => $maintenance_text,
    			'oper_user4' => $_SESSION['backend']['adminInfo']['name'],
    			'last_update_time' => date('Y-m-d H:i:s'),
    			'last_update_user' => $_SESSION['backend']['adminInfo']['name']
    			],
    			'id=:id',
    			array(':id'=>$id)
    	)->execute();
    	$returnArr['status'] = true;
    	echo json_encode($returnArr);
    }
    
    public function actionGet4(){
    	$id = yii::$app->request->get('id');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$connection = yii::$app->db;
    	$sql = 'select responsibility_text,maintenance_text,oper_user4 from cs_car_insurance_claim a where id='.$id;
    	$data = $connection->createCommand($sql)->queryOne();
    	$data['responsibility_text'] = json_decode($data['responsibility_text']);
    	$data['maintenance_text'] = json_decode($data['maintenance_text']);
    
    	$returnArr['status'] = true;
    	$returnArr['data'] = $data;
    	echo json_encode($returnArr);
    }
    
    //5.保险理赔
    public function actionAdd5(){
    	$id = yii::$app->request->post('id');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$claim_num = yii::$app->request->post('claim_num');	//理赔数
    	$claims = array();
        $claim_amounts = 0;
    	for($i=1; $i<=$claim_num; $i++){
    		$insurance_company = yii::$app->request->post('insurance_company'.$i);	//保险公司
    		$claim_type = yii::$app->request->post('claim_type'.$i);			//理赔类型
    		$claim_customer = yii::$app->request->post('claim_customer'.$i);	//理赔对象
    		$claim_account = yii::$app->request->post('claim_account'.$i);		//理赔帐号
    		$claim_time = yii::$app->request->post('claim_time'.$i);			//理赔时间
    		$claim_amount = yii::$app->request->post('claim_amount'.$i);		//理赔金额
    		$claim_sub = array();
    		foreach ($insurance_company as $index=>$value){
    			array_push($claim_sub,
    					array(
    							'insurance_company'=>$value,
    							'claim_type'=>$claim_type[$index],
    							'claim_customer'=>$claim_customer[$index],
    							'claim_account'=>$claim_account[$index],
    							'claim_time'=>$claim_time[$index],
    							'claim_amount'=>$claim_amount[$index]
    					)
    			);
                $claim_amounts+=$claim_amount[$index];
    		}
    		array_push($claims, $claim_sub);
    	}
    	$claim_text = json_encode($claims);	//理赔text
    	$connection = yii::$app->db;
    	$r = $connection->createCommand()->update('cs_car_insurance_claim', [
    			'claim_text' => $claim_text,
                'claim_amount' => $claim_amounts,
    			'oper_user5' => $_SESSION['backend']['adminInfo']['name'],
    			'last_update_time' => date('Y-m-d H:i:s'),
    			'last_update_user' => $_SESSION['backend']['adminInfo']['name']
    			],
    			'id=:id',
    			array(':id'=>$id)
    	)->execute();
    	$returnArr['status'] = true;
    	echo json_encode($returnArr);
    }
    
    public function actionGet5(){
    	$id = yii::$app->request->get('id');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$connection = yii::$app->db;
    	$sql = 'select responsibility_text,claim_text,oper_user5,damaged_text,insurance_text from cs_car_insurance_claim a where id='.$id;
    	$data = $connection->createCommand($sql)->queryOne();
    	$data['responsibility_text'] = json_decode($data['responsibility_text']);
    	$data['claim_text'] = json_decode($data['claim_text']);
        $data['damaged_text'] = json_decode($data['damaged_text']);
        $data['insurance_text'] = json_decode($data['insurance_text']);
    
    	$returnArr['status'] = true;
    	$returnArr['data'] = $data;
    	echo json_encode($returnArr);
    }
    
    //6.保险请款MARK
    public function actionAdd6(){
    	$id = yii::$app->request->post('id');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$customer_name = yii::$app->request->post('customer_name');	//客户名称
    	$customer_name_details = yii::$app->request->post('customer_name_details');	//客户详情
    	$bank_account = yii::$app->request->post('bank_account');	//开户银行
    	$account_name = yii::$app->request->post('account_name');	//账户名
    	$account_opening = yii::$app->request->post('account_opening');	//开户帐号
    	$transfer_amount = yii::$app->request->post('transfer_amount');	//转账金额
		
		$damaged_money2 = yii::$app->request->post('damaged_money2');	//肇事司机自付金额
    	$rent_amount = yii::$app->request->post('rent_amount');	//抵租金额
    	
		$please_use = yii::$app->request->post('please_use');	//请款用途
	
    	
    	$pays = array();
    	foreach ($customer_name as $index=>$value){
    		array_push($pays,
    					array(
    							'customer_name'=>$customer_name[$index],
    							'customer_name_details'=>$customer_name_details[$index],
    							'bank_account'=>$bank_account[$index],
    							'account_name'=>$account_name[$index],
    							'account_opening'=>$account_opening[$index],
    							'transfer_amount'=>$transfer_amount[$index],
    							//'damaged_money2'=>$damaged_money2[$index],
    							//'rent_amount'=>$rent_amount[$index],
    							'please_use'=>$please_use[$index]
    					)
    			);
    	}
		
		//查出定损text
		$connection = yii::$app->db;
    	//$sql = 'select damaged_text from cs_car_insurance_claim a where id='.$id;
    	//$data = $connection->createCommand($sql)->queryOne();    	
    	//$damageds = json_decode($data['damaged_text']);
    			
		//将提交过来的数组并入json
		// foreach ($damageds as $index=>$value){
			// $damageds[$index]['damaged_money2'] = $damaged_money2[$index];    		
    	// }		
		//$damaged_text = json_encode($damageds);	//定损text
		
    	$pay_text = json_encode($pays);	//保险请款text
    	//$connection = yii::$app->db;
    	$r = $connection->createCommand()->update('cs_car_insurance_claim', [
    			'pay_text' => $pay_text,
				//'damaged_text' => $damaged_text,
				'damaged_money2' => $damaged_money2,
				'rent_amount' => $rent_amount,
    			'oper_user6' => $_SESSION['backend']['adminInfo']['name'],
    			'last_update_time' => date('Y-m-d H:i:s'),
    			'last_update_user' => $_SESSION['backend']['adminInfo']['name']
    			],
    			'id=:id',
    			array(':id'=>$id)
    	)->execute();
    	$returnArr['status'] = true;
    	echo json_encode($returnArr);
    }
    
    public function actionGet6(){
    	$id = yii::$app->request->get('id');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$connection = yii::$app->db;
    	$sql = 'select damaged_money2,rent_amount,claim_text,pay_text,oper_user6 from cs_car_insurance_claim a where id='.$id;
    	$data = $connection->createCommand($sql)->queryOne();
    	$data['pay_text'] = json_decode($data['pay_text']);
    	//$data['damaged_text'] = json_decode($data['damaged_text']);
    	$claims = json_decode($data['claim_text']);
    	$claim_amount = 0;
    	foreach ($claims as $row){
    		foreach ($row as $claim){
    			//if($claim->claim_type == '地上铁'){
    				$claim_amount += $claim->claim_amount;
    			//}
    		} 
    	}
		//var_dump($claim_amount);exit;
    	unset($data['claim_text']);
    	$data['claim_amount'] = $claim_amount;
    	$returnArr['status'] = true;
    	$returnArr['data'] = $data;
    	echo json_encode($returnArr);
    }
    
    //7.转账结案
    public function actionAdd7(){
    	$id = yii::$app->request->post('id');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$transfer_time = yii::$app->request->post('transfer_time');	//转账时间
    	//上传转账凭证
    	if(yii::$app->request->post('append_url')){
    		$append_urls_arr = yii::$app->request->post('append_url');
    	}else {
    		$append_urls_arr = Array();
    	}
    	for ($i=0;$i<10;$i++){
    		$_FILES['append'][$i] = @$_FILES['append'.($i+1)];
    	}
    	if(@$_FILES['append']){
    		$file_path="uploads/claim/";
    		if(!is_dir($file_path)){
    			mkdir($file_path);
    		}
    		$file_path .= date("Ymd").'/';
    		if(!is_dir($file_path)){
    			mkdir($file_path);
    		}
    		for($i=0;$i<count($_FILES['append']);$i++){
    			if(!@$_FILES['append'][$i]){
    				continue;
    			}
    			$_FILES['append'][$i]['name'] = date("YmdHis").'_'.$_FILES['append'][$i]['name']; //加个时间戳防止重复文件上传后被覆盖
    			
    			move_uploaded_file($_FILES['append'][$i]['tmp_name'],$file_path.iconv("UTF-8","gb2312", $_FILES['append'][$i]['name']));
    			if(@$append_urls_arr[$i]){	//替换原URL
    				$append_urls_arr[$i] = $file_path.$_FILES['append'][$i]['name'];
    			}else {
    				array_push($append_urls_arr, $file_path.$_FILES['append'][$i]['name']);
    			}
    		}
    	}
    	
    	$transfers = array();
    	foreach ($transfer_time as $index=>$value){
    		array_push($transfers,
    				array(
    						'transfer_time'=>$transfer_time[$index],
    						'append_url'=>@$append_urls_arr[$index]
    				)
    		);
    	}
    	$transfer_text = json_encode($transfers);	//转账结案text
    	$connection = yii::$app->db;
    	$r = $connection->createCommand()->update('cs_car_insurance_claim', [
    			'transfer_text' => $transfer_text,
    			'oper_user7' => $_SESSION['backend']['adminInfo']['name'],
    			'last_update_time' => date('Y-m-d H:i:s'),
    			'last_update_user' => $_SESSION['backend']['adminInfo']['name']
    			],
    			'id=:id',
    			array(':id'=>$id)
    	)->execute();
    	$returnArr['status'] = true;
    	echo json_encode($returnArr);
    }
    
    public function actionGet7(){
    	$id = yii::$app->request->get('id');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$connection = yii::$app->db;
    	$sql = 'select pay_text,transfer_text,oper_user7 from cs_car_insurance_claim a where id='.$id;
    	$data = $connection->createCommand($sql)->queryOne();
    	$data['transfer_text'] = json_decode($data['transfer_text']);
    	$data['pay_text'] = json_decode($data['pay_text']);
    
    	$returnArr['status'] = true;
    	$returnArr['data'] = $data;
    	echo json_encode($returnArr);
    }
    
    //获取客户列表
    public function actionGetCustomers()
    {
    	$queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
    	$connection = yii::$app->db;
    	$data = $connection->createCommand(
    			"select company_name from cs_customer_company 
    			where is_del=0 and company_name like :company_name 
    			group by company_name limit 10"
    	)->bindValues([':company_name'=>'%'.$queryStr.'%'])
    	->queryAll();
    	echo json_encode($data);
    }
    
    //获取维修厂商
    public function actionGetMaintenanceShop(){
    	$connection = yii::$app->db;
    	$sql = 'select site_name from oa_service_site';
    	$data = $connection->createCommand($sql)->queryAll();
    	echo json_encode($data);
    }
	//2016/1/13 根据出险单id获取出险记录
    public function actionGetClaimById(){
    	$connection = yii::$app->db;
		    	$id = yii::$app->request->get('id');
		//var_dump($id);exit;
		if (!isset($id) || $id == "") {
			exit;			
		}
    	$sql = 'SELECT cs_car_insurance_claim.id
					   ,cs_car_insurance_claim.number
					   ,cs_car.plate_number					   
					   ,cs_car_let_record.cCustomer_id					   
					   ,cs_car_let_record.pCustomer_id					   
					   ,cs_car_insurance_claim.danger_date
					   
					   ,cs_car_let_record.cCustomer_id
					   ,cs_car_let_record.pCustomer_id
					   ,cs_customer_company.company_name
					   ,cs_customer_personal.id_name
				FROM cs_car_insurance_claim				
				LEFT JOIN cs_car ON cs_car.id=cs_car_insurance_claim.car_id
				LEFT JOIN cs_car_let_record ON cs_car_let_record.car_id=cs_car.id AND 
				(
							(cs_car_let_record.`back_time` >= UNIX_TIMESTAMP(cs_car_insurance_claim.danger_date) and cs_car_let_record.`let_time` <= UNIX_TIMESTAMP(cs_car_insurance_claim.danger_date))
						 	or 
							(UNIX_TIMESTAMP(cs_car_insurance_claim.danger_date) >= cs_car_let_record.`let_time` and cs_car_let_record.`back_time` = 0)
				)
				LEFT JOIN cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id
        		LEFT JOIN cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
				WHERE cs_car_insurance_claim.id='.$id;
    
		
		
		$data = $connection->createCommand($sql)->queryAll();
		
		foreach ($data as $k => $v) {
			if (isset($v['cCustomer_id']) && $v['cCustomer_id'] != 0) {
				$data[$k]['customer'] = $v['company_name'];
			}
			if (isset($v['pCustomer_id']) && $v['pCustomer_id'] != 0 ) {
				$data[$k]['customer'] = $v['id_name'];
			}			
		}
		// var_dump($data);exit;
    	echo json_encode($data);
    }
	//2016/12/28 根据车牌号获取出险记录
    public function actionGetClaimByCarno(){
    	$connection = yii::$app->db;
		    	$id = yii::$app->request->get('id');
		//var_dump($id);exit;
		if (!isset($id) || $id == "") {
			exit;			
		}
    	$sql = 'SELECT cs_car_insurance_claim.id
					   ,cs_car_insurance_claim.number
					   ,cs_car.plate_number					   
					   ,cs_car_let_record.cCustomer_id					   
					   ,cs_car_let_record.pCustomer_id					   
					   ,cs_car_insurance_claim.danger_date
					   
					   ,cs_car_let_record.cCustomer_id
					   ,cs_car_let_record.pCustomer_id
					   ,cs_customer_company.company_name
					   ,cs_customer_personal.id_name
				FROM cs_car_insurance_claim				
				LEFT JOIN cs_car ON cs_car.id=cs_car_insurance_claim.car_id
				LEFT JOIN cs_car_let_record ON cs_car_let_record.car_id=cs_car.id AND 
				(
							(cs_car_let_record.`back_time` >= UNIX_TIMESTAMP(cs_car_insurance_claim.danger_date) and cs_car_let_record.`let_time` <= UNIX_TIMESTAMP(cs_car_insurance_claim.danger_date))
						 	or 
							(UNIX_TIMESTAMP(cs_car_insurance_claim.danger_date) >= cs_car_let_record.`let_time` and cs_car_let_record.`back_time` = 0)
				)
				LEFT JOIN cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id
        		LEFT JOIN cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
				WHERE cs_car_insurance_claim.car_id='.$id;
    
		
		
		$data = $connection->createCommand($sql)->queryAll();
		
		foreach ($data as $k => $v) {
			if (isset($v['cCustomer_id']) && $v['cCustomer_id'] != 0) {
				$data[$k]['customer'] = $v['company_name'];
			}
			if (isset($v['pCustomer_id']) && $v['pCustomer_id'] != 0 ) {
				$data[$k]['customer'] = $v['id_name'];
			}			
		}
		// var_dump($data);exit;
    	echo json_encode($data);
    }
    
    //获取保险公司
    public function actionInsuranceCompany(){
    	$id = yii::$app->request->get('id');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$connection = yii::$app->db;
    	$sql = 'select car_id,danger_date from cs_car_insurance_claim where id='.$id;
        $insurance_claim = $connection->createCommand($sql)->queryOne();
    	$carId = $insurance_claim['car_id'];
        $danger_date = strtotime($insurance_claim['danger_date']);
    	
    	$config['INSURANCE_COMPANY'] = array();
    	//获取当前购买的保险公司
    	$insurance_company_arr = $connection->createCommand(
    			"(select 'compulsory',insurer_company,id insurance_text from cs_car_insurance_compulsory where car_id = :car_id and :date >= start_date and :date <= end_date limit 1)
    			union ALL
    			(select 'business',insurer_company,insurance_text from cs_car_insurance_business where car_id = :car_id and :date >= start_date and :date <= end_date limit 1)
    			union all
    			(select 'other',insurer_company,insurance_text from cs_car_insurance_other where car_id = :car_id and :date >= start_date and :date <= end_date limit 1)"
    	)->bindValues([':car_id'=>$carId,':date'=>$danger_date])
    	->queryAll();
    	$insurance_company_str='';
    	$insurance_text_arr = array();	//当前购买的保险公司对应险种
    	foreach ($insurance_company_arr as $row){
    		$insurance_company_str .= "'{$row['insurer_company']}',";
    		if(!@$insurance_text_arr[$row['insurer_company']]){
    			$insurance_text_arr[$row['insurer_company']] = array();
    		}
    		if(is_numeric($row['insurance_text'])){	//交强险
    			array_push($insurance_text_arr[$row['insurer_company']], $row['insurance_text']);
    		}else {
    			array_push($insurance_text_arr[$row['insurer_company']], json_decode($row['insurance_text']));
    		}
    	}
    	if($insurance_company_str){
    		$insurance_company_str = substr($insurance_company_str, 0, strlen($insurance_company_str)-1);
    		$config['INSURANCE_COMPANY'] = $connection->createCommand("select value,text from cs_config_item where belongs_id=41 and `value` in ({$insurance_company_str})")
    		->queryAll();
    	}
    	
    	foreach ($config['INSURANCE_COMPANY'] as $index=>$row){
    		$config['INSURANCE_COMPANY'][$index]['insurance'] = $insurance_text_arr[$row['value']];
    	}
//     	print_r($config['INSURANCE_COMPANY']);
//     	print_r($insurance_text_arr);
//     	exit;
    	echo json_encode(array(
    			'company'=>$config['INSURANCE_COMPANY'],
//     			'insurance'=>$insurance_text_arr,
    			));
    	
    }
    
    //获取车辆
    public function actionGetCars()
    {
    	$queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
    	$query = Car::find()
    	->select(['id','plate_number','vehicle_dentification_number'])
    	->where(['is_del'=>0]);
    	
    	// 检索过滤时
    	$total = $query->andWhere([
    				'or',
    				['like', 'plate_number', $queryStr],
    				['like', 'vehicle_dentification_number', $queryStr]
    				])
    				->count();
    	
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    	$pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
    	$data = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
    	$returnArr = [];
    	$returnArr['rows'] = $data;
    	$returnArr['total'] = $total;
    	return json_encode($returnArr);
    }
    //获取省市地区
    public function actionGetRegion(){
    	$region_id = yii::$app->request->get('region_id');
    	
    	$connection = yii::$app->db;
    	if($region_id){
    		$sql = 'select * from zc_region where parent_id='.$region_id;
    	}else {
    		$sql = 'select * from zc_region where parent_id=1';
    	}
    	$data = $connection->createCommand($sql)->queryAll();
    	echo json_encode($data);
    }
    
    function unicode_encode($name){
    	$name = iconv('UTF-8', 'UCS-2', $name);
    	$len = strlen($name);
    	$str = '';
    	//for ($i = 0; $i < $len – 1; $i = $i + 2){
    	for($i=0;$i<$len-1;$i=$i+2){
    		$c = $name[$i];
    		$c2 = $name[$i + 1];
    		if (ord($c) > 0){    // 两个字节的文字
    			$str .= 'u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
    		}else{
    			$str .= $c2;
    		}
    	}
    	return $str;
    }
    
    /**
     * 导出出险理赔列表
     */
    public function actionExportWidthCondition()
    {
    	$carId = yii::$app->request->get('carId') or die('param carId is required');
    $carId = yii::$app->request->get('carId') or die('param carId is required');
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    	$query = CarInsuranceClaim::find()
    	->select([
    			'{{%car_insurance_claim}}.*,
    			{{%car_insurance_claim}}.insurance_text _insurance_text,
    			{{%car_insurance_claim}}.claim_text _claim_text'
    			])
    	->andWhere(['=','{{%car_insurance_claim}}.`is_del`',0])
    	->andWhere(['=','{{%car_insurance_claim}}.`car_id`',$carId]);
    	//查询条件
    	$insurer_type = yii::$app->request->get('insurer_type');
    	if($insurer_type){
    		$query->andFilterWhere(['like','{{%car_insurance_claim}}.`insurance_text`',json_encode(yii::$app->request->get('insurer_type'))]);
    	}    	
    	if(yii::$app->request->get('claim_time')){
    		$query->andFilterWhere(['like','{{%car_insurance_claim}}.`claim_text`',json_encode(yii::$app->request->get('claim_time'))]);
    	}
    	if(yii::$app->request->get('transfer_time')){
    		$query->andFilterWhere(['like','{{%car_insurance_claim}}.`transfer_text`',json_encode(yii::$app->request->get('transfer_time'))]);
    	}
    	$query->andFilterWhere(['like','{{%car_insurance_claim}}.`insurance_text`',yii::$app->request->get('insurer_company')]);
    	$query->andFilterWhere(['like','{{%car_insurance_claim}}.`people`',yii::$app->request->get('people')]);
    	$query->andFilterWhere(['like','{{%car_insurance_claim}}.`tel`',yii::$app->request->get('tel')]);
    	if(yii::$app->request->get('start_danger_date')){
    		$query->andFilterWhere(['>=','{{%car_insurance_claim}}.`danger_date`',strtotime(yii::$app->request->get('start_danger_date'))]);
    	}
    	if(yii::$app->request->get('end_danger_date')){
    		$query->andFilterWhere(['<=','{{%car_insurance_claim}}.`danger_date`',strtotime(yii::$app->request->get('end_danger_date'))]);
    	}
    	$status = yii::$app->request->get('status');
    	if($status){
    		if($status==7){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user7`','']);
    		}else if($status==6){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user6`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user7', '']);
    		}else if($status==5){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user5`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user6', '']);
    		}else if($status==4){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user4`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user5', '']);
    		}else if($status==3){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user3`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user4', '']);
    		}else if($status==2){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user2`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user3', '']);
    		}else if($status==1){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user1`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user2', '']);
    		}
    	}
    	$data = $query->asArray()->all();
    	$filename = '出险理赔列表.csv'; //设置文件名
    	$str = "出现日期,报案人,报案电话\n";
    	foreach ($data as $row){
    		
    		$str .= "{$row['danger_date']},{$row['people']},{$row['tel']}"."\n";
    	}
    	$str = mb_convert_encoding($str, "GBK", "UTF-8");
    	$this->export_csv($filename,$str); //导出
    }
    
    function export_csv($filename,$data)
    {
    	//		header("Content-type: text/html; charset=utf-8");
    	header("Content-type:text/csv;charset=GBK");
    	header("Content-Disposition:attachment;filename=".$filename);
    	header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    	header('Expires:0');
    	header('Pragma:public');
    	echo $data;
    }
    
    /**
     * 获取出险理赔列表
     */
    public function actionGetList()
    {
    	$carId = yii::$app->request->get('carId') or die('param carId is required');
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    	$query = CarInsuranceClaim::find()
    	->select([
    			'{{%car_insurance_claim}}.*,
    			{{%car_insurance_claim}}.insurance_text _insurance_text,
    			{{%car_insurance_claim}}.claim_text _claim_text'
    			])
    	->andWhere(['=','{{%car_insurance_claim}}.`is_del`',0])
    	->andWhere(['=','{{%car_insurance_claim}}.`car_id`',$carId]);
    	//查询条件
    	$insurer_type = yii::$app->request->get('insurer_type');
    	if($insurer_type){
    		$query->andFilterWhere(['like','{{%car_insurance_claim}}.`insurance_text`',json_encode(yii::$app->request->get('insurer_type'))]);
    	}    	
    	if(yii::$app->request->get('claim_time')){
    		$query->andFilterWhere(['like','{{%car_insurance_claim}}.`claim_text`',json_encode(yii::$app->request->get('claim_time'))]);
    	}
    	if(yii::$app->request->get('transfer_time')){
    		$query->andFilterWhere(['like','{{%car_insurance_claim}}.`transfer_text`',json_encode(yii::$app->request->get('transfer_time'))]);
    	}
    	$query->andFilterWhere(['like','{{%car_insurance_claim}}.`insurance_text`',yii::$app->request->get('insurer_company')]);
    	$query->andFilterWhere(['like','{{%car_insurance_claim}}.`people`',yii::$app->request->get('people')]);
    	$query->andFilterWhere(['like','{{%car_insurance_claim}}.`tel`',yii::$app->request->get('tel')]);
    	if(yii::$app->request->get('start_danger_date')){
    		$query->andFilterWhere(['>=','{{%car_insurance_claim}}.`danger_date`',strtotime(yii::$app->request->get('start_danger_date'))]);
    	}
    	if(yii::$app->request->get('end_danger_date')){
    		$query->andFilterWhere(['<=','{{%car_insurance_claim}}.`danger_date`',strtotime(yii::$app->request->get('end_danger_date'))]);
    	}
    	$status = yii::$app->request->get('status');
    	if($status){
    		if($status==7){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user7`','']);
    		}else if($status==6){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user6`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user7', '']);
    		}else if($status==5){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user5`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user6', '']);
    		}else if($status==4){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user4`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user5', '']);
    		}else if($status==3){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user3`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user4', '']);
    		}else if($status==2){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user2`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user3', '']);
    		}else if($status==1){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user1`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user2', '']);
    		}
    	}
//     	echo $query->createCommand()->getRawSql();exit;
    	//查询条件结束
    	//排序开始
    	$sortColumn = yii::$app->request->get('sort');
    	$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
    	$orderBy = '';
    	if($sortColumn){
    		switch ($sortColumn) {
    			case 'username':
    				$orderBy = '{{%admin}}.`'.$sortColumn.'` ';
    				break;
    			default:
    				$orderBy = '{{%car_insurance_claim}}.`'.$sortColumn.'` ';
    			break;
    		}
    	}else{
    		$orderBy = '{{%car_insurance_claim}}.`id` ';
    	}
    	$orderBy .= $sortType;
    	//排序结束
    	$total = $query->count();
    	$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
    	$data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
    	foreach ($data as $index=>$row){
    		if($row['oper_user7']){
        		$data[$index]['status'] = '7.已结案';
				$data[$index]['step'] = 8;
        	}else if($row['oper_user6']){
        		$data[$index]['status'] = '6.已请款，等待结案';
				$data[$index]['step'] = 7;
        	}else if($row['oper_user5']){
        		$data[$index]['status'] = '5.已理赔，保险请款';
				$data[$index]['step'] = 6;
        	}else if($row['oper_user4']){
        		$data[$index]['status'] = '4.维修中，等待理赔';
				$data[$index]['step'] = 5;
        	}else if($row['oper_user3']){
        		$data[$index]['status'] = '3.已定损，维修中';
				$data[$index]['step'] = 4;
        	}else if($row['oper_user2']){
        		$data[$index]['status'] = '2.已查勘，等待定损';
				$data[$index]['step'] = 3;
        	}else if($row['oper_user1']){
        		$data[$index]['status'] = '1.已报案，等待查勘';
				$data[$index]['step'] = 2;
        	}else {
        		$data[$index]['status'] = '';
				$data[$index]['step'] = 1;
        	}
    	}
    	$returnArr = [];
    	$returnArr['rows'] = $data;
    	$returnArr['total'] = $total;
    	echo json_encode($returnArr);
    }
    
	//查看详情
    public function actionScan(){
    	$id = yii::$app->request->get('id') or die('param id is required');
    	
    	$connection = yii::$app->db;
    	$claim = $connection->createCommand('select car_id from cs_car_insurance_claim where id='.$id)->queryOne();
    	$car_id = $claim['car_id'];
    	$sql = 'select id,plate_number,brand_id,car_model,car_status from cs_car where id='.$car_id;
    	$data = $connection->createCommand($sql)->queryOne();
    	//加载品牌
    	$brand = $connection->createCommand('select name from cs_car_brand where id='.$data['brand_id'])->queryOne();
    	$data['brand_name'] = $brand['name'];
    	//加载车型
    	$car_model = $connection->createCommand('select text from cs_config_item where value="'.$data['car_model'].'"')->queryOne();
    	$data['car_model_name'] = $car_model['text'];
    	//加载归属客户
    	$configItems = ['car_status','INSURANCE_COMPANY'];
    	$config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
    	$query = $connection->createCommand(
    			"select cCustomer_id,pCustomer_id,cs_customer_company.company_name,cs_customer_personal.id_name
    			from cs_car_let_record
    			left join cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id
    			left join cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
    			where (back_time>".time()." or back_time=0) and car_id=".$data['id']
    	);
    	$customer = $query->queryOne();
    	if($customer){
    		if($customer['company_name']){
    			$data['customer_name'] = $customer['company_name'];
    		}else if($customer['id_name']){
    			$data['customer_name'] = $customer['id_name'];
    		}
    	}else {
    		$data['customer_name'] = $config['car_status'][$data['car_status']]['text'];
    	}
    	//加载保险信息
    	$insurance_compulsory = $connection->createCommand(
    			'select id,money_amount,insurer_company,start_date,end_date,note 
    			from cs_car_insurance_compulsory 
    			where car_id='.$data['id'].' order by end_date desc limit 1'
    			)->queryOne();
    	$data['insurance_compulsory'] = $insurance_compulsory;
    	$data['insurance_compulsory']['insurer_company_name'] = $config['INSURANCE_COMPANY'][$insurance_compulsory['insurer_company']]['text'];
    	$insurance_business = $connection->createCommand(
    			'select id,money_amount,insurer_company,start_date,end_date,note,insurance_text 
    			from cs_car_insurance_business 
    			where car_id='.$data['id'].' order by end_date desc limit 1'
    		)->queryOne();
    	$data['insurance_business'] = $insurance_business;
    	$data['insurance_business']['insurer_company_name'] = $config['INSURANCE_COMPANY'][$insurance_business['insurer_company']]['text'];
    	//加载出险信息
    	$insurance_claim = $connection->createCommand(
    			'select *
    			from cs_car_insurance_claim
    			where car_id='.$data['id'].' order by danger_date desc limit 1'
    	)->queryOne();
    	$claim_car = $connection->createCommand('select plate_number from cs_car where id='.$insurance_claim['car_id'])->queryOne();
    	$insurance_claim['claim_car'] = $claim_car['plate_number'];
    	$data['insurance_claim'] = $insurance_claim;
    	$insurance_claim_state = '';//出险状态
    	if($insurance_claim['oper_user7']){
    		$insurance_claim_state = '转账结案';
    	}else if($insurance_claim['oper_user6']){
    		$insurance_claim_state = '保险请款';
    	}else if($insurance_claim['oper_user5']){
    		$insurance_claim_state = '保险理赔';
    	}else if($insurance_claim['oper_user4']){
    		$insurance_claim_state = '车辆维修';
    	}else if($insurance_claim['oper_user3']){
    		$insurance_claim_state = '保险定损';
    	}else if($insurance_claim['oper_user2']){
    		$insurance_claim_state = '查勘结论';
    	}else if($insurance_claim['oper_user1']){
    		$insurance_claim_state = '报案出险';
    	}
    	$data['insurance_claim_state'] = $insurance_claim_state;
    	//加载出险时归属客户
    	$danger_time = strtotime($insurance_claim['danger_date']);
    	$query = $connection->createCommand(
    			"select cCustomer_id,pCustomer_id,cs_customer_company.company_name,cs_customer_personal.id_name
    			from cs_car_let_record
    			left join cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id
    			left join cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
    			where (({$danger_time}>=let_time and {$danger_time}<=back_time) or ({$danger_time}>=let_time and back_time=0)) and car_id=".$data['id']
    	);
    	$customer = $query->queryOne();
    	if($customer){
    		if($customer['company_name']){
    			$data['claim_customer_name'] = $customer['company_name'];
    		}else if($customer['id_name']){
    			$data['claim_customer_name'] = $customer['id_name'];
    		}
    	}else {
    		$data['claim_customer_name'] = '无';
    	}
    	return $this->render('scan',[
    			'obj'=>$data
    			]);
    }
    
    /**
     * 删除出险理赔
     */
    public function actionTciRemove()
    {
    	$id = yii::$app->request->get('id') or die('param id is required');
    	$model = CarInsuranceCompulsory::findOne(['id'=>$id]);
    	$model or die('record not found');
    	//检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
    	$checkArr = Car::checkOperatingCompanyIsMatch($model->car_id);
    	if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
    		return json_encode(['status'=>false,'info'=>$checkArr['info']]);
    	}
    	$returnArr = [];
    	$returnArr['status'] = true;
    	$returnArr['info'] = '';
    	if(CarInsuranceCompulsory::updateAll(['is_del'=>1],['id'=>$id])){
    		$returnArr['status'] = true;
    		$returnArr['info'] = '车辆强制保险记录删除成功！';
    	}else{
    		$returnArr['status'] = false;
    		$returnArr['info'] = '车辆强制保险记录删除失败！';
    	}
    	echo json_encode($returnArr);
    }
}