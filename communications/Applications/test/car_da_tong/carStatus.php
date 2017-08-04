<?php
/**
 * 模拟车辆终端状态数据
 */
include_once('./function.php');
include_once('./carLogin.php');
$dataToPackage = [
    'year'=>substr(date('Y'),2),
    'month'=>date('m'),
    'day'=>date('d'),
    'hour'=>date('H'),
    'minute'=>date('i'),
    'second'=>date('s'),
    'status'=>8,
    'yl'=>'',
];
$formatArr = [
    'year'=>'C1',
    'month'=>'C1',
    'day'=>'C1',
    'hour'=>'C1',
    'minute'=>'C1',
    'second'=>'C1',
    'status'=>'C1',
    'yl'=>'a4',
];
$format = '';
foreach($formatArr as $key=>$val){
    $format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data = packStruct($format, $dataToPackage);

//按指定格式打包所有数据准备发送
$dataToPackage = [
    'startMark'=>'##',//数据包起始符（2字节##）
    'commandSingle'=>0x03,//命令标识（1字节）
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
var_dump('正在发送车载终端状态数据...');
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
        var_dump('车载终端状态数据提交成功！');
    }else{
        var_dump('车载终端状态数据提交失败！');
    }
}else{
    var_dump('车载终端状态数据提交失败！');
}
//socket_close($socket);