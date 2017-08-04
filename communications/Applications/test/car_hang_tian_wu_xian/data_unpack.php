<?php
/**
 * 车辆实时数据上报控制器
 * 消息id    0x02
 * 应答消息  是
 * @author   wangmin
 * @time     2015/10/29 17:07
 */
use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Store;
use \GatewayWorker\Lib\Db;
date_default_timezone_set('PRC');
class CarDataSubmit
{
    protected $data;//客户端发来的数据
    protected $saveData = [];//将被保存到数据库中的数据
    protected $alertData = [];//报警数据
    public function init($message,$client_id)
    {
        $message['data'] = hex2bin('232302fe4c484231335433453846523132313438390001340f0c0a113a250100540101540e4f0e4d0e4d0e4d0e4a0e4c0e4c0e500e500e4d0e4c0e4a0e4c0e4d0e4c0e470e4a0e490e490e4d0e4c0e490e4c0e400e4c0e500e4c0e4f0e4a0e500e520e520e4c0e4f0e4f0e4a0e4c0e4f0e4c0e4f0e4c0e4d0e4d0e4f0e4d0e400e490e4c0e4d0e4c0e4a0e4d0e4c0e4a0e4d0e4f0e500e470e4c0e490e490e4a0e4c0e4a0e4a0e490e4a0e4a0e4a0e4c0e4a0e490e4c0e470e490e4c0e4a0e490e490e490e440e4c0e400e4702002a01012a3e3d3d3d3e3e3e3d3d3d3d3d3c3c3c3d3d3d3d3d3e3d3d3d3d3e3d3d3d3d3d3d3d3d3e3d3d3d3d3d3d3d03001800004592042000024f01335a00002710ff00000000000000040006ccb0b30157fa5b0000000000000000050101ffff0000ffff01013e01023c0bf427a45f0000ffff000000000006000000000082');
        $message['data'] = substr($message['data'],24);
        //echo '收到车辆实时数据上报信息...',"\n";
        //查询车辆id
        //$db = Db::instance('db');
        /*$car = $db->select('id,car_vin')->from('cs_tcp_car')
                ->where('car_vin = :car_vin')->bindValues(['car_vin'=>$message['carVIN']])
                ->row();*/
        $car = true;
        if(!$car){
            //车辆没有注册
            $send_data = [
                'commandSingle'=>$message['commandSingle'],
                'commandAnswer'=>0x02,//
                'carVIN'=>$message['carVIN'],
                'data'=>'',
            ];
            Gateway::sendToClient($client_id,$send_data);
            return false;
        }
        $this->data = $message['data'];
        //解析数据采集时间
        $subData = substr($this->data,0,6);
        $this->data = substr($this->data,6);
        $formatArr = [
            'year'=>'C1',
            'month'=>'C1',
            'day'=>'C1',
            'hour'=>'C1',
            'minute'=>'C1',
            'second'=>'C1',
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $collectionDatetime = unpack($format,$subData);
        $this->saveData['collection_datetime'] = strtotime("20{$collectionDatetime['year']}-{$collectionDatetime['month']}-{$collectionDatetime['day']} {$collectionDatetime['hour']}:{$collectionDatetime['minute']}:{$collectionDatetime['second']}");
        $this->saveData['data_source'] = '北汽';
        $this->saveData['update_datetime'] = time();
        $this->saveData['car_vin'] = $car['car_vin'];
        var_dump(strlen($this->data));
        while(strlen($this->data) > 0){
            //解析小包头查看消息属于哪个类型
            $messageType = unpack('C1messageType',$this->data);
            $this->data = substr($this->data,1);//舍去已经解析的消息类型
            var_dump($messageType['messageType']);
            switch ($messageType['messageType']) {
                case 0x01:
                    //单体蓄电池电压数据
                    $this->batteryVoltageData();
                    break;
                case 0x02:
                    //动力蓄电池包温度数据
                    $this->batteryTemperatureData();
                    break;
                case 0x03:
                    //整车数据
                    $this->carData();
                    break;
                case 0x04:
                    //卫星定位
                    $this->positionData();
                    break;
                case 0x05:
                    //极值数据
                    $this->poleValueData();
                    break;
                case 0x06:
                    //报警数据
                    $this->alertData();
                    break;
                default:
                    //return false;
                    break;
            }
        }
        echo '<pre>';
        var_dump($this->saveData);die;
        //保存数据到数据库-存在记录则更新否则插入
        //--保存实时数据
        $hasRecord = $db->select('id')->from('cs_tcp_car_realtime_data')
            ->where('car_vin = :car_vin')
            ->bindValues(['car_vin'=>$car['car_vin']])
            ->row();
        if($hasRecord){
            $db->update('cs_tcp_car_realtime_data')->cols($this->saveData)->where('id='.$hasRecord['id'])->query();
        }else{
            $db->insert('cs_tcp_car_realtime_data')->cols($this->saveData)->query();
        }
        //--保存报警数据
        if($this->alertData){
            //删除本次辆的异常数据
            $db->delete('cs_tcp_car_exception')->where('car_vin = "'.$car['car_vin'].'"')->query();
            //插入新的异常数据
            $sql = 'insert into cs_tcp_car_exception(`id`,`car_vin`,`alert_type`,`ecu_module`,`content`,`level`,`collection_datetime`,`update_datetime`) values';
            foreach($this->alertData as $val){
                $sql .= "(null,\"{$car['car_vin']}\",\"{$val['alert_type']}\",'',\"{$val['content']}\",0,";
                $sql .= $this->saveData['collection_datetime'].',';
                $sql .= time().'),';
            }
            $sql = rtrim($sql,',');
            $db->query($sql);
        }
        //数据保存结束
        //记录车辆实时信息日志
        $dataPath = dirname(dirname(getcwd())).'/CacheData/'.rtrim($message['carVIN']);
        is_dir($dataPath) or mkdir($dataPath,0777,true);
        //--保存所有数据
        is_dir($dataPath.'/RealtimeData') or mkdir($dataPath.'/RealtimeData');
        $file = $dataPath.'/RealtimeData/'.date('YmdH').'.json';
        file_exists($file) or touch($file);
        file_put_contents($file,json_encode($this->saveData)."\n",FILE_APPEND);
        //--保存轨迹数据
        is_dir($dataPath.'/TrackData') or mkdir($dataPath.'/TrackData');
        $file = $dataPath.'/TrackData/'.date('YmdH').'.json';
        file_exists($file) or touch($file);
        $positionData = [];
        $positionDataKey = [
            'data_source','collection_datetime','update_datetime','position_effective',
            'latitude_type','longitude_type','latitude_value','longitude_value','speed','direction'
        ];
        foreach($positionDataKey as $val){
            $positionData[$val] = $this->saveData[$val];
        }
        file_put_contents($file,json_encode($positionData)."\n",FILE_APPEND);
        //--保存报警数据
        is_dir($dataPath.'/AlertData') or mkdir($dataPath.'/AlertData');
        $file = $dataPath.'/AlertData/'.date('YmdH').'.json';
        file_exists($file) or touch($file);
        if($this->alertData){
            foreach($this->alertData as $val){
                file_put_contents($file,json_encode($val)."\n",FILE_APPEND);
            }
        }
        //记录车辆实时信息日志结束
        //发送消息
        $send_data = [
            'commandSingle'=>$message['commandSingle'],
            'commandAnswer'=>0x01,//
            'carVIN'=>$message['carVIN'],
            'data'=>'',
        ];
        //var_dump($send_data);
        //echo '车辆实时数据保存成功！',"\n";
        Gateway::sendToClient($client_id,$send_data);
        return true;
    }

    /**
     * 单体蓄电池电压数据
     */
    public function batteryVoltageData()
    {
        $batteryVoltageData = [];
        $unpackData = unpack('n1totalSingleBattery/C1totalPackage',$this->data);
        $this->data = substr($this->data,3);
        $batteryVoltageData['totalSingleBattery'] = $unpackData['totalSingleBattery'];//单体蓄电池总数
        $batteryVoltageData['totalPackage'] = $unpackData['totalPackage'];//动力蓄电池包总数
        $batteryVoltageData['batteryPackage'] = [];//动力蓄电池的包信息
        for($i = 0;$i < $batteryVoltageData['totalPackage'];$i++){
            //分别解析各个电池包
            $package = [];
            $unpackData = unpack('C1packageSerialNumber/C1packageTotalBattery',$this->data);
            $this->data = substr($this->data,2);
            $package['serialNumber'] = $unpackData['packageSerialNumber'];//本包序号
            $package['totalBattery'] = $unpackData['packageTotalBattery'];//本包单个电池总数
            $package['battteryVoltage'] = [];//各个单电池的电压值
            for($j = 0;$j < $package['totalBattery'];$j++){
                $unpackData = unpack('n1voltage',$this->data);
                $this->data = substr($this->data,2);
                $package['battteryVoltage'][] = $unpackData['voltage'] / 1000;
            }
            $batteryVoltageData['batteryPackage'][] = $package;
        }
        //等待保存
        $this->saveData['battery_package_voltage'] = json_encode($batteryVoltageData);
    }

    /**
     * 动力蓄电池温度数据
     */
    public function batteryTemperatureData()
    {
        $batteryTemperatureData = [];
        $unpackData = unpack('n1totalProbe/C1totalPackage',$this->data);
        $this->data = substr($this->data,3);
        $batteryTemperatureData['totalProbe'] = $unpackData['totalProbe'];//动力蓄电池包温度探针总数
        $batteryTemperatureData['totalPackage'] = $unpackData['totalPackage'];//动力蓄电池包总数
        $batteryTemperatureData['temperatureList'] = [];//温度值列表
        for($i = 0;$i < $batteryTemperatureData['totalPackage'];$i ++){
            //解析每个包的温度
            $package = [];
            $unpackData = unpack('C1packageSerialNumber/C1totalProbe',$this->data);
            $this->data = substr($this->data,2);
            $package['serialNumber'] = $unpackData['packageSerialNumber'];//本包序号
            $package['totalProbe'] = $unpackData['totalProbe'];//本包探针总数
            $package['probeTemperature'] = [];//本包的所有探针的温度
            for($j = 0;$j < $package['totalProbe'];$j ++){
                $unpackData = unpack('C1probeTemperature',$this->data);
                $this->data = substr($this->data,1);
                $package['probeTemperature'][] = $unpackData['probeTemperature'];
            }
            $batteryTemperatureData['temperatureList'][] = $package;
        }
        //等待保存
        $this->saveData['battery_package_temperature'] = json_encode($batteryTemperatureData);
    }

    /**
     * 整车数据
     */
    public function carData()
    {
        //总长26
        $formatArr = [
            'speed'=>'n1',//速度
            'total_driving_mileage'=>'N1',//里程
            'gear'=>'C1',//档位
            'accelerator_pedal'=>'C1',//加速踏板行程
            'brake_pedal_distance'=>'C1',//制动踏板行程
            'charge_discharge_status'=>'C1',//充放电状态
            'moter_controller_temperature'=>'C1',//电机控制器温度
            'moter_speed'=>'n1',//电机转速
            'moter_temperature'=>'C1',//电机温度
            'moter_voltage'=>'n1',//电机电压
            'moter_current'=>'n1',//电机电流
            'air_condition_temperature'=>'C1',//空调设定温度
            //'yl'=>'a7',//预留7字节没解析（长度已经被计算在内）
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $carData = unpack($format,$this->data);
        $this->data = substr($this->data,26);
        //对数据进行处理
        $this->saveData['speed'] = $carData['speed'] / 10;
        $this->saveData['total_driving_mileage'] = $carData['total_driving_mileage'] / 10;
        //--档位处理开始
        $gearBinInfo = str_pad(decbin($carData['gear']),8,'0',STR_PAD_LEFT);
        $carData['gear'] = [];
        switch (substr($gearBinInfo,4)) {
            case '0000':
                $carData['gear'][] = '空档';
                break;
            case '1110':
                $carData['gear'][] = '倒档';
                break;
            case '1110':
                $carData['gear'][] = '自动档';
                break;
            default:
                $carData['gear'][] = bindec(substr($gearBinInfo,4)).'档';
                break;
        }
        $carData['gear'][] = substr($gearBinInfo,3,1) == 1 ? '制动有效' : '制动无效' ;
        $carData['gear'][] = substr($gearBinInfo,2,1) == 1 ? '驱动有效' : '驱动无效' ;
        $this->saveData['gear'] = json_encode($carData['gear']);
        //--档位处理结束
        $this->saveData['accelerator_pedal'] = $carData['accelerator_pedal'];
        $this->saveData['brake_pedal_distance'] = $carData['brake_pedal_distance'];
        $this->saveData['moter_voltage'] = ($carData['moter_voltage'] / 10) . 'V';
		echo '<hr />';
        var_dump($carData['charge_discharge_status']);
		echo '<hr />';
        //var_dump(0x01);
        switch ($carData['charge_discharge_status']) {
            case 0x01:
                //充电
                $this->saveData['car_current_status'] = 2;//充电
                break;
            default:
                //放电
                if($carData['speed'] > 0){
                    $this->saveData['car_current_status'] = 1;//行驶
                }else{
                    $this->saveData['car_current_status'] = 0;//停止
                }
                break;
        }
        $this->saveData['moter_controller_temperature'] = $carData['moter_controller_temperature'];
        $this->saveData['moter_speed'] = $carData['moter_speed'];
        $this->saveData['moter_temperature'] = $carData['moter_temperature'];
        $this->saveData['moter_voltage'] = $carData['moter_voltage'] / 10;
        $this->saveData['moter_current'] = $carData['moter_current'] / 10;
        $this->saveData['air_condition_temperature'] = $carData['air_condition_temperature'];
    }

    /**
     * 处理定位数据
     */
    public function positionData()
    {
        $formatArr = [
            'status'=>'C1',//定位状态
            'longitude_value'=>'N1',//经度值
            'latitude_value'=>'N1',//纬度值
            'speed'=>'n1',//车速
            'direction'=>'n1',//方向
            //'yl'=>'a4',//预留4字节
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $positionData = unpack($format,$this->data);
        $this->data = substr($this->data,17);
        //数据处理
        $statusBinInfo = str_pad(decbin($positionData['status']),8,'0',STR_PAD_LEFT);
        $this->saveData['position_effective'] = $statusBinInfo[7] ? 1 : 0;//无效
        $this->saveData['latitude_type'] = $statusBinInfo[6] ? 1 : 0;//1南纬
        $this->saveData['longitude_type'] = $statusBinInfo[5] ? 1 : 0;//1西经
        $this->saveData['longitude_value'] = $positionData['longitude_value'] / 1000000;
        $this->saveData['latitude_value'] = $positionData['latitude_value'] / 1000000;
        $this->saveData['speed'] = $positionData['speed'] / 10;
        $this->saveData['direction'] = $positionData['direction'];
    }

    /**
     * 极值数据
     */
    public function poleValueData()
    {
        //总长度28字节
        $formatArr = [
            'battery_package_hv_serial_num'=>'C1',//最高电压动力蓄电池所在电池包序号
            'battery_single_hv_serial_num'=>'C1',//最高电压单体蓄电池序号
            'battery_single_hv_value'=>'n1',//电池单体电压最高值
            'battery_package_lv_serial_num'=>'C1',//最低电压动力蓄电池所在电池包序号
            'battery_single_lv_serial_num'=>'C1',//最低电压单体蓄电池序号
            'battery_single_lv_value'=>'n1',//电池单体电压最低值
            'battery_package_ht_serial_num'=>'C1',//最高温度动力蓄电池所在电池包序号
            'battery_single_ht_serial_num'=>'C1',//最高温度探针序号
            'battery_single_ht_value'=>'C1',//最高温度值
            'battery_package_lt_serial_num'=>'C1',//最低温度动力蓄电池所在电池包序号
            'battery_single_lt_serial_num'=>'C1',//最低温度探针序号
            'battery_single_lt_value'=>'C1',//最低温度值
            'battery_package_total_voltage'=>'n1',//总电压
            'battery_package_current'=>'n1',//总电流
            'battery_package_soc'=>'C1',//剩余电量
            'battery_package_power'=>'n1',//剩余能量
            'battery_package_resistance_value'=>'n1',//绝缘电阻
            //'yl'=>'a5',//预留
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $unpackData = unpack($format,$this->data);
        $this->data = substr($this->data,28);
        //数据处理
        $this->saveData = array_merge($this->saveData,$unpackData);
        $this->saveData['battery_single_hv_value'] /= 1000;
        $this->saveData['battery_single_lv_value'] /= 1000;
        $this->saveData['battery_package_total_voltage'] /= 10;
        $this->saveData['battery_package_current'] /= 10;
        $this->saveData['battery_package_soc'] = sprintf("%.2f",$this->saveData['battery_package_soc']/2.5);
        $this->saveData['battery_package_power'] /= 10;
    }

    /**
     * 报警数据
     */
    public function alertData()
    {
        $unpackData = unpack('n1batteryAlertSingle',$this->data);
        $this->data = substr($this->data,2);
        $batteryAlertSingleBinInfo = str_pad(decbin($unpackData['batteryAlertSingle']),16,'0',STR_PAD_LEFT);
        if($batteryAlertSingleBinInfo[15] != 0){
            $this->alertData[] = ['alert_type'=>'动力蓄电池','content'=>'动力蓄电池温度差异报警'];
        }
        if($batteryAlertSingleBinInfo[14] != 0){
            $this->alertData[] = ['alert_type'=>'动力蓄电池','content'=>'动力蓄电池极注高温报警'];
        }
        if($batteryAlertSingleBinInfo[13] != 0){
            $this->alertData[] = ['alert_type'=>'动力蓄电池','content'=>'动力蓄电池包过压报警'];
        }
        if($batteryAlertSingleBinInfo[12] != 0){
            $this->alertData[] = ['alert_type'=>'动力蓄电池','content'=>'动力蓄电池包欠压报警'];
        }
        if($batteryAlertSingleBinInfo[11] != 0){
            $this->alertData[] = ['alert_type'=>'动力蓄电池','content'=>'SOC低报警'];
        }
        if($batteryAlertSingleBinInfo[10] != 0){
            $this->alertData[] = ['alert_type'=>'动力蓄电池','content'=>'单体蓄电池过压报警'];
        }
        if($batteryAlertSingleBinInfo[9] != 0){
            $this->alertData[] = ['alert_type'=>'动力蓄电池','content'=>'单体蓄电池欠压报警'];
        }
        if($batteryAlertSingleBinInfo[8] != 0){
            $this->alertData[] = ['alert_type'=>'动力蓄电池','content'=>'SOC太低报警'];
        }
        if($batteryAlertSingleBinInfo[7] != 0){
            $this->alertData[] = ['alert_type'=>'动力蓄电池','content'=>'SOC过高报警'];
        }
        if($batteryAlertSingleBinInfo[6] != 0){
            $this->alertData[] = ['alert_type'=>'动力蓄电池','content'=>'动力蓄电池包不匹配报警'];
        }
        if($batteryAlertSingleBinInfo[5] != 0){
            $this->alertData[] = ['alert_type'=>'动力蓄电池','content'=>'动力蓄电池一致性报警'];
        }
        if($batteryAlertSingleBinInfo[4] != 0){
            $this->alertData[] = ['alert_type'=>'动力蓄电池','content'=>'绝缘故障'];
        }
        //动力蓄电池其他报警总数
        $unpackData = unpack('C1batteryOtherAlertNum',$this->data);
        $this->data = substr($this->data,1);
        $batteryOtherAlertNum = $unpackData['batteryOtherAlertNum'];
        for($i = 0;$i < $batteryOtherAlertNum;$i++){
            $unpackData = unpack('C1alertCode', $this->data);
            $this->data = substr($this->data,1);
            $this->alertData[] = ['alert_type'=>'动力蓄电池','content'=>$unpackData['alertCode']];
        }
        //电机故障
        $unpackData = unpack('C1motorFaultNum',$this->data);
        $this->data = substr($this->data,1);
        $motorFaultNum = $unpackData['motorFaultNum'];
        for($i = 0;$i < $motorFaultNum;$i++){
            $unpackData = unpack('C1faultCode', $this->data);
            $this->data = substr($this->data,1);
            $this->alertData[] = ['alert_type'=>'动力蓄电池','content'=>$unpackData['faultCode']];
        }
        //其他故障
        $unpackData = unpack('C1otherFaultNum',$this->data);
        $this->data = substr($this->data,1);
        $otherFaultNum = $unpackData['otherFaultNum'];
        for($i = 0;$i < $otherFaultNum;$i++){
            $unpackData = unpack('C1faultCode', $this->data);
            $this->data = substr($this->data,1);
            $this->alertData[] = ['alert_type'=>'其他故障','content'=>$unpackData['faultCode']];
        }
    }

}
(new CarDataSubmit)->init('','');