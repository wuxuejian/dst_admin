<?php
require_once('common/Common.php');
$appid = WX_API_APPID;
//$redirect_uri = urlencode ( 'http://yqzc.dstzc.com/car_weixin/getinfo.php');
$redirect_uri = urlencode ( 'http://yqzc.dstzc.com/index.php?r=promotion/sign/index');
//静默
$url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
header("Location:".$url);