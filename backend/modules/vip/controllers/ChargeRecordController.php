<?php
/**
 * @Desc:   会员充电记录控制器
 * @author: chengwk
 * @date:   2015-11-10
 */
namespace backend\modules\vip\controllers;
use yii;
use yii\data\Pagination;
use backend\controllers\BaseController;
use backend\models\VipChargeRecord;
use backend\models\ChargeSpots;
use backend\models\ChargeFrontmachine;
use backend\models\VipChargeRecordCount;
use common\models\Excel;
use backend\classes\UserLog;

class ChargeRecordController extends BaseController{

    public function actionIndex(){
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取充电启停记录列表
     */
    public function actionGetList(){
        $query = VipChargeRecord::find()
            ->select([
                '{{%vip_charge_record}}.number',
                '{{%vip_charge_record}}.measuring_point',
                '{{%vip_charge_record}}.write_datetime',
                '{{%vip_charge_record}}.start_status',
                '{{%vip_charge_record}}.start_fail_reason',
                '{{%vip_charge_record}}.end_datetime',
                '{{%vip_charge_record}}.end_status',
                'vip_id'=>'{{%vip}}.`id`',
                'vip_code'=>'{{%vip}}.`code`',
                'vip_mobile'=>'{{%vip}}.`mobile`',
                '{{%charge_spots}}.`logic_addr`',
                '{{%vip_charge_record_count}}.`money`',
                '{{%vip_charge_record_count}}.`count_datetime`',
            ])
            ->joinWith('vip',false)
            ->joinWith('charger',false)
            ->joinWith('vipChargeRecordCount',false);
        //查询条件
        $query->andFilterWhere(['like','{{%vip_charge_record}}.`number`',yii::$app->request->get('number')]);
        $query->andFilterWhere(['like','CONCAT("999",LPAD({{%vip}}.`id`,13,0))',yii::$app->request->get('card_no')]);
        $query->andFilterWhere(['like','{{%vip}}.`mobile`',yii::$app->request->get('mobile')]);
        $query->andFilterWhere(['like','{{%charge_spots}}.`logic_addr`',yii::$app->request->get('logic_addr')]);
        $query->andFilterWhere(['=','{{%vip_charge_record}}.`start_status`',yii::$app->request->get('start_status')]);
        $query->andFilterWhere(['=','{{%vip_charge_record}}.`end_status`',yii::$app->request->get('end_status')]);
        $query->andFilterWhere(['like','{{%vip_charge_record}}.`platform_trade_no`',yii::$app->request->get('platform_trade_no')]);
        $query->andFilterWhere(['=','{{%vip_charge_record}}.`pay_status`',yii::$app->request->get('pay_status')]);
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'vip_code':
                    $orderBy = '{{%vip}}.`code` ';
                    break;
                case 'vip_mobile':
                    $orderBy = '{{%vip}}.`mobile` ';
                    break;
                case 'logic_addr':
                    $orderBy = '{{%charge_spots}}.`logic_addr` ';
                    break;
                default:
                    $orderBy = '{{%vip_charge_record}}.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = '{{%vip_charge_record}}.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $query2 = clone $query; // 底部合计用
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy($orderBy)->asArray()->all();
        
        if($data){
            foreach($data as &$crItem){
                $crItem['card_no'] = '999'.str_pad($crItem['vip_id'],13,'0',STR_PAD_LEFT);
            }
        }
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        // 表格底部增加合计行
        $mySum = $query2->select(['c_amount'=>'SUM(c_amount)'])->asArray()->one();
        $returnArr['footer'] = [[
            'number'=>'合计：',
            'c_amount'=>$mySum['c_amount']
        ]];
        return json_encode($returnArr);
    }

    /**
     * 导出Excel
     */
    public function actionExportGridData()
    {
        // 要查的字段，与导出的excel表头对应
        $selectArr = [
            '{{%vip_charge_record}}.number',
            'vip_id'=>'{{%vip}}.id',
            '{{%vip}}.mobile',
            '{{%charge_spots}}.logic_addr',
            '{{%vip_charge_record}}.measuring_point',
            '{{%vip_charge_record}}.write_datetime',
            '{{%vip_charge_record}}.start_status',
            '{{%vip_charge_record}}.start_fail_reason',
            '{{%vip_charge_record}}.end_datetime',
            '{{%vip_charge_record}}.end_status',
            '{{%vip_charge_record_count}}.`money`',
            '{{%vip_charge_record_count}}.`count_datetime`'
        ];

        $query = VipChargeRecord::find()
            ->select($selectArr)
            ->joinWith('vip',false)
            ->joinWith('charger',false)
            ->joinWith('vipChargeRecordCount',false);
        //查询条件
        $query->andFilterWhere(['like','{{%vip_charge_record}}.`number`',yii::$app->request->get('number')]);
        $query->andFilterWhere(['like','CONCAT("999",LPAD({{%vip}}.`id`,13,0))',yii::$app->request->get('card_no')]);
        $query->andFilterWhere(['like','{{%vip}}.`mobile`',yii::$app->request->get('mobile')]);
        $query->andFilterWhere(['like','{{%charge_spots}}.`logic_addr`',yii::$app->request->get('logic_addr')]);
        $query->andFilterWhere(['=','{{%vip_charge_record}}.`start_status`',yii::$app->request->get('start_status')]);
        $query->andFilterWhere(['=','{{%vip_charge_record}}.`end_status`',yii::$app->request->get('end_status')]);
        $query->andFilterWhere(['like','{{%vip_charge_record}}.`platform_trade_no`',yii::$app->request->get('platform_trade_no')]);
        $query->andFilterWhere(['=','{{%vip_charge_record}}.`pay_status`',yii::$app->request->get('pay_status')]);
        $data = $query->asArray()->all();
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'vip_charge_record',
            'subject'=>'vip_charge_record',
            'description'=>'vip_charge_record',
            'keywords'=>'vip_charge_record',
            'category'=>'vip_charge_record'
        ]);

