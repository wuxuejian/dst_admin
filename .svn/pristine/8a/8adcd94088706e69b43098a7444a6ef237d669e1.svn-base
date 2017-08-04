<?php
/**
 * 提醒控制器
 */
namespace backend\modules\interfaces\controllers;
use backend\models\Vip;
use backend\models\VipChargeRecord;
use backend\models\ChargeAppointment;
use backend\models\VipNotice;
use backend\models\VipSuggestion;
class TipController extends BaseController{
    public function actionIndex(){
        $datas = [];
        $datas['error'] = 0;
        $datas['msg'] = '';
        $datas['data'] = [];
        //会员
        $mobile = $_REQUEST['mobile'];
        $vipId = Vip::getVipIdByPhoneNumber($mobile);
        $datas['data']['charging'] = $this->chargingRecord($vipId);
        $datas['data']['notSettled'] = $this->notSettled($vipId);
        $datas['data']['weekAppointmentForms'] = $this->weekAppointmentForms($vipId);
        $datas['data']['lastNotice'] = $this->lastNotice();
        $datas['data']['lastResponse'] = $this->lastResponse($vipId);
        return json_encode($datas);
    }

    /**
     * 检测是否有正在进行充电的记录
     */
    protected function chargingRecord($vipId){
        $hasRecord = VipChargeRecord::find()
            ->select(['vcr_id'=>'id','pole_id','measuring_point'])
            ->where([
                'vip_id'=>$vipId,
                'start_status'=>'success',
                'end_status'=>'noaction',
                'pay_status'=>'wait_pay',
            ])->orderBy('`write_datetime` desc')->asArray()->one();
        if($hasRecord){
            return $hasRecord;
        }else{
            return ['vcr_id'=>'','pole_id'=>'','measuring_point'=>''];
        }
    }

    /**
     * 未结算的记录
     */
    protected function notSettled($vipId){
        $hasRecord = VipChargeRecord::find()
            ->select(['id'])
            ->where([
                'vip_id'=>$vipId,'start_status'=>'success',
                'end_status'=>'noaction'
            ])->orderBy('`write_datetime` desc')->asArray()->one();
        return $hasRecord ? true : false;
    }

    /**
     * 返回最近一周的充电预约单
     */
    protected function weekAppointmentForms($vipId){
        $hasRecord = ChargeAppointment::find()
            ->select(['id'])->where([
                'and',
                ['vip_id'=>$vipId],
                ['isfinished'=>0],
                ['between','appointed_date',date('Y-m-d'),date('Y-m-d',strtotime('+7 days'))]
            ])->all();
        return $hasRecord ? true : false;
    }

    /**
     * 获取最新一条通知
     */
    protected function lastNotice(){
        return VipNotice::find()
            ->select(['vn_id','vn_public_time'])
            ->orderBy('vn_id DESC')
            ->asArray()->one();
    }

    /**
     * 获取最新的回复时间
     */
    protected function lastResponse($vipId){
        $lastResponse = VipSuggestion::find()
            ->select(['vs_respond_time'])
            ->where([
                'vs_vip_id'=>$vipId,
                'vs_is_del'=>0
            ])->orderBy('`vs_respond_time` desc')
            ->asArray()->one();
        if($lastResponse && $lastResponse['vs_respond_time']){
            return $lastResponse['vs_respond_time'];
        }else{
            return '';
        }
    }
}