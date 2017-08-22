<?php
/**
 * 车辆数据分析控制器
 * time    2015/12/30 10：17
 * @author wangmin
 */
namespace backend\modules\carmonitorgb\controllers;
use yii\db\MongoDBNew;

use backend\controllers\BaseController;
use common\classes\CarRealtimeDataAnalysis;
use yii;
use yii\data\Pagination;
class AnalysisController extends BaseController{
    /**
     * 电池数据分析
     */
    public function actionBattery(){
        if(yii::$app->request->isPost){
            //post请求
            $returnArr = [
                'status'=>false,
                'msg'=>'',
                'temData'=>[],
                'volData'=>[],
            ];
            $carVin = yii::$app->request->post('car_vin');
            if(!$carVin){
                $returnArr['msg'] = '参数vin缺失！';
                return json_encode($returnArr);
            }
            $startDate = yii::$app->request->post('start_datetime');
            if(!$startDate){
                $startDate = date('Y-m-d H').":00:00";
            }
            $startDatetime = strtotime($startDate);
            $endDate = yii::$app->request->get('end_date');
            $endDatetime = $endDate?strtotime($endDate):time();
            
            $db = new MongoDBNew('car_history_data_'.date('Ym',$startDatetime).'_'.substr($carVin,-1));
            $filter = [];
            $options = [
	            'projection' => [
		            '_id'=>0,
		            'collectionDatetime'=>1,'battteryTemperatureData'=>1,'battteryVoltageData'=>1
	            ],
	            'sort' => ['collectionDatetime' => 1]	//-1降序
            ];
            //查询条件
            $filter = [
	            'carVin' => $carVin,
	            'collectionDatetime' => ['$gte' => $startDatetime, '$lte' => $endDatetime]
            ];
//             $db->where(['carVin' => $carVin]);
//             $db->where_gte('collectionDatetime',$startDatetime);
//             $db->where_lte('collectionDatetime',$endDatetime);
            //////查询条件结束
//             print_r($db->getSql());
            $count = $db->getCount($filter);
            if($count >= 5000){
            	$returnArr['msg'] = '数据过大，请选择合适的开始时间与结束时间！';
            	return json_encode($returnArr);
            }else if($count == 0){
            	$returnArr['msg'] = '所选时段无数据！';
            	return json_encode($returnArr);
            }
            
            $data = $db->query($filter, $options);
            foreach($data as $row){
                //处理温度数据
            	$battteryTemperatureData = $row->battteryTemperatureData;
                if($battteryTemperatureData){
                	$totalProbe = 0;
                    foreach ($battteryTemperatureData as $k=>$val){
                    	$totalProbe += $val->totalProbe;
                    	$cDate = date('Y-m-d H:i:s',$row->collectionDatetime);
                    	$temVal = $val->battteryTemperatureList?max($val->battteryTemperatureList):0;
                    	$returnArr['temData']['data'][$k][] = [
	                    	'collection_datetime'=>$cDate,
	                    	'tem_val'=>$temVal,
                    	];
                    }
                    $returnArr['temData']['totalPackage'] = count($battteryTemperatureData);
                    $returnArr['temData']['totalProbe'] = $totalProbe;
                }
                //处理电压数据
                $battteryVoltageData = $row->battteryVoltageData;
                if($battteryVoltageData){
                	$totalSingleBattery = 0;
                	foreach($battteryVoltageData as $k=>$v){
                		$totalSingleBattery += $v->totalBattery;
                		$cDate = date('Y-m-d H:i:s',$row->collectionDatetime);
                		$volVal = $v->battteryVoltageList ? max($v->battteryVoltageList) : 0 ;
                		$volVal = number_format($volVal,3);
                		$returnArr['volData']['data'][$k][] = [
	                		'collection_datetime'=>$cDate,
	                        'vol_val'=>$volVal,
                        ];
                    }
                    $returnArr['volData']['totalPackage'] = count($battteryVoltageData);
                    $returnArr['volData']['totalSingleBattery'] = $totalSingleBattery;
                }
            }
            $returnArr['status'] = true;
            return json_encode($returnArr);
        }else{
            //get请求
            $carVin = yii::$app->request->get('car_vin') or die('param car_vin is required');
            $buttons = $this->getCurrentActionBtn();
            return $this->render('battery',[
                'carVin'=>$carVin,
                'buttons'=>$buttons,
            ]);
        }
    }

}