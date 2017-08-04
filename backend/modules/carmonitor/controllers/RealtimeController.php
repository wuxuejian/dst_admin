<?php
/**
 * 车辆实时数据控制器
 * time    2015/11/10 09:48
 * @author wangmin
 */
namespace backend\modules\carmonitor\controllers;
use backend\controllers\BaseController;
use backend\models\TcpCarRealtimeData;
use backend\models\Car;
use backend\models\ConfigCategory;
use backend\models\CarLetRecord;
use backend\models\CarTrialProtocolDetails;
use backend\models\CustomerCompany;
use yii;
use yii\data\Pagination;
class RealtimeController extends BaseController
{

    /**
     * 
     */
    public function actionIndex()
    {
        $buttons  = $this->getCurrentActionBtn();
        $config = (new ConfigCategory)->getCategoryConfig(['car_type','car_status','car_status2'],'value');
        //查询表单select选项
        $searchFormOptions = [];
        if($config['car_status']){
        	$searchFormOptions['car_status'] = [];
        	$searchFormOptions['car_status'][] = ['value'=>'','text'=>'不限'];
        	foreach($config['car_status'] as $val){
        		$searchFormOptions['car_status'][] = ['value'=>$val['value'],'text'=>$val['text']];
        	}
        }
        if($config['car_status2']){
        	$searchFormOptions['car_status2'] = [];
        	$searchFormOptions['car_status2'][] = ['value'=>'','text'=>'不限'];
        	foreach($config['car_status2'] as $val){
        		$searchFormOptions['car_status2'][] = ['value'=>$val['value'],'text'=>$val['text']];
        	}
        }
        return $this->render('index',[
            'buttons'=>$buttons,
            'config'=>$config,
        	'searchFormOptions'=>$searchFormOptions,
        ]);
    }
    /**
     * 按条件导出车辆列表
     */
    public function actionExportWidthCondition()
    {
        $not_car_vins = ['LHB13T3E7FR117627','LGHC7V1D3FJ106311','LGHC7V1D3FJ106316','LGHC7V1D3FJ106318','LGHC7V1D3FJ106319','LGHC7V1D3FJ106348','LGHC7V1D3FJ106349','LGHC7V1D3FJ106357','LGHC7V1D3FJ106352','LGHC7V1D3FJ106353','LGHC7V1D3FJ106341','LGHC7V1D1FJ106351','LGHC7V1D3FJ106333'];
        $query = TcpCarRealtimeData::find()
            ->select([
                '`data_source`',
                '`collection_datetime`',
                '`update_datetime`',
                '`car_vin`',
                '`total_driving_mileage`',
                '`position_effective`',
                '`latitude_type`',
                '`longitude_type`',
                '`longitude_value`',
                '`latitude_value`',
                '`speed`',
                '`direction`',
                '`car_current_status`',
                '`battery_package_soc`',
            	'battery_single_hv_value',
            	'battery_single_lv_value',
            	'battery_package_voltage',
            	'battery_package_current',
            	'battery_single_ht_value',
            	'battery_single_lt_value',
            	'battery_package_total_voltage'
//             ])->andWhere(['<>','car_vin','LHB13T3E7FR117627']);
            ])->andWhere(['not in','car_vin',$not_car_vins]);
        //////查询条件
        $searchCondition = [];
        $searchCondition['plate_number'] = yii::$app->request->get('plate_number');
        $searchCondition['car_status'] = yii::$app->request->get('car_status');
        $searchCondition['car_status2'] = yii::$app->request->get('car_status2');
        if($searchCondition['plate_number'] || $searchCondition['car_status'] || $searchCondition['car_status2']){
            $scCarInfoQuery = Car::find()->select([
                    'car_vin'=>'vehicle_dentification_number'
                ]);
            if($searchCondition['plate_number']){
            	$scCarInfoQuery->andFilterWhere([
            			'like',
            			'`plate_number`',
            			$searchCondition['plate_number']
            		]);
            }
            if($searchCondition['car_status']){
            	$scCarInfoQuery->andFilterWhere([
            			'=',
            			'`car_status`',
            			$searchCondition['car_status']
            			]);
            }
            if($searchCondition['car_status2']){
            	$scCarInfoQuery->andFilterWhere([
            			'=',
            			'`car_status2`',
            			$searchCondition['car_status2']
            			]);
            }
            $scCarInfo = $scCarInfoQuery->asArray()->all(); 
            if(!$scCarInfo){
                return json_encode($returnArr);
            }else{
                $query->andWhere(['car_vin'=>array_column($scCarInfo,'car_vin')]);
            }
            unset($scCarInfo);
        }
        $query->andFilterWhere([
            'like',
            '`car_vin`',
            yii::$app->request->get('car_vin')
        ]);
        $query->andFilterWhere([
            '=',
            '`data_source`',
            yii::$app->request->get('data_source')
        ]);
        $searchCondition['car_current_status'] = yii::$app->request->get('car_current_status');
        $searchCondition['car_current_status_code'] = [
            'stop'=>0,
            'driving'=>1,
            'charging'=>2,
        ];
        switch ($searchCondition['car_current_status']) {
            case 'stop':
            case 'driving':
            case 'charging':
                $statusCode = $searchCondition['car_current_status_code'][$searchCondition['car_current_status']];
                $query->andWhere(['=','`car_current_status`',$statusCode]);
                $query->andWhere(['>','`collection_datetime`',time() - 600]);
                break;
            case 'offline':
                $query->andWhere(['<=','`collection_datetime`',time() - 600]);
                break;
        }
        //当前正在试用或租用的客户条件筛选
        $scCustomerId = yii::$app->request->get('customer_id');
        if($scCustomerId){
            $scLetCarIds = [];
            $scCarInfo = [];
            //获取当前客户正在出租的车辆
            $letCar = CarLetRecord::find()
                ->select(['distinct `car_id`'])
                ->where(['cCustomer_id'=>$scCustomerId,'back_time'=>0])->asArray()->all();
            if($letCar){
                $scLetCarIds = array_column($letCar,'car_id');
            }
            //获取当前客户正在试用的车辆
            $testCar = CarTrialProtocolDetails::find()
                ->select(['distinct `ctpd_car_id`'])
                ->where(['ctpd_cCustomer_id'=>$scCustomerId,'ctpd_back_date'=>'IS NULL'])->asArray()->all();
            if($testCar){
                $scLetCarIds = array_merge($scLetCarIds,array_column($testCar,'ctpd_car_id'));
            }
            if($scLetCarIds){
                $scCarInfo = Car::find()
                    ->select([
                        'id',
                        'car_vin'=>'vehicle_dentification_number',
                    ])->where([
                        'id'=>$scLetCarIds,
                        'is_del'=>0
                    ])->asArray()->all();
            }
            if(!$scCarInfo){
                return json_encode($returnArr);
            }else{
                $query->andWhere(['car_vin'=>array_column($scCarInfo,'car_vin')]);
            }
            unset($scCarInfo);
            unset($scLetCarIds);
            unset($letCar);
            unset($testCar);
        }
        //车辆类型
        $scCarType = yii::$app->request->get('car_type');
        if($scCarType){
            $typeCarIds = [];
            $scCarInfo = Car::find()
                ->select([
                    'id',
                    'car_vin'=>'vehicle_dentification_number',
                ])->where(['car_type'=>$scCarType])
                ->asArray()->all();
            if(!$scCarInfo){
                return json_encode($returnArr);
            }else{
                $query->andWhere(['car_vin'=>array_column($scCarInfo,'car_vin')]);
            }
            unset($scCarInfo);
            unset($scCarType);
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $scCarInfo = Car::find()
                ->select(['car_vin'=>'vehicle_dentification_number'])
                ->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})")
                ->asArray()->all();
            if(!$scCarInfo){
                return json_encode($returnArr);
            }else{
                $query->andWhere(['car_vin'=>array_column($scCarInfo,'car_vin')]);
            }
            unset($scCarInfo);
        }
        //////查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                default:
                    $orderBy = '`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = '`collection_datetime` ';
        }
        $orderBy .= $sortType;
        //排序结束
    	$data = $query->all();
    	$filename = '车辆实时数据监控.csv'; //设置文件名
    	$str = "车牌号,车架号,一级状态,二级状态,数据来源,数据采集时间,累计形式里程(km),电池电量,单体电压最高值,单体电压最低值,电池包最高温度值,电池包最低温度值,总电压,电池包电流,电池包电压数据\n";
    	
    	$car_type_arr = array(1=>'自用车',2=>'备用车');
    	$car_status_arr = array(1=>'已替换',2=>'未替换');
    	$getConfigItem = [
    		'car_status','car_status2'
    	];
    	$config = (new ConfigCategory)->getCategoryConfig($getConfigItem,'value');
    	
    	foreach ($data as $row){
	//	add plate_number
			$carvin = $row['car_vin'];
			$carInfo = (new \yii\db\Query())
				->select(['id','plate_number','car_status','car_status2'])
				->from('cs_car')
				->where(['vehicle_dentification_number' => $carvin,'is_del'=>0])->one();
			
			$car_status = isset($config['car_status'][$carInfo['car_status']]) ? $config['car_status'][$carInfo['car_status']]['text'] : '';
			$car_status2 = isset($config['car_status2'][$carInfo['car_status2']]) ? $config['car_status2'][$carInfo['car_status2']]['text'] : '';
            $total_driving_mileage = $row['total_driving_mileage']==429496736?'无效':$row['total_driving_mileage'];
            $battery_package_soc = $row['battery_package_soc']==102?'无效':$row['battery_package_soc'];
    		$row['collection_datetime'] = date('Y-m-d H:i:s', $row['collection_datetime']);
    		$battery_package_voltage = str_replace(",","，",$row['battery_package_voltage']);
    		$str .= "{$carInfo['plate_number']},{$row['car_vin']},{$car_status},{$car_status2},{$row['data_source']},{$row['collection_datetime']},{$total_driving_mileage},{$battery_package_soc},{$row['battery_single_hv_value']},{$row['battery_single_lv_value']},{$row['battery_single_ht_value']},{$row['battery_single_lt_value']},{$row['battery_package_total_voltage']},{$row['battery_package_current']},{$battery_package_voltage}"."\n";
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
     * 获取车辆实时数据列表
     */
    public function actionGetList()
    {
        $returnArr = [
            'total'=>0,
            'rows'=>[],
        ];
        $not_car_vins = ['LHB13T3E7FR117627','LGHC7V1D3FJ106311','LGHC7V1D3FJ106316','LGHC7V1D3FJ106318','LGHC7V1D3FJ106319','LGHC7V1D3FJ106348','LGHC7V1D3FJ106349','LGHC7V1D3FJ106357','LGHC7V1D3FJ106352','LGHC7V1D3FJ106353','LGHC7V1D3FJ106341','LGHC7V1D1FJ106351','LGHC7V1D3FJ106333'];
        $query = TcpCarRealtimeData::find()
            ->select([
                '`data_source`',
                '`collection_datetime`',
                '`update_datetime`',
                '`car_vin`',
                '`total_driving_mileage`',
                '`position_effective`',
                '`latitude_type`',
                '`longitude_type`',
                '`longitude_value`',
                '`latitude_value`',
                '`speed`',
                '`direction`',
                '`car_current_status`',
                '`battery_package_soc`',
//             ])->andWhere(['<>','car_vin','LHB13T3E7FR117627']);
            ])->andWhere(['not in','car_vin',$not_car_vins]);
        //////查询条件
        $searchCondition = [];
        $searchCondition['plate_number'] = yii::$app->request->get('plate_number');
        $searchCondition['car_status'] = yii::$app->request->get('car_status');
        $searchCondition['car_status2'] = yii::$app->request->get('car_status2');
        if($searchCondition['plate_number'] || $searchCondition['car_status'] || $searchCondition['car_status2']){
            $scCarInfoQuery = Car::find()->select([
                    'car_vin'=>'vehicle_dentification_number'
                ]);
            if($searchCondition['plate_number']){
            	$scCarInfoQuery->andFilterWhere([
            			'like',
            			'`plate_number`',
            			$searchCondition['plate_number']
            		]);
            }
            if($searchCondition['car_status']){
            	$scCarInfoQuery->andFilterWhere([
            			'=',
            			'`car_status`',
            			$searchCondition['car_status']
            			]);
            }
            if($searchCondition['car_status2']){
            	$scCarInfoQuery->andFilterWhere([
            			'=',
            			'`car_status2`',
            			$searchCondition['car_status2']
            			]);
            }
            $scCarInfo = $scCarInfoQuery->asArray()->all(); 
                
            if(!$scCarInfo){
                return json_encode($returnArr);
            }else{
                $query->andWhere(['car_vin'=>array_column($scCarInfo,'car_vin')]);
            }
            unset($scCarInfo);
        }
        $query->andFilterWhere([
            'like',
            '`car_vin`',
            yii::$app->request->get('car_vin')
        ]);
        $query->andFilterWhere([
            '=',
            '`data_source`',
            yii::$app->request->get('data_source')
        ]);
        $searchCondition['car_current_status'] = yii::$app->request->get('car_current_status');
        $searchCondition['car_current_status_code'] = [
            'stop'=>0,
            'driving'=>1,
            'charging'=>2,
        ];
        switch ($searchCondition['car_current_status']) {
            case 'stop':
            case 'driving':
            case 'charging':
                $statusCode = $searchCondition['car_current_status_code'][$searchCondition['car_current_status']];
                $query->andWhere(['=','`car_current_status`',$statusCode]);
                $query->andWhere(['>','`update_datetime`',time() - 600]);
                break;
            case 'offline':
                $query->andWhere(['<=','`update_datetime`',time() - 600]);
                break;
        }
        //当前正在试用或租用的客户条件筛选
        $scCustomerId = yii::$app->request->get('customer_id');
        if($scCustomerId){
            $scLetCarIds = [];
            $scCarInfo = [];
            //获取当前客户正在出租的车辆
            $letCar = CarLetRecord::find()
                ->select(['distinct `car_id`'])
                ->where(['cCustomer_id'=>$scCustomerId,'back_time'=>0])->asArray()->all();
            if($letCar){
                $scLetCarIds = array_column($letCar,'car_id');
            }
            //获取当前客户正在试用的车辆
            $testCar = CarTrialProtocolDetails::find()
                ->select(['distinct `ctpd_car_id`'])
                ->where(['ctpd_cCustomer_id'=>$scCustomerId,'ctpd_back_date'=>'IS NULL'])->asArray()->all();
            if($testCar){
                $scLetCarIds = array_merge($scLetCarIds,array_column($testCar,'ctpd_car_id'));
            }
            if($scLetCarIds){
                $scCarInfo = Car::find()
                    ->select([
                        'id',
                        'car_vin'=>'vehicle_dentification_number',
                    ])->where([
                        'id'=>$scLetCarIds,
                        'is_del'=>0
                    ])->asArray()->all();
            }
            if(!$scCarInfo){
                return json_encode($returnArr);
            }else{
                $query->andWhere(['car_vin'=>array_column($scCarInfo,'car_vin')]);
            }
            unset($scCarInfo);
            unset($scLetCarIds);
            unset($letCar);
            unset($testCar);
        }
        //车辆类型
        $scCarType = yii::$app->request->get('car_type');
        if($scCarType){
            $typeCarIds = [];
            $scCarInfo = Car::find()
                ->select([
                    'id',
                    'car_vin'=>'vehicle_dentification_number',
                ])->where(['car_type'=>$scCarType])
                ->asArray()->all();
            if(!$scCarInfo){
                return json_encode($returnArr);
            }else{
                $query->andWhere(['car_vin'=>array_column($scCarInfo,'car_vin')]);
            }
            unset($scCarInfo);
            unset($scCarType);
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $scCarInfo = Car::find()
	            ->select(['car_vin'=>'vehicle_dentification_number'])
	            ->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})")
	            ->asArray()->all();
            if(!$scCarInfo){
                return json_encode($returnArr);
            }else{
                $query->andWhere(['car_vin'=>array_column($scCarInfo,'car_vin')]);
            }
            unset($scCarInfo);
        }
        //////查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                default:
                    $orderBy = '`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = '`collection_datetime` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy($orderBy)->asArray()->all();
        if($data){
            //查询本页数据车牌号
            $vinArr = array_column($data,'car_vin');
            $carInfo = Car::find()->select([
                    '`plate_number`',
                    '`vehicle_dentification_number`',
                    'car_type',
            		'car_status',
            		'car_status2'
                ])->where([
                    'vehicle_dentification_number'=>$vinArr,
                    'is_del'=>0
                ])->asArray()
                ->indexBy('vehicle_dentification_number')->all();
            foreach($data as &$realTimeDataItem){
                //判断车辆是否离线
                if(time() - $realTimeDataItem['update_datetime'] > 600){
                    $realTimeDataItem['car_current_status'] = 3;
                    $realTimeDataItem['position_effective'] = 1;
                    $realTimeDataItem['direction'] = 0;
                    $realTimeDataItem['speed'] = 0;
                }
                if(isset($carInfo[$realTimeDataItem['car_vin']])){
                    $realTimeDataItem['plate_number'] = $carInfo[$realTimeDataItem['car_vin']]['plate_number'];
                    $realTimeDataItem['car_type'] = $carInfo[$realTimeDataItem['car_vin']]['car_type'];
                    $realTimeDataItem['car_status'] = $carInfo[$realTimeDataItem['car_vin']]['car_status'];
                    $realTimeDataItem['car_status2'] = $carInfo[$realTimeDataItem['car_vin']]['car_status2'];
                }else{
                    $realTimeDataItem['plate_number'] = '';
                    $realTimeDataItem['car_type'] = '';
                    $realTimeDataItem['car_status'] = '';
                    $realTimeDataItem['car_status2'] = '';
                }
            }
        }
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }

    /**
     * 查看详细
     */
    public function actionDetail()
    {
        //post数据请求
        if(yii::$app->request->isPost){
            $carVin = yii::$app->request->post('car_vin');
            $model = TcpCarRealtimeData::findOne(['car_vin'=>$carVin]);
            if($model){
                $data = $model->getOldAttributes();
                //---临时改动-20160329------------------
                $data['battery_package_resistance_value'] = '-';
                $data['accelerator_pedal'] = '-';
                $data['brake_pedal_distance'] = '-';
                $data['air_condition_temperature'] = '-';
                $data['battery_package_power'] = '-';
                //---临时改动-20160329------------------
                return json_encode($data);
            }
            return;
        }
        //post数据请求结束
        $carVin = yii::$app->request->get('car_vin');
        $model = TcpCarRealtimeData::findOne(['car_vin'=>$carVin]);
        if(!$model){
            return 'record not found!';
        }
        $data = $model->getOldAttributes();
        //---临时改动-20160329------------------
        $data['battery_package_resistance_value'] = '-';
        $data['accelerator_pedal'] = '-';
        $data['brake_pedal_distance'] = '-';
        $data['air_condition_temperature'] = '-';
        $data['battery_package_power'] = '-';
        //---临时改动-20160329------------------
        return $this->render('detail',[
            'carVin'=>$carVin,
            'data'=>$data,
            'attributeLabels'=>(new TcpCarRealtimeData)->attributeLabels(),
        ]);
    }

    /**
     * 车辆实时定位
     */
    public function actionRealtimePosition()
    {
        if(yii::$app->request->isPost){
            $returnArr = [
                "status"=>false,
                'msg'=>'',
                'data'=>[],
            ];
            $carVin = yii::$app->request->post('car_vin');
            if(!$carVin){
                $returnArr['msg'] = '参数car_vin缺失！';
                return json_encode($returnArr);
            }
            $data = TcpCarRealtimeData::find()
                ->select([
                    'car_vin','data_source','collection_datetime',
                    'update_datetime',
                    'tdm'=>'total_driving_mileage','longitude_value',
                    'latitude_value','speed',
                    'soc'=>'battery_package_soc','direction'
                ])->where(['car_vin'=>$carVin])->asArray()->one();
            if(!$data){
                $returnArr['msg'] = '无该车辆数据！';
                return json_encode($returnArr);
            }
            //获取车辆车牌
            $carInfo = Car::find()
                ->select([
                    'plate_number'
                ])->where([
                    'vehicle_dentification_number'=>$carVin,
                    'is_del'=>0,
                ])->asArray()->one();
            if($carInfo){
                $data['plate_number'] = $carInfo['plate_number'];
            }else{
                $data['plate_number'] = '';
            }
            if($data['collection_datetime']){
                $data['collection_datetime'] = date('Y-m-d H:i:s',$data['collection_datetime']);
            }
            $returnArr['status'] = true;
            $returnArr['data'] = $data;
            return json_encode($returnArr);
        }else{
            $carVin = yii::$app->request->get('car_vin') or die('param car_vin is required!');
            $config = (new ConfigCategory)->getCategoryConfig(['bmap_api_addr','bmap_geoconv_addr']);
            $_config = [];
            foreach($config as $key=>$val){
                $_config[$key] = array_values($val)[0]['value'];
            }
            return $this->render('realtime-position',[
                'carVin'=>$carVin,
                '_config'=>$_config
            ]);
        }
        
    }


    /**
     * 查看车辆运行轨迹
     */
    public function actionCarTrack()
    {
        $carVin = yii::$app->request->get('car_vin');
        if(!$carVin){
            return '缺少必要参数！';
        }
        $day = yii::$app->request->get('day');
        if(!$day){
            $day = date('Y-m-d');
        }
        //判断该年份的“车辆运行轨迹缓存表”是否存在
        $connection = yii::$app->db1;
        $nowTable = 'cs_cache_car_track_' . substr($day,0,4);
        $tables = $connection->createCommand("SHOW TABLES LIKE '{$nowTable}'")->queryAll();
        if(!$tables){
            return "缓存表（{$nowTable}）不存在！";
        }
        $dayTimeStamp = strtotime($day);
        //保存该月有轨迹的日期、相邻三日有轨迹的时点
        $selectMonthHasDataDay = [];
        $threeDayHasDataHour = [
            date('Y-m-d',$dayTimeStamp-86400)=>[],
            date('Y-m-d',$dayTimeStamp)=>[],
            date('Y-m-d',$dayTimeStamp+86400)=>[]
        ];
        //查找出该车辆的当前月份的轨迹缓存数据
        $res = (new \yii\db\Query())
            ->select(['*'])
            ->from($nowTable)
            ->where(
                '`car_vin` = :car_vin AND `month` = :month',
                [':car_vin'=>$carVin, ':month'=>substr($day,5,2)]
            )
            ->one($connection);
        if($res){
            unset($res['id']);
            unset($res['car_vin']);
            unset($res['month']);
            $hasDataDays = [];
            foreach($res as $dayKey=>$trackHours){
                if($trackHours){
                    $ymd = substr($day,0,7) . '-' . substr($dayKey,-2);
                    $hasDataDays[$ymd] = $trackHours;
                }
            }
            $selectMonthHasDataDay = array_keys($hasDataDays);
            foreach($threeDayHasDataHour as $d=>$v){
                if(isset($hasDataDays[$d])){
                    $hours = explode('|',$hasDataDays[$d]);
                    foreach($hours as $h){
                        $threeDayHasDataHour[$d][$h] = strtotime($d.' '.$h.':00:00');
                    }
                }else{
                    //若相邻三日有不属当前月而且比当日小，则一定为以往某月的某日
                    if($d < date('Y-m-d')){
                        $dd = substr($d,-2);
                        $res = (new \yii\db\Query())
                            ->select(['month',"day_{$dd}"])
                            ->from($nowTable)
                            ->where(
                                '`car_vin` = :car_vin AND `month` = :month',
                                [':car_vin'=>$carVin, ':month'=>substr($d,5,2)]
                            )
                            ->one($connection);
                        if($res){
                            $hours = explode('|',$res["day_{$dd}"]);
                            foreach($hours as $h){
                                $threeDayHasDataHour[$d][$h] = strtotime($d.' '.$h.':00:00');
                            }
                        }
                    }
                }
                //若相邻三日包含有今日，则这里再单独查询出当前小时是否存在轨迹数据，因为缓存表只统计到上一小时为止。
                if($d == date('Y-m-d')){
                    $tabName = 'cs_tcp_car_history_data_' . date('Ym') . '_' . substr($carVin,-1);
                    $tabRes = $connection->createCommand("SHOW TABLES LIKE '{$tabName}'")->queryOne();
                    if($tabRes){
                        $curHourTimes = strtotime(date('Y-m-d H:00:00'));
                        $curHour = date('H',$curHourTimes);
                        $sql = "SELECT `id` FROM `{$tabName}` WHERE `car_vin` = '{$carVin}' AND `speed` > 0 AND `longitude_value` > 0 AND `latitude_value` > 0 AND `collection_datetime` > {$curHourTimes} LIMIT 1";
                        $result = $connection->createCommand($sql)->queryOne();
                        if($result){
                            $threeDayHasDataHour[$d][$curHour] = strtotime($d.' '.$curHour.':00:00');
                        }
                    }
                }
            }
        }
        return $this->render('car-track',[
            'carVin'=>$carVin,
            'selectMonthHasDataDay'=>$selectMonthHasDataDay,//本月份有轨迹的日期
            'threeDayHasDataHour'=>$threeDayHasDataHour,//所选时间前后一天有轨迹的小时
            'day'=>$dayTimeStamp,
        ]);
    }

    /**
     * 载入车辆运行轨迹地图
     */
    public function actionCarTrackMap(){
        $carVin = yii::$app->request->get('car_vin') or die('param car_vin is required');
        $startDate = yii::$app->request->get('start_date');
        if(!$startDate){
            $startDate = strtotime(date('Y-m-d H').":00:00");
        }
        //var_dump(date('Y-m-d H:i:s',$startDate));
        $endDate = yii::$app->request->get('end_date');
        if(!$endDate){
            $endDate = $startDate + 3600;
        }else{
            $endDate += 3600;
        }
        //var_dump(date('Y-m-d H:i:s',$endDate));
        //最大跨度不超过十天
        if($endDate - $startDate > 864000){
            $endDate = $startDate + 3600;
        }
        if(date('Ym',$startDate) == date('Ym') || date('Ym',$startDate) == date("Ym",strtotime("-1 month"))){
            $connection = yii::$app->db1;
        }else{
            $connection = yii::$app->db2;
        }
        $tables = $connection->createCommand('show tables')->queryAll();
        if(!$tables){
            return '<script>window.parent.$.messager.alert("操作失败","所选时段无轨迹数据！","error");</script>';
        }
        $tables = array_column($tables,'Tables_in_car_monidata');
        if($startDate < strtotime('2016-04-01')){
            //2016年4月1日之前的数据
            $nowTable = 'cs_tcp_car_history_data_'.date('Ym',$startDate);
        }else{
            //2016年4月1日之后的数据
            $nowTable = 'cs_tcp_car_history_data_'.date('Ym',$startDate).'_'.substr($carVin,-1);
        }
        if(array_search($nowTable,$tables) === false){
            //不存在该数据表
            return '<script>window.parent.$.messager.alert("操作失败","所选时段无轨迹数据！","error");</script>';
        }
        $query = (new \yii\db\Query())
            ->from($nowTable)
            ->select([
                'collection_datetime','car_vin',
                'longitude_value','latitude_value','speed','battery_package_soc'
            ])->where('car_vin = :car_vin and collection_datetime >= :collection_datetime_s and  collection_datetime <= :collection_datetime_e and speed > 0 and longitude_value > 0 and latitude_value > 0',[
                ':car_vin'=>$carVin,
                ':collection_datetime_s'=>$startDate,
                ':collection_datetime_e'=>$endDate,
            ]);//->createCommand()->sql;
            //var_dump($query);
            //die;
        /*$total = $query->count('*',$connection);
        if($total == 0){
            return '<script>window.parent.$.messager.alert("操作失败","所选时段无轨迹数据！","error");</script>';
        }*/
        $trackData = $query->orderBy('`collection_datetime` asc')->all($connection);
        /*if($total >= 500){
            echo '<script>window.parent.$.messager.show({"title":"记录超出","msg": "超过500个轨迹记录轨迹可能不精确！"})</script>';
            $pre = intval($total / 500);
            foreach($trackData as $k=>$v){
                if($k % $pre != 0){
                    unset($trackData[$k]);
                }
            }
            $trackData = array_values($trackData);
        }*/
        $config = (new ConfigCategory)->getCategoryConfig(['bmap_api_addr','bmap_ls_addr','bmap_geoconv_addr']);
        $_config = [];
        foreach($config as $key=>$val){
            $_config[$key] = array_values($val)[0]['value'];
        }
        //查出车牌号
        $carInfo = Car::find()->select('plate_number')->where(['vehicle_dentification_number'=>$carVin,'is_del'=>0])->asArray()->one();
        return $this->render('car-track-map',[
            'trackData'=>$trackData,
            'plate_number'=>$carInfo['plate_number'],
            'config'=>$_config,
            'playSpeed'=>40 * intval(yii::$app->request->get('playSpeed')),
            'trackCatch'=>intval(yii::$app->request->get('trackCatch')),
        ]);
    }

    /**
     * 查看车辆分布图
     */
    public function actionCarDistribution(){
        //获取车型
        $config = (new ConfigCategory)->getCategoryConfig(['car_type']);
        return $this->render('car-distribution',[
            'config'=>$config
        ]);
    }

    /**
     * 车辆分布地图
     */
    public function actionCarDistributionMap(){
        $config = (new ConfigCategory)->getCategoryConfig(['bmap_api_addr','bmap_geoconv_addr']);
        $_config = [];
        foreach($config as $key=>$val){
            $_config[$key] = array_values($val)[0]['value'];
        }
        //获取数据
        $arModel = TcpCarRealtimeData::find()
            ->select([
                '`car_vin`',
                '`collection_datetime`',
                '`longitude_value`',
                '`latitude_value`',
                '`speed`',
                '`battery_package_soc`'
            ]);
        //////查询条件开始
        $searchCondition = [];
        $searchCondition['car_current_status'] = yii::$app->request->get('car_current_status');
        if(is_null($searchCondition['car_current_status'])){
            $searchCondition['car_current_status'] = 'driving';
        }
        $searchCondition['car_current_status_code'] = [
            'stop'=>0,
            'driving'=>1,
            'charging'=>2,
        ];
        switch ($searchCondition['car_current_status']) {
            case 'stop':
            case 'driving':
            case 'charging':
                $statusCode = $searchCondition['car_current_status_code'][$searchCondition['car_current_status']];
                $arModel->andWhere(['car_current_status'=>$statusCode]);
                $arModel->andWhere(['>=','collection_datetime',time() - 600]);
                break;
            case 'offline':
                $arModel->andWhere(['<','collection_datetime',time() - 600]);
                break;
        }
        //当前正在试用或租用的客户条件筛选
        $scCustomerId = yii::$app->request->get('customer_id');
        if($scCustomerId){
            $csLetCarIds = [];
            //获取当前客户正在出租的车辆
            $csLetCarInfo = CarLetRecord::find()
                ->select(['distinct `car_id`'])
                ->where(['cCustomer_id'=>$scCustomerId,'back_time'=>0])->asArray()->all();
            if($csLetCarInfo){
                $csLetCarIds = array_column($csLetCarInfo,'car_id');
            }
            //获取当前客户正在试用的车辆
            $csTestCarInfo = CarTrialProtocolDetails::find()
                ->select(['distinct `ctpd_car_id`'])
                ->where(['ctpd_cCustomer_id'=>$scCustomerId,'ctpd_back_date'=>'IS NULL'])->asArray()->all();
            if($csTestCarInfo){
                $csLetCarIds = array_merge($csLetCarIds,array_column($csTestCarInfo,'ctpd_car_id'));
            }
            $csCarInfo = [];
            if($csLetCarIds){
                $csCarInfo = Car::find()
                    ->select([
                        'car_vin'=>'vehicle_dentification_number'
                    ])->where(['id'=>$csLetCarIds])
                    ->asArray()->all();
            }
            if($csCarInfo){
                $arModel->andWhere(['car_vin'=>array_column($csCarInfo,'car_vin')]);
            }else{
                return $this->render('car-distribution-map',[
                    'config'=>$_config,
                    'realTimeData'=>[]
                ]);
            }
            unset($csLetCarIds);
            unset($csCarInfo);
            unset($csLetCarInfo);
            unset($csTestCarInfo);
        }
        //车辆类型
        $csCarType = yii::$app->request->get('car_type');
        if($csCarType){
            $csCarInfo = [];
            $csCarInfo = Car::find()
                ->select([
                    'car_vin'=>'vehicle_dentification_number'
                ])->where(['car_type'=>$csCarType])
                ->asArray()->all();
            if($csCarInfo){
                $arModel->andWhere(['car_vin'=>array_column($csCarInfo,'car_vin')]);
            }else{
                return $this->render('car-distribution-map',[
                    'config'=>$_config,
                    'realTimeData'=>[],
                ]);
            }
            unset($csCarInfo);
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $scCarInfo = Car::find()
	            ->select(['car_vin'=>'vehicle_dentification_number'])
	            ->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})")
	            ->asArray()->all();
            if($scCarInfo){
                $arModel->andWhere(['car_vin'=>array_column($scCarInfo,'car_vin')]);
            }else{
                return $this->render('car-distribution-map',[
                    'config'=>$_config,
                    'realTimeData'=>[]
                ]);
            }
            unset($scCarInfo);
        }
        //////查询条件结束
        $realTimeData = $arModel->asArray()->all();
        if($realTimeData){
            //查询车辆基本信息
            $carInfo = Car::find()
                ->select([
                    'plate_number',
                    'car_vin'=>'vehicle_dentification_number'
                ])->where([
                    'vehicle_dentification_number'=>array_column($realTimeData,'car_vin'),
                    'is_del'=>0
                ])->asArray()->indexBy('car_vin')->all();
            foreach($realTimeData as $key=>$val){
                if($carInfo && isset($carInfo[$val['car_vin']])){
                    $realTimeData[$key]['plate_number'] = $carInfo[$val['car_vin']]['plate_number'];
                }else{
                    $realTimeData[$key]['plate_number'] = '';
                }
            }
        }
        
