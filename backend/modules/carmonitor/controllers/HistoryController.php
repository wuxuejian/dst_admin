<?php
/**
 * 车辆监控数据导出控制器
 * time    2016/02/25
 * @author wangmin
 */
namespace backend\modules\carmonitor\controllers;
use backend\controllers\BaseController;
use backend\models\Car;

use common\classes\CarRealtimeDataAnalysis;
use common\models\Excel;
use common\classes\File;

use yii;
use yii\data\Pagination;
class HistoryController extends BaseController
{
    public function actionIndex(){
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }

    public function actionGetListData(){
    	date_default_timezone_set('PRC');
        $returnArr = [
            'rows'=>[],
            'total'=>0
        ];
        $plate_number = yii::$app->request->get('plate_number');
        $carVin = yii::$app->request->get('car_vin');
        if(!$plate_number && !$carVin){
            return json_encode($returnArr);
        }
        if($plate_number){	//根据车牌号获取vin
        	$scCarInfo = Car::find()->select([
        			'car_vin'=>'vehicle_dentification_number'
        			])->where(['plate_number'=>$plate_number])
        			->asArray()->one();
        	if($scCarInfo){
        		$carVin = $scCarInfo['car_vin'];
        	}
        	unset($scCarInfo);
        }
        $startDate = yii::$app->request->get('start_date');
        if(!$startDate){
            $startDate = date('Y-m-d');
        }
        $startTimeStamp = strtotime($startDate);
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
            return json_encode($returnArr);
        }
        