        //---向excel添加表头-------------------------------------
        // 构建导出的excel表头
        $excHeaders = [
            ['content'=>'订单编号','font-weight'=>true,'width'=>'15'],
            ['content'=>'充电卡号','font-weight'=>true,'width'=>'20'],
            ['content'=>'会员手机号','font-weight'=>true,'width'=>'15'],
            ['content'=>'电桩逻辑地址','font-weight'=>true,'width'=>'15'],
            ['content'=>'测量点号','font-weight'=>true,'width'=>'10'],
            ['content'=>'请求时间','font-weight'=>true,'width'=>'20'],
            ['content'=>'启动状态','font-weight'=>true,'width'=>'10'],
            ['content'=>'启动失败原因','font-weight'=>true,'width'=>'25'],
            ['content'=>'停止时间','font-weight'=>true,'width'=>'20'],
            ['content'=>'停止状态','font-weight'=>true,'width'=>'10'],
            ['content'=>'消费金额','font-weight'=>true,'width'=>'15'],
            //['content'=>'支付状态','font-weight'=>true,'width'=>'15'],
            ['content'=>'结算时间','font-weight'=>true,'width'=>'20']
        ];
        $excel->addLineToExcel($excHeaders);
        //---向excel添加具体数据----------------------------------
        foreach($data as $item){
            $lineData = [];
            $item['vip_id'] = ' 999'.str_pad($item['vip_id'],13,'0',STR_PAD_LEFT);
            if($item['start_status'] == 'success'){
                $item['start_status'] = '成功';
            }elseif ($item['start_status'] == 'fail'){
                $item['start_status'] = '失败';
            }elseif ($item['start_status'] == 'timeout'){
                $item['start_status'] = '超时';
            }
            if($item['end_status'] == 'success'){
                $item['end_status'] = '成功';
            }elseif ($item['end_status'] == 'fail'){
                $item['end_status'] = '失败';
            }elseif ($item['end_status'] == 'timeout'){
                $item['end_status'] = '超时';
            }elseif ($item['end_status'] == 'noaction'){
                $item['end_status'] = '未操作';
            }
            //$item['pay_way'] = $item['pay_way'] == 'wechat' ? '微信' : ($item['pay_way'] == 'alipay' ? '支付宝' : $item['pay_way']);
            /*if($item['pay_type'] == 'wechatapp'){
                $item['pay_type'] = '微信APP';
            }elseif ($item['pay_type'] == 'alipayapp'){
                $item['pay_type'] = '支付宝APP';
            }elseif ($item['pay_type'] == 'ubalance'){
                $item['pay_type'] = '余额';
            }
            if($item['pay_status'] == 'success'){
                $item['pay_status'] = '成功';
            }else{
                $item['pay_status'] = '失败';
            }*/
            foreach($item as $k=>$v) {
                if(!is_array($v)){
                    $lineData[] = ['content'=>$v];
                }
            }
            $excel->addLineToExcel($lineData);
        }
        unset($data);

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','APP启停记录导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    /**
     * 异常充电记录处理
     * vip/charge-record/exception-do
     */
    public function actionExceptionDo(){
        if(yii::$app->request->isPost){
            $datas = [
                'error'=>1,
                'msg'=>'',
            ];
            //post请求
            $id = yii::$app->request->post('id');
            $vcrInfo = VipChargeRecord::find()
                ->select(['id'])->where([
                    'id'=>$id,
                    'start_status'=>'success',
                    'pay_status'=>'wait_pay',
                ])->one();
            if(!$vcrInfo){
                $datas['msg'] = '只有启动状态为成功，支付状态为等待支付的记录才能进行本操作！';
                return json_encode($datas);
            }
            $model = new VipChargeRecord;
            $cAmount = sprintf('%.2f',yii::$app->request->post('c_amount'));
            if($model->payCharge($id,$cAmount,'')){
                $datas['error'] = 0;
                $datas['msg'] = '操作成功，成功处理该记录！';
            }else{
                $datas['msg'] = '操作失败！';
            }
            return json_encode($datas);
        }else{
            //get请求
            $id = yii::$app->request->get('id');
            $vcrInfo = VipChargeRecord::find()
                ->select([
                    'id',
                    'pole_id',
                    'measuring_point',
                    'write_datetime'
                ])->where(['id'=>$id])
                ->asArray()->one();
            if(!$vcrInfo){
                return;
            }
            return $this->render('exception-do',[
                'vcrInfo'=>$vcrInfo,
            ]);
        }
    }

