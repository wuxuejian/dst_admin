<?php
/**
 * @Desc:   车辆信息控制器 
 * @author: pengyl
 * @date:   2016-12-21 14:28
 */
namespace backend\modules\interfaces\controllers;
use backend\models\Car;
use backend\models\TcpCarRealtimeData;
use backend\models\ConfigCategory;
use yii;
use yii\web\Controller;
use yii\data\Pagination;
class CarInfoController extends Controller{
	public function init(){
		date_default_timezone_set('PRC');
		//验证，md5(szclou),md5(dst2017)
		$tokens = array('bb15508fc229425aac882e11fcf0aa1b','56c5e8a53dbe397cc079ee3c04c262f0');
    	if(!isset($_REQUEST['token']) || !in_array($_REQUEST['token'], $tokens)){
    		die(json_encode(['error'=>1,'msg'=>'验证失败！']));
    	}
		return true;
	}
	
	/**
	 * 获取一条车辆时实数据
	 */
	public function actionGetOneRealtimeData(){
        $car_vin = yii::$app->request->get('car_vin');
        if(!$car_vin){
        	die(json_encode(['error'=>2,'msg'=>'缺少vin！']));
        }
        
        $query = TcpCarRealtimeData::find()
        ->select([
        		'car_vin',
        		'longitude_value',		//经度值
        		'latitude_value',		//纬度值
        		'total_driving_mileage',//累计行驶里程
        		'speed',
        		'battery_package_soc',	//SOC
        		'car_current_status',	//车辆状态,0停止；1行驶；2充电
        		'collection_datetime'	//数据采集时间
        		])->andWhere(['car_vin'=>$car_vin]);
        $data = $query->asArray()->one();
//         echo $query->createCommand()->getRawSql();
        if(!$data){
        	die(json_encode(['error'=>4,'msg'=>'无数据！']));
        }
        
        $returnArr['error'] = 0;
        $returnArr['data'] = $data;
        return json_encode($returnArr);
    }
    
    /**
     * 获取车辆历史数据
     */
    public function actionGetHistoryListData(){
    	$carVin = yii::$app->request->get('car_vin');
    	$startDate = yii::$app->request->get('start_date');
    	$endDate = yii::$app->request->get('end_date');
    	if(!$carVin || !$startDate || !$endDate ){
        	die(json_encode(['error'=>2,'msg'=>'缺少参数！']));
        }
    	$startTimeStamp = strtotime($startDate);
    	$endTimeStamp = strtotime($endDate);
    	
    	if(($endTimeStamp - $startTimeStamp) > 86400){
    		//最大数据限制
    		die(json_encode(['error'=>3,'msg'=>'单次最大数据24小时！']));
    	}
    	
    	if(date('Ym',$startTimeStamp) == date('Ym') || date('Ym',$startTimeStamp) == date("Ym",strtotime("-1 month"))){
    		$connection = yii::$app->db1;//本地数据库
    	}else{
    		$connection = yii::$app->db2;//备份数据库
    	}
    	$tables = $connection->createCommand('show tables')->queryAll();
    	if(!$tables){
    		return json_encode($returnArr);
    	}
    	$tables = array_column($tables,'Tables_in_car_monidata');
    	$nowTable = 'cs_tcp_car_history_data_'.date('Ym',$startTimeStamp).'_'.substr($carVin,-1);
    	if(array_search($nowTable,$tables) === false){
    		//不存在该数据表
    		die(json_encode(['error'=>4,'msg'=>'无数据！']));
    	}
    	
    	//查询条件处理
    	$query = (new \yii\db\Query())
    	->from($nowTable)
    	->select(['collection_datetime','longitude_value','latitude_value','speed','car_current_status','battery_package_soc'])
    	->andWhere(['>=','collection_datetime',$startTimeStamp])
    	->andWhere(['<=','collection_datetime',$endTimeStamp])
    	->andWhere(['>','longitude_value',0])
    	->andWhere(['car_vin'=>$carVin]);
    	//查询条件处理结束
//     	        echo $query->createCommand()->getRawSql();exit;
    	$orderBy = 'collection_datetime asc';
    	$data = $query->orderBy($orderBy)->all($connection);
    	
    	$returnArr['error'] = 0;
    	$returnArr['data'] = $data;
    	return json_encode($returnArr);
    }
	
