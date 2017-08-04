<?php
/**
 * 车辆基本信息模型
 * @author wangmin
 */
namespace backend\models;
use backend\models\CarLetRecord;
use backend\models\CarTrialProtocolDetails;
use yii;

class Car extends \common\models\Car
{
    /**
     * 管理员表对应关系
     */
    public function getAdmin(){
        return $this->hasOne(Admin::className(),[
            'id'=>'add_aid'
        ]);
    }

    /**
     * 关联车辆品牌
     */
    public function getCarBrand(){
        return $this->hasOne(CarBrand::className(),[
            'id'=>'brand_id'
        ]);
    }

    /**
     * 关联行驶证
     */
    public function getCarDrivingLicense(){
        return $this->hasOne(CarDrivingLicense::className(),[
            'car_id'=>'id'
        ]);
    }

    /**
     * 关联二级维护记录
     */
    public function getCarSecondMaintenance(){
        return $this->hasMany(CarSecondMaintenance::className(),[
            'car_id'=>'id'
        ]);
    }

    /**
     * 关联交强险
     */
    public function getCarInsuranceCompulsory(){
        return $this->hasMany(CarInsuranceCompulsory::className(),[
            'car_id'=>'id'
        ]);
    }

    /**
     * 关联商业险
     */
    public function getCarInsuranceBusiness(){
        return $this->hasMany(CarInsuranceBusiness::className(),[
            'car_id'=>'id'
        ]);
    }

    /**
     * 关联道路运输证
     */
    public function getCarRoadTransportCertificate(){
        return $this->hasOne(CarRoadTransportCertificate::className(),[
            'car_id'=>'id'
        ]);
    }

    /**
     * 关联电池系统
     */
    public function getBattery(){
        return $this->hasOne(Battery::className(),['battery_model'=>'battery_model']);
    }

    /**
     * 关联电机系统
     */
    public function getMotor(){
        return $this->hasOne(Motor::className(),['motor_model'=>'motor_model']);
    }

    /**
     * 关联电机控制器
     */
    public function getMotorMonitor(){
        return $this->hasOne(MotorMonitor::className(),['motor_monitor_model'=>'motor_monitor_model']);
    }

    //关联试用登记表
    public function getCarTrialRegister()
    {
        return $this->hasOne(CarTrialRegister::className(), ['trial_car_id' => 'id']);
    }

    /**
     * 关联【运营公司】
     */
    public function getOperatingCompany()
    {
        return $this->hasOne(OperatingCompany::className(),[
            'id'=>'operating_company_id',
        ]);
    }

    /**
     * 关联车辆所有人表
     */
    public function getOwner()
    {
        return $this->hasOne(Owner::className(),[
            'id'=>'owner_id',
        ]);
    }

    public function rules()
    {
        $rules = [];
        //$attributeLabels = $this->attributeLabels();
        //xss跨站攻击
        $rules[] = [
            [
                'plate_number','buy_batch_number','identity_name',
                'identity_number','reg_organ','reg_number','car_model',
                'vehicle_dentification_number','engine_number',
                'engine_model','manufacturer_name','wheel_specifications',
                'issuing_organ','note'
            ],'filter','filter'=>'htmlspecialchars'
        ];
        //车辆状态修改验证
        $rules[] = ['car_status','checkCarStatus','skipOnEmpty'=>false,'on'=>'edit'];
        $rules[] = [
            [
                'car_type','car_brand','car_color','import_domestic','fuel_type',
                'turn_type','use_nature','gain_way'
            ],'checkConfig','skipOnEmpty'=>false
        ];
        //时间处理
        $rules[] = [
            ['reg_date','leave_factory_date','issuing_date'],'filter','filter'=>'strtotime','skipOnEmpty'=>true
        ];
        //强转整形
        $rules[] = [['reg_date', 'displacement', 'power', 'wheel_distance_f', 'wheel_distance_b', 'wheel_amount', 'plate_amount', 'shaft_distance', 'shaft_amount', 'outside_long', 'outside_width', 'outside_height', 'inside_long', 'inside_width', 'inside_height', 'total_mass', 'check_mass', 'check_passenger', 'check_tow_mass', 'cab_passenger', 'leave_factory_date', 'issuing_date', 'is_trial', 'is_del', 'add_time', 'add_aid'], 'filter','filter'=>'intval'];
        //默认值
		$rules[] = [['owner_id','brand_id'],'default','value'=>'0'];
		
		return array_merge($rules,parent::rules());
    }

