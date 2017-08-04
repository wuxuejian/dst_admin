<?php
/**
 * 模拟车辆异常数据提交
 */
include_once('./function.php');
include_once('./carLogin.php');
//信息搜集时间
$dataToPackage = [
    'collectTimeYear'=>15,
    'collectTimeMonth'=>11,
    'collectTimeDay'=>3,
    'collectTimeHour'=>14,
    'collectTimeMinute'=>57,
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
//信息收集时间打包结束

$dataToPackage = [
    'type1'=>0x01,//电机控制器（MCU）异常报警
    'total1'=>2,//两个异常
    'number11'=>0x01,//电机控制器温度
    'value11'=>0,
    'level11'=>0x05,//五级
    'number12'=>0x02,//驱动电机温度
    'value12'=>1,
    'level12'=>0x03,//三级
    'type2'=>0x03,//电池管理系统（BMS）异常报警
    'total2'=>3,//三个异常
    'number21'=>0x07,//电池单体最高温度
    'value21'=>1,
    'level21'=>0x05,//五级
    'number22'=>0x08,//电池单体最低温度
    'value22'=>1,
    'level22'=>0x03,//三级
    'number23'=>0x09,//电池单体最高电压
    'value23'=>1,
    'level23'=>0x01,//一级
];
$formatArr = [
    'type1'=>'C1',//电机控制器（MCU）异常报警
    'total1'=>'C1',//两个异常
    'number11'=>'C1',//电机控制器温度
    'value11'=>'C1',
    'level11'=>'C1',//五级
    'number12'=>'C1',//驱动电机温度
    'value12'=>'C1',
    'level12'=>'C1',//三级
    'type2'=>'C1',//电池管理系统（BMS）异常报警
    'total2'=>'C1',//三个异常
    'number21'=>'C1',//电池单体最高温度
    'value21'=>'C1',
    'level21'=>'C1',//五级
    'number22'=>'C1',//电池单体最低温度
    'value22'=>'C1',
    'level22'=>'C1',//三级
    'number23'=>'C1',//电池单体最高电压
    'value23'=>'C1',
    'level23'=>'C1',//一级
];
$format = '';
foreach($formatArr as $key=>$val){
    $format .= "{$val}{$key}/";
}
$format = rtrim($format,'/');
$data .= packStruct($format, $dataToPackage);

//按指定格式打包所有数据准备发送
$dataToPackage = [
    'startSingleMark'=>0x7e,//固定起始标识符（1字节8位）
    'startMark'=>'##',//数据包起始符（2字节##）
    'commandSingle'=>0x03,//命令标识（1字节）
    'commandAnswer'=>0xFE,//命令应答标识（1字节）
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
var_dump('正在发送车辆异常数据...');
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
        var_dump('车辆异常数据提交成功！');
    }else{
        var_dump('车辆异常数据提交失败！');
    }
}else{
    var_dump('车辆车辆实时数据提交失败！');
}
//socket_close($socket);