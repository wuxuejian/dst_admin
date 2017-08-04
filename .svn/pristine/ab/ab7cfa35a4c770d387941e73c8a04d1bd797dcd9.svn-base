<?php
/**
 * 充电记录控制器
 */
namespace backend\modules\charge\controllers;
use backend\controllers\BaseController;
use backend\models\ChargeFrontmachine;
use backend\models\ChargeSpots;
use common\models\Excel;
use yii;
use yii\data\Pagination;
class RechargeRecordController extends BaseController
{
    
    /**
     * 获取充电列表
     */
    public function actionList(){
        $buttons = $this->getCurrentActionBtn();
        return $this->render('list',[
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取充电列表数据
     */
    public function actionGetListData(){
        $returnArr = [
            'rows'=>[],
            'total'=>0,
        ];
        $fmId = yii::$app->request->get('fm_id');
        $fmId = $fmId ? $fmId : 1;
        $dealType = yii::$app->request->get('DEAL_TYPE');
        $connectRes = ChargeFrontmachine::connect($fmId);
        if(!$connectRes[0]){
            return json_encode($returnArr);
        }
        //排序
        $column = yii::$app->request->get('sort');
        $sort = yii::$app->request->get('order','desc');
        if($column){
            switch($column){
                case 'DEV_ADDR':
                    $orderStr = 'charge_pole.DEV_ADDR';
                    break;
                default:
                    $orderStr = 'charge_record.' . $column;
            }
            $orderStr .= ' ' . $sort;
        }else{
            $orderStr = 'charge_record.ID DESC';
        }
        //分页
        $size = isset($_REQUEST['rows']) && $_REQUEST['rows'] <= 50 ? intval($_REQUEST['rows']) : 10;
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        switch ($dealType) {
            case 1:
            case 2:
                //结束
                $query = (new \yii\db\Query())
                    ->from('charge_record')
                    ->select(['charge_record.*','charge_pole.DEV_ADDR'])
                    ->leftJoin('charge_pole','charge_pole.DEV_ID = charge_record.DEV_ID')
                    ->andWhere(['charge_record.DEAL_TYPE'=>[1,2]])
                    ->andFilterWhere(['DEAL_NO'=>yii::$app->request->get('DEAL_NO')])
                    ->andFilterWhere(['like','START_CARD_NO',yii::$app->request->get('START_CARD_NO')])
                    ->andFilterWhere(['charge_pole.DEV_ADDR'=>yii::$app->request->get('DEV_ADDR')]);
                if(yii::$app->request->get('DEAL_START_DATE_start')){ //充电时间
                    $query->andWhere(['>=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_start').' 00:00:00']);
                }
                if(yii::$app->request->get('DEAL_START_DATE_end')){
                    $query->andWhere(['<=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_end').' 23:59:59']);
                }
                if(yii::$app->request->get('TIME_TAG_start')){ //记录时间
                    $query->andWhere(['>=','TIME_TAG',yii::$app->request->get('TIME_TAG_start').' 00:00:00']);
                }
                if(yii::$app->request->get('TIME_TAG_end')){
                    $query->andWhere(['<=','TIME_TAG',yii::$app->request->get('TIME_TAG_end').' 23:59:59']);
                }
                if(yii::$app->request->get('cs_id')){
                    $conditionChargePole = ChargeSpots::find()
                        ->select(['logic_addr'])
                        ->where(['is_del'=>0,'station_id'=>yii::$app->request->get('cs_id')])
                        ->asArray()->all();
                    if($conditionChargePole){
                        $conditionChargePole = array_column($conditionChargePole,'logic_addr');
                    }else{
                        $conditionChargePole = [];
                    }
                    $query->andWhere(['charge_pole.DEV_ADDR'=>$conditionChargePole]);
                }
                $total = $query->count('*',$connectRes[1]);
                $rows = $query->offset(($page-1)*$size)->limit($size)
                    ->orderby($orderStr)->all($connectRes[1]);
                break;
            default:
                //正在充电
                $subQuery = (new \yii\db\Query())->select([
                        'ID',
                        'ins'=>'concat(charge_record.`DEV_ID`,"-",charge_record.`DEAL_NO`,"-",charge_record.`START_CARD_NO`)',
                    ])->from('charge_record')
                    ->leftJoin('charge_pole','charge_pole.DEV_ID = charge_record.DEV_ID')
                    ->andWhere(['>','TIME_TAG',date('Y-m-d H:i:s',strtotime('-1 day'))])
                    ->andFilterWhere(['DEAL_NO'=>yii::$app->request->get('DEAL_NO')])
                    ->andFilterWhere(['like','START_CARD_NO',yii::$app->request->get('START_CARD_NO')])
                    ->andFilterWhere(['charge_pole.DEV_ADDR'=>yii::$app->request->get('DEV_ADDR')]);
                if(yii::$app->request->get('DEAL_START_DATE_start')){ //充电时间
                    $subQuery->andWhere(['>=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_start').' 00:00:00']);
                }
                if(yii::$app->request->get('DEAL_START_DATE_end')){
                    $subQuery->andWhere(['<=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_end').' 23:59:59']);
                }
                if(yii::$app->request->get('TIME_TAG_start')){ //记录时间
                    $subQuery->andWhere(['>=','TIME_TAG',yii::$app->request->get('TIME_TAG_start').' 00:00:00']);
                }
                if(yii::$app->request->get('TIME_TAG_end')){
                    $subQuery->andWhere(['<=','TIME_TAG',yii::$app->request->get('TIME_TAG_end').' 23:59:59']);
                }
                if(yii::$app->request->get('cs_id')){
                    $conditionChargePole = ChargeSpots::find()
                        ->select(['logic_addr'])
                        ->where(['is_del'=>0,'station_id'=>yii::$app->request->get('cs_id')])
                        ->asArray()->all();
                    if($conditionChargePole){
                        $conditionChargePole = array_column($conditionChargePole,'logic_addr');
                    }else{
                        $conditionChargePole = [];
                    }
                    $subQuery->andWhere(['charge_pole.DEV_ADDR'=>$conditionChargePole]);
                }
                $chargingRecord = (new \yii\db\Query())->select([
                        'ID'=>'min(ID)',
                        'ins',
                        'num'=>'count(ins)',
                    ])->from(['subTable'=>$subQuery])
                    ->groupBy('ins')
                    ->having('num < 2')
                    ->all($connectRes[1]);
                if(!$chargingRecord){
                    return json_encode($returnArr);
                }
                unset($subQuery);
                /*foreach($chargingRecord as $val){
                    $val['ins'] = explode('-',$val['ins']);
                    if(!isset($query)){
                        $query = (new \yii\db\Query())
                            ->select(['ID'])
                            ->from('charge_record')
                            ->where([
                                'DEV_ID'=>$val['ins'][0],
                                'DEAL_NO'=>$val['ins'][1],
                                'START_CARD_NO'=>$val['ins'][2],
                            ]);
                    }else{
                        $queryTmp = (new \yii\db\Query())
                            ->select(['ID'])
                            ->from('charge_record')
                            ->where([
                                'DEV_ID'=>$val['ins'][0],
                                'DEAL_NO'=>$val['ins'][1],
                                'START_CARD_NO'=>$val['ins'][2],
                            ]);
                        $query->union($queryTmp);
                    }
                }
                unset($queryTmp);*/
                /*$data = $query->all($connectRes[1]);
                if($data){
                    $chargingIds = array_column($data,'ID');
                }else{
                    $chargingIds = [];
                }
                unset($data);*/
                $chargingIds = array_column($chargingRecord,'ID');
                $query = (new \yii\db\Query())
                    ->from('charge_record')
                    ->select(['charge_record.*','charge_pole.DEV_ADDR'])
                    ->leftJoin('charge_pole','charge_pole.DEV_ID = charge_record.DEV_ID')
                    ->andWhere(['charge_record.ID'=>$chargingIds])
                    ->andWhere(['charge_record.DEAL_TYPE'=>0]);
                $total = $query->count('*',$connectRes[1]);
                $rows = $query->offset(($page-1)*$size)->limit($size)
                    ->orderby($orderStr)->all($connectRes[1]);
                break;
        }
        if($rows){
            //查询本页面订单的结算信息
            /*$countRecord = VipChargeRecordCount::find()
                ->select(['fm_end_id'])
                ->where(['fm_id'=>$fmId,'fm_end_id'=>array_column($rows,'ID')])
                ->indexBy('fm_end_id')
                ->asArray()->all();*/
            //查询本页面数据所属电桩信息
            $chargeSpotsInfo = ChargeSpots::find()
                ->select([
                    '{{%charge_spots}}.`charge_type`',
                    '{{%charge_spots}}.`logic_addr`',
                    '{{%charge_station}}.`cs_name`',
                ])->joinWith('chargeStation',false)
                ->where([
                    '{{%charge_spots}}.`is_del`'=>0,
                    '{{%charge_spots}}.`logic_addr`'=>array_unique(array_column($rows,'DEV_ADDR')),
                ])->indexBy('logic_addr')->asArray()->all();
            //查询本页数据电站信息
            foreach($rows as $key=>$val){
                if($dealType != 0){
                    $rows[$key]['c_amount'] = sprintf('%.2f',$val['REMAIN_BEFORE_DEAL'] - $val['REMAIN_AFTER_DEAL']);
                    $rows[$key]['c_dl'] = sprintf('%.2f',$val['END_DEAL_DL'] - $val['START_DEAL_DL']);
                    /*if($countRecord && isset($countRecord[$val['ID']])){
                        $rows[$key]['count_status'] = 1;
                    }*/
                }
                if($chargeSpotsInfo && isset($chargeSpotsInfo[$val['DEV_ADDR']])){
                    $rows[$key]['cs_name'] = $chargeSpotsInfo[$val['DEV_ADDR']]['cs_name'];
                    $rows[$key]['gun_name'] = ChargeSpots::getGunNameWithMP($chargeSpotsInfo[$val['DEV_ADDR']]['charge_type'],$val['INNER_ID']);
                }
            }
        }
        $returnArr['total'] = $total;
        $returnArr['rows'] = $rows;
        return json_encode($returnArr);
    }

    /**
     * 充电记录导出
     */
    public function actionExport(){
        set_time_limit(300);
        $returnArr = [
            'rows'=>[],
            'total'=>0,
        ];
        $fmId = yii::$app->request->get('fm_id');
        $fmId = $fmId ? $fmId : 1;
        $dealType = yii::$app->request->get('DEAL_TYPE');
        $connectRes = ChargeFrontmachine::connect($fmId);
        if(!$connectRes[0]){
            return json_encode($returnArr);
        }
        /*//分页
        $size = isset($_REQUEST['rows']) && $_REQUEST['rows'] <= 50 ? intval($_REQUEST['rows']) : 10;
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;*/
        switch ($dealType) {
            case 1:
            case 2:
                //结束
                $query = (new \yii\db\Query())
                    ->from('charge_record')
                    ->select(['charge_record.*','charge_pole.DEV_ADDR'])
                    ->leftJoin('charge_pole','charge_pole.DEV_ID = charge_record.DEV_ID')
                    ->andWhere(['charge_record.DEAL_TYPE'=>[1,2]])
                    ->andFilterWhere(['DEAL_NO'=>yii::$app->request->get('DEAL_NO')])
                    ->andFilterWhere(['like','START_CARD_NO',yii::$app->request->get('START_CARD_NO')])
                    ->andFilterWhere(['charge_pole.DEV_ADDR'=>yii::$app->request->get('DEV_ADDR')]);
                if(yii::$app->request->get('DEAL_START_DATE_start')){ //充电时间
                    $query->andWhere(['>=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_start').' 00:00:00']);
                }
                if(yii::$app->request->get('DEAL_START_DATE_end')){
                    $query->andWhere(['<=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_end').' 23:59:59']);
                }
                if(yii::$app->request->get('TIME_TAG_start')){ //记录时间
                    $query->andWhere(['>=','TIME_TAG',yii::$app->request->get('TIME_TAG_start').' 00:00:00']);
                }
                if(yii::$app->request->get('TIME_TAG_end')){
                    $query->andWhere(['<=','TIME_TAG',yii::$app->request->get('TIME_TAG_end').' 23:59:59']);
                }
                if(yii::$app->request->get('cs_id')){
                    $conditionChargePole = ChargeSpots::find()
                        ->select(['logic_addr'])
                        ->where(['is_del'=>0,'station_id'=>yii::$app->request->get('cs_id')])
                        ->asArray()->all();
                    if($conditionChargePole){
                        $conditionChargePole = array_column($conditionChargePole,'logic_addr');
                    }else{
                        $conditionChargePole = [];
                    }
                    $query->andWhere(['charge_pole.DEV_ADDR'=>$conditionChargePole]);
                }
                $total = $query->count('*',$connectRes[1]);
                if($total > 10000){
                    return '<script>alert("数据超过10000条请分段导出！");<script>';
                }
                $rows = $query->orderby('charge_record.ID desc')->all($connectRes[1]);
                break;
            default:
                //正在充电
                $subQuery = (new \yii\db\Query())->select([
                        'ins'=>'concat(charge_record.`DEV_ID`,"-",charge_record.`DEAL_NO`,"-",charge_record.`START_CARD_NO`)',
                    ])->from('charge_record')
                    ->leftJoin('charge_pole','charge_pole.DEV_ID = charge_record.DEV_ID')
                    ->andWhere(['>','DEAL_START_DATE',date('Y-m-d H:i:s',strtotime('-3 day'))])
                    ->andFilterWhere(['DEAL_NO'=>yii::$app->request->get('DEAL_NO')])
                    ->andFilterWhere(['like','START_CARD_NO',yii::$app->request->get('START_CARD_NO')])
                    ->andFilterWhere(['charge_pole.DEV_ADDR'=>yii::$app->request->get('DEV_ADDR')]);
                if(yii::$app->request->get('DEAL_START_DATE_start')){ //充电时间
                    $subQuery->andWhere(['>=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_start').' 00:00:00']);
                }
                if(yii::$app->request->get('DEAL_START_DATE_end')){
                    $subQuery->andWhere(['<=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_end').' 23:59:59']);
                }
                if(yii::$app->request->get('TIME_TAG_start')){ //记录时间
                    $subQuery->andWhere(['>=','TIME_TAG',yii::$app->request->get('TIME_TAG_start').' 00:00:00']);
                }
                if(yii::$app->request->get('TIME_TAG_end')){
                    $subQuery->andWhere(['<=','TIME_TAG',yii::$app->request->get('TIME_TAG_end').' 23:59:59']);
                }
                if(yii::$app->request->get('cs_id')){
                    $conditionChargePole = ChargeSpots::find()
                        ->select(['logic_addr'])
                        ->where(['is_del'=>0,'station_id'=>yii::$app->request->get('cs_id')])
                        ->asArray()->all();
                    if($conditionChargePole){
                        $conditionChargePole = array_column($conditionChargePole,'logic_addr');
                    }else{
                        $conditionChargePole = [];
                    }
                    $subQuery->andWhere(['charge_pole.DEV_ADDR'=>$conditionChargePole]);
                }
                $chargingRecord = (new \yii\db\Query())->select([
                        'ins',
                        'num'=>'count(ins)',
                    ])->from(['subTable'=>$subQuery])
                    ->groupBy('ins')
                    ->having('num < 2')
                    ->all($connectRes[1]);
                if(!$chargingRecord){
                    return json_encode($returnArr);
                }
                unset($subQuery);
                foreach($chargingRecord as $val){
                    $val['ins'] = explode('-',$val['ins']);
                    if(!isset($query)){
                        $query = (new \yii\db\Query())
                            ->select(['ID'])
                            ->from('charge_record')
                            ->where([
                                'DEV_ID'=>$val['ins'][0],
                                'DEAL_NO'=>$val['ins'][1],
                                'START_CARD_NO'=>$val['ins'][2],
                            ]);
                    }else{
                        $queryTmp = (new \yii\db\Query())
                            ->select(['ID'])
                            ->from('charge_record')
                            ->where([
                                'DEV_ID'=>$val['ins'][0],
                                'DEAL_NO'=>$val['ins'][1],
                                'START_CARD_NO'=>$val['ins'][2],
                            ]);
                        $query->union($queryTmp);
                    }
                }
                unset($queryTmp);
                $data = $query->all($connectRes[1]);
                if($data){
                    $chargingIds = array_column($data,'ID');
                }else{
                    $chargingIds = [];
                }
                unset($data);
                $query = (new \yii\db\Query())
                    ->from('charge_record')
                    ->select(['charge_record.*','charge_pole.DEV_ADDR'])
                    ->leftJoin('charge_pole','charge_pole.DEV_ID = charge_record.DEV_ID')
                    ->andWhere(['charge_record.ID'=>$chargingIds])
                    ->andWhere(['charge_record.DEAL_TYPE'=>0]);
                $total = $query->count('*',$connectRes[1]);
                if($total > 10000){
                    return '<script>alert("数据超过10000条请分段导出！");<script>';
                }
                $rows = $query->orderby('charge_record.ID desc')
                    ->all($connectRes[1]);
                break;
        }
        if($rows){
            //查询本页面订单的结算信息
            /*$countRecord = VipChargeRecordCount::find()
                ->select(['fm_end_id'])
                ->where(['fm_id'=>$fmId,'fm_end_id'=>array_column($rows,'ID')])
                ->indexBy('fm_end_id')
                ->asArray()->all();*/
            //查询本页面数据所属电桩信息
            $chargeSpotsInfo = ChargeSpots::find()
                ->select([
                    '{{%charge_spots}}.`charge_type`',
                    '{{%charge_spots}}.`logic_addr`',
                    '{{%charge_station}}.`cs_name`',
                ])->joinWith('chargeStation',false)
                ->where([
                    '{{%charge_spots}}.`is_del`'=>0,
                    '{{%charge_spots}}.`logic_addr`'=>array_unique(array_column($rows,'DEV_ADDR')),
                ])->indexBy('logic_addr')->asArray()->all();
            //查询本页数据电站信息
            foreach($rows as $key=>$val){
                if($dealType != 0){
                    $rows[$key]['c_amount'] = sprintf('%.2f',$val['REMAIN_BEFORE_DEAL'] - $val['REMAIN_AFTER_DEAL']);
                    $rows[$key]['c_dl'] = sprintf('%.2f',$val['END_DEAL_DL'] - $val['START_DEAL_DL']);
                    /*if($countRecord && isset($countRecord[$val['ID']])){
                        $rows[$key]['count_status'] = 1;
                    }*/
                }
                if($chargeSpotsInfo && isset($chargeSpotsInfo[$val['DEV_ADDR']])){
                    $rows[$key]['cs_name'] = $chargeSpotsInfo[$val['DEV_ADDR']]['cs_name'];
                    $rows[$key]['gun_name'] = ChargeSpots::getGunNameWithMP($chargeSpotsInfo[$val['DEV_ADDR']]['charge_type'],$val['INNER_ID']);
                }
            }
        }
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'charge_card',
            'subject'=>'charge_card',
            'description'=>'charge_card',
            'keywords'=>'charge_card',
            'category'=>'charge_card'
        ]);
        $lineIndex = [
            'DEAL_NO'=>'交易流水号',
            'START_CARD_NO'=>'开始卡号',
            'DEAL_TYPE'=>'状态',
            'cs_name'=>'充电站',
            'DEV_ADDR'=>'充电桩',
            'START_DEAL_DL'=>'开始电量(度)',
            'END_DEAL_DL'=>'结束电量(度)',
            'c_dl'=>'消费电量(度)',
            'REMAIN_BEFORE_DEAL'=>'交易前余额(元)',
            'REMAIN_AFTER_DEAL'=>'交易后余额(元)',
            'c_amount'=>'消费金额(元)',
            'DEAL_START_DATE'=>'开始时间',
            'DEAL_END_DATE'=>'结束时间',
            'CAR_NO'=>'车号',
            'INNER_ID'=>'测量点',
            'gun_name'=>'电枪',
            'TIME_TAG'=>'记录时间',
        ];
        // 构建导出的excel表头
        $lineData = [];
        foreach ($lineIndex as $value) {
            $lineData[] = ['content'=>$value];
        }
        //---向excel添加表头----------
        $excel->addLineToExcel($lineData);
        //---向excel添加具体数据-------
        foreach($rows as $item){
            $item['START_CARD_NO'] = ' '.$item['START_CARD_NO'];
            switch ($item['DEAL_TYPE']) {
                case 0:
                    $item['DEAL_TYPE'] = '正在充电';
                    break;
                case 1:
                    $item['DEAL_TYPE'] = '结束正常';
                    break;
                default:
                    $item['DEAL_TYPE'] = '结束异常';
                    break;
            }
            $lineData = [];
            foreach ($lineIndex as $key=>$value) {
                if(isset($item[$key])){
                    $lineData[] = ['content'=>$item[$key]];
                }else{
                    $lineData[] = ['content'=>''];
                }
                
            }
            $excel->addLineToExcel($lineData);
        }
        unset($rows);

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','充电记录.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}