    /**
     * 场景验证字段设置
     */
    public function scenarios()
    {
        $columns = $this->getAttributes();
        $addValidColumns = $columns;//添加验证字段
        unset($addValidColumns['car_status']);
        return [
            'default'=>array_keys($columns),
            'add'=>$addValidColumns,
            //修改时验证
            'edit'=>array_keys($columns),
        ];
    }

    /**
     * 验证配置项是否正确
     */
    public function checkConfig($attribute){
        $attributeLabels = $this->attributeLabels();
        if(empty($this->$attribute)){
            $this->addError($attribute,$attributeLabels[$attribute].'不能为空！');
            return false;
        }
        //与配置表中的对应关系
        $configRelation = [
            'car_type'=>'car_type',
            'car_brand'=>'car_brand',
            'car_color'=>'car_color',
            'import_domestic'=>'import_domestic',
            'fuel_type'=>'fuel_type',
            'turn_type'=>'turn_type',
            'use_nature'=>'use_nature',
            'gain_way'=>'gain_way'
        ];
        $key = $configRelation[$attribute];
        $configCategory = ConfigCategory::find()->select(['id'])->where(['key'=>$key])->asArray()->one();
        $configItem = ConfigItem::find()
                      ->select(['id'])
                      ->where(['belongs_id'=>$configCategory['id'],'value'=>$this->$attribute])
                      ->one();
        if(!$configItem){
            $this->addError($attribute,$attributeLabels[$attribute].'不是有效的值！');
            return false;
        }
        return true;
    }

    /**
     * 修改车辆数据验证车辆状态
     */
    public function checkCarStatus()
    {
        if($this->car_status === $this->getoldAttribute('car_status')){
            //没有修改车辆状态
            return true;
        }
        //修改车辆状态时验证车辆证件是否齐全
        $als = $this->attributeLabels();
        //检测车辆是否已经上牌
        /*if(empty($this->plate_number)){
            $car = Car::find()->select(['plate_number'])->where(['id'=>$this->id])->asArray()->one();
            if(!$car || empty($car['plate_number'])){
                $this->addError('car_status','车辆未上牌，无法修改'.$als['car_status'].'！');
                return false;
            }
        }*/
        //检测车辆是否已经办理行驶证
/*         if(!CarDrivingLicense::find()->select(['id'])->where(['car_id'=>$this->id])->one()){
            $this->addError('car_status','车辆未办理行驶证，无法修改'.$als['car_status'].'！');
                return false;
        } */
        //检测车辆是否已经存在交强险
/*         if(!CarInsuranceCompulsory::find()->select(['id'])->where(['car_id'=>$this->id])->one()){
            $this->addError('car_status','车辆未购买交强险，无法修改'.$als['car_status'].'！');
                return false;
        } */
        //检测车辆是否已经存在商业险
        /*if(!CarInsuranceBusiness::find()->select(['id'])->where(['car_id'=>$this->id])->one()){
            $this->addError('car_status','车辆未购买商业险，无法修改'.$als['car_status'].'！');
                return false;
        }*/
        //检测车辆是否已经存在办理道路运输证
        /*if(!CarRoadTransportCertificate::find()->select(['id'])->where(['car_id'=>$this->id])->one()){
            $this->addError('car_status','车辆未办理道路运输证，无法修改'.$als['car_status'].'！');
                return false;
        }*/
        return true;
    }

    public function attributeLabels()
    {
        $attributeLabels = [
            'id' => 'ID',
            'plate_number' => '车牌号',
            'car_status' => '一级状态',
            'buy_batch_number' => '购买批次号',
            'owner_id' => '所有人',
            'operating_company_id' => '车辆运营公司',
            'identity_name' => '身份证明名称',
            'identity_number' => '身份证明号码',
            'reg_organ' => '登记机关',
            'reg_date' => '登记日期',
            'reg_number' => '机动车登记编号',
            'car_type' => '车辆类型',
            'car_model' => '车辆型号',
            'car_color' => '车身颜色',
            'vehicle_dentification_number' => '车架号',
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
            'note' => '备注',
            'is_del' => 'Is Del',
            'add_time' => '入库时间',
            'add_aid' => '操作人员',
            'insurance_last_update_time' => '保险最后修改时间',
            'insurance_add_aid' => '保险最后操作人员ID',
            'modified_type'=>'改装车类型',
            'car_status2'=>'二级状态',
            
        ];
        return array_merge(parent::attributeLabels(),$attributeLabels);
    }

