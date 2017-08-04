<?php
/**
 * 流程配置类
 * @author Administrator
 *
 */
namespace backend\modules\process\controllers;
use backend\classes\MyUploadFile;

use backend\classes\Approval;
use backend\classes\Approval1;

use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\classes\CarStatus;
use backend\models\CarLetContract;
use backend\models\Car;

class CarController extends BaseController
{
	
	//新增operating_company_id ，更新以前operating_company_id数据
	public function actionTest()
	{
		$extract_row = (new \yii\db\Query())->select('id,user_id')->from('oa_extract_report')->all();
		foreach ($extract_row as $val)
		{

			$admin = (new \yii\db\Query())->select('operating_company_id')->from('cs_admin')->where('id=:id AND is_del=0',[':id'=>$val['user_id']])->one();
			if($admin){
				$db = \Yii::$app->db;
				$db->createCommand()->update('oa_extract_report',
						[
						'operating_company_id'=> $admin['operating_company_id'],
						],'id=:id',[':id'=>$val['id']])->execute();
			}	
		}
	
		echo 'ok';
	}
	
	public function actionIndex()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			/*   			$approval = new Approval();
			 $result = $approval->my_approvel();
			echo '<pre>';
			var_dump($result);exit();  */
	
			$db = new \yii\db\Query();
			$query = $db->select('oa_extract_report.*,cs_admin.name as username,cs_admin.department_id')->from('oa_extract_report')->where('oa_extract_report.is_del=1');
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			$session = yii::$app->session;
			$session->open();
			$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
			$query->andWhere("oa_extract_report.operating_company_id in ({$ocs})");
			//按名称模糊搜索
			$name = yii::$app->request->post('name');
			$shenqing_time_start = yii::$app->request->post('shenqing_time_start');
			$shenqing_time_end = yii::$app->request->post('shenqing_time_end');
	
			$my_approvel = yii::$app->request->post('my_approvel');
			if(!isset($my_approvel) || $my_approvel==1)
			{
				//我的待办
				$approval = new Approval();
				$result = $approval->my_approvel();
				if($result !=0)
				{
					$query->andWhere(['oa_extract_report.id'=>$result]);
				}
			}
			if($name)
			{
				$query->andWhere(['like','oa_extract_report.name',$name]);
			}
			if($shenqing_time_start)
			{
				$start_time = strtotime($shenqing_time_start);
				$query->andWhere("shenqing_time >= {$start_time}");
			}
			if($shenqing_time_end)
			{
				$end_time = strtotime($shenqing_time_end)+86400;
				$query->andWhere("shenqing_time <= {$end_time}");
			}
			$car_no = yii::$app->request->post('car_no');
			if($car_no)
			{
				$carquery = (new \yii\db\Query())->select('tc_receipts')->from('oa_prepare_car')->where(['like','car_no',$car_no]);
				$query->andWhere(['oa_extract_report.id'=>$carquery]);
			}
			$tiche_manage_user  = yii::$app->request->post('tiche_manage_user');
			if($tiche_manage_user)
			{
				$query->andWhere(['like','tiche_manage_user',$tiche_manage_user]);
			}
			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			if($sort)
			{
				$query->orderBy("{$sort} {$order}");
			}else{
				$query->orderBy("shenqing_time desc ");
			}
	
			$query->join('LEFT JOIN','cs_admin','cs_admin.id = oa_extract_report.user_id');
	
			//所属运营公司
			/* $session = yii::$app->session;
			 $session->open();
			$oc_id = $_SESSION['backend']['adminInfo']['operating_company_id'];
			if($oc_id)
			{
			$query->andWhere("cs_admin.operating_company_id = {$oc_id}");
			} */
	
			$total = $query->count();
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
	
			$result = $query->offset($pages->offset)->limit($pages->limit)->all();

			$result = json_decode(json_encode($result));
			if($result){
				$approval = new Approval();
				foreach ($result as $val)
				{
					//echo'<pre>';
					//var_dump($val);exit;
					$car_types = json_decode($val->car_type,true);
					$val->car_type = '';
					foreach ($car_types as $k=>$v)
					{
						$val->car_type .=$k.':'.$v.'辆&nbsp;';
					}
					$val->shenqing_time = date('Y-m-d H:i',$val->shenqing_time);
					//avg 当前url路由 ，id ，申请状态
					$val->current_status = $approval->approval_status('process/car/index',$val->id,$val->is_cancel,'oa_extract_report');
					$val->count_down = $approval->count_down('process/car/index','oa_extract_report');
					if($val->extract_way == 1)
					{
						$val->extract_way = '自提';
					}elseif($val->extract_way == 2){
						$val->extract_way = '送车上门';
					}
					//$val->extract_way =  $val->extract_way == 1 ? '自提':'送车上门';
	
	
					$row = (new \yii\db\Query())->select('name')->from('cs_department')->where('id=:id',[':id'=>$val->department_id])->one();
					$val->department_id = $row['name'] ? $row['name']:'';
					/* 					echo $val->current_status;
					 echo '<br>';
					echo $val->current_operation;
					echo '--------------------'; */
					/* 					echo '<pre>';
					 var_dump($val->car_type);exit(); */
					if($val->batch_no)
					{
						$val->contract_number = $val->contract_number.'-'.$val->batch_no;
					}

					//交车数量
					$jc_numbers = (new \yii\db\Query())->select('is_delivery')->from('oa_prepare_car')->where('is_delivery=1 and is_jiaoche=1 and tc_receipts=:tc_receipts',[':tc_receipts'=>$val->id])->all();
					if(count($jc_numbers) == 0) {
						$val->jc_number = 0;	
					}
					if(count($jc_numbers) != 0) {
						$val->jc_number = count($jc_numbers);
					}
					
				}
				//	exit();
			}
	
	
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
	
