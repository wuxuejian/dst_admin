<?php
/**
 * 车辆数据分析控制器
 * time    2015/12/30 10：17
 * @author wangmin
 */
namespace backend\modules\carmonitor\controllers;
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
            $startDatetime = yii::$app->request->post('start_datetime');
            if(!$startDatetime){
                $startDatetime = date('Y-m-d H').":00:00";
            }
            $startDatetime = strtotime($startDatetime);
            if(date('Ym',$startDatetime) == date('Ym')){
                $connection = yii::$app->db1;
            }else{
                $connection = yii::$app->db2;
            }
            if($startDatetime < strtotime('2016-04-15')){
                //2016年4月1日之前的数据
                $nowTable = 'cs_tcp_car_history_data_'.date('Ym',$startDatetime);
            }else{
                //2016年4月1日之后的数据
                $nowTable = 'cs_tcp_car_history_data_'.date('Ym',$startDatetime).'_'.substr($carVin,-1);
            }
            $tables = $connection->createCommand('show tables')->queryAll();
            if(!$tables){
                $returnArr['msg'] = '数据库无数据！';
                return json_encode($returnArr);
            }
            $tables = array_column($tables,'Tables_in_car_monidata');
            if(array_search($nowTable,$tables) === false){
                //不存在该数据表
                $returnArr['msg'] = '无法找到请求的数据表！';
                return json_encode($returnArr);
            }
            $query = (new \yii\db\Query())
                ->from($nowTable)
                ->select([
                    'collection_datetime',
                    'data_hex',
                ])->andWhere(['>=','collection_datetime',$startDatetime])
                ->andWhere(['car_vin'=>$carVin]);
                //->createCommand()->sql;
            if(yii::$app->request->post('end_datetime')){
                $query->andWhere([
                    '<=',
                    'collection_datetime',
                    strtotime(yii::$app->request->post('end_datetime')),
                ]);
            }
            $total = $query->count('*',$connection);
            if($total >= 5000){
                $returnArr['msg'] = '数据过大，请选择合适的开始时间与结束时间！';
                return json_encode($returnArr);
            }
            $data = $query->orderBy('`collection_datetime` asc')->all($connection);
            if(!$data){
                $returnArr['msg'] = '所选时段无数据！';
                return json_encode($returnArr);
            }
            foreach($data as $val){
                $analysisObj = new CarRealtimeDataAnalysis($val['data_hex']);
                $realtimeData = $analysisObj->getRealtimeData();
                if(!$realtimeData){
                    continue;
                }
                //处理温度数据
                if(isset($realtimeData['battery_package_temperature'])){
                    $temData = json_decode($realtimeData['battery_package_temperature'],true);
                    $returnArr['temData']['totalProbe'] = $temData['totalProbe'];
                    $returnArr['temData']['totalPackage'] = $temData['totalPackage'];
                    if($temData['temperatureList']){ 
                        foreach($temData['temperatureList'] as $k=>$v){
                            $cDate = date('Y-m-d H:i:s',$val['collection_datetime']);
                            $temVal = $v['probeTemperature'] ? max($v['probeTemperature']) : 0 ;
                            $returnArr['temData']['data'][$k][] = [
                                'collection_datetime'=>$cDate,
                                'tem_val'=>$temVal,
                            ];
                        }
                    }
                }
                if(isset($realtimeData['battery_package_voltage'])){
                    //处理电压数据
                    $volData = json_decode($realtimeData['battery_package_voltage'],true);
                    $returnArr['volData']['totalSingleBattery'] = $volData['totalSingleBattery'];
                    $returnArr['volData']['totalPackage'] = $volData['totalPackage'];
                    if($volData['batteryPackage']){ 
                        foreach($volData['batteryPackage'] as $k=>$v){
                            $cDate = date('Y-m-d H:i:s',$val['collection_datetime']);
                            $volVal = $v['battteryVoltage'] ? max($v['battteryVoltage']) : 0 ;
                            $returnArr['volData']['data'][$k][] = [
                                'collection_datetime'=>$cDate,
                                'vol_val'=>$volVal,
                            ];
                        }
                    }
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