    /*
     * 检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
     * 注：车辆基本信息列表、车辆实时数据监控列表、选择车辆combogrid等使用。
     * @$isStrictlyLimited: 是否严格按所属运营公司来限制数据的显示。
     * 当参数为false时，地上铁人员也能看其他运营公司车辆数据，如车辆基本信息列表；
     * 当参数为true时，包括地上铁在内各运营公司都只能看自己的数据，如选择车辆combogrid。
     */
    public static function isLimitedToShowByAdminOperatingCompany($isStrictlyLimited=false){
        $arr = [];
        //显示当前登录用户对应运营公司的车辆-20160325
        $super = $_SESSION['backend']['adminInfo']['super'];
        $username = $_SESSION['backend']['adminInfo']['username'];
        //除了开发人员和超级管理员不受管控外，其他人必须检测
        if(!$super && $username != 'administrator'){
            $adminInfo_operatingCompanyIds = $_SESSION['backend']['adminInfo']['operating_company_ids'];
            if($isStrictlyLimited){
                $arr = ['status'=>true,'adminInfo_operatingCompanyId'=>$adminInfo_operatingCompanyIds];
            }else{
                //除了地上铁人员外，只能查看匹配运营公司的车辆
                if(!$adminInfo_operatingCompanyIds){
                	$adminInfo_operatingCompanyIds = 10000;
                }
				$arr = ['status'=>true,'adminInfo_operatingCompanyId'=>$adminInfo_operatingCompanyIds];
            }
        }
        return $arr;
    }


    /*
     * 检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
     * 注：修改车辆、行驶证管理等车辆管控操作时使用。
     */
    public static function checkOperatingCompanyIsMatch($carId){
        $arr = [];
        $super = $_SESSION['backend']['adminInfo']['super'];
        $username = $_SESSION['backend']['adminInfo']['username'];
        //除了开发人员和超级管理员不受管控外，其他人必须检测
        if(!$super && $username != 'administrator'){
            $adminInfo_operatingCompanyIds = $_SESSION['backend']['adminInfo']['operating_company_ids'];
            if(!$adminInfo_operatingCompanyIds){
            	$adminInfo_operatingCompanyIds = 10000;
            }
            
            $connection = yii::$app->db;
            $data = $connection->createCommand(
            		"select count(*) cnt from cs_car where is_del=0 and id={$carId} and operating_company_id in (0,{$adminInfo_operatingCompanyIds})"
            		)->queryOne();
            //若登录用户所属运营公司不等于车辆所属运营公司
            if($data['cnt'] == 0){
            	$arr = ['status'=>false,'info'=>"对不起，您与该车辆的所属运营公司不匹配，不允许操作！"];
            }
        }
        return $arr;
    }


    /*
     * 获取行编辑时combox中可选的库存车辆-20160325
     * 注：新增、修改企业/个人客户合同和企业/个人试用协议时使用。
     */
    public static function getAvailableStockCars(){
        $carQuery = Car::find()
            ->select(['id', 'plate_number'])
            ->where(['is_del'=>0]);                      //获取行编辑时combox中可选的所有车辆-20161020
        //->where(['car_status' => 'STOCK','is_del'=>0]);   
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany(true);
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
        	$carQuery->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        $stockCars = $carQuery->asArray()->all();
        return $stockCars;
    }
    