		$buttons = $this->getCurrentActionBtn();
		return $this->render('index',['buttons'=>$buttons]);
	}
	
	//按条件导出
	public function actionExport()
	{
		//
		//echo 'w1';exit;
		$db = new \yii\db\Query();
		$query = $db->select('oa_extract_report.*,cs_admin.name as username,cs_admin.department_id')->from('oa_extract_report')->where('oa_extract_report.is_del=1');
		$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
		$session = yii::$app->session;
		$session->open();
		$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
		$query->andWhere("oa_extract_report.operating_company_id in ({$ocs})");
		//按名称模糊搜索
		$name = yii::$app->request->post('name');
		$shenqing_time_start = yii::$app->request->post('shenqing_time_start');
		$shenqing_time_end = yii::$app->request->post('shenqing_time_end');
		
		$my_approvel = yii::$app->request->post('my_approvel');
		//echo 'ww';exit;
		
		/*$query->andFilterWhere([
        'like',
        //'{{oa_extract_report}}.`current_status`',
        $current_status
    	]);
		*/

		/*if(!isset($my_approvel) || $my_approvel==1)
		{
			//我的待办
			$approval = new Approval();
			$result = $approval->my_approvel();
			if($result !=0)
			{
				$query->andWhere(['oa_extract_report.id'=>$result]);
			}
		}*/
		$name = yii::$app->request->get('name');
		if($name)
		{
			$query->andWhere(['like','oa_extract_report.name',$name]);
		}
		$shenqing_time_start = yii::$app->request->get('shenqing_time_start');
		if($shenqing_time_start)
		{
			$start_time = strtotime($shenqing_time_start);
			$query->andWhere("shenqing_time >= {$start_time}");
		}
		$shenqing_time_end = yii::$app->request->get('shenqing_time_end');
		if($shenqing_time_end)
		{
			$end_time = strtotime($shenqing_time_end)+86400;
			$query->andWhere("shenqing_time <= {$end_time}");
		}
		$car_no = yii::$app->request->post('car_no');
		if($car_no)
		{
			$carquery = (new \yii\db\Query())->select('tc_receipts')->from('oa_prepare_car')->where(['like','car_no',$car_no]);
			$query->andWhere(['oa_extract_report.id'=>$carquery]);
		}
		$tiche_manage_user  = yii::$app->request->post('tiche_manage_user');
		if($tiche_manage_user)
		{
			$query->andWhere(['like','tiche_manage_user',$tiche_manage_user]);
		}
		//排序字段
		/*$sort = yii::$app->request->post('sort');
		$order = yii::$app->request->post('order');  //asc|desc
		if($sort)
		{
			$query->orderBy("{$sort} {$order}");
		}else{
			$query->orderBy("shenqing_time desc ");
		}*/
		
		$query->join('LEFT JOIN','cs_admin','cs_admin.id = oa_extract_report.user_id');
		//echo '<pre>';
		//var_dump($query);exit;
		
		//所属运营公司
		/* $session = yii::$app->session;
		$session->open();
		$oc_id = $_SESSION['backend']['adminInfo']['operating_company_id'];
		if($oc_id)
		{
			$query->andWhere("cs_admin.operating_company_id = {$oc_id}");
		} */
		
		$total = $query->count();
		$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
		
		//$result = $query->offset($pages->offset)->limit($pages->limit)->all();
		$result = $query->all();
		
		//var_dump($result);exit;
		$result = json_decode(json_encode($result));
		if($result){
			$approval = new Approval();
			foreach ($result as $val)
			{
				$car_types = json_decode($val->car_type,true);
				//var_dump($car_types);exit;
				$val->car_type = '';
				foreach ($car_types as $k=>$v)
				{
					$val->car_type .=$k.':'.$v.'辆&nbsp;';

				}
				$val->shenqing_time = date('Y-m-d H:i',$val->shenqing_time);
				$val->current_status = $approval->approval_status('process/car/index',$val->id,$val->is_cancel,'oa_extract_report');
				$val->count_down = $approval->count_down('process/car/index','oa_extract_report');
				if($val->extract_way == 1)
				{
					$val->extract_way = '自提';
				}elseif($val->extract_way == 2){
					$val->extract_way = '送车上门';
				}
				//$val->extract_way =  $val->extract_way == 1 ? '自提':'送车上门';
				
				$row = (new \yii\db\Query())->select('name')->from('cs_department')->where('id=:id',[':id'=>$val->department_id])->one();
				$val->department_id = $row['name'] ? $row['name']:'';
/* 					echo $val->current_status;
				echo '<br>';
				echo $val->current_operation;
				echo '--------------------'; */
/* 					echo '<pre>';
				var_dump($val->car_type);exit(); */
				//$vai_id = $val->id;
				//var_dump($vai_id);exit;
				if($val->batch_no)
				{
					$val->contract_number = $val->contract_number.'-'.$val->batch_no;
				}
				
			}
		//	exit();
		}
		//var_dump($result);exit;
		//查询提车状态///////////////////////////////////////////////////////////////////////////////////////////////////////
		//if($current_status){
			$current_status = yii::$app->request->get('current_status');
		//}
		
		//var_dump($current_status);exit;
		foreach ($result as $key => $val2){
			//var_dump(isset($current_status));
			//echo 'ww4';
			if(isset($current_status)  && $current_status != '不限'){
				//echo 'ww5';
				if((strip_tags($val2->current_status) != $current_status)){
					//echo 'ww2';exit;
					unset($result[$key]);
				} else {
					$result[$key]->current_status = strip_tags($val2->current_status);
					$result[$key]->car_type = trim(html_entity_decode($val2->car_type),chr(0xc2).chr(0xa0));

				}
			} else {
				$result[$key]->current_status = strip_tags($val2->current_status);
				$result[$key]->car_type = trim(html_entity_decode($val2->car_type),chr(0xc2).chr(0xa0));
			}
		}

		
		//echo 'ww3';
		$rows = count($result); //计算数组所得到记录总数
		/*$pagecount = ceil($rows / $pageSize);
		if(isset($_POST['page'])){
			$page = $_POST['page'];
		} else {
			$page = 1;
		}
		$start=$pageSize*($page - 1);
		$end = $start + $pageSize; //初始化上限*/
		//$result = array_slice($result,$start,$pageSize);//结果集


		$filename = '提车记录列表.csv'; //设置文件名
    	$str = "申请人,申请部门,申请时间,车辆品牌,数量,状态,审批倒计时,提车时间,提车方式,客户名称,合同编号\n";
    	//$car_status_arr = array('available'=>'可用','out_car'=>'出车','repair'=>'维修');
    	//$car_status_arr = array(1=>'已替换',2=>'未替换');
        //var_dump($result);exit;

    	foreach ($result as $row){
    		//$str .= "{$row['plate_number']},{$row['car_brand']},{$row['car_model']},{$car_status_arr[$row['status']]},{$row['remain_distance']},{$row['department_name']},{$row['username']},{$row['reg_name']},{$row['reg_time']}"."\n";
    		//var_dump($row);exit;
    		$str .= "{$row->username},{$row->department_id},{$row->shenqing_time},{$row->car_type},{$row->number},{$row->current_status},{$row->count_down},{$row->extract_time},{$row->extract_way},{$row->name},{$row->contract_number}"."\n";
    	}
    	$str = mb_convert_encoding($str, "GBK", "UTF-8");
    	$this->export_csv($filename,$str); //导出




	}

	 function export_csv($filename,$data)
    {
    	//echo 'w2';exit;
    	//		header("Content-type: text/html; charset=utf-8");
    	header("Content-type:text/csv;charset=GBK");
    	header("Content-Disposition:attachment;filename=".iconv('utf-8','gbk',$filename));
    	header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    	header('Expires:0');
    	header('Pragma:public');
    	echo $data;
    }

	/**
	 * 查询车牌品牌 库存
	 */
	public function actionSearchNumber()
	{
		
/* 		echo 'id='.$_GET['id'];
		echo 'car_brand='.$_POST['car_brand'];
		echo 'car_model='.$_POST['car_model'];
		
		echo '<pre>';
		var_dump($_POST);
		
		$id =yii::$app->request->get('id');
		$car_brand = yii::$app->request->post('car_brand');
		$car_model_name  = yii::$app->request->post('car_model'); */
		
		$id = $_GET['id'];
		$car_brand = $_POST['car_brand'];
		$car_model_name = $_POST['car_model'];
		

		$brand = (new \yii\db\Query())->select('id')->from('cs_car_brand')->where('name=:name AND is_del=0',[':name'=>$car_brand])->one();
		$brand_id = $brand['id'];
		
		
		$modelQuery = (new \yii\db\Query())->select('value')->from('cs_config_item')->where('belongs_id=62 AND is_del=0 AND text=:text',[':text'=>$car_model_name]);
		$query = (new \yii\db\Query())->from('cs_car')->where('brand_id=:brand_id  AND car_status=:car_status AND is_del=0',[':brand_id'=>$brand_id,':car_status'=>'STOCK']);
		//所属运营公司
		$session = yii::$app->session;
		$session->open();
		$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
		//$oc_id = $_SESSION['backend']['adminInfo']['operating_company_id'];
		if($ocs)
		{
			//$query->andWhere("operating_company_id = {$oc_id}");
			$query->andWhere("operating_company_id in ({$ocs})");
		}
		$count = $query->andWhere(['car_model' => $modelQuery])->count();
		
		
		
		//查询出未填写租金（车辆状态为提车中）的申请
		$template_now = (new \yii\db\Query())->select('id')->from('oa_process_template')->where('by_business=:by_business',[':by_business'=>'process/car/index'])->one();
		$result_query = (new \yii\db\Query())->select('by_business_id')->from('oa_approval_result')
				->where('template_id=:template_id AND event=:event AND event_status=:event_status',
						[':template_id'=>$template_now['id'],
						':event'=>'process/car/rent',
						':event_status'=>0]
						);
		
		$query = (new \yii\db\Query())->select('oa_extract_report.id,car_type')->from('oa_extract_report')->where(['oa_extract_report.id'=>$result_query,'oa_extract_report.is_del'=>1,'is_cancel'=>1]);
		$query =$query->join('LEFT JOIN','cs_admin','cs_admin.id = oa_extract_report.user_id');
		if($ocs)
		{
			$query->andWhere("cs_admin.operating_company_id in ({$ocs})");
		}
		$extract_reports = $query->all();
		//$lock_count = 0;
		//已进入提车需求数
		$demand_count = 0;
		//已备车数
		$extract_car_count = 0;
		if($extract_reports)
		{
			foreach ($extract_reports as $extract_report)
			{
				if($extract_report['id'] != $id)
				{
					$car_type = json_decode($extract_report['car_type'],true);
					foreach ($car_type as $k=>$v)
					{
						if($k == $car_brand.'-'.$car_model_name)
						{
							//$lock_count += $v;
							$demand_count += $v;
							$extract_car_query = (new \yii\db\Query())->from('oa_prepare_car')->where(['tc_receipts'=>$extract_report['id'],'is_jiaoche'=>1]);
							$extract_car_query->join('LEFT JOIN','cs_car','cs_car.plate_number = oa_prepare_car.car_no');
							$extract_car_query->andWhere('cs_car.is_del=0');
							$extract_car_query->andWhere("cs_car.brand_id = {$brand_id}");
							$extract_car_query->andWhere(['cs_car.car_model' => $modelQuery]);
							$extract_car_count += $extract_car_query->count();
						}
					}
				}
			}
		}
		//实际可用库存  可用库存   = (库存-(提车需求数-已整备车辆))
		//库存锁定数  锁定 = 提车需求数-已整备车辆
		$returnArr['count'] = $count -($demand_count-$extract_car_count) >0 ? $count -($demand_count-$extract_car_count) :0;
		$returnArr['lock_count'] = $demand_count-$extract_car_count;
		return json_encode($returnArr);
	}
	
	/**
	 * ajax 获取客户（企业、个人）
	 */
	public function actionGetCustomer()
	{
		$data = [];
		//合同 类型  1 租赁合同 2 使用协议 3 虚拟合同
		$contract_type = yii::$app->request->get('contract_type');
		//客户类型 1企业客户 2个人客户
		$customer_type = yii::$app->request->get('customer_type');
		if(empty($contract_type) || empty($customer_type))
		{
			return json_encode($data);
		}
		switch($contract_type){
			//虚拟合同创建在租赁合同中
			case 1:
			case 3:
				$data = (new \yii\db\Query())->select('cCustomer_id,pCustomer_id,number,start_time ,end_time,bail')->from('cs_car_let_contract')->where('is_del=0')->all();
				break;
			case 2:
				$data = (new \yii\db\Query())->select('ctp_cCustomer_id as cCustomer_id,ctp_pCustomer_id as pCustomer_id,ctp_number as number, ctp_start_date as start_time, ctp_end_date as end_time ')->from('cs_car_trial_protocol')->where('ctp_is_del=0')->all();
				break;
		}
		if(empty($data))
		{
			return json_encode($data);
		}
		if($customer_type == 1)
		{
			foreach ($data as $key=>$val)
			{
				$row = (new \yii\db\Query())->select('company_name')->from('cs_customer_company')->where('id=:id',[':id'=>$val['cCustomer_id']])->one();
				if(!empty($row))
				{
					$data[$key]['name'] = $row['company_name'];
				}else{
					unset($data[$key]);
				}
				$data[$key]['start_time'] = is_numeric($val['start_time']) ? date('Y-m-d',$val['start_time']):$val['start_time'];
				$data[$key]['end_time'] = is_numeric($val['end_time']) ? date('Y-m-d',$val['end_time']):$val['end_time'];
			}
			
		}else{
			foreach ($data as $key=>$val)
			{
				$row = (new \yii\db\Query())->select('id_name')->from('cs_customer_personal')->where('id=:id',[':id'=>$val['pCustomer_id']])->one();
				if(!empty($row))
				{
					$data[$key]['name'] = $row['id_name'];
				}else{
					unset($data[$key]);
				}
				$data[$key]['start_time'] = is_numeric($val['start_time']) ? date('Y-m-d',$val['start_time']):$val['start_time'];
				$data[$key]['end_time'] = is_numeric($val['end_time']) ? date('Y-m-d',$val['end_time']):$val['end_time'];
			}
			
		}
		$data = array_values($data);
/*  		echo '<pre>';
		var_dump($data);exit();  */
		return json_encode($data);
	}
	
	/**
	 * 财务确认
	 */
	public function actionFinanceConfirm()
	{
		//echo 'dfdf';exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			
			$id = yii::$app->request->post('id');
			$step_id = yii::$app->request->post('step_id');
			$template_id = yii::$app->request->post('template_id');
			
			$real_rent = yii::$app->request->post('real_rent');
			$real_margin    = yii::$app->request->post('real_margin');
			$confirm_remark    = yii::$app->request->post('confirm_remark');
			
			$result = $db->createCommand()->update('oa_extract_report',
					['real_rent'=>$real_rent,
					'real_margin'=>$real_margin,
					'confirm_remark'=>$confirm_remark,
					],
					'id=:id',[':id'=>$id]
			)->execute();
			
			if($result)
			{
				$approval = new Approval();
				$approval->complete_event($template_id,$id,$step_id,'process/car/finance-confirm');
				$returnArr['status'] = true;
				$returnArr['info'] = '确认成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '确认失败！';
			}
			
			return json_encode($returnArr);
		}
		$id = yii::$app->request->get('id');
		$result = (new \yii\db\Query())->from('oa_extract_report')->where('id=:id',[':id'=>$id])->one();
		$result['step_id'] = yii::$app->request->get('step_id');
		$result['template_id'] = yii::$app->request->get('template_id');
		switch ($result['contract_type']){
			case 1:
				$result['contract_type'] = '租赁合同';
				break;
			case 2:
				$result['contract_type'] = '试用协议';
				break;
			case 3:
				$result['contract_type'] = '虚拟合同';
				break;
		}
		switch ($result['invoice_type']){
			case 1:
				$result['invoice_type'] = '增值税专用发票';
				break;
			case 2:
				$result['invoice_type'] = '增值税普通发票';
				break;
		}
		
		return $this->render('finance-confirm',['result'=>$result]);
	}
	
	/**
	 * 提车点
	 */
	public function actionTicheSite()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			
			/*  echo '<pre>';
			var_dump(yii::$app->request->post('site'));
			echo '<pre>';
			var_dump(yii::$app->request->post('brand_type'));
			echo '<pre>';
			var_dump(yii::$app->request->post('car_number'));
			exit();  */
			$id = yii::$app->request->post('id');
			$step_id= yii::$app->request->post('step_id');
			$template_id = yii::$app->request->post('template_id');
			
			$site = yii::$app->request->post('site');
			$brand_type = yii::$app->request->post('brand_type');
			$car_number = yii::$app->request->post('car_number');
			$user_id = yii::$app->request->post('user_id');
			$current = array();
			foreach ($brand_type as $k=>$v)
			{
				$current[$v]['count'] = @$current[$v]['count'] + $car_number[$k];
				$current_count = @$current_count+$car_number[$k]; 
			}
			
			$r = (new \yii\db\Query())->from('oa_extract_report')->where('id=:id',[':id'=>$id])->one();
			//1、验证车辆总数是否正确
			if($r['number'] != $current_count)
			{
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！分配的提车数量和实际提车总数不相等';
				return json_encode($returnArr);
			}
			//2、需要提取的车辆品牌、数量 是否正确
			$car_types = json_decode($r['car_type'],true);
			foreach ($car_types as $k=>$v)
			{
				if(@$current[$k]['count'] != $v)
				{
					$returnArr['status'] = false;
					$returnArr['info'] = "操作失败！分配给【{$k}】的提车数量和该车型的实际提车数量不相等";
					return json_encode($returnArr);
				}
			}
			foreach ($site as $k=>$v)
			{
				$arr[] = array('site'=>$v,'brand_type'=>$brand_type[$k],'car_number'=>$car_number[$k],'user_id'=>$user_id[$k]);
			}
			$tiche_site = json_encode($arr);
			$tiche_remark = yii::$app->request->post('tiche_remark');
			$db = new \yii\db\Query();
			try {
				$result = $db->createCommand()->update('oa_extract_report',
						[
						'tiche_site'=>$tiche_site,
						'tiche_remark'=>$tiche_remark,
						],
						'id=:id',[':id'=>$id]
				)->execute();
				$approval = new Approval();
				$approval->complete_event($template_id,$id,$step_id,'process/car/tiche-site');
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！请留意现场人员备车进度！';
			} catch (Exception $e) {
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
			}
			
			/* 
			if($result)
			{
				$approval = new Approval();
				$approval->complete_event($template_id,$id,$step_id,'process/car/tiche-site');
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！请留意现场人员备车进度！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
			} */
			
			return json_encode($returnArr);
		}
		$result['id'] = yii::$app->request->get('id');
		$result['step_id'] = yii::$app->request->get('step_id');
		$result['template_id'] = yii::$app->request->get('template_id');
		//$tiche_site = (new \yii\db\Query())->from('cs_config_item')->where('belongs_id=:belongs_id AND is_del=0',[':belongs_id'=>67])->all();
		$r = (new \yii\db\Query())->from('oa_extract_report')->where('id=:id',[':id'=>$result['id']])->one();
		
		//需要提取的车辆品牌、数量
		$car_types = json_decode($r['car_type'],true);
		$tiche_site = (new \yii\db\Query())->from('oa_extract_car_site')->where('parent_id=0 AND is_del=0')->all();
		
		/*车辆品牌列表*/
		$db = new \yii\db\Query();
		$query = $db->select('brand_id,car_model,cs_config_item.text')->from('cs_car')->groupBy('brand_id,car_model');
		$query->andWhere("cs_car.car_model != ''");
		//查询车辆型号
		$query->join('INNER JOIN','cs_config_item','cs_config_item.value = cs_car.car_model');
		//查询车辆品牌
		$r = (new \yii\db\Query())->select('a.*,name')->from(['a'=>$query])->join('LEFT JOIN','cs_car_brand','a.brand_id = cs_car_brand.id')->all();
		foreach ($r as $val)
		{
		
			$brand_type[] = $val['name'].'-'.$val['text'];
		}
		$brand_type = array_unique($brand_type);
		$site_users = array();
		$res = (new \yii\db\Query())->select('oa_extract_car_site.*,cs_admin.name as name')
			->from('oa_extract_car_site')->where('parent_id>0 AND oa_extract_car_site.is_del=0')
			->join('LEFT JOIN','cs_admin','oa_extract_car_site.user_id = cs_admin.id')->all();
		foreach ($res as $v)
		{
			$site_users[$v['parent_id']][] = array('user_id'=>$v['user_id'],'name'=>$v['name']);
		}

		return $this->render('tiche-site',['tiche_site'=>$tiche_site,'result'=>$result,'car_types'=>$car_types,'brand_type'=>$brand_type,'site_users'=>$site_users]);
	}
	/**
	 * 
	 *指派站点负责人
	 */
	public function actionAssign() 
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
		
			$id = yii::$app->request->post('id');
			$step_id= yii::$app->request->post('step_id');
			$template_id = yii::$app->request->post('template_id');
			
			//$tiche_manage_user = yii::$app->request->post('tiche_manage_user');
			$site = yii::$app->request->post('site');
			$brand_type = yii::$app->request->post('brand_type');
			$car_number = yii::$app->request->post('car_number');
			$user_id = yii::$app->request->post('user_id');
			foreach ($site as $k=>$v)
			{
				$arr[] = array('site'=>$v,'brand_type'=>$brand_type[$k],'car_number'=>$car_number[$k],'user_id'=>$user_id[$k]);
			}
			$tiche_site = json_encode($arr);
			
			
			$assign_remark = yii::$app->request->post('assign_remark');
			$result = $db->createCommand()->update('oa_extract_report',
					[
					'tiche_site'=>$tiche_site,
					'assign_remark'=>$assign_remark,
					],
					'id=:id',[':id'=>$id]
			)->execute();
		
			if($result)
			{
				$approval = new Approval();
				$approval->complete_event($template_id,$id,$step_id,'process/car/assign');
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！请留意现场人员备车进度！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
			}
		
			return json_encode($returnArr);
		}
		$result['id'] = yii::$app->request->get('id');
		$result['step_id'] = yii::$app->request->get('step_id');
		$result['template_id'] = yii::$app->request->get('template_id');
		$row = (new \yii\db\Query())->select('tiche_site ')->from('oa_extract_report')->where('id=:id',[':id'=>$result['id']])->one();
		$tiche_site  = $row['tiche_site'];
		$departments = (new \yii\db\Query())->from('cs_department')->where('is_del = 0')->all();
		
		$sites = (new \yii\db\Query())->from('oa_extract_car_site')->where('parent_id=0')->all();
		$res = (new \yii\db\Query())->select('oa_extract_car_site.*,cs_admin.name as name')
			->from('oa_extract_car_site')->where('parent_id>0 AND oa_extract_car_site.is_del=0')
		    ->join('LEFT JOIN','cs_admin','oa_extract_car_site.user_id = cs_admin.id')->all();
		foreach ($res as $v)
		{
			$site_users[$v['parent_id']][] = array('user_id'=>$v['user_id'],'name'=>$v['name']);
		}
		
		return $this->render('assign',['tiche_site'=>$tiche_site,'result'=>$result,'departments'=>$departments,'sites'=>$sites,'site_users'=>$site_users]);
	}
	
	/**
	 * 交车登记列表
	 */
	public function actionJiaoche()
	{
		
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$step_id= yii::$app->request->post('step_id');
			$template_id = yii::$app->request->post('template_id');
			
			$row = (new \yii\db\Query())->from('oa_extract_report')->where('id=:id',[':id'=>$id])->one();
			json_decode($row['tiche_site']);
			if(json_last_error() != JSON_ERROR_NONE){     //判断提车地点是否为json数据，兼容旧版本
				
				$row = (new \yii\db\Query())->from('oa_prepare_car')->where('tc_receipts=:tc_receipts AND is_delivery=-1 AND is_jiaoche=1',[':tc_receipts'=>$id])->one();
				if($row)
				{
					$returnArr['status'] = false;
					$returnArr['info'] = '请先进行“交付车辆”操作！';
				}else{
						$approval = new Approval();
						$approval->complete_event($template_id,$id,$step_id,'process/car/jiaoche');
						//$this->change_car_status($id);
						$returnArr['status'] = true;
						$returnArr['info'] = '操作成功！';
				}
			}else{
				$session = yii::$app->session;
				$session->open();
				$operator = $_SESSION['backend']['adminInfo']['id'];
				$row = (new \yii\db\Query())->from('oa_prepare_car')->where('tc_receipts=:tc_receipts AND is_delivery=-1 AND is_jiaoche=1 AND operator=:operator',[':tc_receipts'=>$id,':operator'=>$operator])->one();
				if($row)
				{
					$returnArr['status'] = false;
					$returnArr['info'] = '请先进行“交付车辆”操作！';
				}else{
					$row = (new \yii\db\Query())->from('oa_prepare_car')->where('tc_receipts=:tc_receipts AND is_jiaoche=1 AND is_delivery=-1',[':tc_receipts'=>$id])->one();
					if(empty($row)){
						$approval = new Approval();
						$approval->complete_event($template_id,$id,$step_id,'process/car/jiaoche');
						//$this->change_car_status($id);
						$returnArr['status'] = true;
						$returnArr['info'] = '操作成功！';
					}else{
						$returnArr['status'] = true;
						$returnArr['info'] = '你备的车辆已交付，等待其他人完成交付后，流程流转到下一步!';
					}
					
				}
			}
			
			
			return json_encode($returnArr);
		}
		$result['id'] = yii::$app->request->get('id');
		$result['step_id'] = yii::$app->request->get('step_id');
		$result['template_id'] = yii::$app->request->get('template_id');
		
 		$row = (new \yii\db\Query())->from('oa_extract_report')->where('id=:id',[':id'=>$result['id']])->one();
		$result['extract_auth_image'] = $row['extract_auth_image']; 
		$result['extract_user_image'] = $row['extract_user_image'];
		$tiche_sites = json_decode($row['tiche_site'],true);
		$car_type = array();
		if(is_array($tiche_sites)){
			foreach ($tiche_sites as $k=>$v)
			{
				$site_row = (new \yii\db\Query())->select('name')->from('oa_extract_car_site')->where('id=:id AND is_del=0',[':id'=>$v['site']])->one();
				$admin_row = (new \yii\db\Query())->select('name')->from('cs_admin')->where('id=:id AND is_del=0',[':id'=>$v['user_id']])->one();
				$tiche_sites[$k]['site'] = !empty($site_row) ? $site_row['name'] :'未知站点';
				$tiche_sites[$k]['user_id'] = !empty($admin_row) ? $admin_row['name'] :'未知负责人';
			}
		}else{
			$car_type = json_decode($row['car_type'],true);
			$tiche_sites = array();
		}
		
		return $this->render('jiaoche',['result'=>$result,'tiche_sites'=>$tiche_sites,'car_type'=>$car_type]);
	}
	
	/**
	 * 移除不需要的整备车辆
	 */
	public function actionRemoveTiche()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$db = \Yii::$app->db;
			$result = $db->createCommand()->update('oa_prepare_car',['is_jiaoche'=>0],'id=:id',[':id'=>$id])->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '移除成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '移除失败！';
			}
	
			return json_encode($returnArr);
		}
	}
	
	/**
	 * 车辆交付
	 */
	public function actionUdelivery()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = \Yii::$app->db;
			
			$id = yii::$app->request->post('id');
			$is_delivery = yii::$app->request->post('is_delivery');
			$remark = yii::$app->request->post('remark');
			$jiaoche_time = strtotime(yii::$app->request->post('jiaoche_time'));
			
			$upload = new MyUploadFile();
			$u_result = $upload->handleUploadFile($_FILES['verify_car_photo']);
			$verify_car_photo  =  $u_result['error'] ? '': $u_result['filePath'];
			
			$ud_operator = $_SESSION['backend']['adminInfo']['id'];
			//选未交付，开始事务
			$transaction = $db->beginTransaction();
			$result = $db->createCommand()->update('oa_prepare_car',
					[
					'is_delivery'=> $is_delivery,
					'remark'=> $remark,
					'verify_car_photo'=> $verify_car_photo,
					'ud_operator'=> $ud_operator,
					'jiaoche_time'=> $jiaoche_time,
					],'id=:id',[':id'=>$id]
			)->execute();
			$prepare_row = (new \yii\db\Query())->select('car_no')->from('oa_prepare_car')->where('id=:id',[':id'=>$id])->one();
			$car = (new \yii\db\Query())->select('id,car_status')->from('cs_car')->where("plate_number=:plate_number AND is_del =0",[':plate_number'=>$prepare_row['car_no']])->one();
			//车辆id
			$car_id = $car['id'];
			if($is_delivery == 0)
			{
				//未交付的车辆变为库存中
				$statusRet = Car::changeCarStatusNew($car_id, 'STOCK', 'process/car/udelivery', '提车流程，未交付车辆',['car_status'=>'PREPARE','is_del'=>0]);
			}elseif($is_delivery == 1){
				//已交付的车辆变为提车中
				if($car['car_status'] != 'PREPARE'){
					$statusRet = Car::changeCarStatusNew($car_id, 'PREPARE', 'process/car/udelivery', '提车流程，已交付车辆',['car_status'=>'STOCK','is_del'=>0]);
				}else {
					$statusRet['status'] = true;
				}
			}
			
			if($result && $statusRet['status']){
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！';
				$transaction->commit();  //提交事务
			}else {
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
				$transaction->rollback(); //回滚事务
			}
			return json_encode($returnArr);
		}
		$id = yii::$app->request->get('id');
		$result = (new \yii\db\Query())->from('oa_prepare_car')->where('id=:id',[':id'=>$id])->one();
		return $this->render('udelivery',['result'=>$result]);
	}
	
	/**
	 * 填写租金信息
	 */
	public function actionRent()
	{
		//echo '4689';exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$step_id= yii::$app->request->post('step_id');
			$template_id = yii::$app->request->post('template_id');
			
			//是否存在没有填写租金的车辆
			$r = (new \yii\db\Query())->from('oa_prepare_car')->where('tc_receipts=:tc_receipts AND money_fee is null AND is_delivery=1 AND is_jiaoche=1',[':tc_receipts'=>$id])->one();
			if($r){
				$returnArr['status'] = false;
				$returnArr['info'] = '车辆租金信息没有填写完！';
				return json_encode($returnArr);
			}
			
			$db = \yii::$app->db;
			$transaction = $db->beginTransaction();  //开启事物
			$approval = new Approval();
			$result = $approval->complete_event($template_id,$id,$step_id,'process/car/rent');
			
			//车辆关联进合同、状态改变
			$car_status = new CarStatus();
		    $result1 = $car_status->extract_car($id);
			if($result && $result1===true)
			{
				$transaction->commit();  //提交
				
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！';
			}else{
				$transaction->rollBack(); //回滚
				
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！车辆关联错误码:'.$result1;
			}
			
			/* $returnArr['status'] = true;
			$returnArr['info'] = '操作成功！'; */
			return json_encode($returnArr);
		}
		$result['id'] = yii::$app->request->get('id');
		$result['step_id'] = yii::$app->request->get('step_id');
		$result['template_id'] = yii::$app->request->get('template_id');
		return $this->render('rent',['result'=>$result]);
	}

	
	/**
	 * 登记车辆租金信息
	 */
	public function actionAddRent()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$session = yii::$app->session;
			$session->open();
			
			$id =  yii::$app->request->post('id');
			$db = \Yii::$app->db;
			
			$car_no = yii::$app->request->post('car_no');
			$first_phase = yii::$app->request->post('first_phase');
			$first_phase_fee = yii::$app->request->post('first_phase_fee');
			$money_fee = yii::$app->request->post('money_fee');
			$time_limit = yii::$app->request->post('time_limit');
			//$margin = yii::$app->request->post('margin');
			$start_time = yii::$app->request->post('start_time');
			$end_time = yii::$app->request->post('end_time');
			$last_stage_rent = yii::$app->request->post('last_stage_rent');
			
			
			$operator = $_SESSION['backend']['adminInfo']['id'];
			
			try {
				$result = $db->createCommand()->update('oa_prepare_car',
						[
						'first_phase'=>$first_phase,
						'first_phase_fee' 		=> $first_phase_fee,
						'money_fee'   => $money_fee,
						'time_limit'    => $time_limit,
						//'margin'         => $margin,
						'start_time'   => $start_time,
						'end_time'      => $end_time,
						'rent_operator'    => $operator,
						'last_stage_rent'=>$last_stage_rent,
						],'id=:id',[':id'=>$id]
				)->execute();
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！';
			} catch (Exception $e) {
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
			}
			
			/* if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
			} */
			return json_encode($returnArr);
		}
		
		$id =  yii::$app->request->get('id');
		$result = (new \yii\db\Query())->from('oa_prepare_car')->where('id=:id',[':id'=>$id])->one();
		return $this->render('add-rent',['result'=>$result]);
	}
	
	/**
	 * 登记交车信息时 更换车辆
	 */
	public function actionReplace()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			
			$session = yii::$app->session;
			$session->open();
			$db = \Yii::$app->db;
			
			$car_no = yii::$app->request->post('car_no');
			$vehicle_license = yii::$app->request->post('vehicle_license');
			$road_transport = yii::$app->request->post('road_transport');
			$insurance = yii::$app->request->post('insurance');
			$business_risks = yii::$app->request->post('business_risks');
			$monitoring = yii::$app->request->post('monitoring');
			$certificate = yii::$app->request->post('certificate');
			$electricity = yii::$app->request->post('electricity');
			
			/* $upload = new MyUploadFile();
			 $u_result = $upload->handleUploadFile($_FILES['verify_car_photo']);
			$verify_car_photo  =  $u_result['error'] ? '': $u_result['filePath']; */
			//随车证件
			$follow_car_card = yii::$app->request->post('follow_car_card');
			if(!empty($follow_car_card) && is_array($follow_car_card))
			{
				$follow_car_card =  implode(',', $follow_car_card);
			}
			//随车资料
			$follow_car_data = yii::$app->request->post('follow_car_data');
			if(!empty($follow_car_data) && is_array($follow_car_data))
			{
				$follow_car_data =  implode(',', $follow_car_data);
			}
			$operator = $_SESSION['backend']['adminInfo']['id'];
			
			$replace = (new \yii\db\Query())->from('oa_prepare_car')->where('id=:id',[':id'=>$id])->one();
			
			
			$transaction = $db->beginTransaction();
			$s1 = false;
			//更换的是整备车辆
			if($replace['is_zhengbei'] ==1){
				$result1 = $db->createCommand()->insert('oa_prepare_car',
						[
						'tc_receipts'=>$replace['tc_receipts'],
						'car_no' 		=> $car_no,
						'vehicle_license'   => $vehicle_license,
						'road_transport'    => $road_transport,
						'insurance'         => $insurance,
						'business_risks'   => $business_risks,
						'monitoring'      => $monitoring,
						'certificate'    => $certificate,
						'electricity' => $electricity,
						'operator'=> $operator,
						'is_zhengbei'=> 0,
						'is_jiaoche'=>1,
						'follow_car_card'=>$follow_car_card,
						'follow_car_data'=>$follow_car_data,
						'first_phase'=>$replace['first_phase'],
						'first_phase_fee' 		=> $replace['first_phase_fee'],
						'money_fee'   => $replace['money_fee'],
						'time_limit'    => $replace['time_limit'],
						'margin'         => $replace['margin'],
						'start_time'   => $replace['start_time'],
						'end_time'      => $replace['end_time'],
						'rent_operator'    => $replace['rent_operator'],
						]
				)->execute();
				$result2 = $db->createCommand()->update('oa_prepare_car',
						[
						'is_jiaoche'=>0,
						],'id=:id',[':id'=>$id]
				)->execute();
				if($result1 && $result2){
					$s1 = true;
				}
			}else{
				$result = $db->createCommand()->update('oa_prepare_car',
						[
						'car_no' 		=> $car_no,
						'vehicle_license'   => $vehicle_license,
						'road_transport'    => $road_transport,
						'insurance'         => $insurance,
						'business_risks'   => $business_risks,
						'monitoring'      => $monitoring,
						'certificate'    => $certificate,
						'electricity' => $electricity,
						'operator'=> $operator,
						'is_zhengbei'=> 0,
						'is_jiaoche'=>1,
						'follow_car_card'=>$follow_car_card,
						'follow_car_data'=>$follow_car_data,
						],'id=:id',[':id'=>$id]
				)->execute();
				$s1 = $result?true:false;
			}
			
			$car = (new \yii\db\Query())->select('id')->from('cs_car')->where("plate_number=:plate_number AND is_del =0",[':plate_number'=>$car_no])->one();
			//车辆id
			$car_id = $car['id'];
			//新添加的车辆变为提车中
			$statusRet1 = Car::changeCarStatusNew($car_id, 'PREPARE', 'process/car/replace', '提车流程，登记交车信息时 更换车辆',['car_status'=>'STOCK','is_del'=>0]);
			
