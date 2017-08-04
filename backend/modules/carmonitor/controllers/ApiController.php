<?php
/**
 * Api车辆实时数据
 * @author Administrator
 *
 */
namespace backend\modules\carmonitor\controllers;
use yii\web\Controller;
use yii;

use backend\models\TcpCarRealtimeData;
use backend\models\Car;
use backend\models\CarLetRecord;
use backend\models\CarTrialProtocolDetails;
use backend\models\CustomerCompany;
use backend\models\ConfigCategory;
class ApiController extends Controller
{
	public function init(){
		parent::init();
		header( 'Access-Control-Allow-Origin:*' );
		//去掉csrf验证
		$this->enableCsrfValidation = false;
	}
	
	
	public function actionIndex()
	{			
		$returnArr = [
                'errcode'=>1,
                'msg'=>'',
                'data'=>[],
            ];
        $query = (new \yii\db\Query())
         ->select('
           car_vin,collection_datetime,collection_datetime,
         	total_driving_mileage AS  tdm,
           longitude_value,latitude_value,speed,
           battery_package_soc AS soc ,direction,car_current_status,
           cs_car.plate_number
           ')
         ->from('car_monidata.cs_tcp_car_realtime_data as a');
            
        $query->join('LEFT JOIN','cs_car','cs_car.vehicle_dentification_number = a.car_vin')->where('is_del=0');
        //车辆类型
        $car_type = yii::$app->request->get('car_type');
		if($car_type)
		{
			$query->andWhere('car_type=:car_type',[':car_type'=>$car_type]);
		}
		//当前正在试用或租用的客户条件筛选
		$customer_id = yii::$app->request->get('customer_id');
		if($customer_id)
		{
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
                $query->andWhere(['car_vin'=>array_column($csCarInfo,'car_vin')]);
            }
            unset($csLetCarIds);
            unset($csCarInfo);
            unset($csLetCarInfo);
            unset($csTestCarInfo);
		}
		$data = [];
		//车辆状态(复选)
		$car_status= yii::$app->request->get('car_status');
		if(!empty($car_status))
		{
			$car_status_arr = explode(',', $car_status);
			foreach ($car_status_arr as $car_status)
			{
				if($car_status)
				{
					$_query = clone $query;
					switch ($car_status){
						case 'stop':
							$_query->andWhere('car_current_status=0');
							$_query->andWhere(['>=','collection_datetime',time() - 600]);
							break;
						case 'driving':
							$_query->andWhere('car_current_status=1');
							$_query->andWhere(['>=','collection_datetime',time() - 600]);
							break;
						case 'charging':
							$_query->andWhere('car_current_status=2');
							$_query->andWhere(['>=','collection_datetime',time() - 600]);
							break;
						case 'offline':
							//600秒没有上报数据 车辆视为离线状态
							$_query->andWhere(['<','collection_datetime',time() - 600]);
							break;
					}
					$result = $_query->all();
					$data = array_merge($data,$result);
				}
			}
		}
		
/* 		echo '<pre>';
		var_dump($data);exit();	 */	
		
        //$data = $query->all();
        if(!$data){
             $returnArr['msg'] = '没有查询到车辆数据！';
             return json_encode($returnArr);
        }
            
        foreach ($data as $key=>$val){
        	
        	if($val['collection_datetime'] < time() - 600)
        	{
        		$data[$key]['car_current_status'] = 'offline';
        	}else{
        		switch($val['car_current_status']){
        			case 0:
        				$data[$key]['car_current_status'] = 'stop';
        				break;
        			case 1:
        				$data[$key]['car_current_status'] = 'driving';
        				break;
        			case 2:
        				$data[$key]['car_current_status'] = 'charging';
        				break;
        		}
        	}
        	
	        if($val['collection_datetime']){
	            $data[$key]['collection_datetime'] = date('Y-m-d H:i:s',$val['collection_datetime']);
	        }
	        
         }
         $returnArr['errcode'] = 0;
         $returnArr['data'] = $data;
         return json_encode($returnArr);
	}
	
	/**
	 * 获取当前正在租车或正在试用的客户
	 */
	public function actionGetLetingCustomer()
	{
		$returnArr = [
		'errcode'=>1,
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
		->select(['id','company_name'])
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
		//每页显示条数
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
		//当前页数
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$offset = ($page-1)*$pageSize;
		$returnArr['total'] = $total;
		$returnArr['rows'] = $query->offset($offset)
		->limit($pageSize)
		->orderBy($orderBy)->asArray()->all();
		if($total)
		{
			$returnArr['errcode'] = 0;
		}
		
		return json_encode($returnArr);
	}
	
	/**
	 * 获取车辆类型
	 */
	public function actionGetCarType()
	{
		$data = ['errcode'=>1,'data'=>[]];
		$config = (new ConfigCategory)->getCategoryConfig(['car_type']);
		if($config)
		{
			foreach ($config['car_type'] as $val)
			{
			
				$data['data'][] = array('value'=>$val['value'],'text'=>$val['text']);
			}
			$data['errcode'] = 0;
		}
		return json_encode($data);
	}
}