        $query = (new \yii\db\Query())
            ->from($nowTable)
            ->select(['update_datetime','data_hex'])
            ->andWhere(['>=','collection_datetime',$startTimeStamp])
            ->andWhere(['car_vin'=>$carVin]);
        //查询条件处理
        $endDate = yii::$app->request->get('end_date');
        if($endDate){
            $query->andWhere(['<=','collection_datetime',strtotime($endDate)]);
        }
        //查询条件处理结束
//         echo $query->createCommand()->getRawSql();exit;
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
        $total = $query->count('`id`',$connection);
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
            ->orderBy($orderBy)->all($connection);
        if($data){
            $carInfo = Car::find()
                ->select([
                    'plate_number',
                    'car_vin'=>'vehicle_dentification_number'
                ])->where([
                    'vehicle_dentification_number'=>$carVin,
                    'is_del'=>0,
                ])->limit(1)->asArray()->one();
            foreach($data as &$moniDataItem){
                if($carInfo){
                    $moniDataItem['plate_number'] = $carInfo['plate_number'];
                    $moniDataItem['car_vin'] = $carInfo['car_vin'];
                }
                //解析数据
                $dataAnalysisObj = new CarRealtimeDataAnalysis($moniDataItem['data_hex']);
                $moniDataItem = array_merge($moniDataItem,$dataAnalysisObj->getRealtimeData());
                if(isset($moniDataItem['gear'])){
                    $moniDataItem['gear'] = join(',',json_decode($moniDataItem['gear'],true));
                }
            }
        }
        $returnArr['total'] = $total;
        $returnArr['rows'] = $data;
        return json_encode($returnArr);
    }

    /**
     * 导出监控数据
     * carmonitor/history/export
     */
    public function actionExport(){
        set_time_limit(300);
        $carVin = yii::$app->request->get('car_vin');
        $plate_number = yii::$app->request->get('plate_number');
        
        if(!$plate_number && !$carVin){
        	return '<script>alert("参数错误！");window.close();</script>';
        }
        if($plate_number){	//根据车牌号获取vin
        	$scCarInfo = Car::find()->select([
        			'car_vin'=>'vehicle_dentification_number'
        			])->where(['plate_number'=>$plate_number])
        			->asArray()->one();
        	if($scCarInfo){
        		$carVin = $scCarInfo['car_vin'];
        	}
        	unset($scCarInfo);
        }
        
        $startDate = yii::$app->request->get('start_date');
        if(!$startDate){
            $startDate = date('Y-m-d');
        }
        $startTimeStamp = strtotime($startDate);
        if(date('Ym',$startTimeStamp) == date('Ym') || date('Ym',$startTimeStamp) == date("Ym",strtotime("-1 month"))){
            $connection = yii::$app->db1;//本地数据库
        }else{
            $connection = yii::$app->db2;//备份数据库
        }
        $tables = $connection->createCommand('show tables')->queryAll();
        if(!$tables){
            return '<script>alert("无数据导出！");window.close();</script>';
        }
        $tables = array_column($tables,'Tables_in_car_monidata');
        if($startTimeStamp < strtotime('2016-04-15')){
            //2016年4月1日之前的数据
            $nowTable = 'cs_tcp_car_history_data_'.date('Ym',$startTimeStamp);
        }else{
            //2016年4月1日之后的数据
            $nowTable = 'cs_tcp_car_history_data_'.date('Ym',$startTimeStamp).'_'.substr($carVin,-1);
        }
        if(array_search($nowTable,$tables) === false){
            //不存在该数据表
            return '<script>alert("无数据导出！");window.close();</script>';
        }
        //excel
        $exportAttributes = [
            'car_vin'=>'车架号',
            'plate_number'=>'车牌号',
            'collection_datetime'=>'采集时间',
            'update_datetime'=>'更新时间',
            'ignition_datetime'=>'点火时间',
            'flameout_datetime'=>'熄火时间',
            'total_driving_mileage'=>'里程',
            //'position_effective'=>'定位有效',
            //'latitude_type'=>'纬度类型',
            //'longitude_type'=>'经度类型',
            'longitude_value'=>'经度值',
            'latitude_value'=>'纬度值',
            'speed'=>'速度',
            'direction'=>'方向',
            'gear'=>'档位',
            'accelerator_pedal'=>'加速踏板行程值',
            'brake_pedal_distance'=>'制动踏板行程值',
            'moter_controller_temperature'=>'电机控制器温度',
            'moter_speed'=>'电机转速',
            'moter_temperature'=>'电机温度',
            'moter_voltage'=>'电机电压',
            'moter_current'=>'电机电流',
            'moter_generatrix_current'=>'电机母线电流',
            'air_condition_temperature'=>'空调预设温度',
            'brake_pedal_status'=>'制动踏板状态',
            'power_system_ready'=>'动力系统就绪',
            'emergency_electric_request'=>'紧急正电请求',
            'car_current_status'=>'车辆状态',
            'battery_package_voltage'=>'电池包电压数据',
            'battery_package_total_voltage'=>'电池总电压',
            'battery_package_temperature'=>'电池包温度数据',
            'battery_package_current'=>'电池包电流',
            'battery_package_soc'=>'SOC',
            'battery_package_power'=>'剩余能量',
            'battery_package_hv_serial_num'=>'电压最高包号',
            'battery_single_hv_serial_num'=>'电压最高电池号',
            'battery_single_hv_value'=>'电压最高值',
            'battery_package_lv_serial_num'=>'电压最低包号',
            'battery_single_lv_serial_num'=>'电压最低电池号',
            'battery_single_lv_value'=>'电压最低值',
            'battery_package_ht_serial_num'=>'高温电池包号',
            'battery_single_ht_serial_num'=>'高温电池号',
            'battery_single_ht_value'=>'温度最高值',
            'battery_package_lt_serial_num'=>'低温电池包号',
            'battery_single_lt_serial_num'=>'低温探针号',
            'battery_single_lt_value'=>'最低温度值',
            'battery_package_resistance_value'=>'绝缘电阻值',
            'battery_package_equilibria_active'=>'电池均衡活动',
            'battery_package_fuel_consumption'=>'液体燃料消耗',
            'data_hex'=>'上报数据',
        ];
        //写入文件
        $file = '../runtime/'.session_id().'_carmonitor_history.csv';
        if(file_exists($file)){
            unlink($file);
        }
        file_put_contents($file,iconv('utf-8','gbk',join(',',$exportAttributes)."\n"),FILE_APPEND);
        $query = (new \yii\db\Query())
            ->from($nowTable)
            ->select(['update_datetime','data_hex'])
            ->andWhere(['>=','collection_datetime',$startTimeStamp])
            ->andWhere(['car_vin'=>$carVin]);
        //查询条件处理
        $endDate = yii::$app->request->get('end_date');
        if($endDate){
            $query->andWhere(['<=','collection_datetime',strtotime($endDate)]);
        }
        //查询条件处理结束
        //查询车牌号
        $carInfo = Car::find()
            ->select([
                'plate_number',
                'car_vin'=>'vehicle_dentification_number'
            ])->where([
                'vehicle_dentification_number'=>$carVin,
                'is_del'=>0,
            ])->limit(1)->asArray()->one();
        $total = $query->count('`id`',$connection);
        if($total > 20000){
            return '<script>alert("数据过大无法导出！");window.close();</script>';
        }
        $pageSize = 500;
        $_GET['page'] = 1;
        do{
            $data = $query->limit($pageSize)->offset(($_GET['page'] - 1) * $pageSize)->orderBy('`id`')->all($connection);
            if($data){
                foreach($data as $moniDataItem){
                    if($carInfo){
                        $moniDataItem['plate_number'] = $carInfo['plate_number'];
                        $moniDataItem['car_vin'] = $carInfo['car_vin'];
                    }
                    //解析数据
                    $dataAnalysisObj = new CarRealtimeDataAnalysis($moniDataItem['data_hex']);
                    $moniDataItem = array_merge($moniDataItem,$dataAnalysisObj->getRealtimeData());
                    $moniDataItem['collection_datetime'] = date('Y-m-d H:i:s',$moniDataItem['collection_datetime']);
                    $moniDataItem['update_datetime'] = date('Y-m-d H:i:s',$moniDataItem['update_datetime']);
                    $lineData = [];
                    foreach($exportAttributes as $k=>$v){
                        if(isset($moniDataItem[$k])){
                            $lineData[] = str_replace(',','|',$moniDataItem[$k]);
                        }else{
                            $lineData[] = '';
                        }
                    }
                    file_put_contents($file,iconv('utf-8','gbk',join(',',$lineData)."\n"),FILE_APPEND);
                }
            }
            $_GET['page'] ++;
        }while($data);
        File::fileDownload($file,iconv('utf-8','gbk','车辆监控数据').'.csv');
        unlink($file);
    }
}