<?php
/**
 * APP入口文件
 */

//路由分发开始
//控制器分发变量
$actionDistribute = [
    // 注册会员时发送短信，创建新的vip记录
    'sendMSGCreate'=>['ali-shotmessage','create-user'],
    // 找回密码时发送短信，不创建新的vip记录
    'sendMSGExists'=>['ali-shotmessage','user-exists'],
    // 检查各类账单是否支付完成
    'checkIsPaySuccess'=>['check','check-is-pay-success'],
    // 会员注册、登录、修改密码
    'register'=>['register-login','register'],
    'login'=>['register-login','login'],
    'forgetPwd'=>['register-login','forget-pwd'],
    'editPwd'=>['register-login','edit-pwd'],
    // 会员账户余额、余额变动记录
    'getVipAccount'=>['vip-account','get-vip-account'],
    'getMoneyChangeRecord'=>['vip-account','get-money-change-record'],
    // 会员充值、充值记录
    'recharge'=>['recharge','create-order'],
    'getRechargeRecord'=>['recharge','get-recharge-record'],
    // 附近电桩
	//'getChargers'=>['charger','get-chargers'],
    // 开始充电、结束充电、充电记录
    'poleInfo'=>['charge','pole-info'],
    'startCharge'=>['charge','start-charge'],
    'endCharge'=>['charge','end-charge'],
    'getChargeRecord'=>['charge','get-charge-record'],
    // 会员车辆
    'addEditVehicle'=>['vehicle','add-edit-vehicle'],
    'getVehicle'=>['vehicle','get-vehicle'],
    'removeVehicle'=>['vehicle','remove-vehicle'],
    // 预约充电
    'addEditAppointment'=>['appointment','add-edit-appointment'],
    'getAppointment'=>['appointment','get-appointment'],
    'removeAppointment'=>['appointment','remove-appointment'],
    // 会员分享
    'addEditShare'=>['share','add-edit-share'],
    'getShare'=>['share','get-share'],
    // 会员收藏
    'addFavorite'=>['favorite','add-favorite'],
    'getFavorite'=>['favorite','get-favorite'],
    'removeFavorite'=>['favorite','remove-favorite'],
    // 会员建议
    'addSuggestion'=>['suggestion','add-suggestion'],
    'getSuggestion'=>['suggestion','get-suggestion'],
    'removeSuggestion'=>['suggestion','remove-suggestion'],
    // 获取配置
    'getConfigs'=>['configs','get-configs'],
    // 上传图片文件（头像、驾照等）
    'upload'=>['upload','upload'],
    //企业客户关联
    'getComCusInfo'=>['company-customer','customer-info'],
    'getComCusLetingCar'=>['company-customer','leting-car'],
    'getLetingCarRealtimeBrief'=>['company-customer','realtime-data-brief'],
    'getLetingCarRealtimeDetail'=>['company-customer','realtime-data-detail'],
];

if(isset($_GET['r'])){
    //支付返回调
    $_GET['r'] = ltrim($_GET['r'],'/');
}else{
    if(!isset($_REQUEST['act'])){
        header("HTTP/1.1 404 Not Found");
        die('404 Not Found'); 
    }
    if(isset($actionDistribute[$_REQUEST['act']])){
        $_GET['r'] = 'interfaces/'.$actionDistribute[$_REQUEST['act']][0].'/'.$actionDistribute[$_REQUEST['act']][1];
    }else{
        $_GET['r'] = 'interfaces/'.str_replace('_','/',$_REQUEST['act']);
    }
}

//路由分发结束

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);

$application = new yii\web\Application($config);
$application->run();
