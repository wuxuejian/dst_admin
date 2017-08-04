<?php
/**
 * 企业短信通知日志控制器
 * @author pengyl
 */
namespace backend\modules\customer\controllers;
use appcustomer\models\CustomerCompany;

use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ConfigCategory;
use backend\models\CompanySmsNotifyLog;

class CompanySmsNotifyLogController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取通知日志列表
     */
    public function actionGetList()
    {
    	$connection = yii::$app->db;
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CompanySmsNotifyLog::find();
        ////查询条件
        $query->andFilterWhere([
            'like',
            '{{%company_sms_notify_log}}.`company_name`',
            yii::$app->request->get('company_name')
        ]);
        $query->andFilterWhere([
        		'like',
        		'{{%company_sms_notify_log}}.`company_number`',
        		yii::$app->request->get('company_number')
        		]);
        if(yii::$app->request->get('start_send_time')){
        	$query->andFilterWhere([
        			'>=',
        			'{{%company_sms_notify_log}}.`send_time`',
        			yii::$app->request->get('start_send_time')
        			]);
        }
        if(yii::$app->request->get('end_send_time')){
        	$query->andFilterWhere([
        			'<=',
        			'{{%company_sms_notify_log}}.`send_time`',
        			yii::$app->request->get('end_send_time')
        			]);
        }
        
//         exit($query->createCommand()->sql);
        //////查询条件
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                default:
                    $orderBy = '{{%company_sms_notify_log}}.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = '{{%company_sms_notify_log}}.`id` ';
        }
        $orderBy .= $sortType;
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy($orderBy)
                ->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }
    
    /**
     * 条件导出
     */
    public function actionExportWidthCondition()
    {
    	$query = CompanySmsNotifyLog::find();
    ////查询条件
        $query->andFilterWhere([
            'like',
            '{{%company_sms_notify_log}}.`company_name`',
            yii::$app->request->get('company_name')
        ]);
        $query->andFilterWhere([
        		'like',
        		'{{%company_sms_notify_log}}.`company_number`',
        		yii::$app->request->get('company_number')
        		]);
        if(yii::$app->request->get('start_send_time')){
        	$query->andFilterWhere([
        			'>=',
        			'{{%company_sms_notify_log}}.`send_time`',
        			yii::$app->request->get('start_send_time')
        			]);
        }
        if(yii::$app->request->get('end_send_time')){
        	$query->andFilterWhere([
        			'<=',
        			'{{%company_sms_notify_log}}.`send_time`',
        			yii::$app->request->get('end_send_time')
        			]);
        }
    	
    	//         exit($query->createCommand()->sql);
    	//////查询条件
    	$data = $query->asArray()->all();
    	$filename = '租金催缴通知日志列表.csv';
    	$str = "客户号,客户名称,租车数量,本月应缴租金,交租时间,管理人姓名,管理人手机,发送时间,操作人\n";
    	foreach ($data as $row){
    		$str .= "\t{$row['company_number']},{$row['company_name']},{$row['car_num']},{$row['amount']},{$row['delivery_time']},{$row['keeper_name']},{$row['keeper_mobile']},{$row['send_time']},{$row['oper_user']}\n";
    	}
    	$str = mb_convert_encoding($str, "GBK", "UTF-8");
    	$this->export_csv($filename,$str);
    }
    function export_csv($filename,$data)
    {
    	header("Content-type:text/csv;charset=GBK");
    	header("Content-Disposition:attachment;filename=".$filename);
    	header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    	header('Expires:0');
    	header('Pragma:public');
    	echo $data;
    }
}