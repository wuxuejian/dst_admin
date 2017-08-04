<?php
/**
 * 电桩充电记录 控制器
 * time 2015-12-30 14:29 
 * @author chengwk
 */
namespace backend\modules\polemonitor\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ChargeSpots;
use backend\models\ChargeFrontmachine;
use common\models\Excel;

class ChargeRecordController extends BaseController
{
    /**
     * 访问‘查看电桩充电记录’视图
     */
    public function actionIndex(){
        return $this->render('index',[
            'buttons'=>$this->getCurrentActionBtn(),
            'defaultChargerId'=>ChargeSpots::getDefaultChargerId()
        ]);
    }

    /**
     * 获取查某个电桩的充电记录列表
     */
    public function actionGetList()
    {
		// 以要查询的电桩ID去查出对应前置机id和逻辑地址
        $chargerId = intval(yii::$app->request->get('chargerId'));
        if ($chargerId < 1) {
            $chargerId = ChargeSpots::getDefaultChargerId();
        }
		$charger = ChargeSpots::find()
			->select(['id','fm_id','logic_addr'])
			->where(['id'=>$chargerId,'is_del'=>0])
			->asArray()->one();
        if (empty($charger)) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>'找不到对应的电桩！']);
        }
        // 连接对应前置机数据库,并查询充电记录
        $logic_addr = $charger['logic_addr'];
        $fm_id = $charger['fm_id'];
        $connectArr = ChargeFrontmachine::connect($fm_id);
        if (!$connectArr[0]) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>$connectArr[1]]);
        }
        $fmConnection = $connectArr[1];
        //由逻辑地址关联电桩的设备ID，查询充电记录
        $query = (new \yii\db\Query())
            ->select([
                'cr.*',
                'cp.DEV_ADDR'
            ])
            ->from('charge_record cr')
            ->join('LEFT JOIN', 'charge_pole cp', 'cp.DEV_ID=cr.DEV_ID')
            ->where(['cp.DEV_ADDR' => $logic_addr]);
        //////其他查询条件begin
        $query->andFilterWhere(['LIKE', 'cr.`DEAL_NO`', yii::$app->request->get('DEAL_NO')]);
        $query->andFilterWhere(['LIKE', 'cr.`START_CARD_NO`', yii::$app->request->get('START_CARD_NO')]);
        //开始充电时间
        $query->andFilterWhere(['>=', 'cr.`DEAL_START_DATE`', yii::$app->request->get('DEAL_START_DATE_start')]);
        //结束充电时间
        if (yii::$app->request->get('DEAL_START_DATE_end')) {
            $query->andFilterWhere(['<=', 'cr.`DEAL_START_DATE`', yii::$app->request->get('DEAL_START_DATE_end') . ' 23:59:59']);
        }
        //////其他查询条件end
        //总数
        $total = $query->count('cr.DEV_ID', $fmConnection);
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        //排序
        if(yii::$app->request->get('sort')){
            $field = yii::$app->request->get('sort');		//field
            $direction = yii::$app->request->get('order');  //asc or desc
            $orderStr = $field.' '.$direction;
        }else{
            $orderStr = 'TIME_TAG desc';
        }
        $chargeRecords = $query->orderBy($orderStr)->offset($pages->offset)->limit($pages->limit)->all($fmConnection);
        $returnArr['rows'] = $chargeRecords;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }


    /**
     * 导出Excel
     */
    public function actionExportGridData()
    {
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'charge_record',
            'subject'=>'charge_record',
            'description'=>'charge_record',
            'keywords'=>'charge_record',
            'category'=>'charge_record'
        ]);
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'设备ID','font-weight'=>true,'width'=>'8'],
                ['content'=>'交易流水号','font-weight'=>true,'width'=>'10'],
                ['content'=>'数据时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'逻辑地址','font-weight'=>true,'width'=>'10'],
                ['content'=>'交易类型','font-weight'=>true,'width'=>'10'],
                ['content'=>'地区代码','font-weight'=>true,'width'=>'8'],
                ['content'=>'开始卡号','font-weight'=>true,'width'=>'15'],
                ['content'=>'结束卡号','font-weight'=>true,'width'=>'15'],
                ['content'=>'开始交易电量(度)','font-weight'=>true,'width'=>'15'],
                ['content'=>'结束交易电量(度)','font-weight'=>true,'width'=>'15'],
                ['content'=>'交易费率1电价(元)','font-weight'=>true,'width'=>'15'],
                ['content'=>'停车费单价(元/小时)','font-weight'=>true,'width'=>'15'],
                ['content'=>'交易开始时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'交易结束时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'停车费(元)','font-weight'=>true,'width'=>'10'],
                ['content'=>'交易前余额(元)','font-weight'=>true,'width'=>'10'],
                ['content'=>'交易后余额(元)','font-weight'=>true,'width'=>'10'],
                ['content'=>'终端号','font-weight'=>true,'width'=>'10'],
                ['content'=>'卡版本号','font-weight'=>true,'width'=>'8'],
                ['content'=>'POS机号','font-weight'=>true,'width'=>'8'],
                ['content'=>'卡状态码','font-weight'=>true,'width'=>'8'],
                ['content'=>'写库时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'车号','font-weight'=>true,'width'=>'8'],
                ['content'=>'测量点','font-weight'=>true,'width'=>'8']
            ]
        ];
        //---向excel添加表头----------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }

        // 要查的字段，与导出的excel表头对应
        // 以要查询的电桩ID去查出对应前置机id和逻辑地址
        set_time_limit(0);
        $chargerId = intval(yii::$app->request->get('chargerId'));
        if ($chargerId < 1) {
            $chargerId = ChargeSpots::getDefaultChargerId();
        }
        $charger = ChargeSpots::find()
            ->select(['id','fm_id','logic_addr'])
            ->where(['id'=>$chargerId,'is_del'=>0])
            ->asArray()->one();
        if (empty($charger)) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>'找不到对应的电桩！']);
        }
        // 连接对应前置机数据库,并查询充电记录
        $logic_addr = $charger['logic_addr'];
        $fm_id = $charger['fm_id'];
        $connectArr = ChargeFrontmachine::connect($fm_id);
        if (!$connectArr[0]) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>$connectArr[1]]);
        }
        $fmConnection = $connectArr[1];
        //由逻辑地址关联电桩的设备ID，查询充电记录
        $query = (new \yii\db\Query())
            ->select([
                'cr.DEV_ID',
                'cr.DEAL_NO',
                'cr.TIME_TAG',
                'cp.DEV_ADDR',
                'cr.DEAL_TYPE',
                'cr.AREA_CODE',
                'cr.START_CARD_NO',
                'cr.END_CARD_NO',
                'cr.START_DEAL_DL',
                'cr.END_DEAL_DL',
                'cr.DEAL_R1_PRICE',
                'cr.STOP_FEE_PRICE',
                'cr.DEAL_START_DATE',
                'cr.DEAL_END_DATE',
                'cr.STOP_FEE',
                'cr.REMAIN_BEFORE_DEAL',
                'cr.REMAIN_AFTER_DEAL',
                'cr.TRM_NO',
                'cr.CARD_VER_NO',
                'cr.POS_NO',
                'cr.CARD_STATUS',
                'cr.WRITE_TIME',
                'cr.CAR_NO',
                'cr.INNER_ID',
            ])
            ->from('charge_record cr')
            ->join('LEFT JOIN', 'charge_pole cp', 'cp.DEV_ID=cr.DEV_ID')
            ->where(['cp.DEV_ADDR' => $logic_addr]);
        //////其他查询条件begin
        $query->andFilterWhere(['LIKE', 'cr.`DEAL_NO`', yii::$app->request->get('DEAL_NO')]);
        $query->andFilterWhere(['LIKE', 'cr.`START_CARD_NO`', yii::$app->request->get('START_CARD_NO')]);
        //开始充电时间
        $query->andFilterWhere(['>=', 'cr.`DEAL_START_DATE`', yii::$app->request->get('DEAL_START_DATE_start')]);
        //结束充电时间
        if (yii::$app->request->get('DEAL_START_DATE_end')) {
            $query->andFilterWhere(['<=', 'cr.`DEAL_START_DATE`', yii::$app->request->get('DEAL_START_DATE_end') . ' 23:59:59']);
        }
        //////其他查询条件end
        //总数
        $total = $query->count('cr.DEV_ID', $fmConnection);
        $data = $query->orderBy('cr.TIME_TAG desc')->all($fmConnection);
        //print_r($data);exit;
        if($data){
            //---向excel添加具体数据-------
            foreach($data as $item){
                $item['START_CARD_NO'] = ' '.$item['START_CARD_NO'];
                $item['END_CARD_NO']   = ' '.$item['END_CARD_NO'];
                $lineData = [];
                foreach($item as $k=>$v) {
                    if(!is_array($v)){
                        $lineData[] = ['content'=>$v];
                    }
                }
                $excel->addLineToExcel($lineData);
            }
            unset($data);
        }

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','电桩充电记录导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }


    /**
     * 获取某电站内电桩的充电记录
     */
    public function actionGetChargeRecords()
    {
        $stationId = intval(yii::$app->request->get('stationId'));      // 电站ID
        $chargerCode = yii::$app->request->get('code_from_compony');    // 电桩编号
        $logicAddr = yii::$app->request->get('logic_addr');             // 电桩逻辑地址
        // 优先根据某一个具体的电桩编号或逻辑地址去查出该电桩
        if($chargerCode || $logicAddr){
            $res = ChargeSpots::find()
                ->select(['id','fm_id','logic_addr'])
                ->where(['is_del'=>0])
                ->andFilterWhere(['like','code_from_compony',yii::$app->request->get('code_from_compony')])
                ->andFilterWhere(['like','logic_addr',yii::$app->request->get('logic_addr')])
                ->asArray()->all();
        }elseif($stationId){
            // 先查出该电站内的所有电桩
            $res = ChargeSpots::find()
                ->select(['id','fm_id','logic_addr'])
                ->where(['is_del'=>0,'station_id'=>$stationId])
                ->asArray()->all();
        }
        if (!isset($res) || empty($res)) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>'找不到对应的电桩！']);
        }
        // 连接对应前置机数据库,并查询充电记录
        $logicAddr_arr = array_unique(array_column($res,'logic_addr'));
        $fm_id = $res[0]['fm_id'];
        $connectArr = ChargeFrontmachine::connect($fm_id);
        if (!$connectArr[0]) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>$connectArr[1]]);
        }
        $fmConnection = $connectArr[1];
        // 1.由逻辑地址查出设备ID
        $chargePoles = (new \yii\db\Query())
            ->select(['DEV_ID','DEV_ADDR'])
            ->from('charge_pole')
            ->where(['DEV_ADDR' => $logicAddr_arr])
            ->all($fmConnection);
        // 2.由设备ID查询充电记录
        $query = (new \yii\db\Query())
            ->select(['charge_record.*'])
            ->from('charge_record')
            ->where(['DEV_ID' => array_unique(array_column($chargePoles,'DEV_ID'))]);
        $total = $query->count('DEV_ID', $fmConnection);
        // 分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        //排序
        if(yii::$app->request->get('sort')){
            $field = yii::$app->request->get('sort');		//field
            $direction = yii::$app->request->get('order');  //asc or desc
            $orderStr = $field.' '.$direction;
        }else{
            $orderStr = 'TIME_TAG desc';
        }
        $chargeRecords = $query->orderBy($orderStr)->offset($pages->offset)->limit($pages->limit)->all($fmConnection);
        $returnArr['rows'] = $chargeRecords;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }




}