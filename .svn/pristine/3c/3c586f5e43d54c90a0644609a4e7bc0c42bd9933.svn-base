<?php
namespace backend\models;
use yii;
class VipRechargeRecord extends \common\models\VipRechargeRecord{

    // 关联【会员表】
    public function getVip()
    {
        return $this->hasOne(Vip::className(), ['id' => 'vip_id']);
    }

    /**
     * 支付成功后回调
     * @param string $trade_no           本站订单号
     * @param int    $gmtCreateDatetime  订单创建时间
     * @param int    $gmtPaymentDatetime 订单支付时间
     * @param string $reasion            资金变动原因
     * @param string $platform_trade_no  支付平台交易号
     */
    public function rechargeSuccess($trade_no,$gmtCreateDatetime = 0,$gmtPaymentDatetime,$reasion,$platform_trade_no){
        $rechargeRecordInfo = self::find()
            ->select(['id','vip_id','total_fee'])
            ->where(['trade_no'=>$trade_no,'trade_status'=>'wait_pay'])
            ->asArray()->one();
        if(!$rechargeRecordInfo){
            return false;
        }
        $vipInfo = Vip::find()->select(['money_acount'])
            ->where(['id'=>$rechargeRecordInfo['vip_id']])->asArray()->one();
        $transaction = yii::$app->db->beginTransaction();
        //修改订单状态
        $res1 = self::updateAll(['trade_status'=>'success'],['id'=>$rechargeRecordInfo['id']]);
        //修改订单附加信息
        $orderUpdateData = [];
        $orderUpdateData['last_notify_datetime'] = time();
        $orderUpdateData['gmt_payment_datetime'] = $gmtPaymentDatetime;
        if($gmtCreateDatetime > 0){
            $orderUpdateData['gmt_create_datetime'] = $gmtCreateDatetime;
        }
        $orderUpdateData['platform_trade_no'] = $platform_trade_no;
        self::updateAll($orderUpdateData,['id'=>$rechargeRecordInfo['id']]);
        //会员增加资金
        $res2 = Vip::updateAll(['money_acount'=>$vipInfo['money_acount'] + $rechargeRecordInfo['total_fee']],['id'=>$rechargeRecordInfo['vip_id']]);
        //记录资金变动日志
        $vipMoneyChangeModel = new VipMoneyChange();
        $vipMoneyChangeModel->vip_id = $rechargeRecordInfo['vip_id'];
        $vipMoneyChangeModel->change_money = $rechargeRecordInfo['total_fee'];
        $vipMoneyChangeModel->reason = $reasion;
        $vipMoneyChangeModel->systime = time();
        $res3 = $vipMoneyChangeModel->save(false);
        //记录资金变动日志结束
        if($res1 && $res2 && $res3){
            $transaction->commit();
            return true;
        }else{
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 根据充电记录订单号到前置机结算订单
     */
    /*public function settlementRechargeOrder($id){
        //查询结束充电结束记录（正常停机或异常停机）
        $endRecord = (new \yii\db\Query())
            ->select(['START_DEAL_DL','END_DEAL_DL','REMAIN_BEFORE_DEAL','REMAIN_AFTER_DEAL','DEAL_START_DATE','DEAL_END_DATE'])
            ->from('charge_record')
            ->where('`DEV_ID` = :DEV_ID and `INNER_ID` = :INNER_ID and (`DEAL_TYPE` = 1 or `DEAL_TYPE` = 2) and `START_CARD_NO` = :START_CARD_NO and `DEAL_NO` = :DEAL_NO',[
                'DEV_ID'=>$fmPoleInfo['DEV_ID'],
                'INNER_ID'=>$vcrInfo['measuring_point'],
                'START_CARD_NO'=>'999'.str_pad($vipInfo['id'],13,'0',STR_PAD_LEFT),
                'DEAL_NO'=>$startRecord['DEAL_NO'],
            ])->one($fmConnection);
    }*/

}
