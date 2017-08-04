<?php
/**
 * 检查验证控制器（检查各类账单支付状态等）
 */
namespace backend\modules\interfaces\controllers;
use backend\models\Vip;
use backend\models\VipRechargeRecord;

class CheckController extends BaseController{
		
    /**
     *  @微信/支付宝支付后，检查相关账单是否支付完成。
     */
    public function actionCheckIsPaySuccess(){
        $datas = [];
        $_mobile = isset($_REQUEST['mobile'])  ? trim($_REQUEST['mobile']) : '';	// 手机号
        $_code   = isset($_REQUEST['code'])  ? trim($_REQUEST['code']) : '';         // 账单号
        $_type   = isset($_REQUEST['type'])  ? trim($_REQUEST['type']) : '';          // 账单类型

        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);

        if(!$_code || !$_type){
            $datas['error'] = 1;
            $datas['msg'] = '缺少相关联的账单号或账单类型！';
            return json_encode($datas);
        }

        $flag = '';
        switch($_type){
            case 'VipRechargeRecord': // 会员充值账单
                $res = VipRechargeRecord::find()
					->select(['id','trade_no','total_fee','trade_status'])
					->where(['trade_no'=>$_code])
					->asArray()->one();
                $flag = $res['trade_status'];
                break;
            default:
                $res = [];
                break;
        }
        if($flag != 'success'){
            $datas['error'] = 1;
            $datas['msg'] = '账单支付失败！';
        }else{
            $datas['error'] = 0;
            $datas['msg'] = '账单支付成功！';
        }
        return json_encode($datas);
    }
	
	
	
	
	
}