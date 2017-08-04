<?php
/**
 * 车辆异常上报控制器
 * 消息id    0x03
 * 应答消息  是
 * @author   wangmin
 * @time     2015/11/03 11:50
 */
use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Store;
use \GatewayWorker\Lib\Db;
class CarException
{
    //报警类型
    public static $alertType = [
        0x01 => '电机控制器（MCU）异常报警',
        0x02 => '整车控制器（VCU）异常报警',
        0x03 => '电池管理系统（BMS）异常报警',
        0x04 => '制动系统异常报警',
    ];
    //报警信号编号及含义
    public static $alertSerialNumber = [
        0x01 => ['电机控制器温度','MCU'],
        0x02 => ['驱动电机温度','MCU'],
        0x03 => ['电机驱动系统故障','MCU'],
        0x04 => ['DCDC温度','MCU'],
        0x05 => ['DCDC状态','MCU'],
        0x06 => ['电池总电压','BMS'],
        0x07 => ['电池单体最高温度','BMS'],
        0x08 => ['电池单体最低温度','BMS'],
        0x09 => ['电池单体最高电压','BMS'],
        0x0A => ['电池单体最低电压','BMS'],
        0x0B => ['高压互锁状态 ','BMS'],
        0x0C => ['绝缘电阻值','BMS'],
        0x0D => ['碰撞信号状态','BMS'],
        0x0E => ['储能系统故障指示','BMS'],
        0x0F => ['制动系统故障','ABS'],
    ];
    public function init($message,$client_id)
    {
        $db = Db::instance('db');
        //查询上报的车辆数据
        $carInfo = $db->select('id,car_vin')->from('cs_tcp_car')
            ->where('car_vin = :car_vin')->bindValues(['car_vin'=>$message['carVIN']])
            ->row();
        if(!$carInfo){
            //上报的车辆未注册
            $send_data = [
                'commandSingle'=>$message['commandSingle'],
                'commandAnswer'=>0x02,
                'serialNumber'=>intval($_SESSION['serialNumber']),
                'carVIN'=>$message['carVIN'],
                'data'=>'',
            ];
            Gateway::sendToClient($client_id,$send_data);
            return false;
        }
        //解析信息收集时间
        $collectionDatetime = unpack('C1year/C1month/C1day/C1hour/C1minute/C1second',substr($message['data'],0,6));
        $collectionDatetime = strtotime("20{$collectionDatetime['year']}-{$collectionDatetime['month']}-{$collectionDatetime['day']} {$collectionDatetime['hour']}:{$collectionDatetime['minute']}:{$collectionDatetime['second']}");
        $message['data'] = substr($message['data'],6);
        $saveData = [];//将被保存到数据库的数据
        while(strlen($message['data']) > 0){
            //确定第一个报警数据小数据包的长度
            $packageHeader = unpack('C1type/C1itemNum',$message['data']);
            $packageLenght = 2 + 3 * $packageHeader['itemNum'];
            //得到当前小数据包
            $packageData = substr($message['data'],0,$packageLenght);
            $packageData = substr($packageData,2);//跳过当前类型与该类型下的报警总数
            for($i = 0;$i < $packageHeader['itemNum'];$i++){
                $packageDataUnpack = unpack('C1serialNumber/C1value/C1level',substr($packageData,$i*3,3));
                if($packageDataUnpack['value'] == 1){
                    //有报警
                    $packageSerialNumber = $packageDataUnpack['serialNumber'];
                    if(isset(self::$alertSerialNumber[$packageSerialNumber])){
                        $alertSerialNumber = self::$alertSerialNumber[$packageSerialNumber];
                    }else{
                        $alertSerialNumber = ['',''];
                    }
                    $alert_type = isset(self::$alertType[$packageHeader['type']]) ? self::$alertType[$packageHeader['type']] : '';
                    $saveData[] = [
                        'alert_type'=>$alert_type,
                        'ecu_module'=>$alertSerialNumber[1],
                        'content'=>$alertSerialNumber[0],
                        'level'=>intval($packageDataUnpack['level']),
                    ];
                }else{
                    //无报警
                }
            }
            //剩下的没有解析的数据包
            $message['data'] = substr($message['data'],$packageLenght);
        }
        //保存数据到数据库
        $sql = 'insert into `cs_tcp_car_exception`(`car_vin`,`alert_type`,`ecu_module`,`content`,`level`,`collection_datetime`,`update_datetime`) values ';
        $update_datetime = time();
        foreach($saveData as $val){
            $sql .= '(';
            $sql .= "\"{$carInfo['car_vin']}\",\"{$val['alert_type']}\",\"{$val['ecu_module']}\",\"{$val['content']}\",{$val['level']},{$collectionDatetime},{$update_datetime}";
            $sql .= '),';
        }
        $sql = rtrim($sql,',');
        $send_data = [
            'commandSingle'=>$message['commandSingle'],
            'commandAnswer'=>0,
            'serialNumber'=>intval($_SESSION['serialNumber']),
            'carVIN'=>$message['carVIN'],
            'data'=>'',
        ];
        if($db->query($sql)){
            $send_data['commandAnswer'] = 0x01;//成功
        }else{
            $send_data['commandAnswer'] = 0x02;//失败
        }
        Gateway::sendToClient($client_id,$send_data);
        //缓存报警数据到文件
        $dataPath = dirname(dirname(getcwd())).'/CacheData/'.rtrim($message['carVIN']);
        is_dir($dataPath) or mkdir($dataPath,0777,true);
        is_dir($dataPath.'/AlertData') or mkdir($dataPath.'/AlertData');
        $fileName = $dataPath.'/AlertData/'.date('YmdH').'.json';
        file_exists($fileName) or touch($fileName);
        $saveStr = '';
        foreach($saveData as $val){
            $val['collection_datetime'] = $collectionDatetime;
            $val['update_datetime'] = $update_datetime;
            $saveStr .= json_encode($val)."\n";
        }
        file_put_contents($fileName,$saveStr,FILE_APPEND);
    }
}