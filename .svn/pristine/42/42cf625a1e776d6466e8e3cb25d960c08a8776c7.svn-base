<?php
/**
 * @Desc:	会员充值记录管理控制器 
 * @author: chengwk
 * @date:	2015-12-22
 */
namespace backend\modules\vip\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\VipRechargeRecord;
use common\models\Excel;

class VipRechargeRecordController extends BaseController
{
    public function actionIndex()
    {	
		$data['buttons'] = $this->getCurrentActionBtn();
        return $this->render('index',$data); 
    }
    
    /**
     * 获取充值记录列表
     */
    public function actionGetList()
    {
        $query = VipRechargeRecord::find()
			->select([
				'{{%vip_recharge_record}}.*',
				'vip_code'=>'{{%vip}}.code',
				'vip_name'=>'{{%vip}}.client',
				'vip_mobile'=>'{{%vip}}.mobile',
			])
			->joinWith('vip',false);
        //查询条件
        $query->andFilterWhere(['like','trade_no',yii::$app->request->get('trade_no')]);
        $query->andFilterWhere(['=','pay_way',yii::$app->request->get('pay_way')]);
        $query->andFilterWhere(['=','platform_trade_no',yii::$app->request->get('platform_trade_no')]);
        $query->andFilterWhere(['=','trade_status',yii::$app->request->get('trade_status')]);
        $query->andFilterWhere(['like','{{%vip}}.code',yii::$app->request->get('vip_code')]);
        $query->andFilterWhere(['like','{{%vip}}.client',yii::$app->request->get('vip_name')]);
        $query->andFilterWhere(['like','{{%vip}}.mobile',yii::$app->request->get('vip_mobile')]);
        $sDate = yii::$app->request->get('request_datetime_start');
        if($sDate){
            $query->andFilterWhere(['>=','{{%vip_recharge_record}}.request_datetime',strtotime($sDate)]);
        }
        $eDate = yii::$app->request->get('request_datetime_end');
        if($eDate){
            $query->andFilterWhere(['<=','{{%vip_recharge_record}}.request_datetime',strtotime($eDate.' 23:59:59')]);
        }
        $total = $query->count();
        $query2 = clone $query; // 底部合计用
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
		//排序
		if(yii::$app->request->get('sort')){
			$field = yii::$app->request->get('sort');		//field
			$direction = yii::$app->request->get('order');  //asc or desc
			$orderStr = $field .' '. $direction;
		}else{
			$orderStr = 'id desc';
		}		
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderStr)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        // 表格底部增加合计行
        $mySum = $query2->select(['total_fee'=>'SUM(total_fee)'])->asArray()->one();
        $returnArr['footer'] = [[
            'trade_no'=>'合计：',
            'total_fee'=>$mySum['total_fee']
        ]];
        echo json_encode($returnArr);
    }

	
	/*
	 * 异常处理
	 */
    public function actionExceptionHandle(){
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            $recInfo = VipRechargeRecord::find()
                ->select(['id','trade_no','pay_way'])
                ->where(['id'=>$formData['id']])
                ->asArray()->one();
            if(!$recInfo){
                return '未找到当前记录！';
            }
            if($recInfo['pay_way'] == 'wechat'){
                $reason = '充值[微信app]';
            }else{
                $reason = '充值[支付宝app]';
            }
            $res = (new VipRechargeRecord)->rechargeSuccess($recInfo['trade_no'],0,strtotime($formData['gmt_payment_datetime']),$reason,$formData['platform_trade_no']);
            if($res){
                return json_encode(['status'=>true,'info'=>'处理成功！']);
            }else{
                return json_encode(['status'=>false,'info'=>'处理失败！']);
            }
        }else{
            $id = yii::$app->request->get('id',0);
            $recInfo = VipRechargeRecord::find()
                ->select(['id','trade_no','total_fee'])
                ->where(['id'=>$id])
                ->asArray()->one();
            if(!$recInfo){
                return '未找到当前记录！';
            }
            return $this->render('exceptionHandleWin',['recInfo'=>$recInfo]);
        }
    }


	/**
     * 导出Excel
     */
    public function actionExportGridData()
    {
    	set_time_limit(60);
		// 构建导出的excel表头
		$excHeaders = [
            [
                ['content'=>'充值单号','font-weight'=>true,'width'=>'15'],
                ['content'=>'充值金额','font-weight'=>true,'width'=>'15'],
                ['content'=>'支付方式','font-weight'=>true,'width'=>'15'],
                ['content'=>'支付平台交易号','font-weight'=>true,'width'=>'15'],
                ['content'=>'支付状态','font-weight'=>true,'width'=>'15'],
                ['content'=>'系统记录时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'最后通知时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'交易创建时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'交易支付时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'会员编号','font-weight'=>true,'width'=>'15'],
                ['content'=>'会员名称','font-weight'=>true,'width'=>'15'],
                ['content'=>'会员手机号','font-weight'=>true,'width'=>'15']
            ]
		];
		
		// 要查的字段，与导出的excel表头对应
		$selectArr = [		
			'{{%vip_recharge_record}}.trade_no',
			'{{%vip_recharge_record}}.total_fee',
			'{{%vip_recharge_record}}.pay_way',
			'{{%vip_recharge_record}}.platform_trade_no',
			'{{%vip_recharge_record}}.trade_status',
			'{{%vip_recharge_record}}.request_datetime',
			'{{%vip_recharge_record}}.last_notify_datetime',
			'{{%vip_recharge_record}}.gmt_create_datetime',
			'{{%vip_recharge_record}}.gmt_payment_datetime',
			'{{%vip}}.code',
			'{{%vip}}.client',
			'{{%vip}}.mobile'
		];

        $query = VipRechargeRecord::find()
            ->select($selectArr)
            ->joinWith('vip',false);
        //查询条件
        $query->andFilterWhere(['like','trade_no',yii::$app->request->get('trade_no')]);
        $query->andFilterWhere(['=','pay_way',yii::$app->request->get('pay_way')]);
        $query->andFilterWhere(['=','platform_trade_no',yii::$app->request->get('platform_trade_no')]);
        $query->andFilterWhere(['=','trade_status',yii::$app->request->get('trade_status')]);
        $query->andFilterWhere(['like','{{%vip}}.code',yii::$app->request->get('vip_code')]);
        $query->andFilterWhere(['like','{{%vip}}.client',yii::$app->request->get('vip_name')]);
        $query->andFilterWhere(['like','{{%vip}}.mobile',yii::$app->request->get('vip_mobile')]);
        $sDate = yii::$app->request->get('request_datetime_start');
        if($sDate){
        	$query->andFilterWhere(['>=','{{%vip_recharge_record}}.request_datetime',strtotime($sDate)]);
        }
        $eDate = yii::$app->request->get('request_datetime_end');
        if($eDate){
        	$query->andFilterWhere(['<=','{{%vip_recharge_record}}.request_datetime',strtotime($eDate.' 23:59:59')]);
        }
		$data = $query->asArray()->all();
// 		print_r(count($data));
// 		exit;
		// print_r($data);exit;
		
		$excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'vip_recharge_record',
            'subject'=>'vip_recharge_record',
            'description'=>'vip_recharge_record',
            'keywords'=>'vip_recharge_record',
            'category'=>'vip_recharge_record'
        ]);
		
		//---向excel添加表头-------------------------------------
		foreach($excHeaders as $lineData){
			$excel->addLineToExcel($lineData);
		}
        
		//---向excel添加具体数据----------------------------------
        foreach($data as $item){
            $lineData = [];
            $item['pay_way'] = $item['pay_way'] == 'wechat' ? '微信' : ($item['pay_way'] == 'alipay' ? '支付宝' : $item['pay_way']);
            $item['trade_status'] = $item['trade_status'] == 'wait_pay' ? '等待支付' : ($item['trade_status'] == 'success' ? '支付完成' : $item['trade_status']);
            $item['platform_trade_no'] = "'" . $item['platform_trade_no']; // 给支付平台交易号前加英文单引号，防止导出出现科学计数法形式。
            $item['request_datetime'] = $item['request_datetime'] ? date('Y-m-d',$item['request_datetime']) : '';
            $item['last_notify_datetime'] = $item['last_notify_datetime'] ? date('Y-m-d',$item['last_notify_datetime']) : '';
            $item['gmt_create_datetime'] = $item['gmt_create_datetime'] ? date('Y-m-d',$item['gmt_create_datetime']) : '';
            $item['gmt_payment_datetime'] = $item['gmt_payment_datetime'] ? date('Y-m-d',$item['gmt_payment_datetime']) : '';
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
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','会员充值记录_'.date('YmdHis').'.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');	
    }
    
}