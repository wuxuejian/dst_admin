<?php
namespace backend\controllers;
use yii;
use yii\web\Controller;
use backend\models\VipChargeRecord;
use backend\models\ChargeFrontmachine;
use backend\models\VipChargeRecordCount;
/**
 * yii内部计划任务委托脚本以http执行
 */
class PlanningTaskController extends Controller{
    /**
     * app异常的订单自动结算
     * planning-task/app-charge-count
     */
    public function actionAppChargeCount(){
        set_time_limit(0);
        //echo '<pre>';
        $logFile = '../runtime/app_charge_count.log';
        $connRes = ChargeFrontmachine::connect();
        if(!$connRes[0]){
            //无法链接到前置机
            $log = date('Y-m-d H:i:s')."  无法链接到前置机数据库！\r\n";
            file_put_contents($logFile,$log,FILE_APPEND);
            return;
        }
        //查询X天前本系统结算的订单
        $countRecord = VipChargeRecordCount::find()
            ->select(['fm_end_id'])
            ->where([
                '>=',
                'count_datetime',
                date('Y-m-d H:i:s',strtotime('-1 day'))
            ])->asArray()->all();
        if($countRecord){
            $countRecord = array_column($countRecord,'fm_end_id');
        }else{
            $countRecord = [];
        }
        //查询X天前充电记录检查是否有漏结算订单
        $endRecords = (new \yii\db\Query())
            ->select([
                '`ID`','`DEV_ID`','DEAL_NO','`INNER_ID`','`START_CARD_NO`',
                '`REMAIN_BEFORE_DEAL`','`REMAIN_AFTER_DEAL`','DEAL_START_DATE'
            ])->from('charge_record')
            ->andWhere(['>=','TIME_TAG',date('Y-m-d H:i:s',strtotime('-1 day'))])
            ->andWhere('`START_CARD_NO` like "999%"')
            ->andWhere(['`DEAL_TYPE`'=>[1,2]])
            ->andFilterWhere(['not in','ID',$countRecord])
            //->createCommand()->sql;
            ->all($connRes[1]);
        //var_dump($endRecords);
        //die;
        if(!$endRecords){
            //无法链接到前置机
            $log = date('Y-m-d H:i:s')."  没有未支付的APP充电记录！\r\n";
            file_put_contents($logFile,$log,FILE_APPEND);
            return;
        }
        foreach($endRecords as $val){
            //检查开始记录是否已经结束
            $startRecord = (new \yii\db\Query())
                ->select(['`ID`'])->from('charge_record')
                ->andWhere(['<','ID',$val['ID']])
                ->andWhere(['DEV_ID'=>$val['DEV_ID']])
                ->andWhere(['DEAL_NO'=>$val['DEAL_NO']])
                ->andWhere(['INNER_ID'=>$val['INNER_ID']])
                ->andWhere(['START_CARD_NO'=>$val['START_CARD_NO']])
                ->andWhere(['DEAL_TYPE'=>0])
                //->createCommand()->sql;
                ->one($connRes[1]);
            //var_dump($endRecord);
            if($startRecord){
                //尝试查询app启动记录
                $appStartRecord = VipChargeRecord::find()
                    ->select(['id'])
                    ->where(['fm_id'=>1,'fm_start_id'=>$startRecord['ID']])
                    ->asArray()->one();
                //var_dump($appStartRecord);
                $vcrId = $appStartRecord ? $appStartRecord['id'] : 0;
                $fmStartId = $startRecord['ID'];
                
                //test
                $log = date('Y-m-d H:i:s')."  fmEndID:{$val['ID']},START_CARD_NO:{$val['START_CARD_NO']}，vcrId：{$vcrId}，fmStartId：{$fmStartId}，找到开始记录！\r\n";
                file_put_contents($logFile,$log,FILE_APPEND);
            }else{
                $vcrId = 0;
                $fmStartId = $val['ID'];
                
                //test
                $log = date('Y-m-d H:i:s')."  ID:{$val['ID']},START_CARD_NO:{$val['START_CARD_NO']}，未找到开始记录！\r\n";
                file_put_contents($logFile,$log,FILE_APPEND);
            }
            $vipId = intval(substr($val['START_CARD_NO'],3));
            $money = sprintf('%.2f',$val['REMAIN_BEFORE_DEAL'] - $val['REMAIN_AFTER_DEAL']);
            $vcrModel = new VipChargeRecord;
            if(!$vcrModel->payCharge($vcrId,$vipId,$money,1,$fmStartId,$val['ID'],$val['DEAL_NO'])){
                $log = date('Y-m-d H:i:s')."  ID:{$val['ID']},START_CARD_NO:{$val['START_CARD_NO']}，结算失败！\r\n";
                file_put_contents($logFile, $log, FILE_APPEND);
                //var_dump($val);
                //var_dump($vcrId,$vipId,$money,$val['ID'],$endRecord['ID']);
            }else{
                $log = date('Y-m-d H:i:s')."  ID:{$val['ID']},START_CARD_NO:{$val['START_CARD_NO']}，结算成功！\r\n";
                file_put_contents($logFile, $log, FILE_APPEND);
            }
        }
        $connRes[1]->close();
        $log = date('Y-m-d H:i:s')."  操作完成！\r\n\r\n";
        file_put_contents($logFile, $log, FILE_APPEND);
    }

}