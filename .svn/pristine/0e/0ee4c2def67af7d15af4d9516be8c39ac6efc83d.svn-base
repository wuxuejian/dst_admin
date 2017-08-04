<?php
/**
 * 模拟车辆注册
 */
include_once('./function.php');
include_once('./carLogin.php');
//打包车辆基本数据
$dataToPackage = [
    'regTimeYear'=>17,//注册时间年
    'regTimeMonth'=>01,//注册时间月
    'regTimeDay'=>04,//注册时间日
    'regTimeHour'=>10,//注册时间时
    'regTimeMinute'=>39,//注册时间分
    'regTimeSecond'=>50,//注册时间秒
    'regNumber'=>1,//注册流水号
    'iccid'=>'01234567890123456789',//iccid
    'batteryRatio'=>3,//可充电储能子系统数(n)
    'batteryLength'=>4,//可充电储能子系统编码长度(m)
    'batteryData'=>'012345678900',//可充电储能子系统编码(n*m)
];
 $formatArr = [
    'regTimeYear'=>'C1',//注册时间年
    'regTimeMonth'=>'C1',//注册时间月
    'regTimeDay'=>'C1',//注册时间日
    'regTimeHour'=>'C1',//注册时间时
    'regTimeMinute'=>'C1',//注册时间分
    'regTimeSecond'=>'C1',//注册时间秒
    'regNumber'=>'n1',//注册流水号
    'iccid'=>'a20',//iccid号,20字节
    'batteryRatio'=>'C1',//可充电储能子系统数(n)
    'batteryLength'=>'C1',//可充电储能子系统编码长度(m)
    'batteryData'=>'a12',//可充电储能子系统编码(n*m)
];
$format = '';
foreach ($formatArr as $key => $value) {
    $format .= $value.$key.'/';
}
$format = rtrim($format,'/');
$data = packStruct($format,$dataToPackage);
//车辆数据打包结束


//按指定格式打包所有数据准备发送
$dataToPackage = [
    'startMark'=>'##',//数据包起始符（2字节##）
    'commandSingle'=>0x01,//命令标识（1字节）
    'commandAnswer'=>0xFE,//命令应答标识（1字节）
    'carVIN'=>'vin00001234567890',//车辆vin码或充电站编码+充电桩编码(17字节)
    'dataEncryptionWay'=>0x01,//数据加密方式（1字节8位）
    'dataLength'=>strlen($data),//数据长度（2字节16位0-65531）
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