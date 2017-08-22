<?php
/**
 * 车辆实时数据控制器（国标）
 * time    2017/8/7 09:48
 * @author pengyl
 */
namespace backend\modules\carmonitorgb\controllers;
use yii\db\MongoDBNew;

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
		$config = (new ConfigCategory)->getCategoryConfig(['car_type'],'value');
		return $this->render('index',[
				'buttons'=>$buttons,
				'config'=>$config
				]);
	}
	/**
	 * 获取车辆实时数据列表
	 */
	public function actionGetList()
	{
		$car_vin = yii::$app->request->get('car_vin');
		$company_no = yii::$app->request->get('company_no');
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
			
		$db = new MongoDBNew('car_realtime_data');
		$filter = [];
		$options = [
			'projection' => [
					'_id' => 0
				],
			'sort' => ['_id' => -1],
		];
		//查询条件
		if($car_vin){
			$filter['carVin'] = new \MongoDB\BSON\Regex(".*{$car_vin}.*", '');
		}
		if($company_no){
			$filter['companyNo'] = (int)$company_no;
		}
		//////查询条件结束
		//排序开始
		$sortColumn = yii::$app->request->get('sort');
		$sortType = yii::$app->request->get('order') == 'asc' ? '1' : '-1';
		if($sortColumn){
			$options['sort'] = [$sortColumn => (int)$sortType];
		}
		//////排序结束
		//     	print_r($db->getSql());
		//     	exit;
			
		$total = $db->getCount($filter);
		$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
		$options['skip'] = $pages->offset;
		$options['limit'] = $pageSize;
		$data = $db->query($filter,$options);
		//数据格式化
		foreach ($data as $index=>$row){
			$row->speed = number_format($row->speed,2);
			$row->totalDrivingMileage = number_format($row->totalDrivingMileage,2);
			$data[$index] = $row;
		}

		$returnArr['rows'] = $data;
		$returnArr['total'] = $total;
		return json_encode($returnArr);
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
			
			$db = new MongoDBNew('car_realtime_data');
			$filter = [];
			$options = [
				'projection' => [
					'_id'=>0,
					'collectionDatetime'=>1,'carVin'=>1,'updateDatetime'=>1,'totalDrivingMileage'=>1,
					'longitudeValue'=>1,'latitudeValue'=>1,'speed'=>1,'soc'=>1
				],
				'limit' => 1
			];
			//查询条件
			$filter['carVin'] = $carVin;
			$data = $db->queryOne($filter, $options);
			if(!$data){
				$returnArr['msg'] = '无该车辆数据！';
				return json_encode($returnArr);
			}
			//数据格式化
			$data['speed'] = number_format($data['speed'],1);
			$data['moterVoltage'] = number_format($data['moterVoltage'],1);
			$data['moterCurrent'] = number_format($data['moterCurrent'],1);
			$data['batteryPackageTotalVoltage'] = number_format($data['batteryPackageTotalVoltage'],1);
			$data['batteryPackageCurrent'] = number_format($data['batteryPackageCurrent'],1);
			//数据格式化end
			if($data['collectionDatetime']){
				$data['collectionDatetime'] = date('Y-m-d H:i:s',$data['collectionDatetime']);
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
		$nowTable = 'cs_cache_new_car_track_' . substr($day,0,4);
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
					$tabName = 'cs_tcp_new_car_history_data_' . date('Ym') . '_' . substr($carVin,-1);
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
			$endDate = $startDate + 864000;
		}
		$nowTable = 'car_history_data_'.date('Ym',$startDate).'_'.substr($carVin,-1);
		
		//根据$carVin获取车型
		$db = new MongoDBNew('car_vin_device');
		$filter = ['carVin' => $carVin];
		$options = [
			'projection' => [
				'_id' => 0,'carType'=>1
			]
		];
		$car = $db->queryOne($filter,$options);
		if($car){
			$carType = $car['carType'];
		}
		//end
		
		$db = new MongoDBNew($nowTable);
		$filter = [];
		$options = [
			'projection' => [
				'_id' => 0,'collectionDatetime'=>1,'carVin'=>1,'longitudeValue'=>1,'latitudeValue'=>1,'speed'=>1,'soc'=>1
			],
			'sort' => ['collectionDatetime' => 1],
		];
		//查询条件
		$filter = [
			'carVin' => $carVin,
			'collectionDatetime' => ['$gte' => (int)$startDate,'$lte' => (int)$endDate],
			'moterSpeed' => ['$gt' => 0],
			'longitudeValue' => ['$gt' => 0],
			'latitudeValue' => ['$gt' => 0]
		];
// 		if($carType && $carType==1){	//大通EV80
// 			unset($filter['speed']);
// 		}
		//////查询条件结束
		$trackData = $db->query($filter,$options);
		//end
		//格式化数据
// 		foreach ($trackData as $index=>$row){
// 			unset($row['_id']);
// 			foreach ($row as $key=>$value){
// 				if($row[$key]->value){
// 					$row[$key] = $row[$key]->value;
// 				}
// 			}
// 			$trackData[$index] = $row;
// 		}
// 		print_r($trackData);
// 		exit;
		
		$config = (new ConfigCategory)->getCategoryConfig(['bmap_api_addr','bmap_ls_addr','bmap_geoconv_addr']);
		$_config = [];
		foreach($config as $key=>$val){
			$_config[$key] = array_values($val)[0]['value'];
		}
		return $this->render('car-track-map',[
				'trackData'=>$trackData,
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
		$db = new MongoDBNew('car_realtime_data');
		$filter = [];
		$options = [
			'projection' => [
				'_id'=>0,
				'collectionDatetime'=>1,'carVin'=>1,'speed'=>1,'soc'=>1,
				'longitudeValue'=>1,'latitudeValue'=>1
			]
		];
		//查询条件
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
		/**
			停止，同时满足车辆启动、速度为0、采集时间大于-600、非充电状态
			行驶，同时满足速度大于、采集时间大于-600
			充电，=充电状态、采集时间大于-600
			离线，<当前时间-600
		 */
		switch ($searchCondition['car_current_status']) {
			case 'stop':
				$filter = [
					'carStatus' => 1,
					'speed' => 0,
					'collectionDatetime' => ['$gte' => (time() - 600)],
					'carChargeStatus' => ['$ne' => 2]
				];
// 				$db->where(['carStatus' => 1,'speed' => 0]);
// 				$db->where_gte('collectionDatetime', time() - 600);
// 				$db->where_ne('carChargeStatus',2);
				break;
			case 'driving':
				$filter = [
					'speed' => ['$gt' => 0],
					'collectionDatetime' => ['$gte' => (time() - 600)]
				];
// 				$db->where_gt('speed', 0);
// 				$db->where_gte('collectionDatetime', time() - 600);
				break;
			case 'charging':
				$filter = [
					'carChargeStatus' => 2,
					'collectionDatetime' => ['$gte' => (time() - 600)]
				];
// 				$db->where(['carChargeStatus' => 2]);
// 				$db->where_gte('collectionDatetime', time() - 600);
				break;
			case 'offline':
				$filter = [
					'collectionDatetime' => ['$lt' => (time() - 600)]
				];
// 				$db->where_lt('collectionDatetime', time() - 600);
				break;
		}
		$car_vins = yii::$app->request->get('car_vins');
		if($car_vins){
			$filter['carVin'] = ['$in' => explode(',',$car_vins)];
// 			$db->where_in('carVin', explode(',',$car_vins));
		}
		
// 		print_r($db->getSql());
// 		exit;
		//////查询条件结束
		$realTimeData = $db->query($filter,$options);
		//end
		//格式化数据
		foreach ($realTimeData as $index=>$row){
			$row->speed = number_format($row->speed,1);
// 			print_r($row);
// 			exit;
// 			$data['speed'] = number_format($data['speed'],1);
		}
		return $this->render('car-distribution-map',[
				'config'=>$_config,
				'realTimeData'=>$realTimeData
				]);
	}
	
	/**
	 * 查看详细
	 */
	public function actionDetail()
	{
		$carVin = yii::$app->request->get('car_vin')?yii::$app->request->get('car_vin'):yii::$app->request->post('car_vin');
		$db = new MongoDBNew('car_realtime_data');
		//查询条件
		$filter['carVin'] = $carVin;
		$data = $db->queryOne($filter);
		//数据格式化
		$data['speed'] = number_format($data['speed'],2);
		$data['moterVoltage'] = number_format($data['moterVoltage'],1);
		$data['moterCurrent'] = number_format($data['moterCurrent'],1);
		$data['batteryPackageTotalVoltage'] = number_format($data['batteryPackageTotalVoltage'],1);
		$data['batteryPackageCurrent'] = number_format($data['batteryPackageCurrent'],1);
		$data['batterySingleHvValue'] = number_format($data['batterySingleHvValue'],3);
		$data['batterySingleLvValue'] = number_format($data['batterySingleLvValue'],3);
		
		foreach ($data['battteryVoltageData'] as $index => $row){
			foreach ($row->battteryVoltageList as $index2 => $battteryVoltage){
				$row->battteryVoltageList[$index2] = number_format($battteryVoltage,3);
			}
			$data['battteryVoltageData'][$index] = $row;
		}
		//post数据请求
		if(yii::$app->request->isPost){
			return json_encode($data);
		}
		
		return $this->render('detail',[
				'carVin'=>$carVin,
				'data'=>$data,
				]);
	}
	
	/**
	 * 按条件导出车辆列表
	 */
	public function actionExportWidthCondition()
	{
		$car_vin = yii::$app->request->get('car_vin');
		$company_no = yii::$app->request->get('company_no');
		$db = new MongoDBNew('car_realtime_data');
		$filter = [];
		$options = [
			'projection' => [
					'_id' => 0,
					'_class' => 0,
					'dataHex' => 0,
				],
			'sort' => ['_id' => -1],
		];
		//查询条件
		if($car_vin){
			$filter['carVin'] = new \MongoDB\BSON\Regex(".*{$car_vin}.*", '');
		}
		if($company_no){
			$filter['companyNo'] = (int)$company_no;
		}
		//////查询条件结束
		$data = $db->query($filter,$options);
		//数据格式化
		foreach ($data as $index=>$row){
			$row->speed = number_format($row->speed,1);
			$data[$index] = $row;
		}
		
		$filename = '车辆实时数据监控(国标).csv'; //设置文件名
		$str = "车架号,设备号,数据来源,数据采集时间,累计形式里程(km),电池电量,单体电压最高值,单体电压最低值,电池包最高温度值,电池包最低温度值,总电压,电池包电流,电池包电压数据\n";
		$companyNoArr = array(1=>'福嘉太',2=>'G7');
		foreach ($data as $row){
			$companyName = @$companyNoArr[$row->companyNo];
			$totalDrivingMileage = $row->totalDrivingMileage;
			$soc = $row->soc;
			$collectionDatetime = date('Y-m-d H:i:s', $row->collectionDatetime);
			$battteryVoltageData = str_replace(",","，",json_encode($row->battteryVoltageData));
			$carVin = $row->carVin.'	';
			$deviceNo = $row->deviceNo.'	';
			$str .= "{$carVin},{$deviceNo},{$companyName},{$collectionDatetime},{$totalDrivingMileage},{$soc},{$row->batterySingleHvValue},{$row->batterySingleLvValue},{$row->batterySingleHtValue},{$row->batterySingleLtValue},{$row->batteryPackageTotalVoltage},{$row->batteryPackageCurrent},{$battteryVoltageData}"."\n";
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
}