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
      //Gateway::sendToClient($client_id,'welcome');
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
      //获取操作状态
      $action = unpack('Caction',substr($message,2,1));
      switch ($action['action']) {
        case 2:
          //充电
          Gateway::sendToClient($client_id,[
            'action'=>2,
            'actionTarget'=>0,
            'result'=>0,
            'reason'=>0xff,
          ]);
          //var_dump(unpack('nregister/Caction/a8card/Cgun/fmoney',$message));
          break;
        case 3:
          //结账
          //var_dump(unpack('nregister/Caction/a8card/Cgun',$message));
          Gateway::sendToClient($client_id,[
            'action'=>3,
            'actionTarget'=>0,
            'result'=>0,
            'money'=>50,
          ]);
          break;
      }
    }

    /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
    public static function onClose($client_id)
    {
     echo "\n action:onClose client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:{$client_id}";
    }
}