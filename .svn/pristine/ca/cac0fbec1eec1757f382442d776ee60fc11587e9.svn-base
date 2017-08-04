<?php
/**
 * 北汽车辆注册
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
        //echo '收到车辆注册信息...',"\n";
        $this->db = DB::instance('db');
        //处理车辆基本信息
        //车辆信息定长53字节，文档明确标注
        $this->saveCarInfo(substr($message['data'],0,28),$message['carVIN']);
        //处理车辆电池信息//去掉车辆信息
        $this->saveBatteryInfo(substr($message['data'],28),$message['carVIN']);
        //创建车辆数据包存目录
        /*$dataPath = dirname(dirname(__FILE__)).'/Data/'.rtrim($message['carVIN']);
        is_dir($dataPath) or mkdir($dataPath);
        //实时数据目录
        is_dir($dataPath.'/RealtimeData') or mkdir($dataPath.'/RealtimeData');
        //车辆运行轨迹目录
        is_dir($dataPath.'/TrackData') or mkdir($dataPath.'/TrackData');
        //车辆报警数据目录
        is_dir($dataPath.'/AlertData') or mkdir($dataPath.'/TrackData');*/
        //发送成功消息
        $send_data = [
            'commandSingle'=>$message['commandSingle'],
            'commandAnswer'=>0x01,
            'carVIN'=>$message['carVIN'],
            'data'=>'',
        ];
        Gateway::sendToClient($client_id,$send_data);
        return true;
    }

    /**
     * 处理车辆基本信息
     * @param  bin    $data   车辆基本数据的二进制数据流
     * @param  string $carVin 车辆vin识别码
     * @return 插入车辆基本信息后返回车辆的insertid
     */
    protected function saveCarInfo($data,$carVin){
        $formatArr = [
            'regTimeYear'=>'C1',//注册时间年
            'regTimeMonth'=>'C1',//注册时间月
            'regTimeDay'=>'C1',//注册时间日
            'regTimeHour'=>'C1',//注册时间时
            'regTimeMinute'=>'C1',//注册时间分
            'regTimeSecond'=>'C1',//注册时间秒
            'regNumber'=>'n1',//注册流水号
            'plateNumber'=>'a8',//车牌号
            'terminalManufactor'=>'a4',//车载终端生产厂家代码
            'terminalNumber'=>'a6',//车载终端批号
            'terminalSerialNumber'=>'n1',//车载终端流水号
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
            'data_source'=>'东风',
            'car_vin'=>$carVin,
            'reg_time'=>strtotime("20{$carInfo['regTimeYear']}-{$carInfo['regTimeMonth']}-{$carInfo['regTimeDay']} {$carInfo['regTimeHour']}:{$carInfo['regTimeMinute']}:{$carInfo['regTimeSecond']}"),
            'reg_number'=>$carInfo['regNumber'],
            //'plate_number'=>trim($carInfo['plateNumber']),
            'terminal_manufactor'=>trim($carInfo['terminalManufactor']),
            'terminal_number'=>trim($carInfo['terminalNumber']),
            'terminal_serial_number'=>$carInfo['terminalSerialNumber'],
        ];
        if($hasRecord){
            //更新车辆数据
            $this->db->update('cs_tcp_car')->cols($saveData)->where('id = '.$hasRecord['id'])->query();
            return $hasRecord['id'];
        }else{
            //保存车辆信息
            return $this->db->insert('cs_tcp_car')->cols($saveData)->query();
        }
    }

    /**
     * 处理车辆电池信息
     * @param  bin $data  电池二进制数据信息
     * @param  int $carVin 车辆识别码
     * @return null
     */
    protected function saveBatteryInfo($data,$carVin){
        //删除该车辆电池数据防止数据提交重复
        $this->db->delete('cs_tcp_car_battery')
        ->where('`car_vin` = :car_vin')->bindValues(['car_vin'=>$carVin])
        ->query();
        //获取该车辆电池总数量
        $battery = unpack('C1batteryNum',$data);
        $data = substr($data,1);
        //按电池数据分别解析电池小包数据
        //单包数据解析格式
        $formatArr = [
            'batteryPackageNumber'=>'C1',//动力蓄电池包序号
            'batteryItemManufacturer'=>'a4',//生产厂商代码
            'batteryItemType'=>'C1',//电池类型代码
            'batteryItemPower'=>'n1',//额定能量
            'batteryItemVoltage'=>'n1',//额定电压
            'batteryItemManufactureYear'=>'C1',
            'batteryItemManufactureMonth'=>'C1',
            'batteryItemManufactureDay'=>'C1',
            'batteryItemManufactureHour'=>'C1',
            'batteryItemManufactureMinute'=>'C1',
            'batteryItemManufactureSecond'=>'C1',
            'batteryItemSerialNumber'=>'n1',//流水号
            //'yl'=>'a5'//预留5字节没解析
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $batteryInfo = [];
        for($i = 0;$i < $battery['batteryNum'];$i++){
            $batteryItem = unpack($format,substr($data,$i * 23,23));
            $batteryInfo[] = [
                'manufacturer'=>trim($batteryItem['batteryItemManufacturer']),
                'type'=>$batteryItem['batteryItemType'],
                'power'=>$batteryItem['batteryItemPower'] / 10,
                'voltage'=>$batteryItem['batteryItemVoltage'] / 10,
                'manufacture_datetime'=>strtotime("20{$batteryItem['batteryItemManufactureYear']}-{$batteryItem['batteryItemManufactureMonth']}-{$batteryItem['batteryItemManufactureDay']} {$batteryItem['batteryItemManufactureHour']}:{$batteryItem['batteryItemManufactureMinute']}:{$batteryItem['batteryItemManufactureSecond']}"),
                'serial_number'=>$batteryItem['batteryItemSerialNumber'],
            ];
        }
        //组装sql保存数据
        $sql = 'insert into `cs_tcp_car_battery`(`car_vin`,`manufacturer`,`type`,`power`,`voltage`,`manufacture_datetime`,`serial_number`) values ';
        foreach($batteryInfo as $val){
            $val['manufacture_datetime'] = $val['manufacture_datetime'] ? $val['manufacture_datetime'] : 0;
            $sql .= '(';
            $sql .=  "\"{$carVin}\",\"{$val['manufacturer']}\",{$val['type']},{$val['power']},{$val['voltage']},{$val['manufacture_datetime']},{$val['serial_number']}";
            $sql .= '),';
        }
        $sql = rtrim($sql,',');
        $this->db->query($sql);
    }
}