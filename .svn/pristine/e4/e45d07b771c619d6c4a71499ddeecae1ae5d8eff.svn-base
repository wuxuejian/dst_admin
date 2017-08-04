<?php
/**
 * 北汽车载终端状态
 * 消息id   0x03
 * 应答消息 是
 * @author wangmin
 * @time 2015/11/06 14:43
 */
use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Store;
use \GatewayWorker\Lib\Db;
class CarStatus
{
    public function init($message,$client_id)
    {
        //echo '收到车载终端状态信息...',"\n";
        //检查车辆是否存在
        $db = Db::instance('db');
        $car = $db->select('id,car_vin')->from('cs_tcp_car')
                ->where('car_vin = :car_vin')->bindValues(['car_vin'=>$message['carVIN']])
                ->row();
        if(!$car){
            //车辆没有注册
            $send_data = [
                'commandSingle'=>$message['commandSingle'],
                'commandAnswer'=>0x02,//
                'carVIN'=>$message['carVIN'],
                'data'=>'',
            ];
            Gateway::sendToClient($client_id,$send_data);
            return false;
        }
        //解析数据
        $formatArr = [
            'year'=>'C1',
            'month'=>'C1',
            'day'=>'C1',
            'hour'=>'C1',
            'minute'=>'C1',
            'second'=>'C1',
            'status'=>'C1',
            //'yl'=>'a4',
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $unpackData = unpack($format,$message['data']);
        $saveData = [];
        $saveData['collection_datetime'] = strtotime("20{$unpackData['year']}-{$unpackData['month']}-{$unpackData['day']} {$unpackData['hour']}:{$unpackData['minute']}:{$unpackData['second']}");
        $status = str_pad(decbin($unpackData['status']),8,'0',STR_PAD_LEFT);
        $statusArr = [];
        if($status[7] == 1){
            $statusArr[] = '通电';
        }else{
            $statusArr[] = '断开';
        }
        if($status[6] == 1){
            $statusArr[] = '电源正常';
        }else{
            $statusArr[] = '电源异常';
        }
        if($status[5] == 1){
            $statusArr[] = '通讯传输正常';
        }else{
            $statusArr[] = '通讯传输异常';
        }
        if($status[4] != 1){
            $statusArr[] = '其他异常';
        }
        $saveData['status'] = json_encode($statusArr);
        //保存记录
        $hasRecord = $db->select('id')->from('cs_tcp_car_terminal_info')
            ->where('car_vin = :car_vin')
            ->bindValues(['car_vin'=>$car['car_vin']])
            ->row();
        $saveData['car_vin'] = $car['car_vin'];
        if($hasRecord){
            $db->update('cs_tcp_car_terminal_info')->cols($saveData)->where('id='.$hasRecord['id'])->query();
        }else{
            $db->insert('cs_tcp_car_terminal_info')->cols($saveData)->query();
        }
        /*//记录日志
        $dataPath = dirname(dirname(getcwd())).'/CacheData/'.rtrim($message['carVIN']);
        is_dir($dataPath) or mkdir($dataPath,0777,true);
        is_dir($dataPath.'/CarStatus') or mkdir($dataPath.'/CarStatus');
        $file = $dataPath.'/CarStatus/'.date('YmdH').'.json';
        file_exists($file) or touch($file);
        file_put_contents($file,json_encode($saveData)."\n",FILE_APPEND);*/
        //发送成功消息
        $send_data = [
            'commandSingle'=>$message['commandSingle'],
            'commandAnswer'=>0x01,//
            'carVIN'=>$message['carVIN'],
            'data'=>'',
        ];
        //echo '车载终端状态信息保存成功！',"\n";
        //var_dump($send_data);
        Gateway::sendToClient($client_id,$send_data);
        return true;
    }
}