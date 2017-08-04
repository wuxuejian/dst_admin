<?php
/**
 * @Desc:	充电记录 控制器
 * @author: chengwk
 * @date:	2016-02-19
 */
namespace backend\modules\card\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ConfigCategory;
use backend\models\ChargeFrontmachine;
use common\models\Excel;

class ChargeRecordController extends BaseController
{
    /**
     * 访问【IC电卡充电记录】菜单视图
     */
    public function actionIcRecords()
    {
		$configItems = [];
        $data['config'] = (new ConfigCategory())->getCategoryConfig($configItems,'value');
		$data['buttons'] = []; //$this->getCurrentActionBtn();
        return $this->render('icRecords',$data);
    }
    
    /**
     * 获取【IC电卡充电记录】菜单视图里的充电记录列表
     */
    public function actionGetIcRecords()
    {
        //连接前置机数据库，根据卡号查出IC卡充电记录（注意：卡号以999开头的是app充电记录，其他才为IC卡充电记录）
        $connectArr = ChargeFrontmachine::connect();
        if (!$connectArr[0]) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>$connectArr[1]]);
        }
        $fmConnection = $connectArr[1];

        //===判断是否指定要查询某“充电结束状态”的充电记录=============================
        $endStatus = yii::$app->request->get('end_status');
        //不限状态
        if(!$endStatus){
            //查DEAL_TYPE=0（开始充电）
            $query = (new \yii\db\Query())
                ->select([
                    'DEV_ID', 'DEAL_NO', 'START_CARD_NO', 'START_DEAL_DL',
                    'REMAIN_BEFORE_DEAL', 'DEAL_START_DATE', 'CAR_NO',
                    'INNER_ID', 'TIME_TAG'
                ])
                ->from('charge_record')
                ->where([
                    'and',
                    ['not like', 'END_CARD_NO', '999%', false],
                    ['DEAL_TYPE' => 0]
                ]);
            //查询条件
            $query->andFilterWhere(['LIKE', 'DEAL_NO', yii::$app->request->get('DEAL_NO')]);
            $query->andFilterWhere(['LIKE', 'START_CARD_NO', yii::$app->request->get('START_CARD_NO')]);
            $query->andFilterWhere(['>=', 'DEAL_START_DATE', yii::$app->request->get('DEAL_START_DATE_start')]); //开始充电时间
            if (yii::$app->request->get('DEAL_START_DATE_end')) { //结束充电时间
                $query->andFilterWhere(['<=', 'DEAL_START_DATE', yii::$app->request->get('DEAL_START_DATE_end') . ' 23:59:59']);
            }
            //查总数
            $total = $query->count('DEAL_NO', $fmConnection);
            //分页
            $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
            $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
            //排序
            if (yii::$app->request->get('sort')) {
                $field = yii::$app->request->get('sort');        //field
                $direction = yii::$app->request->get('order');  //asc or desc
                $orderStr = $field . ' ' . $direction;
            } else {
                $orderStr = 'TIME_TAG desc';
            }
            $query->orderBy($orderStr)->offset($pages->offset)->limit($pages->limit);
            //echo $query->createCommand()->sql;exit;
            $res = $query->all($fmConnection);
            if ($res) {
                //遍历查对应的充电结束记录
                foreach ($res as &$_CCRItem) {
                    $endRecord = (new \yii\db\Query())
                        ->select(['END_DEAL_DL', 'REMAIN_AFTER_DEAL', 'DEAL_END_DATE', 'DEAL_TYPE'])
                        ->from('charge_record')
                        ->where('`DEAL_NO` = "' . $_CCRItem['DEAL_NO'] . '" AND `END_CARD_NO` = "' . $_CCRItem['START_CARD_NO'] . '" AND (`DEAL_TYPE` = 1 or  `DEAL_TYPE` = 2) AND `INNER_ID` = ' . $_CCRItem['INNER_ID'])
                        ->one($fmConnection);
                    if ($endRecord) {
                        if ($endRecord['DEAL_TYPE'] == 1) {
                            $_CCRItem['end_status'] = 1; //表示'结束正常'
                        } elseif ($endRecord['DEAL_TYPE'] == 2) {
                            $_CCRItem['end_status'] = 2; //表示'结束异常'
                        }
                        $_CCRItem = array_merge($_CCRItem, $endRecord);
                        //计算消费电量和消费金额
                        $_CCRItem['consume_DL'] = number_format($_CCRItem['END_DEAL_DL'] - $_CCRItem['START_DEAL_DL'], 2);
                        $_CCRItem['consume_money'] = number_format($_CCRItem['REMAIN_BEFORE_DEAL'] - $_CCRItem['REMAIN_AFTER_DEAL'], 2);
                    } else {
                        $_CCRItem['end_status'] = 0; //表示'正在充电'
                    }
                }
            }
            $returnArr = [];
            $returnArr['rows'] = $res;
            $returnArr['total'] = $total;
            return json_encode($returnArr);
        }

        switch($endStatus){
            case '1': { //正在充电

                break;
            }
            case '2': { //结束正常
                $query = (new \yii\db\Query())
                    ->select([
                        'DEV_ID', 'DEAL_NO', 'START_CARD_NO', 'START_DEAL_DL',
                        'REMAIN_BEFORE_DEAL', 'DEAL_START_DATE', 'CAR_NO',
                        'INNER_ID', 'TIME_TAG',
                        'END_DEAL_DL', 'REMAIN_AFTER_DEAL', 'DEAL_END_DATE', 'DEAL_TYPE',
                        'end_status'=>'DEAL_TYPE',
                        'consume_DL'=>'(END_DEAL_DL - START_DEAL_DL)',
                        'consume_money'=>'(REMAIN_BEFORE_DEAL - REMAIN_AFTER_DEAL)'
                    ])
                    ->from('charge_record')
                    ->where([
                        'and',
                        ['not like', 'END_CARD_NO', '999%', false],
                        ['DEAL_TYPE' => 1]
                    ]);
                break;
            }
            case '3': { //结束异常
                $query = (new \yii\db\Query())
                    ->select([
                        'DEV_ID', 'DEAL_NO', 'START_CARD_NO', 'START_DEAL_DL',
                        'REMAIN_BEFORE_DEAL', 'DEAL_START_DATE', 'CAR_NO',
                        'INNER_ID', 'TIME_TAG',
                        'END_DEAL_DL', 'REMAIN_AFTER_DEAL', 'DEAL_END_DATE', 'DEAL_TYPE',
                        'end_status'=>'DEAL_TYPE',
                        'consume_DL'=>'(END_DEAL_DL - START_DEAL_DL)',
                        'consume_money'=>'(REMAIN_BEFORE_DEAL - REMAIN_AFTER_DEAL)'
                    ])
                    ->from('charge_record')
                    ->where([
                        'and',
                        ['not like', 'END_CARD_NO', '999%', false],
                        ['DEAL_TYPE' => 2]
                    ]);
                break;
            }
        }
        //查询条件
        $query->andFilterWhere(['LIKE', 'DEAL_NO', yii::$app->request->get('DEAL_NO')]);
        $query->andFilterWhere(['LIKE', 'START_CARD_NO', yii::$app->request->get('START_CARD_NO')]);
        $query->andFilterWhere(['>=', 'DEAL_START_DATE', yii::$app->request->get('DEAL_START_DATE_start')]); //开始充电时间
        if (yii::$app->request->get('DEAL_START_DATE_end')) { //结束充电时间
            $query->andFilterWhere(['<=', 'DEAL_START_DATE', yii::$app->request->get('DEAL_START_DATE_end') . ' 23:59:59']);
        }
        //查总数
        $total = $query->count('DEAL_NO', $fmConnection);
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        //排序
        if (yii::$app->request->get('sort')) {
            $field = yii::$app->request->get('sort');        //field
            $direction = yii::$app->request->get('order');  //asc or desc
            $orderStr = $field . ' ' . $direction;
        } else {
            $orderStr = 'TIME_TAG desc';
        }
        $query->orderBy($orderStr)->offset($pages->offset)->limit($pages->limit);
        //echo $query->createCommand()->sql;exit;
        $res = $query->all($fmConnection);

        $returnArr = [];
        $returnArr['rows'] = $res;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }



    /**
     * 导出Excel
     */
    public function actionExportGridData()
    {
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'交易流水号','font-weight'=>true,'width'=>'15'],
                ['content'=>'电卡编号','font-weight'=>true,'width'=>'25'],
                ['content'=>'状态','font-weight'=>true,'width'=>'10'],
                ['content'=>'开始电量(度)','font-weight'=>true,'width'=>'15'],
                ['content'=>'结束电量(度)','font-weight'=>true,'width'=>'15'],
                ['content'=>'消费电量(度)','font-weight'=>true,'width'=>'15'],
                ['content'=>'交易前余额(元)','font-weight'=>true,'width'=>'15'],
                ['content'=>'交易后余额(元)','font-weight'=>true,'width'=>'15'],
                ['content'=>'消费金额(元)','font-weight'=>true,'width'=>'15'],
                ['content'=>'开始时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'结束时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'车号','font-weight'=>true,'width'=>'10'],
                ['content'=>'测量点','font-weight'=>true,'width'=>'10'],
                ['content'=>'记录时间','font-weight'=>true,'width'=>'20']
            ]
        ];

        // 要查的字段，与导出的excel表头对应
        $selectArr = [
            'DEAL_NO',
            'START_CARD_NO',
            'end_status'=>'DEAL_TYPE', //状态
            'START_DEAL_DL',
            'END_DEAL_DL',
            'consume_DL'=>'END_DEAL_DL - START_DEAL_DL', //消费电量
            'REMAIN_BEFORE_DEAL',
            'REMAIN_AFTER_DEAL',
            'consume_money'=>'REMAIN_BEFORE_DEAL - REMAIN_AFTER_DEAL', //消费金额
            'DEAL_START_DATE',
            'DEAL_END_DATE',
            'CAR_NO',
            'INNER_ID',
            'TIME_TAG'
        ];

        //连接前置机数据库，根据卡号查出IC卡充电记录（注意：卡号以999开头的是app充电记录，其他才为IC卡充电记录）
        $connectArr = ChargeFrontmachine::connect();
        if (!$connectArr[0]) {
            return json_encode(['rows'=>[],'total'=>0,'errInfo'=>$connectArr[1]]);
        }
        $fmConnection = $connectArr[1];
        //===【1】先按DEAL_TYPE=0（开始充电）==============
        $query = (new \yii\db\Query())
            ->select([
                'DEAL_NO','START_CARD_NO','START_DEAL_DL',
                'REMAIN_BEFORE_DEAL','DEAL_START_DATE','CAR_NO',
                'INNER_ID','TIME_TAG'
            ])->from('charge_record')
            ->where([
                'and',
                ['not like','END_CARD_NO','999%',false],
                ['DEAL_TYPE'=>0]
            ]);
        //查询条件
        $query->andFilterWhere(['LIKE','DEAL_NO',yii::$app->request->get('DEAL_NO')]);
        $query->andFilterWhere(['LIKE','START_CARD_NO',yii::$app->request->get('START_CARD_NO')]);
        $query->andFilterWhere(['>=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_start')]); //开始充电时间
        if(yii::$app->request->get('DEAL_START_DATE_end')){ //结束充电时间
            $query->andFilterWhere(['<=','DEAL_START_DATE',yii::$app->request->get('DEAL_START_DATE_end').' 23:59:59']);
        }
        //查总数
        $total = $query->count('DEAL_NO', $fmConnection);
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
        $query->orderBy($orderStr)->offset($pages->offset)->limit($pages->limit);
        //echo $query->createCommand()->sql;exit;
        $res = $query->all($fmConnection);
        if($res){
            //遍历查对应的充电结束记录
            foreach($res as &$_CCRItem){
                $endRecord = (new \yii\db\Query())
                    ->select(['END_DEAL_DL','REMAIN_AFTER_DEAL','DEAL_END_DATE','DEAL_TYPE'])
                    ->from('charge_record')
                    ->where('`DEAL_NO` = "'.$_CCRItem['DEAL_NO'].'" AND `END_CARD_NO` = "'.$_CCRItem['START_CARD_NO'].'" AND (`DEAL_TYPE` = 1 or  `DEAL_TYPE` = 2) AND `INNER_ID` = '.$_CCRItem['INNER_ID'])
                    ->one($fmConnection);
                if($endRecord){
                    if($endRecord['DEAL_TYPE'] == 1){
                        $_CCRItem['end_status'] = 1; //表示'结束正常'
                    }elseif($endRecord['DEAL_TYPE'] == 2){
                        $_CCRItem['end_status'] = 2; //表示'结束异常'
                    }
                    $_CCRItem = array_merge($_CCRItem,$endRecord);
                    //计算消费电量和消费金额
                    $_CCRItem['consume_DL'] = number_format($_CCRItem['END_DEAL_DL'] - $_CCRItem['START_DEAL_DL'],2);
                    $_CCRItem['consume_money'] = number_format($_CCRItem['REMAIN_BEFORE_DEAL'] - $_CCRItem['REMAIN_AFTER_DEAL'],2);
                }else{
                    $_CCRItem['end_status'] = 0; //表示'正在充电'
                }
            }
        }
        //print_r($res);exit;

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

        //---向excel添加表头-------------------------------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }

        //---向excel添加具体数据----------------------------------
        foreach($res as $item){
            $lineData = [];
            $item['end_status'] = $item['end_status'] == 0 ? '正在充电' : ($item['end_status']==1 ? '结束正常' : '结束异常');
            foreach($selectArr as $field){ //遍历按指定字段顺序读取数据
                if(isset($item[$field])){
                    if($field != 'START_CARD_NO') {
                        $lineData[] = ['content' =>$item[$field]];
                    }else{
                        $lineData[] = ['content'=>"'".$item[$field]];
                    }
                }else{
                    $lineData[] = ['content'=>''];
                }
            }
            $excel->addLineToExcel($lineData);
        }
        unset($res);

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','IC电卡充电记录.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }


    /**
     * 获取指定充电卡的异常充电记录
     */
    public function actionExceptionCharge(){
        $cardNo = yii::$app->request->get('cardNo');
        if(!$cardNo){
            echo json_encode(['rows'=>'','total'=>0]);
            return;
        }
        //连接前置机数据库，根据卡号查出IC卡充电记录（注意：卡号以999开头的是app充电记录，其他才为IC卡充电记录）
        $fmConnection = $this->connectFrontMachineDbByFmId();
        if(!is_object($fmConnection)){
            echo json_encode(['rows'=>[],'total'=>0,'errInfo'=>$fmConnection]);
            return;
        }
        //===【1】先按DEAL_TYPE=0（开始充电）==============
        $query = (new \yii\db\Query())
            ->select([
                'DEAL_NO','START_CARD_NO','START_DEAL_DL',
                'REMAIN_BEFORE_DEAL','DEAL_START_DATE','CAR_NO',
                'INNER_ID','TIME_TAG',
                'END_DEAL_DL','REMAIN_AFTER_DEAL','DEAL_END_DATE','DEAL_TYPE'
            ])->from('charge_record')
            ->where(
                '`END_CARD_NO` = :END_CARD_NO and `DEAL_TYPE` = 2',
                ['END_CARD_NO'=>$cardNo]
            );
        //查总数
        $total = $query->count('DEAL_NO', $fmConnection);
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        //排序
        if(yii::$app->request->get('sort')){
            $field = yii::$app->request->get('sort');       //field
            $direction = yii::$app->request->get('order');  //asc or desc
            $orderStr = $field.' '.$direction;
        }else{
            $orderStr = 'TIME_TAG desc';
        }
        $query->orderBy($orderStr)->offset($pages->offset)->limit($pages->limit);
        //echo $query->createCommand()->sql;exit;
        $res = $query->all($fmConnection);
        if($res){
            foreach($res as &$_controlerExceptionChargeItem){
                $_controlerExceptionChargeItem['end_status'] =  $_controlerExceptionChargeItem['DEAL_TYPE'];
                //计算电量
                $_controlerExceptionChargeItem['consume_DL']
                = $_controlerExceptionChargeItem['END_DEAL_DL'] - $_controlerExceptionChargeItem['START_DEAL_DL'];
                $_controlerExceptionChargeItem['consume_DL'] = sprintf('%.2f',$_controlerExceptionChargeItem['consume_DL']);
                //计算金额
                $_controlerExceptionChargeItem['consume_money']
                = $_controlerExceptionChargeItem['REMAIN_BEFORE_DEAL'] - $_controlerExceptionChargeItem['REMAIN_AFTER_DEAL'];
                $_controlerExceptionChargeItem['consume_money'] = sprintf('%.2f',$_controlerExceptionChargeItem['consume_money']);
            }
        }
        echo json_encode(['rows'=>$res,'total'=>$total]);
    }
	

}