// 			$db->createCommand()->update('cs_car',['car_status'=>'PREPARE'],'id=:id AND is_del =0',[':id'=>$car_id])->execute();
			
			$old_car = (new \yii\db\Query())->select('id')->from('cs_car')->where("plate_number=:plate_number AND is_del =0",[':plate_number'=>$replace['car_no']])->one();
			//车辆id
			$old_car_id = $old_car['id'];
			//被替换的车辆变为库存中
			$statusRet2 = Car::changeCarStatusNew($old_car_id, 'STOCK', 'process/car/replace', '提车流程，登记交车信息时 更换车辆',['car_status'=>'PREPARE','is_del'=>0]);
// 			$db->createCommand()->update('cs_car',['car_status'=>'STOCK'],'id=:id AND is_del =0',[':id'=>$old_car_id])->execute();
			
			if($s1 && $statusRet1['status'] && $statusRet2['status'])
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！';
				$transaction->commit();  //提交事务
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
				$transaction->rollback(); //回滚事务
			}
			return json_encode($returnArr);
		}
		$id = yii::$app->request->get('id');
		$row = (new \yii\db\Query())->from('oa_prepare_car')->where('id=:id',[':id'=>$id])->one();
		
		//查询出当前车辆 品牌、车型
		$car = (new \yii\db\Query())->select('brand_id,car_model')->from('cs_car')->where('plate_number=:plate_number AND is_del=0',[':plate_number'=>$row['car_no']])->one();
		$brand = (new \yii\db\Query())->select('name')->from('cs_car_brand')->where('id=:id',[':id'=>$car['brand_id']])->one();
		$car_model = (new \yii\db\Query())->select('text')->from('cs_config_item')->where('value=:value',[':value'=>$car['car_model']])->one();
		
		$result = array(
			'id'=>$id,
			'car_no'=>$row['car_no'],
			'brand_id'=>$car['brand_id'],
			'car_model'=>$car['car_model'],
			'car_type'=>$brand['name'].'-'.$car_model['text'],
			'tc_receipts'=>$row['tc_receipts'],
		);
		
		$db = \Yii::$app->db;
		$result1 = $db->createCommand("SELECT d.* FROM(
				SELECT c.*,cs_car_insurance_business.end_date AS business_end_date FROM (
				SELECT b.*,cs_car_insurance_compulsory.end_date AS traffic_end_date FROM (
				SELECT a.*,cs_car_road_transport_certificate.next_annual_verification_date FROM (
				SELECT cs_car.id,cs_car.plate_number,cs_car.car_status,cs_car.vehicle_dentification_number as car_vin ,cs_car_driving_license.valid_to_date FROM cs_car LEFT JOIN cs_car_driving_license ON cs_car.id = cs_car_driving_license.car_id WHERE is_del=0)
				AS a  LEFT JOIN cs_car_road_transport_certificate ON a.id= cs_car_road_transport_certificate.car_id)
				AS b LEFT JOIN  cs_car_insurance_compulsory ON b.id =  cs_car_insurance_compulsory.car_id)
				AS c LEFT JOIN 
				
				(SELECT * FROM (SELECT * FROM cs_car_insurance_business where end_date=( 
						SELECT max(end_date)
						FROM cs_car_insurance_business AS b
						WHERE b.car_id=cs_car_insurance_business.car_id
					 )    
				ORDER BY end_date DESC) as a GROUP BY car_id) as cs_car_insurance_business 
				
				ON c.id = cs_car_insurance_business.car_id) AS d WHERE car_status = 'STOCK' AND plate_number !=''  ORDER BY business_end_date DESC")->queryAll();
		if(!empty($result1))
		{
			foreach ($result1 as $key=>$row){
				$result1[$key]['collection_datetime'] = '';
				if($row['car_vin']){
					$db1 = \Yii::$app->db1;
					$realtime_row= $db->createCommand("select collection_datetime from  cs_tcp_car_realtime_data where car_vin='{$row['car_vin']}'")->queryOne();
					$result1[$key]['collection_datetime'] = $realtime_row['collection_datetime'];
				}
			}
			
			
		}
		
		return $this->render('replace',['result'=>$result,'result1'=>$result1]);
	}
	
	/**
	 * 填写收款方式
	 */
	public function actionProceeds()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$step_id= yii::$app->request->post('step_id');
			$template_id = yii::$app->request->post('template_id');
			
			$proceeds = yii::$app->request->post('proceeds');
			$rent = yii::$app->request->post('rent');
			$rent = json_encode($rent);
			$margin = yii::$app->request->post('margin');
			if($proceeds == 'other')
			{
				$proceeds = yii::$app->request->post('other');
			}
			//上传图片
			$myFile = $_FILES['transfer_accounts_img'];
			$transfer_accounts_img = '';
			if($myFile){
				$types = ".jpg|.jpeg|.png"; //此处判断图片类型
				$info = getimagesize($myFile['tmp_name']);
				$ext = image_type_to_extension($info['2']);
				if(false === strpos($types, $ext)){
					$returnArr['status'] = false;
					$returnArr['info'] = "文件类型错误，只能上传图片！";
					return json_encode($returnArr);
				}
				$upload = new MyUploadFile();
				$uploadResult = $upload->handleUploadFile($myFile,'',rand(1,time()));
				if($uploadResult['error']){
					$returnArr['status'] = false;
					$returnArr['info'] = "图片上传失败:".$uploadResult['msg'];
					return json_encode($returnArr);
				}
				$transfer_accounts_img = !empty($uploadResult['filePath']) ? $uploadResult['filePath']:'';
			}

			$db = new \yii\db\Query();
			try {
				$db->createCommand()->update('oa_extract_report',
						[
							'proceeds'=>$proceeds,
							'rent' => $rent,
							'margin'=>$margin,
							'transfer_accounts_img'=>$transfer_accounts_img,
						],
						'id=:id',[':id'=>$id]
				)->execute();
				$approval = new Approval();
				$approval->complete_event($template_id,$id,$step_id,'process/car/proceeds');
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！';
			} catch (Exception $e) {
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
				
			}
			return json_encode($returnArr);
			
		}
		$result['id'] = yii::$app->request->get('id');
		$result['step_id'] = yii::$app->request->get('step_id');
		$result['template_id'] = yii::$app->request->get('template_id');
		$row = (new \yii\db\Query())->select('car_type ')->from('oa_extract_report')->where('id=:id',[':id'=>$result['id']])->one();
		$car_type = json_decode($row['car_type'],true);
		return $this->render('proceeds',['result'=>$result,'car_type'=>$car_type]);
	}
	
	/**
	 * 车辆归档
	 */
	public function actionArchive()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			$id = yii::$app->request->post('id');
			$step_id = yii::$app->request->post('step_id');
			$template_id = yii::$app->request->post('template_id');
			$archive_remark = yii::$app->request->post('archive_remark');
			$result = $db->createCommand()->update('oa_extract_report',
					[
					'is_archive'=>1,
					'archive_remark'=>$archive_remark,
					],
					'id=:id',[':id'=>$id]
			)->execute();
			
			if($result)
			{
				$approval = new Approval();
				$approval->complete_event($template_id,$id,$step_id,'process/car/archive');
				
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！本次提车申请流程已全部结束。';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
			}
			
			return json_encode($returnArr);
			
		}
		$result['id'] = yii::$app->request->get('id');
		$result['step_id'] = yii::$app->request->get('step_id');
		$result['template_id'] = yii::$app->request->get('template_id');
		return $this->render('archive',['result'=>$result]);
	}
	
	public function actionInfo()
	{
		$id = yii::$app->request->get('id');
		$query = (new \yii\db\Query())->select('oa_extract_report.*,cs_admin.name as username')->from('oa_extract_report')->where('oa_extract_report.id=:id',[':id'=>$id]);
		$result = $query->join('LEFT JOIN','cs_admin','cs_admin.id=oa_extract_report.user_id')->andWhere('cs_admin.is_del=0')->one();
		$car_types = json_decode($result['car_type'],true);
		$result['car_type'] = '';
		foreach ($car_types as $k=>$v)
		{
			$result['car_type'] .=$k.':'.$v.'台<br/>';
		}
		//申请人角色名称
		$query = (new \yii\db\Query())->select('cs_rbac_role.name')->from('cs_admin_role')->where('admin_id=:admin_id',[':admin_id'=>$result['user_id']]);
		$user_role_row = $query->join('LEFT JOIN','cs_rbac_role','cs_rbac_role.id=cs_admin_role.role_id')->andWhere('is_del=0')->one();
		$result['role_name'] = !empty($user_role_row) ? $user_role_row['name']:'';
		//步骤id
/* 		$step_id= yii::$app->request->get('step_id');
		$template_id = yii::$app->request->get('template_id');
		setcookie('id',$id);
		setcookie('step_id',$step_id);
		setcookie('template_id',$template_id); */
		
		
		//查询流程节点信息
		$process_data = [];
		$template_info = (new \yii\db\Query())->from('oa_process_template')->where('by_business=:by_business',[':by_business'=>'process/car/index'])->one();
		$template_id = !empty($template_info) ? $template_info['id']:'';
		if($template_id)
		{
		 	$query = (new \yii\db\Query())->select('oa_approval_result.*,cs_admin.name')->from('oa_approval_result')->where('template_id=:template_id AND by_business_id=:by_business_id',[':template_id'=>$template_id,':by_business_id'=>$id]);
		 	$query->join('LEFT JOIN','cs_admin','cs_admin.id=oa_approval_result.operator')->andWhere('is_del=0');
		 	$process_data = $query->orderBy('sort ASC')->all();
		 	//echo '<pre>';
		 	//var_dump($process_data[6]);exit;
		 	foreach ($process_data as $key=>$val)
		 	{
		 		
		 		//当前步骤，进度完成
		 		if($val['approval_status'] ==1 || $val['approval_status'] ==2 || $val['event_status'] == 1 )
		 		{
		 			//每个步骤，操作人是否及时完成
		 			if($key==0)
		 			{
		 				$start = $val['create_time'];
		 			
		 			}else{
		 				$start = $process_data[$key-1]['time'];
		 			}
		 			$end = $val['time'];
		 			//审批是否及时
		 			$process_data[$key]['is_timely'] = $end-$start > $val['count_down']*86400 ? '超时':'及时';
		 			//审批进度
		 			$process_data[$key]['plan']      = '完成';
		 			//审批结果
		 			$process_data[$key]['res'] = $val['approval_status'] ==2 ? '驳回':'通过';
		 			
		 		}else{
		 			$process_data[$key]['is_timely'] = '';
		 			$process_data[$key]['plan']      = '未完成';
		 			$process_data[$key]['res'] = '';
		 		}
				//操作人角色名称
		 		/* $query = (new \yii\db\Query())->select('cs_rbac_role.name')->from('cs_admin_role')->where('admin_id=:admin_id',[':admin_id'=>$val['operator']]);
		 		$role_row = $query->join('LEFT JOIN','cs_rbac_role','cs_rbac_role.id=cs_admin_role.role_id')->andWhere('is_del=0')->one(); */
		 		$role_row = (new \yii\db\Query())->select('name')->from('cs_rbac_role')->where('id=:id',[':id'=>$val['assign_role_id']])->one();
		 		$process_data[$key]['role_name'] = !empty($role_row) ? $role_row['name']:'';
		 	}
		 	
		}
		//备车、交车信息
		//$prepare_car = (new \yii\db\Query())->from('oa_prepare_car')->where('tc_receipts=:tc_receipts',[':tc_receipts'=>$id])->all();
/* 		echo '<pre>';
		var_dump($process_data);exit(); */
		$result['delivery']  = (new \yii\db\Query())->from('oa_prepare_car')->where('tc_receipts=:tc_receipts AND is_jiaoche=1 AND is_delivery=1',[':tc_receipts'=>$id])->count();
		$result['no_delivery'] = (new \yii\db\Query())->from('oa_prepare_car')->where('tc_receipts=:tc_receipts  AND is_jiaoche=1  AND is_delivery!=1 ',[':tc_receipts'=>$id])->count();
		
		$tiche_sites = json_decode($result['tiche_site'],true);
		if(is_array($tiche_sites))
		{
			foreach ($tiche_sites as $k=>$v)
			{
				$site_row = (new \yii\db\Query())->select('name')->from('oa_extract_car_site')->where('id=:id AND is_del=0',[':id'=>$v['site']])->one();
				$admin_row = (new \yii\db\Query())->select('name')->from('cs_admin')->where('id=:id AND is_del=0',[':id'=>$v['user_id']])->one();
				$tiche_sites[$k]['site'] = !empty($site_row) ? $site_row['name'] :'未知站点';
				$tiche_sites[$k]['user_id'] = !empty($admin_row) ? $admin_row['name'] :'未知负责人';
			}
		}else{
			$tiche_sites[$k]['site']= $result['tiche_site'];
			$tiche_sites[$k]['user_id']= $result['tiche_manage_user'];
		}
		$result['tiche_site'] = $tiche_sites;
		return $this->render('info',['result'=>$result,'process_data'=>$process_data]);
	}
	
	/**
	 * 发起申请
	 */
	public function actionAdd()
	{
		//echo '123mmm';exit;
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			
			//提车时间
			/* $extract_time = yii::$app->request->post('extract_time');
			$this->check_time($extract_time); */
			$session = yii::$app->session;
			$session->open();
									
			$db = new \yii\db\Query();
			
			$arr_car_brand = yii::$app->request->post('car_brand');
			$arr_car_type = yii::$app->request->post('car_type');
			$arr_number = yii::$app->request->post('car_number');
			$numbers = 0;
			foreach ($arr_number as $key=>$number)
			{
				if($number)
				{
					@$arr[$arr_car_brand[$key].'-'.$arr_car_type[$key]] += $number;
					$numbers += $number;
				}
			}
	
			//用户id
			$user_id = $_SESSION['backend']['adminInfo']['id'];
			//运营公司ID
			$oc = $_SESSION['backend']['adminInfo']['operating_company_id'];
			//车辆型号
			//$car_type = yii::$app->request->post('car_brand').'-'.yii::$app->request->post('car_type');
			$car_type = json_encode($arr);
			$number = $numbers;
			//数量
			//$number   = yii::$app->request->post('number');
			//提车时间
			$extract_time = yii::$app->request->post('extract_time');
			//提车备注
			$extract_remark = yii::$app->request->post('extract_remark');
			//提车方式
			$extract_way = yii::$app->request->post('extract_way');
			//合同类型//需要修改
			$contract_type = yii::$app->request->post('contract_type');
			//var_dump($contract_type);exit;
			$carletcontract = CarLetContract::find()
			->select(['id','contract_type','number'])
			//->where(['is_del'=>0])
			->where(['id'=>$contract_type])
			->asArray()
			->all();
			foreach($carletcontract as $key => $value4) {
				//var_dump($value4);exit;
				$contract_type = $value4['contract_type'];
			}
			
			//客户类型
			$customer_type = yii::$app->request->post('customer_type');
			//客户名称
			$name = yii::$app->request->post('name');
			//合同编号
			$contract_number = yii::$app->request->post('contract_number');
			
			//验证合同是否终止
			$tmp_contract = CarLetContract::find()
				->select(['is_stop'])
				->where(['number'=>$contract_number])
				->asArray()->one();
			if(!$tmp_contract || $tmp_contract['is_stop']==1){
				$returnArr['status'] = false;
				$returnArr['info'] = '合同已终止！';
				return json_encode($returnArr);
			}
			//end
			
			$carletcontract3 = CarLetContract::find()
			->select(['id','number'])
			//->where(['is_del'=>0])
			//->where(['id'=>$contract_number])
			->asArray()
			->all();
			$array_c_n = array();//定义一个空数组，保存合同
			foreach($carletcontract3 as $value5){
				$array_c_n[]=$value5['number'];
			}
			if(!in_array($contract_number, $array_c_n)){
				echo "<script>alert('保存失败')</script>";
				exit;
			}

			//合同批次号
			$batch_no = yii::$app->request->post('batch_no');
			//申请人姓名
			$shenqingren = yii::$app->request->post('shenqingren');
			//电话
			$tel = yii::$app->request->post('tel');

			//传真
			//$fax = yii::$app->request->post('fax');
			$result = $db->createCommand()->insert('oa_extract_report',
					['user_id'=>$user_id,
					'car_type'=>$car_type,
					'number'=>$number,
					'extract_time'=>$extract_time,
					'extract_remark'=>$extract_remark,
					'shenqing_time'=>time(),
					'extract_time'=>$extract_time,
					'extract_way'=>$extract_way,
					'contract_type'=>$contract_type,
					'customer_type'=>$customer_type,
					'name'=>$name,
					'contract_number'=>$contract_number,
					'batch_no'=>$batch_no,
					'shenqingren'=>$shenqingren,
					'tel'=>$tel,
					'operating_company_id'=>$oc,
					//'fax'=>$fax,
					])->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '提车申请已经保存，点击编辑后可以提交审批。';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '数据保存失败！';
			}
			
			return json_encode($returnArr);
		} else {
			$carletcontract = CarLetContract::find()
			->select(['id','contract_type'])
			//->where(['is_del'=>0])
			//->where(['contract_type'<>" "])
			->groupBy('contract_type')
			->asArray()
			->all();
		
		//$carletcontract->andFilterWhere(['<>','{{cs_car_let_contract}}.`contract_type`',0]);
		
		}
		//合同类型
		
		//var_dump($carletcontract[$key]['contract_type']);exit;
		$db = new \yii\db\Query();
		$query = $db->select('brand_id,car_model,cs_config_item.text')->from('cs_car')->groupBy('brand_id,car_model');
		//查询车辆型号
		$query->join('INNER JOIN','cs_config_item','cs_config_item.value = cs_car.car_model');
		$query->andWhere("cs_car.car_model != ''");
		//查询车辆品牌
		$result = (new \yii\db\Query())->select('a.*,name')->from(['a'=>$query])->join('LEFT JOIN','cs_car_brand','a.brand_id = cs_car_brand.id')->all();
		foreach ($result as $val)
		{
			
			$cars[$val['name']][] = $val['text'];
			$cars[$val['name']] = array_unique($cars[$val['name']]);
		}
		
		//查询出租赁合同、试用协议
		$db = Yii::$app->db;
		$let_contracts = $db->createCommand('SELECT `number`, `customer_type`, `cCustomer_id`, `pCustomer_id`, 1 FROM `cs_car_let_contract` WHERE is_del=0 ORDER BY `last_modify_datetime` DESC')->queryAll();
		$trial_protocol = $db->createCommand('SELECT `ctp_number` AS `number`, `ctp_customer_type` AS `customer_type`, `ctp_cCustomer_id` AS `cCustomer_id`, `ctp_pCustomer_id` AS `pCustomer_id`, 2  FROM `cs_car_trial_protocol` WHERE ctp_is_del=0 ORDER BY `ctp_last_modify_datetime` DESC')->queryAll();
		$contracts = array_merge($let_contracts,$trial_protocol);	

