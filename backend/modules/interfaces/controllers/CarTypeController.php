<?php
/**
 * 会员车辆管理控制器
 */
namespace backend\modules\interfaces\controllers;
use yii;
use backend\models\ConfigCategory;
use backend\models\Vip;
use backend\models\Vehicle;

class CarTypeController extends BaseController{	
	
	public function init(){
		date_default_timezone_set('PRC');
		//验证，md5(szclou)
    	if(!isset($_REQUEST['token']) || $_REQUEST['token'] != 'bb15508fc229425aac882e11fcf0aa1b'){
    		die(json_encode(['error'=>1,'msg'=>'验证失败！']));
    	}
		return true;
	}	
    	
	//获得车型详情 TODO车型模板id+城市id 解决价格问题
	public function actionGetCarTypeScan() {		
		$id = yii::$app->request->get('id');		
		if (!$id) {			
			$datas['error'] = 1001;
			$datas['msg'] = '没有提供id参数！';
			$datas['errline'] = __LINE__;
			return json_encode($datas);
		}       
				
		$datas = [];	
		$connection = yii::$app->db;
		$car_type_category_id = 11;
		$car_model_category_id = 62;
		//$id = 3;
		$sql = "
				select 
				b.name as brand_name,
				l.id,
				a.car_front_img,
				a.car_full_img,
				a.car_left_img,
				a.car_right_img,
				a.car_control_img,
				a.car_tail_img,
				l.time_text,

				i.text as car_model_name,
				j.text as car_type_name,
				a.check_mass,
				a.shaft_distance,
				a.cubage,
				a.endurance_mileage,
				a.outside_long,
				a.outside_width,
				a.outside_height

				from cs_car_type as a
				left join cs_config_item as i on i.`value`=a.car_model and i.belongs_id=$car_model_category_id
				left join cs_config_item as j on j.`value`=a.car_type and j.belongs_id=$car_type_category_id
				left join cs_car_type_lease as l on l.car_type_id=a.id 		
				left join cs_car_brand as b on a.brand_id=b.id
				where l.id=$id
				";	
		$v = $connection->createCommand($sql)->queryOne();		
		
		if ($v && count($v) != 0) {	
		
			$time_obj = json_decode($v['time_text']);//分时数据
			$v['time_price'] = $time_obj->base_info->time_price;
			$v['day_price'] = $time_obj->base_info->day_price;
			$v['starting_price'] = $time_obj->base_info->starting_price;
			$v['deposit'] = $time_obj->base_info->deposit;
			$v['wz_deposit'] = $time_obj->base_info->wz_deposit;
			$v['insurance_expense'] = $time_obj->base_info->insurance_expense;
			$v['insurance_bjmp'] = $time_obj->base_info->insurance_bjmp;
			$v['imgs'] = $v['car_front_img'].",".$v['car_left_img'].",".$v['car_right_img'].",".$v['car_tail_img'].",".$v['car_control_img'];
			unset($v['car_front_img']);
			unset($v['car_left_img']);
			unset($v['car_right_img']);
			unset($v['car_control_img']);
			unset($v['car_tail_img']);
			unset($v['time_text']);
		
			$datas['error'] = 0;
			$datas['msg'] = '获取车型详情成功！';
			$datas['data'] = $v;
			$datas['total'] = count($v);
		} else {
			$datas['error'] = 1;
			$datas['msg'] = '未找到车型详情！';
			$datas['errline'] = __LINE__;
		}
        return json_encode($datas);
	}
	
