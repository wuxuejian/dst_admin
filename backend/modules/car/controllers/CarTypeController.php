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
class CarTypeController extends BaseController
{
	public function actionIndex()
	{	
		//echo '111';exit;
		$buttons = $this->getCurrentActionBtn();
		//获取配置数据
        $configItems = ['car_status','gain_year','gain_way','car_type','use_nature','car_color','car_model_name','car_status2'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
        //查询表单select选项
        $searchFormOptions = [];
		//车辆类型
        if($config['car_type'])
        {
        	$searchFormOptions['car_type'] = [];
        	$searchFormOptions['car_type'][] = ['value'=>'','text'=>'不限'];
        	foreach($config['car_type'] as $val){
        		$searchFormOptions['car_type'][] = ['value'=>$val['value'],'text'=>$val['text']];
        	}
        }
		//var_dump($buttons);exit; 
		//echo '111';exit;
		return $this->render('index',[
            'buttons'=>$buttons,
            'config'=>$config,
            'searchFormOptions'=>$searchFormOptions,
        ]);
	}

	//车辆模板添加
	public function actionAdd()
	{
		//echo '111';exit;
		date_default_timezone_set('PRC');
		$connection = yii::$app->db;
		$add_time = strtotime(date('Y-m-d H:i:s'));
		//var_dump($add_time);exit;
		$add_aid  = $_SESSION['backend']['adminInfo']['id'];


		if(yii::$app->request->isPost){
			$brand_id = yii::$app->request->post('brand_id');//车辆品牌
			$car_type = yii::$app->request->post('car_type');//车辆类型
			$car_model = yii::$app->request->post('car_model');//车辆型号
			//车辆名称
			$manufacturer_name = yii::$app->request->post('manufacturer_name');//车辆制造厂
			//$use_nature = yii::$app->request->post('use_nature');//车辆使用性质
			$outside_long = yii::$app->request->post('outside_long');//长度
			$outside_width = yii::$app->request->post('outside_width');//宽度
			$outside_height = yii::$app->request->post('outside_height');//高度
			//判断长宽高是否填写
			if($outside_long == '') {
				$outside_long = 0;
			}
			if($outside_width == '') {
				$outside_width = 0;
			}
			if($outside_height == '') {
				$outside_height = 0;
			}
			//判断轴距是否录入
			$shaft_distance = yii::$app->request->post('shaft_distance');//轴距
			if($shaft_distance == '') {
				$shaft_distance = 0;
			}
			$wheel_distance_f = yii::$app->request->post('wheel_distance_f');//前轮距
			$wheel_distance_b = yii::$app->request->post('wheel_distance_b');//后轮距
			if($wheel_distance_f == '') {
				$wheel_distance_f = 0;
			}
			if($wheel_distance_b == '') {
				$wheel_distance_b = 0;
			}
			$cubage = yii::$app->request->post('cubage');//容积
			if($cubage == '') {
				$cubage = 0;
			}
			$approach_angle = yii::$app->request->post('approach_angle');//接近角
			if($approach_angle == '') {
				$approach_angle = 0;
			}
			$departure_angle = yii::$app->request->post('departure_angle');//离去角
			if($departure_angle == '') {
				$departure_angle = 0;
			}
			$total_mass = yii::$app->request->post('total_mass');//总质量
			if($total_mass == '') {
				$total_mass = 0;
			}
			$empty_mass = yii::$app->request->post('empty_mass');//整备质量
			if($empty_mass == '') {
				$empty_mass = 0;
			}
			$check_mass = yii::$app->request->post('check_mass');//额定载重质量
			if($check_mass == '') {
				$check_mass = 0;
			}
			$cab_passenger = yii::$app->request->post('cab_passenger');//驾驶室乘客数量
			$wheel_specifications = yii::$app->request->post('wheel_specifications');//轮胎型号
			/*$wheel_amount = yii::$app->request->post('wheel_amount');//轮胎数量
			if($wheel_amount == '') {
				$wheel_amount = 0;
			}*/
			$engine_model = yii::$app->request->post('engine_model');//发动机型号
			$fuel_type = yii::$app->request->post('fuel_type');//燃料形式
			$displacement = yii::$app->request->post('displacement');//排量
			if($displacement == '') {
				$displacement = 0;
			}
			$endurance_mileage = yii::$app->request->post('endurance_mileage');//工部续航里程
			if($endurance_mileage == '') {
				$endurance_mileage = 0;
			}
			$rated_power = yii::$app->request->post('rated_power');//驱动电机额定功率
			if($rated_power == '') {
				$rated_power = 0;
			}
			$peak_power = yii::$app->request->post('peak_power');//驱动电机峰值功率
			if($peak_power == '') {
				$peak_power = 0;
			}
			$power_battery_capacity = yii::$app->request->post('power_battery_capacity');//动力电池容量kW
			if($power_battery_capacity == '') {
				$power_battery_capacity = 0;
			}
			$power_battery_manufacturer = yii::$app->request->post('power_battery_manufacturer');//动力电池生产厂家
			$drive_motor_manufacturer = yii::$app->request->post('drive_motor_manufacturer');//驱动电机生产厂家
			$max_speed = yii::$app->request->post('max_speed');//最高车速
			if($max_speed == '') {
				$max_speed = 0;
			}
			$fast_charging_time = yii::$app->request->post('fast_charging_time');//充电时间 快
			if($fast_charging_time == '') {
				$fast_charging_time = 0.0;
			}
			$slow_charging_time = yii::$app->request->post('slow_charging_time');//充电时间 慢
			if($slow_charging_time == '') {
				$slow_charging_time = 0.0;
			}
			$charging_type = yii::$app->request->post('charging_type');//充电方式
			//图片
			$car_front_img = yii::$app->request->post('car_front_img');//车头图片
			$car_left_img = yii::$app->request->post('car_left_img');//左侧车身图片
			$car_right_img = yii::$app->request->post('car_right_img');//右侧车身图片
			$car_tail_img = yii::$app->request->post('car_tail_img');//车尾图片
			$car_control_img = yii::$app->request->post('car_control_img');//中控图片
			$car_full_img = yii::$app->request->post('car_full_img');//全车图片

			$inside_long = yii::$app->request->post('inside_long');
			if($inside_long == '') {
				$inside_long = 0;
			}
			$inside_width = yii::$app->request->post('inside_width');
			if($inside_width == '') {
				$inside_width = 0;
			}
			$inside_height = yii::$app->request->post('inside_height');
			if($inside_height == '') {
				$inside_height = 0;
			}
			$car_model_name_ = yii::$app->request->post('car_model_name_');//车型名称
			//var_dump($car_model_name_);exit;
			$reg_record = $connection->createCommand()->insert('cs_car_type', [
						'brand_id' => $brand_id,
						'car_type' => $car_type,
						'car_model' => $car_model,
						'manufacturer_name' => $manufacturer_name,
						//'use_nature' => $use_nature,
						'outside_long' => $outside_long,
						'outside_width' => $outside_width,
						'outside_height' => $outside_height,
						'shaft_distance' => $shaft_distance,
						'wheel_distance_f' => $wheel_distance_f,
						'wheel_distance_b' => $wheel_distance_b,
						'cubage' => $cubage,

						'approach_angle' => $approach_angle,
						'departure_angle' => $departure_angle,
						'total_mass' => $total_mass,
						'empty_mass' => $empty_mass,
						'check_mass' => $check_mass,
						'cab_passenger' => $cab_passenger,
						'wheel_specifications' => $wheel_specifications,
						//'wheel_amount' => $wheel_amount,
						'engine_model' => $engine_model,
						'fuel_type' => $fuel_type,

						'displacement' => $displacement,
						'endurance_mileage' => $endurance_mileage,
						'rated_power' => $rated_power,
						'peak_power' => $peak_power,
						'power_battery_capacity' => $power_battery_capacity,
						'power_battery_manufacturer' => $power_battery_manufacturer,
						'drive_motor_manufacturer' => $drive_motor_manufacturer,
						'max_speed' => $max_speed,
						'fast_charging_time' => $fast_charging_time,
						'slow_charging_time' => $slow_charging_time,
						'charging_type' => $charging_type,
						'add_time'=>$add_time,
						'add_aid'=>$add_aid,

						'car_front_img'=>$car_front_img,
						'car_left_img'=>$car_left_img,
						'car_right_img'=>$car_right_img,
						'car_tail_img'=>$car_tail_img,
						'car_control_img'=>$car_control_img,
						'car_full_img'=>$car_full_img,

						'inside_long'=>$inside_long,
						'inside_width'=>$inside_width,
						'inside_height'=>$inside_height,
						'car_model_name'=>$car_model_name_

						])->execute();
			if($reg_record){
				$reg_record_id = Yii::$app->db->getLastInsertID();
				$this->synData($reg_record_id);//同步新增数据到菜鸟
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
            'car_type','use_nature','car_color','battery_type','gain_year',
            'car_model_name',
            'import_domestic','fuel_type','turn_type','gain_way','modified_car_type'
        ];
        $configCategoryModel = new ConfigCategory();
        $config = $configCategoryModel->getCategoryConfig($configItems,'value');
        //echo '<pre>';
        //var_dump($config['fuel_type']);
        //var_dump($config['use_nature']);
       // exit;
		return $this->render('add',['config'=>$config]);
	}
	//同步车型数据到菜鸟
	public function synData($reg_record) {
		
		$connection = yii::$app->db;
		//车辆模板添加成功后，
		$car_type_category_id = 11;
		$car_model_category_id = 62;
		// $reg_record = 5;
		//查询要同步的车型数据
		//t.brand_id as vehicle_brand_id,j.id as vehicle_model_id,
		$sql = "
				SELECT 
				t.power_battery_capacity as battery_capacity,
				
				b.name as brand_name,
				i.text as vehicle_type, 
				j.text as vehicle_model,
				t.*

				FROM `cs_car_type` as t
							LEFT JOIN cs_car_brand as b on b.id=t.brand_id 
							LEFT JOIN cs_config_item as i on i.value=t.car_type and i.belongs_id=$car_type_category_id 
							LEFT JOIN cs_config_item as j on j.value=t.car_model and j.belongs_id=$car_model_category_id 
					  WHERE t.is_del=0
								AND t.id=$reg_record			
				";	

		$data = $connection->createCommand($sql)->queryOne();		
		if ($data) {
			$aliDaYuDir = dirname(dirname(getcwd())).'/extension/taobao-sdk-PHP-daily_bg_type';
			include_once($aliDaYuDir.'/TopSdk.php');	
			$c = new \TopClient;  
			$c->gatewayUrl = 'http://gw.api.tbsandbox.com/router/rest';
			$c->appkey = '1023717193';
			$c->secretKey = 'sandbox15945c74b3dcd70a2d5f6d861';
			$req = new \CainiaoLvsVmsVehiclemodelUploadRequest(); 
			$model = new \VehicleModels;
			//var_dump($req);
			
			//一些没有但是必须要传的字段
			$model->approved_passenger_quality = "1";
			$model->battery_model = "1";
			$model->branch_name = "-";
			$model->city = "-";
			$model->domestic = "true";
			$model->motor_controller_model = "1";
			$model->motor_model = "1";
			$model->number_of_axes = "1";
			$model->number_of_leaf_springs = "1";
			$model->number_of_tires = "1";
			$model->total_weight_of_quasitraction = "1";
			
			/** 
			 * 电池容量
			 **/
			$model->battery_capacity = $data['battery_capacity'];
			/** 
			 * 前轮距s 1
			 **/
			 $model->front_track = $data['wheel_distance_f'];
			/** 
			 * 车厢内高s 1
			 **/
			 $model->height_of_interior = $data['inside_height'];
			/** 
			 * 外廓高s 1
			 **/
			 $model->height_of_outer = $data['outside_height'];
			/** 
			 * 车厢内长s 1
			 **/
			 $model->length_of_interior = $data['inside_long'];
			/** 
			 * 外廓长s 1
			 **/
			 $model->length_of_outer = $data['outside_long'];
			
			/** 
			 * 租赁公司名称s n
			 **/
			 $model->lessor_name = '地上铁租赁';
			/** 
			 * 载重量s 1
			 **/
			 $model->load_capacity = $data['check_mass'];
			/** 
			 * 制造商s n
			 **/
			 $model->manufacturer = $data['manufacturer_name'];
			/** 
			 * 快充最大时间s 1
			 **/
			 $model->max_time_of_fast_charge = $data['fast_charging_time'];
			/** 
			 * 慢充最大时间s 1
			 **/
			 $model->max_time_of_slow_charge = $data['slow_charging_time'];
			/** 
			 * 快充最小时间s 1.5
			 **/
			 $model->minimum_time_of_fast_charge = $data['fast_charging_time'];
			/** 
			 * 慢充最小时间s 1.5
			 **/
			 $model->minimum_time_of_slow_charge = $data['slow_charging_time'];
			
			/** 
			 * 驾驶室准坐人数i 1
			 **/
			 $model->number_of_cab_passengers = $data['cab_passenger'];
			
			/** 
			 * 后轮距s 1
			 **/
			 $model->rear_track = $data['wheel_distance_b'];
			/** 
			 * 续航里程s 1
			 **/
			 $model->recharge_mileage = $data['endurance_mileage'];
			/** 
			 * 轮胎规格s 1*1
			 **/
			 $model->tire_specifications = $data['wheel_specifications'];
			/** 
			 * 总重量s 1
			 **/
			 $model->total_weight = $data['total_mass'];
			
			/** 
			 * ---车辆品牌s ev80
			 **/
			 $model->vehicle_brand = $data['brand_name'];
			/** 
			 * 车辆品牌id i 
			 **/
			// $model->vehicle_brand_id = $data['brand_id'];
			/** 
			 * ---车辆型号s n key
			 **/
			 $model->vehicle_model = $data['vehicle_model'];
			
			/** 
			 * 车型 ? s n --
			 **/
			 $model->vehicle_pattern = $data['car_model'];
			/** 
			 * -- 车辆类型s n
			 **/
			 $model->vehicle_type = $data['vehicle_type']; 
			/** 
			 * 车辆容积s 1
			 **/
			 $model->vehicle_volume = $data['cubage'];
			/** 
			 * 轴距s 1
			 **/
			 $model->wheelbase = $data['shaft_distance'];
			/** 
			 * 车厢内宽s 1
			 **/
			 $model->width_of_interior = $data['inside_width'];
			 /** 
			 * 租赁公司码i 1
			 **/
			 $model->lessor_code = "1";
			 //$model->name = "test456";
			
			/** 
			 * 外廓宽s 1
			 **/
			 $model->width_of_outer = $data['outside_width'];
			 $req->setVehicleModels(json_encode($model));
			//var_dump($req);exit;
			// var_dump($req->getVehicleModels());exit;
			//执行传输
			$vmsResultBo = $c->execute($req);
			// var_dump($vmsResultBo);		exit;
			if (isset($vmsResultBo->data) && $vmsResultBo->data == true) {//同步成功
				//echo "数据同步成功".$reg_record;
			} else if(isset($vmsResultBo->code)){    //同步失败				
				//echo "数据同步失败!错误代码：".$vmsResultBo->code.'描述：'.$vmsResultBo->msg;
			} else {
				//echo "未知错误，数据同步失败!";
			}			
		}				
	}

	//列表显示
	public function actionGetList(){


		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;

		$query = (new \yii\db\Query())->select([
    			'a.*',
    			'b.name as brand_name',
    			'c.name as username'
    			])->from('cs_car_type a')
    			->leftJoin('cs_car_brand b', 'a.brand_id = b.id')
    			->leftJoin('cs_admin c', 'a.add_aid = c.id')
    			 ->where(['a.is_del'=>0])
				;
		$query->andFilterWhere(['=','a.`car_type`',yii::$app->request->get('car_type')]);
		//查品牌，查父品牌时也会查出子品牌
        if(yii::$app->request->get('brand_id')){
            $brand_id = yii::$app->request->get('brand_id');
            $query->andFilterWhere([
                'or',
                ['a.`brand_id`'=>$brand_id],
               /* ['{{%car_brand}}.`pid`'=>$brand_id]*/
            ]);
        }
		$total = $query->count();
    	$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
    	$data = $query->offset($pages->offset)->limit($pages->limit)->all();
    	//echo '<pre>';
    	//var_dump($data);exit;
    	foreach($data as $key =>$value){
    		//$value;
    		$data[$key]['outside_ckg']= $value['outside_long'].'*'.$value['outside_width'].'*'.$value['outside_height'];
    		//$value['brand_id']
    		$car_code = CarBrand::find()->where(['id'=>$value['brand_id']])->asArray()->one();
    		$data[$key]['c_code']='DST-'.$car_code['code'].$value['id'];
    		
    		$configItems = ['fuel_type'];
	        $configCategoryModel = new ConfigCategory();
	        $config = $configCategoryModel->getCategoryConfig($configItems,'value');
	        //echo '<pre>';
    		//var_dump($config['fuel_type'][$value['fuel_type']]['text']);exit;
    		$data[$key]['fuel_type']= $config['fuel_type'][$value['fuel_type']]['text'];


    	}
    	//echo '<pre>';
    	//var_dump($data);exit;
    	$returnArr = [];
    	$returnArr['rows'] = $data;
    	$returnArr['total'] = $total;
  
    	return json_encode($returnArr);
	}

	//查看详情
	public function actionScan()
	{
		$id = yii::$app->request->get('id') or die('param id is required');
		$query = (new \yii\db\Query())->select([
    			'a.*',
    			'b.name as brand_name',
    			//'c.name as username'
    			])->from('cs_car_type a')->where(['a.id'=>$id])
    			->leftJoin('cs_car_brand b', 'a.brand_id = b.id')
    			//->leftJoin('cs_admin c', 'a.add_aid = c.id')
				;
		//$total = $query->count();
    	//$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
    	$data = $query->one();
    	
    	 //配置项
        $configItems = [
            'car_status','fault_status','car_type','use_nature',
            'car_model_name','car_color','import_domestic','fuel_type','turn_type','gain_way','DL_REG_ADDR','TC_ISSUED_BY',
            'battery_type','connection_type','battery_spec','encoder','','cooling_type'
        ];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        //处理车辆名称
        /*$data['car_model_name'] = '';
        if(isset($config['car_model_name'][$data['car_model']])){
            $data['car_model_name'] = $config['car_model_name'][$data['car_model']]['text'];
        }*/
		return $this->render('scan',[
			'data'=>$data,
			'config'=>$config
			]);
	}

	public function actionEdit() {
		//echo '125';exit;
		if(yii::$app->request->isPost){
			//*********************

			$id = intval(yii::$app->request->post('id')) or die('param id is required');
			//echo '12';exit;

            $model = CarType::findOne(['id'=>$id]);
            $model or die('record not found');
            $add_aid = $model->add_aid = $_SESSION['backend']['adminInfo']['id'];
            $formData = yii::$app->request->post();
            //echo '<pre>';
            //var_dump($formData);exit;
            $formData['add_aid'] = $add_aid;
           // var_dump($formData);exit;
            $model->load($formData,'');

            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){

                if($model->save(false)){
		    $this->synData($id);//同步修改数据到菜鸟
                    $returnArr['status'] = true;
                    $returnArr['info'] = '修改成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '数据保存失败！';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    foreach($error as $val){
                        $returnArr['info'] .= $val[0];
                    }
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr);
		} 


		$id = intval(yii::$app->request->get('id'));
		if(!$id){
            die('param id is required');
        }
        if($id){
            $model = CarType::find()			
			
			 ->select([
                '{{%car_type}}.*',
   
            ])
            //->leftJoin('{{%car}}','{{%maintain_record}}.car_id = {{%car}}.id')
            ->where(['{{%car_type}}.id' => $id,'{{%car_type}}.is_del'=>0])->one();
	
       // }else {
        //    $model = MaintainRecord::find()->where(['car_id' => $carId,'is_del'=>0])->orderBy('add_time desc')->one();
        }
       $model or die('record not found');	
       //echo '<pre>';
       //var_dump($model->getOldAttributes());exit;
       //获取配置数据
        $configItems = [
            'car_status','car_type','use_nature','car_color','battery_type','gain_year',
            'car_model_name',
            'import_domestic','fuel_type','turn_type','gain_way','modified_car_type'
        ];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
        //echo '<pre>';
        //var_dump($config);exit;

		return $this->render('edit',[
			 'row'=>$model->getOldAttributes(),
			 'config'=>$config,
			]);
	}

	/**
     * 删除保养类型
     */
    public function actionRemove()
    {
		//echo "hi";
    	$id = yii::$app->request->get('id') or die('param id is required');
    	$model = CarType::findOne(['id'=>$id]);
    	$model or die('record not found');
    	$returnArr = [];
    	$returnArr['status'] = true;
    	$returnArr['info'] = '';
    	if(CarType::updateAll(['is_del'=>1],['id'=>$id])){
    		$returnArr['status'] = true;
    		$returnArr['info'] = '记录删除成功！';
    	}else{
    		$returnArr['status'] = false;
    		$returnArr['info'] = '记录删除失败！';
    	}
    	echo json_encode($returnArr);
    }
}