/* 		echo '<pre>';
		var_dump($contracts);exit(); */
		
		return $this->render('add',['cars'=>$cars,'contracts'=>$contracts,'carletcontract'=>$carletcontract]);
	}
	
	/**
	 * 编辑
	 */
	public function actionEdit()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			//echo '456';exit;
			//提车时间
			/* $extract_time = yii::$app->request->post('extract_time');
			$this->check_time($extract_time); */
			
			$session = yii::$app->session;
			$session->open();
			
			$db = new \yii\db\Query();
			
			$arr_car_brand = yii::$app->request->post('car_brand');
			$arr_car_type = yii::$app->request->post('car_type');
			$arr_number = yii::$app->request->post('car_number');
			$numbers = 0;
			foreach ($arr_number as $key=>$number)
			{
				if($number)
				{
					@$arr[$arr_car_brand[$key].'-'.$arr_car_type[$key]] += $number;
					$numbers += $number;
				}
			}
			
			//用户id
			$user_id = $_SESSION['backend']['adminInfo']['id'];
			$id = yii::$app->request->post('id');
			//车辆型号
			//$car_type = yii::$app->request->post('car_brand').'-'.yii::$app->request->post('car_type');
			$car_type = json_encode($arr);
			$number = $numbers;
			//数量
			//$number   = yii::$app->request->post('number');
			//提车时间
			$extract_time = yii::$app->request->post('extract_time');
			//提车备注
			$extract_remark = yii::$app->request->post('extract_remark');
			
			
			
			//提车方式
			$extract_way = yii::$app->request->post('extract_way');
			//合同类型
			$contract_type = yii::$app->request->post('contract_type');
			//客户类型
			$customer_type = yii::$app->request->post('customer_type');
			//客户名称
			$name = yii::$app->request->post('name');
			//合同编号
			$contract_number = yii::$app->request->post('contract_number');
			//合同批次号
			$batch_no = yii::$app->request->post('batch_no');
			//申请人姓名
			$shenqingren = yii::$app->request->post('shenqingren');
			//电话
			$tel = yii::$app->request->post('tel');
			//传真
			//$fax = yii::$app->request->post('fax');
 			$result = $db->createCommand()->update('oa_extract_report',
					['user_id'=>$user_id,
					'car_type'=>$car_type,
					'number'=>$number,
					'extract_time'=>$extract_time,
					'extract_remark'=>$extract_remark,
					'shenqing_time'=>time(),
 					'extract_time'=>$extract_time,
 					'extract_way'=>$extract_way,
 					'contract_type'=>$contract_type,
 					'customer_type'=>$customer_type,
 					'name'=>$name,
 					'contract_number'=>$contract_number,
 					'batch_no'=>$batch_no,
					'shenqingren'=>$shenqingren,
					'tel'=>$tel,
					//'fax'=>$fax,
					],
					'id=:id',[':id'=>$id]
					)->execute();

			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '提车申请已经保存，点击编辑后可以提交审批。';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '数据保存失败！';
			}
			
			return json_encode($returnArr);
		} else {
			$carletcontract = CarLetContract::find()
			->select(['id','contract_type'])
			//->where(['is_del'=>0])
			//->where(['contract_type'<>" "])
			->groupBy('contract_type')
			->asArray()
			->all();
		}
		$db = new \yii\db\Query();
		$query = $db->select('brand_id,car_model,cs_config_item.text')->from('cs_car')->groupBy('brand_id,car_model');
		$query->andWhere("cs_car.car_model != ''");
		//查询车辆型号
		$query->join('INNER JOIN','cs_config_item','cs_config_item.value = cs_car.car_model');
		//查询车辆品牌
		$result = (new \yii\db\Query())->select('a.*,name')->from(['a'=>$query])->join('LEFT JOIN','cs_car_brand','a.brand_id = cs_car_brand.id')->all();
		foreach ($result as $val)
		{
		
			$cars[$val['name']][] = $val['text'];
			$cars[$val['name']] = array_unique($cars[$val['name']]);
		}
		$id = yii::$app->request->get('id');
		$result = (new \yii\db\Query())->from('oa_extract_report')->where('id=:id',[':id'=>$id])->one();
		if($result){
			
			$car_types = json_decode($result['car_type'],true);
			foreach ($car_types as $k=>$v)
			{
				$arr_car = explode('-', $k);
				$result['car_types'][] = array('car_brand'=>$arr_car[0],'car_type'=>$arr_car[1],'car_number'=>$v);
			} 
			
			//$arr_car = explode('-', $result['car_type']);
			//$result['car_brand'] = $arr_car[0];
			//$result['car_type']  = $arr_car[1];
			
			
		}
