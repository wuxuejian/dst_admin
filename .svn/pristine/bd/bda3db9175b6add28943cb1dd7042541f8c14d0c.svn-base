<?php
/**
 * 模拟认证
 */
include_once('./function.php');
//打包登陆数据
$dataToPackage = [
    'uid'=>'2e17ee81a89a82efa9c580ba4ed209ed',//账号
    'password'=>'123456',//密码
    'type'=>1,//类型
];
$formatArr = [
    'uid'=>'a40',//账号
    'password'=>'a20',//密码
    'type'=>'C1',//类型
];
$format = '';
foreach ($formatArr as $key => $value) {
    $format .= $value.$key.'/';
}
$format = rtrim($format,'/');
$data = packStruct($format,$dataToPackage);
//打包登陆数据结束

//按指定格式打包所有数据准备发送
$dataToPackage = [
    'startSingleMark'=>0x7e,//固定起始标识符（1字节8位）
    'startMark'=>'##',//数据包起始符（2字节##）
    'commandSingle'=>0x07,//命令标识（1字节）
    'commandAnswer'=>0xFE,//命令应答标识（1字节）
    'serialNumber'=>65535,//命令流水号（2字节16位）
    'carVIN'=>'vin123456789',//车辆vin码或充电站编码+充电桩编码(17字节)
    'dataEncryptionWay'=>0x00,//数据加密方式（1字节8位）
    'dataLength'=>strlen($data),//数据长度（2字节16位0-65534）
    'data'=>$data,//数据内容
    'checkCode'=>'j',//校码码（1字节8位）
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
var_dump('认证数据发送...');
$content = packStruct($format, $dataToPackage);
//var_dump(bin2hex($content));
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//if(!socket_connect($socket, 'cdkf.szc2c.com', 3000)){
if(!socket_connect($socket, '192.168.96.123', 3100)){
    echo socket_strerror(socket_last_error()),'<br />';
    die;
}
socket_write($socket,$content,strlen($content));
$result = socket_read($socket,1024*1024);
sleep(1);
if($result){
    $result = _unpack($result);
    if($result['commandAnswer'] == 1){
        var_dump('认证通过！');
    }else{
        var_dump('认证失败！');
    }
}else{
    var_dump('认证失败！');
}
//socket_close($socket);