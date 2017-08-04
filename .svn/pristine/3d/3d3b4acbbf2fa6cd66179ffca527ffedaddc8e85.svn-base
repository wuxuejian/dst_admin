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
use \Workerman\Worker;
use \Workerman\Autoloader;
use \Workerman\Connection\AsyncTcpConnection;
use \Workerman\Lib\Timer;
//use \Protocols\ShenZhenShunFeng;
date_default_timezone_set('PRC');

// 自动加载类
require_once __DIR__ . '/../../Workerman/Autoloader.php';
require_once './Core/MQTT.php';
include_once './Core/Gpsmsg.class.php';
include_once './Core/GpsmsgStatus.class.php';
Autoloader::setRootPath(__DIR__);

$transmitConnection = null;//转发服务器链接资源
function connectToTransmitServer() {
    global $transmitConnection;
    if($transmitConnection){
        $transmitConnection->send(MQTT::disconnect());
        $transmitConnection->close();
    }
    //链接转发服务器
    $transmitConnection = new AsyncTcpConnection('tcp://60.28.195.206:883');
    $transmitConnection->onMessage = function($connection, $buffer) {
        //echo 'resived...',"\n";
        //解析返回数据
        if(MQTT::decode($buffer) === false){
            //连接不可用强制重连
            connectToTransmitServer();
        }
        //buffer日志
        file_put_contents('./return.text',date('Y-m-d H:i:s')." | Reply(hex): ".bin2hex($buffer)."\n",FILE_APPEND);
    };
    $transmitConnection->onError = function($connection, $err_code, $err_msg) {
        //echo "$err_code, $err_msg";
    };
    $transmitConnection->onClose = function($connection) {
        echo 'closed by server,try connect again...';
        connectToTransmitServer();
    };
    $transmitConnection->connect();
    $transmitConnection->send(MQTT::connect());
}

// bussinessWorker 进程
$worker = new Worker('text://127.0.0.1:5000');
// worker名称
$worker->name = 'ShenZhenShunFeng';
// bussinessWorker进程数量
$worker->count = 2;
static $hfMessageSerialNumber = 0;
$worker->onWorkerStart = function($worker) {
    //链接转发服务器
    connectToTransmitServer();
    //添加ping消息定时器
    Timer::add(8, function() {
        global $transmitConnection;
        $transmitConnection->send(MQTT::ping());
    });
};
$worker->onMessage = function($connection, $data) {
    global $transmitConnection,$hfMessageSerialNumber;
    $data = json_decode($data,true);
    //按类型获取将要发送的消息类型
    if(method_exists('MQTT', $data['type'])){
        $actionName = $data['type'];
        $hfMessageSerialNumber ++;
        if($hfMessageSerialNumber > 255){
            $hfMessageSerialNumber = 0;
        }
        $sendData = MQTT::$actionName($hfMessageSerialNumber,$data['params']);
        if( filesize('./return.text') < 5000 ){
            file_put_contents('./return.text',date('Y-m-d H:i:s')." | Send: ".bin2hex($sendData)."\n",FILE_APPEND);
        }else{
            file_put_contents('./return.text',date('Y-m-d H:i:s')." | Send: ".bin2hex($sendData)."\n");
        }
        $transmitConnection->send($sendData);
    }
};

// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START')) {
    Worker::runAll();
}