/*  		echo '<pre>';
		var_dump($cars);exit(); */
		//查询出租赁合同、试用协议
		$db = Yii::$app->db;
		$let_contracts = $db->createCommand('SELECT `number`, `customer_type`, `cCustomer_id`, `pCustomer_id`, 1 FROM `cs_car_let_contract` WHERE is_del=0 ORDER BY `last_modify_datetime` DESC')->queryAll();
		$trial_protocol = $db->createCommand('SELECT `ctp_number` AS `number`, `ctp_customer_type` AS `customer_type`, `ctp_cCustomer_id` AS `cCustomer_id`, `ctp_pCustomer_id` AS `pCustomer_id`, 2  FROM `cs_car_trial_protocol` WHERE ctp_is_del=0 ORDER BY `ctp_last_modify_datetime` DESC')->queryAll();
		$contracts = array_merge($let_contracts,$trial_protocol);
		return $this->render('edit',['cars'=>$cars,'result'=>$result,'contracts'=>$contracts,'carletcontract'=>$carletcontract]);
	}
	
	
	public function check_time($datetime)
	{
		$w = date('w',time());
		//只能选择当前时间(周末不算工作日，当前时间为周末，工作日是从星期一开始)3个工作日之后的日期（不包含今天，周末不算工作日）
		switch($w){
			//当前日期星期一
			case 1:
				$t = date('Y-m-d',strtotime("+ 4 days"));
				break;
			case 2:
			case 3:
			case 4:
			case 5:
				$t = date('Y-m-d',strtotime("+ 6 days"));
				break;
			case 6:
			case 0:  //星期日
				$t = date('Y-m-d',strtotime("+ 5 days"));
				break;
		}
		if(strtotime($datetime) < strtotime($t))
		{
			$returnArr['status'] = false;
			$returnArr['info'] = "必须提前三个工作日发起提车申请，并且只能在工作日的9:00-18:00间提车。当前可以申请的提车时间为{$t} 9:00起";
			echo json_encode($returnArr);
			exit();
		}
		$week = date('w',strtotime($datetime));
		if($week == 0 || $week==6)
		{
			$returnArr['status'] = false;
			$returnArr['info'] = "必须提前三个工作日发起提车申请，并且只能在工作日的9:00-18:00间提车。当前可以申请的提车时间为{$t} 9:00起";
			echo json_encode($returnArr);
			exit();
		}
		$hours = date('G',strtotime($datetime));
		if($hours<9 || $hours>18)
		{
			$returnArr['status'] = false;
			$returnArr['info'] = "必须提前三个工作日发起提车申请，并且只能在工作日的9:00-18:00间提车。当前可以申请的提车时间为{$t} 9:00起";
			echo  json_encode($returnArr);
			exit();
		}
	}
	
	
	/**
	 * 删除
	 */
	
	public function actionDelete()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$db = \Yii::$app->db;
			$result = $db->createCommand()->delete('oa_extract_report','id=:id',[':id'=>$id])->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '删除成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '删除失败！';
			}
		
			return json_encode($returnArr);
		}
	}
	/**
	 * 重新申请
	 */
	public function actionAgain()
	{
		$db = new \yii\db\Query();
		$query = $db->select('brand_id,car_model,cs_config_item.text')->from('cs_car')->groupBy('brand_id,car_model');
		$query->andWhere("cs_car.car_model != ''");
		//查询车辆型号
		$query->join('INNER JOIN','cs_config_item','cs_config_item.value = cs_car.car_model');
		//查询车辆品牌
		$result = (new \yii\db\Query())->select('a.*,name')->from(['a'=>$query])->join('LEFT JOIN','cs_car_brand','a.brand_id = cs_car_brand.id')->all();
		foreach ($result as $val)
		{
	
			$cars[$val['name']][] = $val['text'];
			$cars[$val['name']] = array_unique($cars[$val['name']]);
		}
		$id = yii::$app->request->get('id');
		$result = (new \yii\db\Query())->from('oa_extract_report')->where('id=:id',[':id'=>$id])->one();
/* 		if($result){
			$arr_car = explode('-', $result['car_type']);
			$result['car_brand'] = $arr_car[0];
			$result['car_type']  = $arr_car[1];
		} */
		if($result){
		
			$car_types = json_decode($result['car_type'],true);
			foreach ($car_types as $k=>$v)
			{
				$arr_car = explode('-', $k);
				$result['car_types'][] = array('car_brand'=>$arr_car[0],'car_type'=>$arr_car[1],'car_number'=>$v);
			}
		}
		//查询出租赁合同、试用协议
		$db = Yii::$app->db;
		$let_contracts = $db->createCommand('SELECT `number`, `customer_type`, `cCustomer_id`, `pCustomer_id`, 1 FROM `cs_car_let_contract` WHERE is_del=0 ORDER BY `last_modify_datetime` DESC')->queryAll();
		$trial_protocol = $db->createCommand('SELECT `ctp_number` AS `number`, `ctp_customer_type` AS `customer_type`, `ctp_cCustomer_id` AS `cCustomer_id`, `ctp_pCustomer_id` AS `pCustomer_id`, 2  FROM `cs_car_trial_protocol` WHERE ctp_is_del=0 ORDER BY `ctp_last_modify_datetime` DESC')->queryAll();
		$contracts = array_merge($let_contracts,$trial_protocol);
		return $this->render('again',['cars'=>$cars,'result'=>$result,'contracts'=>$contracts]);
	}
	

	
	/**
	 * 登记提车信息
	 */
	public function actionTiche()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$step_id = yii::$app->request->post('step_id');
			$template_id = yii::$app->request->post('template_id');
			
			$ret = $this->actionCheckUserCarNumber($id, 1);
			if($ret !== TRUE){
				$returnArr['status'] = false;
				$returnArr['info'] = '错误，提示:'.$ret;
				return json_encode($returnArr);
			}
			
			//查询出要提车的需求数量
			$row = (new \yii\db\Query())->select('car_type,number,tiche_site')->from('oa_extract_report')->where('id=:id',[':id'=>$id])->one();
			//现有备车辆
			$existing_count = (new \yii\db\Query())->select('*')->from('oa_prepare_car')->where('tc_receipts=:tc_receipts AND is_zhengbei=1',[':tc_receipts'=>$id])->count();
			
			//车辆数量是否配齐
			if($existing_count < $row['number'])
			{
				json_decode($row['tiche_site']);
				if(json_last_error() != JSON_ERROR_NONE){     //判断提车地点是否为json数据，兼容旧版本
					$returnArr['status'] = false;
					$returnArr['info'] = '当前所登记车辆未达到提车需求数量，请补足！';
					return json_encode($returnArr);
				}else{
					$returnArr['status'] = true;
					$returnArr['info'] = '你的车辆已备齐，等待其他站点负责人登记其余车辆后提交。';
					return json_encode($returnArr);
				}
			}else{
				
				$db = \Yii::$app->db;
				$result = $db->createCommand("SELECT count(*) as count, cs_config_item.text,b.* FROM(
					SELECT cs_car_brand.name,a.* FROM(
					SELECT cs_car.brand_id,cs_car.car_model, oa_prepare_car.car_no from oa_prepare_car LEFT JOIN
					(select brand_id,car_model,plate_number FROM cs_car where is_del =0 ) as cs_car
					ON oa_prepare_car.car_no = cs_car.plate_number where tc_receipts ={$id})
					AS a LEFT JOIN cs_car_brand ON a.brand_id = cs_car_brand.id) as b LEFT JOIN cs_config_item ON b.car_model = cs_config_item.value GROUP BY text,name")->queryAll();
				
				$car_type_arr = json_decode($row['car_type'],true);
				$arr = array();
				foreach ($result as $val)
				{
					$k = $val['name'].'-'.$val['text'];
					$arr[$k] = $val['count'];
				}
				
/* 				echo '<pre>';
				var_dump($car_type_arr);exit(); */
				foreach ($car_type_arr as $key=>$car_type)
				{
					if(@array_key_exists($key, $arr)){
						
						if($arr[$key] < $car_type)
						{
							$count = $car_type-$arr[$key];
							$returnArr['status'] = false;
							$returnArr['info'] = "$key".'缺少'.$count.'辆';
							return json_encode($returnArr);
						}						
					}else{
						$returnArr['status'] = false;
						$returnArr['info'] = "$key".'缺少'.$car_type.'辆';
						return json_encode($returnArr);
					}
					
					
				}
				
				$approval = new Approval();
				$approval->complete_event($template_id, $id, $step_id, 'process/car/tiche');
				$returnArr['status'] = true;
				$returnArr['info'] = '提交成功！请留意审批进度！';
			}
			return json_encode($returnArr);
		}
		$result['id'] = yii::$app->request->get('id');
		$result['step_id'] = yii::$app->request->get('step_id');
		$result['template_id'] = yii::$app->request->get('template_id');
		
 		$row = (new \yii\db\Query())->from('oa_extract_report')->where('id=:id',[':id'=>$result['id']])->one();
		$result['extract_auth_image'] = $row['extract_auth_image']; 
		$result['extract_user_image'] = $row['extract_user_image'];
		
		$tiche_sites = json_decode($row['tiche_site'],true);
		$car_type = array();
		if(is_array($tiche_sites))
		{
			foreach ($tiche_sites as $k=>$v)
			{
				$site_row = (new \yii\db\Query())->select('name')->from('oa_extract_car_site')->where('id=:id AND is_del=0',[':id'=>$v['site']])->one();
				$admin_row = (new \yii\db\Query())->select('name')->from('cs_admin')->where('id=:id AND is_del=0',[':id'=>$v['user_id']])->one();
				$tiche_sites[$k]['site'] = !empty($site_row) ? $site_row['name'] :'未知站点';
				$tiche_sites[$k]['user_id'] = !empty($admin_row) ? $admin_row['name'] :'未知负责人';
			}
		}else{
			$car_type = json_decode($row['car_type'],true);
			$tiche_sites = array();
		}
		
		
		
		return $this->render('tiche',['result'=>$result,'tiche_sites'=>$tiche_sites,'car_type'=>$car_type]);
	}
	
	/**
	 * 校验当前备车人员的备车车型、数量是否与提车方案的一致
	 * @param unknown_type $id    申请ID
	 * @param unknown_type $type  1、备车登记车辆 2、交车登记车辆
	 * @return string
	 */
	public function actionCheckUserCarNumber($id,$type)
	{
		//echo 'ceshi';exit;
		//1、查出提车分配方案
		$row = (new \yii\db\Query())->select('tiche_site')->from('oa_extract_report')->where('id=:id',[':id'=>$id])->one();
		if(empty($row))
		{
			return 'NOT_SELECT_TICHE_SITE';
		}
		$site = json_decode($row['tiche_site'],true);
		if(json_last_error() != JSON_ERROR_NONE){
			return true;        //兼容旧版本提车地点
		}
		//2、查询出当前用户所对应的提车地点
		$session = yii::$app->session;
		$session->open();
		$user_id = $_SESSION['backend']['adminInfo']['id'];
		/* $car_site = (new \yii\db\Query())->select('parent_id')->from('oa_extract_car_site')->where('user_id=:user_id AND is_del=0',[':user_id'=>$user_id])->one();
		if(empty($car_site)){
			return 'NOT_FOUND_USER_BY_SITE';
		}
		$user_by_site = $car_site['parent_id']; */
		//3、查询出当前用户所属的提车点 的提车方案
		$tiche_site = array();
		$tiche_site_count = 0;

		foreach ($site as $k=>$v)
		{
			//var_dump($v);
			//var_dump($user_id);
			//exit;
			if($v['user_id'] == $user_id){

				$tiche_site[$v['brand_type']] = @$tiche_site[$v['brand_type']] + $v['car_number'];
				$tiche_site_count += $v['car_number'];
			}
		}

		if(empty($tiche_site)){
			return '当前提车申请未指定您登记备车信息，请移除您添加的车辆！';
		}
		//4、查询出当前用户对于此次申请录入的车辆
		$db = \Yii::$app->db;
		if($type == 1){
			$result = $db->createCommand("SELECT count(*) as count, cs_config_item.text,b.* FROM(
				SELECT cs_car_brand.name,a.* FROM(
				SELECT cs_car.brand_id,cs_car.car_model, oa_prepare_car.car_no from oa_prepare_car LEFT JOIN
				(select brand_id,car_model,plate_number FROM cs_car where is_del =0 ) as cs_car
				ON oa_prepare_car.car_no = cs_car.plate_number where tc_receipts ={$id} AND operator={$user_id} AND is_zhengbei=1)
				AS a LEFT JOIN cs_car_brand ON a.brand_id = cs_car_brand.id) as b LEFT JOIN cs_config_item ON b.car_model = cs_config_item.value GROUP BY text,name")->queryAll();
		}else{
			$result = $db->createCommand("SELECT count(*) as count, cs_config_item.text,b.* FROM(
					SELECT cs_car_brand.name,a.* FROM(
					SELECT cs_car.brand_id,cs_car.car_model, oa_prepare_car.car_no from oa_prepare_car LEFT JOIN
					(select brand_id,car_model,plate_number FROM cs_car where is_del =0 ) as cs_car
					ON oa_prepare_car.car_no = cs_car.plate_number where tc_receipts ={$id} AND operator={$user_id} AND is_jiaoche=1)
					AS a LEFT JOIN cs_car_brand ON a.brand_id = cs_car_brand.id) as b LEFT JOIN cs_config_item ON b.car_model = cs_config_item.value GROUP BY text,name")->queryAll();
		}
		if(empty($result)){
			return '当前未添加车辆，请根据您所负责站点的提车需求添加车辆！';
		}
		$record = array();
		$total_count = 0;
		foreach ($result as $v){
			$k = $v['name'].'-'.$v['text'];
			$record[$k] = $v['count'];
			$total_count += $v['count'];
		}
		//提车数量是否相等
		if($tiche_site_count !=$total_count)
		{
			return '您当前所添加的车辆与需求数量不符，请核实之后调整！';
		}
		//相应的车型品牌数量是否相等
		foreach ($tiche_site as $k=>$v){
			
			if(@$record[$k] != $v)
			{
				return "您当前登记的车型（{$k}）与需求数量不符，请核实之后调整！";
			}
		}
		
		return true;
		
	}
	
	
	public function actionGetList()
	{
		//echo 'm1m1';exit;
		$tc_receipts = yii::$app->request->get('id');
		$db = new \yii\db\Query();
		$query = $db->select(
					'oa_prepare_car.*,
					cs_admin.name as username,
					cs_car.id as car_id,
					cs_car.vehicle_dentification_number as car_vin,
					cs_car.car_status,
					cs_car.brand_id,
					cs_car_brand.`name` as brand_name,
					cs_config_item.text as car_model_name,
					cs_car_driving_license.valid_to_date,
					cs_car_road_transport_certificate.next_annual_verification_date,
					cs_car_insurance_compulsory.end_date as traffic_end_date,
					cs_car_insurance_business.end_date as business_end_date'
				)->from('oa_prepare_car');
		$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
		
		$is_jiaoche = yii::$app->request->get('is_jiaoche');
		//备车|交车
		if($is_jiaoche)
		{
			$query->andWhere('is_jiaoche=:is_jiaoche',[':is_jiaoche'=>1]);
		}else{
			$query->andWhere('is_zhengbei=1');
		}
		//个人|全部 数据
		$flag = yii::$app->request->get('flag'); 
		if($flag){
			$row = (new \yii\db\Query())->select('tiche_site')->from('oa_extract_report')->where('id=:id',[':id'=>$tc_receipts])->one();
			json_decode($row['tiche_site']);
			if(json_last_error() == JSON_ERROR_NONE){     //判断提车地点是否为json数据，新版本
				$session = yii::$app->session;
				$session->open();
				$operator = $_SESSION['backend']['adminInfo']['id'];
				$query->andWhere('operator=:operator',[':operator'=>$operator]);
			}
			
			
		}
		$is_delivery = yii::$app->request->get('is_delivery');
		if($is_delivery){
			$query->andWhere('is_delivery=1');
		}
		
		//排序字段
		$sort = yii::$app->request->post('sort');
		$order = yii::$app->request->post('order');  //asc|desc
		if($sort)
		{
			$query->orderBy("{$sort} {$order}");
		}
		$query->andWhere('tc_receipts=:tc_receipts',[':tc_receipts'=>$tc_receipts]);
		$total = $query->count();
		$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
		$query->join('LEFT JOIN','cs_admin','cs_admin.id = oa_prepare_car.operator');
		$query->join('LEFT JOIN','cs_car','cs_car.plate_number = oa_prepare_car.car_no and cs_car.is_del=0');
		$query->join('LEFT JOIN','cs_car_brand','cs_car_brand.id = cs_car.brand_id and cs_car_brand.is_del=0');
		$query->join('LEFT JOIN','cs_config_item','cs_config_item.value = cs_car.car_model and cs_config_item.is_del=0');
		
		$query->join('LEFT JOIN','(select car_id,max(cs_car_driving_license.valid_to_date) as valid_to_date from cs_car_driving_license where valid_to_date>'.time().'  group by car_id) as cs_car_driving_license','cs_car_driving_license.car_id = cs_car.id');
		$query->join('LEFT JOIN','cs_car_road_transport_certificate','cs_car.id=cs_car_road_transport_certificate.car_id and cs_car_road_transport_certificate.next_annual_verification_date>'.time());
		$query->join('LEFT JOIN','(select car_id,max(cs_car_insurance_compulsory.end_date) as end_date from cs_car_insurance_compulsory where end_date>'.time().' and is_del=0 group by car_id) as cs_car_insurance_compulsory','cs_car_insurance_compulsory.car_id = cs_car.id');
		$query->join('LEFT JOIN','(select car_id,max(cs_car_insurance_business.end_date) as end_date from cs_car_insurance_business where end_date>'.time().' and is_del=0 group by car_id) as cs_car_insurance_business','cs_car_insurance_business.car_id = cs_car.id');
		
// 		echo $query->createCommand()->getRawSql();exit;
		//
		$result = $query->offset($pages->offset)->limit($pages->limit)->all();
		foreach ($result as $key=>$val)
		{
// 			$car = (new \yii\db\Query())->select('brand_id,car_model')->from('cs_car')->where('plate_number=:plate_number AND is_del = 0 ',[':plate_number'=>$val['car_no']])->one();
			
// 			$car_brand = (new \yii\db\Query())->select('name')->from('cs_car_brand')->where('id=:id',[':id'=>$car['brand_id']])->one();
// 			$car_model = (new \yii\db\Query())->select('text')->from('cs_config_item')->where('value=:value',[':value'=>$car['car_model']])->one();
			
			$result[$key]['car_type'] = $val['brand_name'].'-'.$val['car_model_name'];
			$result[$key]['jiaoche_time'] = !empty($val['jiaoche_time']) ? date('Y-m-d H:i:s',$val['jiaoche_time']):'';
			
// 			$car_no = $val['car_no'];
// 			$db = \Yii::$app->db;
// 			$row = $db->createCommand("SELECT d.* FROM(
// 					SELECT c.*,cs_car_insurance_business.end_date AS business_end_date FROM (
// 					SELECT b.*,cs_car_insurance_compulsory.end_date AS traffic_end_date FROM (
// 					SELECT a.*,cs_car_road_transport_certificate.next_annual_verification_date FROM (
// 					SELECT cs_car.id,cs_car.plate_number,cs_car.car_status,cs_car.vehicle_dentification_number as car_vin ,cs_car_driving_license.valid_to_date FROM cs_car LEFT JOIN cs_car_driving_license ON cs_car.id = cs_car_driving_license.car_id WHERE is_del=0)
// 					AS a  LEFT JOIN cs_car_road_transport_certificate ON a.id= cs_car_road_transport_certificate.car_id)
// 					AS b LEFT JOIN  cs_car_insurance_compulsory ON b.id =  cs_car_insurance_compulsory.car_id)
// 					AS c LEFT JOIN 
					
// 					(SELECT * FROM (SELECT * FROM cs_car_insurance_business where end_date=( 
// 						SELECT max(end_date)
// 						FROM cs_car_insurance_business AS b
// 						WHERE b.car_id=cs_car_insurance_business.car_id
// 					 )    
// 					ORDER BY end_date DESC) as a GROUP BY car_id) as cs_car_insurance_business 
					
// 					ON c.id = cs_car_insurance_business.car_id) AS d  WHERE plate_number ='{$car_no}' ORDER BY business_end_date DESC")->queryOne();
			
			/*$row['collection_datetime'] = '';
			if($row['car_vin']){
				$db1 = \Yii::$app->db1;
				$realtime_row= $db->createCommand("select collection_datetime from  cs_tcp_car_realtime_data where car_vin='{$row['car_vin']}'")->queryOne();
				$row['collection_datetime'] = $realtime_row['collection_datetime'];
			}*/
			
			/* var_dump($db->createCommand("SELECT d.*,cs_car_anomaly_detection_datetime.collection_datetime FROM(
					SELECT c.*,cs_car_insurance_business.end_date AS business_end_date FROM (
					SELECT b.*,cs_car_insurance_compulsory.end_date AS traffic_end_date FROM (
					SELECT a.*,cs_car_road_transport_certificate.next_annual_verification_date FROM (
					SELECT cs_car.id,cs_car.plate_number,cs_car.car_status,cs_car.vehicle_dentification_number as car_vin ,cs_car_driving_license.valid_to_date FROM cs_car LEFT JOIN cs_car_driving_license ON cs_car.id = cs_car_driving_license.car_id WHERE is_del=0)
					AS a  LEFT JOIN cs_car_road_transport_certificate ON a.id= cs_car_road_transport_certificate.car_id)
					AS b LEFT JOIN  cs_car_insurance_compulsory ON b.id =  cs_car_insurance_compulsory.car_id)
					AS c LEFT JOIN cs_car_insurance_business ON c.id = cs_car_insurance_business.car_id) AS d LEFT JOIN car_monidata.cs_tcp_car_realtime_data  as cs_car_anomaly_detection_datetime ON d.car_vin = cs_car_anomaly_detection_datetime.car_vin WHERE plate_number ='{$car_no}' ORDER BY business_end_date DESC")->getRawSql());
			exit(); */			
			$result[$key]['vehicle_license'] = !empty($val['valid_to_date']) ? date('Y-m-d H:i',$val['valid_to_date']) :'未办理';
			$result[$key]['road_transport'] = !empty($val['next_annual_verification_date']) ? date('Y-m-d H:i',$val['next_annual_verification_date']) :'未办理';
			$result[$key]['insurance'] = !empty($val['traffic_end_date']) ? date('Y-m-d H:i',$val['traffic_end_date']) :'未购买'; 
			$result[$key]['business_risks'] = !empty($val['business_end_date']) ? date('Y-m-d H:i',$val['business_end_date']) :'未购买'; 
			/*$result[$key]['monitoring'] = !empty($row['collection_datetime']) ? date('Y-m-d H:i',$row['collection_datetime']) :'没有监控数据';  */
		}
		$returnArr = [];
		$returnArr['rows'] = $result;
		$returnArr['total'] = $total;
		return json_encode($returnArr);
	}
	
	/**
	 * 查看备车车辆记录
	 */
	public function actionRecord()
	{
		//$this->actionGetList();
/* 		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$this->actionGetList();
		} */
		$result['id'] = yii::$app->request->get('id');
		
		$row = (new \yii\db\Query())->select('extract_auth_image,extract_user_image')->from('oa_extract_report')->where('id=:id',[':id'=>$result['id']])->one();
		$result['extract_auth_image'] = $row['extract_auth_image'];
		$result['extract_user_image'] = $row['extract_user_image'];
		return $this->render('record',['result'=>$result]);
	}
	
	/**
	 * 是否已经备完所有车辆
	 */
	public function actionCountContrast()
	{
		$id = yii::$app->request->post('id');
		//查询出要提车的需求数量
		$row = (new \yii\db\Query())->select('number')->from('oa_extract_report')->where('id=:id',[':id'=>$id])->one();
		//现有备车辆
		$existing_count = (new \yii\db\Query())->select('*')->from('oa_prepare_car')->where('tc_receipts=:tc_receipts',[':tc_receipts'=>$id])->count();
		if($existing_count < $row['number'])
		{
			$returnArr['status'] = false;
			$returnArr['info'] = '当前所登记车辆未达到提车需求数量，请补足！';
		}else{
			$returnArr['status'] = true;
			$returnArr['info'] = '当前所登记车辆已达到提车需求数量，不能继续添加！';
		}
		return json_encode($returnArr);
	}
	/**
	 * 添加车辆
	 */
	public function actionAddTiche()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$session = yii::$app->session;
			$session->open();
			
			$tc_receipts =  yii::$app->request->post('id');
			$db = \Yii::$app->db;
			
			$car_no = yii::$app->request->post('car_no');
			$vehicle_license = yii::$app->request->post('vehicle_license');
			$road_transport = yii::$app->request->post('road_transport');
			$insurance = yii::$app->request->post('insurance');
			$business_risks = yii::$app->request->post('business_risks');
			$monitoring = yii::$app->request->post('monitoring');
			$certificate = yii::$app->request->post('certificate');
			$electricity = yii::$app->request->post('electricity');
			
			/* $upload = new MyUploadFile();
			$u_result = $upload->handleUploadFile($_FILES['verify_car_photo']);
			$verify_car_photo  =  $u_result['error'] ? '': $u_result['filePath']; */
			//随车证件
			$follow_car_card = yii::$app->request->post('follow_car_card');
			if(!empty($follow_car_card) && is_array($follow_car_card))
			{
				$follow_car_card =  implode(',', $follow_car_card);
			}
			//随车资料
			$follow_car_data = yii::$app->request->post('follow_car_data');
			if(!empty($follow_car_data) && is_array($follow_car_data))
			{
				$follow_car_data =  implode(',', $follow_car_data);
			}

			
			$operator = $_SESSION['backend']['adminInfo']['id'];
			$car = (new \yii\db\Query())->select('id,car_status2')->from('cs_car')->where("plate_number=:plate_number AND is_del =0",[':plate_number'=>$car_no])->one();
			$transaction = yii::$app->db->beginTransaction();
			$result = $db->createCommand()->insert('oa_prepare_car',
					[
					'tc_receipts'=>$tc_receipts,
					'car_no' 		=> $car_no,
					'vehicle_license'   => $vehicle_license,
					'road_transport'    => $road_transport,
					'insurance'         => $insurance,
					'business_risks'   => $business_risks,
					'monitoring'      => $monitoring,
					'certificate'    => $certificate,
					'electricity' => $electricity,
					//'verify_car_photo'=> $verify_car_photo,
					'operator'=> $operator,
					'is_zhengbei'=> 1,
					'is_jiaoche'=>1,
					'follow_car_card'=>$follow_car_card,
					'follow_car_data'=>$follow_car_data,
					]
			)->execute();
			//车辆id
			$car_id = $car['id'];
			//修改车辆基本信息
			$statusRet = Car::changeCarStatusNew($car_id, 'PREPARE', 'process/car/add-tiche', '提车添加车辆',['car_status'=>'STOCK','is_del'=>0]);
			if($result && $statusRet['status']){
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！';
				if($car['car_status2'] == 'REPAIRING'){
					$returnArr['info'] = '操作成功，目前该车有正在维修的记录，请注意确认！';
				}
				$transaction->commit();  //提交事务
			}else {
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败，请确认车辆是否为库存！';
				$transaction->rollback(); //回滚事务
				
			}
			return json_encode($returnArr);
		}
		$db = \Yii::$app->db;
		$result = $db->createCommand("SELECT d.* FROM(
				SELECT c.*,cs_car_insurance_business.end_date AS business_end_date FROM (
				SELECT b.*,cs_car_insurance_compulsory.end_date AS traffic_end_date FROM (
				SELECT a.*,cs_car_road_transport_certificate.next_annual_verification_date FROM (
				SELECT cs_car.id,cs_car.plate_number,cs_car.car_status,cs_car.vehicle_dentification_number as car_vin ,cs_car_driving_license.valid_to_date FROM cs_car LEFT JOIN cs_car_driving_license ON cs_car.id = cs_car_driving_license.car_id  WHERE is_del=0) 
				AS a  LEFT JOIN cs_car_road_transport_certificate ON a.id= cs_car_road_transport_certificate.car_id)
				AS b LEFT JOIN  cs_car_insurance_compulsory ON b.id =  cs_car_insurance_compulsory.car_id)
				AS c LEFT JOIN 
				
				(SELECT * FROM (SELECT * FROM cs_car_insurance_business where end_date=( 
						SELECT max(end_date)
						FROM cs_car_insurance_business AS b
						WHERE b.car_id=cs_car_insurance_business.car_id
					 )    
				ORDER BY end_date DESC) as a GROUP BY car_id) as cs_car_insurance_business 
				
				
				ON c.id = cs_car_insurance_business.car_id) AS d WHERE car_status = 'STOCK' AND plate_number !=''  ORDER BY business_end_date DESC")->queryAll();
		
		if(!empty($result))
		{
			foreach ($result as $key=>$row){
				$result[$key]['collection_datetime'] = '';
				if($row['car_vin']){
					$db1 = \Yii::$app->db1;
					$realtime_row= $db->createCommand("select collection_datetime from  cs_tcp_car_realtime_data where car_vin='{$row['car_vin']}'")->queryOne();
					$result[$key]['collection_datetime'] = $realtime_row['collection_datetime'];
				}
			}
			
			
		}
		
		$db = new \yii\db\Query();
		$query = $db->select('brand_id,car_model,cs_config_item.text,cs_config_item.value')->from('cs_car')->groupBy('brand_id,car_model');
		//查询车辆型号
		$query->join('INNER JOIN','cs_config_item','cs_config_item.value = cs_car.car_model');
		$query->andWhere('belongs_id=62');
		//查询车辆品牌
		$car_result = (new \yii\db\Query())->select('a.*,id,name')->from(['a'=>$query])->join('LEFT JOIN','cs_car_brand','a.brand_id = cs_car_brand.id')->all();
		$cars = array();
		$arr = array();
		foreach ($car_result as $val)
		{
		
			$cars[$val['name']][$val['text']] = $val['text'];
			
/* 			echo '<pre>$arr[$valname';
			var_dump(@$arr[$val['name']]);
			
			echo '<br/>';
			echo '<pre>val[text]';
			var_dump($val['text']);
			
			echo '----------------'; */
			
			
			if(@array_key_exists($val['text'], $arr[$val['name']]))
			{
				$arr[$val['name']][$val['text']]['car_model'] = $arr[$val['name']][$val['text']]['car_model'].','.$val['value'];
			}else{
				$arr[$val['name']][$val['text']] = array('brand_id'=>$val['id'],'car_model'=>$val['value'],'model_name'=>$val['text']);
			}
			
			$cars[$val['name']] = array_unique($cars[$val['name']]);
		}
 		if(!empty($arr))
		{
			foreach ($arr as $key=>$val)
			{
				foreach ($val as $k=>$v)
				{
					$cars[$key][$k] = $v;
				}
			}
		}

