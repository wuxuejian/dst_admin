<?php
/**
 * 
 * @author 
 */
namespace backend\modules\car\controllers;
use backend\controllers\BaseController;
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
use backend\models\CarType;
class CarTypeLeaseController extends BaseController
{
	public function actionIndex()
	{	
		
		$buttons = $this->getCurrentActionBtn();
		//获取配置数据
        $configItems = [
						'car_model_name',
						//'cainiao_status',
						//'time_status',
						//'long_lease_status',
		
		
		
		'car_status','gain_year','gain_way','car_type','use_nature','car_color','car_status2'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
        //查询表单select选项
        $searchFormOptions = [];
		
		//if($config['cainiao_status'])
       // {
        	$searchFormOptions['cainiao_status'] = [];
        	$searchFormOptions['cainiao_status'][] = ['value'=>'','text'=>'不限'];
        	$searchFormOptions['cainiao_status'][] = ['value'=>'0','text'=>'未开启'];
        	$searchFormOptions['cainiao_status'][] = ['value'=>'1','text'=>'开启'];
        	//foreach($config['cainiao_status'] as $val){
        	//	$searchFormOptions['cainiao_status'][] = ['value'=>$val['value'],'text'=>$val['text']];
        	//}
       // }
		//if($config['time_status'])
       // {
        	$searchFormOptions['time_status'] = [];
        	$searchFormOptions['time_status'][] = ['value'=>'','text'=>'不限'];
        	$searchFormOptions['time_status'][] = ['value'=>'0','text'=>'未开启'];
        	$searchFormOptions['time_status'][] = ['value'=>'1','text'=>'开启'];
        	//foreach($config['time_status'] as $val){
        	//	$searchFormOptions['time_status'][] = ['value'=>$val['value'],'text'=>$val['text']];
        	//}
       // }
		//if($config['long_lease_status'])
       // {
        	$searchFormOptions['long_lease_status'] = [];
        	$searchFormOptions['long_lease_status'][] = ['value'=>'','text'=>'不限'];
        	$searchFormOptions['long_lease_status'][] = ['value'=>'0','text'=>'未开启'];
        	$searchFormOptions['long_lease_status'][] = ['value'=>'1','text'=>'开启'];
        	//foreach($config['long_lease_status'] as $val){
        	//	$searchFormOptions['long_lease_status'][] = ['value'=>$val['value'],'text'=>$val['text']];
        	//}
       // }
				
		 //查询所有的车型
        $arr2 = [];
        $cartype_a = CarType::find()->select(['id','car_model'])->andWhere(['`is_del`'=>0])->asArray()->all();
        foreach($cartype_a as $key2 =>$val2) {  
            $obje = [];
            $obje['text'] = $config['car_model_name'][$val2['car_model']]['text'];
            $obje['value'] = $val2['id'];
            $searchFormOptions['car_type_id'][] = $obje;
        }
		
		 //车辆运营公司
        $oc = (new \yii\db\Query())->from('cs_operating_company')->where('is_del=0')->all();
        if($oc)
        {
        	$searchFormOptions['operating_company_id'] = [];
        	$searchFormOptions['operating_company_id'][] = ['value'=>'','text'=>'不限'];
        	
        	$adminInfo_operatingCompanyIds = @$_SESSION['backend']['adminInfo']['operating_company_ids'];
        	$super = $_SESSION['backend']['adminInfo']['super'];
        	$username = $_SESSION['backend']['adminInfo']['username'];
        	//除了开发人员和超级管理员不受管控外，其他人必须检测
			if($super || $username == 'administrator'){
				foreach($oc as $val){
					$searchFormOptions['operating_company_id'][] = ['value'=>$val['id'],'text'=>$val['name']];
        		}
			}else if($adminInfo_operatingCompanyIds){
        		$adminInfo_operatingCompanyIds = explode(',',$adminInfo_operatingCompanyIds);
        		foreach($oc as $val){
        			if(in_array($val['id'],$adminInfo_operatingCompanyIds)){
        				$searchFormOptions['operating_company_id'][] = ['value'=>$val['id'],'text'=>$val['name']];
        			}
        		}
        	}
        }
		
		
		//城市
		$connection = yii::$app->db;
		$citys_list = $connection->createCommand(
				"select * from zc_region where region_type=2"
		)->queryAll();
        if($citys_list)
        {
        	$searchFormOptions['citys_list'] = [];
        	$searchFormOptions['citys_list'][] = ['value'=>'','text'=>'不限'];
        	foreach($citys_list as $val){
        		$searchFormOptions['citys_list'][] = ['value'=>$val['region_id'],'text'=>$val['region_name']];
        	}
        }
		
		return $this->render('index',[
            'buttons'=>$buttons,
            'config'=>$config,
            'searchFormOptions'=>$searchFormOptions,
        ]);
	}

	//车型租赁信息添加
	public function actionAdd()
	{
	
		date_default_timezone_set('PRC');
		$connection = yii::$app->db;
		$add_time = strtotime(date('Y-m-d H:i:s'));	
		$add_aid  = $_SESSION['backend']['adminInfo']['id'];


		if(yii::$app->request->isPost){
			//基本信息
			$brand_id = yii::$app->request->post('brand_id');//车辆品牌
			$car_type_id = yii::$app->request->post('car_type_id');//车辆类型
			$operating_company_id = yii::$app->request->post('operating_company_id');
			$is_enable_cainiao = yii::$app->request->post('is_enable_cainiao');
			$is_enable_time = yii::$app->request->post('is_enable_time');
			$is_enable_long = yii::$app->request->post('is_enable_long');
			$province_id = yii::$app->request->post('province_id');
			$city_id = yii::$app->request->post('city_id');
			
			
			//长租信息
			$long_text = '';
			$month_price_long = yii::$app->request->post('month_price_long');
			$year_price_long = yii::$app->request->post('year_price_long');
			$deposit_long = yii::$app->request->post('deposit_long');
			$wz_deposit_long = yii::$app->request->post('wz_deposit_long');
			$service_site_long = yii::$app->request->post('service_site_long');
			//长租数据json格式
			$long_arr = array (
				//基本信息
				"base_info" => array(
					"month_price" => $month_price_long,//运营库存
					"year_price" => $year_price_long,//月租金
					"deposit" => $deposit_long,//租车押金
					"wz_deposit" => $wz_deposit_long//违章押金					
				),
				//提车点
				"service_site" => $service_site_long
			);
			//var_dump($cainiao_arr);exit;
			$long_text = json_encode($long_arr);
			
			//菜鸟信息
			$cainiao_text = '';
			$stock_number = yii::$app->request->post('stock_number');
			$month_price = yii::$app->request->post('month_price');
			$deposit = yii::$app->request->post('deposit');
			$wz_deposit = yii::$app->request->post('wz_deposit');
			$service_site = yii::$app->request->post('service_site');
			//菜鸟数据json格式
			$cainiao_arr = array (
				//基本信息
				"base_info" => array(
					"stock_number" => $stock_number,//运营库存
					"month_price" => $month_price,//月租金
					"deposit" => $deposit,//租车押金
					"wz_deposit" => $wz_deposit//违章押金					
				),
				//提车点
				"service_site" => $service_site
			);
			//var_dump($cainiao_arr);exit;
			$cainiao_text = json_encode($cainiao_arr);
			
			//分时信息
			$time_text = '';
			$starting_mileage = yii::$app->request->post('starting_mileage');
			$starting_price = yii::$app->request->post('starting_price');
			$deposit_time = yii::$app->request->post('deposit_time');
			$wz_deposit_time = yii::$app->request->post('wz_deposit_time');
			$insurance_expense = yii::$app->request->post('insurance_expense');
			$insurance_bjmp = yii::$app->request->post('insurance_bjmp');
			$time_price = yii::$app->request->post('time_price');
			$time_out_price_ = yii::$app->request->post('time_out_price_');
			$day_price = yii::$app->request->post('day_price');
			$day_out_price_h = yii::$app->request->post('day_out_price_h');
			$day_out_price_d = yii::$app->request->post('day_out_price_d');
			
			$service_site_time = yii::$app->request->post('service_site_time');
			//分时数据json格式
			$time_arr = array (
				//基本信息
				"base_info" => array(
					"starting_mileage" => $starting_mileage,//起步里程(km)
					"starting_price" => $starting_price,//起步价格
					"deposit" => $deposit_time,//租车押金
					"wz_deposit" => $wz_deposit_time,//违章押金					
					"insurance_expense" => $insurance_expense,//违章押金					
					"insurance_expense" => $insurance_expense,//违章押金					
					"insurance_bjmp" => $insurance_bjmp,//违章押金					
					"time_price" => $time_price,//违章押金					
					"time_out_price_" => $time_out_price_,//违章押金					
					"day_price" => $day_price,//违章押金					
					"day_out_price_h" => $day_out_price_h,//违章押金					
					"day_out_price_d" => $day_out_price_d//违章押金					
				),
				//提车点
				"service_site" => $service_site_time
			);
			
			$time_text = json_encode($time_arr);
			//var_dump($cainiao_arr);
			
			/*
			*同一城市只能有一个车型
			*/
			$arr_type = (new \yii\db\Query())->from('cs_car_type_lease')->select('car_type_id,city_id')->where('is_del=0')->all();
			foreach($arr_type as $k =>$v) {
				if($v['car_type_id']==$car_type_id && $v['city_id']==$city_id) {
					//echo '123';exit;
		    		$returnArr['info'] = '该城市的车型已存在';
					return json_encode($returnArr);
				}

			}

			$reg_record = $connection->createCommand()->insert('cs_car_type_lease', [
						'brand_id' => $brand_id,
						'car_type_id' => $car_type_id,
						'province_id' => $province_id,
						'city_id' => $city_id,					
						'operating_company_id' => $operating_company_id,
						'is_enable_cainiao' => $is_enable_cainiao,
						'is_enable_time' => $is_enable_time,
						'is_enable_long' => $is_enable_long,
						'time_text' => $time_text,
						'long_text' => $long_text,
						'cainiao_text' => $cainiao_text

						])->execute();
			if($reg_record){
				$reg_record_id = Yii::$app->db->getLastInsertID();
				if ($is_enable_cainiao == 1) {
					$this->synData($reg_record_id);//同步新增数据到菜鸟
				}
				$returnArr['status'] = true;
	    		$returnArr['info'] = '添加成功!';
			} else {
				$returnArr['status'] = false;
		    	$returnArr['info'] = '添加失败!';	
			}
			return json_encode($returnArr);
		}
		
		//获取配置数据
        $configItems = [
            'car_model_name'
        ];
        $configCategoryModel = new ConfigCategory();
        $config = $configCategoryModel->getCategoryConfig($configItems,'value');
       
	    //提车点		
		$query = (new \yii\db\Query())->select([
    			's.id as value',    			
    			's.name as text'    			
    			])->from('oa_extract_car_site s')    		 			
    			 ->where(['s.is_del'=>0,'parent_id'=>0])
				;
		
    	$site = $query		
		->all();
	    //查询所有的车型
        $arr2 = [];
        $cartype_a = CarType::find()->select(['id','car_model'])->andWhere(['`is_del`'=>0])->asArray()->all();
        foreach($cartype_a as $key2 =>$val2) {  
            $obje = [];
            $obje['text'] = $config['car_model_name'][$val2['car_model']]['text'];
            $obje['value'] = $val2['id'];
            $config['car_type_id'][] = $obje;
        }
	  
			foreach ($site as $ss) {
				$config['service_site'][] =	$ss;
			}
			
			
        	$config['cainiao_status'] = [];
   
        	$config['cainiao_status'][] = ['value'=>'0','text'=>'未开启'];
        	$config['cainiao_status'][] = ['value'=>'1','text'=>'开启'];
        	
        	$config['time_status'] = [];
   
        	$config['time_status'][] = ['value'=>'0','text'=>'未开启'];
        	$config['time_status'][] = ['value'=>'1','text'=>'开启'];
        	
        	$config['long_lease_status'] = [];
      
        	$config['long_lease_status'][] = ['value'=>'0','text'=>'未开启'];
        	$config['long_lease_status'][] = ['value'=>'1','text'=>'开启'];
        	
	   
	   //身份	
		$provinces = $connection->createCommand(
				"select * from zc_region where region_type=1"
		)->queryAll();
			   
		return $this->render('add',['config'=>$config,'provinces'=>$provinces]);
	}
	public function actionCheck3() {
		//return "hi";
        $brand_id = yii::$app->request->post('brand_id');
        $connection = yii::$app->db;
		
		//$searchFormOptions = array();
		//获取配置数据
        $configItems = [
            'car_model_name'
        ];
        $configCategoryModel = new ConfigCategory();
        $config = $configCategoryModel->getCategoryConfig($configItems,'value');

		$arr2 = [];
        $cartype_a = CarType::find()->select(['id as value','car_model as text'])->andWhere(['`is_del`'=>0,'brand_id'=>$brand_id])->asArray()->all();
        //var_dump($cartype_a);exit;
        foreach($cartype_a as $key2 =>$val2) {  
            //$obje = [];
            $cartype_a[$key2]['text'] = $config['car_model_name'][$val2['text']]['text'];
            $cartype_a[$key2]['value'] = $val2['value'];
           // $searchFormOptions[ = $obje;
        }
			
        return json_encode($cartype_a);
		
	}
	//同步车型租赁数据到菜鸟
	public function synData($reg_record) {
		
		$connection = yii::$app->db;		
		//$reg_record = 2;
		$car_model_category_id = 62;
		//查询要同步的数据
		$sql = "
				SELECT 
				a.*,
				zc.region_name as province,
				zc2.region_name as city,
				o.id as branch_id,
				o.name as branch_name,
				j.text as car_model,				
    			b.id as brand_id,  
				b.code,    
				t.car_model as model_code,				
    			b.name as brand   

				FROM `cs_car_type_lease` as a
							LEFT JOIN cs_car_brand as b on b.id=a.brand_id 
							LEFT JOIN cs_car_type as t on a.car_type_id=t.id 							
							LEFT JOIN cs_config_item as j on j.value=t.car_model and j.belongs_id=$car_model_category_id 
							LEFT JOIN cs_operating_company o on a.operating_company_id = o.id    
							LEFT JOIN zc_region zc on zc.region_id = a.province_id    
							LEFT JOIN zc_region zc2 on zc2.region_id = a.city_id    
					  WHERE a.is_del=0
								AND a.id=$reg_record			
				";		
		$data = $connection->createCommand($sql)->queryOne();		
				
		if ($data) {
		
			$aliDaYuDir = dirname(dirname(getcwd())).'/extension/taobao-sdk-PHP-daily_bg_type';
			include_once($aliDaYuDir.'/TopSdk.php');	
			$c = new \TopClient;  
			$c->gatewayUrl = 'http://gw.api.tbsandbox.com/router/rest';
			$c->appkey = '1023717193';
			$c->secretKey = 'sandbox15945c74b3dcd70a2d5f6d861';
			
			$req = new \CainiaoLvsVmsVehiclemodelbasicdataUploadRequest(); 			
			
			$cainiao_SB = json_decode($data['cainiao_text']);
			$model = new \VehicleModelBasicData;//租赁信息
			
			//租金押金	
			$vehicleRentAndDeposit = new \VehicleRentAndDeposit;			
			$vehicleRentAndDeposit->deposit = $cainiao_SB->base_info->deposit;//押金			
			$vehicleRentAndDeposit->rent = $cainiao_SB->base_info->month_price;//租金			
			$vehicleRentAndDeposit->illegal_deposit = $cainiao_SB->base_info->wz_deposit;//违章押金			
			$model->vehicle_rent_and_deposit = $vehicleRentAndDeposit;
			
			//提车点列表			
			foreach ($cainiao_SB->service_site as $skey => $site){
					$sql = "
						SELECT 
						a.address,						
						a.tel as phone,
						m.name as manager
						FROM `oa_extract_car_site` as a
						LEFT JOIN cs_admin as m on m.id=a.user_id
									 WHERE a.is_del=0
										AND a.parent_id=$site			
						";		
				$man = $connection->createCommand($sql)->queryOne();
				if ($man) {
					$gettingVehicleLocation = new \GettingVehicleLocation;
					$gettingVehicleLocation->manager = $man['manager'];
					$gettingVehicleLocation->phone = $man['phone'];
					$gettingVehicleLocation->description = '-';
					$gettingVehicleLocation->address = $man['address'];
					
					$model->picking_up_locations[] = $gettingVehicleLocation;
				}
			}			
		
			
	/** 
	 * 车型名称
	 **/
			$model->model = $data['car_model'];	
			/** 
		 * 库存数量
		 **/
			$model->amount = $cainiao_SB->base_info->stock_number;
			/** 
	 * 运营商id
	 **/
			$model->branch_id = $data['branch_id'];
			/** 
	 * 运营商名称
	 **/
			$model->branch_name = $data['branch_name'];
			/** 
	 * 车辆品牌名称
	 **/
			$model->brand = $data['brand'];
			/** 
	 * 车型品牌id
	 **/
			// $model->brand_id = $data['code'];
			/** 
	 * 所属城市
	 **/
			$model->city = $data['city'];
			/** 
	 * 车辆所在区县
	 **/
			$model->district = '未知';
			/** 
	 * 租赁公司编码
	 **/
			$model->lessor_company_code = 1;
			/** 
	 * 租赁公司名称
	 **/
			$model->lesssor_company = '地上铁租赁';
			
			/** 
	 * 车型id
	 **/
			// $model->model_id = $data['model_code'];
			
			/** 
	 * 车辆所在省
	 **/
			$model->province = $data['province'];
			/** 
	 * 车辆所在乡镇
	 **/
			$model->town = '未知';
			
						
		
			 $req->setModelBasicParameters(json_encode($model));
			//var_dump($model);
			//var_dump($req->getModelBasicParameters());exit;
			//执行传输
			$vmsResultBo = $c->execute($req);
			// var_dump($vmsResultBo);		exit;
			if (isset($vmsResultBo->data) && $vmsResultBo->data == true) {//同步成功
				//echo "数据同步成功".$reg_record;
			} else if(isset($vmsResultBo->code)){    //同步失败				
				//echo "数据同步失败!错误代码：".$vmsResultBo->code.'描述：'.$vmsResultBo->msg;
			} else if (isset($vmsResultBo->hsf_error_code)){
				//echo "数据同步失败!错误代码：".$vmsResultBo->hsf_error_code.'描述：'.$vmsResultBo->error_message;
			} else {
				//echo "未知错误，数据同步失败!";
			}	
			//var_dump($vmsResultBo);
		}
			//exit;
	}

	//列表显示
	public function actionGetList(){


		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;

		$query = (new \yii\db\Query())->select([
    			'a.*',	
				'r.region_name',
				'o.name as operating_company_id',
				'i.text as car_model',
    			'b.name as brand_id'    			
    			])->from('cs_car_type_lease a')
    			->leftJoin('cs_car_brand b', 'a.brand_id = b.id')    			
    			->leftJoin('cs_car_type t', 'a.car_type_id = t.id')    			
    			->leftJoin('cs_config_item i', 'i.value = t.car_model and i.belongs_id=62')    			
    			->leftJoin('cs_operating_company o', 'a.operating_company_id = o.id')    			
    			->leftJoin('zc_region r', 'a.city_id = r.region_id')    			
    			 ->where(['a.is_del'=>0])
				;
		//$query->andFilterWhere(['=','a.`car_type`',yii::$app->request->get('car_type')]);
		//查品牌，查父品牌时也会查出子品牌
        if(yii::$app->request->get('brand_id')){
            $brand_id = yii::$app->request->get('brand_id');
            $query->andFilterWhere([
                'or',
                ['a.`brand_id`'=>$brand_id],
             
            ]);
        }
		$query->andFilterWhere(['=','a.`car_type_id`',yii::$app->request->get('car_type_id')]);
		// var_dump(yii::$app->request->get('car_type_id'));exit;
		$query->andFilterWhere(['=','a.`city_id`',yii::$app->request->get('city')]);
		$query->andFilterWhere(['=','a.`operating_company_id`',yii::$app->request->get('operating_company_id')]);
		
		$query->andFilterWhere(['=','a.`is_enable_time`',yii::$app->request->get('time_status')]);
		$query->andFilterWhere(['=','a.`is_enable_cainiao`',yii::$app->request->get('cainiao_status')]);
		$query->andFilterWhere(['=','a.`is_enable_long`',yii::$app->request->get('long_lease_status')]);
		
		$total = $query->count();
    	$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
    	$data = $query->offset($pages->offset)->limit($pages->limit)->all();
    	
    	foreach($data as $key =>$value){
    		$time_obj = json_decode($value['time_text']);
			$data[$key]['time_price'] = $time_obj->base_info->time_price;
			$data[$key]['day_price'] = $time_obj->base_info->day_price;
			
			$cainiao_obj = json_decode($value['cainiao_text']);
			$data[$key]['month_price'] = $cainiao_obj->base_info->month_price;
			$data[$key]['deposit'] = $cainiao_obj->base_info->deposit;
			$data[$key]['wz_deposit'] = $cainiao_obj->base_info->wz_deposit;			
			
			$long_obj = json_decode($value['long_text']);
			$data[$key]['month_price_long'] = $long_obj->base_info->month_price;
			$data[$key]['year_price_long'] = $long_obj->base_info->year_price;			
    	}
    	
    	$returnArr = [];
    	$returnArr['rows'] = $data;
    	$returnArr['total'] = $total;
  
    	return json_encode($returnArr);
	}

	//查看详情
	public function actionScan()
	{
		$id = yii::$app->request->get('id') or die('param id is required');
			$id = intval(yii::$app->request->get('id'));
		if(!$id){
            die('param id is required');
        }
        if($id){
           $query = (new \yii\db\Query())->select([
				'a.*',	
				'r.region_name',
				'r2.region_name as province_name',
				'o.name as operating_company_name',
				'i.text as car_model',
    			'b.name as brand_name'    			
    			])->from('cs_car_type_lease a')
    			->leftJoin('cs_car_brand b', 'a.brand_id = b.id')    			
    			->leftJoin('cs_car_type t', 'a.car_type_id = t.id')    			
    			->leftJoin('cs_config_item i', 'i.value = t.car_model and i.belongs_id=62')    			
    			->leftJoin('cs_operating_company o', 'a.operating_company_id = o.id')    			
    			->leftJoin('zc_region r', 'a.city_id = r.region_id')    			
    			->leftJoin('zc_region r2', 'a.province_id = r2.region_id')    			
    		   ->where(['a.id'=>$id])
		     	
				;
			$data = $query->one();
		}
       //获取配置数据
        $configItems = [
            'car_model_name'
        ];
        $configCategoryModel = new ConfigCategory();
        $config = $configCategoryModel->getCategoryConfig($configItems,'value');       
	    //提车点		
		$query = (new \yii\db\Query())->select([
    			's.id as value',    			
    			's.name as text'    			
    			])->from('oa_extract_car_site s')    		 			
    			 ->where(['s.is_del'=>0,'parent_id'=>0])
				;		
    	$site = $query		
		->all();
	    //查询所有的车型
        $arr2 = [];
        $cartype_a = CarType::find()->select(['id','car_model'])->andWhere(['`is_del`'=>0])->asArray()->all();
        foreach($cartype_a as $key2 =>$val2) {  
            $obje = [];
            $obje['text'] = $config['car_model_name'][$val2['car_model']]['text'];
            $obje['value'] = $val2['id'];
            $config['car_type_id'][] = $obje;
        }
	   
			foreach ($site as $ss) {
				$config['service_site'][] =	$ss;
			}
			
			
        	$config['cainiao_status'] = [];
   
        	$config['cainiao_status'][] = ['value'=>'0','text'=>'未开启'];
        	$config['cainiao_status'][] = ['value'=>'1','text'=>'开启'];        	
		
        	$config['time_status'] = [];   
        	$config['time_status'][] = ['value'=>'0','text'=>'未开启'];
        	$config['time_status'][] = ['value'=>'1','text'=>'开启'];
        	
        	$config['long_lease_status'] = [];      
        	$config['long_lease_status'][] = ['value'=>'0','text'=>'未开启'];
        	$config['long_lease_status'][] = ['value'=>'1','text'=>'开启'];
        	
	   
	   //身份
		$connection = yii::$app->db;
		$provinces = $connection->createCommand(
				"select * from zc_region where region_type=1"
		)->queryAll();
				
				
		//长租数据
		$long_obj = json_decode($data['long_text']);
		$data['month_price_long'] = $long_obj->base_info->month_price;
		$data['year_price_long'] = $long_obj->base_info->year_price;
		$data['deposit_long'] = $long_obj->base_info->deposit;
		$data['wz_deposit_long'] = $long_obj->base_info->wz_deposit;
		
		//分时数据
		$time_obj = json_decode($data['time_text']);
		$data['starting_mileage'] = $time_obj->base_info->starting_mileage;
		$data['starting_price'] = $time_obj->base_info->starting_price;
		$data['deposit_time'] = $time_obj->base_info->deposit;
		$data['wz_deposit_time'] = $time_obj->base_info->wz_deposit;
		$data['insurance_expense'] = $time_obj->base_info->insurance_expense;
		$data['insurance_bjmp'] = $time_obj->base_info->insurance_bjmp;
		$data['time_price'] = $time_obj->base_info->time_price;
		$data['time_out_price_'] = $time_obj->base_info->time_out_price_;
		$data['day_price'] = $time_obj->base_info->day_price;
		$data['day_out_price_h'] = $time_obj->base_info->day_out_price_h;
		$data['day_out_price_d'] = $time_obj->base_info->day_out_price_d;

		//菜鸟数据
		$cainiao_obj = json_decode($data['cainiao_text']);
		$data['stock_number'] = $cainiao_obj->base_info->stock_number;
		$data['month_price'] = $cainiao_obj->base_info->month_price;
		$data['deposit'] = $cainiao_obj->base_info->deposit;
		$data['wz_deposit'] = $cainiao_obj->base_info->wz_deposit;
				
	
		return $this->render(
		'scan',
		['row_result'=>$data,
		'sites'=>$time_obj->service_site,
		'sites_long'=>$long_obj->service_site,
		'sites_cainiao'=>$cainiao_obj->service_site,
		'config'=>$config,'provinces'=>$provinces]);

	}
	
	//编辑
	public function actionEdit() {
		$connection = yii::$app->db;
		if(yii::$app->request->isPost){

			$id = intval(yii::$app->request->post('id')) or die('param id is required');			
            $add_aid = $_SESSION['backend']['adminInfo']['id'];         
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';          
			//var_dump($id);exit;
			
			//ddm
			//基本信息
			$brand_id = yii::$app->request->post('brand_id');//车辆品牌
			$car_type_id = yii::$app->request->post('car_type_id');//车辆类型
			$operating_company_id = yii::$app->request->post('operating_company_id');
			$is_enable_cainiao = yii::$app->request->post('is_enable_cainiao');
			$is_enable_time = yii::$app->request->post('is_enable_time');
			$is_enable_long = yii::$app->request->post('is_enable_long');
			$province_id = yii::$app->request->post('province_id');
			$city_id = yii::$app->request->post('city_id');
			
			//长租信息
			$long_text = '';
			$month_price_long = yii::$app->request->post('month_price_long');
			$year_price_long = yii::$app->request->post('year_price_long');
			$deposit_long = yii::$app->request->post('deposit_long');
			$wz_deposit_long = yii::$app->request->post('wz_deposit_long');
			$service_site_long = yii::$app->request->post('service_site_long');
			//长租数据json格式
			$long_arr = array (
				//基本信息
				"base_info" => array(
					"month_price" => $month_price_long,//运营库存
					"year_price" => $year_price_long,//月租金
					"deposit" => $deposit_long,//租车押金
					"wz_deposit" => $wz_deposit_long//违章押金					
				),
				//提车点
				"service_site" => $service_site_long
			);
			//var_dump($cainiao_arr);exit;
			$long_text = json_encode($long_arr);
			
			//菜鸟信息
			$cainiao_text = '';
			$stock_number = yii::$app->request->post('stock_number');
			$month_price = yii::$app->request->post('month_price');
			$deposit = yii::$app->request->post('deposit');
			$wz_deposit = yii::$app->request->post('wz_deposit');
			$service_site = yii::$app->request->post('service_site');
			//菜鸟数据json格式
			$cainiao_arr = array (
				//基本信息
				"base_info" => array(
					"stock_number" => $stock_number,//运营库存
					"month_price" => $month_price,//月租金
					"deposit" => $deposit,//租车押金
					"wz_deposit" => $wz_deposit//违章押金					
				),
				//提车点
				"service_site" => $service_site
			);
			//var_dump($cainiao_arr);exit;
			$cainiao_text = json_encode($cainiao_arr);
			
			//分时信息
			$time_text = '';
			$starting_mileage = yii::$app->request->post('starting_mileage');
			$starting_price = yii::$app->request->post('starting_price');
			$deposit_time = yii::$app->request->post('deposit_time');
			$wz_deposit_time = yii::$app->request->post('wz_deposit_time');
			$insurance_expense = yii::$app->request->post('insurance_expense');
			$insurance_bjmp = yii::$app->request->post('insurance_bjmp');
			$time_price = yii::$app->request->post('time_price');
			$time_out_price_ = yii::$app->request->post('time_out_price_');
			$day_price = yii::$app->request->post('day_price');
			$day_out_price_h = yii::$app->request->post('day_out_price_h');
			$day_out_price_d = yii::$app->request->post('day_out_price_d');
			
			$service_site_time = yii::$app->request->post('service_site_time');
			//var_dump($service_site_time);
			//var_dump($service_site);
			//exit;
			//分时数据json格式
			$time_arr = array (
				//基本信息
				"base_info" => array(
					"starting_mileage" => $starting_mileage,//起步里程(km)
					"starting_price" => $starting_price,//起步价格
					"deposit" => $deposit_time,//租车押金
					"wz_deposit" => $wz_deposit_time,//违章押金					
					"insurance_expense" => $insurance_expense,//违章押金					
					"insurance_expense" => $insurance_expense,//违章押金					
					"insurance_bjmp" => $insurance_bjmp,//违章押金					
					"time_price" => $time_price,//违章押金					
					"time_out_price_" => $time_out_price_,//违章押金					
					"day_price" => $day_price,//违章押金					
					"day_out_price_h" => $day_out_price_h,//违章押金					
					"day_out_price_d" => $day_out_price_d//违章押金					
				),
				//提车点
				"service_site" => $service_site_time
			);
			
			$time_text = json_encode($time_arr);
			//var_dump($cainiao_arr);
			//var_dump($time_arr);exit;

			/*
			*同一城市只能有一个车型
			*/
			/*$arr_type = (new \yii\db\Query())->from('cs_car_type_lease')->select('car_type_id,city_id')->where('is_del=0')->all();
			foreach($arr_type as $k =>$v) {
				if($v['car_type_id']==$car_type_id && $v['city_id']==$city_id) {
					//echo '123';exit;
		    		$returnArr['info'] = '该城市的车型已存在';
					return json_encode($returnArr);
				}

			}*/
			/*echo '<pre>';
			var_dump($brand_id,$car_type_id,$province_id,$city_id,$operating_company_id,$is_enable_cainiao,$is_enable_long,$is_enable_time,$time_text,$long_text,$cainiao_text);
			var_dump($id);exit;
			exit;*/

			$reg_record = $connection->createCommand()->update('cs_car_type_lease', [
						'brand_id' => $brand_id,
						'car_type_id' => $car_type_id,
						'province_id' => $province_id,
						'city_id' => $city_id,					
						'operating_company_id' => $operating_company_id,
						'is_enable_cainiao' => $is_enable_cainiao,
						'is_enable_long' => $is_enable_long,
						'is_enable_time' => $is_enable_time,
						'time_text' => $time_text,
						'long_text' => $long_text,
						'cainiao_text' => $cainiao_text

						],"id=$id")->execute();
			//var_dump($reg_record);exit;

			if($reg_record){
				// $reg_record_id = Yii::$app->db->getLastInsertID();
				$reg_record_id =$id;
				if ($is_enable_cainiao == 1) {
					$this->synData($reg_record_id);//同步新增数据到菜鸟
				}
				$returnArr['status'] = true;
	    		$returnArr['info'] = '修改成功!';
			} else {
				$returnArr['status'] = true;
		    	$returnArr['info'] = '修改成功!';	
			}
			return json_encode($returnArr);
			
		} 

		
		$id = intval(yii::$app->request->get('id'));
		if(!$id){
            die('param id is required');
        }
        if($id){
           $query = (new \yii\db\Query())->select([
    			'a.*',
    			'b.name as brand_name'    		
    			])->from('cs_car_type_lease a')->where(['a.id'=>$id])
    			->leftJoin('cs_car_brand b', 'a.brand_id = b.id')    	
				;
			$data = $query->one();
		}
		//var_dump($data);exit;
       //获取配置数据
        $configItems = [
            'car_model_name'
        ];
        $configCategoryModel = new ConfigCategory();
        $config = $configCategoryModel->getCategoryConfig($configItems,'value');       
	    //提车点		
		$query = (new \yii\db\Query())->select([
    			's.id as value',    			
    			's.name as text'    			
    			])->from('oa_extract_car_site s')    		 			
    			 ->where(['s.is_del'=>0,'parent_id'=>0])
				;		
    	$site = $query		
		->all();
	    //查询所有的车型
        $arr2 = [];
        $cartype_a = CarType::find()->select(['id','car_model'])->andWhere(['`is_del`'=>0,'brand_id'=>$data['brand_id']])->asArray()->all();
        //var_dump($cartype_a);exit;
        foreach($cartype_a as $key2 =>$val2) {  
            $obje = [];
            $obje['text'] = $config['car_model_name'][$val2['car_model']]['text'];
            $obje['value'] = $val2['id'];
            $config['car_type_id'][] = $obje;
        }
	   
			foreach ($site as $ss) {
				$config['service_site'][] =	$ss;
			}
			
			
        	$config['cainiao_status'] = [];
   
        	$config['cainiao_status'][] = ['value'=>'0','text'=>'未开启'];
        	$config['cainiao_status'][] = ['value'=>'1','text'=>'开启'];        	
		
        	$config['time_status'] = [];   
        	$config['time_status'][] = ['value'=>'0','text'=>'未开启'];
        	$config['time_status'][] = ['value'=>'1','text'=>'开启'];
        	
        	$config['long_lease_status'] = [];      
        	$config['long_lease_status'][] = ['value'=>'0','text'=>'未开启'];
        	$config['long_lease_status'][] = ['value'=>'1','text'=>'开启'];
        	
	   
	   //身份
		$connection = yii::$app->db;
		$provinces = $connection->createCommand(
				"select * from zc_region where region_type=1"
		)->queryAll();
		$citys = $connection->createCommand(
				//"select * from zc_region where region_type=2 and parent_id={$the_site['province_id']}"
				"select * from zc_region where region_type=2 and parent_id={$data['province_id']} "
		)->queryAll();		
				
		//长租数据
		$long_obj = json_decode($data['long_text']);
		$data['month_price_long'] = $long_obj->base_info->month_price;
		$data['year_price_long'] = $long_obj->base_info->year_price;
		$data['deposit_long'] = $long_obj->base_info->deposit;
		$data['wz_deposit_long'] = $long_obj->base_info->wz_deposit;
		
		//分时数据
		$time_obj = json_decode($data['time_text']);
		$data['starting_mileage'] = $time_obj->base_info->starting_mileage;
		$data['starting_price'] = $time_obj->base_info->starting_price;
		$data['deposit_time'] = $time_obj->base_info->deposit;
		$data['wz_deposit_time'] = $time_obj->base_info->wz_deposit;
		$data['insurance_expense'] = $time_obj->base_info->insurance_expense;
		$data['insurance_bjmp'] = $time_obj->base_info->insurance_bjmp;
		$data['time_price'] = $time_obj->base_info->time_price;
		$data['time_out_price_'] = $time_obj->base_info->time_out_price_;
		$data['day_price'] = $time_obj->base_info->day_price;
		$data['day_out_price_h'] = $time_obj->base_info->day_out_price_h;
		$data['day_out_price_d'] = $time_obj->base_info->day_out_price_d;

		//菜鸟数据
		$cainiao_obj = json_decode($data['cainiao_text']);
		$data['stock_number'] = $cainiao_obj->base_info->stock_number;
		$data['month_price'] = $cainiao_obj->base_info->month_price;
		$data['deposit'] = $cainiao_obj->base_info->deposit;
		$data['wz_deposit'] = $cainiao_obj->base_info->wz_deposit;
		/*echo '<pre>';
		var_dump($data);exit;*/	
	
		return $this->render(
		'edit',
		['row_result'=>$data,
		'sites'=>$time_obj->service_site,
		'sites_long'=>$long_obj->service_site,
		'sites_cainiao'=>$cainiao_obj->service_site,
		'config'=>$config,'provinces'=>$provinces,'citys'=>$citys]);
       
	}

	 
	 //删除
	 public function actionRemove() {

	 		$id = yii::$app->request->post('id');
		 	$connection = yii::$app->db;
		 	$result = $connection->createCommand()->update('cs_car_type_lease',['is_del'=>1],'id=:id',[':id'=>$id])->execute();

		 	if($result) {
		 		$returnArr['status'] = true;
		 		$returnArr['info'] = '删除成功！';
		 	} else {
		 		$returnArr['status'] = true;
		 		$returnArr['info'] = '删除失败！';
		 	}
		 	return json_encode($returnArr);
	 	
	 } 
}