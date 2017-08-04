<?php
$startTime = microtime(true);
require_once dirname(__FILE__) . '/common/Common.php';
require_once dirname(__FILE__) . '/function/interface_func.php';

//成为开发者使用
/*require_once dirname(__FILE__) . '/class/interface_class.php';
define("TOKEN", "chinaiwb");
$wechat = new wechatCallbackapiTest();
$wechat->valid();*/

$postStr = file_get_contents ( "php://input" );
//本地测试
/*
$postStr = "<xml><ToUserName><![CDATA[gh_ba3a92a8f1ab]]></ToUserName>
<FromUserName><![CDATA[oGDWpuJQg6nXxVCuxTltCr1UtLYA]]></FromUserName>
<CreateTime>1385687769</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[CLICK]]></Event>
<EventKey><![CDATA[PRINT_ORDER]]></EventKey>
</xml>";*/
interface_log(INFO, EC_OK, "");
interface_log(INFO, EC_OK, "***********************************");
interface_log(INFO, EC_OK, "***** interface request start *****");
interface_log(INFO, EC_OK, 'request:' . $postStr);
interface_log(INFO, EC_OK, 'get:' . var_export($_GET, true));

if (empty ( $postStr )) {
	interface_log ( ERROR, EC_OK, "error input!" );
	exitErrorInput();
}
// 获取参数
$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
if(NULL == $postObj) {
	interface_log(ERROR, 0, "can not decode xml");	
	exit(0);
}

$toUserName = ( string ) trim ( $postObj->ToUserName );
if (! $toUserName) {
	interface_log ( ERROR, EC_OK, "error input!" );
	exitErrorInput();
} else {
	$wechatObj = getWeChatObj ( $toUserName );
}
$ret = $wechatObj->init ( $postObj );
if (! $ret) {
	interface_log ( ERROR, EC_OK, "error input!" );
	exitErrorInput();
}
$retStr = $wechatObj->process ();
interface_log ( INFO, EC_OK, "response:" . $retStr );
echo $retStr;


interface_log(INFO, EC_OK, "***** interface request end *****");
interface_log(INFO, EC_OK, "*********************************");
interface_log(INFO, EC_OK, "");
$useTime = microtime(true) - $startTime;
interface_log ( INFO, EC_OK, "cost time:" . $useTime . " " . ($useTime > 4 ? "warning" : "") );
?>