/* 		echo '<pre> car_result';
		var_dump($car_result);
		echo '<br/>';
		echo '<pre> cars';
		var_dump($cars);
		echo '<br/> arr';
		echo '<pre>';
		var_dump($arr);
		exit(); */
		$tc_receipts =  yii::$app->request->get('id');
		return $this->render('add-tiche',['result'=>$result,'cars'=>$cars,'tc_receipts'=>$tc_receipts]);
	}
	
	
	public function actionGetCarNo()
	{
		$tc_receipts =  yii::$app->request->get('tc_receipts');
		$brand_id = yii::$app->request->post('brand_id');
		$car_model = yii::$app->request->post('car_model');
		$arr = explode(',', $car_model);
		$car_model = '';
 		foreach ($arr as $k=>$v)
		{
			if($k==count($arr)-1)
			{
				$car_model .= "'".$v."'";
			}else{
				$car_model .= "'".$v."',";
			}
			
		}
		//查询出已添加的车辆车牌号
		$prepare_car = (new \yii\db\Query())->select('car_no')->from('oa_prepare_car')->where('tc_receipts=:tc_receipts',[':tc_receipts'=>$tc_receipts])->all();
		$plate_number = '';
		if($prepare_car){
			foreach ($prepare_car as $k=>$v)
			{
				if($k==count($prepare_car)-1)
				{
					$plate_number .= "'".$v['car_no']."'";
				}else{
					$plate_number .= "'".$v['car_no']."',";
				}
			
			}
		}else{
			$plate_number = "''";
		}
		
/* 		echo '<pre>';
		var_dump($arr);
		echo '<br/>------';
		echo $car_model;
		exit(); */
		
		//所属运营公司
		$session = yii::$app->session;
		$session->open();
		//$oc_id = $_SESSION['backend']['adminInfo']['operating_company_id'];
		$oc_id = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
		
		$db = \Yii::$app->db;
		$result = $db->createCommand("SELECT  d.* FROM(
				SELECT c.*,cs_car_insurance_business.end_date AS business_end_date FROM (
				SELECT b.*,cs_car_insurance_compulsory.end_date AS traffic_end_date FROM (
				SELECT a.*,cs_car_road_transport_certificate.next_annual_verification_date FROM (
				SELECT cs_car.operating_company_id , cs_car.id,cs_car.brand_id, cs_car.car_model, cs_car.plate_number,cs_car.car_status,cs_car.vehicle_dentification_number as car_vin ,cs_car_driving_license.valid_to_date FROM cs_car LEFT JOIN cs_car_driving_license ON cs_car.id = cs_car_driving_license.car_id WHERE is_del=0)
				AS a  LEFT JOIN cs_car_road_transport_certificate ON a.id= cs_car_road_transport_certificate.car_id)
				AS b LEFT JOIN  cs_car_insurance_compulsory ON b.id =  cs_car_insurance_compulsory.car_id)
				AS c LEFT JOIN
				
				(SELECT * FROM (SELECT * FROM cs_car_insurance_business where end_date=( 
						SELECT max(end_date)
						FROM cs_car_insurance_business AS b
						WHERE b.car_id=cs_car_insurance_business.car_id
					 )    
				ORDER BY end_date DESC) as a GROUP BY car_id) as cs_car_insurance_business 
				
				ON c.id = cs_car_insurance_business.car_id )
				AS d  where business_end_date >= UNIX_TIMESTAMP(NOW()) AND car_status = 'STOCK' AND plate_number !='' AND brand_id={$brand_id} AND  car_model in ({$car_model}) AND plate_number not in({$plate_number}) AND operating_company_id in ({$oc_id})  GROUP BY id")->queryAll();
		
		
		if(!empty($result))
		{
			foreach ($result as $key=>$row){
				$result[$key]['collection_datetime'] = '';
				if($row['car_vin']){
					$db1 = \Yii::$app->db1;
					$realtime_row= $db->createCommand("select collection_datetime from  cs_tcp_car_realtime_data where car_vin='{$row['car_vin']}'")->queryOne();
					$result[$key]['collection_datetime'] = $realtime_row['collection_datetime'];
				}
			}
		
		
		}
		
 		/* echo $db->createCommand("SELECT e.* FROM (
				SELECT  d.*,cs_car_anomaly_detection_datetime.collection_datetime FROM(
				SELECT c.*,cs_car_insurance_business.end_date AS business_end_date FROM (
				SELECT b.*,cs_car_insurance_compulsory.end_date AS traffic_end_date FROM (
				SELECT a.*,cs_car_road_transport_certificate.next_annual_verification_date FROM (
				SELECT cs_car.operating_company_id ,  cs_car.id,cs_car.brand_id, cs_car.car_model, cs_car.plate_number,cs_car.car_status,cs_car.vehicle_dentification_number as car_vin ,cs_car_driving_license.valid_to_date FROM cs_car LEFT JOIN cs_car_driving_license ON cs_car.id = cs_car_driving_license.car_id WHERE is_del=0)
				AS a  LEFT JOIN cs_car_road_transport_certificate ON a.id= cs_car_road_transport_certificate.car_id)
				AS b LEFT JOIN  cs_car_insurance_compulsory ON b.id =  cs_car_insurance_compulsory.car_id)
				AS c LEFT JOIN
				
				(SELECT * FROM (SELECT * FROM cs_car_insurance_business ORDER BY end_date DESC) as a GROUP BY car_id) as cs_car_insurance_business 
				
				ON c.id = cs_car_insurance_business.car_id )
				AS d LEFT JOIN car_monidata.cs_tcp_car_realtime_data  as cs_car_anomaly_detection_datetime ON d.car_vin = cs_car_anomaly_detection_datetime.car_vin) AS e where car_status = 'STOCK' AND plate_number !='' AND brand_id={$brand_id} AND  car_model in ({$car_model}) AND plate_number not in({$plate_number}) AND operating_company_id={$oc_id}   ")->getRawSql();
		
		exit();  */
		
		
		
		/* echo'<pre>';
		var_dump($result);
		echo'<br/>--------';
		echo'<pre>';
		var_dump($prepare_car);exit(); */
		//return json_encode($result);
		
		
		//品牌车型
		/* $brand_row = (new \yii\db\Query())->from('cs_car_brand')->where('id=:id',[':id'=>$brand_id])->one();
		$brand_name = $brand_row['name'];
		$model_row = (new \yii\db\Query())->from('cs_config_item')->where("value in ({$car_model})")->one();
		$model_name = $model_row['text'];
		
		//查询出已申请未交车的申请
		$template_now = (new \yii\db\Query())->select('id')->from('oa_process_template')->where('by_business=:by_business',[':by_business'=>'process/car/index'])->one();
		$result_query = (new \yii\db\Query())->select('by_business_id')->from('oa_approval_result')
		->where('template_id=:template_id AND event=:event AND event_status=:event_status',
				[':template_id'=>$template_now['id'],
				':event'=>'process/car/jiaoche',
				':event_status'=>0]
		);
		
		$query = (new \yii\db\Query())->select('oa_extract_report.id,car_type')->from('oa_extract_report')->where(['oa_extract_report.id'=>$result_query,'oa_extract_report.is_del'=>1,'is_cancel'=>1]);
		$query =$query->join('LEFT JOIN','cs_admin','cs_admin.id = oa_extract_report.user_id');
		if($oc_id)
		{
			$query->andWhere("cs_admin.operating_company_id = {$oc_id}");
		}
		$extract_reports = $query->all();
		if($extract_reports){
			//已经被选为备车，但是还没有交车的车辆车牌
			$use_car_arr = array();
			foreach ($extract_reports as $extract_report)
			{
				$car_type = json_decode($extract_report['car_type'],true);
				foreach ($car_type as $k=>$v){
					if($k == $brand_name.'-'.$model_name)
					{
						$query = (new \yii\db\Query())->select('car_no')->from('oa_prepare_car')->where(['tc_receipts'=>$extract_report['id'],'is_jiaoche'=>1]);
						$query->join('LEFT JOIN','cs_car','cs_car.plate_number = oa_prepare_car.car_no');
						$query->andWhere('cs_car.is_del=0');
						$query->andWhere("cs_car.brand_id = {$brand_id}");
						$query->andWhere("cs_car.car_model in ({$car_model}) ");
						$use_result = $query->all();
						
						if(!empty($use_result)){
							foreach ($use_result as $val){
								$use_car_arr[] = $val['car_no'];
							}
						}
						
					}
				}
				
			}
			
			if(!empty($use_car_arr)){
				foreach ($result as $key=>$val)
				{
					if(in_array($val['plate_number'], $use_car_arr))
					{
						unset($result[$key]);
					}
				}
			}
			
		} */
		return json_encode(array_values($result));
	}
	/**
	 * 移除车辆
	 */
	public function actionDeleteTiche()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			//1.根据提车车辆ID，获取车牌，再根据车牌获取车辆ID
			$prepare_row = (new \yii\db\Query())->select('car_no')->from('oa_prepare_car')->where('id=:id',[':id'=>$id])->one();
			if(!empty($prepare_row['car_no'])){
				$car = (new \yii\db\Query())->select('id')->from('cs_car')->where("plate_number=:plate_number AND is_del =0",[':plate_number'=>$prepare_row['car_no']])->one();
			}
			if(!$car){
				$returnArr['status'] = false;
				$returnArr['info'] = '车辆不存在！';
				return json_encode($returnArr);
			}
			//2.移除车辆、修改车辆状态
