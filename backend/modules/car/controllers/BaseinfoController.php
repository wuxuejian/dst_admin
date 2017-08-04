<?php
/**
 * 车辆基本信息控制器
 * @author wangmin
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
class BaseinfoController extends BaseController
{
    public function actionIndex()
    {	  
        //echo '111';exit;	
        //获取本页按钮
        $buttons = $this->getCurrentActionBtn(); 
        //获取配置数据
        $configItems = ['car_status','gain_year','gain_way','car_type','use_nature','car_color','car_model_name','car_status2'];
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
        
        if($config['gain_way']){
        	$searchFormOptions['gain_way'] = [];
        	$searchFormOptions['gain_way'][] = ['value'=>'','text'=>'不限'];
        	foreach($config['gain_way'] as $val){
        		$searchFormOptions['gain_way'][] = ['value'=>$val['value'],'text'=>$val['text']];
        	}
        }
        if($config['gain_year']){
        	$searchFormOptions['gain_year'] = [];
        	$searchFormOptions['gain_year'][] = ['value'=>'','text'=>'不限'];
        	foreach($config['gain_year'] as $val){
        		$searchFormOptions['gain_year'][] = ['value'=>$val['value'],'text'=>$val['text']];
        	}
        }
        //echo '111';exit;  
        //二级状态   by 2016/11/29
        if($config['car_status2']){
        	$searchFormOptions['car_status2'] = [];
        	$searchFormOptions['car_status2'][] = ['value'=>'','text'=>'不限'];
        	foreach($config['car_status2'] as $val){
        		$searchFormOptions['car_status2'][] = ['value'=>$val['value'],'text'=>$val['text']];
        	}
        }
        
        
        //车辆运营公司、车辆类型 by  2016/9/20
        //车辆类型
        if($config['car_type'])
        {
        	$searchFormOptions['car_type'] = [];
        	$searchFormOptions['car_type'][] = ['value'=>'','text'=>'不限'];
        	foreach($config['car_type'] as $val){
        		$searchFormOptions['car_type'][] = ['value'=>$val['value'],'text'=>$val['text']];
        	}
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
        
        return $this->render('index',[
            'buttons'=>$buttons,
            'config'=>$config,
            'searchFormOptions'=>$searchFormOptions,
        ]);
    }
    
    /**
     * 获取车辆列表
     */
    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = Car::find()
            ->select([
                '{{%car}}.`id`',
                '{{%car}}.`plate_number`',
                '{{%car}}.`vehicle_dentification_number`',
                '{{%car}}.`engine_number`',
                '{{%car}}.`car_status`',
            	'{{%car}}.`car_status2`',
                '{{%car}}.`car_type`',
                '{{%car}}.`car_color`',
            	'{{%car}}.`car_model`',
            	'{{%car}}.`modified_type`',
                'transact_dl'=>'{{%car_driving_license}}.`id`',
                'transact_rtc'=>'{{%car_road_transport_certificate}}.`id`',
                'transact_sm'=>'{{%car_second_maintenance}}.`id`',
                'transact_ic'=>'{{%car_insurance_compulsory}}.`id`',
                'transact_ib'=>'{{%car_insurance_business}}.`id`',
                '{{%car}}.`add_time`',
                '{{%admin}}.`username`',
                '{{%car}}.`note`',
                'car_brand'=>'{{%car_brand}}.`name`',
                '{{%car_type}}.`car_model_name`'
            ])
            ->leftJoin('{{%car_driving_license}}','{{%car}}.id = {{%car_driving_license}}.car_id and {{%car_driving_license}}.valid_to_date>'.time())
            ->leftJoin('{{%car_road_transport_certificate}}','{{%car}}.id = {{%car_road_transport_certificate}}.car_id and {{%car_road_transport_certificate}}.next_annual_verification_date>'.time())
            ->joinWith('carSecondMaintenance',false)
            ->leftJoin('{{%car_insurance_compulsory}}','{{%car}}.id = {{%car_insurance_compulsory}}.car_id and {{%car_insurance_compulsory}}.is_del=0 and {{%car_insurance_compulsory}}.end_date>'.time())
            ->leftJoin('{{%car_insurance_business}}','{{%car}}.id = {{%car_insurance_business}}.car_id and {{%car_insurance_business}}.is_del=0 and {{%car_insurance_business}}.end_date>'.time())
            ->leftJoin('{{%car_type}}','{{%car}}.car_type_id={{%car_type}}.id')
            ->joinWith('admin',false)
            ->joinWith('carBrand',false)
            ->andWhere(['{{%car}}.`is_del`'=>0]);
        
		////其他查询条件
		$query->andFilterWhere(['like','{{%car}}.`plate_number`',yii::$app->request->get('plate_number')]);
		$query->andFilterWhere(['like','{{%car}}.`vehicle_dentification_number`',yii::$app->request->get('vehicle_dentification_number')]);
		$query->andFilterWhere(['=','{{%car}}.`car_status`',yii::$app->request->get('car_status')]);
		$query->andFilterWhere(['=','{{%car}}.`car_status2`',yii::$app->request->get('car_status2')]);
		//车辆运营公司、车辆类型 by  2016/9/20
		$query->andFilterWhere(['=','{{%car}}.`operating_company_id`',yii::$app->request->get('operating_company_id')]);
		$query->andFilterWhere(['=','{{%car}}.`car_type`',yii::$app->request->get('car_type')]);
		$query->andFilterWhere(['=','{{%car}}.`gain_way`',yii::$app->request->get('gain_way')]);//车辆获得方式
		if(yii::$app->request->get('gain_year')){//车辆购买年份
			$query->andFilterWhere(['=','{{%car}}.`gain_year`',yii::$app->request->get('gain_year')]);
		}
		
        //查品牌，查父品牌时也会查出子品牌
        if(yii::$app->request->get('brand_id')){
            $brand_id = yii::$app->request->get('brand_id');
            $query->andFilterWhere([
                'or',
                ['{{%car_brand}}.`id`'=>$brand_id],
                ['{{%car_brand}}.`pid`'=>$brand_id]
            ]);
        }
        if(yii::$app->request->get('owner_id')){
        	$owner_id = yii::$app->request->get('owner_id');
        	$query->andFilterWhere(['{{%car}}.`owner_id`'=>$owner_id]);
        }
        
        //是否办理行驶证
        $transactDl = intval(yii::$app->request->get('transact_dl'));
        if($transactDl == 1){
            //查询已经办理
            $query->andWhere('{{%car_driving_license}}.`id` IS NOT NULL');
        }elseif($transactDl == 2){
            //查询未办理
            $query->andWhere('{{%car_driving_license}}.`id` IS NULL');
        }
        //是否办理道路运输证
        $transactRtc = intval(yii::$app->request->get('transact_rtc'));
        if($transactRtc == 1){
            //查询已经办理
            $query->andWhere('{{%car_road_transport_certificate}}.`id` IS NOT NULL');
        }elseif($transactRtc == 2){
            //查询未办理
            $query->andWhere('{{%car_road_transport_certificate}}.`id` IS NULL');
        }
        //是否办理交强险
        $transactRtc = intval(yii::$app->request->get('transact_ic'));
        if($transactRtc == 1){
            //查询已经办理
            $query->andWhere('{{%car_insurance_compulsory}}.`id` IS NOT NULL');
        }elseif($transactRtc == 2){
            //查询未办理
            $query->andWhere('{{%car_insurance_compulsory}}.`id` IS NULL');
        }
        //是否办理商业险
        $transactRtc = intval(yii::$app->request->get('transact_ib'));
        if($transactRtc == 1){
            //查询已经办理
            $query->andWhere('{{%car_insurance_business}}.`id` IS NOT NULL');
        }elseif($transactRtc == 2){
            //查询未办理
            $query->andWhere('{{%car_insurance_business}}.`id` IS NULL');
        }
        //是否办理二级维护卡
        $transactRtc = intval(yii::$app->request->get('transact_sm'));
        if($transactRtc == 1){
            //查询已经办理
            $query->andWhere('{{%car_second_maintenance}}.`id` IS NOT NULL');
        }elseif($transactRtc == 2){
            //查询未办理
            $query->andWhere('{{%car_second_maintenance}}.`id` IS NULL');
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
//         echo $query->createCommand()->getRawSql();exit;
        ////查询条件结束
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'username':
                    $orderBy = '{{%admin}}.`'.$sortColumn.'` ';
                    break;
                case 'transact_dl':
                    $orderBy = '{{%car_driving_license}}.`id` ';
                    break;
                case 'transact_ic':
                    $orderBy = '{{%car_insurance_compulsory}}.`id` ';
                    break;
                case 'transact_rtc':
                    $orderBy = '{{%car_road_transport_certificate}}.`id` ';
                    break;
                case 'transact_sm':
                    $orderBy = '{{%car_second_maintenance}}.`id` ';
                    break;
                case 'transact_ib':
                    $orderBy = '{{%car_insurance_business}}.`id` ';
                    break;
                default:
                    $orderBy = '{{%car}}.`'.$sortColumn.'` ';
                    break;
            }
        }else{
            $orderBy = '{{%car}}.`id` ';
        }
        $orderBy .= $sortType;
        $total = $query->groupBy('{{%car}}.`id`')->count();
//         echo $query->createCommand()->getRawSql();exit;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();	
        $returnArr = [];
        $returnArr['rows'] = $data; 
        $returnArr['total'] = $total;
      
        //echo '<pre>';
        //var_dump($data);exit;
        return json_encode($returnArr);
    }
    
    /**
     * 添加车辆基本信息
     */
    public function actionAdd(){
        //data submit start
        if(yii::$app->request->isPost){
            $model = new Car();
            $post = yii::$app->request->post();
            if(!empty($post['modified_type'])){
            	$post['modified_type'] = implode(',', $post['modified_type']);
            }
            $model->load($post,'');
            $model->operating_company_id = $_SESSION['backend']['adminInfo']['operating_company_id'];
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
            	//检查车牌号、车架号、发动机号是否已存在
                if(yii::$app->request->post('plate_number')){
                    $car = Car::find()->where(
                        '(plate_number = :plate_number or 
                        vehicle_dentification_number = :vehicle_dentification_number or
                        engine_number = :engine_number) and is_del=0', 
                        [
                            ':plate_number' => yii::$app->request->post('plate_number'), 
                            ':vehicle_dentification_number' => yii::$app->request->post('vehicle_dentification_number'),
                            ':engine_number' => yii::$app->request->post('engine_number')
                        ])->one();
                }else {
                    $car = Car::find()->where(
                        '(vehicle_dentification_number = :vehicle_dentification_number or
                        engine_number = :engine_number) and is_del=0', 
                        [
                            ':vehicle_dentification_number' => yii::$app->request->post('vehicle_dentification_number'),
                            ':engine_number' => yii::$app->request->post('engine_number')
                        ])->one();
                }
            	
            	if($car){
            		$returnArr['status'] = false;
            		$returnArr['info'] = '车辆已存在！';
            		return json_encode($returnArr);
            	}
//             	exit($car->createCommand()->getRawSql());
            	