    /*
     * 获取行编辑时combox中可选的库存车辆-20160325
    * 注：新增、修改企业/个人客户合同和企业/个人试用协议时使用。
    */
    public static function getAvailableStockCarsVin(){
    	$carQuery = Car::find()
    	->select(['id', 'vehicle_dentification_number'])
    	->where(['car_status' => 'STOCK','is_del'=>0]);
    	//检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
    	$isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany(true);
    	if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
    		$carQuery->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
    	}
    	$stockCars = $carQuery->asArray()->all();
    	return $stockCars;
    }
    
    /**
     * 车辆状态变更条件检查
     * @param unknown_type $carIds
     * @param unknown_type $afterStatus
     * @param unknown_type $condition
     */
    public static function changeCarStatusNewCheck($carIds, $afterStatus, $condition){
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	$returnArr['carIds'] = $carIds;
    	$returnArr['afterStatus'] = $afterStatus;
    	$returnArr['condition'] = $condition;
    	
    	$cars = [];
    	if(!is_array($carIds)){
    		$carIds = [$carIds];
    	}
    	
    	//1.变更条件验证
    	foreach ($carIds as $carId){
    		if($condition){
    			$conditions = ['id'=>$carId];
    			foreach ($condition as $key=>$value){
    				$conditions[$key] = $value;
    			}
    			$preCar = Car::findOne($conditions);
    		}else {
    			$preCar = Car::findOne($carId);
    		}
    		if(!$preCar){
    			$returnArr['info'] = '目标状态'.$afterStatus.'，更改前记录未找到！';
    			return $returnArr;
    		}
    		$preStatus = $preCar->car_status;
    		switch ($afterStatus){
    			case 'NAKED':	//裸车
    				if($preStatus && $preStatus != 'STOCK'){
    					$returnArr['info'] = '目标状态裸车，更改前状态不满足！，'.$preStatus;
    					return $returnArr;
    				}
    				break;
    			case 'STOCK':	//库存
    				if(!in_array($preStatus, ['NAKED','DSTCAR','INTRIAL','LETING','PREPARE','BACK'])){
    					$returnArr['info'] = '目标状态库存，更改前状态不满足！，'.$preStatus;
    					return $returnArr;
    				}
    				break;
    			case 'DSTCAR':	//自用车
    				if(!in_array($preStatus, ['STOCK'])){
    					$returnArr['info'] = '目标状态自用车，更改前状态不满足！，'.$preStatus;
    					return $returnArr;
    				}
    				break;
    			case 'INTRIAL':	//试用中
					if(!in_array($preStatus, ['STOCK'])){
						$returnArr['info'] = '目标状态试用中，更改前状态不满足！，'.$preStatus;
						return $returnArr;
					}
    				break;
    			case 'LETING':	//租赁中
					if(!in_array($preStatus, ['STOCK','PREPARE','BACK'])){
						$returnArr['info'] = '目标状态租赁中，更改前状态不满足！，'.$preStatus;
						return $returnArr;
					}
    				break;
    			case 'BACK':	//退车中
    				if(!in_array($preStatus, ['LETING','STOCK'])){
    					$returnArr['info'] = '目标状态退车中，更改前状态不满足！，'.$preStatus;
    					return $returnArr;
    				}
    				break;
    			case 'PREPARE':	//提车中
    				if(!in_array($preStatus, ['STOCK'])){
    					$returnArr['info'] = '目标状态提车中，更改前状态不满足！，'.$preStatus;
    					return $returnArr;
    				}
    				break;
    			default:
    				$returnArr['info'] = '目标状态不存在！';
    				return $returnArr;
    		}
    	
    		array_push($cars, array('carId' => $carId,'preStatus' => $preStatus));
    	}
    	$returnArr['status'] = true;
    	$returnArr['info'] = '验证成功！';
    	$returnArr['cars'] = $cars;
    	return $returnArr;
    }
    
    /**
     * 车辆状态变更记录
     * @param unknown_type $carIds		车辆IDs
     * @param unknown_type $afterStatus	变更后状态
     * @param unknown_type $codeUrl		变更代码路径
     * @param unknown_type $note		变更说明
     * @param unknown_type $is_lock		是否加锁
     */
	public static function changeCarStatusNew($carIds, $afterStatus, $codeUrl, $note, $condition='', $is_check=false, $is_lock=false){
		$returnArr['status'] = false;
		$returnArr['info'] = '';
		$username = $_SESSION['backend']['adminInfo']['username'];
		if(!is_array($carIds)){
			$carIds = [$carIds];
		}
		//1.变更条件验证
		$statusCheckRet = self::changeCarStatusNewCheck($carIds, $afterStatus, $condition);
		
// 		print_r($statusCheckRet);
// 		exit;
		if(!$statusCheckRet['status']){
			$log = array(
					'oper_time' => date('Y-m-d H:i:s'),
					'code_url' => $codeUrl,
					'oper_user' => $username,
					'info' => $statusCheckRet
					);
			file_put_contents(dirname(__FILE__).'/cacheCarTrack.php.log', json_encode($log) . "\n", FILE_APPEND);
			return $statusCheckRet;
		}
		
		//2.状态变更、记录日志
		$cars = $statusCheckRet['cars'];
		foreach ($cars as $car){
			Car::updateAll(['car_status'=>$afterStatus],['id'=>$car['carId']]);
			yii::$app->db->createCommand()->insert('cs_car_status_change_log', [
					'car_id' => $car['carId'],
					'pre_status' => $car['preStatus'],
					'after_status' => $afterStatus,
					'code_url' => $codeUrl,
					'add_time' => date('Y-m-d H:i:s'),
					'note' => $note,
					'oper_name' => $username
					])->execute();
		}
		$returnArr['status'] = true;
		$returnArr['info'] = '状态变更成功！';
		return $returnArr;
	}

    /**
     * 视情况设置车辆状态
     * 注：在处理车辆故障并改变故障状态时使用。
     */