// 			$db->createCommand()->update('cs_car',['car_status'=>'STOCK'],'id=:id AND is_del =0',[':id'=>$car['id']])->execute();
			
			$db = \Yii::$app->db;
			$transaction = $db->beginTransaction();  //开启事物
			$result = $db->createCommand()->delete('oa_prepare_car','id=:id',[':id'=>$id])->execute();
			$statusRet = Car::changeCarStatusNew($car['id'], 'STOCK', 'process/car/delete-tiche', '提车车辆移除',['car_status'=>'PREPARE','is_del'=>0]);
			if($result && $statusRet['status']){
				$returnArr['status'] = true;
				$returnArr['info'] = '移除成功！';
				$transaction->commit();  //提交事务
			}else {
				$returnArr['status'] = false;
				$returnArr['info'] = '移除失败！';
				$transaction->rollback(); //回滚事务
			
			}
		
			return json_encode($returnArr);
		}
	}
	
	
	/**
	 * 获取客户名称
	 */
	public function actionGetName()
	{
		//合同编号
		$number = yii::$app->request->post('number');
		$contract = (new \yii\db\Query())->select('customer_type,cCustomer_id,pCustomer_id,bail')->from('cs_car_let_contract')->where('number=:number',[':number'=>$number])->one();
		//租赁合同
		//var_dump($contract['customer_type']);exit;
		if($contract)
		{
			if($contract['customer_type'] =='COMPANY')  //企业客户
			{
				$row = (new \yii\db\Query())->select('company_name')->from('cs_customer_company')->where('id=:id',[':id'=>$contract['cCustomer_id']])->one();
				$name = $row['company_name'];
				//var_dump($name);exit;
			}else{
				$row = (new \yii\db\Query())->select('id_name')->from('cs_customer_personal')->where('id=:id',[':id'=>$contract['pCustomer_id']])->one();
				$name = $row['id_name'];
				//echo '123';exit;
				//var_dump($contract['pCustomer_id']);exit;
			}
		}
		//试用协议
		$trial_protocol = (new \yii\db\Query())->select('ctp_customer_type as customer_type,ctp_cCustomer_id as cCustomer_id,ctp_pCustomer_id as pCustomer_id')->from('cs_car_trial_protocol')->where('ctp_number=:number',[':number'=>$number])->one();
		//var_dump($trial_protocol);exit;
		if($trial_protocol)
		{
			if($trial_protocol['customer_type'] =='COMPANY')  //企业客户
			{
				$row = (new \yii\db\Query())->select('company_name')->from('cs_customer_company')->where('id=:id',[':id'=>$trial_protocol['cCustomer_id']])->one();
				$name = $row['company_name'];
				//var_dump($name);exit;
			}else{
				$row = (new \yii\db\Query())->select('id_name')->from('cs_customer_personal')->where('id=:id',[':id'=>$trial_protocol['cCustomer_id']])->one();
				$name = $row['id_name'];
				//var_dump($name);exit;
			}
		}
		if($name)
		{
			$returnArr['status'] = true;
			$returnArr['info'] = '操作成功！';
			$returnArr['name'] = $name;
			$returnArr['bail'] = $contract['bail'];
		}
		return json_encode($returnArr);
	}
	
	/**
	 * 上传图片
	 */
	public function actionUpload()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$upload = new MyUploadFile();
			$result1 = $upload->handleUploadFile($_FILES['extract_auth_image'],'',rand(1,time()));
			$result2 = $upload->handleUploadFile($_FILES['extract_user_image'],'',rand(1,time()));

			//$extract_auth_image = empty($result1['error']) ? '': $result1['filePath'];
			//$extract_user_image = empty($result2['error']) ? '': $result2['filePath'];
			$customer_name = yii::$app->request->post('customer_name');
			$customer_tel = yii::$app->request->post('customer_tel');
			$db = \Yii::$app->db;
			$result = $db->createCommand()->update('oa_extract_report',
					['extract_auth_image'=> !empty($result1['filePath']) ? $result1['filePath']:'',
					'extract_user_image' => !empty($result2['filePath']) ? $result2['filePath']:'',
					'customer_name'=>$customer_name,
					'customer_tel'=>$customer_tel,
					],'id=:id',[':id'=>$id])->execute();
			
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '上传成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '上传失败！';
			}
			
			return json_encode($returnArr);
		}
		$id = yii::$app->request->get('id');
		return $this->render('upload',['id'=>$id]);
	}
	
	/**
	 * 提交申请
	 */
	public function actionConfirm()
	{
		$contract_number = yii::$app->request->post('contract_number');
		//验证合同是否终止
		$tmp_contract = CarLetContract::find()
		->select(['is_stop'])
		->where(['number'=>$contract_number])
		->asArray()->one();
		if(!$tmp_contract || $tmp_contract['is_stop']==1){
			$returnArr['status'] = false;
			$returnArr['info'] = '合同已终止！';
			return json_encode($returnArr);
		}
		//end
		
		$editer = yii::$app->request->get('editer');
		$_SERVER['REQUEST_METHOD'] == 'POST';
		if($editer =='add')
		{
			$json_result = $this->actionAdd();
			$result = json_decode($json_result,true);
			$id = \Yii::$app->db->getLastInsertID();
		}else{
			$id = yii::$app->request->post('id');
			$json_result = $this->actionEdit();
			$result = json_decode($json_result,true);
			
		}
		
	 	if($result['status'])
		{
			$row = (new \yii\db\Query())->select('car_type')->from('oa_extract_report')->where('id=:id',[':id'=>$id])->one();
			$car_types = json_decode($row['car_type'],true);
			$_GET['id'] = $id;
			foreach ($car_types as $k=>$v)
			{
				$arr = explode('-', $k);
				$_POST['car_brand'] = $arr[0];
				$_POST['car_model'] = $arr[1];
				$retjson = $this->actionSearchNumber();
				$ret = json_decode($retjson,true);
				if($ret['count'] < $v)
				{
					$returnArr['status'] = false;
					$returnArr['info'] = '提交申请失败！'.$arr[0].'-'.$arr[1].'当前可提车辆库存不足，请与车管部门确认';
					return json_encode($returnArr);
				}
			}
			
			
			$approval = new Approval();
			$result = $approval->confirm('process/car/index', $id);
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '申请已提交成功，请留意审批进度！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '提交申请失败！';
			}
			
			return json_encode($returnArr);
		}
		
		/* $approval = new Approval();
		$result = $approval->confirm('process/car/index', yii::$app->request->get('id'));
		if($result)
		{
			$returnArr['status'] = true;
			$returnArr['info'] = '申请已提交成功，请留意审批进度！';
		}else{
			$returnArr['status'] = false;
			$returnArr['info'] = '提交申请失败！';
		}
		
		return json_encode($returnArr); */
	} 
	
	/**
	 * 取消申请
	 */
	public function actionCancel()
	{
		$id = yii::$app->request->get('id');
		$template_id = yii::$app->request->get('template_id');
		$approval = new Approval();
		$result = $approval->cancel('process/car/index', $id,'oa_extract_report',$template_id,0);
		if($result)
		{
			$returnArr['status'] = true;
			$returnArr['info'] = '取消申请成功！';
			$db = \Yii::$app->db;
			$prepare_res = (new \yii\db\Query())->select('car_no')->from('oa_prepare_car')->where('tc_receipts=:tc_receipts',[':tc_receipts'=>$id])->all();
			if($prepare_res){
				$car_ids = array();
				foreach ($prepare_res as $val){
					$car = (new \yii\db\Query())->select('id,car_status')->from('cs_car')->where("plate_number=:plate_number AND is_del =0",[':plate_number'=>$val['car_no']])->one();
					//车辆id
					$car_id = $car['id'];
					if($car['car_status'] == 'PREPARE'){
						array_push($car_ids, $car_id);
					}
					//被替换的车辆变为库存中
// 					$db->createCommand()->update('cs_car',['car_status'=>'STOCK'],'id=:id AND is_del =0',[':id'=>$car_id])->execute();
				}
				$transaction = $db->beginTransaction();
				$statusRet = Car::changeCarStatusNew($car_ids, 'STOCK', 'process/car/cancel', '提车流程取消申请',['car_status'=>'PREPARE','is_del'=>0]);
				if($statusRet['status'])
				{
					$returnArr['status'] = true;
					$returnArr['info'] = '取消申请成功！';
					$transaction->commit();  //提交事务
				}else{
					$returnArr['status'] = false;
					$returnArr['info'] = '取消申请失败！';
					$transaction->rollback(); //回滚事务
				}
				
			}
			
		}else{
			$returnArr['status'] = false;
			$returnArr['info'] = '取消申请失败！';
		}
		
		
		
		return json_encode($returnArr);
	}
	
	/**
	 * 通过
	 */
	public function actionPass()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			//id
