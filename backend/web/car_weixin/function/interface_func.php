<?php
function getWeChatObj($toUserName) {
if($toUserName == USERNAME_IWB) {
	require_once dirname(__FILE__) . '/../class/haofeng.php';
	return new HaoFeng();
	}
	require_once dirname(__FILE__) . '/../class/WeChatCallBack.php';
	return  new WeChatCallBack();
}
function exitErrorInput(){

	echo 'error input!';
	interface_log(INFO, EC_OK, "***** interface request end *****");
	interface_log(INFO, EC_OK, "*********************************");
	interface_log(INFO, EC_OK, "");
	exit ( 0 );
}
?>