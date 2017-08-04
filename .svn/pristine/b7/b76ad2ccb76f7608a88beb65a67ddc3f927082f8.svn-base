<?php
/**
 * 对于客户，运维评估模型（统计）
 * @author pengyl
 *
 */
namespace backend\modules\system\controllers;
use backend\classes\MyUploadFile;

use backend\classes\Approval;
use backend\classes\Mail;
use backend\classes\CarStatus;

use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use yii\web\UploadedFile;
use common\models\Excel;
use backend\models\CarLetRecord;
use backend\models\CarTrialProtocolDetails;
use common\classes\CarRealtimeDataAnalysis;
class TmpStatisticsController extends BaseController
{
	public function init(){
		set_time_limit(0);
	}
	function log($file,$content){
		file_put_contents("./{$file}",date('Y-m-d H:i:s')." | ".$content."\n",FILE_APPEND);
	}
	//东风9月、10月、11月、12月行驶里程统计
	public function actionTotalDrivingMileage(){
		$str = "车架号,9月,10月,11月,12月\n";
		$brand_id = 7;
		$connection = yii::$app->db;
		$sql = 'select vehicle_dentification_number from cs_car where brand_id='.$brand_id;
		$data = $connection->createCommand($sql)->queryAll();
		$this->log('TmpStatistics_TotalDrivingMileage_log.txt', '统计开始...');
		$this->log('TmpStatistics_TotalDrivingMileage_log.txt', '共'.count($data).'台车');
		$connection2 = yii::$app->db2;
		
		$months = array('09','10','11','12');
		foreach ($data as $row){
			$carVin = $row['vehicle_dentification_number'];
			$str .= $carVin.',';
			foreach ($months as $month){	//获取单辆车每月行驶里程
				$table = 'cs_tcp_car_history_data_2016'.$month.'_'.substr($carVin,-1);
				$sql = "select data_hex from {$table} where car_vin='{$carVin}' limit 2";
				$moniDataItems = $connection2->createCommand($sql)->queryAll();
				$total_driving_mileage = 0;	//车辆行驶里程
				foreach ($moniDataItems as $moniDataItem){
					//解析数据
					$dataAnalysisObj = new CarRealtimeDataAnalysis($moniDataItem['data_hex']);
					$total_driving_mileage = $dataAnalysisObj->getRealtimeData()['total_driving_mileage'];
					if($total_driving_mileage){
						break;
					}
				}
				$str .= $total_driving_mileage;
				if($month != 12){
					$str.=',';
				}else {
					$str.="\n";
				}
			}
			$this->log('TmpStatistics_TotalDrivingMileage_log.txt', $carVin.' '.$total_driving_mileage);
		}
		
		$str = mb_convert_encoding($str, "GBK", "UTF-8");
		$this->export_csv('行驶里程统计.csv',$str); //导出
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
	 * 服务的完结情况
	 */
	public function actionRepair()
	{
		/**
		 * select a.id,a.tel_time,b.* from oa_repair a RIGHT JOIN
			 (select a.cCustomer_id,a.let_time,a.back_time,b.plate_number from cs_car_let_record a left join cs_car b on a.car_id=b.id and a.is_del=0) as b
		 on a.car_no=b.plate_number and ((a.tel_time>=b.let_time and a.tel_time<=b.back_time) or (a.tel_time>=b.let_time and b.back_time=0))
		where  cCustomer_id=167 and a.id is not null
		 * 如要显示无记录车辆，请用以上语句
		 */
		$connection = yii::$app->db;
		$sql = 'select a.id,a.tel_time,b.* from oa_repair a left join
			 (select a.cCustomer_id,a.let_time,a.back_time,b.plate_number from cs_car_let_record a left join cs_car b on a.car_id=b.id and a.is_del=0) as b
		 on a.car_no=b.plate_number and ((a.tel_time>=b.let_time and a.tel_time<=b.back_time) or (a.tel_time>=b.let_time and b.back_time=0))
		where cCustomer_id is not null';
		$data = $connection->createCommand($sql)->queryAll();
// 		print_r($data);
// 		exit;
		
		$statisticsList = [];
		foreach ($data as $row){
			$year = date("Y",$row['tel_time']);
			$month = date("n",$row['tel_time']);
			// 			$customer_id = $row['cCustomer_id'];
			$customer_id = $row['cCustomer_id'];
			$plate_number = $row['plate_number'];
			if(@!$statisticsList[$customer_id.'_'.$year.'_'.$month.'_'.$plate_number]){
				$statisticsList[$customer_id.'_'.$year.'_'.$month.'_'.$plate_number] = 1;
			}else {
				$statisticsList[$customer_id.'_'.$year.'_'.$month.'_'.$plate_number] += 1;
			}
		}
// 		print_r($statisticsList);
// 		exit;
		foreach ($statisticsList as $key=>$row){
			//客户_年份_月份_车牌
			$customer_year_month_car = explode("_",$key);
			$sql = "select id from tmp_repair_statistics where customer_id={$customer_year_month_car[0]} and year={$customer_year_month_car[1]} and car_no='{$customer_year_month_car[3]}'";
			$data = $connection->createCommand($sql)->queryOne();
			if(!$data){
				$connection->createCommand()->insert('tmp_repair_statistics', [
						'customer_id' => $customer_year_month_car[0],
						'year' => $customer_year_month_car[1],
						'month'.$customer_year_month_car[2] => $row,
						'car_no' => $customer_year_month_car[3]
						])->execute();
			}else {
				$sql = "update tmp_repair_statistics set month{$customer_year_month_car[2]}=month{$customer_year_month_car[2]}+{$row} where id={$data['id']}";
				$query = $connection->createCommand($sql)->execute();
			}
		}
	}
	/**
	 * 车辆自身故障情况
	 */
	public function actionFault(){
		$connection = yii::$app->db;
		$sql = 'select a.id,UNIX_TIMESTAMP(a.f_datetime) f_datetime,b.* from cs_car_fault a left join
	(select a.cCustomer_id,a.let_time,a.back_time,a.car_id,b.plate_number from cs_car_let_record a left join cs_car b on a.car_id=b.id and a.is_del=0) as b
		on a.car_id=b.car_id and ((UNIX_TIMESTAMP(a.f_datetime)>=b.let_time and UNIX_TIMESTAMP(a.f_datetime)<=b.back_time) or (UNIX_TIMESTAMP(a.f_datetime)>=b.let_time and b.back_time=0))
	where b.cCustomer_id is not null and a.is_del=0';
		$data = $connection->createCommand($sql)->queryAll();
		// 		print_r($data);
		// 		exit;
		
		$statisticsList = [];
		foreach ($data as $row){
			$year = date("Y",$row['f_datetime']);
			$month = date("n",$row['f_datetime']);
			$customer_id = $row['cCustomer_id'];
			$plate_number = $row['plate_number'];
			if(@!$statisticsList[$customer_id.'_'.$year.'_'.$month.'_'.$plate_number]){
				$statisticsList[$customer_id.'_'.$year.'_'.$month.'_'.$plate_number] = 1;
			}else {
				$statisticsList[$customer_id.'_'.$year.'_'.$month.'_'.$plate_number] += 1;
			}
		}
		// 		print_r($statisticsList);
		// 		exit;
		foreach ($statisticsList as $key=>$row){
			//客户_年份_月份_车牌
			$customer_year_month_car = explode("_",$key);
			$sql = "select id from tmp_fault_statistics where customer_id={$customer_year_month_car[0]} and year={$customer_year_month_car[1]} and car_no='{$customer_year_month_car[3]}'";
			$data = $connection->createCommand($sql)->queryOne();
			if(!$data){
				$connection->createCommand()->insert('tmp_fault_statistics', [
						'customer_id' => $customer_year_month_car[0],
						'year' => $customer_year_month_car[1],
						'month'.$customer_year_month_car[2] => $row,
						'car_no' => $customer_year_month_car[3]
						])->execute();
			}else {
				$sql = "update tmp_fault_statistics set month{$customer_year_month_car[2]}=month{$customer_year_month_car[2]}+{$row} where id={$data['id']}";
				$query = $connection->createCommand($sql)->execute();
			}
		}
	}
	/**
	 * 违章情况
	 */
	public function actionWz(){
		$connection = yii::$app->db;
		$sql = 'select id,UNIX_TIMESTAMP(date) wz_time,plate_number,cCustomer_id from car_wz';
		$data = $connection->createCommand($sql)->queryAll();
		// 		print_r($data);
		// 		exit;
	
		$statisticsList = [];
		foreach ($data as $row){
			$year = date("Y",$row['wz_time']);
			$month = date("n",$row['wz_time']);
			$customer_id = $row['cCustomer_id'];
			$plate_number = $row['plate_number'];
			if(@!$statisticsList[$customer_id.'_'.$year.'_'.$month.'_'.$plate_number]){
				$statisticsList[$customer_id.'_'.$year.'_'.$month.'_'.$plate_number] = 1;
			}else {
				$statisticsList[$customer_id.'_'.$year.'_'.$month.'_'.$plate_number] += 1;
			}
		}
		// 		print_r($statisticsList);
		// 		exit;
		foreach ($statisticsList as $key=>$row){
			//客户_年份_月份_车牌
			$customer_year_month_car = explode("_",$key);
			$sql = "select id from tmp_wz_statistics where customer_id={$customer_year_month_car[0]} and year={$customer_year_month_car[1]} and car_no='{$customer_year_month_car[3]}'";
			$data = $connection->createCommand($sql)->queryOne();
			if(!$data){
				$connection->createCommand()->insert('tmp_wz_statistics', [
						'customer_id' => $customer_year_month_car[0],
						'year' => $customer_year_month_car[1],
						'month'.$customer_year_month_car[2] => $row,
						'car_no' => $customer_year_month_car[3]
						])->execute();
			}else {
				$sql = "update tmp_wz_statistics set month{$customer_year_month_car[2]}=month{$customer_year_month_car[2]}+{$row} where id={$data['id']}";
				$query = $connection->createCommand($sql)->execute();
			}
		}
	}
	/**
	 * 出险情况
	 */
	public function actionInsuranceClaim(){
	$connection = yii::$app->db;
		$sql = 'select a.id,UNIX_TIMESTAMP(a.danger_date) danger_date,b.* from cs_car_insurance_claim a left join
	(select a.cCustomer_id,a.let_time,a.back_time,a.car_id,b.plate_number from cs_car_let_record a left join cs_car b on a.car_id=b.id and a.is_del=0) as b
		on a.car_id=b.car_id and ((UNIX_TIMESTAMP(a.danger_date)>=b.let_time and UNIX_TIMESTAMP(a.danger_date)<=b.back_time) or (UNIX_TIMESTAMP(a.danger_date)>=b.let_time and b.back_time=0))
	where b.cCustomer_id is not null and a.is_del=0';
		$data = $connection->createCommand($sql)->queryAll();
		// 		print_r($data);
		// 		exit;
		
		$statisticsList = [];
		foreach ($data as $row){
			$year = date("Y",$row['danger_date']);
			$month = date("n",$row['danger_date']);
			$customer_id = $row['cCustomer_id'];
			$plate_number = $row['plate_number'];
			if(@!$statisticsList[$customer_id.'_'.$year.'_'.$month.'_'.$plate_number]){
				$statisticsList[$customer_id.'_'.$year.'_'.$month.'_'.$plate_number] = 1;
			}else {
				$statisticsList[$customer_id.'_'.$year.'_'.$month.'_'.$plate_number] += 1;
			}
		}
		// 		print_r($statisticsList);
		// 		exit;
		foreach ($statisticsList as $key=>$row){
			//客户_年份_月份_车牌
			$customer_year_month_car = explode("_",$key);
			$sql = "select id from tmp_insurance_claim_statistics where customer_id={$customer_year_month_car[0]} and year={$customer_year_month_car[1]} and car_no='{$customer_year_month_car[3]}'";
			$data = $connection->createCommand($sql)->queryOne();
			if(!$data){
				$connection->createCommand()->insert('tmp_insurance_claim_statistics', [
						'customer_id' => $customer_year_month_car[0],
						'year' => $customer_year_month_car[1],
						'month'.$customer_year_month_car[2] => $row,
						'car_no' => $customer_year_month_car[3]
						])->execute();
			}else {
				$sql = "update tmp_insurance_claim_statistics set month{$customer_year_month_car[2]}=month{$customer_year_month_car[2]}+{$row} where id={$data['id']}";
				$query = $connection->createCommand($sql)->execute();
			}
		}
	}
}