//     public static function changeCarStatus($carId){
//         //检测车辆是否还有未处理故障
//         $fault = CarFault::find()
//             ->select(['fault_status'])
//             ->where([
//                 'car_id'=>$carId,
//                 'fault_status'=>['RECEIVED','SENT','REPAIRING'],
//                 'is_del'=>0
//             ])
//             ->orderBy('id desc')->asArray()->one();
//         if($fault){
//             //---【1】仍有故障----------------------------
//             //当登记的故障状态除了为'已完结'之外时，都得去同步更改车辆状态。
//             switch ($fault['fault_status']) {
//                 case 'RECEIVED':  //已受理
//                 case 'SENT':      //已送修(车辆故障)
//                     Car::updateAll(['car_status'=>'FAULT'],['id'=>$carId]);
//                     break;
//                 case 'REPAIRING': //维修中(车辆维修中)
//                     Car::updateAll(['car_status'=>'REPAIRING'],['id'=>$carId]);
//                     break;
//             }
//         }else{
//             //---【2】没有故障---------------------------
//             // 1.检测该车辆是否有未归还的"出租"记录（车辆出租中）
//             $nobackRecord = CarLetRecord::find()->select(['id'])->where(['car_id'=>$carId,'back_time'=>0])->one();
//             if($nobackRecord){
//                 Car::updateAll(['car_status'=>'LETING'],['id'=>$carId]);
//                 //车辆状态变更记录
//                 yii::$app->db->createCommand()->insert('cs_car_status_change_log', [
//                 		'car_id' => $carId,
//                 		'add_time' => date('Y-m-d H:i:s'),
//                 		'car_status' => 'LETING',
//                 		'note' => '此车所有故障已修复'
//                 		])->execute();
//                 return;
//             }else{  // 2.再检测该车辆是否有未归还的"试用"记录（车辆试用中）
//                 $nobackRecord = CarTrialProtocolDetails::find()->select(['ctpd_id'])->where(['ctpd_car_id'=>$carId,'ctpd_back_date'=>NULL])->one();
//                 if($nobackRecord){
//                     Car::updateAll(['car_status'=>'INTRIAL'],['id'=>$carId]);
//                     //车辆状态变更记录
//                     yii::$app->db->createCommand()->insert('cs_car_status_change_log', [
//                     		'car_id' => $carId,
//                     		'add_time' => date('Y-m-d H:i:s'),
//                     		'car_status' => 'INTRIAL',
//                     		'note' => '此车所有故障已修复'
//                     		])->execute();
//                     return;
//                 }
//             }
//             //没有未归还的出租或试用记录（车辆库存）
//             Car::updateAll(['car_status'=>'STOCK'],['id'=>$carId]);
//             //车辆状态变更记录
//             yii::$app->db->createCommand()->insert('cs_car_status_change_log', [
//             		'car_id' => $carId,
//             		'add_time' => date('Y-m-d H:i:s'),
//             		'car_status' => 'STOCK',
//             		'note' => '此车所有故障已修复'
//             		])->execute();
//         }
//     }


    /*
     * 查询该车辆当前是否被出租中或试用中，若是则获取该企业/个人客户等信息
     * 注：车辆故障登记和修改时使用。
     */
    public static function checkCarIsLetingOrIntrial($carId){
        // 1.检测该车辆是否有未归还的"出租"记录（车辆出租中）
        $noBackRecord = CarLetRecord::find()
            ->select(['contract_id','cCustomer_id','pCustomer_id'])
            ->where(['car_id'=>$carId,'back_time'=>0])
            ->asArray()->one();
        if($noBackRecord){
            return [
                'status'=>true,
                'contract_id'=>$noBackRecord['contract_id'],
                'protocol_id'=>0,
                'cCustomer_id'=>$noBackRecord['cCustomer_id'],
                'pCustomer_id'=>$noBackRecord['pCustomer_id']
            ];
        }else{
        // 2.再检测该车辆是否有未归还的"试用"记录（车辆试用中）
            $noBackRecord = CarTrialProtocolDetails::find()
                ->select(['ctpd_protocol_id','ctpd_cCustomer_id','ctpd_pCustomer_id'])
                ->where(['ctpd_car_id'=>$carId,'ctpd_back_date'=>NULL])
                ->asArray()->one();
            if($noBackRecord){
                return [
                    'status'=>true,
                    'contract_id'=>0,
                    'protocol_id'=>$noBackRecord['ctpd_protocol_id'],
                    'cCustomer_id'=>$noBackRecord['ctpd_cCustomer_id'],
                    'pCustomer_id'=>$noBackRecord['ctpd_pCustomer_id']
                ];
            }
        }
        //没有未归还的出租或试用记录
        return ['status'=>false];

    }



}