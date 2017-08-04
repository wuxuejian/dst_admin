<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Db;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 *
 * GatewayWorker开发参见手册：
 * @link http://gatewayworker-doc.workerman.net/
 */
class Event
{
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $client_id 连接id
     * @link http://gatewayworker-doc.workerman.net/gateway-worker-development/onconnect.html
     */
    public static function onConnect($client_id)
    {
        // 向当前client_id发送数据 @see http://gatewayworker-doc.workerman.net/gateway-worker-development/send-to-client.html
        echo "\n action:onConnect client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:{$client_id}";
    }
    
   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param string $message 具体消息
    * @link http://gatewayworker-doc.workerman.net/gateway-worker-development/onmessage.html
    */
   public static function onMessage($client_id, $message)
   {
        echo "\n action:onMessage client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:{$client_id}";
        //消息id
        if(isset($_SESSION['serialNumber']) && $_SESSION['serialNumber'] < 2147483640){
            $_SESSION['serialNumber'] += 1;
        }else{
            $_SESSION['serialNumber'] = 1;
        }
        //验证客户端是否已经通过验证
        if($message['commandSingle'] != 0x07){
            //如果命令码不是0x07（调用验证方法）则要验证用户是否验证
            if(!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] != 1){
                $send_data = [
                    'commandSingle'=>$message['commandSingle'],
                    'commandAnswer'=>0x05,//用户未发送企业平台认证消息，或认证失败，就立即开始发送其他消息
                    'serialNumber'=>intval($_SESSION['serialNumber']),
                    'carVIN'=>$message['carVIN'],
                    'data'=>'',
                ];
                Gateway::sendToClient($client_id,$send_data);
                return false;
            }
        }
        //验证客户端是否已经通过验证结束
        $commondType = [
            0x01=>'CarReg',//车辆注册信息
            0x02=>'CarDataSubmit',//车辆实时上报信息
            0x03=>'CarException',//车辆异常报警数据信息
            0x06=>'CarPing',//心跳消息
            0x07=>'CarLogin',//企业平台认证消息
        ];
        if(!isset($commondType[$message['commandSingle']])){
            //不合法的消息码
            $send_data = [
                'commandSingle'=>$message['commandSingle'],
                'commandAnswer'=>0x02,//修改错
                'serialNumber'=>intval($_SESSION['serialNumber']),
                'carVIN'=>$message['carVIN'],
                'data'=>'',
            ];
            Gateway::sendToClient($client_id,$send_data);
            return false;
        }
        $className = $commondType[$message['commandSingle']];
        echo "\n {$className}";
        include_once('./Core/'.$className.'.php');
        (new $className)->init($message,$client_id);
   }
   
   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {
       echo "\n action:onClose client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:{$client_id}";
        //修改数据库记录标注账号退出
        sleep(1);//休眠-秒防止用户秒退而无法修改状态
        $db = Db::instance('db');
        $db->update('cs_tcp_author')
        ->cols([
            'client_id'=>0,
            'is_online'=>0,
        ])->where('client_id = "'.$client_id.'"')->query();
   }
}
