<?php
/**
 * 企业平台认证
 * 消息id   0x07
 * 应答消息 是
 * @author wangmin
 * @time 2015/11/02 14:04
 */
use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Store;
use \GatewayWorker\Lib\Db;
class CarLogin
{
    public function init($message,$client_id)
    {
        $formatArr = [
            'uid'=>'a40',
            'password'=>'a20',
            'clientType'=>'C1'
        ];
        $format = '';
        foreach($formatArr as $key=>$val){
            $format .= $val.$key.'/';
        }
        $format = rtrim($format,'/');
        $data = unpack($format,$message['data']);
        if(empty($data['uid']) || empty($data['password'])){
            //参数不完整
            $send_data = [
                'commandSingle'=>$message['commandSingle'],
                'commandAnswer'=>0x04,//用户名或密码错
                'serialNumber'=>intval($_SESSION['serialNumber']),
                'carVIN'=>$message['carVIN'],
                'data'=>'',
            ];
            Gateway::sendToClient($client_id,$send_data);
            return false;
        }
        if(isset($_SESSION['isLogin']) && $_SESSION['isLogin'] == 1){
            //已经登陆过
            $send_data = [
                'commandSingle'=>$message['commandSingle'],
                'commandAnswer'=>0x01,//登录成功
                'serialNumber'=>intval($_SESSION['serialNumber']),
                'carVIN'=>$message['carVIN'],
                'data'=>'',
            ];
            Gateway::sendToClient($client_id,$send_data);
            return true;
        }
        $db = Db::instance('db');
        $count = rtrim($data['uid']);
        $password = md5(substr(md5(rtrim($data['password'])),0,30));
        $countInfo = $db->select('id,connect_times')->from('cs_tcp_author')->where(['count = :count and password = :password and is_del = 0'])->bindValues(['count'=>$count,'password'=>$password])->row();
        if(!$countInfo){
            //账号不存在
            $send_data = [
                'commandSingle'=>$message['commandSingle'],
                'commandAnswer'=>0x04,//用户名或密码错
                'serialNumber'=>intval($_SESSION['serialNumber']),
                'carVIN'=>$message['carVIN'],
                'data'=>'',
            ];
            Gateway::sendToClient($client_id,$send_data);
            return false;
        }
        //处理登陆
        $saveData = [
            'client_id'=>$client_id,
            'client_ip'=>$_SERVER['REMOTE_ADDR'],
            'connect_datetime'=>time(),
            'connect_times'=>$countInfo['connect_times'] + 1,
            'is_online'=>'1',
        ];
        $db->update('cs_tcp_author')->cols($saveData)->where('id='.$countInfo['id'])->query();
        $_SESSION['isLogin'] = 1;
        //$_SESSION['countId'] = $countInfo['id'];
        $send_data = [
            'commandSingle'=>$message['commandSingle'],
            'commandAnswer'=>0x01,//成功
            'serialNumber'=>intval($_SESSION['serialNumber']),
            'carVIN'=>$message['carVIN'],
            'data'=>'',
        ];
        Gateway::sendToClient($client_id,$send_data);
    }
}