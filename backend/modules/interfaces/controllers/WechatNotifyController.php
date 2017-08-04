<?php
/**
 * @Desc:   app微信支付返回控制器 
 * @author: wangmin
 * @date:   2015-12-28 15:00
 */
namespace backend\modules\interfaces\controllers;
use backend\models\VipRechargeRecord;
use backend\models\VipChargeRecord;
use yii;
use yii\web\Controller;
class WechatNotifyController extends Controller{
    public $layout = false;
    public $enableCsrfValidation = false;
    /**
     * 客户通过app充值账号余额回调
     */
    public function actionRecharge(){
        file_put_contents('../runtime/wechart_pay_notify.log',file_get_contents('php://input'),FILE_APPEND);
        $notify = new PayNotifyCallBack();
        $notify->_callbackType_ = 'recharge';//充值支付
        $notify->Handle(false);
    }

    /**
     * 充电支付
     */
    public function actionCharge(){
        //file_put_contents('./test2.pay',file_get_contents('php://input'));
        $notify = new PayNotifyCallBack();
        $notify->_callbackType_ = 'charge';//充电支付
        $notify->Handle(false);
    }
}

/**
 * 引入微信支付回调类
 */
include_once(dirname(dirname(getcwd())).'/extension/WxpayAPI_php_v3/lib/WxPay.Api.php');
include_once(dirname(dirname(getcwd())).'/extension/WxpayAPI_php_v3/lib/WxPay.Notify.php');
class PayNotifyCallBack extends \WxPayNotify{
    public $_callbackType_ = '';//自定义回调类型
    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new \WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = \WxPayApi::orderQuery($input);
        //Log::DEBUG("query:" . json_encode($result));
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }
    
    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        //Log::DEBUG("call back:" . json_encode($data));
        $notfiyOutput = array();
        
        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }
        //处理本站业务逻辑
        switch ($this->_callbackType_) {
            case 'recharge':
                //充值
                $endTime = strtotime(substr($data['time_end'],0,4).'-'.substr($data['time_end'],4,2).'-'.substr($data['time_end'],6,2)." ".substr($data['time_end'],8,2).':'.substr($data['time_end'],10,2).':'.substr($data['time_end'],12,2));
                (new VipRechargeRecord)->rechargeSuccess($data['out_trade_no'],0,$endTime,'充值[微信app]',$data['transaction_id']);
                return true;
            case 'charge':
                //充电
                $action = VipChargeRecord::paySuccessHandle($data['out_trade_no'],'wechatapp',$data['transaction_id']);
                if($action[0]){
                    return true;
                }else{
                    $msg = $action[1];
                    return false;
                }
        }
        return true;
    }
}