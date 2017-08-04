<?php
/**
 * 会员充值管理 控制器
 */
namespace backend\modules\interfaces\controllers;
use yii;
use backend\models\Vip;
use backend\models\ConfigCategory;
use backend\models\VipRechargeRecord;

class RechargeController extends BaseController{
    
	/**
     * 创建充值订单
     */
    public function actionCreateOrder(){
        $datas = [];
        $_mobile  = isset($_REQUEST['mobile'])  ? trim($_REQUEST['mobile']) : '';            // 手机号
        $_money   = isset($_REQUEST['money'])  ? floatval($_REQUEST['money']) : 0.00;        // 充值金额
        $_payway  = isset($_REQUEST['payway'])  ? trim($_REQUEST['payway']) : '';            // 支付方式:wechat/alipay（微信/支付宝）

        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);
        if(!$vip_id){
            $datas['error'] = 1;
            $datas['msg'] = '无对应会员信息！';
            return json_encode($datas);
        }
        if(!$_money || !$_payway){
            $datas['error'] = 1;
            $datas['msg'] = '充值金额或支付方式未填写！';
            return json_encode($datas);
        }
        // 添加会员充值记录
        $model = new VipRechargeRecord();
        $model->trade_no = 'VRR' . uniqid();    // 交易号（前缀+uniqid()函数生产的13位字符串）
        $model->vip_id = $vip_id;
        $model->total_fee = $_money;
        $model->pay_way = $_payway;             // 支付方式: wechat/alipay
        $model->trade_status = 'wait_pay';      // 交易状态: wait_pay/success
        $model->request_datetime = time();		// 申请时间
        $model->gmt_create_datetime = time();	// 交易创建时间
        if($model->save()){
            $datas['error'] = 0;
            $datas['msg']   = '新建充值账单成功！';
            $datas['data']  = $model->getAttributes(['trade_no','total_fee','pay_way']);
            switch ($_payway) {
                case 'alipay':
                    //用户选择支付宝支付
                    $datas['data']['notify_url'] = yii::$app->urlManager->createAbsoluteUrl(['interfaces/alipay-notify/recharge']);
                    break;
                case 'wechat':
                    //用户选择微信支付
                    $datas['data']['notify_url'] = yii::$app->urlManager->createAbsoluteUrl(['interfaces/wechat-notify/recharge']);
                    //调用微信下单结口
                    /*$wxInterfaceRes = $this->wxPayUnifiedOrder($model->getAttribute('trade_no'),$datas['data']['notify_url']);
                    if($wxInterfaceRes['return_code'] == 'FAIL'){
                        $datas['error'] = 1;
                        $datas['msg']   = '新建充值账单失败['.$wxInterfaceRes['return_msg'].']！';
                    }else{
                        $datas['data']['']
                    }*/
                    break;
            }
            $datas['data']['notify_url'] = urldecode(str_replace('index-app.php?r=', 'pay-notify.php/', $datas['data']['notify_url']));
        }else{
            $datas['error'] = 1;
            $datas['msg'] = '新建充值账单时出错！';
        }
        return json_encode($datas);
    }

	
    /**
     * 调用微信统一下单接口
     */
    /*protected function wxPayUnifiedOrder($orderNumber,$notifyUrl){
        //引入微信支付类
        $wxPayRootPath = dirname(dirname(getcwd())).'/extension/WxpayAPI_php_v3/lib';
        include_once($wxPayRootPath.'/WxPay.Api.php');
        //支付配置
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("test");
        //$input->SetAttach("test");
        $input->SetOut_trade_no($orderNumber);
        $input->SetTotal_fee("1");
        //$input->SetTime_start(date("YmdHis"));
        //$input->SetTime_expire(date("YmdHis", time() + 600));
        //$input->SetGoods_tag("test");
        $input->SetNotify_url($notifyUrl);
        $input->SetProduct_id('123');
        $input->SetTrade_type("APP");
        //$input->SetOpenid($openId);
        return \WxPayApi::unifiedOrder($input);
    }*/
	
	
	/**
     *  获取会员充值订单
     */
    public function actionGetRechargeRecord(){
        $datas = [];
        $_mobile  = isset($_REQUEST['mobile'])  ? trim($_REQUEST['mobile']) : '';	          // 手机号

        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);
        $query = VipRechargeRecord::find()
            ->select([
                'vip_name'=>'{{%vip}}.client',
                'trade_no','total_fee','pay_way','trade_status','request_datetime'
            ])
            ->joinWith('vip',false)
            ->where([
                'vip_id'=>$vip_id,
            ]);
        $total = $query->count();
        //分页
        $size = isset($_REQUEST['rows']) && $_REQUEST['rows'] <= 50 ? intval($_REQUEST['rows']) : 10;
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $records = $query->orderBy('{{%vip_recharge_record}}.`id` desc')->offset(($page-1)*$size)->limit($size)->asArray()->all();
        if(empty($records)){
            $datas['error'] = 1;
            if($page == 1){
                $datas['msg'] = '没有找到任何充值记录！';
            }else{
                $datas['msg'] = '没有更多充值记录了！';
            }
        }else{
			// 以下部分是为了适应app需求所作处理：
			foreach($records as $k=>$record){
                $records[$k]['total_fee'] = '+'.$records[$k]['total_fee'];
				if($record['trade_status'] == 'wait_pay'){
					$records[$k]['trade_status_txt'] = '交易关闭';
				}elseif($record['trade_status'] == 'success'){
					$records[$k]['trade_status_txt'] = '支付完成';
				}
			}
			
            $datas['error'] = 0;
            $datas['msg'] = '获取充值记录成功！';
            $datas['data'] = $records;
            $datas['total'] = $total;
        }
		return json_encode($datas);
    }

}