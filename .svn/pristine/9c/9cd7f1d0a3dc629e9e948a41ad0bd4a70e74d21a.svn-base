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
class CarDataSubmit
{
    protected $saveData = [];//将被保存到数据库中的数据
    public function init($message,$client_id)
    {
        //查询车辆id
        $db = Db::instance('db');
        $car = $db->select('id,car_vin')->from('cs_tcp_car')
                ->where('car_vin = :car_vin')->bindValues(['car_vin'=>$message['carVIN']])
                ->row();
        if(!$car){
            //车辆没有注册
            return false;
        }
        $childPackageNum = intval((strlen($message['data']) - 6) / 20);
        for($i = 0;$i < $childPackageNum;$i++){
            $dataItem = substr($message['data'],($i * 20) + 6,20);
            $type = unpack('C1messageType',$dataItem);
            switch ($type['messageType']) {
                case 0x01:
                    //点火与熄火时间
                    $this->ignitionAndFlameoutTime($dataItem);
                    break;
                case 0x02:
                    //累计行驶里程
                    $this->totalDrivingMileage($dataItem);
                    break;
                case 0x03:
                    //定位数据
                    $this->positionData($dataItem);
                    break;
                case 0x04:
                    //驱动电机数据
                    $this->motorData($dataItem);
                    break;
                case 0x05:
                    //车辆状态
                    $this->carData($dataItem);
                    break;
                case 0x06:
                    //动力蓄电池包高低温数据
                    $this->batteryTemperature($dataItem);
                    break;
                case 0x07:
                    //电池包总体数据
                    $this->batteryTotalData($dataItem);
                    break;
                case 0x09:
                    //平台交换协议数据
                    break;
                case 0x0A:
                    //预留
                    break;
                case 0x0B:
                    //用户自定义
                    break;
            }
        }
        //解析信息收集时间
        $formatArr = [
            'y'=>'C1',
            'm'=>'C1',
            'd'=>'C1',
            'h'=>'C1',
            'i'=>'C1',
            's'=>'C1',
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $unpackData = unpack($format,$message['data']);
        $this->saveData['collection_datetime'] = strtotime("20{$unpackData['y']}-{$unpackData['m']}-{$unpackData['d']} {$unpackData['h']}:{$unpackData['i']}:{$unpackData['s']}");
        //解析信息收集时间结束
        $this->saveData['data_source'] = '比亚迪';
        $this->saveData['car_vin'] = $car['car_vin'];
        $this->saveData['update_datetime'] = time();
        //保存数据到数据库
        //存在记录则更新否则插入
        $hasRecord = $db->select('id')->from('cs_tcp_car_realtime_data')
            ->where('car_vin = :car_vin')
            ->bindValues(['car_vin'=>$car['car_vin']])
            ->row();
        if($hasRecord){
            $db->update('cs_tcp_car_realtime_data')->cols($this->saveData)->where('id='.$hasRecord['id'])->query();
        }else{
            $db->insert('cs_tcp_car_realtime_data')->cols($this->saveData)->query();
        }
        //记录车辆实时信息日志
        $dataPath = dirname(dirname(getcwd())).'/CacheData/'.rtrim($message['carVIN']);
        is_dir($dataPath) or mkdir($dataPath,0777,true);
        //--保存所有数据
        is_dir($dataPath.'/RealtimeData') or mkdir($dataPath.'/RealtimeData');
        $fileName = $dataPath.'/RealtimeData/'.date('YmdH').'.json';
        file_exists($fileName) or touch($fileName);
        file_put_contents($fileName,json_encode($this->saveData)."\n",FILE_APPEND);
        //--保存轨迹数据
        $trackData = [
            'data_source'=>$this->saveData['data_source'],
            'collection_datetime'=>$this->saveData['collection_datetime'],
            'update_datetime'=>$this->saveData['update_datetime'],
            'position_effective'=>$this->saveData['position_effective'],
            'latitude_type'=>$this->saveData['latitude_type'],
            'longitude_type'=>$this->saveData['longitude_type'],
            'latitude_value'=>$this->saveData['latitude_value'],
            'longitude_value'=>$this->saveData['longitude_value'],
            'speed'=>$this->saveData['speed'],
            'direction'=>$this->saveData['direction'],
        ];
        is_dir($dataPath.'/TrackData') or mkdir($dataPath.'/TrackData');
        $fileName = $dataPath.'/TrackData/'.date('YmdH').'.json';
        file_exists($fileName) or touch($fileName);
        file_put_contents($fileName,json_encode($trackData)."\n",FILE_APPEND);
        //记录车辆实时信息日志结束
        //发送消息
        $send_data = [
            'commandSingle'=>$message['commandSingle'],
            'commandAnswer'=>0x01,//
            'serialNumber'=>intval($_SESSION['serialNumber']),
            'carVIN'=>$message['carVIN'],
            'data'=>'',
        ];
        Gateway::sendToClient($client_id,$send_data);
        return false;
    }

    /**
     * 点火与熄火时间
     * @param bin $data
     */
    public function ignitionAndFlameoutTime($data)
    {
        $formatArr = [
            'messageType'=>'C1',
            'ignitionTimeYear'=>'C1',
            'ignitionTimeMonth'=>'C1',
            'ignitionTimeDay'=>'C1',
            'ignitionTimeHour'=>'C1',
            'ignitionTimeMinute'=>'C1',
            'ignitionTimesecond'=>'C1',
            'flameoutTimeYear'=>'C1',
            'flameoutTimeMonth'=>'C1',
            'flameoutTimeDay'=>'C1',
            'flameoutTimeHour'=>'C1',
            'flameoutTimeMinute'=>'C1',
            'flameoutTimesecond'=>'C1',
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $unpackTime = unpack($format,$data);
        $this->saveData['ignition_datetime'] = strtotime("20{$unpackTime['ignitionTimeYear']}-{$unpackTime['ignitionTimeMonth']}-{$unpackTime['ignitionTimeDay']} {$unpackTime['ignitionTimeHour']}:{$unpackTime['ignitionTimeMinute']}:{$unpackTime['ignitionTimesecond']}");//点火时间
        $this->saveData['flameout_datetime'] = strtotime("20{$unpackTime['flameoutTimeYear']}-{$unpackTime['flameoutTimeMonth']}-{$unpackTime['flameoutTimeDay']} {$unpackTime['flameoutTimeHour']}:{$unpackTime['flameoutTimeMinute']}:{$unpackTime['flameoutTimesecond']}");//熄火时间
    }

    /**
     * 累计行驶里程
     * @param bin $data
     */
    public function totalDrivingMileage($data)
    {
        $mileage = unpack('C1messageType/N1drivingMileage',$data);
        $this->saveData['total_driving_mileage'] = $mileage['drivingMileage'] / 10;
    }

    /**
     * 定位数据
     * @param bin $data
     */
    public function positionData($data)
    {
        $formatArr = [
            'messageType'=>'C1',
            'status'=>'C1',
            'longitudeValue'=>'N1',//经度值
            'latitudeValue'=>'N1',//纬度值
            'speed'=>'n1',//速度
            'direction'=>'n1',//方向
            //预留四字节没解析
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $positionInfo = unpack($format,$data);
        $binOfStatus = str_pad(decbin($positionInfo['status']),8,'0',STR_PAD_LEFT);
        $this->saveData['position_effective'] = $binOfStatus[7];//定位是否有效0有效1无效
        $this->saveData['latitude_type'] = $binOfStatus[6];//纬度类型0北纬1南纬
        $this->saveData['longitude_type'] = $binOfStatus[5];//经度类型0东经1西经
        $this->saveData['latitude_value'] = $positionInfo['latitudeValue'] / 1000000;//纬度值
        $this->saveData['longitude_value'] = $positionInfo['longitudeValue'] / 1000000;//经度值
        $this->saveData['speed'] = $positionInfo['speed'] / 10;//速度
        $this->saveData['direction'] = $positionInfo['direction'];//方向
    }

    /**
     * 驱动电机数据
     * @param bin $data
     */
    public function motorData($data)
    {
        $formatArr = [
            'messageType'=>'C1',
            'moter_controller_temperature'=>'C1',//电机控制器温度
            'moter_speed'=>'s1',//电机转速 s无大小端
            'moter_temperature'=>'C1',//驱动电机温度
            'moter_generatrix_current'=>'n1'//电机母线电流
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $motorInfo = unpack($format,$data);
        $this->saveData['moter_controller_temperature'] = intval($motorInfo['moter_controller_temperature']);
        $this->saveData['moter_speed'] = intval($motorInfo['moter_speed']);
        $this->saveData['moter_temperature'] = intval($motorInfo['moter_temperature']);
        $this->saveData['moter_generatrix_current'] = $motorInfo['moter_generatrix_current'] / 10;
    }

    /**
     * 车辆状态
     * @param bin $data
     */
    public function carData($data)
    {
        $formatArr = [
            'messageType'=>'C1',
            'acceleratorPedal'=>'C1',//加速踏板行程
            'brakePedalStatus'=>'C1',//制动踏板状态
            'powerSystemReady'=>'C1',//动力系统就绪
            'emergencyElectricRequest'=>'C1',//紧急下电请求
            'carCurrentStatus'=>'C1',//车辆当前状态
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $carInfo = unpack($format,$data);
        $this->saveData['accelerator_pedal'] = intval($carInfo['acceleratorPedal']);
        $this->saveData['brake_pedal_status'] = intval($carInfo['brakePedalStatus']);
        $this->saveData['power_system_ready'] = intval($carInfo['powerSystemReady']);
        $this->saveData['emergency_electric_request'] = intval($carInfo['emergencyElectricRequest']);
        $this->saveData['car_current_status'] = intval($carInfo['carCurrentStatus']);
    }

    /**
     * 动力蓄电池包高低温数据
     * @param bin $data
     */
    public function batteryTemperature($data)
    {
        $unpackData = unpack('C1messageType/C1batteryNum',$data);
        $data = substr($data,2);
        $batteryNum = $unpackData['batteryNum'];
        $batteryTemperatureInfo = [];
        for($i = 0;$i < $batteryNum;$i++){
            $formatArr = [];
            $formatArr['serial_number'] = 'C1';//电池包序号
            $formatArr['height_temperature'] = 'C1';//最高温度
            $formatArr['low_temperature'] = 'C1';//最低温度
            $format = '';
            foreach($formatArr as $key=>$val){
                $format .= $val.$key.'/';
            }
            $format = rtrim($format,'/');
            $unpackData = unpack($format,$data);
            $data = substr($data,3);
            $batteryTemperatureInfo[] = [
                'serial_number'=>$unpackData['serial_number'],
                'height_temperature'=>$unpackData['height_temperature'],
                'low_temperature'=>$unpackData['low_temperature'],
            ];
        }
        //将电池温度信息保存为json字符串
        $this->saveData['battery_package_temperature'] = json_encode($batteryTemperatureInfo);
    }

    /**
     * 电池包总体数据
     * @param bin $data
     */
    public function batteryTotalData($data)
    {
        $formatArr = [
            'messageType'=>'C1',
            'battery_package_current'=>'n1',//高压电池电流
            'battery_package_soc'=>'C1',//电池电量(SOC)
            'battery_package_power'=>'n1',//剩余能量
            'battery_package_total_voltage'=>'n1',//电池总电压
            'battery_single_ht_value'=>'C1',//单体最高温度
            'battery_single_lt_value'=>'C1',//单体最低温度
            'battery_single_hv_value'=>'n1',//单体最高电压
            'battery_single_lv_value'=>'n1',//单体最低电压        
            'battery_package_resistance_value'=>'n1',//绝缘电阻值
            'battery_package_equilibria_active'=>'C1',//电池均衡激活        
            'battery_package_fuel_consumption'=>'n1',//液体燃料消耗量
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $batteryPackageInfo = unpack($format,$data);
        $this->saveData['battery_package_current'] = $batteryPackageInfo['battery_package_current'] / 10;
        $this->saveData['battery_package_soc'] = $batteryPackageInfo['battery_package_soc'] / 2.5;//转换为白分比
        $this->saveData['battery_package_power'] = $batteryPackageInfo['battery_package_power'] / 10;
        $this->saveData['battery_package_total_voltage'] = $batteryPackageInfo['battery_package_total_voltage'] / 10;
        $this->saveData['battery_single_ht_value'] = $batteryPackageInfo['battery_single_ht_value'];
        $this->saveData['battery_single_lt_value'] = $batteryPackageInfo['battery_single_lt_value'];
        $this->saveData['battery_single_hv_value'] = $batteryPackageInfo['battery_single_hv_value'] / 1000;
        $this->saveData['battery_single_lv_value'] = $batteryPackageInfo['battery_single_lv_value'] / 1000;
        $this->saveData['battery_package_resistance_value'] = $batteryPackageInfo['battery_package_resistance_value'];
        $this->saveData['battery_package_equilibria_active'] = $batteryPackageInfo['battery_package_equilibria_active'];
        $this->saveData['battery_package_fuel_consumption'] = $batteryPackageInfo['battery_package_fuel_consumption'];
    }

    /**
     * 平台交换协议数据
     */

    /**
     * 预留
     */

    /**
     * 用户自定义
     */
}