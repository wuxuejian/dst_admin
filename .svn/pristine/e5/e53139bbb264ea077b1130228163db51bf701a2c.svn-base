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
use \Workerman\Lib\Timer;
//use \Protocols\ShenZhenShunFeng;

// 自动加载类
require_once __DIR__ . '/../../Workerman/Autoloader.php';
require_once './Core/Transmit.php';
require_once './Core/AsyncTcpConnectionHF.php';
Autoloader::setRootPath(__DIR__);

$transmitConnectionArr = [];//转发服务器链接资源
/**
 * 指定车辆连接服务
 */
function connectToTransmitServer($carvin,$mobile) {
    global $transmitConnectionArr;
    if(isset($transmitConnectionArr[$carvin])){
        $transmitConnectionArr[$carvin]->close();
    }
    //链接转发服务器
    $transmitConnection = new AsyncTcpConnectionHF('tcp://60.28.195.206:906');
    $transmitConnection->hfCarvin = $carvin;
    $transmitConnection->hfMobile = $mobile;
    $transmitConnection->hfLastActiveTime = time();
    $transmitConnection->onError = function($connection, $err_code, $err_msg) {
        //echo "$err_code, $err_msg";
    };
    $transmitConnection->onMessage = function($connection, $buffer) {
        echo 'reply...';
        //消息返转义
        $buffer = bin2hex( $buffer ); //转成十六进制
        $buffer = str_replace('7d02','7e',$buffer);
        $buffer = str_replace('7d01','7d',$buffer);
        //file_put_contents('./return.text',$buffer);
        preg_match_all("|7e(.{36})7e|U", $buffer, $output); //获取有效应答消息包
        $arr = $output[1];
        $returnArr = [];
        //遍历解析每个消息包
        foreach($arr as $val){
            $msgHeadHex = substr($val,0,24);  //消息头
            $msgBodyHex = substr($val,24,10); //消息体
            $checkCodeHex = substr($val,-2);  //检验码
            //---解包消息头-------------------------
            //消息ID
            $msgIdHex = substr($msgHeadHex,0,4);
            $msgIdBin = hex2bin($msgIdHex);
            $msgIdArr = unpack('nmsgId',$msgIdBin);
            //消息体属性
            $msgPropertyHex = substr($msgHeadHex,4,4);
            $msgPropertyBin = hex2bin($msgPropertyHex);
            $msgPropertyArr = unpack('nmsgProperty',$msgPropertyBin);
            $msgPropertyArr['msgProperty'] = str_pad(decbin($msgPropertyArr['msgProperty']),16,0,STR_PAD_LEFT);
            //终端手机号，注意此16进制字符串直接就是打包时使用的ICCID号的后12位数
            $terninalMobileHex = substr($msgHeadHex,8,12);
            //消息流水号
            $msgNumberHex = substr($msgHeadHex,-4);
            $msgNumberBin = hex2bin($msgNumberHex);
            $msgNumberArr = unpack('nmsgNumber',$msgNumberBin);
            $msgHeadArr = array_merge($msgIdArr,$msgPropertyArr,['terninalMobile'=>$terninalMobileHex],$msgNumberArr);
            //---解包消息体-------------------------
            $msgBodyBin = hex2bin($msgBodyHex);
            $msgBodyArr = unpack('nreplyNumber/nreplyId/CreplyResult',$msgBodyBin);
            //---解包检验码-------------------------
            $checkCodeBin = hex2bin($checkCodeHex);
            $checkCodeArr = unpack('CcheckCode',$checkCodeBin);
            //合并并存入数组
            $returnArr[] = array_merge($msgHeadArr,$msgBodyArr,$checkCodeArr);
        }
        //print_r($returnArr);
        file_put_contents('./return.text',date('Y-m-d H:i:s')." | Reply(hex): ".$buffer."\n",FILE_APPEND);
        file_put_contents('./return.text','Unpack: '.var_export($returnArr,true)."\n\n",FILE_APPEND);
    };
    $transmitConnection->onClose = function($connection) {
        global $transmitConnectionArr;
        unset($transmitConnectionArr[$connection->hfCarvin]);
        //echo 'closed by server,try connect again...';
        //connectToTransmitServer();
    };
    $transmitConnection->connect();
    $transmitConnectionArr[$carvin] = $transmitConnection;
}

// bussinessWorker 进程
$worker = new Worker('text://127.0.0.1:5000');
// worker名称
$worker->name = 'ShenZhenShunFeng';
// bussinessWorker进程数量
$worker->count = 2;
$worker->onWorkerStart = function($worker) {
    //添加ping消息定时器
    Timer::add('10', function() {
        global $transmitConnectionArr;
        $checkTime = time();
        foreach($transmitConnectionArr as $connection){
            if($checkTime - $connection->hfLastActiveTime > 120){
                $connection->close();
                unset($transmitConnectionArr[$connection->hfCarvin]);
            }else{
                $connection->hfMessageSerialNumber ++;
                $connection->send( Transmit::ping($connection->hfMessageSerialNumber,$connection->hfMobile) );
            }
        }
    });
};
$worker->onMessage = function($connection, $data) {
    global $transmitConnectionArr;
    $data = json_decode($data,true);
    //按类型获取将要发送的消息类型
    if(method_exists('Transmit', $data['type'])){
        $carvin = $data['params']['car_vin'];
        $mobile = $data['params']['mobile'];
        if(!isset($transmitConnectionArr[$carvin])){
            connectToTransmitServer($carvin,$mobile);
        }
        if(!isset($transmitConnectionArr[$carvin])){
            return;
        }
        $transmitConnectionArr[$carvin]->hfMessageSerialNumber ++;
        $actionName = $data['type'];
        $sendData = Transmit::$data['type']($transmitConnectionArr[$carvin]->hfMessageSerialNumber,$data['params']);
        if(filesize('./return.text') < 50000){
            file_put_contents('./return.text',date('Y-m-d H:i:s')." | Send: ".bin2hex($sendData)."\n",FILE_APPEND);
        }else{
            file_put_contents('./return.text',date('Y-m-d H:i:s')." | Send: ".bin2hex($sendData)."\n");
        }
        $transmitConnectionArr[$carvin]->send($sendData);
    }
};

// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START')) {
    Worker::runAll();
}