	//获得场站、取车门店列表(分时)
	public function actionGetCarSiteList(){		
		$datas = [];	
		
		// $city_id   = yii::$app->request->get('city_id');			
		$id   = yii::$app->request->get('id');			
		if (!$id) {			
			$datas['error'] = 1001;
			$datas['msg'] = '没有提供车型id参数！';
			$datas['errline'] = __LINE__;
			return json_encode($datas);
		} 
		// if (!$city_id) {			
			// $datas['error'] = 1001;
			// $datas['msg'] = '没有提供城市id参数！';
			// $datas['errline'] = __LINE__;
			// return json_encode($datas);
		// } 
		
		$connection = yii::$app->db;
		//查出车型模板的分时场站信息
		$sql = "
				select id,time_text from cs_car_type_lease where
				id=$id				
				";	
		// var_dump($sql);exit;
		$car_type_lease = $connection->createCommand($sql)->queryOne();		
		if ($car_type_lease){			
			$time_obj = json_decode($car_type_lease['time_text']);//分时数据			
			$service_site = $time_obj->service_site;
			
			// var_dump($service_site);exit;
			$service_site=array_filter($service_site);
			$service_site=array_unique($service_site);
			$site_list = array();
			if (count($service_site)!= 0) {
				foreach ($service_site as $key=>$v){
					//是否至少有一辆车已经提车到该租赁信息的指定场站，且启用
					$sql = "
							select *
							from app_car
							where car_type_id=$id and status=0 and car_store=$v
							";
					$exist_more = $connection->createCommand($sql)->queryAll();					
					if ($exist_more && count($exist_more) != 0) {						
						$sql = "
								select id,name from oa_extract_car_site where 							
								is_del=0
								and id = $v				
								";							
						$site_list[] = $connection->createCommand($sql)->queryOne();									
					} 
				}						
			}		
		}			
		
		if ($site_list && count($site_list) != 0) {				
			$datas['error'] = 0;
			$datas['msg'] = '获取门店成功！';
			$datas['data'] = $site_list;
			$datas['total'] = count($site_list);
		} else {
			$datas['error'] = 1202;
			$datas['msg'] = '未找到任何门店！';
			$datas['errline'] = __LINE__;
		}
        return json_encode($datas);
	}
	
