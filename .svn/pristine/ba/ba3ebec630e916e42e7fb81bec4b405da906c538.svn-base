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
    'collectTimeMonth'=>10,
    'collectTimeDay'=>30,
    'collectTimeHour'=>16,
    'collectTimeMinute'=>8,
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
//点火与熄火数据包
$dataToPackage = [
    'messageType'=>0x01,
    'ignitionTimeYear'=>15,
    'ignitionTimeMonth'=>10,
    'ignitionTimeDay'=>30,
    'ignitionTimeHour'=>16,
    'ignitionTimeMinute'=>8,
    'ignitionTimesecond'=>0,
    'flameoutTimeYear'=>15,
    'flameoutTimeMonth'=>11,
    'flameoutTimeDay'=>30,
    'flameoutTimeHour'=>16,
    'flameoutTimeMinute'=>8,
    'flameoutTimesecond'=>0,
];
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
$data .= pack('a20',packStruct($format,$dataToPackage));
//点火与熄火数据包结束

//车辆行驶里程数据
$dataToPackage = [
    'messageType'=>0x02,
    'drivingMileage'=>1000,
];
$formatArr = [
    'messageType'=>'C1',
    'drivingMileage'=>'N1',
];
$format = '';
foreach($formatArr as $key=>$val){
    $format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data .= pack('a20',packStruct($format,$dataToPackage));
//车辆行驶里程数据结束

//定位数据开始
$dataToPackage = [
    'messageType'=>0x03,
    'status'=>0,//有效北纬东经
    'longitudeValue'=>31000000,
    'latitudeValue'=>131000000,
    'speed'=>16,
    'direction'=>8,
];
$formatArr = [
    'messageType'=>'C1',
    'status'=>'C1',
    'longitudeValue'=>'N1',//经度值
    'latitudeValue'=>'N1',//纬度值
    'speed'=>'n1',//速度
    'direction'=>'n1',//方向
];
$format = '';
foreach($formatArr as $key=>$val){
    $format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data .= pack('a20',packStruct($format,$dataToPackage));
//定位数据结束

//驱动电机数据
$dataToPackage = [
    'messageType'=>0x04,
    'moterControllerTemperature'=>100,
    'moterSpeed'=>30000,
    'moterTemperature'=>30,
    'moterGeneratrixCurrent'=>16,
];
$formatArr = [
    'messageType'=>'C1',
    'moterControllerTemperature'=>'C1',//电机控制器温度
    'moterSpeed'=>'s1',//电机转速 s无大小端
    'moterTemperature'=>'C1',//驱动电机温度
    'moterGeneratrixCurrent'=>'n1'//电机母线电流
];
$format = '';
foreach($formatArr as $key=>$val){
    $format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data .= pack('a20',packStruct($format,$dataToPackage));
//驱动电机数据开始

//车辆状态开始
$dataToPackage = [
    'messageType'=>0x05,
    'acceleratorPedal'=>50,//加速踏板行程
    'brakePedalStatus'=>1,//制动踏板状态
    'powerSystemReady'=>1,//动力系统就绪
    'emergencyElectricRequest'=>0,//紧急下电请求
    'carCurrentStatus'=>0,//车辆当前状态
];
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
$data .= pack('a20',packStruct($format,$dataToPackage));
//车辆状态结束

//动力蓄电池包高低温数据
$dataToPackage = [
    'messageType'=>0x06,
    'batteryNum'=>2,
];
for($i = 0;$i < 2;$i++){
    $dataToPackage['batterySerialNumber'.$i] = $i;//电池包序号
    $dataToPackage['batteryHeightTemperature'.$i] = 50 + $i;//最高温度
    $dataToPackage['batteryLowTemperature'.$i] = 10 + $i;//最低温度
}
$formatArr = [
    'messageType'=>'C1',
    'batteryNum'=>'C1',
];
for($i = 0;$i < 2;$i++){
    $formatArr['batterySerialNumber'.$i] = 'C1';//电池包序号
    $formatArr['batteryHeightTemperature'.$i] = 'C1';//最高温度
    $formatArr['batteryLowTemperature'.$i] = 'C1';//最低温度
}
$format = '';
foreach($formatArr as $key=>$val){
    $format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data .= pack('a20',packStruct($format,$dataToPackage));
//动力蓄电池包高低温数据结束

//电池包总体数据
$dataToPackage = [
    'messageType'=>0x07,
    'batteryPackageCurrent'=>20000,//高压电池电流
    'batteryPackageElectricity'=>250,//电池电量(SOC)
    'batteryPackagePower'=>8888,//剩余能量
    'batteryPackageTotalVoltage'=>50000,//电池总电压
    'batteryPackageSingleHT'=>150,//单体最高温度
    'batteryPackageSingleLT'=>10,//单体最低温度
    'batteryPackageSingleHV'=>15000,//单体最高电压
    'batteryPackageSingleLV'=>100,//单体最低电压        
    'batteryPackageResistanceValue'=>2000,//绝缘电阻值
    'batteryPackageEquilibriaActive'=>0,//电池均衡激活        
    'batteryPackageFuelConsumption'=>1000,//液体燃料消耗量
];
$formatArr = [
    'messageType'=>'C1',
    'batteryPackageCurrent'=>'n1',//高压电池电流
    'batteryPackageElectricity'=>'C1',//电池电量(SOC)
    'batteryPackagePower'=>'n1',//剩余能量
    'batteryPackageTotalVoltage'=>'n1',//电池总电压
    'batteryPackageSingleHT'=>'C1',//单体最高温度
    'batteryPackageSingleLT'=>'C1',//单体最低温度
    'batteryPackageSingleHV'=>'n1',//单体最高电压
    'batteryPackageSingleLV'=>'n1',//单体最低电压        
    'batteryPackageResistanceValue'=>'n1',//绝缘电阻值
    'batteryPackageEquilibriaActive'=>'C1',//电池均衡激活        
    'batteryPackageFuelConsumption'=>'n1',//液体燃料消耗量
];
$format = '';
foreach($formatArr as $key=>$val){
    $format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data .= pack('a20',packStruct($format,$dataToPackage));
//电池包总体数据结束

//按指定格式打包所有数据准备发送
$dataToPackage = [
    'startSingleMark'=>0x7e,//固定起始标识符（1字节8位）
    'startMark'=>'##',//数据包起始符（2字节##）
    'commandSingle'=>0x02,//命令标识（1字节）
    'commandAnswer'=>0x01,//命令应答标识（1字节）
    'serialNumber'=>65535,//命令流水号（2字节16位）
    'carVIN'=>'vin123456789',//车辆vin码或充电站编码+充电桩编码(17字节)
    'dataEncryptionWay'=>0x00,//数据加密方式（1字节8位）
    'dataLength'=>strlen($data),//数据长度（2字节16位0-65534）
    'data'=>$data,//数据内容
    'checkCode'=>'x',//校码码（1字节8位）
    //'endSingleMark'=>0x7e//固定结束标识符（1字节8位）
];
$formatArr = [
    'startSingleMark'=>'C1',//固定起始标识符（1字节8位）
    'startMark'=>'a2',//数据包起始符（2字节##）
    'commandSingle'=>'C1',//命令标识（1字节）
    'commandAnswer'=>'C1',//命令应答标识（1字节）
    'serialNumber'=>'n1',//命令流水号（2字节16位）
    'carVIN'=>'a17',//车辆vin码（17字节）
    'dataEncryptionWay'=>'C1',//数据加密方式（1字节8位）
    'dataLength'=>'n1',//数据长度（2字节16位0-65534）
    'data'=>'a'.strlen($data),//数据内容
    'checkCode'=>'a1',//校码码（1字节8位）
    //'endSingleMark'=>'C1'//固定结束标识符（1字节8位）
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