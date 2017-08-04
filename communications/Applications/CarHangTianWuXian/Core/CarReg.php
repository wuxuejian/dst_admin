<?php
/**
 * 大通车辆注册
 * 消息id   0x01
 * 应答消息 是
 * @author pengyl
 * @time 2016/12/28 14:07
 */
use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Store;
use \GatewayWorker\Lib\Db;
class CarReg
{
    public $db;
    public function init($message,$client_id)
    {
        //echo '收到车辆注册信息...',"\n";
        $this->db = DB::instance('db');
        //处理车辆基本信息
        //车辆信息定长53字节，文档明确标注
        $this->saveCarInfo($message['data'],$message['carVIN']);
        
        //发送成功消息
        $send_data = [
            'commandSingle'=>$message['commandSingle'],
            'commandAnswer'=>0x01,
            'carVIN'=>$message['carVIN'],
            'data'=>'',
        ];
        Gateway::sendToClient($client_id,$send_data);
        return true;
    }

    /**
     * 处理车辆基本信息
     * @param  bin    $data   车辆基本数据的二进制数据流
     * @param  string $carVin 车辆vin识别码
     * @return 插入车辆基本信息后返回车辆的insertid
     */
    protected function saveCarInfo($data,$carVin){
        $formatArr = [
            'regTimeYear'=>'C1',//注册时间年
            'regTimeMonth'=>'C1',//注册时间月
            'regTimeDay'=>'C1',//注册时间日
            'regTimeHour'=>'C1',//注册时间时
            'regTimeMinute'=>'C1',//注册时间分
            'regTimeSecond'=>'C1',//注册时间秒
            'regNumber'=>'n1',//注册流水号
            'iccid'=>'a20',//iccid号
            'batteryRatio'=>'C1',//可充电储能子系统数(n)
            'batteryLength'=>'C1',//可充电储能子系统编码长度(m)
            
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $carInfo = unpack($format,$data);
        //解析电池数据
        $data = substr($data,30);
        $formatArr = [
	        'batteryData'=>'a'.($carInfo['batteryRatio']*$carInfo['batteryLength']),//可充电储能子系统编码(n*m)
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
        	$format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $batteryInfo = unpack($format,$data);
        $carInfo['batteryData'] = $batteryInfo['batteryData'];
        //end
        
        //检测该车辆是否已经存在
        $hasRecord = $this->db->select('id')->from('cs_tcp_car')
                    ->where('car_vin = :car_vin')->bindValues(['car_vin'=>$carVin])
                    ->row();
        $saveData = [
            'data_source'=>'大通',
            'car_vin'=>addslashes($carVin),
            'reg_time'=>strtotime("20{$carInfo['regTimeYear']}-{$carInfo['regTimeMonth']}-{$carInfo['regTimeDay']} {$carInfo['regTimeHour']}:{$carInfo['regTimeMinute']}:{$carInfo['regTimeSecond']}"),
            'reg_number'=>$carInfo['regNumber'],
//             'iccid'=>$carInfo['iccid'],
//             'battery_data'=>$carInfo['batteryData']
        ];
        if($hasRecord){
            //更新车辆数据
            $this->db->update('cs_tcp_car')->cols($saveData)->where('id = '.$hasRecord['id'])->query();
            return $hasRecord['id'];
        }else{
            //保存车辆信息
            return $this->db->insert('cs_tcp_car')->cols($saveData)->query();
        }
    }
}