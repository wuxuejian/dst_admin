<?php
/**
 * 北汽ping
 * 消息id   0x04
 * 应答消息 是
 * @author wangmin
 * @time 2015/11/06 14:41
 */
use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Store;
use \GatewayWorker\Lib\Db;
class CarPing
{
    public function init($message,$client_id)
    {
        //echo '收到车辆ping信息...',"\n";
        //发送成功消息
        $send_data = [
            'commandSingle'=>$message['commandSingle'],
            'commandAnswer'=>0x01,//
            'carVIN'=>$message['carVIN'],
            'data'=>'',
        ];
        //var_dump($send_data);
        Gateway::sendToClient($client_id,$send_data);
        //echo 'ping消息返回成功！',"\n";
        return true;
    }
}