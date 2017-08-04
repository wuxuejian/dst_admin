<?php
/**
 * 提车申请接口
 */
namespace backend\modules\interfaces\controllers;
use yii;
use yii\web\Controller;
class ExtractReportController extends Controller{	
	
	public function init(){
		//验证，md5(szclou)
    	if(!isset($_REQUEST['token']) || $_REQUEST['token'] != 'bb15508fc229425aac882e11fcf0aa1b'){
    		die(json_encode(['error'=>1,'msg'=>'验证失败！']));
    	}
		return true;
	}

	/**
	 * 根据提车ID获取流程归档完后的车辆
	 */
	public function actionByIdList(){
		$data = [];
		$id = intval(yii::$app->request->get('id'));
		if(empty($id)){
			die(json_encode(['error'=>1,'msg'=>'缺少参数！']));
		}
		$db = \Yii::$app->db;
		$sql ="SELECT oa_extract_report.id,oa_prepare_car.car_no  FROM oa_extract_report  
				LEFT JOIN  oa_prepare_car ON oa_prepare_car.tc_receipts = oa_extract_report.id
				WHERE oa_extract_report.id={$id} AND oa_extract_report.is_archive=1  AND oa_prepare_car.is_delivery=1";
		$data = $db->createCommand($sql)->queryAll();
		if(empty($data)){
			die(json_encode(['error'=>1,'msg'=>'提车流程不存在或未完结！']));
		}
		$ret_data = array_column($data,'car_no');
		die(json_encode(['error'=>0,'msg'=>'操作成功！','data'=>$ret_data]));
	}
}