    /**
     * 订单详细
     * vip/charge-record/detail
     */
    public function actionDetail(){
        $id = yii::$app->request->get('id');
        //---[1]查本地的充电记录-------------------
        $vcrInfo = VipChargeRecord::find()
            ->select([
                'number','{{%vip_charge_record}}.vip_id','pole_id','measuring_point',
                'write_datetime','start_status',
                'start_fail_reason','end_datetime','end_status',
                'c_amount','pay_status','fm_charge_no',
                'vip_mobile'=>'{{%vip}}.`mobile`',
                '{{%charge_spots}}.`logic_addr`',
                '{{%vip_charge_record_count}}.`money`',
                '{{%vip_charge_record_count}}.`count_datetime`'
            ])
            ->joinWith('vip',false)
            ->joinWith('charger',false)
            ->joinWith('vipChargeRecordCount',false)
            ->where(['{{%vip_charge_record}}.id'=>$id])
            ->asArray()->one();
        $vcrInfo['card_no'] = '999'.str_pad($vcrInfo['vip_id'],13,'0',STR_PAD_LEFT);
        if(!$vcrInfo){
            return '记录未找到！';
        }
        //print_r($vcrInfo);exit;
        //---[2]查前置机上对应的充电记录-----------------
        $connectArr = ChargeFrontmachine::connect();
        if (!$connectArr[0]) {
            return '前置机查询连接失败！';
        }
        $fmConnection = $connectArr[1];
        $query = (new \yii\db\Query())
            ->select([
                '`charge_record`.`DEV_ID`',
                '`charge_record`.`DEAL_NO`',
                '`charge_record`.`DEAL_TYPE`',
                '`charge_record`.`START_CARD_NO`',
                '`charge_record`.`START_DEAL_DL`',
                '`charge_record`.`END_DEAL_DL`',
                '`charge_record`.`REMAIN_BEFORE_DEAL`',
                '`charge_record`.`REMAIN_AFTER_DEAL`',
                '`charge_record`.`DEAL_START_DATE`',
                '`charge_record`.`DEAL_END_DATE`',
                '`charge_record`.`CAR_NO`',
                '`charge_record`.`INNER_ID`',
                '`charge_record`.`TIME_TAG`',
                '`CHARGE_POLE`.`DEV_ADDR`',
            ])
            ->from('charge_record')
            ->join('LEFT JOIN','CHARGE_POLE','CHARGE_POLE.`DEV_ID` = charge_record.`DEV_ID`');
        $fmRecord = [];
        if($vcrInfo['fm_charge_no'] && $vcrInfo['card_no'] && $vcrInfo['logic_addr']){
            $query->andWhere(['LIKE', '`charge_record`.`DEAL_NO`', $vcrInfo['fm_charge_no']]);
            $query->andWhere(['LIKE', '`charge_record`.`START_CARD_NO`', $vcrInfo['card_no']]);
            $query->andWhere(['LIKE', 'CHARGE_POLE.`DEV_ADDR`', $vcrInfo['logic_addr']]);
            $res = $query->orderBy('charge_record.`TIME_TAG` ASC')->all($fmConnection);
            //print_r($res);exit;
            if($res){
                //不论充电状态都使用最后一条记录即可
                $fmRecord = $res[count($res)-1];
                //查询本地电桩和电站
                $stationInfo = ChargeSpots::find()
                    ->select([
                        '{{%charge_spots}}.`logic_addr`',
                        '{{%charge_station}}.`cs_name`'
                    ])->joinWith('chargeStation',false)
                    ->where([
                        '{{%charge_spots}}.`logic_addr`'=>$vcrInfo['logic_addr']
                    ])
                    ->asArray()->one();
                $fmRecord['cs_name'] = $stationInfo['cs_name'];
                if($fmRecord['DEAL_TYPE'] == 0){
                    //正在充电记录处理
                    $fmRecord['DEAL_END_DATE'] = '';
                    $fmRecord['consume_DL'] = '0.00';
                    $fmRecord['consume_money'] = '0.00';
                }else{
                    //非正在充电记录处理
                    $fmRecord['consume_DL'] = $fmRecord['END_DEAL_DL'] - $fmRecord['START_DEAL_DL'];
                    $fmRecord['consume_DL'] = sprintf('%.2f',$fmRecord['consume_DL']);
                    $fmRecord['consume_money'] = $fmRecord['REMAIN_BEFORE_DEAL'] - $fmRecord['REMAIN_AFTER_DEAL'];
                    $fmRecord['consume_money'] = sprintf('%.2f',$fmRecord['consume_money']);
                }
            }
        }
        return $this->render('detail',[
            'vcrInfo'=>$vcrInfo,
            'fmRecord'=>$fmRecord
        ]);
    }


