<?php
/**
 * @Desc:   前置机控制器 
 * @author: pengyl
 * @date:   2017-2-6 14:28
 */
namespace backend\modules\interfaces\controllers;

use yii;
use yii\web\Controller;
class FrontmachineController extends Controller{
	/**
	 * 前置机状态
	 */
	public function actionStatus(){
		$ip = @$_GET['ip'];
		$ip = $ip?$ip:'120.76.41.26';
		// 建立客户端的socet连接
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		//发送超时
// 		socket_set_option($socket,SOL_SOCKET,SO_SNDTIMEO,array("sec"=>3,"usec"=>0 ));
// 		//接收超时
// 		socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,array("sec"=>3,"usec"=>0 ));
		$connection = @socket_connect($socket, $ip, 9094);
		if(!$connection){
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			exit;
		}
	}
}