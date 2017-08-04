<?php
/**
 * 车辆监控控制器
 * time    2015/11/10 09:48
 * @author wangmin
 */
namespace backend\modules\car\controllers;
use backend\controllers\BaseController;
use backend\models\TcpCarRealtimeData;
use backend\models\Car;
use yii;
use yii\data\Pagination;
class MonitorController extends BaseController
{

    /**
     * 
     */
    public function actionIndex()
    {
        $buttons  = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }
    /**
     * 查看车辆实时整车数据
     */
    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = TcpCarRealtimeData::find()
                ->select([
                    TcpCarRealtimeData::tableName().'.`id`',
                    TcpCarRealtimeData::tableName().'.`data_source`',
                    TcpCarRealtimeData::tableName().'.`collection_datetime`',
                    TcpCarRealtimeData::tableName().'.`update_datetime`',
                    TcpCarRealtimeData::tableName().'.`car_vin`',
                    TcpCarRealtimeData::tableName().'.`total_driving_mileage`',
                    TcpCarRealtimeData::tableName().'.`position_effective`',
                    TcpCarRealtimeData::tableName().'.`latitude_type`',
                    TcpCarRealtimeData::tableName().'.`longitude_type`',
                    TcpCarRealtimeData::tableName().'.`latitude_value`',
                    TcpCarRealtimeData::tableName().'.`longitude_value`',
                    TcpCarRealtimeData::tableName().'.`speed`',
                    TcpCarRealtimeData::tableName().'.`direction`',
                    TcpCarRealtimeData::tableName().'.`battery_package_soc`',
                    TcpCarRealtimeData::tableName().'.`car_current_status`',
                    Car::tableName().'.`plate_number`'
                ])
                ->joinWith('car',false,'LEFT JOIN');
        //查询条件
        $query->andFilterWhere([
            'like',
            Car::tableName().'.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $query->andFilterWhere([
            'like',
            TcpCarRealtimeData::tableName().'.`car_vin`',
            yii::$app->request->get('car_vin')
        ]);
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'plate_number':
                    $orderBy = Car::tableName().'.`plate_number` ';
                    break;
                default:
                    $orderBy = TcpCarRealtimeData::tableName().'.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = TcpCarRealtimeData::tableName().'.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy($orderBy)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 查看详细
     */
    public function actionDetail()
    {
        $id = yii::$app->request->get('id') or die('param id is required!');
        $attributes = (new TcpCarRealtimeData)->attributeLabels();
        //移出不需要查询的字段
        unset($attributes['id']);
        unset($attributes['gear']);
        unset($attributes['battery_package_voltage']);
        unset($attributes['battery_package_temperature']);
        unset($attributes['alert_data']);
        $data = TcpCarRealtimeData::find()->select(array_keys($attributes))->where(['id'=>$id])->asArray()->one();
        $_data = [];
        if($data){
            //数据处理
            $data['collection_datetime'] = date('Y-m-d H:i:s',$data['collection_datetime']);
            $data['update_datetime'] = date('Y-m-d H:i:s',$data['update_datetime']);
            $data['ignition_datetime'] = $data['ignition_datetime'] ? date('Y-m-d H:i:s',$data['ignition_datetime']) : '';
            $data['flameout_datetime'] = $data['flameout_datetime'] ? date('Y-m-d H:i:s',$data['flameout_datetime']) : '';
            $data['position_effective'] = $data['position_effective'] ? '无效' : '有效';
            $data['latitude_type'] = $data['latitude_type'] ? '南纬' : '北纬';
            $data['longitude_type'] = $data['longitude_type'] ? '西经' : '东经';
            $data['brake_pedal_status'] = $data['brake_pedal_status'] == 1 ? '启用' : '不启用' ;
            $data['power_system_ready'] = $data['power_system_ready'] ? '就绪' : '未就绪' ;
            $data['emergency_electric_request'] = $data['emergency_electric_request'] ? '异常' : '正常' ;
            switch ($data['car_current_status']) {
                case '0':
                    $data['car_current_status'] = '停止';
                    break;
                case '1':
                    $data['car_current_status'] = '行驶';
                    break;
                case '2':
                    $data['car_current_status'] = '充电';
                    break;
            }
            $data['battery_package_equilibria_active'] = $data['battery_package_equilibria_active'] ? '无均衡' : '均衡活动中' ;
            foreach($data as $key=>$val){
                $_data[$key] = [
                    'name'=>$attributes[$key],
                    'value'=>$val,
                    'unit'=>'',
                ];
            }
            //添加单位
            $_data['speed']['unit'] = 'km/h';
            $_data['accelerator_pedal']['unit'] = '%';
            $_data['brake_pedal_distance']['unit'] = '%';
            $_data['moter_controller_temperature']['unit'] = '℃';
            $_data['moter_speed']['unit'] = 'r/min';
            $_data['moter_temperature']['unit'] = 'r/min';
            $_data['moter_voltage']['unit'] = 'V';
            $_data['moter_current']['unit'] = 'A';
            $_data['moter_generatrix_current']['unit'] = 'A';
            $_data['air_condition_temperature']['unit'] = '℃';
            $_data['battery_package_total_voltage']['unit'] = 'V';
            $_data['battery_package_current']['unit'] = 'A';
            $_data['battery_package_soc']['unit'] = '%';
            $_data['battery_package_power']['unit'] = 'kw.h';
            $_data['battery_single_hv_value']['unit'] = 'V';
            $_data['battery_single_ht_value']['unit'] = '℃';
            $_data['battery_single_lt_value']['unit'] = '℃';
            $_data['battery_package_resistance_value']['unit'] = 'KΩ';
            $_data['battery_package_fuel_consumption']['unit'] = 'ml/100km';
        }
        return $this->render('detail',[
            'realtimeData'=>$_data
        ]);
    }
}