    /**
     * 获取充电记录
     * vip/charge-record/charge-record
     */
    public function actionChargeRecord(){
        $buttons = $this->getCurrentActionBtn();
        return $this->render('charge-record',[
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取充电记录列表数据
     * vip/charge-record/get-charge-list
     */
    public function actionGetChargeList(){
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
                    ->andWhere('START_CARD_NO LIKE "999%"')
                    ->andFilterWhere(['DEAL_NO'=>yii::$app->request->get('DEAL_NO')])
                    ->andFilterWhere(['like','START_CARD_NO',yii::$app->request->get('START_CARD_NO')])
                    ->andFilterWhere(['charge_pole.DEV_ADDR'=>yii::$app->request->get('DEV_ADDR')]);
                if(yii::$app->request->get('DEAL_START_DATE_start')){
                    $query->andWhere(['>=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_start').' 00:00:00']);
                }
                if(yii::$app->request->get('DEAL_START_DATE_end')){
                    $query->andWhere(['<=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_end').' 23:59:59']);
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
                    ->orderby('charge_record.ID desc')->all($connectRes[1]);
                break;
            default:
                //正在充电
                $subQuery = (new \yii\db\Query())->select([
                        'ID',
                        'ins'=>'concat(charge_record.`DEV_ID`,"-",charge_record.`DEAL_NO`,"-",charge_record.`START_CARD_NO`)',
                    ])->from('charge_record')
                    ->leftJoin('charge_pole','charge_pole.DEV_ID = charge_record.DEV_ID')
                    ->andWhere('START_CARD_NO LIKE "999%"')
                    ->andWhere(['>','DEAL_START_DATE',date('Y-m-d H:i:s',strtotime('-1 day'))])
                    ->andFilterWhere(['DEAL_NO'=>yii::$app->request->get('DEAL_NO')])
                    ->andFilterWhere(['like','START_CARD_NO',yii::$app->request->get('START_CARD_NO')])
                    ->andFilterWhere(['charge_pole.DEV_ADDR'=>yii::$app->request->get('DEV_ADDR')]);
                if(yii::$app->request->get('DEAL_START_DATE_start')){
                    $subQuery->andWhere(['>=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_start').' 00:00:00']);
                }
                if(yii::$app->request->get('DEAL_START_DATE_end')){
                    $subQuery->andWhere(['<=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_end').' 23:59:59']);
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
                unset($queryTmp);
                $data = $query->all($connectRes[1]);
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
                    ->orderby('charge_record.ID desc')->all($connectRes[1]);
                break;
        }
        if($rows){
            //查询本页面订单的结算信息
            $countRecord = VipChargeRecordCount::find()
                ->select(['fm_end_id'])
                ->where(['fm_id'=>$fmId,'fm_end_id'=>array_column($rows,'ID')])
                ->indexBy('fm_end_id')
                ->asArray()->all();
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
                    if($countRecord && isset($countRecord[$val['ID']])){
                        $rows[$key]['count_status'] = 1;
                    }
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


    /*
     * 异常处理-APP充电记录
     */
    public function actionHandleException(){
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            //print_r($formData);exit;
            $connectRes = ChargeFrontmachine::connect();
            if(!$connectRes[0]){
                return json_encode(['status'=>false,'info'=>$connectRes[1]]);
            }
            //查出开始充电记录，以便获取对应结束记录部分字段的值
            $startRec = (new \yii\db\Query())
                ->select(['*'])
                ->from('charge_record')
                ->where(['ID'=>$formData['ID']])
                ->one($connectRes[1]);
            $endRecArr = [];
            foreach($startRec as $k=>$v){
                if($k == 'ID'){
                   continue;
                }
                switch($k){
                    case 'DEAL_TYPE':
                        $endRecArr[$k] = 2; //类型标记为“结束异常”
                        break;
                    case 'TIME_TAG':
                    case 'WRITE_TIME':
                        $endRecArr[$k] = date('Y-m-d H:i:s'); //记录时间
                        break;
                    case 'END_DEAL_DL':
                        $endRecArr[$k] = $formData['END_DEAL_DL']; //结束电量
                        break;
                    case 'REMAIN_AFTER_DEAL':
                        $endRecArr[$k] = $formData['REMAIN_AFTER_DEAL']; //交易后余额
                        break;
                    case 'DEAL_END_DATE':
                        $endRecArr[$k] = $formData['DEAL_END_DATE']; //结束时间
                        break;
                    default:
                        $endRecArr[$k] = $v;
                }
            }
            //print_r($endRecArr);exit;
            //插入对应的结束充电记录
            $flag = $connectRes[1]
                ->createCommand()
                ->insert('charge_record',$endRecArr)
                ->execute();
            if(!$flag){
                $returnArr = ['status'=>false,'info'=>'异常处理失败！'];
            }else{
                $returnArr = ['status'=>true,'info'=>'异常处理成功！'];
                //添加日志
                UserLog::log('App充电记录异常处理（异常记录id：'.$formData['ID'].'）','sys');
            }
            return json_encode($returnArr);
        }else{
            $ID = yii::$app->request->get('ID',0); //前置机上的充电记录id
            if(!$ID){
                return json_encode(['status'=>false,'info'=>'参数ID缺失！']);
            }
            $connectRes = ChargeFrontmachine::connect();
            if(!$connectRes[0]){
                return json_encode(['status'=>false,'info'=>$connectRes[1]]);
            }
            $rec = (new \yii\db\Query())
                ->select([
                    'ID','DEAL_NO','START_CARD_NO',
                    'START_DEAL_DL','END_DEAL_DL',
                    'REMAIN_BEFORE_DEAL','REMAIN_AFTER_DEAL',
                    'DEAL_START_DATE','DEAL_END_DATE',
                    'DEV_ID','INNER_ID'
                ])
                ->from('charge_record')
                ->where(['ID'=>$ID])
                ->one($connectRes[1]);
            //print_r($rec);
            return $this->render('handleExceptionWin',[
                'recInfo'=>$rec
            ]);
        }
    }

    /**
     * 计费计量监控
     * vip/charge-record/pole-monitor
     */
    public function actionPoleMonitor(){
        $datas = [
            'rows'=>[],
            'total'=>0
        ];
        $DEV_ID = yii::$app->request->get('DEV_ID');
        $INNER_ID = yii::$app->request->get('INNER_ID');
        $DEAL_START_DATE = yii::$app->request->get('DEAL_START_DATE');
        if(!$DEV_ID || !$INNER_ID || !$DEAL_START_DATE){
            return json_encode($datas);
        }
        //连接前置机数据库
        $fmConnection = ChargeFrontmachine::connect();
        if(!$fmConnection[0]){
            return json_encode($datas);
        }
        $query = (new \yii\db\Query())
            ->select([
                'charging.`TIME_TAG`',
                'charging.`INNER_ID`',
                'charging.`COSUM_AMOUNT`',
                'charging.`CHARGE_AMOUNT`',
                'charging.`SOC`',
                'charging.`CAR_NO`',
                'charging.`WRITE_TIME`'
            ])
            ->from('charging')
            ->where('charging.`DEV_ID` = :DEV_ID
                    and charging.`INNER_ID` = :INNER_ID
                    and charging.`TIME_TAG` >= :TIME_TAG',
                [
                    'DEV_ID'=>$DEV_ID,
                    'INNER_ID'=>$INNER_ID,
                    'TIME_TAG'=>$DEAL_START_DATE
                ]
            );
        //限制计量计费记录的结束时间--begin--
        //查出该电桩的该电枪的下一次充电开始时间
        $nextRec = (new \yii\db\Query())
            ->select(['ID','DEAL_NO','DEAL_START_DATE'])
            ->from('charge_record')
            ->where('DEV_ID = :DEV_ID and INNER_ID = :INNER_ID  and DEAL_START_DATE > :DEAL_START_DATE',
                [
                    'DEV_ID'=>$DEV_ID,
                    'INNER_ID'=>$INNER_ID,
                    'DEAL_START_DATE'=>$DEAL_START_DATE
                ]
            )
            ->groupBy('DEAL_START_DATE') //以开始时间为准
            ->orderBy('DEAL_START_DATE ASC')
            ->one($fmConnection[1]);
        if($nextRec && $nextRec['DEAL_START_DATE']){
            $query->andWhere('charging.`TIME_TAG` < :TIME_TAG_end',['TIME_TAG_end'=>$nextRec['DEAL_START_DATE']]);
        }
        //限制计量计费记录的结束时间--end--
        $total = $query->count('*',$fmConnection[1]);
        ////排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            $orderBy = 'charging.`'.$sortColumn.'` ';
        }else{
            $orderBy = 'charging.`TIME_TAG` ';
        }
        $orderBy .= $sortType;
        ////排序结束
        //查询指定页数据
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $rows = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->all($fmConnection[1]);
        $datas['rows'] = $rows;
        $datas['total'] = $total;
        return json_encode($datas);
    }


}