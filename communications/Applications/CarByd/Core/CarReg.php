<?php
/**
 * 车辆注册
 * 消息id   0x01
 * 应答消息 是
 * @author wangmin
 * @time 2015/10/29 17:07
 */
use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Store;
use \GatewayWorker\Lib\Db;
class CarReg
{
    public $db;
    public function init($message,$client_id)
    {
        //var_dump($message);
        $this->db = DB::instance('db');
        //处理车辆基本信息
        //车辆信息定长53字节，文档明确标注
        //try{
            $carId = $this->saveCarInfo(substr($message['data'],0,53),$message['carVIN']);
            //处理车辆电池信息//去掉车辆信息
            $this->saveBatteryInfo(substr($message['data'],53),$message['carVIN']);
            //发送成功消息
            $send_data = [
                'commandSingle'=>$message['commandSingle'],
                'commandAnswer'=>0x01,
                'serialNumber'=>intval($_SESSION['serialNumber']),
                'carVIN'=>$message['carVIN'],
                'data'=>'',
            ];
            Gateway::sendToClient($client_id,$send_data);
            return true;
        /*}catch(\Exception $e){
            //发送失败消息
            $send_data = [
                'commandSingle'=>$message['commandSingle'],
                'commandAnswer'=>0x02,//
                'serialNumber'=>intval($_SESSION['serialNumber']),
                'carVIN'=>$message['carVIN'],
                'data'=>'',
            ];
            Gateway::sendToClient($client_id,$send_data);
            return false;
        }*/

    }

