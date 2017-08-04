<?php
/**
 * 模拟车辆实时数据提交
 */
include_once('./function.php');
include_once('./carLogin.php');
// include_once('./carReg.php');
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

//整车数据开始
$dataToPackage = [
	'messageType'=>0x01,
	'car_status'=>1,//车辆状态
	'charge_status'=>2,//充电状态
	'run_mode'=>1,//运行模式
	'speed'=>1000,//车速
	'mileage'=>100,//累计里程
	'battery_package_total_voltage'=>50,//总电压
	'battery_package_current'=>50,//总电流
	'battery_package_soc'=>50,//SOC
	'dc_dc'=>1,//DC-DC状态
	'gear'=>1,//档位
	'battery_package_resistance_value'=>1000,//绝缘电阻
	'y1'=>'',//预留2字节没解析（长度已经被计算在内）
];
$formatArr = [
	'messageType'=>'C1',
	'car_status'=>'C1',//车辆状态
	'charge_status'=>'C1',//充电状态
	'run_mode'=>'C1',//运行模式
	'speed'=>'n1',//车速
	'mileage'=>'N1',//累计里程
	'battery_package_total_voltage'=>'n1',//总电压
	'battery_package_current'=>'n1',//总电流
	'battery_package_soc'=>'C1',//SOC
	'dc_dc'=>'C1',//DC-DC状态
	'gear'=>'C1',//档位
	'battery_package_resistance_value'=>'n1',//绝缘电阻
	'y1'=>'n1',//预留2字节没解析（长度已经被计算在内）
];
$format = '';
foreach($formatArr as $key=>$val){
	$format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data .= packStruct($format,$dataToPackage);
//整车数据结束

//电机数据开始
$dataToPackage = [
	'messageType'=>0x02,
	'moter_num'=>2,//电机个数
];
$formatArr = [
	'messageType'=>'C1',
	'moter_num'=>'C1',//电机个数
];
$format = '';
foreach($formatArr as $key=>$val){
	$format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data .= packStruct($format,$dataToPackage);

$formatArr = [
	'moter_serial_num'=>'C1',//驱动电机序号
	'moter_status'=>'C1',//电机状态
	'moter_controller_temperature'=>'C1',//电机控制器温度
	'moter_speed'=>'n1',//电机转速
	'moter_torque'=>'n1',//电机转矩
	'moter_temperature'=>'C1',//电机温度
	'moter_voltage'=>'n1',//电机控制器输入电压
	'moter_current'=>'n1',//电机控制器直流母线电流
];
$format = '';
foreach($formatArr as $key=>$val){
	$format .= $val.$key.'/';
}
$format = rtrim($format,'/');
for($i = 0;$i < 2;$i++){	//循环打包单个电机数据
	$dataToPackage = [
		'moter_serial_num'=>$i,//驱动电机序号
		'moter_status'=>0x01,//电机状态
		'moter_controller_temperature'=>100,//电机控制器温度
		'moter_speed'=>1000,//电机转速
		'moter_torque'=>1000,//电机转矩
		'moter_temperature'=>100,//电机温度
		'moter_voltage'=>50,//电机控制器输入电压
		'moter_current'=>50,//电机控制器直流母线电流
	];
	$data .= packStruct($format,$dataToPackage);
}
//电机数据结束

//定位数据开始
$dataToPackage = [
	'messageType'=>0x05,
	'status'=>0,//有效北纬东经
	'longitudeValue'=>10000000,
	'latitudeValue'=>30000000,
];
$formatArr = [
	'messageType'=>'C1',
	'status'=>'C1',
	'longitudeValue'=>'N1',//经度值
	'latitudeValue'=>'N1',//纬度值
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
	'messageType'=>0x06,
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
];
$format = '';
foreach($formatArr as $key=>$val){
	$format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data .= packStruct($format,$dataToPackage);
//极值数据结束

//电池电压数据
$dataToPackage = [
	'messageType'=>0x08,
	'total_package'=>1,//电池包总数（可充电储能子系统个数）
	'serial_number'=>1,//电池包序号
	'x1'=>'xxxx',
	'total_battery'=>1,//单体电池总数
	'x2'=>'xxx',
	'batttery_voltage'=>60000,	//单体电池电压
];
$formatArr = [
	'messageType'=>'C1',
	'total_package'=>'C1',
	'serial_number'=>'C1',
	'x1'=>'a4',
	'total_battery'=>'n1',
	'x2'=>'a3',
	'batttery_voltage'=>'n1',
];
$format = '';
foreach($formatArr as $key=>$val){
	$format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data .= packStruct($format,$dataToPackage);
//电池电压数据结束

//电池温度数据
$dataToPackage = [
	'messageType'=>0x09,
	'total_package'=>1,	//电池包总数（可充电储能子系统个数）
	'serial_number'=>1,	//电池包序号
	'total_probe'=>2,	//探针数
	'batttery_temperature1'=>0,	//温度1
	'batttery_temperature2'=>120,	//温度2
];
$formatArr = [
	'messageType'=>'C1',
	'total_package'=>'C1',
	'serial_number'=>'C1',
	'total_probe'=>'n1',
	'batttery_temperature1'=>'C1',
	'batttery_temperature2'=>'C1',
];
$format = '';
foreach($formatArr as $key=>$val){
	$format .= $val.$key.'/';
}
$format = rtrim($format,'/');
$data .= packStruct($format,$dataToPackage);
//电池温度数据结束

//校验码计算
$dataToPackage = [
	'startMark'=>'##',//数据包起始符（2字节##）
	'commandSingle'=>0x02,//命令标识（1字节）
	'commandAnswer'=>0xFE,//命令应答标识（1字节）
	'carVIN'=>'vin00001234567890',//车辆vin码或充电站编码+充电桩编码(17字节)
	'dataEncryptionWay'=>0x01,//数据加密方式（1字节8位）
	'dataLength'=>strlen($data),//数据长度（2字节16位0-65534）
	'data'=>$data,//数据内容
	'checkCode'=>87,//校码码（1字节8位）
];
$formatArr = [
	'startMark'=>'a2',//数据包起始符（2字节##）
	'commandSingle'=>'C1',//命令标识（1字节）
	'commandAnswer'=>'C1',//命令应答标识（1字节）
	'carVIN'=>'a17',//车辆vin码（17字节）
	'dataEncryptionWay'=>'C1',//数据加密方式（1字节8位）
	'dataLength'=>'n1',//数据长度（2字节16位0-65534）
	'data'=>'a'.strlen($data),//数据内容
	'checkCode'=>'C1',//校码码（1字节8位）
];
$format = '';
foreach($formatArr as $key=>$val){
	$format .= "{$val}{$key}/";
}
$format = rtrim($format,'/');
$content = bin2hex(packStruct($format, $dataToPackage));
$checkContentArr = str_split(substr($content, 4,strlen($content)-6), 2);
$checkCode = hexdec($checkContentArr[0]) ^ hexdec($checkContentArr[1]);
foreach($checkContentArr as $key=>$val){
	switch ($key) {
		case 0:
		case 1:
			break;
		default:
			$checkCode ^= hexdec($val);
		break;
	}
}
//end

//按指定格式打包所有数据准备发送
$dataToPackage = [
	'startMark'=>'##',//数据包起始符（2字节##）
	'commandSingle'=>0x02,//命令标识（1字节）
	'commandAnswer'=>0xFE,//命令应答标识（1字节）
	'carVIN'=>'vin00001234567890',//车辆vin码或充电站编码+充电桩编码(17字节)
	'dataEncryptionWay'=>0x01,//数据加密方式（1字节8位）
	'dataLength'=>strlen($data),//数据长度（2字节16位0-65534）
	'data'=>$data,//数据内容
	'checkCode'=>$checkCode,//校码码（1字节8位）
];
$formatArr = [
	'startMark'=>'a2',//数据包起始符（2字节##）
	'commandSingle'=>'C1',//命令标识（1字节）
	'commandAnswer'=>'C1',//命令应答标识（1字节）
	'carVIN'=>'a17',//车辆vin码（17字节）
	'dataEncryptionWay'=>'C1',//数据加密方式（1字节8位）
	'dataLength'=>'n1',//数据长度（2字节16位0-65534）
	'data'=>'a'.strlen($data),//数据内容
	'checkCode'=>'C1',//校码码（1字节8位）
];
$format = '';
foreach($formatArr as $key=>$val){
	$format .= "{$val}{$key}/";
}
$format = rtrim($format,'/');
$content = packStruct($format, $dataToPackage);
var_dump(bin2hex($content));
var_dump('正在发送车辆实时数据...');


echo bin2hex(pack("C1",190));
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
