<?php
/**
 * 企业短信通知控制器
 * @author pengyl
 */
namespace backend\modules\customer\controllers;
use appcustomer\models\CustomerCompany;

use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ConfigCategory;
use backend\models\CompanySmsNotify;

class CompanySmsNotifyController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取通知列表
     */
    public function actionGetList()
    {
    	$connection = yii::$app->db;
    	//检查当月数据是否存在
    	$query = $connection->createCommand(
    			'SELECT count(*) cnt from cs_company_sms_notify where add_time>"'.date('Y-m-01 00:00:00')
    			.'" and add_time<"'.date('Y-m-d 23:59:59')
    			.'" limit 1'
    			);
    	$data = $query->queryOne();
    	if($data['cnt'] == 0){
    		$connection->createCommand('TRUNCATE cs_company_sms_notify')->execute();
    		//加载当月数据
    		$query = $connection->createCommand('select number,company_name,sum(car_num) car_num,sum(month_rent) month_rent,keeper_name,keeper_mobile from (
    				SELECT `a`.`number`, `a`.`company_name`,count(c.id) car_num,sum(c.month_rent) month_rent,
    				a.keeper_name,a.keeper_mobile FROM `cs_customer_company` `a`
    				LEFT JOIN `cs_car_let_contract` `b` ON a.id = b.cCustomer_id
    				LEFT JOIN `cs_car_let_record` `c` ON b.id = c.contract_id
    				WHERE `b`.`id` IS NOT NULL and c.back_time=0 group by b.id HAVING car_num>0) d group by number');
    		$data = $query->queryAll();
    		foreach ($data as $row){
    			$connection->createCommand()->insert('cs_company_sms_notify', [
    					'company_number' => $row['number'],
    					'company_name' => $row['company_name'],
    					'car_num' => $row['car_num'],
    					'amount' => $row['month_rent'],
    					'delivery_time' => date('Y-m-05'),
    					'keeper_name' => $row['keeper_name'],
    					'keeper_mobile' => $row['keeper_mobile'],
    					'is_del' => (int)$row['month_rent']>0?0:1,
    					'add_time' => date('Y-m-d H:i:s')
    					])->execute();
    		}
    	}
    	
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CompanySmsNotify::find();
        ////查询条件
        $query->andFilterWhere([
            'like',
            '{{%company_sms_notify}}.`company_name`',
            yii::$app->request->get('company_name')
        ]);
        $query->andFilterWhere([
        		'like',
        		'{{%company_sms_notify}}.`company_number`',
        		yii::$app->request->get('company_number')
        		]);
        if(yii::$app->request->get('is_send') === '0' || yii::$app->request->get('is_send')){
        	$query->andFilterWhere([
        			'=',
        			'{{%company_sms_notify}}.`is_send`',
        			yii::$app->request->get('is_send')
        			]);
        }
        if(yii::$app->request->get('start_send_time')){
        	$query->andFilterWhere([
        			'>=',
        			'{{%company_sms_notify}}.`send_time`',
        			yii::$app->request->get('start_send_time')
        			]);
        }
        if(yii::$app->request->get('end_send_time')){
        	$query->andFilterWhere([
        			'<=',
        			'{{%company_sms_notify}}.`send_time`',
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
                    $orderBy = '{{%company_sms_notify}}.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = '{{%company_sms_notify}}.`id` ';
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
    	$query = CompanySmsNotify::find();
    	////查询条件
    	$query->andFilterWhere([
    			'like',
    			'{{%company_sms_notify}}.`company_name`',
    			yii::$app->request->get('company_name')
    			]);
    	$query->andFilterWhere([
    			'like',
    			'{{%company_sms_notify}}.`company_number`',
    			yii::$app->request->get('company_number')
    			]);
    	if(yii::$app->request->get('is_send') === '0' || yii::$app->request->get('is_send')){
    		$query->andFilterWhere([
    				'=',
    				'{{%company_sms_notify}}.`is_send`',
    				yii::$app->request->get('is_send')
    				]);
    	}
    	if(yii::$app->request->get('start_send_time')){
    		$query->andFilterWhere([
    				'>=',
    				'{{%company_sms_notify}}.`send_time`',
    				yii::$app->request->get('start_send_time')
    				]);
    	}
    	if(yii::$app->request->get('end_send_time')){
    		$query->andFilterWhere([
    				'<=',
    				'{{%company_sms_notify}}.`send_time`',
    				yii::$app->request->get('end_send_time')
    				]);
    	}
    	
    	//         exit($query->createCommand()->sql);
    	//////查询条件
    	$data = $query->asArray()->all();
    	$filename = '租金催缴通知列表.csv';
    	$str = "客户号,客户名称,租车数量,本月应缴租金,交租时间,管理人姓名,管理人手机,是否发送,发送状态,发送时间,操作人\n";
    	foreach ($data as $row){
    		$is_del = $row['is_del']==1?'否':'是';
    		$is_send = $row['is_send']==1?'已发送':'未发送';
    		$send_time = date('Y-m-d H:i:s',$row['send_time']);
    		$str .= "\t{$row['company_number']},{$row['company_name']},{$row['car_num']},{$row['amount']},{$row['delivery_time']},{$row['keeper_name']},{$row['keeper_mobile']},{$is_del},{$is_send},{$send_time},{$row['oper_user']},{$row['add_time']}\n";
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
    
    /**
     * 发送通知
     */
    public function actionSendNotify()
    {
    	$query = Yii::$app->db->createCommand()->update(
    			'{{%company_sms_notify}}',
    			[
    			'oper_user' => $_SESSION['backend']['adminInfo']['username']
    			])->execute();
    
    	$pars='';
    	system ("/usr/local/php/bin/php /data/wwwroot/DST/extension/Task/companySmsNotify.php {$pars} >/dev/null &");
//     	exec ("php E:\workspace\DST\extension\Task\companySmsNotify.php {$pars} >/dev/null &");
//     	exec ("(php E:\\workspace\\DST\\extension\\Task\\companySmsNotify.php >/dev/null &)");
		$returnArr['status'] = true;
		$returnArr['info'] = '发送成功！';
		echo json_encode($returnArr);
    }

    /**
     * 修改通知信息
     */
    public function actionEdit()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            $model = CompanySmsNotify::findOne(['id'=>$id]);
            $model or die('record not found');
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
            	$query = Yii::$app->db->createCommand()->update(
            			'{{%company_sms_notify}}', 
            			[
            				'amount' => yii::$app->request->post('amount'),
            				'delivery_time' => yii::$app->request->post('delivery_time'),
            				'is_del' => yii::$app->request->post('is_del'),
	            			'keeper_mobile' => yii::$app->request->post('keeper_mobile'),
	            			'car_num' => yii::$app->request->post('car_num')
            			], 
            			'id = '.$id)->execute();
//             	exit($query->sql);
				$returnArr['status'] = true;
				$returnArr['info'] = '修改成功！';
            }else{
                $returnArr['status'] = false;
                $errors = $model->getErrors();
                if($errors){
                    foreach($errors as $val){
                        $returnArr['info'] .= $val[0];
                    }
                }else{
                    $returnArr['info'] = '未知错误！';
                }
            }
            echo json_encode($returnArr);
            return null;
        }
        //data submit end
        $id = yii::$app->request->get('id') or die('param id is required');
        $info = CompanySmsNotify::find()
        	->where(['id'=>$id])
            ->asArray()->one();
        $info or die('record not found');
        return $this->render('edit',[
            'info'=>$info
        ]);
    }
}