//                 $model->car_status = 'NAKED';
                $model->add_time = time();
                $model->add_aid = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                	$car_id = $model->attributes['id'];
                	
                	$status_ret = Car::changeCarStatusNew($car_id, 'NAKED', 'car/baseinfo/add', '添加车辆基本信息');
                	if($status_ret['status']){
                		$returnArr['status'] = true;
                		$returnArr['info'] = '车辆信息添加成功！';
                	}else {
                		$returnArr['status'] = false;
                		$returnArr['info'] = '车辆信息添加成功，车辆状态初始化失败！，log:'.$status_ret['info'];
                	}
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
                    $returnArr['info'] = '未知错误！';
                }
            }
            return json_encode($returnArr);
        }
        //data submit end
        
        //获取配置数据
        $configItems = [
            'car_type','use_nature','car_color','battery_type','gain_year',
            'car_model_name',
            'import_domestic','fuel_type','turn_type','gain_way','modified_car_type'
        ];
        $configCategoryModel = new ConfigCategory();
        $config = $configCategoryModel->getCategoryConfig($configItems,'value');
        $buttons = $this->getCurrentActionBtn();
        $buttonAutoComplete = [];//获取自动完成按钮
        if($buttons){
            foreach ($buttons as $value) {
                if($value['target_mca_code'] == 'car/baseinfo/auto-complete'){
                    $buttonAutoComplete = $value;
                    break;
                }
            }
        }
        //获取最近一次添加的车辆的vin
        $lastCarVin = Car::find()->select(['vehicle_dentification_number'])
            ->where(['is_del'=>0])
            ->andWhere(['<>','vehicle_dentification_number',''])
            ->orderBy('`id` desc')->limit('1')->one();

        /* echo '<pre>';
        var_dump($config);
        die; */
        
        return $this->render('add',[
            'config'=>$config,
            'buttonAutoComplete'=>$buttonAutoComplete,
            'last_car_vin'=>$lastCarVin['vehicle_dentification_number'],
        ]);
    }

    /**
     * 添加车辆时自动完成表单
     */
    public function actionAutoComplete(){
        $returnArr = [];
        $returnArr['status'] = false;
        $vin = yii::$app->request->get('vehicle_dentification_number');
        if(!$vin){
            $returnArr['info'] = '请输入车架号！';
            return json_encode($returnArr);
        }
        $columns = (new Car)->getAttributes();
        unset($columns['id']);
        unset($columns['plate_number']);
        unset($columns['vehicle_dentification_number']);
        unset($columns['engine_number']);
        unset($columns['is_trial']);
        unset($columns['note']);
        unset($columns['is_del']);
        unset($columns['reg_number']);
        $carInfo = Car::find()
            ->select(array_keys($columns))
            ->where(['vehicle_dentification_number'=>$vin])
            ->orderBy('`id` desc')->asArray()->one();
        if($carInfo){
            $returnArr['status'] = true;
            $carInfo['reg_date'] = $carInfo['reg_date'] ? date('Y-m-d',$carInfo['reg_date']) : '';
            $carInfo['leave_factory_date'] = $carInfo['leave_factory_date'] ? date('Y-m-d',$carInfo['leave_factory_date']) : '';
            $carInfo['issuing_date'] = $carInfo['issuing_date'] ? date('Y-m-d',$carInfo['issuing_date']) : '';
            $returnArr['info'] = $carInfo;
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '没有找到车辆：'.$vin.'的基本信息！';
        }
        return json_encode($returnArr);
    }
    
    //查看车辆数据
    public function actionScan(){
        $id = yii::$app->request->get('id') or die('param id is required');
        //查询车辆基本信息
        //$car = Car::find()->where(['id'=>$id])->asArray()->one();
        $car = (new \yii\db\query())
            ->select([
                    'cs_car.*',
                    /*'cs_car_type.car_model as car_model_',
                    'cs_car_type.car_model_name'*/
                    'cs_car_type.*'
                    ])
            ->leftJoin('cs_car_type','cs_car_type.id=cs_car.car_type_id')
            ->from('cs_car')->where(['cs_car.id'=>$id])->one();
        /*echo '<pre>';
        var_dump($car);exit;*/
        if(empty($car)){
            return false;
        }
        $owner = Owner::findOne($car['owner_id']);
        if($owner){
            $car['owner_id'] = $owner->name;
        }
        $operatingCompany = OperatingCompany::findOne($car['operating_company_id']);
        if($operatingCompany){
            $car['operating_company_id'] = $operatingCompany->name;
        }else{
            $car['operating_company_id'] = '';
        }
        //查询车辆行驶证信息
        $drivingLicense = CarDrivingLicense::findOne(['car_id'=>$id]);
        //查询车辆道路运输证信息
        $roadTransportCertificate = CarRoadTransportCertificate::findOne(['car_id'=>$id]);
        //三电系统信息
        $threeElectricSystem = ['battery'=>[],'motor'=>[],'motor_monitor'=>[]];
        if($car['battery_model']){
            $threeElectricSystem['battery'] = Battery::find()
                ->where(['battery_model'=>$car['battery_model']])
                ->asArray()->one();
        }
        if($car['motor_model']){
            $threeElectricSystem['motor'] = Motor::find()
                ->where(['motor_model'=>$car['motor_model']])
                ->asArray()->one();
        }
        if($car['motor_monitor_model']){
            $threeElectricSystem['motor_monitor'] = MotorMonitor::find()
                ->where(['motor_monitor_model'=>$car['motor_monitor_model']])
                ->asArray()->one();
        }
        //查询车辆品牌
        $car['brand_name'] = '';
        if($car['brand_id']){
            $carBrand = CarBrand::find()
                ->select(['name'])
                ->where(['id'=>$car['brand_id']])
                ->limit(1)->asArray()->one();
            if(!empty($carBrand)){
                $car['brand_name'] = $carBrand['name'];
            }
            unset($carBrand);
        }
        
        //配置项
        $configItems = [
            'car_status','fault_status','car_type','use_nature',
            'car_model_name','car_color','import_domestic','fuel_type','turn_type','gain_way','DL_REG_ADDR','TC_ISSUED_BY',
            'battery_type','connection_type','battery_spec','encoder','','cooling_type'
        ];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        //处理车辆名称
        /*$car['car_model_name'] = '';
        if(isset($config['car_model_name'][$car['car_model']])){
            $car['car_model_name'] = $config['car_model_name'][$car['car_model']]['text'];
        }*/
        return $this->render('scan',[
            'config'=>$config,
            'car'=>$car,
            'drivingLicense'=>$drivingLicense,
            'roadTransportCertificate'=>$roadTransportCertificate,
            'threeElectricSystem'=>$threeElectricSystem,
        ]);
    }


    //修改车辆信息
    public function actionEdit()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = intval(yii::$app->request->post('id')) or die('param id is required');
            $model = Car::findOne(['id'=>$id]) or die('data not found');
            //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
            $checkArr = Car::checkOperatingCompanyIsMatch($id);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $model->setScenario('edit');
            $post = yii::$app->request->post();
            if(!empty($post['modified_type'])){
            	$post['modified_type'] = implode(',', $post['modified_type']);
            }
            
            $model->load($post,'');
            unset($model->car_status);
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
            	//检查车牌号、车架号、发动机号是否已存在
            	if(yii::$app->request->post('plate_number')){
            		$car = Car::find()->where(
            				'(plate_number = :plate_number or
            				vehicle_dentification_number = :vehicle_dentification_number or
            				engine_number = :engine_number) and is_del=0 and id <> :id',
            				[
            				':plate_number' => yii::$app->request->post('plate_number'),
            				':vehicle_dentification_number' => yii::$app->request->post('vehicle_dentification_number'),
            				':engine_number' => yii::$app->request->post('engine_number'),
            				':id' => yii::$app->request->post('id')
            				])->one();
            	}else {
            		$car = Car::find()->where(
            				'(vehicle_dentification_number = :vehicle_dentification_number or
            				engine_number = :engine_number) and is_del=0 and id <> :id',
            				[
            				':vehicle_dentification_number' => yii::$app->request->post('vehicle_dentification_number'),
            				':engine_number' => yii::$app->request->post('engine_number'),
            				':id' => yii::$app->request->post('id')
            				])->one();
            	}
            	/*if($car){
            		$returnArr['status'] = false;
            		$returnArr['info'] = '车辆已存在！';
            		return json_encode($returnArr);
            	}*/
            	//             	exit($car->createCommand()->getRawSql());
            	
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '车辆信息修改成功！';
                    $plateNumber = $model->getOldAttribute('plate_number');
                    UserLog::log("修改车辆：{$plateNumber}，基本信息！",'sys'); 
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
                    $returnArr['info'] = '未知错误！';
                }
            }
            echo json_encode($returnArr);
            return null;
        }
        //data submit end
        $id = intval(yii::$app->request->get('id')) or die('param id is required');
       // var_dump($id);exit;
        //获取配置数据
        $configItems = [
            'car_status','car_type','use_nature','car_color','battery_type','gain_year',
            'car_model_name',
            'import_domestic','fuel_type','turn_type','gain_way','modified_car_type'
        ];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');


        //$model = Car::findOne(['id'=>$id]) or die('record not found');
        
        $carInfo = Car::find()->select([
                                        '{{%car}}.*',
                                        //'{{%car_type}}.*',
                                        '{{%car_type}}.car_model',
                                        '{{%car_type}}.car_model_name',
                                        '{{%car_type}}.engine_model',
                                        '{{%car_type}}.fuel_type',
                                        '{{%car_type}}.displacement',
                                        '{{%car_type}}.peak_power',
                                        '{{%car_type}}.rated_power',
                                        '{{%car_type}}.endurance_mileage',
                                        '{{%car_type}}.manufacturer_name',
                                        '{{%car_type}}.wheel_distance_f',
                                        '{{%car_type}}.wheel_distance_b',
                                        '{{%car_type}}.wheel_specifications',
                                        '{{%car_type}}.shaft_distance',
                                        '{{%car_type}}.outside_long',
                                        '{{%car_type}}.outside_width',
                                        '{{%car_type}}.outside_height',
                                        '{{%car_type}}.total_mass',
                                        '{{%car_type}}.check_mass',
                                        '{{%car_type}}.cab_passenger',
                                        //'{{%car_type}}.add_time',
                                        //'{{%car_type}}.add_aid',
                                        '{{%car_type}}.cubage',
                                        '{{%car_type}}.approach_angle',
                                        '{{%car_type}}.departure_angle',
                                        '{{%car_type}}.empty_mass',
                                        '{{%car_type}}.power_battery_capacity',
                                        '{{%car_type}}.power_battery_manufacturer',
                                        '{{%car_type}}.drive_motor_manufacturer',
                                        '{{%car_type}}.max_speed',
                                        '{{%car_type}}.fast_charging_time',
                                        '{{%car_type}}.slow_charging_time',
                                        '{{%car_type}}.charging_type',
                                        
                                        ])
                   ->leftJoin('{{%car_type}}', '{{%car_type}}.`id` = {{%car}}.`car_type_id` and cs_car_type.is_del=0')
                   ->where(['`cs_car`.id'=>$id])
                   ->asArray()
                   ->one();
         /*$carInfo = Car::find()->select(['{{%car22}}.*','{{%car_type}}.*',])->where(['`cs_car`.id'=>$id,'cs_car_type.is_del'=>0])
                   ->leftJoin('{{%car_type}}', '{{%car_type}}.`id` = {{%car}}.`car_type_id` ')

                   ->asArray()
                   ->one();*/
      //  var_dump($carInfo);exit;

        return $this->render('edit',[
            'config'=>$config,
            //'carInfo'=>$model->getOldAttributes(),
            'carInfo'=>$carInfo
        ]);
    }
    
    /**
     * 汽车基础信息删除
     */
    public function actionRemove()
    {
        $id = intval(yii::$app->request->get('id')) or die('param id is required');
        //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
        $checkArr = Car::checkOperatingCompanyIsMatch($id);
        if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
            return json_encode(['status'=>false,'info'=>$checkArr['info']]);
        }
        $returnArr = [];
        if(Car::updateAll(['is_del'=>1],['id'=>$id])){
            //删除车辆故障信息
            //CarFault::updateAll(['is_del'=>1],['car_id'=>$id]);
            $returnArr['status'] = true;
            $returnArr['info'] = '汽车数据删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '汽车数据删除失败！';
        }
        echo json_encode($returnArr);
    }

    /*
     * 检查行驶证和交强险是否齐全，若是则更改车辆状态由“裸车”变更为“库存”（不管是否到期）。
     */
    protected  function checkDrivingLicenseAndTrafficCompulsoryInsurance($carId){
        $DrivingLicense = CarDrivingLicense::find()->select(['id'])->where(['car_id'=>$carId])->asArray()->one();
        $InsuranceCompulsory = CarInsuranceCompulsory::find()->select(['id'])->where(['car_id'=>$carId,'is_del'=>0])->asArray()->one();
        if(!empty($DrivingLicense) && !empty($InsuranceCompulsory)){
        	Car::changeCarStatusNew($carId, 'STOCK', 'car/baseinfo/checkDrivingLicenseAndTrafficCompulsoryInsurance', '检查行驶证和交强险是否齐全',['car_status'=>'NAKED']);
        }
    }


     /**
     * 行驶证管理按钮
     */
   /* public function actionDrivingLicense()
    {
        $carId = yii::$app->request->get('carId') or die('param carId is required');
        $buttons = $this->getCurrentActionBtn();
        return $this->render('driving-license',[
            'carId'=>$carId,
            'buttons'=>$buttons
        ]);
    }*/

    public function actionDrivingLicense(){
        $carId = yii::$app->request->get('carId') or die('param carId is required');
        $buttons  = [
            ['text'=>'添加','on_click'=>'CarBaseinfoDrivingLicenseRecord.add()','icon'=>'icon-add'],
            ['text'=>'修改','on_click'=>'CarBaseinfoDrivingLicenseRecord.edit()','icon'=>'icon-edit'],
            //['text'=>'导出 excel','on_click'=>'CountDriverRelIndex.exportWidthCondition()','icon'=>'icon-add']
        ];
        
        return $this->render('driving-license',[
                'carId'=>$carId,
                'buttons'=>$buttons
                ]);
    }
    
    /**
     * 行驶证管理
     */
    public function actionAddDrivingLicense()
    {
        /**
        * 添加行驶证记录
        */
       
        if(yii::$app->request->isPost){

            $formData = yii::$app->request->post();
            //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
           $checkArr = Car::checkOperatingCompanyIsMatch($formData['car_id']);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $model = new CarDrivingLicense();
            $formData['register_date']= strtotime($formData['register_date']);
            $formData['issue_date']= strtotime($formData['issue_date']);
            $formData['force_scrap_date']= strtotime($formData['force_scrap_date']);
            $formData['valid_to_date']= strtotime($formData['valid_to_date']);
          
            $model->load($formData,'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';

            if($model->validate()){
                if(date('L',strtotime((date('Y',$model->issue_date) + 1).'-01-01')) == 1){
                    //下一年是润年
                    $model->next_valid_date = $model->issue_date + 31622400;
                }else{;
                    //下一年是平年
                    $model->next_valid_date = $model->issue_date + 31536000;
                }
                $model->add_datetime = time();
                $model->add_aid = $_SESSION['backend']['adminInfo']['id'];

                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '行驶证添加成功！';
                    //检查行驶证和交强险是否齐全，若是则更改车辆状态
                    $this->checkDrivingLicenseAndTrafficCompulsoryInsurance($formData['car_id']);
                    $carInfo = Car::find()
                        ->select(['plate_number'])
                        ->where(['id'=>$formData['car_id']])->asArray()->one();
                    UserLog::log("添加车辆：{$carInfo['plate_number']}，行驶证信息！",'sys'); 
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

            echo json_encode($returnArr);
            return null;
        }
        $carId = yii::$app->request->get('carId') or die('param carId is required');
        
        $config = (new ConfigCategory)->getCategoryConfig(['DL_REG_ADDR']);
        //echo '<pre>';
        //var_dump($config);exit;
        return $this->render('add-driving-license',[
            'carId'=>$carId,
            'config'=>$config,
            //'licenseInfo'=>$licenseInfo
        ]);
    
    }

     /**
     * 获取行驶证管理列表
     */
     public function actionGetDrivingLicenseList()
     {
        $id = yii::$app->request->get('carId') or die('param id is required');
        $number = yii::$app->request->get('number');
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;

        $connection = yii::$app->db;

        $query = CarDrivingLicense::find()
        ->select(['{{%car_driving_license}}.*'])
        /*->leftJoin('{{%car}}','{{%customer_driver_rel}}.car_id = {{%car}}.id and {{%car}}.is_del=0')*/
        ->andWhere(['=','{{%car_driving_license}}.`car_id`',$id])
        ;
        //查询条件
        //$query->andFilterWhere(['like','{{%car_driving_license}}.`archives_number`',yii::$app->request->get('archives_number')]);
        if($number){
            $query->andFilterWhere([
                    'like',
                    '{{%car_driving_license}}.`archives_number`',
                    $number
                    ]);
        }
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        //var_dump($sortColumn);exit;
        $orderBy = '';
        /*if($sortColumn){
            switch ($sortColumn) {
                case 'username':
//                  $orderBy = '{{%admin}}.`'.$sortColumn.'` ';
                    break;
                default:
//                  $orderBy = '{{%car_insurance_business}}.`'.$sortColumn.'` ';
                break;
            }
        }else{
            $orderBy = '{{%car_driving_license}}.`id` ';
        }*/
        if($sortColumn){
            $orderBy = '`'.$sortColumn.'` ';
        }else{
            $orderBy = '`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        //获取车辆配置信息
        $configItems = ['DL_REG_ADDR'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value'
        );
        //echo '<pre>';
        //var_dump($config);exit;
        foreach ($data as $key => $val) {
            //$drinvingInfo['addr'] = $config['DL_REG_ADDR'][$drinvingInfo['addr']]['text'];
            //echo '<pre>';
            //var_dump($config['DL_REG_ADDR'][$val['addr']]['text']);exit;
            $data[$key]['addr_'] = $config['DL_REG_ADDR'][$val['addr']]['text'];
            //var_dump($val[$key]['addr_']);exit;
        }
        //echo '<pre>';
        //var_dump($data);exit;
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
     }

    /**
     * 修改行驶证管理
     */
    public function actionEditDrivingLicense()
    {       
        //echo 'mmmmm';exit;
        //data submit start
        if(yii::$app->request->isPost){
            //var_dump($car_id);exit;
            $formData = yii::$app->request->post();
            $carId = yii::$app->request->post('car_id') or die('param carId is required');

            $model = CarDrivingLicense::findOne(['car_id'=>$carId]);
            //var_dump($model);exit;
            $formData['register_date']= strtotime($formData['register_date']);
            $formData['issue_date']= strtotime($formData['issue_date']);
            $formData['force_scrap_date']= strtotime($formData['force_scrap_date']);
            $formData['valid_to_date']= strtotime($formData['valid_to_date']);
            /*echo '<pre>';
            var_dump($formData);exit;*/

            $model or die('record not found');
            //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
            $checkArr = Car::checkOperatingCompanyIsMatch($model->car_id);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            //$model->load(yii::$app->request->post(),'');
            $model->load($formData,'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                $model->add_datetime = time();
                $model->add_aid = $_SESSION['backend']['adminInfo']['id'];
                $model->image = $formData['image'];
                //print_r($model);
                //exit;
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '记录修改成功！';
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
            echo json_encode($returnArr);
            return null;
        }

        //data submit end
        /*$carId = yii::$app->request->post('car_id') or die('param 33id is required');
        $model = CarDrivingLicense::findOne(['car_id'=>$carId]);*/

        $carId = yii::$app->request->get('carId') or die('param carId is required');
        
        //$model = CarDrivingLicense::findOne(['car_id'=>$carId]);
        $model = CarDrivingLicense::findOne(['id'=>$carId]);
        if($model){
            $licenseInfo = $model->getOldAttributes();
        }else{
            $licenseInfo = [];
        }
        /*echo '<pre>';
        var_dump($licenseInfo);exit;*/
        //$model = CarDrivingLicense::findOne(['id'=>$carId]);
        
        $model or die('record not found');
        $config = (new ConfigCategory)->getCategoryConfig(['DL_REG_ADDR']);
        return $this->render('edit-driving-license',[
            'drivingLicense'=>$model->getOldAttributes(),
            'config'=>$config,
            'carId'=>$carId,
            'licenseInfo'=>$licenseInfo
        ]);
    }


    /**
     * 道路运输证管理
     */
    public function actionRoadTransportCertificate()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $carId = yii::$app->request->post('car_id') or die('param carId is required');
            //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
            $checkArr = Car::checkOperatingCompanyIsMatch($carId);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $model = CarRoadTransportCertificate::findOne(['car_id'=>$carId]);
            $model or $model = new CarRoadTransportCertificate;
            $model->load(yii::$app->request->post(),'');
            $model->issuing_date = strtotime($model->issuing_date);
            $model->last_annual_verification_date = strtotime($model->last_annual_verification_date);
            
            $model->image = yii::$app->request->post('image');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                $carInfo = Car::find()->select(['plate_number'])
                            ->where(['id'=>$carId])
                            ->asArray()->one();
                //计算下次审核时间（上次时间+1年）
                $model->next_annual_verification_date = $model->last_annual_verification_date + 31536000;
//                 $nextValidateYear = date('Y',$model->last_annual_verification_date) + 1;
//                 $nextValidateMonth = $this->getNnnualVerificationMonth($carInfo['plate_number']);
//                 $nextValidateDay = date('t',strtotime($nextValidateYear.'-'.$nextValidateMonth.'-01'));
//                 $model->next_annual_verification_date = strtotime($nextValidateYear.'-'.$nextValidateMonth.'-'.$nextValidateDay.' 23:59:59');
                $model->add_datetime = time();
                $model->add_aid = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '道路运输证信息修改成功！';
                    UserLog::log("修改车辆：{$carInfo['plate_number']}，道路运输证信息！",'sys');
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
            echo json_encode($returnArr);
            return null;
        }
        //data submit end
        $carId = yii::$app->request->get('carId') or die('param carId is required');
        $model = CarRoadTransportCertificate::findOne(['car_id'=>$carId]);
        if($model){
            $certificateInfo = $model->getOldAttributes();
        }else{
            $certificateInfo = [];
        }
        //获取配置
        $config = (new ConfigCategory)->getCategoryConfig(['TC_ISSUED_BY']);
        return $this->render('road-transport-certificate',[
            'carId'=>$carId,
            'certificateInfo'=>$certificateInfo,
            'config'=>$config,
        ]);
    }

    /**
     * 根据车辆车牌号判断车辆的道路运输证年审月份
     */
    protected function getNnnualVerificationMonth($plateNumber)
    {
        $lastNumber = substr($plateNumber,-1,1);
        $november = ['A','B','C','D','E','F','G','H','I','J','K','L','M'];
        if(is_numeric($lastNumber)){
            if($lastNumber == 0){
                $annualMonth = 10;
            }else{
                $annualMonth = $lastNumber;
            }
        }elseif(in_array(strtoupper($lastNumber),$november)){
            $annualMonth = 11;
        }else{
            $annualMonth = 12;
        }
        return $annualMonth < 10 ? '0'.$annualMonth : $annualMonth ;
    }

    /**
     * 车辆二级维护记录管理
     */
    public function actionSecondMaintenanceRecord()
    {
        $carId = yii::$app->request->get('carId') or die('param carId is required');
        $buttons = $this->getCurrentActionBtn();
        return $this->render('second-maintenance-record',[
            'carId'=>$carId,
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取车辆二级维护记录列表
     */
    public function actionGetSecondMaintenanceList()
    {
        $carId = yii::$app->request->get('carId') or die('param carId is required');
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CarSecondMaintenance::find()
                    ->andWhere(['is_del'=>0])
                    ->andWhere(['car_id'=>$carId]);
        //查询条件
        $query->andFilterWhere(['like','number',yii::$app->request->get('number')]);
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            $orderBy = '`'.$sortColumn.'` ';
        }else{
            $orderBy = '`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 添加车辆二级维护记录
     */
    public function actionAddSecondMaintenance()
    {
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
            $checkArr = Car::checkOperatingCompanyIsMatch($formData['car_id']);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $model = new CarSecondMaintenance();
            $model->load($formData,'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                $model->add_datetime = time();
                $model->add_aid = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '车辆二级维护记录添加成功！';
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
            echo json_encode($returnArr);
            return null;
        }
        $carId = yii::$app->request->get('carId') or die('param carId is required');
        return $this->render('add-second-maintenance',[
            'carId'=>$carId
        ]);
    }

    /**
     * 修改车辆二级维护记录
     */
    public function actionEditSecondMaintenance()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            $model = CarSecondMaintenance::findOne(['id'=>$id]);
            $model or die('record not found');
            //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
            $checkArr = Car::checkOperatingCompanyIsMatch($model->car_id);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                $model->add_datetime = time();
                $model->add_aid = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '车辆二级维护记录修改成功！';
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
            echo json_encode($returnArr);
            return null;
        }
        //data submit end
        $id = yii::$app->request->get('id') or die('param id is required');
        $model = CarSecondMaintenance::findOne(['id'=>$id]);
        $model or die('record not found');
        return $this->render('edit-second-maintenance',[
            'secondMaintenance'=>$model->getOldAttributes()
        ]);
    }

    /**
     * 删除车辆二级维护记录
     */
    public function actionRemoveSecondMaintenance()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $model = CarSecondMaintenance::findOne(['id'=>$id]);
        $model or die('record not found');
        //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
        $checkArr = Car::checkOperatingCompanyIsMatch($model->car_id);
        if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
            return json_encode(['status'=>false,'info'=>$checkArr['info']]);
        }
        $returnArr = [];
        $returnArr['status'] = true;
        $returnArr['info'] = '';
        if(CarSecondMaintenance::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '车辆二级维护记录删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '车辆二级维护记录删除失败！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 车辆交强险管理
     */
    public function actionTrafficCompulsoryInsurance()
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
		return $this->render('traffic-compulsory-insurance',[
			'carId'=>$carId,
			'buttons'=>$buttons,
            'config'=>$config,
            'insurerCompany'=>$insurerCompany,
		]);
    }

    /**
     * 获取指定车辆交强险列表
     */
    public function actionTciGetList()
    {
        $carId = yii::$app->request->get('carId') or die('param carId is required');
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CarInsuranceCompulsory::find()
            ->select(['{{%car_insurance_compulsory}}.*','{{%admin}}.`username`'])
            ->joinWith('admin',false,'LEFT JOIN')
            ->andWhere(['=','{{%car_insurance_compulsory}}.`is_del`',0])
            ->andWhere(['=','{{%car_insurance_compulsory}}.`car_id`',$carId]);
        //查询条件
        $query->andFilterWhere(['=','{{%car_insurance_compulsory}}.`insurer_company`',yii::$app->request->get('insurer_company')]);
        //查询条件结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
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
                    $orderBy = '{{%car_insurance_compulsory}}.`'.$sortColumn.'` ';
                    break;
            }
        }else{
            $orderBy = '{{%car_insurance_compulsory}}.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 添加指定车辆交强险记录
     */
    public function actionTciAdd()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
            $checkArr = Car::checkOperatingCompanyIsMatch($formData['car_id']);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $model = new CarInsuranceCompulsory();
            $model->load($formData,'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                $model->add_datetime = time();
                $model->add_aid = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '车辆强制保险记录添加成功！';
                    $carId = $model->car_id;
                    //检查行驶证和交强险是否齐全，若是则更改车辆状态
                    $this->checkDrivingLicenseAndTrafficCompulsoryInsurance($carId);
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
        //data sbumit end
        //获取配置
        $config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY']);
        $config['INSURANCE_COMPANY'] = array_values($config['INSURANCE_COMPANY']);
        $carId = yii::$app->request->get('carId') or die('param carId is required');
        return $this->render('tci-add',[
            'carId'=>$carId,
            'config'=>$config
        ]);
    }

    /**
     * 修改指定车辆交强险记录
     */
    public function actionTciEdit(){
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            $model = CarInsuranceCompulsory::findOne(['id'=>$id]);
            $model or die('record not found');
            //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
            $checkArr = Car::checkOperatingCompanyIsMatch($model->car_id);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                $model->add_datetime = time();
                $model->add_aid = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '车辆强制保险记录修改成功！';
                    $carId = $model->car_id;
                    //检查行驶证和交强险是否齐全，若是则更改车辆状态
                    $this->checkDrivingLicenseAndTrafficCompulsoryInsurance($carId);
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
            echo json_encode($returnArr);
            return null;
        }
        //data submit end
        $id = yii::$app->request->get('id') or die('param id is required');
        $model = CarInsuranceCompulsory::findOne(['id'=>$id]);
        $model or die('record not found');
        $config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY']);
        return $this->render('tci-edit',[
            'tciInfo'=>$model->getOldAttributes(),
            'config'=>$config,
        ]);
    }

    /**
     * 删除指定车辆交强险记录
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

    /**
     * 指定车辆商业保险管理
     */
    public function actionBusinessInsurance()
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
		return $this->render('business-insurance',[
			'carId'=>$carId,
			'buttons'=>$buttons,
            'config'=>$config,
            'insurerCompany'=>$insurerCompany,
		]);
    }

    /**
     * 获取指定车辆商业保险列表
     */
    public function actionBiGetList()
    {
        $carId = yii::$app->request->get('carId') or die('param carId is required');
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CarInsuranceBusiness::find()
            ->select(['{{%car_insurance_business}}.*','{{%admin}}.`username`'])
            ->joinWith('admin',false,'LEFT JOIN')
            ->andWhere(['=','{{%car_insurance_business}}.`is_del`',0])
            ->andWhere(['=','{{%car_insurance_business}}.`car_id`',$carId]);
        //查询条件
        $query->andFilterWhere(['=','{{%car_insurance_business}}.`insurer_company`',yii::$app->request->get('insurer_company')]);
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
                    $orderBy = '{{%car_insurance_business}}.`'.$sortColumn.'` ';
                    break;
            }
        }else{
            $orderBy = '{{%car_insurance_business}}.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 指定车辆添加商业保险记录
     */
    public function actionBiAdd()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
            $checkArr = Car::checkOperatingCompanyIsMatch($formData['car_id']);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $model = new CarInsuranceBusiness();
            $model->load($formData,'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                $model->add_datetime = time();
                $model->add_aid = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '车辆商业保险记录添加成功！';
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
            echo json_encode($returnArr);
            return null;
        }
        //data sbumit end
        $carId = yii::$app->request->get('carId') or die('param carId is required');
        $config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY']);
        $config['INSURANCE_COMPANY'] = array_values($config['INSURANCE_COMPANY']);
        return $this->render('bi-add',[
            'carId'=>$carId,
            'config'=>$config
        ]);
    }

    /**
     * 修改指定车辆商业保险记录
     */
    public function actionBiEdit(){
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            $model = CarInsuranceBusiness::findOne(['id'=>$id]);
            $model or die('record not found');
            //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
            $checkArr = Car::checkOperatingCompanyIsMatch($model->car_id);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                $model->add_datetime = time();
                $model->add_aid = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '车辆商业保险记录修改成功！';
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
        //data submit end
        $id = yii::$app->request->get('id') or die('param id is required');
        $model = CarInsuranceBusiness::findOne(['id'=>$id]);
        $model or die('record not found');
        $config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY']);
        return $this->render('bi-edit',[
            'biInfo'=>$model->getOldAttributes(),
            'config'=>$config,
        ]);
    }

    /**
     * 删除指定车辆商业保险记录
     */
    public function actionBiRemove()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $model = CarInsuranceBusiness::findOne(['id'=>$id]);
        $model or die('record not found');
        //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
        $checkArr = Car::checkOperatingCompanyIsMatch($model->car_id);
        if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
            return json_encode(['status'=>false,'info'=>$checkArr['info']]);
        }
        $returnArr = [];
        $returnArr['status'] = true;
        $returnArr['info'] = '';
        if(CarInsuranceBusiness::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '车辆强制保险记录删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '车辆强制保险记录删除失败！';
        }
        return json_encode($returnArr);
    }	
	

    /**
     * 导出所选择车辆基本信息
     */
    public function actionExportChoose()
    {
        $id = yii::$app->request->get('id') or die('param id is requried');
        $id = trim($id,',');
        $ids = explode(',',$id);
        if(empty($ids)){
            die('no data to export!');
        }
        $excelFile = [];
        foreach($ids as $val){
            $excelFile[] = $this->exportSingle($val);
        }
        $zipFile = dirname(getcwd()).'/runtime/'.time().'.zip';
        File::filesToZip($excelFile,$zipFile);
        File::fileDownload($zipFile);
        foreach($excelFile as $val){
            @unlink($val);
        }
        @unlink($zipFile);
    }

    /**
     * 导出车辆基本信息数据
     */
    protected function exportSingle($id){
        //查询车辆基本信息
        $carBaseinfo = Car::find()
            ->select([
                '{{%car}}.*',
                'operating_company_name'=>'{{%operating_company}}.`name`',
                'car_brand_name'=>'{{%car_brand}}.`name`',
                'admin_username'=>'{{%admin}}.`username`',
                'owner_name'=>'{{%owner}}.`name`'
            ])->joinWith('operatingCompany',false)
            ->joinWith('carBrand',false)
            ->joinWith('admin',false)
            ->joinWith('owner',false)
            ->where(['{{%car}}.`id`'=>$id])
            ->asArray()->limit(1)->one();
        if(empty($carBaseinfo)){
            return false;
        }
        //获取车辆配置信息
        $configItems = ['car_status','car_status2','car_color','car_type','import_domestic','fuel_type','turn_type','use_nature','gain_way','DL_REG_ADDR','TC_ISSUED_BY'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value'
        );
        //处理数据
        $carConfigItems = ['car_status','car_status2','car_color','car_type','import_domestic','fuel_type','turn_type','use_nature','gain_way'];
        foreach($carConfigItems as $val){
            $carBaseinfo[$val] = @$config[$val][$carBaseinfo[$val]]['text'];
        }
        $carBaseinfo['reg_date'] = $carBaseinfo['reg_date'] ? date('Y-m-d',$carBaseinfo['reg_date']) : '';
        $carBaseinfo['leave_factory_date'] = $carBaseinfo['leave_factory_date'] ? date('Y-m-d',$carBaseinfo['leave_factory_date']) : '';
        $carBaseinfo['issuing_date'] = $carBaseinfo['issuing_date'] ? date('Y-m-d',$carBaseinfo['issuing_date']) : '';
        $exportCarBaseInfoMap = [
            'plate_number' => '车牌号',
            'vehicle_dentification_number' => '车架号',
            'car_status' => '一级状态',
            'car_status2' => '二级状态',
            'buy_batch_number' => '购买批次号',
            'operating_company_name' => '车辆运营公司',
            'owner_name' => '所有人',
            'identity_name' => '身份证明名称',
            'identity_number' => '身份证明号码',
            'reg_organ' => '登记机关',
            'reg_date' => '登记日期',
            'reg_number' => '机动车登记编号',
            'car_type' => '车辆类型',
            'car_brand_name' => '车辆品牌',
            'car_model' => '车辆型号',
            'car_color' => '车身颜色',
            'import_domestic' => '国产/进口',
            'engine_number' => '发动机号',
            'engine_model' => '发动机型号',
            'fuel_type' => '燃料种类',
            'displacement' => '排量',
            'power' => '功率',
            'endurance_mileage' => '续航里程',
            'manufacturer_name' => '制造厂名称',
            'turn_type' => '转向形式',
            'wheel_distance_f' => '轮距前',
            'wheel_distance_b' => '轮距后',
            'wheel_amount' => '轮胎数',
            'wheel_specifications' => '轮胎规格',
            'plate_amount' => '钢板弹簧片数',
            'shaft_distance' => '轴距',
            'shaft_amount' => '轴数',
            'outside_long' => '外廓尺寸长',
            'outside_width' => '外廓尺寸宽',
            'outside_height' => '外廓尺寸高',
            'inside_long' => '货厢内部尺寸长',
            'inside_width' => '货厢内部尺寸宽',
            'inside_height' => '货厢内部尺寸高',
            'total_mass' => '总质量',
            'check_mass' => '核定载质量',
            'check_passenger' => '核定载客',
            'check_tow_mass' => '准牵引总质量',
            'cab_passenger' => '驾驶室载客',
            'use_nature' => '使用性质',
            'gain_way' => '车辆获得方式',
            'leave_factory_date' => '车辆出厂日期',
            'issuing_organ' => '发证机关',
            'issuing_date' => '发证日期',
            'battery_model' => '电池型号',
            'motor_model' => '电机型号',
            'motor_monitor_model' => '电机控制器型号',
            'add_time' => '入库时间',
            'admin_username' => '操作人员',
            'note' => '备注',
        ];
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
        ]);
        $excel->addLineToExcel([[
            'content'=>'车辆基本信息',
            'color'=>'00ff0000',
            'font-weight'=>true,
            'background-rgba'=>'004bacc6',
            'color'=>'00ffffff',
            'border-type'=>'thin',
            'border-color'=>'00ffffff',
            'font-size'=>'18',
            'colspan'=>10,
            'height'=>40,
            'valign'=>'center'
        ]]);
        $lineData = [];
        $i = 0;
        foreach($exportCarBaseInfoMap as $key=>$val){
            if( ($i + 1) % 5 == 0){
                $excel->addLineToExcel($lineData);
                $lineData = [];
            }
            $lineData[] = ['content'=>$val,'width'=>24];
            if(isset($carBaseinfo[$key])){
                $lineData[] = ['content'=>$carBaseinfo[$key],'text-align'=>'left','width'=>30];
            }else{
                $lineData[] = ['content'=>'','width'=>30];
            }
            $i ++;
        }
        if(!empty($lineData)){
            $excel->addLineToExcel($lineData);
        }
        //导出行驶证信息
        $excel->addLineToExcel([[
            'content'=>'车辆行驶证',
            'color'=>'00ff0000',
            'font-weight'=>true,
            'background-rgba'=>'004bacc6',
            'color'=>'00ffffff',
            'border-type'=>'thin',
            'border-color'=>'00ffffff',
            'font-size'=>'18',
            'colspan'=>10,
            'height'=>40,
            'valign'=>'center'
        ]]);
        $exportCarBaseInfoMap = [
            'addr' => '行驶证登记地址',
            'register_date' => '行驶证注册日期',
            'issue_date' => '行驶证发证日期',
            'archives_number' => '档案编号',
            'total_mass' => '整备质量',
            'force_scrap_date' => '强制报废日期',
            'valid_to_date' => '检验有效期至',
            'next_valid_date' => '下次年审时间',
            'add_datetime' => '修改时间',
            'admin_username' => '操作人员'
        ];
        $drinvingInfo = CarDrivingLicense::find()
            ->select([
                '{{%car_driving_license}}.`addr`',
                '{{%car_driving_license}}.`register_date`',
                '{{%car_driving_license}}.`issue_date`',
                '{{%car_driving_license}}.`archives_number`',
                '{{%car_driving_license}}.`total_mass`',
                '{{%car_driving_license}}.`force_scrap_date`',
                '{{%car_driving_license}}.`valid_to_date`',
                '{{%car_driving_license}}.`next_valid_date`',
                '{{%car_driving_license}}.`add_datetime`',
                'admin_username'=>'{{%admin}}.`username`'
            ])->joinWith('admin',false)
            ->where(['{{%car_driving_license}}.`car_id`'=>$carBaseinfo['id']])
            ->limit('1')->asArray()->one();
        if($drinvingInfo){
            $drinvingInfo['addr'] = $config['DL_REG_ADDR'][$drinvingInfo['addr']]['text'];
            $drinvingInfo['register_date'] = $drinvingInfo['register_date'] ? date('Y-m-d',$drinvingInfo['register_date']) : '' ;
            $drinvingInfo['issue_date'] = $drinvingInfo['issue_date'] ? date('Y-m-d',$drinvingInfo['issue_date']) : '';
            $drinvingInfo['force_scrap_date'] = $drinvingInfo['force_scrap_date'] ? date('Y-m-d',$drinvingInfo['force_scrap_date']) : '';
            $drinvingInfo['valid_to_date'] = $drinvingInfo['valid_to_date'] ? date('Y-m-d',$drinvingInfo['valid_to_date']) : '';
            $drinvingInfo['next_valid_date'] = $drinvingInfo['next_valid_date'] ? date('Y-m-d',$drinvingInfo['next_valid_date']) : '';
            $drinvingInfo['add_datetime'] = $drinvingInfo['add_datetime'] ? date('Y-m-d',$drinvingInfo['add_datetime']) : '';
            $i = 0;
            $lineData = [];
            foreach($exportCarBaseInfoMap as $key=>$val){
                if( ($i + 1) % 5 == 0){
                    $excel->addLineToExcel($lineData);
                    $lineData = [];
                }
                $lineData[] = ['content'=>$val];
                if(isset($drinvingInfo[$key])){
                    $lineData[] = ['content'=>$drinvingInfo[$key],'text-align'=>'left'];
                }else{
                    $lineData[] = ['content'=>''];
                }
                $i ++;
            }
            if(!empty($lineData)){
                $excel->addLineToExcel($lineData);
            }
        }
        //写入车辆运营证信息
        $excel->addLineToExcel([[
            'content'=>'车辆运营证',
            'color'=>'00ff0000',
            'font-weight'=>true,
            'background-rgba'=>'004bacc6',
            'color'=>'00ffffff',
            'border-type'=>'thin',
            'border-color'=>'00ffffff',
            'font-size'=>'18',
            'colspan'=>10,
            'height'=>40,
            'valign'=>'center'
        ]]);
        $exportCarBaseInfoMap = [
            'ton_or_seat' => '吨位或座位',
            'issuing_organ' => '核发机关',
            'rtc_province' => '省',
            'rtc_city' => '市',
            'rtc_number' => '道路运输证号',
            'issuing_date' => '发证日期',
            'last_annual_verification_date' => '上次年审时间',
            'next_annual_verification_date' => '下次年审时间',
            'add_datetime' => '修改时间',
            'admin_username' => '操作人员'
        ];
        $crtcInfo = CarRoadTransportCertificate::find()
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
                'admin_username'=>'{{%admin}}.`username`',
            ])->joinWith('admin',false)->where([
                '{{%car_road_transport_certificate}}.`car_id`'=>$carBaseinfo['id']
            ])->limit('1')->asArray()->one();
        if($crtcInfo){
            $crtcInfo['issuing_organ'] = $config['TC_ISSUED_BY'][$crtcInfo['issuing_organ']]['text'];
            $crtcInfo['issuing_date'] = $crtcInfo['issuing_date'] ? date('Y-m-d',$crtcInfo['issuing_date']) : '';
            $crtcInfo['last_annual_verification_date'] = $crtcInfo['last_annual_verification_date'] ? date('Y-m-d',$crtcInfo['last_annual_verification_date']) : '';
            $crtcInfo['next_annual_verification_date'] = $crtcInfo['next_annual_verification_date'] ? date('Y-m-d',$crtcInfo['next_annual_verification_date']) : '';
            $crtcInfo['add_datetime'] = $crtcInfo['add_datetime'] ? date('Y-m-d',$crtcInfo['add_datetime']) : '';
            $i = 0;
            $lineData = [];
            foreach($exportCarBaseInfoMap as $key=>$val){
                if( ($i + 1) % 5 == 0){
                    $excel->addLineToExcel($lineData);
                    $lineData = [];
                }
                $lineData[] = ['content'=>$val];
                if(isset($crtcInfo[$key])){
                    $lineData[] = ['content'=>$crtcInfo[$key],'text-align'=>'left'];
                }else{
                    $lineData[] = ['content'=>''];
                }
                $i ++;
            }
            if(!empty($lineData)){
                $excel->addLineToExcel($lineData);
            }
        }
        //生成excel文件
        $objWriter = \PHPExcel_IOFactory::createWriter($excel->getPHPExcel(), 'Excel2007');
        if(!empty($carBaseinfo['plate_number'])){
            $fileName = iconv('utf-8','gbk',$carBaseinfo['plate_number']);
        }elseif(!empty($carBaseinfo['vehicle_dentification_number'])){
            $fileName = iconv('utf-8','gbk',$carBaseinfo['vehicle_dentification_number']);
        }else{
            $fileName = uniqid();
        }
        $excelFileName = dirname(getcwd())."/runtime/{$fileName}.xlsx";
        $objWriter->save($excelFileName);
        return $excelFileName;
    }

    /**
     * 导出单辆车辆数据时写入到excel格式
     */
    /*protected function writeLineDataToExcel($excelObj,$data,$type = 0)
    {
        switch ($type) {
            case '0':
                //写入标题
                $excelObj->addLineToExcel([[
                    'content'=>$data[0],
                    'color'=>'00ff0000',
                    'font-weight'=>true,
                    'background-rgba'=>'004bacc6',
                    'color'=>'00ffffff',
                    'border-type'=>'thin',
                    'border-color'=>'00ffffff',
                    'font-size'=>'18',
                    'colspan'=>10,
                    'height'=>40,
                    'valign'=>'center'
                ]]);
                break;
            default:
                //写入行内数据
                $lineData = [];
                foreach($data as $key=>$val){
                    if($key % 2 == 0){
                        $lineData[] = [
                            'content'=>$val,
                            'width'=>'25',
                            'font-size'=>'12',
                            'color'=>'000000',
                            'background-rgba'=>'00b7dde8',
                            'border-type'=>'thin',
                            'border-color'=>'004bacc6',
                            'valign'=>'center',
                            'align'=>'right'
                        ];
                    }else{
                        $lineData[] = [
                            'content'=>$val,
                            'width'=>'25',
                            'font-size'=>'12',
                            'valign'=>'center',
                            'align'=>'left'
                        ];
                    }
                    if($key != 0 && ($key+1) % 10 == 0){
                        $excelObj->addLineToExcel($lineData);
                        $lineData = [];
                    }
                }
                $excelObj->addLineToExcel($lineData);
                break;
        }
    }*/

    /**
     * 按条件导出车辆列表
     */
    public function actionExportWidthCondition()
    {	
        //echo '123';exit;		
		$query = Car::find()
            ->select([
                '{{%car}}.`id`',
                '{{%car}}.`plate_number`',
                '{{%car}}.`vehicle_dentification_number`',
                '{{%car}}.`engine_number`',
                '{{%car}}.`car_status`',
            	'{{%car}}.`car_status2`',
                //'{{%car}}.`car_brand`', //废弃原配置品牌
                '{{%car_brand}}.`name`',
                '{{%car}}.`car_type`',
                '{{%car}}.`car_color`',
            	'{{%car}}.`car_model`',
            	'car_model_name'=>'{{%config_item}}.`text`',
                'transact_dl'=>'{{%car_driving_license}}.`id`',
            	'transact_ic'=>'{{%car_insurance_compulsory}}.`id`',
                'transact_rtc'=>'{{%car_road_transport_certificate}}.`id`',
                'transact_sm'=>'{{%car_second_maintenance}}.`id`',
                'transact_ib'=>'{{%car_insurance_business}}.`id`',
                'car_owner'=>'{{%owner}}.`name`',
                'car_operating_company'=>'{{%operating_company}}.`name`',
                '{{%car}}.`add_time`',
                '{{%admin}}.`username`',
                '{{%car}}.`note`',
            ])
            ->leftJoin('{{%car_driving_license}}','{{%car}}.id = {{%car_driving_license}}.car_id and {{%car_driving_license}}.valid_to_date>'.time())
            ->leftJoin('{{%car_road_transport_certificate}}','{{%car}}.id = {{%car_road_transport_certificate}}.car_id and {{%car_road_transport_certificate}}.next_annual_verification_date>'.time())
            ->joinWith('carSecondMaintenance',false)
            ->leftJoin('{{%car_insurance_compulsory}}','{{%car}}.id = {{%car_insurance_compulsory}}.car_id and {{%car_insurance_compulsory}}.is_del=0 and {{%car_insurance_compulsory}}.end_date>'.time())
            ->leftJoin('{{%car_insurance_business}}','{{%car}}.id = {{%car_insurance_business}}.car_id and {{%car_insurance_business}}.is_del=0 and {{%car_insurance_business}}.end_date>'.time())
            ->leftJoin('{{%owner}}','{{%car}}.owner_id = {{%owner}}.id')
            ->leftJoin('{{%operating_company}}','{{%car}}.operating_company_id = {{%operating_company}}.id')

            ->joinWith('admin',false)
            ->joinWith('carBrand',false)
            ->leftJoin('{{%config_item}}', '{{%car}}.`car_model` = {{%config_item}}.`value`')
            ->andWhere(['{{%car}}.`is_del`'=>0]);
        ////其他查询条件
		if(yii::$app->request->get('owner_id')){
			$owner_id = yii::$app->request->get('owner_id');
			$query->andFilterWhere(['{{%car}}.`owner_id`'=>$owner_id]);
		}
        $query->andFilterWhere(['like','{{%car}}.`plate_number`',yii::$app->request->get('plate_number')]);
        $query->andFilterWhere(['like','{{%car}}.`vehicle_dentification_number`',yii::$app->request->get('vehicle_dentification_number')]);
        $query->andFilterWhere(['=','{{%car}}.`car_status`',yii::$app->request->get('car_status')]);
        $query->andFilterWhere(['=','{{%car}}.`car_status2`',yii::$app->request->get('car_status2')]);
        $query->andFilterWhere(['like','{{%car}}.`car_brand`',yii::$app->request->get('car_brand')]);
        //车辆运营公司、车辆类型 by  2016/9/20
        $query->andFilterWhere(['=','{{%car}}.`operating_company_id`',yii::$app->request->get('operating_company_id')]);
        $query->andFilterWhere(['=','{{%car}}.`car_type`',yii::$app->request->get('car_type')]);
        $query->andFilterWhere(['=','{{%car}}.`gain_way`',yii::$app->request->get('gain_way')]);//车辆获得方式
    	if(yii::$app->request->get('gain_year')){//车辆购买年份
			$query->andFilterWhere(['=','{{%car}}.`gain_year`',yii::$app->request->get('gain_year')]);
		}
        //查品牌，查父品牌时也会查出子品牌
        if(yii::$app->request->get('brand_id')){
            $brand_id = yii::$app->request->get('brand_id');
            $query->andFilterWhere([
                'or',
                ['{{%car_brand}}.`id`'=>$brand_id],
                ['{{%car_brand}}.`pid`'=>$brand_id]
            ]);
        }
        //是否办理行驶证
        $transactDl = intval(yii::$app->request->get('transact_dl'));
        if($transactDl == 1){
            //查询已经办理
            $query->andWhere('{{%car_driving_license}}.`id` IS NOT NULL');
        }elseif($transactDl == 2){
            //查询未办理
            $query->andWhere('{{%car_driving_license}}.`id` IS NULL');
        }
        //是否办理道路运输证
        $transactRtc = intval(yii::$app->request->get('transact_rtc'));
        if($transactRtc == 1){
            //查询已经办理
            $query->andWhere('{{%car_road_transport_certificate}}.`id` IS NOT NULL');
        }elseif($transactRtc == 2){
            //查询未办理
            $query->andWhere('{{%car_road_transport_certificate}}.`id` IS NULL');
        }
        //是否办理交强险
        $transactRtc = intval(yii::$app->request->get('transact_ic'));
        if($transactRtc == 1){
            //查询已经办理
            $query->andWhere('{{%car_insurance_compulsory}}.`id` IS NOT NULL');
        }elseif($transactRtc == 2){
            //查询未办理
            $query->andWhere('{{%car_insurance_compulsory}}.`id` IS NULL');
        }
        //是否办理商业险
        $transactRtc = intval(yii::$app->request->get('transact_ib'));
        if($transactRtc == 1){
            //查询已经办理
            $query->andWhere('{{%car_insurance_business}}.`id` IS NOT NULL');
        }elseif($transactRtc == 2){
            //查询未办理
            $query->andWhere('{{%car_insurance_business}}.`id` IS NULL');
        }
        //是否办理二级维护卡
        $transactRtc = intval(yii::$app->request->get('transact_sm'));
        if($transactRtc == 1){
            //查询已经办理
            $query->andWhere('{{%car_second_maintenance}}.`id` IS NOT NULL');
        }elseif($transactRtc == 2){
            //查询未办理
            $query->andWhere('{{%car_second_maintenance}}.`id` IS NULL');
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
        	$query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        ////查询条件结束
        $data = $query->asArray()->all(); 
        
        set_time_limit(0);
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'car list',
            'subject'=>'car list',
            'description'=>'car list',
            'keywords'=>'car list',
            'category'=>'car list'
        ]);
        $excHeaders = [
            [
                ['content'=>'车牌号','font-weight'=>true,'width'=>'20'],
                ['content'=>'车架号','font-weight'=>true,'width'=>'20'],
                ['content'=>'发动机号','font-weight'=>true,'width'=>'20'],
                ['content'=>'一级状态','font-weight'=>true,'width'=>'20'],
                ['content'=>'二级状态','font-weight'=>true,'width'=>'20'],
                ['content'=>'车辆品牌','font-weight'=>true,'width'=>'10'],
                ['content'=>'车辆类型','font-weight'=>true,'width'=>'20'],
                ['content'=>'车辆颜色','font-weight'=>true,'width'=>'10'],
                ['content'=>'车辆型号','font-weight'=>true,'width'=>'10'],
                ['content'=>'车型名称','font-weight'=>true,'width'=>'10'],
                ['content'=>'行驶证','font-weight'=>true,'width'=>'10'],
			    ['content'=>'交强险','font-weight'=>true,'width'=>'10'],
			    ['content'=>'道路运输证','font-weight'=>true,'width'=>'10'],
		        ['content'=>'二级维护卡','font-weight'=>true,'width'=>'10'],
                ['content'=>'商业险','font-weight'=>true,'width'=>'10'],
                ['content'=>'机动车所有人','font-weight'=>true,'width'=>'10'],
                ['content'=>'车辆运营公司','font-weight'=>true,'width'=>'10'],
                ['content'=>'入库时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'操作人员','font-weight'=>true,'width'=>'20'],
                ['content'=>'备注','font-weight'=>true,'width'=>'10'],
            ],
        ];
        // excel表头
        $excel->addLineToExcel($excHeaders[0]);

        //获取配置数据
        $getConfigItem = [
            'car_status','car_type','car_brand','car_color'
        ];
        $config = (new ConfigCategory)->getCategoryConfig($getConfigItem,'value');
//         exit;
        foreach($data as $val){
            unset($val['id']);
            $lineData = [];
            $val['car_status'] = isset($config['car_status'][$val['car_status']]) ? $config['car_status'][$val['car_status']]['text'] : '';
            $val['car_status2'] = isset($config['car_status2'][$val['car_status2']]) ? $config['car_status2'][$val['car_status2']]['text'] : '';
            //$val['car_brand'] = isset($config['car_brand'][$val['car_brand']]) ? $config['car_brand'][$val['car_brand']]['text'] :'';
            $val['car_type'] = isset($config['car_type'][$val['car_type']]) ? $config['car_type'][$val['car_type']]['text'] :'';
            $val['car_color'] = isset($config['car_color'][$val['car_color']]) ? $config['car_color'][$val['car_color']]['text'] :'';
            $val['add_time'] = $val['add_time'] ? date('Y-m-d',$val['add_time']) : '';
            //证件办理状况
            $val['transact_dl'] = $val['transact_dl'] ? '已办理' : '未办理';
            $val['transact_ic'] = $val['transact_ic'] ? '已办理' : '未办理';
            $val['transact_rtc'] = $val['transact_rtc'] ? '已办理' : '未办理';
            $val['transact_sm'] = $val['transact_sm'] ? '已办理' : '未办理';
            $val['transact_ib'] = $val['transact_ib'] ? '已办理' : '未办理';
            $val['car_model'] = $val['car_model'];
            $val['car_model_name'] = $val['car_model_name'];

/*
$arr = ($val['plate_number'],$val['vehicle_dentification_number'],$val['engine_number'],$val['car_status'],$val['name'],$val['car_model_name'],$val['car_color'],$val['transact_dl'],$val['transact_ic'],$val['transact_rtc'],$val['transact_sm'],$val['transact_ib'],$val['add_time'],$val['username'],$val['note']);  */
            foreach($val as $v){
        		$lineData[] = ['content'=>$v,'align'=>'left'];
            }
            $excel->addLineToExcel($lineData);
        }
        unset($data);

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream"); 
        header("Accept-Ranges: bytes"); 
        //header("Accept-Length:".$fileSize); 
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','车辆基本信息列表.xls')); 
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }


    function input_csv($handle) {
        $out = array ();
        $n = 0;
        while ($data = fgetcsv($handle, 10000)) {
            $num = count($data);
            for ($i = 0; $i < $num; $i++) {
                $out[$n][$i] = mb_convert_encoding($data[$i], "UTF-8", "GBK");
            }
            $n++;
        }
        return $out;
    }
    
    /**
     * 车辆基本信息批量导入、修改
     */
    public function actionImport(){ 
        if(yii::$app->request->isPost){
            $list = array();
            //1.解析csv
            $filename = $_FILES['append']['tmp_name'];
            if (empty ($filename)) {
                echo '文件不存在';exit;
            }
            $handle = fopen($filename, 'r');
            $result = $this->input_csv($handle);
            //解析csv end...
            $connection = yii::$app->db;
            //2.初始化配置数据
			//车辆所有人
            $owner_arr	= [];
            $sql = "select id,name from cs_owner where is_del=0";
    		$owner_data = $connection->createCommand($sql)->queryAll();
    		foreach ($owner_data as $row){
    			$owner_arr[$row['name']] = $row['id'];
    		}
    		//车辆运营公司
    		$operating_company_arr	= [];
    		$sql = "select id,name from cs_operating_company where is_del=0";
    		$operating_company_data = $connection->createCommand($sql)->queryAll();
    		foreach ($operating_company_data as $row){
    			$operating_company_arr[$row['name']] = $row['id'];
    		}
    		
    		$configItems = ['car_status','car_type','car_model_name','car_color','import_domestic','fuel_type','turn_type','use_nature','gain_way'];
    		$config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
    		//车辆状态
    		$car_status_arr = [];
    		foreach ($config['car_status'] as $key=>$row){
    			$car_status_arr[$row['text']] = $key;
    		}
        	//车辆品牌
    		$brand_arr	= [];
    		$sql = "select id,name from cs_car_brand where is_del=0";
    		$car_brand_data = $connection->createCommand($sql)->queryAll();
    		foreach ($car_brand_data as $row){
    			$brand_arr[$row['name']] = $row['id'];
    		}
    		//车辆类型
    		$car_type_arr = [];
    		foreach ($config['car_type'] as $key=>$row){
    			$car_type_arr[$row['text']] = $key;
    		}
    		//车辆型号
    		$car_model_arr = [];
    		foreach ($config['car_model_name'] as $key=>$row){
    			$car_model_arr[$key] = $key;
    		}
    		//车身颜色
    		$car_color_arr = [];
    		foreach ($config['car_color'] as $key=>$row){
    			$car_color_arr[$row['text']] = $key;
    		}
    		//进口/国产
    		$import_domestic_arr = [];
    		foreach ($config['import_domestic'] as $key=>$row){
    			$import_domestic_arr[$row['text']] = $key;
    		}
    		//燃料总类
    		$fuel_type_arr = [];
    		foreach ($config['fuel_type'] as $key=>$row){
    			$fuel_type_arr[$row['text']] = $key;
    		}
    		//转向类型
    		$turn_type_arr = [];
    		foreach ($config['turn_type'] as $key=>$row){
    			$turn_type_arr[$row['text']] = $key;
    		}
    		//使用性质
    		$use_nature_arr = [];
    		foreach ($config['use_nature'] as $key=>$row){
    			$use_nature_arr[$row['text']] = $key;
    		}
    		//车辆获得方式
    		$gain_way_arr = [];
    		foreach ($config['gain_way'] as $key=>$row){
    			$gain_way_arr[$row['text']] = $key;
    		}
    		//操作人员
    		$admin_arr	= [];
    		$sql = "select id,name from cs_admin where is_del=0";
    		$admin_data = $connection->createCommand($sql)->queryAll();
    		foreach ($admin_data as $row){
    			$admin_arr[$row['name']] = $row['id'];
    		}
            //初始化配置数据end
            
            //3.检查数据合法性
            foreach ($result as $index=>$row) {
            	if($index==0){
            		continue;
            	}
            	$err_info = $this->checkImportData($connection,$row,
            		$owner_arr,$operating_company_arr,$car_status_arr,$brand_arr,$car_type_arr,$car_model_arr,$car_color_arr,
					$import_domestic_arr,$fuel_type_arr,$turn_type_arr,$use_nature_arr,$gain_way_arr,$admin_arr);
            	if ($err_info) {
            		array_unshift($err_info, "检查第{$index}条数据失败<br/>");
					$returnArr['status'] = false;
					$returnArr['info'] = $err_info;
					return json_encode($returnArr);
            	}
            }//end
            //4.新增或修改数据
            $add_num = 0;
            $update_num = 0;
            foreach ($result as $index=>$row) {
                if($index==0){
                    continue;
                }
                $r = $this->addOrUpdate($connection,$row,
	            		$owner_arr,$operating_company_arr,$car_status_arr,$brand_arr,$car_type_arr,$car_model_arr,$car_color_arr,
						$import_domestic_arr,$fuel_type_arr,$turn_type_arr,$use_nature_arr,$gain_way_arr,$admin_arr);
                if($r == 'update'){
                	$update_num++;
                }else {
                	$add_num++;
                }
            }
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = "文件导入成功！新增：{$add_num}，修改{$update_num}条";
            return json_encode($returnArr);
        }
        return $this->render('import');
    }
	
	 /**
     * MARK 行驶证批量导入、修改？
     */
    public function actionDrivingLicenseImport(){ 		
        if(yii::$app->request->isPost){
			
            $list = array();
            //1.解析csv
            $filename = $_FILES['append']['tmp_name'];
            if (empty ($filename)) {
                echo '文件不存在';exit;
            }
            $handle = fopen($filename, 'r');          
		    $result = $this->input_csv($handle);
			$result = array_slice($result, 1); 
			//解析csv end...
            $connection = yii::$app->db;
			$add_num = 0;
            $update_num = 0;
			//var_dump($result);
			foreach ($result as $key => $one) {
					//var_dump(stripos($one[4],'E'));
					//将科学数字转成原版数字字符串
					// if (stripos($one[4],'E') != false || stripos($one[4],'e') != false ) {
						// echo "hi";
						// $num = trim(preg_replace('/[=\'"]/','',$one[4],1),'"');//出现科学计数法，还原成字符串 
						// $t_num = ""; 
						// while ($num > 0){ 
							// $v = $num - floor($num / 10)*10; 
							// $num = floor($num / 10); 
							// $t_num   =   $v . $t_num; 
						// }
						
							// $result[$key][4] = $t_num;
					// }					

			// }			
			//var_dump($result);exit;
			
				//2.初始化配置数据
				
				$addr = $one[1];
				$register_date =  strtotime($one[2]);
				$issue_date =  strtotime($one[3]);
				$force_scrap_date =  strtotime($one[6]);
				$valid_to_date =  strtotime($one[7]);
				$archives_number =  $one[4];//如何解决科学数字问题？
				$total_mass =  $one[5];				
				$image =  'uploads/driving_license/'.$one[9];		//图片地址处理？		
				
				//车辆id
				$car_id = 0;
				$sql = "select id from cs_car where plate_number='$one[0]'";
				$car_info = $connection->createCommand($sql)->queryOne();	
				if ($car_info) {
					$car_id = $car_info['id'];
				}
				
				//操作人员
				$add_aid = 0;
				$sql = "select id,name from cs_admin where `name`='$one[8]'";
				$admin_info = $connection->createCommand($sql)->queryOne();
				if ($admin_info) {
					$add_aid = $admin_info['id'];
				}
				$saveData = [
					'car_id' => $car_id,
					'addr' => $addr,
					'register_date' => $register_date,
					'issue_date' => $issue_date,
					'archives_number' => $archives_number,
					'total_mass' => $total_mass,
					'force_scrap_date' => $force_scrap_date,
					'valid_to_date' => $valid_to_date,
					'add_aid' => $add_aid,
					'image' => $image
				];
			
				//var_dump($saveData);
				//exit;
				//3.检查数据合法性
                       	
            	$err_info = $this->checkDrivingLicenseImportData($connection,$one[0],$one[9],$one[4],$addr,$saveData,$one[8]);
            	if ($err_info) {
            		array_unshift($err_info, "检查第{$key}条数据失败<br/>");
					$returnArr['status'] = false;
					$returnArr['info'] = $err_info;
					return json_encode($returnArr);
            	
				} 
				//var_dump($saveData);exit;
				$result[$key]= $saveData;				
			}
			foreach ($result as $key => $one) {			
				//4.新增或修改数据
				$r = $this->addOrUpdateLicense($connection,$one);
                if($r == 'update'){
                	$update_num++;
                }else if ($r == 'add'){
                	$add_num++;
                }
			}
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = "文件导入成功！新增：{$add_num}，修改{$update_num}条";
            return json_encode($returnArr);
        }
        return $this->render('driving-license-import');
    }
	//检查导入行驶证数据合法性
	function checkDrivingLicenseImportData($connection,$plate_number,$image,$archives_number,$addr,&$saveData,$add_name){    	
    	$err_info = array();
		// var_dump($a = stripos($archives_number,"E"));exit;
		if(!isset($plate_number) || $plate_number == '' || $saveData['car_id'] == 0){
    		array_push($err_info, "车牌号：{$plate_number}，不存在！");
    	}
		if(!isset($add_name) || $add_name == '' || $saveData['add_aid'] == 0){
    		array_push($err_info, "登记人：{$add_name}，不存在！");
    	}			
    	if(!isset($image) || $image == ''){
    		array_push($err_info, "附件名称：{$image}，不存在！");
    	}
		//增加档案编号合法性检查
		if(!isset($archives_number) || $archives_number == '' || stripos($archives_number,"E")!= false){
    		array_push($err_info, "档案编号：{$archives_number}，不正确！");
    	}		
		//TODO 增加地址合法性检查
		 $config = (new ConfigCategory)->getCategoryConfig(['DL_REG_ADDR']);
		//var_dump($addr);
		//var_dump($config);exit;
		 $is_find_addr = false;
		 foreach($config['DL_REG_ADDR'] as $val){
			if ($val['text'] == $addr) {
				$is_find_addr = true;
				//找到了地址,将转换后的英文地址重新赋值。
				$saveData['addr'] = $val['value'];
				//var_dump($saveData);
				break;
			}			 
		 }
		//var_dump($is_find_addr);
		 if ($is_find_addr == false ) {
			array_push($err_info, "登记地址：{$addr}，不存在！"); 
		 }
    	return $err_info;
    }
    /**
     * 检测导入数据合法性
     * @param unknown_type $connection	数据库连接
     * @param unknown_type $obj	数据
     * @param unknown_type $owner_arr	机动车所有人
     * @param unknown_type $operating_company_arr	车辆运营公司
     * @param unknown_type $car_status_arr	车辆状态
     * @param unknown_type $brand_arr	品牌
     * @param unknown_type $car_type_arr	车辆类型
     * @param unknown_type $car_model_arr	车辆型号
     * @param unknown_type $car_color_arr	车身颜色
     * @param unknown_type $import_domestic_arr	国产/进口
     * @param unknown_type $fuel_type_arr	燃料种类
     * @param unknown_type $turn_type_arr	转向形式
     * @param unknown_type $use_nature_arr	使用性质
     * @param unknown_type $gain_way_arr	车辆获得方式
     * @param unknown_type $admin_arr	操作人员
     */
    function checkImportData($connection,$obj,
    		$owner_arr,$operating_company_arr,$car_status_arr,$brand_arr,$car_type_arr,$car_model_arr,$car_color_arr,
    		$import_domestic_arr,$fuel_type_arr,$turn_type_arr,$use_nature_arr,$gain_way_arr,$admin_arr){
    	/*
    	 * 0车牌号,1车架号,2发动机号,3登记编号,4备注,5机动车所有人,6车辆运营公司,7身份证明名称,8身份证明号码,9一级状态,10登记机关,
    	 * 11登记日期,12车辆品牌,13车辆类型,14车辆型号,15车身颜色,16进口/国产,17发动机型号,18燃料种类,19排量,20功率,
    	 * 21续航里程,22制造厂名称,23转向形式,24轮距前,25轮距后,26轮胎数,27轮胎规格,28钢板弹簧片数,29轴距,30轴数,
    	 * 31外廓尺寸长,32外廓尺寸宽,33外廓尺寸高,34货厢内部尺寸长,35货厢内部尺寸宽,36货厢内部尺寸高,37总质量,38核定载质量,39核定载客,40准牵引总质量,
    	 * 41驾驶室载客,42使用性质,43车辆获得方式,44车辆出厂日期,45发证机关,46发证日期,47电池型号,48电机型号,49电机控制器型号,50入库时间,51操作人员
    	 */
    	$err_info = array();
    	if(!@$owner_arr[$obj[5]]){
    		array_push($err_info, "机动车所有人：{$obj[5]}，不存在！");
    	}
    	if(!@$operating_company_arr[$obj[6]]){
    		array_push($err_info, "车辆运营公司：{$obj[6]}，不存在！");
    	}
    	if(!@$car_status_arr[$obj[9]]){
    		array_push($err_info, "一级状态：{$obj[9]}，不存在！");
    	}
    	if(!@$brand_arr[$obj[12]]){
    		array_push($err_info, "车辆品牌：{$obj[12]}，不存在！");
    	}
    	if(!@$car_type_arr[$obj[13]]){
    		array_push($err_info, "车辆类型：{$obj[13]}，不存在！");
    	}
    	if(!@$car_model_arr[$obj[14]]){
    		array_push($err_info, "车辆型号：{$obj[14]}，不存在！");
    	}
    	if(!@$car_color_arr[$obj[15]]){
    		array_push($err_info, "车身颜色：{$obj[15]}，不存在！");
    	}
    	if(!@$import_domestic_arr[$obj[16]]){
    		array_push($err_info, "进口/国产：{$obj[16]}，不存在！");
    	}
    	if(!@$fuel_type_arr[$obj[18]]){
    		array_push($err_info, "燃料种类：{$obj[18]}，不存在！");
    	}
    	if(!@$turn_type_arr[$obj[23]]){
    		array_push($err_info, "转向形式：{$obj[23]}，不存在！");
    	}
    	if(!@$use_nature_arr[$obj[42]]){
    		array_push($err_info, "使用性质：{$obj[42]}，不存在！");
    	}
    	if(!@$gain_way_arr[$obj[43]]){
    		array_push($err_info, "车辆获得方式：{$obj[43]}，不存在！");
    	}
    	if(!@$admin_arr[$obj[51]]){
    		array_push($err_info, "操作人员：{$obj[51]}，不存在！");
    	}
    	return $err_info;
    }
    
	
	
	 /**
     * 新增或修改行驶证信息（批量导入和修改）
     */
	function addOrUpdateLicense($connection,$saveData) {
		$license = $connection->createCommand(
    			"select id from cs_car_driving_license where car_id='$saveData[car_id]'"
    	)->queryOne();
		if($license){
    		$r = $connection->createCommand()->update('cs_car_driving_license', $saveData,
    				'id=:id',
    				array(':id'=>$license['id'])
    		)->execute();
			if ($r) {
				return 'update';
			}
    	}else {
    		$query = $connection->createCommand()->insert('cs_car_driving_license',$saveData);
    		$r = $query->execute();
			if ($r) {
				return 'add';
			}
    	}
		return 'no';
		
	}
    /**
     * 新增或修改车辆基本信息
     */
    function addOrUpdate($connection,$obj,
    		$owner_arr,$operating_company_arr,$car_status_arr,$brand_arr,$car_type_arr,$car_model_arr,$car_color_arr,
    		$import_domestic_arr,$fuel_type_arr,$turn_type_arr,$use_nature_arr,$gain_way_arr,$admin_arr){
    	$car = $connection->createCommand(
    			"select id from cs_car where vehicle_dentification_number='{$obj[1]}' and is_del=0 limit 1"
    	)->queryOne();
    	if (!isset($obj[34]) || $obj[34] == ''){
			$inside_long = 0;
		} else {
			$inside_long = $obj[34];
		}
		
		if (!isset($obj[35]) || $obj[35] == ''){
			$inside_width = 0;
		}else {
			$inside_width = $obj[35];
		}	
		
		if (!isset($obj[36]) || $obj[36] == ''){
			$inside_height = 0;
		}else {
			$inside_height = $obj[36];
		}	
		
		if (!isset($obj[39]) || $obj[39] == ''){
			$check_passenger = 0;
		}else {
			$check_passenger = $obj[39];
		}	
		
		if (!isset($obj[40]) || $obj[40] == ''){
			$check_tow_mass = 0;
		}else {
			$check_tow_mass = $obj[40];
		}		
		
    	$saveData = [
    				'plate_number' => $obj[0],
    				'vehicle_dentification_number' => $obj[1],
    				'engine_number' => $obj[2],
    				'reg_number' => $obj[3],
    				'note' => $obj[4],
    				'owner_id' => $owner_arr[$obj[5]],
    				'operating_company_id' => $operating_company_arr[$obj[6]],
    				'identity_name' => $obj[7],
    				'identity_number' => $obj[8],
    				'car_status' => $car_status_arr[$obj[9]],
    				'reg_organ' => $obj[10],
    				'reg_date' => strtotime($obj[11]),
    				'brand_id' => $brand_arr[$obj[12]],
    				'car_type' => $car_type_arr[$obj[13]],
    				'car_model' => $car_model_arr[$obj[14]],
    				'car_color' => $car_color_arr[$obj[15]],
    				'import_domestic' => $import_domestic_arr[$obj[16]],
    				'engine_model' => $obj[17],
    				'fuel_type' => $fuel_type_arr[$obj[18]],
    				'displacement' => $obj[19],
    				'power' => $obj[20],
    				'endurance_mileage' => $obj[21],
    				'manufacturer_name' => $obj[22],
    				'turn_type' => $turn_type_arr[$obj[23]],
    				'wheel_distance_f' => $obj[24],
    				'wheel_distance_b' => $obj[25],
    				'wheel_amount' => $obj[26],
    				'wheel_specifications' => $obj[27],
    				'plate_amount' => $obj[28],
    				'shaft_distance' => $obj[29],
    				'shaft_amount' => $obj[30],
    				'outside_long' => $obj[31],
    				'outside_width' => $obj[32],
    				'outside_height' => $obj[33],
    				'inside_long' => $inside_long,
    				'inside_width' => $inside_width,
    				'inside_height' => $inside_height,
    				'total_mass' => $obj[37],
    				'check_mass' => $obj[38],
    				'check_passenger' => $check_passenger,
    				'check_tow_mass' => $check_tow_mass,
    				'cab_passenger' => $obj[41],
    				'use_nature' => $use_nature_arr[$obj[42]],
    				'gain_way' => $gain_way_arr[$obj[43]],
    				'leave_factory_date' => strtotime($obj[44]),
    				'issuing_organ' => $obj[45],
    				'issuing_date' => strtotime($obj[46]),
    				'battery_model' => $obj[47],
					'motor_model' => $obj[48],
					'motor_monitor_model' => $obj[49],
    				'add_time' => strtotime($obj[50])?strtotime($obj[50]):time(),
    				'add_aid' => $admin_arr[$obj[51]]
    				];
    	if($car){
    		$r = $connection->createCommand()->update('cs_car', $saveData,
    				'id=:id',
    				array(':id'=>$car['id'])
    		)->execute();
    		return 'update';
    	}else {
    		$query = $connection->createCommand()->insert('cs_car',$saveData);
    		$query->execute();
    		return 'add';
    	}
    }
}