	/**
	 * 获取配置列表
	 */
	public function actionGetConfigList(){
		$configItems = ['car_color','car_type','use_nature'];
		$config = (new ConfigCategory)->getCategoryConfig($configItems,'value'
		);
		$returnArr['error'] = 0;
        $returnArr['data'] = $config;
        return json_encode($returnArr);
	}
	
    /**
     * 获取车辆列表
     */
    public function actionGetList(){
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = Car::find()
            ->select([
                '{{%car}}.`id`',			//车辆ID
                '{{%car}}.`plate_number`',	//车牌号
                'car_brand'=>'{{%car_brand}}.`name`',	//品牌
            	'{{%car}}.`car_model`',		//型号
                '{{%car}}.`car_color`',		//颜色
            	'{{%car}}.`use_nature`',	//使用性质
                '{{%car}}.`car_type`',		//车辆类型
                '{{%car}}.`cab_passenger`',	//乘坐人数（即“驾驶室载客”）
				'{{%car}}.`total_mass`',	//总质量/单位：kg
 				'{{%car}}.`check_mass`',	//核定载质量/单位：kg
				'{{%car}}.`outside_long`',	//外廓尺寸长/单位：mm
				'{{%car}}.`outside_width`',	//外廓尺寸宽/单位：mm
				'{{%car}}.`outside_height`'	//外廓尺寸高/单位：mm
            ])
            ->joinWith('carBrand',false)
            ->andWhere(['{{%car}}.`is_del`'=>0]);
        
        $brand_id = 5;	//目前只返回北汽新能源车
        //查品牌，查父品牌时也会查出子品牌
        if($brand_id){
        	$query->andFilterWhere(['{{%car_brand}}.`id`'=>$brand_id]);
        }
//         $query->andFilterWhere(['{{%car}}.`car_type`'=>'LIGHT_VAN_TRUCK']);
//         $query->andFilterWhere(['<=','{{%car}}.`reg_date`',strtotime('2015-12-31 00:00:00')]);
        $query->andFilterWhere(['<','{{%car}}.`id`',2771]);
        ////查询条件结束
//         echo $query->createCommand()->getRawSql();exit;
        $orderBy = '{{%car}}.`id`';
        $total = $query->groupBy('{{%car}}.`id`')->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();	
        
        $returnArr['error'] = 0;
        $returnArr['data'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }
    
	/**
	 * 获取车辆时实数据
	 */
    public function actionGetRealtimeData(){
        $car_ids = yii::$app->request->get('car_ids');
        if(!$car_ids){
        	die(json_encode(['error'=>2,'msg'=>'缺少car_ids！']));
        }
        $car_ids = explode(',', $car_ids);
        //查询车辆基本信息
        $query = Car::find()->select(['vehicle_dentification_number'])->where(['id'=>$car_ids]);
        $query->andFilterWhere(['<','id',2771]);
//         $query->andFilterWhere(['=','car_type', 'LIGHT_VAN_TRUCK']);
//         $query->andFilterWhere(['<=','reg_date', strtotime('2015-12-31 00:00:00')]);
        $cars = $query->asArray()->all();
        if(empty($cars)){
        	die(json_encode(['error'=>3,'msg'=>'该车辆不存在！']));
        }
        $car_vins = array();
        foreach ($cars as $row){
        	array_push($car_vins, $row['vehicle_dentification_number']);
        }
        
        $query = TcpCarRealtimeData::find()
        ->select([
        		'car_vin as car_id',
        		'longitude_value',		//经度值
        		'latitude_value',		//纬度值
        		'total_driving_mileage',//累计行驶里程
        		'battery_package_soc',	//SOC
        		'car_current_status',	//车辆状态,0停止；1行驶；2充电
        		'collection_datetime'	//数据采集时间
        		])->andWhere(['car_vin'=>$car_vins]);
        $data = $query->asArray()->all();
        
//         echo $query->createCommand()->getRawSql();
        if(!$data){
        	die(json_encode(['error'=>4,'msg'=>'无数据！']));
        }
        
        foreach ($data as $index=>$row){
        	$query = Car::find()->select(['id'])->where(['vehicle_dentification_number'=>$row['car_id']]);
        	$car = $query->asArray()->one();
        	if($car){
        		$data[$index]['car_id'] = (int)$car['id'];
        	}else {
        		$data[$index]['car_id'] = 0;
        	}
        }
        $returnArr['error'] = 0;
        $returnArr['data'] = $data;
        return json_encode($returnArr);
    }
}