    /**
     * 处理车辆基本信息
     * @param  bin    $data   车辆基本数据的二进制数据流
     * @param  string $carVin 车辆vin识别码
     * @return 插入车辆基本信息后返回车辆的insertid
     */
    protected function saveCarInfo($data,$carVin){
        $formatArr = [
            'childCommand'=>'C1',//命令类型
            'regTimeYear'=>'C1',//注册时间年
            'regTimeMonth'=>'C1',//注册时间月
            'regTimeDay'=>'C1',//注册时间日
            'regTimeHour'=>'C1',//注册时间时
            'regTimeMinute'=>'C1',//注册时间分
            'regTimeSecond'=>'C1',//注册时间秒
            'carType'=>'C1',//车辆类型
            'carModel'=>'a20',//车辆型号
            'carStorageType'=>'C1',//储能装置种类
            'motorType'=>'C1',//电机种类
            'motorPower'=>'n1',//驱动电机额定功率
            'motorSpeed'=>'n1',//驱动电机额定转速
            'motorTorque'=>'n1',//驱动电机额定转矩
            'motorNum'=>'C1',//驱动电机安装数量
            'motorPosition'=>'C1',//驱动电机布置型式/位置
            'motorCoolingType'=>'C1',//驱动电机冷却方式
            'carMileage'=>'n1',//电动汽车续驶里程
            'carMaximumSpeed'=>'C1',//电动汽车最高车速
            //'carReserve'=>'a11',//车辆预留信息11字节
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $carInfo = unpack($format,$data);
        //检测该车辆是否已经存在
        $hasRecord = $this->db->select('id')->from('cs_tcp_car')
                    ->where('car_vin = :car_vin')->bindValues(['car_vin'=>$carVin])
                    ->row();
        $saveData = [
            'data_source'=>'比亚迪',
            'car_vin'=>$carVin,
            'reg_time'=>strtotime("20{$carInfo['regTimeYear']}-{$carInfo['regTimeMonth']}-{$carInfo['regTimeDay']} {$carInfo['regTimeHour']}:{$carInfo['regTimeMinute']}:{$carInfo['regTimeSecond']}"),
            'car_type'=>$carInfo['carType'],
            'car_model'=>$carInfo['carModel'],
            'storage_type'=>$carInfo['carStorageType'],
            'motor_type'=>$carInfo['motorType'],
            'motor_power'=>$carInfo['motorPower'],
            'motor_speed'=>$carInfo['motorSpeed'],
            'motor_torque'=>$carInfo['motorTorque'],
            'motor_num'=>$carInfo['motorNum'],
            'motor_position'=>$carInfo['motorPosition'],
            'motor_cooling_type'=>$carInfo['motorCoolingType'],
            'car_mileage'=>$carInfo['carMileage'],
            'car_maximum_speed'=>$carInfo['carMaximumSpeed'],
        ];
        if($hasRecord){
            //更新车辆数据
            $this->db->update('cs_tcp_car')->cols($saveData)->where('id='.$hasRecord['id'])->query();
            return $hasRecord['id'];
        }else{
            //保存车辆信息
            return $this->db->insert('cs_tcp_car')->cols($saveData)->query();
        }
    }

    /**
     * 处理车辆电池信息
     * @param  bin $data   电池二进制数据信息
     * @param  int $carVIN 车辆vin
     * @return null
     */
    protected function saveBatteryInfo($data,$carVIN){
        //删除该车辆电池数据防止数据提交重复
        $carVIN = addslashes(trim($carVIN));
        $this->db->delete('cs_tcp_car_battery')->where('car_vin = "'.$carVIN.'"')->query();
        //获取该车辆电池总数量
        $unpackData = unpack('C1childCommand/C1batteryNum',$data);
        $batteryNum = $unpackData['batteryNum'];
        $data = substr($data,2);
        $batteryInfo = [];
        for($i = 0;$i < $batteryNum;$i++){
            $formatArr = [];
            $formatArr['batteryPackageNumber'] = 'C1';//动力蓄电池包序号
            $formatArr['batteryItemManufacturer'] = 'a4';//生产厂商代码
            $formatArr['batteryItemType'] = 'C1';//电池类型代码
            $formatArr['batteryItemPower'] = 'n1';//额定能量
            $formatArr['batteryItemVoltage'] = 'n1';//额定电压
            //电池生产日期代码
            $formatArr['y'] = 'C1';//年
            $formatArr['m'] = 'C1';//月
            $formatArr['d'] = 'C1';//日
            $formatArr['h'] = 'C1';//时
            $formatArr['i'] = 'C1';//分
            $formatArr['s'] = 'C1';//秒
            //电池生产日期代码结束
            $formatArr['batteryItemSerialNumber'] = 'n1';//流水号
            //$formatArr['batteryItemReserve'] = 'a5';//预留5字节无需解析
            $format = '';
            foreach($formatArr as $key=>$val){
                $format .= $val.$key.'/';
            }
            $format = rtrim($format,'/');
            $unpackData = unpack($format,$data);
            $data = substr($data,23);
            $batteryInfo[] = [
                'manufacturer'=>addslashes($unpackData['batteryItemManufacturer']),
                'type'=>intval($unpackData['batteryItemType']),
                'power'=>$unpackData['batteryItemPower'] / 10,
                'voltage'=>$unpackData['batteryItemVoltage'] / 10,
                'manufacture_datetime'=>strtotime("20{$unpackData['y']}-{$unpackData['m']}-{$unpackData['d']} {$unpackData['h']}:{$unpackData['i']}:{$unpackData['s']}"),
                'serial_number'=>intval($unpackData['batteryItemSerialNumber']),
            ];
        }
        //
        
        //组装sql保存数据
        $sql = 'insert into `cs_tcp_car_battery`(`car_vin`,`manufacturer`,`type`,`power`,`voltage`,`manufacture_datetime`,`serial_number`) values ';
        foreach($batteryInfo as $val){
            $sql .= '(';
            $sql .= "\"{$carVIN}\",\"{$val['manufacturer']}\",{$val['type']},{$val['power']},{$val['voltage']},{$val['manufacture_datetime']},{$val['serial_number']}";
            $sql .= '),';
        }
        $sql = rtrim($sql,',');
        $this->db->query($sql);
    }
}