	//获取车型模板列表(分时)
	public function actionGetCarTypeList(){		
		$datas = [];
		$car_type_category_id = 11;
		$car_model_category_id = 62;
		
		
		$city_id   = yii::$app->request->get('city_id');			
		if (!$city_id) {			
			$datas['error'] = 1001;
			$datas['msg'] = '没有提供城市id参数！';
			$datas['errline'] = __LINE__;
			return json_encode($datas);
		} 
		
		$connection = yii::$app->db;
		$sql = "
				select 
				b.name as brand_name,
				l.id,
				i.text as car_model_name,
				j.text as car_type_name,
				a.endurance_mileage,
				l.time_text,
				a.car_full_img,
				a.cubage
				
				from cs_car_type as a
				left join cs_config_item as i on i.`value`=a.car_model and i.belongs_id=$car_model_category_id
				left join cs_config_item as j on j.`value`=a.car_type and j.belongs_id=$car_type_category_id
				left join cs_car_type_lease as l on l.car_type_id=a.id and l.city_id=$city_id
				left join cs_car_brand as b on a.brand_id=b.id
				where a.is_del=0 and l.is_enable_time=1 and l.is_del=0
							
				";	
		$vehicles = $connection->createCommand($sql)->queryAll();			
		if ($vehicles && count($vehicles) != 0) {	
			foreach ($vehicles as $key=>$v){
				//是否至少有一辆车已经提车到该租赁信息的任意场站，且启用
				$sql = "
						select *
						from app_car
						where car_type_id=$v[id] and status=0
						";
				$exist_more = $connection->createCommand($sql)->queryAll();					
				if ($exist_more && count($exist_more) != 0) {
					$time_obj = json_decode($v['time_text']);//分时数据
					$vehicles[$key]['time_price'] = $time_obj->base_info->time_price;
					$vehicles[$key]['day_price'] = $time_obj->base_info->day_price;
					unset($vehicles[$key]['time_text']);					
				} else {
					//没提车租个毛？
					unset($vehicles[$key]);
				}				
			}	
			$vehicles = array_values($vehicles);			
		}
		//删完后还有
		if ($vehicles && count($vehicles) != 0) {	
			$datas['error'] = 0;
			$datas['msg'] = '获取车型成功！';
			$datas['data'] = $vehicles;
			$datas['total'] = count($vehicles);
		} else {
			$datas['error'] = 1201;
			$datas['msg'] = '未找到任何车型！';
			$datas['errline'] = __LINE__;
		}
        return json_encode($datas);
	}
	//获取车型模板列表(长租)
	public function actionGetCarTypeLongList(){		
		$datas = [];
		$car_type_category_id = 11;
		$car_model_category_id = 62;		
		
		$city_id   = yii::$app->request->get('city_id');			
		if (!$city_id) {			
			$datas['error'] = 1001;
			$datas['msg'] = '没有提供城市id参数！';
			$datas['errline'] = __LINE__;
			return json_encode($datas);
		} 
		
		$connection = yii::$app->db;
		$sql = "
				select 
				b.name as brand_name,
				a.id,
				i.text as car_model_name,
				j.text as car_type_name,
				a.endurance_mileage,				
				a.car_full_img,
				a.cubage
				
				from cs_car_type as a
				left join cs_config_item as i on i.`value`=a.car_model and i.belongs_id=$car_model_category_id
				left join cs_config_item as j on j.`value`=a.car_type and j.belongs_id=$car_type_category_id
				left join cs_car_type_lease as l on l.car_type_id=a.id and l.city_id=$city_id
				left join cs_car_brand as b on a.brand_id=b.id
				where a.is_del=0 				
				and l.is_enable_long=1 
				and l.is_del=0
							
				";	
		$vehicles = $connection->createCommand($sql)->queryAll();		
		
		// if ($vehicles && count($vehicles) != 0) {	
			//是否至少有一辆车已经提车到该租赁信息的任意场站，且启用
			// foreach ($vehicles as $key=>$v){
				// $sql = "
						// select *
						// from app_car
						// where car_type_id=$v[id] and status=0
						// ";
				// $exist_more = $connection->createCommand($sql)->queryAll();					
				// if ($exist_more && count($exist_more) != 0) {} else {
					//没提车租个毛？
					// unset($vehicles[$key]);
				// }			
			// }
			// $vehicles = array_values($vehicles);		
		// } 		
		
		if ($vehicles && count($vehicles) != 0) {	
			$datas['error'] = 0;
			$datas['msg'] = '获取车型成功！';
			$datas['data'] = $vehicles;
			$datas['total'] = count($vehicles);
		} else {
			$datas['error'] = 1201;
			$datas['msg'] = '未找到任何车型！';
			$datas['errline'] = __LINE__;
		}
        return json_encode($datas);
	}

