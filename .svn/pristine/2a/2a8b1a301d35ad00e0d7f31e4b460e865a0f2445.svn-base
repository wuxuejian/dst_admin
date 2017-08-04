<?php
/**
 * @Desc:   app支付宝支付返回控制器 
 * @author: wangmin
 * @date:   2015-12-22 13:45
 */
namespace backend\modules\interfaces\controllers;
use backend\models\VipRechargeRecord;
use backend\models\VipChargeRecord;
use yii;
use yii\web\Controller;
class AlipayNotifyController extends Controller{
    public $layout = false;
    public $enableCsrfValidation = false;
    /**
     * 账号充值返回接口
     */
    public function actionRecharge(){
        $appAlipayFilePath = dirname(dirname(getcwd())).'/extension/AppAlipay';
        require_once($appAlipayFilePath."/alipay.config.php");
        require_once($appAlipayFilePath."/lib/alipay_core.function.php");
        require_once($appAlipayFilePath."/lib/alipay_rsa.function.php");
        require_once($appAlipayFilePath."/lib/alipay_notify.class.php");
        //必需重载的配置
        //商户的私钥（后缀是.pem）文件相对路径
        $alipay_config['private_key_path']  = $appAlipayFilePath.'/key/rsa_private_key.pem';

        //支付宝公钥（后缀是.pem）文件相对路径
        $alipay_config['ali_public_key_path']= $appAlipayFilePath.'/key/alipay_public_key.pem';
        //ca证书路径地址，用于curl中ssl校验
        //请保证cacert.pem文件在当前文件夹目录中
        $alipay_config['cacert']    = $appAlipayFilePath.'/cacert.pem';
        //计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {
            //验证成功
            if($_POST['trade_status'] == 'TRADE_FINISHED') {
                //交易完成
                (new VipRechargeRecord())->rechargeSuccess($_POST['out_trade_no'],strtotime($_POST['gmt_create']),strtotime($_POST['gmt_payment']),$_POST['trade_no']);
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //交易成功
                (new VipRechargeRecord())->rechargeSuccess($_POST['out_trade_no'],strtotime($_POST['gmt_create']),strtotime($_POST['gmt_payment']),'充值[支付宝app]',$_POST['trade_no']);
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）—— 
            echo "success";     //请不要修改或删除
        }
        else {
            //验证失败
            echo "fail";
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }

    /**
     * 充电支付返回
     */
    public function actionChargePay(){
        $appAlipayFilePath = dirname(dirname(getcwd())).'/extension/AppAlipay';
        require_once($appAlipayFilePath."/alipay.config.php");
        require_once($appAlipayFilePath."/lib/alipay_core.function.php");
        require_once($appAlipayFilePath."/lib/alipay_rsa.function.php");
        require_once($appAlipayFilePath."/lib/alipay_notify.class.php");
        //必需重载的配置
        //商户的私钥（后缀是.pem）文件相对路径
        $alipay_config['private_key_path']  = $appAlipayFilePath.'/key/rsa_private_key.pem';

        //支付宝公钥（后缀是.pem）文件相对路径
        $alipay_config['ali_public_key_path']= $appAlipayFilePath.'/key/alipay_public_key.pem';
        //ca证书路径地址，用于curl中ssl校验
        //请保证cacert.pem文件在当前文件夹目录中
        $alipay_config['cacert']    = $appAlipayFilePath.'/cacert.pem';
        //计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {
            //验证成功
            if($_POST['trade_status'] == 'TRADE_FINISHED') {
                //交易完成
                VipChargeRecord::paySuccessHandle($_POST['out_trade_no'],'alipayapp',$_POST['trade_no']);
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //交易成功
                VipChargeRecord::paySuccessHandle($_POST['out_trade_no'],'alipayapp',$_POST['trade_no']);
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）—— 
            echo "success";     //请不要修改或删除
        }
        else {
            //验证失败
            echo "fail";
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }

}