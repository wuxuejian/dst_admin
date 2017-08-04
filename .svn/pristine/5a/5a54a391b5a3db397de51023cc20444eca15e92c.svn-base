<?php
/**
 * 会员账户余额 控制器
 */
namespace backend\modules\interfaces\controllers;
use backend\models\Vip;
use backend\models\VipMoneyChange;
use backend\models\VipChargeRecordCount;

class VipAccountController extends BaseController{
	
    /**
     *  @获取会员账户余额
     */
    public function actionGetVipAccount(){
        $datas = [];
        $_mobile  = isset($_REQUEST['mobile'])  ? trim($_REQUEST['mobile']) : '';	         // 手机号

        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);
		
        $vip = Vip::find()->select(['id','code','client','money_acount'])->where(['id'=>$vip_id])->asArray()->one();
        if(empty($vip)){
            $datas['error'] = 1;
            $datas['msg'] = '找不到该会员！';
        }else{
            $datas['error'] = 0;
            $datas['msg'] = '获取账户余额成功！';
            $datas['data'] = $vip;
        }
		return json_encode($datas);
    }
	
	
    /**
     *  @获取会员账户变动记录
     */
    public function actionGetMoneyChangeRecord(){
        $datas = [];
        $_mobile  = isset($_REQUEST['mobile'])  ? trim($_REQUEST['mobile']) : '';	         // 手机号

        $vip_id = Vip::getVipIdByPhoneNumber($_mobile);

        $query = VipMoneyChange::find()
            ->select([
                'vip_name'=>'{{%vip}}.client',
                '{{%vip_money_change}}.change_money',
                '{{%vip_money_change}}.reason',
                '{{%vip_money_change}}.systime',
                '{{%vip_money_change}}.note'
            ])
            ->joinWith('vip',false)
            ->where(['vip_id'=>$vip_id]);
        $total = $query->count();
        //分页
        $size = isset($_REQUEST['rows']) && $_REQUEST['rows'] <= 50 ? intval($_REQUEST['rows']) : 10;
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $records = $query->offset(($page-1)*$size)->limit($size)
            ->orderBy('{{%vip_money_change}}.`id` desc')
            ->asArray()->all();
        if(empty($records)){
            $datas['error'] = 1;
            if($page == 1){
                $datas['msg'] = '没有找到任何账户变动记录！';
            }else{
                $datas['msg'] = '没有更多数据！';
            }
        }else{
			// 以下部分是为了适应app需求所作处理：
			foreach($records as $k=>$record){
                if($record['change_money'] >= 0){
                    $records[$k]['change_money'] = '+'.$record['change_money'];
                    $reasonAct = 'recharge';
                    $reasonRType = '';
                    if(strpos($records[$k]['reason'],'微信') !== false){
                        $reasonRType = 'wechat';
                    }elseif(strpos($records[$k]['reason'],'支付宝') !== false){
                        $reasonRType = 'alipay';
                    }
                }else{
                    $reasonAct = 'pay';
                    $reasonRType = '';
                }
                $records[$k]['reason_act'] = $reasonAct;
                $records[$k]['reason_rType'] = $reasonRType;
                $records[$k]['status'] = 'success';
                $records[$k]['status_txt'] = '成功';
			}
            $datas['error'] = 0;
            $datas['msg'] = '获取账户变动记录成功！';
            $datas['data'] = $records;
            $datas['total'] = $total;
        }
		return json_encode($datas);
    }

	
    /**
     * 充电支付记录
     * vip-account_charge-count-record
     */	
    public function actionChargeCountRecord(){
        $returnArr = [
            'error'=>0,
            'msg'=>'',
            'data'=>[],
            'total'=>0,
        ];
        $mobile  = isset($_REQUEST['mobile'])  ? trim($_REQUEST['mobile']) : '';
        $vipId = Vip::getVipIdByPhoneNumber($mobile);
        if(!$vipId){
            $returnArr['msg'] = '会员不存在！';
            return json_encode($returnArr);
        }
        $query = VipChargeRecordCount::find()
            ->select(['id','fm_deal_no','money','count_datetime'])
            ->where(['vip_id'=>$vipId]);
        $total = $query->count();
        //分页
        $size = isset($_REQUEST['rows']) && $_REQUEST['rows'] <= 50 ? intval($_REQUEST['rows']) : 10;
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $data = $query->offset(($page-1)*$size)->limit($size)
            ->orderBy('`id` desc')
            ->asArray()->all();
        if(empty($data)){
            $returnArr['error'] = 1;
            if($page == 1){
                $returnArr['msg'] = '没有找到充电支付记录！';
            }else{
                $returnArr['msg'] = '没有更多数据！';
            }
            return json_encode($returnArr);
        }
        $returnArr['error'] = 0;
        $returnArr['data'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }
	
}