	//申请长租
	public function actionSubmitLongOrder(){
		date_default_timezone_set('PRC');
		$db = \Yii::$app->db;	
		if(yii::$app->request->isGet){
			//搜集表单
			$user_id   = yii::$app->request->get('user_id');			
			if (!$user_id) {			
				$datas['error'] = 1001;
				$datas['msg'] = '没有提供身份id参数！';
				$datas['errline'] = __LINE__;
				return json_encode($datas);
			} 
			//获得用户账号
			$sql = "
			select mobile
			from cs_vip				
			where id=$user_id
			";	
			$v = $db->createCommand($sql)->queryOne();
			$apply_customer= "";
			if ($v) {
				$apply_customer = $v['mobile'];
			} 
			//var_dump($apply_customer);exit;
			
			$city_id   = yii::$app->request->get('city_id');
			$car_models   = yii::$app->request->get('car_models');			
			$company_name   = yii::$app->request->get('company_name');
			$contact_name   = yii::$app->request->get('contact_name');
			$contact_mobile   = yii::$app->request->get('contact_mobile');
			$contact_email   = yii::$app->request->get('contact_email');
			$es_take_time   = yii::$app->request->get('es_take_time');
	
			$order_time = date("Y-m-d H:i:s",time());
			$no_time = date("Ymd",time());
			
			// var_dump($no_time);exit;
		
			$result = $db->createCommand()->insert('app_long_rent_apply',
					[
					'city_id'		=> $city_id,
					'company_name'		=> $company_name,
					'car_models'		=> $car_models,
					'contact_name'		=> $contact_name,
					'contact_mobile'		=> $contact_mobile,					
					'contact_email'		=> $contact_email,					
					'es_take_car_time'		=> $es_take_time,					
					'order_time'		=> $order_time,					
				    'apply_customer'	=> $apply_customer,			
				    ])->execute();
			if($result){
				$insert_id = Yii::$app->db->getLastInsertID();			
				$apply_no = 'CZ'.$no_time.$insert_id;
				// var_dump($apply_no);exit;	
				$flag = $db->createCommand()->update('app_long_rent_apply',
					[						
					'apply_no'  => $apply_no,					
					],['apply_id'=>$insert_id])->execute();
				if($flag) {
					$apply = array('apply_no'=>$apply_no);
					$datas['error'] = 0;
					$datas['msg'] = '申请长租提交成功！';
					$datas['data'] = $apply;
					$datas['total'] = count($apply);									
				} else {
					$datas['error'] = 1039;
					$datas['msg'] = '操作失败！';
					$datas['errline'] = __LINE__;	
				}
			}else{
               	$datas['error'] = 1039;
				$datas['msg'] = '申请提交失败！';
				$datas['errline'] = __LINE__;		
			}
			return json_encode($datas);	
		}else{
			$datas['error'] = 1115;
			$datas['msg'] = '没有采用get请求提供必要参数！';
			$datas['errline'] = __LINE__;
			return json_encode($datas);
		}		
	}	
	
	//获取长租申请列表
	public function actionLongOrderList(){		
		$datas = [];	
		$connection = yii::$app->db;
		$user_id   = yii::$app->request->get('user_id');			
		if (!$user_id) {			
			$datas['error'] = 1001;
			$datas['msg'] = '没有提供身份id参数！';
			$datas['errline'] = __LINE__;
			return json_encode($datas);
		} 
		//获得用户账号
		$sql = "
		select mobile
		from cs_vip				
		where id=$user_id
		";	
		$v = $connection->createCommand($sql)->queryOne();
		$apply_customer= "";
		if ($v) {
			$apply_customer = $v['mobile'];
		} else {
			$datas['error'] = 1001;
			$datas['msg'] = '没有找到该用户！';
			$datas['errline'] = __LINE__;
			return json_encode($datas);
		}
		$sql = "
				select 
					apply_id,
					apply_no,
					order_time,				
					es_take_car_time,
					sale_id,
					cs_admin.name as sales,
					car_models,
					sales_mobile			
				from app_long_rent_apply				
				left join cs_admin on cs_admin.id=app_long_rent_apply.sale_id
				where app_long_rent_apply.is_del=0 	
				and app_long_rent_apply.apply_customer='".$apply_customer."'
				";
		$list = $connection->createCommand($sql)->queryAll();		
		
		if ($list && count($list) != 0) {	
			foreach ($list as $key=>$v){			
				if ($v['sale_id']!=0) {
					$list[$key]['status'] = 1;//已经指派服务员
				} else {
					$list[$key]['status'] = 0;
				}
				unset($list[$key]['sale_id']);
				$total_num = 0;
				if ($v['car_models'] != '' && $v['car_models'] != '[]') {
					$models = json_decode($v['car_models']);
					foreach ($models as $the_one) {
						$total_num += $the_one->num;
					}					
				} 
				$list[$key]['total_num'] = $total_num;				
				unset($list[$key]['car_models']);
			}
			$datas['error'] = 0;
			$datas['msg'] = '获取长租申请列表成功！';
			$datas['data'] = $list;
			$datas['total'] = count($list);
		} else {
			$datas['error'] = 1201;
			$datas['msg'] = '未找到任何数据！';
			$datas['errline'] = __LINE__;
		}
        return json_encode($datas);
	}
 
