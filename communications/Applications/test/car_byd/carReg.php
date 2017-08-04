<?php
/**
 * 模拟车辆注册
 */
include_once('./function.php');
include_once('./carLogin.php');
//打包车辆基本数据
$dataToPackage = [
    'childCommand'=>0x01,//命令类型
    'regTimeYear'=>15,//注册时间年
    'regTimeMonth'=>10,//注册时间月
    'regTimeDay'=>29,//注册时间日
    'regTimeHour'=>10,//注册时间时
    'regTimeMinute'=>39,//注册时间分
    'regTimeSecond'=>40,//注册时间秒
    'carType'=>1,//车辆类型
    'carModel'=>'e50',//车辆型号
    'carStorageType'=>1,//储能装置种类
    'motorType'=>4,//电机种类
    'motorPower'=>100,//驱动电机额定功率
    'motorSpeed'=>1000,//驱动电机额定转速
    'motorTorque'=>100,//驱动电机额定转矩
    'motorNum'=>2,//驱动电机安装数量
    'motorPosition'=>6,//驱动电机布置型式/位置
    'motorCoolingType'=>2,//驱动电机冷却方式
    'carMileage'=>300,//电动汽车续驶里程
    'carMaximumSpeed'=>130,//电动汽车最高车速
    'carReserve'=>' ',//车辆预留信息11字节
];
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
    'carReserve'=>'a11',//车辆预留信息11字节
];
$format = '';
foreach ($formatArr as $key => $value) {
    $format .= $value.$key.'/';
}
$format = rtrim($format,'/');
$data = packStruct($format,$dataToPackage);
//车辆数据打包结束

//打包电池数据
$batteryNum = 4;
$data .= packStruct('C1childCommand/C1batteryNum',[
	'childCommand'=>0x02,
	'batteryNum'=>$batteryNum,
]);
for($i = 0;$i < $batteryNum;$i++){
	//格式定义
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
	$formatArr['batteryItemReserve'] = 'a5';//预留5字节无需解析
	$format = '';
	foreach($formatArr as $key=>$val){
		$format .= $val.$key.'/';
	}
	$format = rtrim($format,'/');
	//数据定义
	$dataToPackage = [];
	$dataToPackage['batteryPackageNumber'] = $i;//动力蓄电池包序号
	$dataToPackage['batteryItemManufacturer'] = 'byd';//生产厂商代码
	$dataToPackage['batteryItemType'] = 0x01;//电池类型代码
	$dataToPackage['batteryItemPower'] = 1000;//额定能量
	$dataToPackage['batteryItemVoltage'] = 8000;//额定电压
	//电池生产日期代码
	$dataToPackage['y'] = 15;//年
	$dataToPackage['m'] = 11;//月
	$dataToPackage['d'] = 23;//日
	$dataToPackage['h'] = 16;//时
	$dataToPackage['i'] = 11;//分
	$dataToPackage['s'] = 0;//秒
	//电池生产日期代码结束
	$dataToPackage['batteryItemSerialNumber'] = $i;//流水号
	$dataToPackage['batteryItemReserve'] = '';//预留5字节
	$data .= packStruct($format,$dataToPackage);
}
//电池数据打包结束

//按指定格式打包所有数据准备发送
$dataToPackage = [
    'startSingleMark'=>0x7e,//固定起始标识符（1字节8位）
    'startMark'=>'##',//数据包起始符（2字节##）
    'commandSingle'=>0x01,//命令标识（1字节）
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
$content = packStruct($format, $dataToPackage);
//var_dump(bin2hex($content));
//$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//if(!socket_connect($socket, '192.168.96.107', 7272)){
    //echo socket_strerror(socket_last_error()),'<br />';
    //die;
//}
var_dump('发送车辆注册信息...');
socket_write($socket,$content,strlen($content));
$result = socket_read($socket,1024*1024);
sleep(1);
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