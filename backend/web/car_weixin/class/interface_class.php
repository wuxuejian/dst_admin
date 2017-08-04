<?php
class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];//随机字符串

        //valid signature , option
        if($this->checkSignature()== false){
        	echo $echoStr;
        	exit;
        }
    }
		
	private function checkSignature()
	{
        $signature = $_GET["signature"];//微信加密签名
        $timestamp = $_GET["timestamp"];//时间戳
        $nonce = $_GET["nonce"];	//随机数
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr); //进行字典序排序
		$tmpStr = implode( $tmpArr );//转换为字符串
		$tmpStr = sha1( $tmpStr );//sha1加密用于与微信加密签名比较
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}
?>