	//获得城市列表
	public function actionGetCityList(){		
		$datas = [];	
		$city_type  = yii::$app->request->get('city_type');
		$connection = yii::$app->db;
		if($city_type ==1){	//长租城市
			$sql = "
			select region_id,region_name
			from zc_region
			where region_type=2	and region_id in
			(select city_id from cs_car_type_lease where is_del=0 and is_enable_long=1 group by city_id)
			";
		}else{        		//分时城市
			$sql = "
			select region_id,region_name
			from zc_region
			where region_type=2	and region_id in
			(select city_id from cs_car_type_lease where is_del=0 and is_enable_time=1 group by city_id)
			";
		}
		
		$site_list = $connection->createCommand($sql)->queryAll();		
		
		if ($site_list && count($site_list) != 0) {				
			$datas['error'] = 0;
			$datas['msg'] = '获取城市成功！';
			$datas['data'] = $site_list;
			$datas['total'] = count($site_list);
		} else {
			$datas['error'] = 1202;
			$datas['msg'] = '未找到任何城市数据！';
			$datas['errline'] = __LINE__;
		}
        return json_encode($datas);
	}
	
	
	//获得长租申请详情 
	public function actionLongOrderScan() {		
		$id = yii::$app->request->get('apply_id');		
		if (!$id) {			
			$datas['error'] = 1001;
			$datas['msg'] = '没有提供apply_id参数！';
			$datas['errline'] = __LINE__;
			return json_encode($datas);
		}       
		
		$datas = [];	
		$connection = yii::$app->db;			
		$sql = "
				select 
				apply_no,
				order_time,				
				es_take_car_time,
				car_models,
				company_name,
				contact_name,
				contact_mobile,
				contact_email,
				sale_id,
				cs_admin.name as sales,
				sales_mobile,
				zc_region.region_name as city
				
				from app_long_rent_apply				
				left join cs_admin on cs_admin.id=app_long_rent_apply.sale_id
				left join zc_region on zc_region.region_id=app_long_rent_apply.city_id				
				where apply_id=$id
				";	
		$v = $connection->createCommand($sql)->queryOne();	
		if ($v && count($v) != 0) {	
			if ($v['sale_id']!=0) {
				$v['status'] = 1;//已经指派服务员
			} else {
				$v['status'] = 0;
			}
			unset($v['sale_id']);	

			//转换车型			
			$v['car_models'] = json_decode($v['car_models']);
			if ($v['car_models']){
				foreach ($v['car_models'] as $key => $value) {
					$car_type_id = $value->car_type_id;
					if ($car_type_id) {
						//查询车型模板数据
						$car_model_category_id = 62;		
						$car_type = $connection->createCommand("
						select 
							cs_car_brand.name as brand_name,
							i.text as car_model_name
						from cs_car_type 
						left join cs_car_brand on cs_car_brand.id=cs_car_type.brand_id
						left join cs_config_item as i on i.`value`=cs_car_type.car_model and i.belongs_id=$car_model_category_id				
						where cs_car_type.id=".$car_type_id
						)->queryOne();
						if ($car_type) {
							$v['car_models'][$key]->car_type_id = $car_type['brand_name']." ".$car_type['car_model_name'];							
						}
					}
				}
				$v['car_models'] = json_encode($v['car_models']);
			}
		
			$datas['error'] = 0;
			$datas['msg'] = '获取长租申请详情成功！';
			$datas['data'] = $v;
			$datas['total'] = count($v);
		} else {
			$datas['error'] = 1;
			$datas['msg'] = '未找到详情！';
			$datas['errline'] = __LINE__;
		}
        return json_encode($datas);
	}
}