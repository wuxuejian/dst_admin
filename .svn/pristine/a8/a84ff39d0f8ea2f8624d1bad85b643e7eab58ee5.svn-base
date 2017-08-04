<?php
namespace backend\models;
use yii;
use backend\models\VipChargeRecordCount;
class VipChargeRecord extends \common\models\VipChargeRecord
{

    // 关联【会员表】
    public function getVip()
    {
        return $this->hasOne(Vip::className(), ['id' => 'vip_id']);
    }    
	
	// 关联【电桩表】
    public function getCharger()
    {
        return $this->hasOne(ChargeSpots::className(), ['id' => 'pole_id']);
    }

    // 关联【充电结算表】
    public function getVipChargeRecordCount(){
        return $this->hasOne(VipChargeRecordCount::className(), ['vcr_id' => 'id']);
    }

    /**
     * 充电完成后支付
     * @param  int    $vcrId             充电订单记录id
     * @param  int    $vipId             充电会员id
     * @param  float  $money             充电消费总金额
     * @param  int    $fmId              前置机id
     * @param  int    $fmStartId         前置机开始记录id
     * @param  int    $fmEndId           前置机结束记录id
     * @param  string $fmDealNo          前置机交易流水号
     */
    public function payCharge($vcrId = 0,$vipId = 0,$money,$fmId,$fmStartId,$fmEndId,$fmDealNo){
        if($vcrId){
            $chargeRecord = self::find()
                ->select(['id','vip_id','fm_id'])->where(['id'=>$vcrId])
                ->asArray()->one();
            self::updateAll([
                'end_status'=>'success',
                'end_datetime'=>date('Y-m-d H:i:s'),
                'fm_start_id'=>$fmStartId,
                'fm_end_id'=>$fmEndId,
            ],['id'=>$vcrId]);
            //如果充电记录id参数vipId,fmId将被重新赋值
            $vipId = $chargeRecord['vip_id'];
            $fmId = $chargeRecord['fm_id'];
            //$res1 = self::updateAll(['pay_status'=>'success'],['id'=>$vcrId]);
        }
        
	        //开启事务
	        //fm_id、fm_start_id两个字段组成唯一键（事务核心点）防止重复结算
	        $transaction = yii::$app->db->beginTransaction();
	        $vipChargeRecordCount = new VipChargeRecordCount;
	        $vipChargeRecordCount->vip_id = $vipId;
	        $vipChargeRecordCount->vcr_id = $vcrId;
	        $vipChargeRecordCount->fm_id = $fmId;
	        $vipChargeRecordCount->fm_start_id = $fmStartId;
	        $vipChargeRecordCount->fm_end_id = $fmEndId;
	        $vipChargeRecordCount->fm_deal_no = $fmDealNo;
	        $vipChargeRecordCount->money = $money;
	        $vipChargeRecordCount->count_datetime = date('Y-m-d H:i:s');
	        
	        try{
	        	$res1 = $vipChargeRecordCount->save(false);//添加结算记录
	        }catch (\Exception $e){
	        	$res1 = false;
	        }
	        
	        //如果结算金额为0则不改变账号金额、不添加资金变动记录
	        if($money > 0){
	            $res2 = Vip::updateAllCounters(['money_acount'=>0 - $money],['id'=>$vipId]);
	            //添加日志
	            $VMCModel = new VipMoneyChange;
	            $VMCModel->vip_id = $vipId;
	            $VMCModel->change_money = 0 - $money;
	            $VMCModel->reason = '充电支付';
	            $VMCModel->systime = time();
	            $res3 = $VMCModel->save(false);
	        }else{
	            //金额为0强制true
	            $res2 = true;
	            $res3 = true;
	        }
	        if($res1 && $res2 && $res3){
	            $transaction->commit();//提交事务
	            return true;
	        }else{
	            $transaction->rollback();//回滚事务
	            return false;
	        }
    }

    /**
     * 检测用户是否有没有支付的充电记录如果有则完成支付并扣款
     * @param string  $mobile 用户手机号
     * @param boolean 返回false否则返回true
     */
    /*public function noPayRecordExam($mobile){
        $vipInfo = Vip::find()
            ->select(['id'])
            ->where(['mobile'=>$mobile])
            ->asArray()->one();
        if(!$vipInfo){
            return true;
        }
        //查询未支付的订单
        $nopayVcr = self::find()
            ->select([
                '{{%vip_charge_record}}.`id`',
                '{{%vip_charge_record}}.`pole_id`',
                '{{%vip_charge_record}}.`write_datetime`',
                '{{%charge_spots}}.`fm_id`',
                '{{%charge_spots}}.`logic_addr`',
            ])->joinWith('charger',false)
            ->where([
                '{{%vip_charge_record}}.`vip_id`'=>$vipInfo['id'],
                '{{%vip_charge_record}}.`start_status`'=>'success',
                '{{%vip_charge_record}}.`pay_status`'=>'wait_pay',
            ])->orderBy('{{%vip_charge_record}}.`id` desc')
            ->asArray()->one();
        if(!$nopayVcr){
            //没有未支付订单
            return true;
        }
        //查询改记录的上一次成功结算的充电记录的交易流水号
        $lastpayVcr = self::find()
            ->select(['fm_charge_no'])
            ->where('`id` < '.$nopayVcr['id'].' and `pay_status` = "success"')
            ->orderBy('`id` desc')->asArray()->one();
        //链接到前置机
        $connRes = ChargeFrontmachine::connect($nopayVcr['fm_id']);
        if(!$connRes[0]){
            //无法链接到前置机
            return false;
        }
        //查询结束充电记录
        $endRecord = (new \yii\db\Query())
            ->select([
                'DEAL_NO',
                'REMAIN_BEFORE_DEAL',
                'REMAIN_AFTER_DEAL'
            ])->from('charge_record')
            ->where('`DEAL_END_DATE` > :DEAL_END_DATE and `END_CARD_NO` = :END_CARD_NO and (`DEAL_TYPE` = 1 or `DEAL_TYPE` = 2) ',[
                'DEAL_END_DATE'=>$nopayVcr['write_datetime'],
                'END_CARD_NO'=>'999'.str_pad($vipInfo['id'],13,'0',STR_PAD_LEFT),
            ])->orderby('`WRITE_TIME` desc')->one($connRes[1]);
            //->createCommand()->getRawSql();
        //关闭前置机数据库链接
        $connRes[1]->close();
        if(!$endRecord){
            //无法找到结束记录（可能正在充电）
            return false;
        }
        $money = sprintf('%.2f',$endRecord['REMAIN_BEFORE_DEAL'] - $endRecord['REMAIN_AFTER_DEAL']);
        if(!$this->payCharge($nopayVcr['id'],$money,$endRecord['DEAL_NO'])){
            //支付失败
            return false;
        }
        return true;
    }*/

}