//         $realTimeData =  array();
//         array_push($realTimeData, [
// 				            'car_vin' => 'LGHC7V1D0FJ106232',
// 				            'collection_datetime' => '1495753969',
// 				            'longitude_value' => '113.8244629',
// 				            'latitude_value' => '22.6676521',
// 				            'speed' => '0.0',
// 				            'battery_package_soc' => '99.6',
// 				            'plate_number' => '粤BCG402'
// 				        ]);
//         array_push($realTimeData, [
//         		'car_vin' => 'LGHC7V1D0FJ106232',
//         		'collection_datetime' => '1495753969',
//         		'longitude_value' => '113.8244629',
//         		'latitude_value' => '22.6676521',
//         		'speed' => '0.0',
//         		'battery_package_soc' => '99.6',
//         		'plate_number' => '粤BCG402'
//         		]);
        return $this->render('car-distribution-map',[
            'config'=>$_config,
            'realTimeData'=>$realTimeData
        ]);
    }

    /**
     * 获取当前正在租车或正在试用的客户
     * 本方法无权限控制
     */
    public function actionGetLetingCustomer(){
        $returnArr = [
            'total'=>0,
            'rows'=>[],
        ];
        $letCuteromer = CarLetRecord::find()->select(['distinct `cCustomer_id`'])
            ->where(['back_time'=>0])->asArray()->all();
        $testCustomer = CarTrialProtocolDetails::find()
            ->select(['distinct `ctpd_cCustomer_id`'])
            ->where(['ctpd_back_date'=>'IS NULL'])->asArray()->all();
        $customerId = [];
        if($letCuteromer){
            $customerId = array_column($letCuteromer,'cCustomer_id');
        }
        if($testCustomer){
            $customerId = array_merge(array_column($letCuteromer,'ctpd_cCustomer_id'));
        }
        $customerId = array_unique($customerId);
        $query = CustomerCompany::find()
            ->select(['id','number','company_name'])
            ->where(['id'=>$customerId]);
        //查询条件开始
        if(yii::$app->request->get('q')){
            $query->andWhere(['like','company_name',yii::$app->request->get('q')]);
        }
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
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $returnArr['total'] = $total;
        $returnArr['rows'] = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy($orderBy)->asArray()->all();
        return json_encode($returnArr);
    }

}