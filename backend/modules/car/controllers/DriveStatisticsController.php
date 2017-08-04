<?php
/**
 * 车辆行驶统计
 * 2016/03/28
 * @author wangmin
 */
namespace backend\modules\car\controllers;
use yii;
use yii\data\Pagination;
use common\classes\CarRealtimeDataAnalysis;
use backend\controllers\BaseController;
use backend\models\Car;
use backend\models\ConfigCategory;
use common\models\Excel;
class DriveStatisticsController extends BaseController
{
    public function actionIndex(){
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }

    /****************************************************
     * “车辆行驶统计”-获取列表
     ***************************************************/
    public function actionGetListData(){
        $returnArr = [
            'total'=>0,
            'rows'=>[],
        ];
        $query = Car::find()
            ->select([
                'id','plate_number',
                'car_vin'=>'vehicle_dentification_number'
            ])->where([
                'is_del'=>0,
            ]);
        //查询条件开始
        $query->andFilterWhere(['like','`plate_number`',yii::$app->request->get('plate_number')]);
        $query->andFilterWhere(['like','`vehicle_dentification_number`',yii::$app->request->get('car_vin')]);
        //查询条件结束
        $total = $query->count();
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
            $orderBy = '`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        if(!$data){
            //没有记录
            return json_encode($returnArr);
        }
        $date = yii::$app->request->get('date');
        if(!$date){
            $date = date('Y-m-d');
        }
        $date = strtotime($date);
        $tableName = 'cs_tcp_car_history_data_'.date('Ym',$date);
        if(date('Ym',$date) == date('Ym')){
            $connection = yii::$app->db1;
        }else{
            $connection = yii::$app->db2;
        }
        //查询所选日期车辆的初始数据
        foreach($data as $key => $val){
            if($key == 0){
                $queryStart = (new \yii\db\Query())
                    ->select(['car_vin','battery_package_soc','data_hex'])
                    ->from($tableName)
                    ->where('car_vin = :car_vin and `collection_datetime` >= :collection_datetime_s and `collection_datetime` <= :collection_datetime_e',[
                        ':car_vin'=>$val['car_vin'],
                        ':collection_datetime_s'=>$date,
                        ':collection_datetime_e'=>$date + 86400,
                    ])->orderBy('collection_datetime asc')
                    ->limit('1');
                $queryEnd = (new \yii\db\Query())
                    ->select(['car_vin','battery_package_soc','data_hex'])
                    ->from($tableName)
                    ->where('car_vin = :car_vin and `collection_datetime` >= :collection_datetime_s and `collection_datetime` <= :collection_datetime_e',[
                        ':car_vin'=>$val['car_vin'],
                        ':collection_datetime_s'=>$date,
                        ':collection_datetime_e'=>$date + 86400,
                    ])->orderBy('collection_datetime desc')
                    ->limit('1');
            }else{
                $tmp = (new \yii\db\Query())
                    ->select(['car_vin','battery_package_soc','data_hex'])
                    ->from($tableName)
                    ->where('car_vin = :car_vin and `collection_datetime` >= :collection_datetime_s and `collection_datetime` <= :collection_datetime_e',[
                        ':car_vin'=>$val['car_vin'],
                        ':collection_datetime_s'=>$date,
                        ':collection_datetime_e'=>$date + 86400,
                    ])->orderBy('collection_datetime asc')
                    ->limit('1');
                $queryStart->union($tmp);
                $tmp = (new \yii\db\Query())
                    ->select(['car_vin','battery_package_soc','data_hex'])
                    ->from($tableName)
                    ->where('car_vin = :car_vin and `collection_datetime` >= :collection_datetime_s and `collection_datetime` <= :collection_datetime_e',[
                        ':car_vin'=>$val['car_vin'],
                        ':collection_datetime_s'=>$date,
                        ':collection_datetime_e'=>$date + 86400,
                    ])->orderBy('collection_datetime desc')
                    ->limit('1');
                $queryEnd->union($tmp);
            }
        }
        unset($tmp);
        //echo $queryStart->createCommand()->sql;die;
        $startData = $queryStart->indexBy('car_vin')->all($connection);
        $endData = $queryEnd->indexBy('car_vin')->all($connection);
        //var_dump($startData);
        //var_dump($endData);
        unset($queryStart);
        unset($queryEnd);
        foreach($data as &$carItem){
            $carItem['start_datetime'] = '无数据';
            $carItem['end_datetime'] = '无数据';
            $carItem['start_soc'] = '无数据';
            $carItem['end_soc'] = '无数据';
            $carItem['start_mileage'] = '无数据';
            $carItem['end_mileage'] = '无数据';
            if(isset($startData[$carItem['car_vin']])){
                $unpackData = new CarRealtimeDataAnalysis($startData[$carItem['car_vin']]['data_hex']);
                $unpackData = $unpackData->getRealtimeData();
                //var_dump($unpackData);
                if(isset($unpackData['collection_datetime'])){
                    $carItem['start_datetime'] = date('Y-m-d H:i:s',$unpackData['collection_datetime']);
                }
                if(isset($unpackData['battery_package_soc'])){
                    $carItem['start_soc'] = $unpackData['battery_package_soc'];
                }
                if(isset($unpackData['total_driving_mileage'])){
                    $carItem['start_mileage'] = $unpackData['total_driving_mileage'];
                } 
            }
            if(isset($endData[$carItem['car_vin']])){
                $unpackData = new CarRealtimeDataAnalysis($endData[$carItem['car_vin']]['data_hex']);
                $unpackData = $unpackData->getRealtimeData();
                //var_dump($unpackData);
                if(isset($unpackData['collection_datetime'])){
                    $carItem['end_datetime'] = date('Y-m-d H:i:s',$unpackData['collection_datetime']);
                }
                if(isset($unpackData['battery_package_soc'])){
                    $carItem['end_soc'] = $unpackData['battery_package_soc'];
                }
                if(isset($unpackData['total_driving_mileage'])){
                    $carItem['end_mileage'] = $unpackData['total_driving_mileage'];
                } 
            }
        }
        unset($startData);
        unset($endData);
        $returnArr['total'] = $total;
        $returnArr['rows'] = $data;
        return json_encode($returnArr);
    }

    /**
     * “车辆行驶统计”-明细窗口
     */
    public function actionDetail(){
        $date = yii::$app->request->get('date');
        $carVin = yii::$app->request->get('car_vin');
        if(!$carVin){
            return '参数缺失！';
        }
        if(!$date){
            $date = date('Y-m-d');
        }
        $buttons = $this->getCurrentActionBtn();
        return $this->render('detail',[
            'buttons'=>$buttons,
            'carVin'=>$carVin,
            'date'=>$date,
        ]);
    }

    /**
     * 明细窗口的“行驶记录明细”tab页签
     */
    public function actionDriveRecord(){
        $carVin = yii::$app->request->get('car_vin');
        $date = yii::$app->request->get('date');
        if(!$carVin || !$date){
            return '参数缺失！';
        }
        $startDate = $endDate = $date;
        $res = $this->getDriveHistoryDataIds($carVin, $startDate, $endDate);
        $buttons = $this->getCurrentActionBtn();
        return $this->render('drive-record',[
            'buttons'=>$buttons,
            'statusChangeIds'=>$res['statusChangeIds'],
            'startDate'=>$startDate,
            'endDate'=>$endDate
        ]);
    }

    /**
     * 明细窗口的“充电记录明细”tab页签
     */
    public function actionChargeRecord(){
        $carVin = yii::$app->request->get('car_vin');
        $date = yii::$app->request->get('date');
        if(!$carVin || !$date){
            return '参数缺失！';
        }
        $startDate = $endDate = $date;
        $res = $this->getChargeHistoryDataIds($carVin, $startDate, $endDate);
        $buttons = $this->getCurrentActionBtn();
        return $this->render('charge-record',[
            'buttons'=>$buttons,
            'validIdStr'=>$res['validIdStr'],
            'startDate'=>$startDate,
            'endDate'=>$endDate
        ]);
    }



    /*************************************************************
     * “车辆行驶记录明细”菜单【查询页面入口】
    *************************************************************/
    public function actionDriveRecordIndex(){
        $buttons = $this->getCurrentActionBtn();
        return $this->render('drive-record-index',[
            'buttons'=>$buttons,
        ]);
    }


    /**
     * “车辆行驶记录”-顶部表单检索
     */
    public function actionDriveRecordSearch(){
        $search = yii::$app->request->get();
        $startDate = $search['start_date'];
        $endDate = $search['end_date'];
        $carVin = $search['car_vin'];
        $plateNumber = $search['plate_number'];
        if(!$carVin && !$plateNumber){
            $returnArr['status'] = false;
            $returnArr['info'] = '车牌号和车架号不能同时为空！';
            return json_encode($returnArr);
        }
        if(!$startDate || !$endDate){
            switch ($search['search_type']) {
                case 'this_day':
                    $startDate = date('Y-m-d 00:00:00');
                    $endDate = date('Y-m-d 23:59:59');
                    break;
                case 'this_week':
                    $startDate = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
                    $endDate = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));
                    break;
                case 'this_month':
                    $startDate = date('Y-m-01 00:00:00');
                    $endDate = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y")));
                    break;
                default:
                    break;
            }
        }
        if(!$startDate || !$endDate) {
            $returnArr['status'] = false;
            $returnArr['info'] = '请指定一个查询时间段！';
            return json_encode($returnArr);
        }
        $carInfo = Car::find()
            ->select([
                'car_vin'=>'vehicle_dentification_number'
            ])
            ->andWhere(['is_del'=>0])
            ->andFilterWhere(['plate_number'=>$plateNumber])
            ->andFilterWhere(['vehicle_dentification_number'=>$carVin])
            ->asArray()->one();
        if(!$carInfo){
            $returnArr['status'] = false;
            $returnArr['info'] = '所填车牌号或车架号不存在！';
            return json_encode($returnArr);
        }
        //查车辆行驶记录
        $res = $this->getDriveHistoryDataIds($carInfo['car_vin'], $startDate, $endDate);
        if($res['status']){
            $returnArr['status'] = true;
            $returnArr['car_vin'] = $carInfo['car_vin'];
            $returnArr['statusChangeIds'] = $res['statusChangeIds'];
            $returnArr['startDate'] = $startDate;
            $returnArr['endDate'] = $endDate;
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = $res['info'];
        }
        return json_encode($returnArr);
    }

    /*
     * “车辆行驶记录”-查询符合条件的历史记录id
     */
    protected function getDriveHistoryDataIds($carVin,$startDate,$endDate){
        if(!$carVin){
            return ['status'=>false,'info'=>'缺少车架号！'];
        }
        if(!$startDate || !$endDate){
            return ['status'=>false,'info'=>'缺少查询时间段！'];
        }
        if(substr($startDate,0,7) != substr($endDate,0,7)){
            return ['status'=>false,'info'=>'目前不支持跨月份查询！'];
        }
        $ym = date('Ym',strtotime($startDate));
        //$tableName = 'cs_tcp_car_history_data_'.$ym;
        if(strtotime($startDate) <= strtotime('2016-04-15 10:00:00')){
            //2016年4月1日之前的数据
            $tableName = 'cs_tcp_car_history_data_'.$ym;
        }else{
            //2016年4月1日之后的数据
            $tableName = 'cs_tcp_car_history_data_'.$ym.'_'.substr($carVin,-1);
        }
        if($ym == date('Ym') || $ym == date('Ym',strtotime("-1 month"))){
            $connection = yii::$app->db1;
        }else{
            $connection = yii::$app->db2;
        }
        $res = $connection->createCommand("SHOW TABLES LIKE '{$tableName}'")->queryAll();
        if(!$res){
            return ['status'=>false,'info'=>"找不到数据表：{$tableName}"];
        }
        $data = (new \yii\db\Query())
            ->select(['id','car_current_status'])
            ->from($tableName)
            ->where(
                'car_vin = :car_vin and `collection_datetime` >= :collection_datetime_s and `collection_datetime` <= :collection_datetime_e and ( `car_current_status` = 0 or `car_current_status` = 1 )',
                [
                    ':car_vin'=>$carVin,
                    ':collection_datetime_s'=>strtotime($startDate),
                    ':collection_datetime_e'=>strtotime($endDate) + 86400,
                ]
            )
            ->orderBy('collection_datetime asc')
            ->all($connection);
        if(!$data){
            return ['status'=>false,'info'=>'未查询到车辆行驶历史数据！'];
        }
        $statusChangeIds = [];
        foreach($data as $key=>$val){
            if($key == 0){
                $statusChangeIds[$val['id']] = $val['car_current_status'];
                continue;
            }
            if(end($statusChangeIds) != $val['car_current_status']){
                $statusChangeIds[$val['id']] = $val['car_current_status'];
            }
        }
        $statusChangeIds = join(',',array_keys($statusChangeIds));
        return ['status'=>true,'statusChangeIds'=>$statusChangeIds];
    }


    /**
     * “车辆行驶记录”-获取列表
     */
    public function actionDriveRecordGetList(){
        $carVin = yii::$app->request->post('car_vin','');
        $statusChangeIds = yii::$app->request->post('statusChangeIds','');
        $startDate = yii::$app->request->post('startDate','');
        $endDate = yii::$app->request->post('endDate','');
        if(!$statusChangeIds || !$startDate || !$endDate){
            return json_encode(['total'=>0,'rows'=>[]]);
        }
        $statusChangeIds = explode(',',$statusChangeIds);
        $ym = date('Ym',strtotime($startDate));
        //$tableName = 'cs_tcp_car_history_data_'.$ym;
        if(strtotime($startDate) <= strtotime('2016-04-15 10:00:00')){
            //2016年4月1日之前的数据
            $tableName = 'cs_tcp_car_history_data_'.$ym;
        }else{
            //2016年4月1日之后的数据
            $tableName = 'cs_tcp_car_history_data_'.$ym.'_'.substr($carVin,-1);
        }
        if($ym == date('Ym') || $ym == date('Ym',strtotime("-1 month"))){
            $connection = yii::$app->db1;
        }else{
            $connection = yii::$app->db2;
        }
        $query = (new \yii\db\Query())
            ->select(['car_vin','data_hex'])
            ->from($tableName)
            ->where(['id'=>$statusChangeIds]);
        //查询条件结束
        $total = $query->count('*',$connection);
		$_GET['page'] = $_POST['page']; //注意：底层默认是以get方式获取page参数的，但这里因为要post方法传递，所有加上此句代码。
        $pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) + 1 : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
            ->orderBy('collection_datetime asc')
            ->all($connection);
        if($data){
            //根据车架号查出车牌号
            $res = Car::find()
                ->select(['plate_number'])
                ->where(['vehicle_dentification_number'=>$carVin,'is_del'=>0])
                ->asArray()->one();
            $plate_number = is_array($res) && !empty($res) ? $res['plate_number'] : '';
            //配置百度地图AK
            $config = (new ConfigCategory)->getCategoryConfig(['bmap_ak']);
            $bmapAk = current($config['bmap_ak'])['value'];
            $analysisDataArr = [];
            foreach($data as $key=>$val){
                $carRealtimeDataAnalysis = new CarRealtimeDataAnalysis($val['data_hex']);
                $analysisData = $carRealtimeDataAnalysis->getRealtimeData();
                if( !isset($analysisData['collection_datetime']) || !isset($analysisData['total_driving_mileage']) ||
                    !isset($analysisData['battery_package_soc']) || !isset($analysisData['car_current_status']) ||
                    !isset($analysisData['longitude_value']) || !isset($analysisData['latitude_value'])
                ){
                    unset($data[$key]);
                    continue;
                }
                $analysisDataItem = [
                    'plate_number'=>$plate_number,
                    'car_vin'=>$val['car_vin'],
                    'collection_datetime'=>$analysisData['collection_datetime'],
                    'total_driving_mileage'=>$analysisData['total_driving_mileage'],
                    'battery_package_soc'=>$analysisData['battery_package_soc'],
                    'car_current_status'=>$analysisData['car_current_status'],
                    'longitude_value'=>$analysisData['longitude_value'],
                    'latitude_value'=>$analysisData['latitude_value'],
                    'position'=>''
                ];
                if($analysisDataItem['longitude_value'] > 0 && $analysisDataItem['latitude_value'] > 0){
                    //获取当前位置
                    $apiUrl = "http://api.map.baidu.com/geocoder/v2/?output=json&ak={$bmapAk}&location={$analysisDataItem['latitude_value']},{$analysisDataItem['longitude_value']}";
                    //初始化
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $apiUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    //执行并获取HTML文档内容
                    $output = curl_exec($ch);
                    //释放curl句柄
                    curl_close($ch);
                    //打印获得的数据
                    $apiPosition = json_decode($output);
                    if($apiPosition->status == 0){
                        $analysisDataItem['position'] = $apiPosition->result->formatted_address;
                    }
                }
                $analysisDataArr[] = $analysisDataItem;
                unset($data[$key]);
            }
            unset($data);
            //计算车辆消耗
            foreach($analysisDataArr as $key=>&$moniDataItem){
                if(isset($analysisDataArr[$key + 1])){
                    $moniDataItem['drive_mileage'] = number_format(($analysisDataArr[$key + 1]['total_driving_mileage'] - $moniDataItem['total_driving_mileage']),1);
                    $moniDataItem['use_soc'] = number_format(($moniDataItem['battery_package_soc'] - $analysisDataArr[$key + 1]['battery_package_soc']),1);
                    $moniDataItem['use_time'] = $analysisDataArr[$key + 1]['collection_datetime'] - $moniDataItem['collection_datetime'];
                    //var_dump($moniDataItem);
                    $hour = floor($moniDataItem['use_time'] / 3600);
                    $minute = floor(($moniDataItem['use_time'] - $hour * 3600) / 60);
                    $second = ($moniDataItem['use_time'] - $hour * 3600 ) % 60;
                    $moniDataItem['use_time'] = $hour.'时'.$minute.'分'.$second.'秒';
                    $moniDataItem['start_longitude_latitude'] = $moniDataItem['longitude_value'].'/'.$moniDataItem['latitude_value'];
                    $moniDataItem['end_longitude_latitude'] = $analysisDataArr[$key + 1]['longitude_value'].'/'.$analysisDataArr[$key + 1]['latitude_value'];
                    $moniDataItem['end_position'] = $analysisDataArr[$key + 1]['position'];
                    $moniDataItem['collection_datetime'] = date('Y-m-d H:i:s',$moniDataItem['collection_datetime']);
                }
            }
            $lastRecord = array_pop($analysisDataArr);
            //var_dump($lastRecord);
            if(!empty($lastRecord['drive_mileage'])){
                $lastRecord[] = $lastRecord;
            }else{
                $total --;
            }
        }
        $dataLength = count($analysisDataArr);
        if($dataLength >= $pageSize){
            array_pop($analysisDataArr);
        }
        $returnArr['total'] = $total;
        $returnArr['rows'] = $analysisDataArr;
        // 表格底部增加合计行
        $data = $query->select(['data_hex'])->offset(0)->limit(-1)->orderBy('collection_datetime asc')->all($connection);
        $analysisDataArr = [];
        foreach($data as $key=>$val){
        	$carRealtimeDataAnalysis = new CarRealtimeDataAnalysis($val['data_hex']);
        	$analysisData = $carRealtimeDataAnalysis->getRealtimeData();
        	if( !isset($analysisData['collection_datetime']) || !isset($analysisData['total_driving_mileage']) ||
        			!isset($analysisData['battery_package_soc']) || !isset($analysisData['car_current_status']) ||
        			!isset($analysisData['longitude_value']) || !isset($analysisData['latitude_value'])
        	){
        		unset($data[$key]);
        		continue;
        	}
        	$analysisDataItem = [
        	'collection_datetime'=>$analysisData['collection_datetime'],
        	'total_driving_mileage'=>$analysisData['total_driving_mileage'],
        	'battery_package_soc'=>$analysisData['battery_package_soc']
        	];
        	$analysisDataArr[] = $analysisDataItem;
        	unset($data[$key]);
        }
        unset($data);
        //计算车辆总消耗
        $total_drive_mileage = 0;
        $total_use_soc = 0;
        $total_use_time = 0;
        foreach($analysisDataArr as $key=>&$moniDataItem){
        	if(isset($analysisDataArr[$key + 1])){
        		$moniDataItem['drive_mileage'] = number_format(($analysisDataArr[$key + 1]['total_driving_mileage'] - $moniDataItem['total_driving_mileage']),1);
        		$moniDataItem['use_soc'] = number_format(($moniDataItem['battery_package_soc'] - $analysisDataArr[$key + 1]['battery_package_soc']),1);
        		$moniDataItem['use_time'] = $analysisDataArr[$key + 1]['collection_datetime'] - $moniDataItem['collection_datetime'];
        		$total_drive_mileage += $moniDataItem['drive_mileage'];
        		if($moniDataItem['use_soc']>0){
        			$total_use_soc += $moniDataItem['use_soc'];
        			if($moniDataItem['drive_mileage'] > 0){
        				$total_use_time += $moniDataItem['use_time'];
        			}
        		}
        	}
        }
        $hour = floor($total_use_time / 3600);
        $minute = floor(($total_use_time - $hour * 3600) / 60);
        $second = ($total_use_time - $hour * 3600 ) % 60;
        $total_use_time = $hour.'时'.$minute.'分'.$second.'秒';
        $returnArr['footer'] = [[
                'plate_number'=>'合计：',
                'drive_mileage'=>$total_drive_mileage,
                'use_soc'=>$total_use_soc,
                'use_time'=>$total_use_time
                ]];
        return json_encode($returnArr);
    }


    /**
     * “车辆行驶记录”-导出列表
     */
    public function actionDriveRecordExport(){
        set_time_limit(0);
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'car_drive_record',
            'subject'=>'car_drive_record',
            'description'=>'car_drive_record',
            'keywords'=>'car_drive_record',
            'category'=>'car_drive_record'
        ]);
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'车牌号','font-weight'=>true,'width'=>'15'],
                ['content'=>'车架号','font-weight'=>true,'width'=>'20'],
                ['content'=>'车辆状态','font-weight'=>true,'width'=>'10'],
                ['content'=>'采集时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'当前里程（km）','font-weight'=>true,'width'=>'15'],
                ['content'=>'当前SOC（%）','font-weight'=>true,'width'=>'15'],
                ['content'=>'行驶里程（km）','font-weight'=>true,'width'=>'15'],
                ['content'=>'消耗SOC（%）','font-weight'=>true,'width'=>'15'],
                ['content'=>'使用时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'起始经纬度','font-weight'=>true,'width'=>'25'],
                ['content'=>'结束经纬度','font-weight'=>true,'width'=>'25'],
                ['content'=>'起始位置','font-weight'=>true,'width'=>'35'],
                ['content'=>'结束位置','font-weight'=>true,'width'=>'35']
            ]
        ];
        //---向excel添加表头-----------------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }
        //指定excel字段索引顺序
        $specialKeys = [
            'plate_number','car_vin','car_current_status','collection_datetime',
            'total_driving_mileage','battery_package_soc','drive_mileage','use_soc',
            'use_time','start_longitude_latitude','end_longitude_latitude','position','end_position'
        ];

        //////查询行驶记录-begin
        $carVin = yii::$app->request->get('car_vin','');
        $statusChangeIds = yii::$app->request->get('statusChangeIds','');
        $startDate = yii::$app->request->get('startDate','');
        $endDate = yii::$app->request->get('endDate','');
        if($statusChangeIds && $startDate && $endDate){
            $statusChangeIds = explode(',',$statusChangeIds);
            $ym = date('Ym',strtotime($startDate));
            //$tableName = 'cs_tcp_car_history_data_'.$ym;
            if(strtotime($startDate) <= strtotime('2016-04-15 10:00:00')){
                //2016年4月1日之前的数据
                $tableName = 'cs_tcp_car_history_data_'.$ym;
            }else{
                //2016年4月1日之后的数据
                $tableName = 'cs_tcp_car_history_data_'.$ym.'_'.substr($carVin,-1);
            }
            if($ym == date('Ym') || $ym == date('Ym',strtotime("-1 month"))){
                $connection = yii::$app->db1;
            }else{
                $connection = yii::$app->db2;
            }
            $query = (new \yii\db\Query())
                ->select(['car_vin','data_hex'])
                ->from($tableName)
                ->where(['id'=>$statusChangeIds]);
            //查询条件结束
            $data = $query->orderBy('collection_datetime asc')->all($connection);
            if($data){
                //根据车架号查出车牌号
                $res = Car::find()
                    ->select(['plate_number'])
                    ->where(['vehicle_dentification_number'=>$carVin,'is_del'=>0])
                    ->asArray()->one();
                $plate_number = is_array($res) && !empty($res) ? $res['plate_number'] : '';
                //配置百度地图AK
                $config = (new ConfigCategory)->getCategoryConfig(['bmap_ak']);
                $bmapAk = current($config['bmap_ak'])['value'];
                $analysisDataArr = [];
                foreach($data as $key=>$val){
                    $carRealtimeDataAnalysis = new CarRealtimeDataAnalysis($val['data_hex']);
                    $analysisData = $carRealtimeDataAnalysis->getRealtimeData();
                    if( !isset($analysisData['collection_datetime']) || !isset($analysisData['total_driving_mileage']) ||
                        !isset($analysisData['battery_package_soc']) || !isset($analysisData['car_current_status']) ||
                        !isset($analysisData['longitude_value']) || !isset($analysisData['latitude_value'])
                    ){
                        unset($data[$key]);
                        continue;
                    }
                    $analysisDataItem = [
                        'plate_number'=>$plate_number,
                        'car_vin'=>$val['car_vin'],
                        'collection_datetime'=>$analysisData['collection_datetime'],
                        'total_driving_mileage'=>$analysisData['total_driving_mileage'],
                        'battery_package_soc'=>$analysisData['battery_package_soc'],
                        'car_current_status'=>$analysisData['car_current_status'],
                        'longitude_value'=>$analysisData['longitude_value'],
                        'latitude_value'=>$analysisData['latitude_value'],
                        'position'=>''
                    ];
                    if($analysisDataItem['longitude_value'] > 0 && $analysisDataItem['latitude_value'] > 0){
                        //获取当前位置
                        $apiUrl = "http://api.map.baidu.com/geocoder/v2/?output=json&ak={$bmapAk}&location={$analysisDataItem['latitude_value']},{$analysisDataItem['longitude_value']}";
                        //初始化
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $apiUrl);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        //执行并获取HTML文档内容
                        $output = curl_exec($ch);
                        //释放curl句柄
                        curl_close($ch);
                        //打印获得的数据
                        $apiPosition = json_decode($output);
                        if($apiPosition->status == 0){
                            $analysisDataItem['position'] = $apiPosition->result->formatted_address;
                        }
                    }
                    $analysisDataArr[] = $analysisDataItem;
                    unset($data[$key]);
                }
                unset($data);
                //计算车辆消耗
                foreach($analysisDataArr as $key=>&$moniDataItem){
                    if(isset($analysisDataArr[$key + 1])){
                        $moniDataItem['drive_mileage'] = number_format(($analysisDataArr[$key + 1]['total_driving_mileage'] - $moniDataItem['total_driving_mileage']),1);
                        $moniDataItem['use_soc'] = number_format(($moniDataItem['battery_package_soc'] - $analysisDataArr[$key + 1]['battery_package_soc']),1);
                        $moniDataItem['use_time'] = $analysisDataArr[$key + 1]['collection_datetime'] - $moniDataItem['collection_datetime'];
                        //var_dump($moniDataItem);
                        $hour = floor($moniDataItem['use_time'] / 3600);
                        $minute = floor(($moniDataItem['use_time'] - $hour * 3600) / 60);
                        $second = ($moniDataItem['use_time'] - $hour * 3600 ) % 60;
                        $moniDataItem['use_time'] = $hour.'时'.$minute.'分'.$second.'秒';
                        $moniDataItem['start_longitude_latitude'] = $moniDataItem['longitude_value'].'/'.$moniDataItem['latitude_value'];
                        $moniDataItem['end_longitude_latitude'] = $analysisDataArr[$key + 1]['longitude_value'].'/'.$analysisDataArr[$key + 1]['latitude_value'];
                        $moniDataItem['end_position'] = $analysisDataArr[$key + 1]['position'];
                        $moniDataItem['collection_datetime'] = date('Y-m-d H:i:s',$moniDataItem['collection_datetime']);
                    }
                }
                $lastRecord = array_pop($analysisDataArr);
                //var_dump($lastRecord);
                if(!empty($lastRecord['drive_mileage'])){
                    $lastRecord[] = $lastRecord;
                }
                //print_r($analysisDataArr);exit;
                //---向excel添加具体数据--------------------
                foreach($analysisDataArr as $item){
                    $item['car_current_status'] = $item['car_current_status']==0 ? '停止' : ($item['car_current_status']==1 ? '行驶' : $item['car_current_status']);
                    $lineData = [];
                    foreach($specialKeys as $key) {
                        $lineData[] = ['content'=>$item[$key]];
                    }
                    $excel->addLineToExcel($lineData);
                }
                unset($analysisDataArr);
            }
        }
        //////查询行驶记录-end

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','车辆行驶记录明细列表导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }


    /**
     * soc变化曲线图
     * car/drive-statistics/soc-change
     */
    public function actionSocChange(){
        /*$date = yii::$app->request->get('date');
        $carVin = yii::$app->request->get('car_vin');
        $date = yii::$app->request->get('date');
        if(!$date){
            $date = date('Y-m-d');
        }
        $date = strtotime($date);
        $tableName = 'cs_tcp_car_history_data_'.date('Ym',$date);
        if(date('Ym',$date) == date('Ym')){
            $connection = yii::$app->db1;
        }else{
            $connection = yii::$app->db2;
        }
        $data = (new \yii\db\Query())
            ->select(['distinct `collection_datetime`','battery_package_soc'])
            ->from($tableName)
            ->where('car_vin = :car_vin and `collection_datetime` >= :collection_datetime_s and `collection_datetime` <= :collection_datetime_e',[
                ':car_vin'=>$carVin,
                ':collection_datetime_s'=>$date,
                ':collection_datetime_e'=>$date + 86400,
            ])->orderBy('collection_datetime asc')
            ->all($connection);
        var_dump($data);*/
    }


    /*************************************************************
     * “车辆充电记录明细”菜单【查询页面入口】
     ************************************************************/
    public function actionChargeRecordIndex(){
        $buttons = $this->getCurrentActionBtn();
        return $this->render('charge-record-index',[
            'buttons'=>$buttons,
        ]);
    }

    /**
     * “车辆充电记录”-顶部表单检索
     */
    public function actionChargeRecordSearch(){
        $search = yii::$app->request->get();
        $startDate = $search['start_date'];
        $endDate = $search['end_date'];
        $carVin = $search['car_vin'];
        $plateNumber = $search['plate_number'];
        if(!$carVin && !$plateNumber){
            $returnArr['status'] = false;
            $returnArr['info'] = '车牌号和车架号不能同时为空！';
            return json_encode($returnArr);
        }
        if(!$startDate || !$endDate){
            switch ($search['search_type']) {
                case 'this_day':
                    $startDate = date('Y-m-d 00:00:00');
                    $endDate = date('Y-m-d 23:59:59');
                    break;
                case 'this_week':
                    $startDate = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
                    $endDate = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));
                    break;
                case 'this_month':
                    $startDate = date('Y-m-01 00:00:00');
                    $endDate = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y")));
                    break;
                default:
                    break;
            }
        }
        if(!$startDate || !$endDate) {
            $returnArr['status'] = false;
            $returnArr['info'] = '请指定一个查询时间段！';
            return json_encode($returnArr);
        }
        $carInfo = Car::find()
            ->select([
                'car_vin'=>'vehicle_dentification_number'
            ])
            ->andWhere(['is_del'=>0])
            ->andFilterWhere(['plate_number'=>$plateNumber])
            ->andFilterWhere(['vehicle_dentification_number'=>$carVin])
            ->asArray()->one();
        if(!$carInfo){
            $returnArr['status'] = false;
            $returnArr['info'] = '所填车牌号或车架号不存在！';
            return json_encode($returnArr);
        }
        //查车辆充电记录
        $res = $this->getChargeHistoryDataIds($carInfo['car_vin'], $startDate, $endDate);
        if($res['status']){
            $returnArr['status'] = true;
            $returnArr['car_vin'] = $carInfo['car_vin'];
            $returnArr['validIdStr'] = $res['validIdStr'];
            $returnArr['startDate'] = $startDate;
            $returnArr['endDate'] = $endDate;
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = $res['info'];
        }
        return json_encode($returnArr);
    }


    /*
     * “车辆充电记录”-查询符合条件的历史记录id
     */
    protected function getChargeHistoryDataIds($carVin,$startDate,$endDate){
        if(!$carVin){
            return ['status'=>false,'info'=>'缺少车架号！'];
        }
        if(!$startDate || !$endDate){
            return ['status'=>false,'info'=>'缺少查询时间段！'];
        }
        if(substr($startDate,0,7) != substr($endDate,0,7)){
            return ['status'=>false,'info'=>'目前不支持跨月份查询！'];
        }
        $ym = date('Ym',strtotime($startDate));
        //$tableName = 'cs_tcp_car_history_data_'.$ym;
        if(strtotime($startDate) <= strtotime('2016-04-15 10:00:00')){
            //2016年4月1日之前的数据
            $tableName = 'cs_tcp_car_history_data_'.$ym;
        }else{
            //2016年4月1日之后的数据
            $tableName = 'cs_tcp_car_history_data_'.$ym.'_'.substr($carVin,-1);
        }
        if($ym == date('Ym') || $ym == date('Ym',strtotime("-1 month"))){
            $connection = yii::$app->db1;
        }else{
            $connection = yii::$app->db2;
        }
        $res = $connection->createCommand("SHOW TABLES LIKE '{$tableName}'")->queryAll();
        if(!$res){
            return ['status'=>false,'info'=>"找不到数据表：{$tableName}"];
        }
        $data = (new \yii\db\Query())
            ->select(['id','car_vin','collection_datetime'])
            ->from($tableName)
            ->where(
                'car_vin = :car_vin and `collection_datetime` >= :collection_datetime_s and `collection_datetime` <= :collection_datetime_e and car_current_status = 2',
                [
                    ':car_vin'=>$carVin,
                    ':collection_datetime_s'=>strtotime($startDate),
                    ':collection_datetime_e'=>strtotime($endDate) + 86400,
                ]
            )
            ->orderBy('collection_datetime asc')
            ->all($connection);
        if(!$data){
            return ['status'=>false,'info'=>'未查询到车辆充电历史数据！'];
        }
        //print_r($data);exit;

        //筛选出同一次充电记录的开始和结束记录的id。
        //注意：因为一次充电会不断的上报数据，所以上报间隔固定秒数内都视为同一次充电记录。
        $recIds = [];
        $seconds = 300;  //上报间隔秒数
        foreach($data as $key=>$val){
            //先判断当前记录的前一条记录是否存在并且与当前记录上报时间间隔在范围内，若否，则当前记录为某一次充电开始记录；
            //否则需要进一步判断当前记录的后一条记录是否存在并且与当前记录上报时间间隔在范围内，若是，则当前记录不是本次充电结束记录，否则是本次充电结束记录。
            if(isset($data[$key-1]) && ($val['collection_datetime'] - $data[$key-1]['collection_datetime']) < $seconds){
                if(isset($data[$key+1]) && ($data[$key+1]['collection_datetime']-$val['collection_datetime']) < $seconds){
                    continue;
                }else{
                    array_push($recIds[count($recIds)-1],$val['id']);
                }
            }else{
                $recIds[] = [$val['id']];
            }
        }
        unset($data);
        //print_r($recIds);exit;
        $validIdStr = '';
        if($recIds){
            //筛选出有效记录id（一次充电有开始也有结束记录才统计为有效）
            $validIds = [];
            foreach($recIds as $item){
                if(count($item) == 2){
                    $validIds[] = implode(',',$item);
                }
            }
            $validIdStr = implode(',',$validIds);
        }
        return ['status'=>true,'validIdStr'=>$validIdStr];;
    }


    /*
     * “车辆充电记录”-获取列表
     */
    public function actionChargeRecordGetList(){
        $carVin = yii::$app->request->post('car_vin','');
        $validIdStr = yii::$app->request->post('validIdStr','');
        $startDate = yii::$app->request->post('startDate','');
        $endDate = yii::$app->request->post('endDate','');
        if(!$validIdStr || !$startDate || !$endDate){
            return json_encode(['total'=>0,'rows'=>[]]);
        }
        $ym = date('Ym',strtotime($startDate));
        //$tableName = 'cs_tcp_car_history_data_'.$ym;
        if(strtotime($startDate) <= strtotime('2016-04-15 10:00:00')){
            //2016年4月1日之前的数据
            $tableName = 'cs_tcp_car_history_data_'.$ym;
        }else{
            //2016年4月1日之后的数据
            $tableName = 'cs_tcp_car_history_data_'.$ym.'_'.substr($carVin,-1);
        }
        if($ym == date('Ym') || $ym == date('Ym',strtotime("-1 month"))){
            $connection = yii::$app->db1;
        }else{
            $connection = yii::$app->db2;
        }
        $query = (new \yii\db\Query())
            ->select(['id','car_vin','collection_datetime','car_current_status','battery_package_soc'])
            ->from($tableName)
            ->where(['in','id',explode(',',$validIdStr)]);
        $total = $query->count('*',$connection);
		$_GET['page'] = $_POST['page']; //注意：底层默认是以get方式获取page参数的，但这里因为要post方法传递，所有加上此句代码。
        $pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) * 2 : 10; //需要2条才能合成一条
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query
            ->offset($pages->offset)->limit($pages->limit)
            ->orderBy('collection_datetime ASC')
            ->all($connection);
        if(!$data){
            $arr['status'] = false;
            $arr['info'] = '未查询到车辆充电历史数据！';
            return $arr;
        }
        //print_r($data);exit;

        //根据车架号查出车牌号
        $res = Car::find()
            ->select(['plate_number'])
            ->where(['vehicle_dentification_number'=>$data[0]['car_vin']])
            ->asArray()->one();
        $plate_number = is_array($res) && !empty($res) ? $res['plate_number'] : '';

        //下面是组合充电起止记录
        //拆分成每2条一组的小数组，这样每个小数组即表示同一次充电的开始和结束记录
        $chunkData = array_chunk($data,2);
        $tmp = [];
        foreach($chunkData as $item){
            $tmp[] = [
                'plate_number'=>$plate_number,
                'car_vin'=>$item[0]['car_vin'],
                'start_time'=>$item[0]['collection_datetime'],
                'end_time'=>$item[1]['collection_datetime'],
                'charge_time'=>($item[1]['collection_datetime'] - $item[0]['collection_datetime']),
                'start_soc'=>$item[0]['battery_package_soc'],
                'end_soc'=>$item[1]['battery_package_soc'],
                'charge_soc'=>($item[1]['battery_package_soc'] - $item[0]['battery_package_soc'])
            ];
        }
        unset($data);
        unset($chunkData);
        //print_r($tmp);exit;
        $arr['total'] = $total / 2;
        $arr['rows'] = $tmp;
        return json_encode($arr);
    }


    /**
     * “车辆充电记录”-导出列表
     */
    public function actionChargeRecordExport(){
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'car_charge_record',
            'subject'=>'car_charge_record',
            'description'=>'car_charge_record',
            'keywords'=>'car_charge_record',
            'category'=>'car_charge_record'
        ]);
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'车牌号','font-weight'=>true,'width'=>'15'],
                ['content'=>'车架号','font-weight'=>true,'width'=>'20'],
                ['content'=>'开始充电时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'结束充电时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'充电时长','font-weight'=>true,'width'=>'15'],
                ['content'=>'开始SOC（%）','font-weight'=>true,'width'=>'15'],
                ['content'=>'结束SOC（%）','font-weight'=>true,'width'=>'15'],
                ['content'=>'充电SOC（%）','font-weight'=>true,'width'=>'15']
            ]
        ];
        //---向excel添加表头-----------------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }

        //////查询充电记录-begin
        $carVin = yii::$app->request->get('car_vin','');
        $validIdStr = yii::$app->request->get('validIdStr','');
        $startDate = yii::$app->request->get('startDate','');
        $endDate = yii::$app->request->get('endDate','');
        if($validIdStr && $startDate && $endDate){
            $ym = date('Ym',strtotime($startDate));
            //$tableName = 'cs_tcp_car_history_data_'.$ym;
            if(strtotime($startDate) <= strtotime('2016-04-15 10:00:00')){
                //2016年4月1日之前的数据
                $tableName = 'cs_tcp_car_history_data_'.$ym;
            }else{
                //2016年4月1日之后的数据
                $tableName = 'cs_tcp_car_history_data_'.$ym.'_'.substr($carVin,-1);
            }
            if($ym == date('Ym') || $ym == date('Ym',strtotime("-1 month"))){
                $connection = yii::$app->db1;
            }else{
                $connection = yii::$app->db2;
            }
            $query = (new \yii\db\Query())
                ->select(['id','car_vin','collection_datetime','car_current_status','battery_package_soc'])
                ->from($tableName)
                ->where(['in','id',explode(',',$validIdStr)]);
            //$total = $query->count('*',$connection);
            //$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) * 2 : 10; //需要2条才能合成一条
            //$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
            $data = $query
                //->offset($pages->offset)->limit($pages->limit)
                ->orderBy('collection_datetime ASC')
                ->all($connection);
            if(!$data){
                $arr['status'] = false;
                $arr['info'] = '未查询到车辆充电历史数据！';
                return $arr;
            }
            //print_r($data);exit;
            //根据车架号查出车牌号
            $res = Car::find()
                ->select(['plate_number'])
                ->where(['vehicle_dentification_number'=>$data[0]['car_vin']])
                ->asArray()->one();
            $plate_number = is_array($res) && !empty($res) ? $res['plate_number'] : '';

            //下面是组合充电起止记录
            //拆分成每2条一组的小数组，这样每个小数组即表示同一次充电的开始和结束记录
            $chunkData = array_chunk($data,2);
            $tmp = [];
            foreach($chunkData as $item){
                $tmp[] = [
                    'plate_number'=>$plate_number,
                    'car_vin'=>$item[0]['car_vin'],
                    'start_time'=>$item[0]['collection_datetime'],
                    'end_time'=>$item[1]['collection_datetime'],
                    'charge_time'=>($item[1]['collection_datetime'] - $item[0]['collection_datetime']),
                    'start_soc'=>$item[0]['battery_package_soc'],
                    'end_soc'=>$item[1]['battery_package_soc'],
                    'charge_soc'=>($item[1]['battery_package_soc'] - $item[0]['battery_package_soc'])
                ];
            }
            unset($data);
            unset($chunkData);
            //print_r($tmp);exit;
            //---向excel添加具体数据--------------------
            foreach($tmp as $item){
                $item['start_time'] = $item['start_time']>0 ? date('Y-m-d H:i:s',$item['start_time']) : '';
                $item['end_time'] = $item['end_time']>0 ? date('Y-m-d H:i:s',$item['end_time']) : '';
                if($item['charge_time']){
                    $sec = $item['charge_time'];
                    $h = floor( $sec / 3600 );
                    $diff = $sec - $h * 3600;
                    $m = floor( $diff / 60);
                    $diff2 = $diff - $m * 60;
                    $str = '';
                    if($h){
                        $str .= $h . '时';
                    }
                    if($m){
                        $str .= $m . '分';
                    }
                    if($diff2){
                        $str .= $diff2 . '秒';
                    }
                    $item['charge_time'] = $str;
                }
                $lineData = [];
                foreach($item as $k=>$v) {
                    $lineData[] = ['content'=>$v];
                }
                $excel->addLineToExcel($lineData);
            }
            unset($tmp);
        }
        //////查询充电记录-end

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','车辆充电记录明细列表导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }



}