<?php
/**
 * 模拟车辆实时数据提交
 */
include_once('./function.php');
include_once('./carLogin.php');
include_once('./carReg.php');
//信息搜集时间
$dataToPackage = [
    'collectTimeYear'=>15,
    'collectTimeMonth'=>11,
    'collectTimeDay'=>6,
    'collectTimeHour'=>13,
    'collectTimeMinute'=>33,
    'collectTimesecond'=>0,
];
$formatArr = [
    'collectTimeYear'=>'C1',
    'collectTimeMonth'=>'C1',
    'collectTimeDay'=>'C1',
    'collectTimeHour'=>'C1',
    'collectTimeMinute'=>'C1',
    'collectTimesecond'=>'C1'
];
$format = '';
foreach($formatArr as $key=>$val){
    $format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data = packStruct($format,$dataToPackage);

//单体蓄电池电压数据开始
$dataToPackage = [
    'messageType'=>0x01,
    'totalSingleBattery'=>100,
    'totalPackage'=>10,
];
$formatArr = [
    'messageType'=>'C1',
    'totalSingleBattery'=>'n1',
    'totalPackage'=>'C1',
];
$format = '';
foreach($formatArr as $key=>$val){
    $format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data .= packStruct($format,$dataToPackage);
for($i = 0;$i < 10;$i++){
    $package = [];
    $data .= packStruct('C1packageSerialNumber/C1packageTotalBattery',[
        'packageSerialNumber'=>$i,
        'packageTotalBattery'=>10
    ]);
    for($j = 0;$j < 10;$j++){
        $data .= packStruct('n1voltage',['voltage'=>10000+$j+$i]);
    }
}
//单体蓄电池电压数据结束

//动力蓄电池温度数据
$dataToPackage = [
    'messageType'=>0x02,
    'totalProbe'=>100,
    'totalPackage'=>10,
];
$formatArr = [
    'messageType'=>'C1',
    'totalProbe'=>'n1',
    'totalPackage'=>'C1',
];
$format = '';
foreach($formatArr as $key=>$val){
    $format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data .= packStruct($format,$dataToPackage);
for($i = 0;$i < 10;$i ++){
    $data .= packStruct('C1packageSerialNumber/C1totalProbe',[
        'packageSerialNumber'=>$i,
        'totalProbe'=>'10',
    ]);
    for($j = 0;$j < 10;$j ++){
        $data .= packStruct('C1probeTemperature',['probeTemperature'=>10]);
    }
}
//动力蓄电池温度数据结束

//整车数据开始
$dataToPackage = [
    'messageType'=>0x03,
    'speed'=>1000,//速度
    'mileage'=>100,//里程
    'gear'=>1,//档位
    'accelerator_pedal'=>10,//加速踏板行程
    'brake_pedal_distance'=>20,//制动踏板行程
    'charge_discharge_status'=>1,//充放电状态
    'moter_controller_temperature'=>100,//电机控制器温度
    'moter_speed'=>1000,//电机转速
    'moter_temperature'=>100,//电机温度
    'moter_voltage'=>50,//电机电压
    'moter_current'=>50,//电机电流
    'air_condition_temperature'=>100,//空调设定温度
    'yl'=>'',//预留7字节没解析（长度已经被计算在内）
];
$formatArr = [
    'messageType'=>'C1',
    'speed'=>'n1',//速度
    'mileage'=>'N1',//里程
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
    'yl'=>'a7',//预留7字节没解析（长度已经被计算在内）
];
$format = '';
foreach($formatArr as $key=>$val){
    $format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data .= packStruct($format,$dataToPackage);
//整车数据结束

//定位数据开始
$dataToPackage = [
    'messageType'=>0x04,
    'status'=>0,//有效北纬东经
    'longitudeValue'=>10,
    'latitudeValue'=>30,
    'speed'=>16,
    'direction'=>8,
    'yl'=>'',//预留4字节
];
$formatArr = [
    'messageType'=>'C1',
    'status'=>'C1',
    'longitudeValue'=>'N1',//经度值
    'latitudeValue'=>'N1',//纬度值
    'speed'=>'n1',//速度
    'direction'=>'n1',//方向
    'yl'=>'a4',//预留4字节
];
$format = '';
foreach($formatArr as $key=>$val){
    $format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data .= packStruct($format,$dataToPackage);
//定位数据结束

//极值数据
$dataToPackage = [
    'messageType'=>0x05,
    'highest_v_battery_serial_num'=>1,//最高电压动力蓄电池所在电池包序号
    'highest_v_single_battery_serial_num'=>5,//最高电压单体蓄电池序号
    'highest_v_single_battery_value'=>100,//电池单体电压最高值
    'lowest_v_battery_serial_num'=>6,//最低电压动力蓄电池所在电池包序号
    'lowest_v_single_battery_serial_num'=>4,//最低电压单体蓄电池序号
    'lowest_v_single_battery_value'=>2,//电池单体电压最低值
    'highest_t_battery_serial_num'=>1,//最高温度动力蓄电池所在电池包序号
    'highest_t_probe_serial_num'=>5,//最高温度探针序号
    'highest_t_value'=>100,//最高温度值
    'lowest_t_battery_serial_num'=>2,//最低温度动力蓄电池所在电池包序号
    'lowest_t_probe_serial_num'=>9,//最低温度探针序号
    'lowest_t_value'=>10,//最低温度值
    'total_v'=>100,//总电压
    'total_c'=>50,//总电流
    'soc'=>50,//剩余电量
    'surplus_energy'=>600,//剩余能量
    'insulation_resistance'=>1000,//绝缘电阻
    'yl'=>'',//预留
];
$formatArr = [
    'messageType'=>'C1',
    'highest_v_battery_serial_num'=>'C1',//最高电压动力蓄电池所在电池包序号
    'highest_v_single_battery_serial_num'=>'C1',//最高电压单体蓄电池序号
    'highest_v_single_battery_value'=>'n1',//电池单体电压最高值
    'lowest_v_battery_serial_num'=>'C1',//最低电压动力蓄电池所在电池包序号
    'lowest_v_single_battery_serial_num'=>'C1',//最低电压单体蓄电池序号
    'lowest_v_single_battery_value'=>'n1',//电池单体电压最低值
    'highest_t_battery_serial_num'=>'C1',//最高温度动力蓄电池所在电池包序号
    'highest_t_probe_serial_num'=>'C1',//最高温度探针序号
    'highest_t_value'=>'C1',//最高温度值
    'lowest_t_battery_serial_num'=>'C1',//最低温度动力蓄电池所在电池包序号
    'lowest_t_probe_serial_num'=>'C1',//最低温度探针序号
    'lowest_t_value'=>'C1',//最低温度值
    'total_v'=>'n1',//总电压
    'total_c'=>'n1',//总电流
    'soc'=>'C1',//剩余电量
    'surplus_energy'=>'n1',//剩余能量
    'insulation_resistance'=>'n1',//绝缘电阻
    'yl'=>'a5',//预留
];
$format = '';
foreach($formatArr as $key=>$val){
    $format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data .= packStruct($format,$dataToPackage);
//极值数据结束

//报警数据
$data .= packStruct('C1messageType',['messageType'=>0x06]);
$data .= packStruct('n1batteryAlertSingle',['batteryAlertSingle'=>5]);
$data .= packStruct('C1batteryOtherAlertNum',['batteryOtherAlertNum'=>5]);
for($i = 0;$i < 5;$i++){
    $data .= packStruct('C1faultCode', ['faultCode'=>$i + 10]);
}
$data .= packStruct('C1motorFaultNum',['motorFaultNum'=>5]);
for($i = 0;$i < 5;$i++){
    $data .= packStruct('C1faultCode', ['faultCode'=>$i + 15]);
}
$data .= packStruct('C1otherFaultNum',['otherFaultNum'=>5]);
for($i = 0;$i < 5;$i++){
    $data .= packStruct('C1faultCode', ['faultCode'=>$i + 20]);
}
//报警数据结束

//按指定格式打包所有数据准备发送
$dataToPackage = [
    'startMark'=>'##',//数据包起始符（2字节##）
    'commandSingle'=>0x02,//命令标识（1字节）
    'commandAnswer'=>0xFE,//命令应答标识（1字节）
    'carVIN'=>'vin1234567890',//车辆vin码或充电站编码+充电桩编码(17字节)
    'dataEncryptionWay'=>0x00,//数据加密方式（1字节8位）
    'dataLength'=>strlen($data),//数据长度（2字节16位0-65534）
    'data'=>$data,//数据内容
    'checkCode'=>'x',//校码码（1字节8位）
];
$formatArr = [
    'startMark'=>'a2',//数据包起始符（2字节##）
    'commandSingle'=>'C1',//命令标识（1字节）
    'commandAnswer'=>'C1',//命令应答标识（1字节）
    'carVIN'=>'a17',//车辆vin码（17字节）
    'dataEncryptionWay'=>'C1',//数据加密方式（1字节8位）
    'dataLength'=>'n1',//数据长度（2字节16位0-65534）
    'data'=>'a'.strlen($data),//数据内容
    'checkCode'=>'a1',//校码码（1字节8位）
];
$format = '';
foreach($formatArr as $key=>$val){
    $format .= "{$val}{$key}/";
}
$format = rtrim($format,'/');
$content = packStruct($format, $dataToPackage);
//var_dump(bin2hex($content));
var_dump('正在发送车辆实时数据...');
//$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//if(!socket_connect($socket, '192.168.96.107', 7272)){
    //echo socket_strerror(socket_last_error()),'<br />';
    //die;
//}
socket_write($socket,$content,strlen($content));
$result = socket_read($socket,1024*1024);
sleep(1);
if($result){
    $result = _unpack($result);
    if($result['commandAnswer'] == 1){
        var_dump('车辆实时数据提交成功！');
    }else{
        var_dump('车辆实时数据提交失败！');
    }
}else{
    var_dump('车辆实时数据提交失败！');
}
//socket_close($socket);