/* 			$id = $_COOKIE['id'];
			//步骤id
			$step_id= $_COOKIE['step_id'];
			$template_id = $_COOKIE['template_id'];
			
			
			setcookie('id','',time()-3600);
			setcookie('step_id','',time()-3600);
			setcookie('template_id','',time()-3600); */
			
			$id = yii::$app->request->post('id');
			$step_id = yii::$app->request->post('step_id');
			$template_id = yii::$app->request->post('template_id');
			$remark = yii::$app->request->post('remark');
			
			$approval = new Approval();
			$result = $approval->pass($id, $step_id,$template_id,$remark);
			if($result)
			{
				$row = $this->next_step($id, $step_id, $template_id);
				if($row){
					$returnArr['status'] = "event";
					$returnArr['info'] = $row;
				}else{
					$returnArr['status'] = true;
					$returnArr['info'] = '操作成功！';
				}
				
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
			}
			
			return json_encode($returnArr);
		}
		
		//id
		$result['id'] = yii::$app->request->get('id');
		//步骤id
		$result['step_id'] = yii::$app->request->get('step_id');
		
		$result['template_id'] = yii::$app->request->get('template_id');

		$notice = $this->notice($result['id'], $result['template_id'], $result['step_id']);
		/*  		echo '<pre>';
		 var_dump($result);exit();  */
		return $this->render('pass',['result'=>$result,'notice'=>$notice]);
		
	}
	
	/**
	 * 驳回
	 */
	public function actionNoPass()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$step_id = yii::$app->request->post('step_id');
			$template_id = yii::$app->request->post('template_id');
			$remark = yii::$app->request->post('remark');
/* 			echo '<pre>';
			echo $template_id;exit(); */
			$approval = new Approval();
			$result = $approval->no_pass($id, $step_id,'process/car/index','oa_extract_report',$template_id,$remark);
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
			}
			
			return json_encode($returnArr);
		}
		//id
		$result['id'] = yii::$app->request->get('id');
		//步骤id
		$result['step_id'] = yii::$app->request->get('step_id');
		
		$result['template_id'] = yii::$app->request->get('template_id');
		$notice = $this->notice($result['id'], $result['template_id'], $result['step_id']);
/*  		echo '<pre>';
		var_dump($result);exit();  */
		return $this->render('no-pass',['result'=>$result,'notice'=>$notice]);
	}
	/**
	 * 审批 通过、驳回的文字提示
	 * @param unknown_type $by_business_id
	 * @param unknown_type $template_id
	 * @param unknown_type $step_id
	 */
	public function notice($by_business_id,$template_id,$step_id)
	{
		//查询出当前审批是第几个审批
		$query = (new \yii\db\Query())->select('id')->from('oa_approval_result')->where('by_business_id=:by_business_id AND template_id=:template_id',[':by_business_id'=>$by_business_id,':template_id'=>$template_id]);
		$res = $query->andWhere('event_status is null')->orderBy('sort ASC')->all();
		$notice = '';
		if($res)
		{
			foreach ($res as $val)
			{
				$arr[] = $val['id'];
			}
			$i = array_search($step_id,$arr);
			switch ($i){
				case 0: //部门
					$notice = '请点击“详情”，查看本次申请的提车需求和合同信息，核对后再审批。';
					break;
				case 1: //车管
					$notice = '请点击“详情”，查看本次申请的车型和需求数量，确认库存后再审批。';
					break;
				case 2:
					$notice = '请点击“详情”，查看本次提车的收款方式、租金与保证金信息后再审批。';
					break;
				case 3:  //财务
					$notice = '请点击“详情”，查看本次提车的应收款项信息，核对实收金额后再审批。';
					break;
			}
		}
		return $notice;
	}
	
	
	/**
	 * 流程追踪
	 */
	public function actionTrace()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$by_business_id = yii::$app->request->get('id');
			$template_id = yii::$app->request->get('template_id');
			$db = new \yii\db\Query();
			$query = $db->from('oa_approval_track');
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			
			$query->where('by_business_id=:by_business_id',[':by_business_id'=>$by_business_id]);
			$query->andWhere('template_id=:template_id',[':template_id'=>$template_id]);
			$total = $query->count();
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
			$result = $query->offset($pages->offset)->limit($pages->limit)->all();
			$result = json_decode(json_encode($result));
			if($result){
				foreach ($result as $row)
				{
					$row->time = date('Y-m-d H:i',$row->time);
				}
			}
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
		//id
		$result['id'] = yii::$app->request->get('id');		
		$result['template_id'] = yii::$app->request->get('template_id');
		return $this->render('trace',['result'=>$result]);
	}
	
	/**
	 * 当前操作
	 */
	public function actionOperation()
	{
		$id = yii::$app->request->post('id');
		$is_cancel = yii::$app->request->post('is_cancel');
		$approval = new Approval();
		//avg 当前url路由 ，id ，申请状态
		$approval->approval_status('process/car/index',$id,$is_cancel,'oa_extract_report');
		$returnArr['current_operation'] = $approval->approval_operation($is_cancel,$id,'oa_extract_report');
		return json_encode($returnArr);
		echo '<pre>';
		var_dump($returnArr);exit();
	}
	
	/**
	 * 
	 * @param unknown_type $id
	 * @param unknown_type $step_id    当前步骤ID
	 * @param unknown_type $template_id
	 */
	public function next_step($id, $step_id,$template_id)
	{
		$approval = new Approval();
		$result = $approval->next_step($id, $step_id,$template_id);
		return $result;
	}

	//通过合同类型查询出合同编号
	public function actionCheck2() {
		$contract_type = yii::$app->request->post('contract_type');
        $carletcontract2 = CarLetContract::find()
            ->select([
                //'cs_car_let_contract.id as value',
                'cs_car_let_contract.number as text',
                //'cs_department.name department',
                ])
            ->where(['cs_car_let_contract.is_del'=>0,'cs_car_let_contract.contract_type'=>$contract_type])
            ->asArray()
            ->all();
         
        return json_encode($carletcontract2);
	}

	/*public function actionCheck3() {
		$contract_type = yii::$app->request->post('contract_type');
        $carletcontract3 = CarLetContract::find()
            ->select([
                //'cs_car_let_contract.id as value',
                'cs_car_let_contract.id',
                'cs_car_let_contract.number'
                //'cs_car_let_contract.number as text',
                //'cs_department.name department',
                ])
            ->where(['cs_car_let_contract.is_del'=>0,'cs_car_let_contract.contract_type'=>$contract_type])
            ->asArray()
            ->all();
         
        //return json_encode($carletcontract3);
        if(in_array($contract_type, $carletcontract3)){
        	echo "<font color=red>用户名可以使用！</font>"; 
        } else {
        	echo "<font color=red>用户名已被注册</font>"; 
        }
	}*/

	
}