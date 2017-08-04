<?php
/**
 * 模拟车辆注册
 */
include_once('./function.php');
include_once('./carLogin.php');
//打包车辆基本数据
$dataToPackage = [
    'regTimeYear'=>15,//注册时间年
    'regTimeMonth'=>10,//注册时间月
    'regTimeDay'=>29,//注册时间日
    'regTimeHour'=>10,//注册时间时
    'regTimeMinute'=>39,//注册时间分
    'regTimeSecond'=>40,//注册时间秒
    'regNumber'=>1,//注册流水号
    'plateNumber'=>'川abcdefg',//车牌号
    'terminalManufactor'=>'hftx',//车载终端生产厂家代码
    'terminalNumber'=>'a800',//车载终端批号
    'terminalSerialNumber'=>1,//车载终端流水号
    'batteryNum'=>4,//电池数量
];
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
    'batteryNum'=>'C1',//电池数量
];
$format = '';
foreach ($formatArr as $key => $value) {
    $format .= $value.$key.'/';
}
$format = rtrim($format,'/');
$data = packStruct($format,$dataToPackage);
//车辆数据打包结束

//打包电池数据
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
    'yl'=>'a5',//预留5字节没解析
];
$format = '';
foreach($formatArr as $key=>$val){
    $format .= $val.$key.'/';
}
$format = rtrim($format,'/');
for($i = 0;$i < 4;$i++){
    $dataToPackage = [
        'batteryPackageNumber'=>$i,//动力蓄电池包序号
        'batteryItemManufacturer'=>'hftx',//生产厂商代码
        'batteryItemType'=>0x05,//电池类型代码
        'batteryItemPower'=>9999,//额定能量
        'batteryItemVoltage'=>9999,//额定电压
        'batteryItemManufactureYear'=>15,
        'batteryItemManufactureMonth'=>11,
        'batteryItemManufactureDay'=>3,
        'batteryItemManufactureHour'=>17,
        'batteryItemManufactureMinute'=>55,
        'batteryItemManufactureSecond'=>0,
        'batteryItemSerialNumber'=>100+$i,//流水号
        'yl'=>'',//预留5字节
    ];
    $data .= packStruct($format,$dataToPackage);
}
//电池数据打包结束

//按指定格式打包所有数据准备发送
$dataToPackage = [
    'startMark'=>'##',//数据包起始符（2字节##）
    'commandSingle'=>0x01,//命令标识（1字节）
    'commandAnswer'=>0xFE,//命令应答标识（1字节）
    'carVIN'=>'vin1234567890',//车辆vin码或充电站编码+充电桩编码(17字节)
    'dataEncryptionWay'=>0x00,//数据加密方式（1字节8位）
    'dataLength'=>strlen($data),//数据长度（2字节16位0-65534）
    'data'=>$data,//数据内容
    'checkCode'=>'j',//校码码（1字节8位）
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
var_dump('发送车辆注册信息...');
socket_write($socket,$content,strlen($content));
$result = socket_read($socket,1024*1024);
sleep(1);
var_dump($result);
if($result){
    $result = _unpack($result);
    if($result['commandAnswer'] == 1){
        var_dump('车辆注册成功！');
    }else{
        var_dump('车辆注册失败！');
    }
}else{
    var_dump('车辆注册失败！');
}
//socket_close($socket);