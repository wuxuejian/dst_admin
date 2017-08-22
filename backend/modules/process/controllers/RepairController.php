<?php
/**
 * 报修类
 * @author Administrator
 *
 */
namespace backend\modules\process\controllers;
use backend\classes\MyUploadFile;

use backend\classes\Approval;
use backend\classes\Mail;
use backend\classes\CarStatus;

use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use yii\web\UploadedFile;
use common\models\Excel;
use backend\models\CarLetRecord;
use backend\models\CarTrialProtocolDetails;
use common\classes\Resizeimage;
use backend\models\ConfigCategory;
use backend\models\Car;
class RepairController extends BaseController
{
	
	public function actionTest()
	{
		/* $repair_row = (new \yii\db\Query())->select('id,accept_name')->from('oa_repair')->where('user_id =0')->all();
		foreach ($repair_row as $val)
		{
			$admin = (new \yii\db\Query())->select('id,operating_company_id')->from('cs_admin')->where('name=:name AND is_del=0',[':name'=>$val['accept_name']])->one();
			
			$db = \Yii::$app->db;
			$result = $db->createCommand()->update('oa_repair',
					[
					'user_id'		=> $admin['id'],
					'operating_company_id'=> $admin['operating_company_id'],
					],'id=:id',[':id'=>$val['id']])->execute();
		} */
		
		
		$maintain_row = (new \yii\db\Query())->select('id,accept_name,user_id')->from('oa_car_maintain')->all();
		foreach ($maintain_row as $val)
		{
			if(empty($val['user_id'])){
				
				$admin = (new \yii\db\Query())->select('id,operating_company_id')->from('cs_admin')->where('name=:name AND is_del=0',[':name'=>$val['accept_name']])->one();
				
				if($admin){
					$db = \Yii::$app->db;
					$db->createCommand()->update('oa_car_maintain',
							[
							'user_id'		=> $admin['id'],
							'operating_company_id'=> $admin['operating_company_id'],
							],'id=:id',[':id'=>$val['id']])->execute();
				}
				
			}
			
		}
		
		echo 'ok';
	}
	
	
	
	/**
	 * 客户报修登记
	 */
	public function actionIndex()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			$query = $db->select('*')->from('oa_repair');
			//$query = $db->select('*.operating_company_id')->from('oa_repair')
			$session = yii::$app->session;
			$session->open();
			$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
			$query->andWhere("operating_company_id in ({$ocs})");
			
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;

			//按运营公司查询
			if(yii::$app->request->post('operating_company_id')){
        	$operating_company_id = yii::$app->request->post('operating_company_id');
        	$query->andFilterWhere(['{{oa_repair}}.`operating_company_id`'=>$operating_company_id]);
        	}
        	//var_dump(yii::$app->request->post('operating_company_id'));exit;


			//按车牌号搜索
			$car_no = yii::$app->request->post('car_no');
			if($car_no)
			{
				$query->andWhere(['like','car_no',$car_no]);
			}
			
			$repair_name = yii::$app->request->post('repair_name');
			if($repair_name)
			{
				$query->andWhere('repair_name=:repair_name',[':repair_name'=>$repair_name]);
			}
			
			$tel= yii::$app->request->post('tel');
			if($tel)
			{
				$query->andWhere('tel=:tel',[':tel'=>$tel]);
			}
			
			$order_no= yii::$app->request->post('order_no');
			if($order_no)
			{
				//$query->andWhere('order_no=:order_no',[':order_no'=>$order_no]);
				$query->andWhere(['like','order_no',$order_no]);
			}
			
			$start_tel_time= yii::$app->request->post('start_tel_time');
			if($start_tel_time)
			{
				$query->andWhere('tel_time >=:start_tel_time',[':start_tel_time'=>strtotime($start_tel_time)]);
			}
			
			$end_tel_time= yii::$app->request->post('end_tel_time');
			if($end_tel_time)
			{
				$query->andWhere('tel_time <=:end_tel_time',[':end_tel_time'=>strtotime($end_tel_time)+86400]);
			}
			
			$type = yii::$app->request->post('type');
			if($type)
			{
				$query->andWhere('type=:type',[':type'=>$type]);
			}
			
			$status = yii::$app->request->post('status');
			if($status)
			{
				//已确认
				if($status == 3)
				{
					//有两种状态
					$query->andWhere('status in (3,4)');
				}else{
					$query->andWhere('status =:status',[':status'=>$status]);
				}
				
			}
			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			if($sort)
			{
				$query->orderBy("{$sort} {$order}");
			}else{
				$query->orderBy("create_time DESC");
			}
			$total = $query->count();
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
			$result = $query->offset($pages->offset)->limit($pages->limit)->all();
			$result = json_decode(json_encode($result));
 			if($result){
				foreach ($result as $val)
				{
					switch ($val->type){
						case 1:
							$val->type = '客户报修';
							break;
						case 2:
							$val->type = '车辆出险';
							break;
					}
					
					switch ($val->source){
						case 1:
							$val->source = '400电话';
							break;
					}
					
					switch ($val->urgency){
						case 1:
							$val->urgency = '一般紧急';
							break;
						case 2:
							$val->urgency = '比较紧急';
							break;
						case 3:
							$val->urgency = '非常紧急';
							break;
					}
					$val->tel_time = date('Y-m-d H:i',$val->tel_time);
					$val->time      = date('Y-m-d H:i',$val->time);
				}
			}
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);




		}

		//车辆运营公司
		//车辆运营公司、车辆类型 by  2016/9/20
		/*$query->andFilterWhere(['=','{{%car}}.`operating_company_id`',yii::$app->request->get('operating_company_id')]);*/
		/*if(yii::$app->request->post('operating_company_id')){
        	$operating_company_id = yii::$app->request->post('operating_company_id');
        	$query->andFilterWhere(['{{%car22}}.`operating_company_id`'=>$operating_company_id]);
        }
        var_dump(yii::$app->request->post('operating_company_id'));exit*/;

		$searchFormOptions['car_type'] = [];
		//车辆运营公司
        $oc = (new \yii\db\Query())->from('cs_operating_company')->where('is_del=0')->all();
        if($oc)
        {
        	$searchFormOptions['operating_company_id'] = [];
        	$searchFormOptions['operating_company_id'][] = ['value'=>'','text'=>'不限'];
        	
        	$adminInfo_operatingCompanyIds = @$_SESSION['backend']['adminInfo']['operating_company_ids'];
        	$super = $_SESSION['backend']['adminInfo']['super'];
        	$username = $_SESSION['backend']['adminInfo']['username'];
        	//除了开发人员和超级管理员不受管控外，其他人必须检测
			if($super || $username == 'administrator'){
				foreach($oc as $val){
					$searchFormOptions['operating_company_id'][] = ['value'=>$val['id'],'text'=>$val['name']];
        		}
			}else if($adminInfo_operatingCompanyIds){
        		$adminInfo_operatingCompanyIds = explode(',',$adminInfo_operatingCompanyIds);
        		foreach($oc as $val){
        			if(in_array($val['id'],$adminInfo_operatingCompanyIds)){
        				$searchFormOptions['operating_company_id'][] = ['value'=>$val['id'],'text'=>$val['name']];
        			}
        		}
        	}
        }

		$buttons = $this->getCurrentActionBtn();
		return $this->render('index',[
			'buttons'=>$buttons,
			'searchFormOptions'=>$searchFormOptions

			]);
	}
	
	public function actionAdd()
	{
		
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$session = yii::$app->session;
			$session->open();
			
			//登记编号，格式：GZ+日期+3位数（即该登记是系统当天登记的第几个，第一个是001，第二个是002…）
			$todayNo = (new \yii\db\Query())->select('order_no')->from('oa_repair')
			->where('create_time >=:start_time AND create_time <=:end_time',[':start_time'=>strtotime(date('Y-m-d')),':end_time'=>strtotime(date('Y-m-d'))+86400])
			->orderBy('order_no DESC')->one();
			
			if($todayNo){
				if(preg_match('/^BX(\d+)/i',$todayNo['order_no'],$data))
				{
					$currentNo = $data[1]+1;
					$order_no = 'BX'.$currentNo;
				}else{
					$currentNo = str_pad(1,6,0,STR_PAD_LEFT);
					$order_no = 'BX' . date('Ymd') . $currentNo;
				}
				
			}else{
				$currentNo = str_pad(1,6,0,STR_PAD_LEFT);
				$order_no = 'BX' . date('Ymd') . $currentNo;
			}
			
			
			//工单号
			//$order_no = 'BX' . date('Ymd') . $currentNo;
			//受理人
			$accept_name = $_SESSION['backend']['adminInfo']['name'];
			//工单类型
			$type   = yii::$app->request->post('type');
			$source   = yii::$app->request->post('source');
			$repair_name   = yii::$app->request->post('repair_name');
			$tel   = yii::$app->request->post('tel');
			$tel_time   = strtotime(yii::$app->request->post('tel_time'));
			$urgency   = yii::$app->request->post('urgency');
			$car_no   = yii::$app->request->post('car_no');
			$address  = yii::$app->request->post('address');
			$bearing   = yii::$app->request->post('bearing');
			$desc   = yii::$app->request->post('desc');
			$tel_content   = yii::$app->request->post('tel_content');
			$need_serve   = yii::$app->request->post('need_serve');
			//故障发生时间
			$fault_start_time   = strtotime(yii::$app->request->post('fault_start_time'));
			
			//所属运营公司
			$car_row = (new \yii\db\Query())->select('operating_company_id')->from('cs_car')->where("plate_number='{$car_no}' AND is_del = 0")->one();
			$operating_company_id = !empty($car_row) ? $car_row['operating_company_id'] : $_SESSION['backend']['adminInfo']['operating_company_id'];
			$db = \Yii::$app->db;
			$result = $db->createCommand()->insert('oa_repair',
					['order_no'		=> $order_no,
					'type'			=> $type,
				    'source'		=> $source,
					'repair_name'	=> $repair_name,
					'tel'			=> $tel,
					'tel_time'		=> $tel_time,
					'urgency'		=> $urgency,
					'car_no'		=> $car_no,
					'address'		=> $address,
					'bearing'		=> $bearing,
					'desc'			=> $desc,
					'tel_content'	=> $tel_content,
					'need_serve'	=> $need_serve,
					'accept_name'	=> $accept_name,
					'create_time'	=> time(),
					'time'          => time(),
					'status'		=> 1,
					'fault_start_time'=> $fault_start_time,
					'user_id'		=> $_SESSION['backend']['adminInfo']['id'],
					'operating_company_id'=>$operating_company_id,
					])->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '新增成功！';
				$this->send_email('process', 'repair', 'assigned', '报修工单指派',$operating_company_id);
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '新增失败！';
			}
			
			
			return json_encode($returnArr);
		}
		$cars = (new \yii\db\Query())->select('plate_number')->from('cs_car')->where('is_del = 0 ')->all();
		return $this->render('add',['cars'=>$cars]);
	}
	
	
	public function actionEdit()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$session = yii::$app->session;
			$session->open();
			
			//受理人
			$accept_name = $_SESSION['backend']['adminInfo']['name'];
			$id = yii::$app->request->post('id');
			//工单类型
			$type   = yii::$app->request->post('type');
			$source   = yii::$app->request->post('source');
			$repair_name   = yii::$app->request->post('repair_name');
			$tel   = yii::$app->request->post('tel');
			$tel_time   = strtotime(yii::$app->request->post('tel_time'));
			$urgency   = yii::$app->request->post('urgency');
			$car_no   = yii::$app->request->post('car_no');
			$address  = yii::$app->request->post('address');
			$bearing   = yii::$app->request->post('bearing');
			$desc   = yii::$app->request->post('desc');
			$tel_content   = yii::$app->request->post('tel_content');
			$need_serve   = yii::$app->request->post('need_serve');
			//故障发生时间
			$fault_start_time   = strtotime(yii::$app->request->post('fault_start_time'));
			
			//所属运营公司
			$car_row = (new \yii\db\Query())->select('operating_company_id')->from('cs_car')->where("plate_number='{$car_no}' AND is_del = 0")->one();
			$operating_company_id = !empty($car_row) ? $car_row['operating_company_id'] : $_SESSION['backend']['adminInfo']['operating_company_id'];
			$db = \Yii::$app->db;
			$result = $db->createCommand()->update('oa_repair',
					[
					'type'			=> $type,
					'source'		=> $source,
					'repair_name'	=> $repair_name,
					'tel'			=> $tel,
					'tel_time'		=> $tel_time,
					'urgency'		=> $urgency,
					'car_no'		=> $car_no,
					'address'		=> $address,
					'bearing'		=> $bearing,
					'desc'			=> $desc,
					'tel_content'	=> $tel_content,
					'need_serve'	=> $need_serve,
					'accept_name'	=> $accept_name,
					'time'	=> time(),
					'status'		=> 1,
					'fault_start_time'=> $fault_start_time,
					'operating_company_id'=>$operating_company_id
					],'id=:id',[':id'=>$id])->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '修改成功！';
				
				/* $row = (new \yii\db\Query())->select('operating_company_id')->from("oa_repair")->where('id=:id',[':id'=>$id])->one();
				$oc = $row['operating_company_id']; */
				$this->send_email('process', 'repair', 'assigned', '报修工单指派',$operating_company_id);
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '修改失败！';
			}
			return json_encode($returnArr);
			
		}
		$id = yii::$app->request->get('id');
		$result = (new \yii\db\Query())->from('oa_repair')->where('id=:id',[':id'=>$id])->one();
		$result['tel_time'] = !empty($result['tel_time']) ? date('Y-m-d H:i',$result['tel_time']):'';
		$result['fault_start_time'] = !empty($result['fault_start_time']) ? date('Y-m-d H:i',$result['fault_start_time']):'';
		
		
		$cars = (new \yii\db\Query())->select('plate_number')->from('cs_car')->where('is_del = 0 ')->all();
		return $this->render('edit',['cars'=>$cars,'result'=>$result]);
	}
	
	public function actionDelete()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$db = \Yii::$app->db;
			$result = $db->createCommand()->delete('oa_repair','id=:id',[':id'=>$id])->execute();
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
	 * 查看工单
	 */
	public function actionInfo()
	{
		$order_no = yii::$app->request->get('order_no');
		$id =yii::$app->request->get('id');
		//客户报修工单
		if(preg_match('/^BX(\d+)/i',$order_no,$data) && empty($id))
		{
			$result = (new \yii\db\Query())->from('oa_repair')->where("order_no='{$order_no}'")->one();
			if($result){
					switch ($result['type']){
						case 1:
							$result['type'] = '客户报修';
							break;
						case 2:
							$result['type'] = '车辆出险';
							break;
					}
			
					switch ($result['source']){
						case 1:
							$result['source'] = '400电话';
							break;
					}
			
					switch ($result['urgency']){
						case 1:
							$result['urgency'] = '一般紧急';
							break;
						case 2:
							$result['urgency'] = '比较紧急';
							break;
						case 3:
							$result['urgency'] = '非常紧急';
							break;
					}
					$result['tel_time'] = date('Y-m-d H:i',$result['tel_time']);
			}
			return $this->render('info',['result'=>$result]);
		}else{
			//我方报修工单
			
			$query = (new \yii\db\Query())->select('oa_car_maintain.*,cs_car.plate_number,cs_car.vehicle_dentification_number')->from('oa_car_maintain')->join('LEFT JOIN','cs_car','cs_car.id=oa_car_maintain.car_id');
			
			$result = $query->where('oa_car_maintain.id=:id and is_del=0',[':id'=>$id])->one();
			if($result){
				switch ($result['type']){
					case 3:
						$result['type'] = '我方报修';
						break;
					
				}
			}
			return $this->render('info1',['result'=>$result]);
		}
		
		
	}
	
	
	/**
	 * 工单指派
	 */
	public function actionAssign()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			$query = $db->select('*')->from('oa_repair');
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			$session = yii::$app->session;
			$session->open();
			$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
			$query->andWhere("operating_company_id in ({$ocs})");
			//按车牌号搜索
			$car_no = yii::$app->request->post('car_no');
			if($car_no)
			{
 				//$query->andWhere('car_no=:car_no',[':car_no'=>$car_no]);
				$query->andWhere(['like','car_no',$car_no]);
			}
			
			$assign_name = yii::$app->request->post('assign_name');
			if($assign_name)
			{
				$query->andWhere('assign_name=:assign_name',[':assign_name'=>$assign_name]);
			}
	
			$order_no= yii::$app->request->post('order_no');
			if($order_no)
			{
				$query->andWhere(['like','order_no',$order_no]);
			}
			
			$start_assign_time= yii::$app->request->post('start_assign_time');
			if($start_assign_time)
			{
				$query->andWhere('assign_time >=:start_assign_time',[':start_assign_time'=>strtotime($start_assign_time)]);
			}
			
			$end_assign_time= yii::$app->request->post('end_assign_time');
			if($end_assign_time)
			{
				$query->andWhere('assign_time <=:end_assign_time',[':end_assign_time'=>strtotime($end_assign_time)+86400]);
			}
			
			$type = yii::$app->request->post('type');
			if($type)
			{
				$query->andWhere('type=:type',[':type'=>$type]);
			}
			
			$status = yii::$app->request->post('status');
			if($status)
			{
				//已指派
				if($status == 2)
				{
					$query->andWhere('status >=2');
				}else{
					$query->andWhere('status =:status',[':status'=>$status]);
				}
				
			}
			
			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			if($sort)
			{
				$query->orderBy("{$sort} {$order}");
			}else{
				$query->orderBy("create_time DESC");
			}
			
			$total = $query->count();
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
			$result = $query->offset($pages->offset)->limit($pages->limit)->all();
			$result = json_decode(json_encode($result));
			if($result){
				foreach ($result as $val)
				{
					switch ($val->type){
						case 1:
							$val->type = '客户报修';
							break;
						case 2:
							$val->type = '车辆出险';
							break;
					}
			
					switch ($val->source){
						case 1:
							$val->source = '400电话';
							break;
					}
			
					switch ($val->urgency){
						case 1:
							$val->urgency = '一般紧急';
							break;
						case 2:
							$val->urgency = '比较紧急';
							break;
						case 3:
							$val->urgency = '非常紧急';
							break;
					}
					$val->assign_time   =  !empty($val->assign_time) ? date('Y-m-d H:i',$val->assign_time) : '';
				}
			}
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
		$buttons = $this->getCurrentActionBtn();
		return $this->render('assign',['buttons'=>$buttons]);
	}
	
	/**
	 * 指派工单
	 */
	public function actionAssigned()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$assign_name = yii::$app->request->post('assign_name');
			$assign_remark = yii::$app->request->post('assign_remark');			
			
			$db = \Yii::$app->db;
			$result = $db->createCommand()->update('oa_repair',
					[
					'status'       => 2,
					'assign_name'  => $assign_name,
					'assign_remark'=> $assign_remark,
					'assign_time'  => time(),
					],'id=:id',[':id'=>$id])->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '指派成功！';
				$row = (new \yii\db\Query())->select('operating_company_id')->from("oa_repair")->where('id=:id',[':id'=>$id])->one();
				$oc = $row['operating_company_id'];
				$this->send_email('process', 'repair', 'affirm', '报修工单确认',$oc,$assign_name);
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '指派失败！';
			}
			
			return json_encode($returnArr);
		}
		$id = yii::$app->request->get('id');
		//$departments = (new \yii\db\Query())->from('cs_department')->where('is_del = 0')->all();
		$car_site = (new \yii\db\Query())->select('oa_extract_car_site.*,cs_admin.name as user_name')
			->from('oa_extract_car_site')->join('LEFT JOIN','cs_admin','cs_admin.id = oa_extract_car_site.user_id')
			->where('oa_extract_car_site.is_del=0 ')->all();		
		return $this->render('assigned',['car_site'=>$car_site,'id'=>$id]);
	}
	
	public function actionGetUser()
	{
		$department_id = yii::$app->request->post('department_id');
		$result = (new \yii\db\Query())->select('name')->from('cs_admin')->where('department_id = :department_id AND is_del = 0',[':department_id'=>$department_id])->all();
		return json_encode($result);
	}
	
	/**
	 * 取消指派
	 */
	public function actionCancelAssigned()
	{
		$id = yii::$app->request->get('id');
		
		$result = (new \yii\db\Query())->from('oa_repair')->where('id=:id AND status =2',[':id'=>$id])->one();
		//工单不是已指派状态
		if(empty($result))
		{
			$returnArr['status'] = false;
			$returnArr['info'] = '当前状态不能取消指派！';
			return json_encode($returnArr);
		}
		
		$db = \Yii::$app->db;
		$result = $db->createCommand()->update('oa_repair',
				[
				'status'       => 1,
				'assign_name'  => '',
				'assign_remark'=> '',
				'assign_time'  => '',
				],'id=:id',[':id'=>$id])->execute();
		if($result)
		{
			$returnArr['status'] = true;
			$returnArr['info'] = '取消成功！';
		}else{
			$returnArr['status'] = false;
			$returnArr['info'] = '取消失败！';
		}
		
		return json_encode($returnArr);
	}
	
	/**
	 * 报修工单确认列表
	 */
	public function actionConfirm()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			$query = $db->select('*')->from('oa_repair');
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			$session = yii::$app->session;
			$session->open();
			$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
			$query->andWhere("operating_company_id in ({$ocs})");
			//按车牌号搜索
			$car_no = yii::$app->request->post('car_no');
			if($car_no)
			{
				$query->andWhere(['like','car_no',$car_no]);;
			}
			
			$assign_name = yii::$app->request->post('assign_name');
			if($assign_name)
			{
				$query->andWhere('assign_name=:assign_name',[':assign_name'=>$assign_name]);
			}
	
			$order_no= yii::$app->request->post('order_no');
			if($order_no)
			{
				$query->andWhere(['like','order_no',$order_no]);
			}
			
			$start_confirm_time= yii::$app->request->post('start_confirm_time');
			if($start_confirm_time)
			{
				$query->andWhere('confirm_time >=:start_confirm_time',[':start_confirm_time'=>strtotime($start_confirm_time)]);
			}
			
			$end_confirm_time= yii::$app->request->post('end_confirm_time');
			if($end_confirm_time)
			{
				$query->andWhere('confirm_time <=:end_confirm_time',[':end_confirm_time'=>strtotime($end_confirm_time)+86400]);
			}
			
			$type = yii::$app->request->post('type');
			if($type)
			{
				$query->andWhere('type=:type',[':type'=>$type]);
			}
			
			$status = yii::$app->request->post('status');
			if($status)
			{
				//已确认
				if($status == 3)
				{
					$query->andWhere('status >=3');
				}else{
					$query->andWhere('status =:status',[':status'=>$status]);
				}
				
			}
			//已指派状态
			$query->andWhere('status >=2');
			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			if($sort)
			{
				$query->orderBy("{$sort} {$order}");
			}else{
				$query->orderBy("create_time DESC");
			}
		
			$total = $query->count();
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
			$result = $query->offset($pages->offset)->limit($pages->limit)->all();
			$result = json_decode(json_encode($result));
			if($result){
				foreach ($result as $val)
				{
					switch ($val->type){
						case 1:
							$val->type = '客户报修';
							break;
						case 2:
							$val->type = '车辆出险';
							break;
					}
		
					switch ($val->source){
						case 1:
							$val->source = '400电话';
							break;
					}
		
					switch ($val->urgency){
						case 1:
							$val->urgency = '一般紧急';
							break;
						case 2:
							$val->urgency = '比较紧急';
							break;
						case 3:
							$val->urgency = '非常紧急';
							break;
					}
					$val->assign_time   =  !empty($val->assign_time) ? date('Y-m-d H:i',$val->assign_time) : '';
					$val->confirm_time   =  !empty($val->confirm_time) ? date('Y-m-d H:i',$val->confirm_time) : '';
				}
			}
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
		$buttons = $this->getCurrentActionBtn();
		return $this->render('confirm',['buttons'=>$buttons]);
	}
	
	/**
	 * 确认工单
	 */
	public function actionAffirm()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{	
			$id = yii::$app->request->post('id');
			$result = (new \yii\db\Query())->from('oa_repair')->where('id=:id AND status =2',[':id'=>$id])->one();
						
			//工单不是已指派状态
			if(empty($result))
			{
				$returnArr['status'] = false;
				$returnArr['info'] = '当前状态不能确认工单！';
				return json_encode($returnArr);
			}
			
			
			$is_voice = yii::$app->request->post('is_voice');
			$is_visit = yii::$app->request->post('is_visit');
			$is_attendance = yii::$app->request->post('is_attendance');
			$carry = '';
			$is_use_car = '';
			$use_car_no = '';
			//需要出外勤
			if($is_attendance)
			{
				$carry = yii::$app->request->post('carry');
				$is_use_car = yii::$app->request->post('is_use_car');
				//需要申请用车
				if($is_use_car)
				{
					$use_car_no = yii::$app->request->post('use_car_no');
				}
				
				//工单已确认出外勤中
				$status = 3;
			}else{
				//工单已确认等待归档
				$status = 4;
			}
			$session = yii::$app->session;
			$session->open();
			$name = $_SESSION['backend']['adminInfo']['name'];
			
			$confirm_remark = yii::$app->request->post('confirm_remark');
			$visit_time		=  strtotime(yii::$app->request->post('visit_time'));
			$db = \Yii::$app->db;
			$result = $db->createCommand()->update('oa_repair',
					[
					'is_voice'       	=> $is_voice,
					'is_visit' 	 		=> $is_visit,
					'is_attendance'		=> $is_attendance,
					'carry'  			=> $carry,
					'is_use_car'  		=> $is_use_car,
					'use_car_no'	  	=> $use_car_no,
					'confirm_name'      => $name,
					'confirm_remark'  	=> $confirm_remark,
					'confirm_time'		=> time(),
					'status'			=> $status,
					'visit_time'		=> $visit_time,
					],'id=:id',[':id'=>$id])->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '确认成功！';
				$row = (new \yii\db\Query())->select('operating_company_id')->from("oa_repair")->where('id=:id',[':id'=>$id])->one();
				$oc = $row['operating_company_id'];
				$this->send_email('process', 'repair', 'field-reg', '外勤服务登记',$oc);
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '确认失败！';
			}
			
			return json_encode($returnArr);
		}
		$id = yii::$app->request->get('id');
		//  自用/备用替换车
		$cars = (new \yii\db\Query())->select('cs_car_stock.*,cs_car.plate_number')->from('cs_car_stock')->join('LEFT JOIN','cs_car','cs_car.id = cs_car_stock.car_id')->having('is_del = 0 AND car_status = 2 AND department_id=5')->all();
		return $this->render('affirm',['cars'=>$cars,'id'=>$id]);
	}
	
	/**
	 * 查看确认信息
	 */
	public function actionAffirmInfo()
	{
		$id = yii::$app->request->get('id');
		$result = (new \yii\db\Query())->from('oa_repair')->where('id=:id',[':id'=>$id])->one();
		return $this->render('affirm-info',['result'=>$result]);
	}
	
	/**
	 * 外勤服务登记列表
	 */
	public function actionField()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			$query = $db->select(
					'oa_repair.id,
					oa_repair.car_no,
					oa_repair.order_no,
					oa_repair.type,
					oa_repair.desc,
					oa_repair.status,
					oa_repair.assign_name,
					oa_repair.operating_company_id,
					'
					)->from('oa_repair')->where('is_attendance = 1');
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			
			//按车牌号搜索
			$car_no = yii::$app->request->post('car_no');
			if($car_no)
			{
				$query->andWhere(['like','car_no',$car_no]);;
			}
	
			$order_no= yii::$app->request->post('order_no');
			if($order_no)
			{
				$query->andWhere(['like','order_no',$order_no]);
			}
			
			
			
			$type = yii::$app->request->post('type');
			if($type)
			{
				$query->andWhere('type=:type',[':type'=>$type]);
			}
			
			$status = yii::$app->request->post('status');
			if($status)
			{
				//已外勤登记
				if($status == 5)
				{
					$query->andWhere('status >=5');
				}else{
					$query->andWhere('status =:status',[':status'=>$status]);
				}
				
			}
			//已确认状态
			$query->andWhere('status IN (3,5,6,7)');
			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			
			//与车辆基本信息表关联
			$query1 = (new \yii\db\Query())->select('a.*')->from(['a'=>$query])
				->join('LEFT JOIN','cs_car','cs_car.plate_number = a.car_no')->where('is_del=0');
			
			
			
			$brand_id = yii::$app->request->post('brand_id');
			//查品牌，查父品牌时也会查出子品牌
			if($brand_id)
			{
				$brandQuery = (new \yii\db\Query())->select('id')->from('cs_car_brand')->where('id=:id OR pid=:pid',[':id'=>$brand_id,':pid'=>$brand_id]);
				$query1->andWhere(['brand_id' => $brandQuery]);
			}	
			
			$query2 = (new \yii\db\Query())
				->select('b.*,is_go_scene,maintain_scene,replace_car,accept_name as record_user,time as field_time')
				->from(['b'=>$query1])
				->join('LEFT JOIN','oa_field_record','oa_field_record.repair_id = b.id');
			
			$start_wq_time= yii::$app->request->post('start_wq_time');
			if($start_wq_time)
			{
				$query2->andWhere('oa_field_record.time >=:start_wq_time',[':start_wq_time'=>strtotime($start_wq_time)]);
			}
			
			$end_wq_time= yii::$app->request->post('end_wq_time');
			if($end_wq_time)
			{
				$query2->andWhere('oa_field_record.time <=:end_wq_time',[':end_wq_time'=>strtotime($end_wq_time)+86400]);
			}
			$session = yii::$app->session;
			$session->open();
			$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
			$query2->andWhere("operating_company_id in ({$ocs})");
			
			$total = $query2->count();
			
			
			if($sort)
			{
				$query2->orderBy("{$sort} {$order}");
			}else{
				$query2->orderBy("status ASC , field_time DESC");
			}
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
			$result = $query2->offset($pages->offset)->limit($pages->limit)->all();
			$result = json_decode(json_encode($result));
			if($result){
				foreach ($result as $val)
				{
					switch ($val->type){
						case 1:
							$val->type = '客户报修';
							break;
						case 2:
							$val->type = '车辆出险';
							break;
					}
					
					if($val->is_go_scene >=0)
					{
						$val->is_go_scene = !empty($val->is_go_scene) ? '是':'否';
					}else{
						$val->is_go_scene = '';
					}
					
					//是否替换车辆
					if($val->replace_car >=0)
					{
						$val->is_replace_car = !empty($val->replace_car) ? '是':'否';
					}else{
						$val->is_replace_car = '';
					}
					
					$val->field_time = !empty($val->field_time) ? date('Y-m-d H:i',$val->field_time) :'';
				}
			}
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
		$buttons = $this->getCurrentActionBtn();
		return $this->render('field',['buttons'=>$buttons]);
	}
	
	/**
	 * 外勤登记
	 */
	public function actionFieldReg()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$session = yii::$app->session;
			$session->open();
			
			$id = yii::$app->request->post('id');
			//受理人
			$accept_name = $_SESSION['backend']['adminInfo']['name'];
			
			$car_no = yii::$app->request->post('car_no');
			$repair_id = yii::$app->request->post('repair_id');
			$arrive_time = strtotime(yii::$app->request->post('arrive_time'));
			$scene_desc = yii::$app->request->post('scene_desc');
			$scene_result = yii::$app->request->post('scene_result');
			$is_go_scene = yii::$app->request->post('is_go_scene');
			
			$maintain_scene = '';
			$maintain_name = '';
			$maintain_tel = '';
			$maintain_no = '';
			$expect_time = '';
			$replace_car = '';
			$replace_way = '';
			$replace_start_time = '';
			$replace_end_time = '';
			$field_tolls = '';
			$parking = '';
			if($is_go_scene)
			{
				$maintain_scene = yii::$app->request->post('maintain_scene');
				$maintain_name = yii::$app->request->post('maintain_name');
				$maintain_tel = yii::$app->request->post('maintain_tel');
				$maintain_no = yii::$app->request->post('maintain_no');
				$expect_time = strtotime(yii::$app->request->post('expect_time'));
				$is_replace_car = yii::$app->request->post('is_replace_car');
				//是否替换车
				if($is_replace_car)
				{
					$replace_car = yii::$app->request->post('replace_car');
					$replace_way = yii::$app->request->post('replace_way');
					$replace_start_time = strtotime(yii::$app->request->post('replace_start_time'));
					$replace_end_time = strtotime(yii::$app->request->post('replace_end_time'));
				}
				
				$field_tolls = yii::$app->request->post('field_tolls');
				$parking = yii::$app->request->post('parking');
			}

			$car_no_img = yii::$app->request->post('car_no_img');
			$dashboard_img = yii::$app->request->post('dashboard_img');
			$fault_scene_img = yii::$app->request->post('fault_scene_img');
			$fault_location_img = yii::$app->request->post('fault_location_img');
			$field_record_img = yii::$app->request->post('field_record_img');
			$maintain_jieche_img = yii::$app->request->post('maintain_jieche_img');
			
			$db = \yii::$app->db;
			//开启事物
			$transaction = $db->beginTransaction();
			try {
				if(yii::$app->request->post('id'))
				{
					$result = $db->createCommand()->update('oa_field_record',
							['repair_id'       	=> $repair_id,
							'arrive_time' 	 	=> $arrive_time,
							'scene_desc'		=> $scene_desc,
							'scene_result'  	=> $scene_result,
							'is_go_scene'  		=> $is_go_scene,
							'maintain_scene'	=> $maintain_scene,
							'maintain_name'     => $maintain_name,
							'maintain_tel'  	=> $maintain_tel,
							'maintain_no'		=> $maintain_no,
							'expect_time'		=> $expect_time,
							'replace_car'       => $replace_car,
							'replace_way' 	 	=> $replace_way,
							'replace_start_time'=> $replace_start_time,
							'replace_end_time'  => $replace_end_time,
							'field_tolls'  		=> $field_tolls,
							'parking'	  		=> $parking,
							'car_no_img'      	=> $car_no_img,
							'dashboard_img'  	=> $dashboard_img,
							'fault_scene_img'	=> $fault_scene_img,
							'fault_location_img'=> $fault_location_img,
							'field_record_img'	=> $field_record_img,
							'maintain_jieche_img'=> $maintain_jieche_img,
							'accept_name'		=> $accept_name,
							'time'				=>time(),
							],'id=:id',[':id'=>$id])->execute();
				}else{
					$result = $db->createCommand()->insert('oa_field_record',
							['repair_id'       	=> $repair_id,
							'arrive_time' 	 	=> $arrive_time,
							'scene_desc'		=> $scene_desc,
							'scene_result'  	=> $scene_result,
							'is_go_scene'  		=> $is_go_scene,
							'maintain_scene'	=> $maintain_scene,
							'maintain_name'     => $maintain_name,
							'maintain_tel'  	=> $maintain_tel,
							'maintain_no'		=> $maintain_no,
							'expect_time'		=> $expect_time,
							'replace_car'       => $replace_car,
							'replace_way' 	 	=> $replace_way,
							'replace_start_time'=> $replace_start_time,
							'replace_end_time'  => $replace_end_time,
							'field_tolls'  		=> $field_tolls,
							'parking'	  		=> $parking,
							'car_no_img'      	=> $car_no_img,
							'dashboard_img'  	=> $dashboard_img,
							'fault_scene_img'	=> $fault_scene_img,
							'fault_location_img'=> $fault_location_img,
							'field_record_img'	=> $field_record_img,
							'maintain_jieche_img'=> $maintain_jieche_img,
							'accept_name'		=> $accept_name,
							'time'				=>time(),
							])->execute();
				}
				
				
				//修改状态为车辆维修中
				$db->createCommand()->update('oa_repair',['status'=>5],'id=:id',[':id'=>$repair_id])->execute();
				
				
				//故障车辆替换自用备用、车辆
				if($replace_car)
				{
					//查找故障车辆 
					$car_row = (new \yii\db\Query())->select('id')->from('cs_car')->where("plate_number=:plate_number AND is_del =0",[':plate_number'=>$car_no])->one();
					//被替换id
					$replace_car_id = $car_row['id'];
					
					//获取被替换车所属企业客户
					$let_record = $db->createCommand(
							"select cCustomer_id from cs_car_let_record where car_id={$replace_car_id} and back_time=0 and is_del=0 limit 1"
							)->queryOne();
					if(!$let_record){
						$let_record = $db->createCommand(
								"select ctpd_pCustomer_id from cs_car_trial_protocol_details where ctpd_car_id={$replace_car_id} and ctpd_back_date is null and ctpd_is_del=0 limit 1"
								)->queryOne();
						if(!$let_record){
							$returnArr['status'] = false;
							$returnArr['info'] = '被替换车辆无出租记录！';
							return json_encode($returnArr);
						}else {
							$c_customer_id = $let_record['ctpd_pCustomer_id'];
						}
					}else {
						$c_customer_id = $let_record['cCustomer_id'];
					}
					
					
					//替换车辆信息
					$car = (new \yii\db\Query())->select('id')->from('cs_car')->where('plate_number=:plate_number',[':plate_number'=>$replace_car])->one();
					
					//替换车辆id
					$car_id = $car['id'];
					//自用、备用车id
					$car_stock = (new \yii\db\Query())->select('id')->from('cs_car_stock')->where('car_id=:car_id',[':car_id'=>$car_id])->one();
					
					//修改车辆基本信息
					$db->createCommand()->update('cs_car',['car_status2'=>'REPLACE'],'id=:id AND is_del =0',[':id'=>$car_id])->execute();
					
					//修改自用备用车 状态
					$db->createCommand()->update('cs_car_stock',
							['car_status'=>1,
							'c_customer_id'=>$c_customer_id,
							'add_aid'=>$_SESSION['backend']['adminInfo']['id'],
							'add_time'=>date('Y-m-d H:i')],
							'id =:id',[':id'=>$car_stock['id']]
							)->execute();
					
					//替换记录
					$db->createCommand()->insert('cs_car_stock_replace_log', [
							'car_stock_id' => $car_stock['id'],
							'car_id' => $car_id,
							'c_customer_id' => $c_customer_id,
							'replace_car_id' => $replace_car_id,
							'replace_desc' => '客户报修',
							'replace_start_time' => yii::$app->request->post('replace_start_time'),
							'replace_end_time' => yii::$app->request->post('replace_end_time'),
							'add_aid' => $_SESSION['backend']['adminInfo']['id'],
							'add_time' => date('Y-m-d H:i:s')
							])->execute();
					
				}
				
				
				$transaction->commit();
				
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！';
				
				$row = (new \yii\db\Query())->select('operating_company_id')->from("oa_repair")->where('id=:id',[':id'=>$id])->one();
				$oc = $row['operating_company_id'];
				$this->send_email('process', 'repair', 'archive-confirm', '服务工单归档',$oc);
			} catch (Exception $e) {
				//回滚
				$transaction->rollBack();
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
			}
			return json_encode($returnArr);
			
		}
		$repair_id = yii::$app->request->get('id');
		$repair_row = (new \yii\db\Query())->select('car_no')->from('oa_repair')->where('id=:id',[':id'=>$repair_id])->one();
		$car_no = $repair_row['car_no'];
		
		//=====自用/备用替换车start
		$carsQuery = (new \yii\db\Query())
				->select('cs_car_stock.*,cs_car.plate_number')
				->from('cs_car_stock')
				->join('LEFT JOIN','cs_car','cs_car.id = cs_car_stock.car_id and cs_car.is_del=0')
				->where('cs_car_stock.is_del = 0 AND cs_car_stock.car_status = 2');
		//检测是否要根据当前登录人员所属运营公司来显示列表数据
		$isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
		if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
			$carsQuery->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
		}
// 		exit($carsQuery->createCommand()->getRawSql());
		$cars = $carsQuery->all();
		
		//=====自用/备用替换车end
		// 维修站点
		$maintain_scenes = (new \yii\db\Query())->select('site_name as text')->from('oa_service_site')->all();
		//外勤登记信息
		$field_record = (new \yii\db\Query())->from('oa_field_record')->where('repair_id=:repair_id',[':repair_id'=>$repair_id])->one();
		
		return $this->render('field-reg',['repair_id'=>$repair_id,'cars'=>$cars,'maintain_scenes'=>$maintain_scenes,'car_no'=>$car_no,'field_record'=>$field_record]);
	}
	
	/**
	 * 查看外勤登记详情
	 */
	public function actionFieldInfo()
	{
		$repair_id = yii::$app->request->get('id');
		$row = (new \yii\db\Query())->from('oa_field_record')->where('repair_id=:repair_id',[':repair_id'=>$repair_id])->one();
		
		$repair_row = (new \yii\db\Query())->select('car_no')->from('oa_repair')->where('id=:id',[':id'=>$repair_id])->one();
		$row['car_no'] = $repair_row['car_no'];
		
		return $this->render('field-info',['row'=>$row]);
	}
	
	
	/**
	 * 查看车辆维修登记
	 */
	public function actionMaintain()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$query = (new \yii\db\Query())->select(
					'oa_car_maintain.car_id,
					oa_car_maintain.id,
					oa_car_maintain.order_no,
					oa_car_maintain.type,
					oa_car_maintain.status,
					oa_car_maintain.scene_desc,
					oa_car_maintain.maintain_way,
					oa_car_maintain.maintain_scene,
					oa_car_maintain.replace_car,
					oa_car_maintain.accept_name,
					oa_car_maintain.time,
					oa_car_maintain.fault_report_time,
					oa_car_maintain.fault_start_time,
					oa_car_maintain.into_factory_time,
					cs_car.plate_number,
					oa_car_maintain_result.leave_factory_time,
					oa_car_maintain_result.fault_why,
					oa_car_maintain_result.maintain_method,
					oa_car_maintain_fault.total_code
					')->from('oa_car_maintain')
					->join('LEFT JOIN','cs_car','cs_car.id = oa_car_maintain.car_id and cs_car.is_del=0')
					->join('LEFT JOIN','cs_operating_company','oa_car_maintain.operating_company_id = cs_operating_company.id')
					->join('LEFT JOIN','oa_car_maintain_result','oa_car_maintain.order_no = oa_car_maintain_result.order_no')
					->join('LEFT JOIN','oa_car_maintain_fault','oa_car_maintain.id = oa_car_maintain_fault.maintain_id');
			$brand_id = yii::$app->request->post('brand_id');
			$customer_name = yii::$app->request->post('customer_name');
			if($brand_id){
				$query->join('LEFT JOIN','cs_car_brand','cs_car.brand_id = cs_car_brand.id and cs_car_brand.is_del=0');
			}
			if($customer_name){
				$query->join('LEFT JOIN','cs_customer_company','oa_car_maintain.cCustomer_id = cs_customer_company.id');
				$query->join('LEFT JOIN','cs_customer_personal','oa_car_maintain.pCustomer_id = cs_customer_personal.id');
			}
			
			//查询条件开始
			$car_no = yii::$app->request->post('car_no');
			if($car_no)
			{
				$query->andWhere(['like','cs_car.plate_number',$car_no]);
			}
			$order_no = yii::$app->request->post('order_no');
			if($order_no)
			{
				$query->andWhere(['like','oa_car_maintain.order_no',$order_no]);
			}
			$start_wx_time = yii::$app->request->post('start_wx_time');
			if($start_wx_time)
			{
				$query->andWhere('oa_car_maintain.time >=:start_wx_time',[':start_wx_time'=>strtotime($start_wx_time)]);
			}
			$end_wx_time = yii::$app->request->post('end_wx_time');
			if($end_wx_time)
			{
				$query->andWhere('oa_car_maintain.time <=:end_wx_time',[':end_wx_time'=>strtotime($end_wx_time)+86400]);
			}
			$type = yii::$app->request->post('type');
			if($type)
			{
				$query->andWhere('oa_car_maintain.type=:type',[':type'=>$type]);
			}
			$status = yii::$app->request->post('status');
			if($status)
			{
				//维修登记已结案状态 , 状态大于7即可
				if($status == 8)
				{
					$query->andWhere('status >=7');
				}else{
					$query->andWhere('status =:status',[':status'=>$status]);
				}
			}
			//查品牌，查父品牌时也会查出子品牌
			if($brand_id)
			{
				$query->andWhere(
						[
						'or',
						['cs_car_brand.id' => $brand_id],
						['cs_car_brand.pid' => $brand_id]
						]);
			}
			$start_gz_time = yii::$app->request->post('start_gz_time');
			if($start_gz_time)
			{
				$query->andWhere('oa_car_maintain.fault_start_time >=:start_gz_time',[':start_gz_time'=>strtotime($start_gz_time)]);
			}
			$end_gz_time = yii::$app->request->post('end_gz_time');
			if($end_gz_time)
			{
				$query->andWhere('oa_car_maintain.fault_start_time <=:end_gz_time',[':end_gz_time'=>strtotime($end_gz_time)+86400]);
			}
			$accept_name = yii::$app->request->post('accept_name');
			if($accept_name)
			{
				$query->andWhere(['like','oa_car_maintain.accept_name',$accept_name]);
			}
			$query->andFilterWhere([
					'or',
					['like','{{%customer_company}}.`company_name`',$customer_name],
					['like','{{%customer_personal}}.`id_name`',$customer_name]
					]);
			$pid = yii::$app->request->post('pid');
			if($pid)
			{
				$query->andWhere(['like','oa_car_maintain_fault.tier_pid',','.$pid.',']);
			}
			$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
			$query->andWhere("oa_car_maintain.operating_company_id in ({$ocs})");
			//查询条件结束
			$total = $query->count();
			
// 			echo $query->createCommand()->getRawSql();exit;
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			if($sort)
			{
				$query->orderBy("{$sort} {$order}");
			}else{
				$query->orderBy("status ASC , time DESC");
			}
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
			$result = $query->offset($pages->offset)->limit($pages->limit)->all();
// 			print_r($result);
// 			exit;
			$result = json_decode(json_encode($result));
			$type_arr = array(1=>'客户报修',2=>'保险事故',3=>'我方报修');
			$maintain_way_arr = array(1=>'进厂维修',2=>'现场维修',3=>'自修');
			if($result){
				foreach ($result as $val)
				{
					$val->type = @$type_arr[$val->type];
					$val->maintain_way = @$maintain_way_arr[$val->maintain_way];
					//是否替换车辆
					if($val->replace_car >=0)
					{
						$val->is_replace_car = !empty($val->replace_car) ? '是':'否';
					}else{
						$val->is_replace_car = '';
					}
					$val->time = !empty($val->time) ? date('Y-m-d H:i',$val->time) :'';
					
					
					//车辆是否是进厂维修?
					if($val->maintain_way == '进厂维修')
					{
						//兼容以前逻辑 （以前没有进厂时间）  现在按故障上报时间进行倒计时计算
						$t = !empty($val->into_factory_time) ? $val->into_factory_time : $val->fault_report_time;
						//车辆是否进行了维修登记?
						if($val->leave_factory_time)
						{	
							//维修出厂时间与故障登记中的  故障上报时间  相差不到48小时
							if($val->leave_factory_time -$t <= 48*3600)
							{
								$val->countdown = '及时';
							}else{
								$val->countdown = '超时';
							}
						
						}else{
							//计算当前时间 到  故障上报时间+48小时    。倒计时
							$countdown = round(($t + 48*3600  - time())/3600);
							if($countdown >0)
							{
								$val->countdown = $countdown.'小时';
							}else{
								$val->countdown = '超时';
							}
						}
					}
					$val->fault_start_time   = !empty($val->fault_start_time) ? date('Y-m-d H:i',$val->fault_start_time) :'';
					$val->leave_factory_time = !empty($val->leave_factory_time) ? date('Y-m-d H:i',$val->leave_factory_time) :'';
				}
			}
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
		$buttons = $this->getCurrentActionBtn();
		return $this->render('maintain',['buttons'=>$buttons]);
	}
	
	
	/**
	 * 插入进厂维修登记数据
	 */
	public function actionMaintainAdd()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$session = yii::$app->session;
			$session->open();
			
			//登记编号，格式：GZ+日期+3位数（即该登记是系统当天登记的第几个，第一个是001，第二个是002…）
			$todayNo = (new \yii\db\Query())->select('order_no')->from('oa_car_maintain')
			->where('time >=:start_time AND time <=:end_time and order_no like "%WX%"',[':start_time'=>strtotime(date('Y-m-d')),':end_time'=>strtotime(date('Y-m-d'))+86400])
			->orderBy('time DESC')->one();
			
			
			if($todayNo){
				if(preg_match('/^WX(\d+)/i',$todayNo['order_no'],$data))
				{
					
					$currentNo = $data[1]+1;
					$order_no = 'WX'.$currentNo;
				}else{
					$currentNo = str_pad(1,6,0,STR_PAD_LEFT);
					$order_no = 'WX' . date('Ymd') . $currentNo;
				}
			
			}else{
				$currentNo = str_pad(1,6,0,STR_PAD_LEFT);
				$order_no = 'WX' . date('Ymd') . $currentNo;
			}
			
			
			
			$type = yii::$app->request->post('type');
			$claim_id = "";
			if (yii::$app->request->post('claim_id') !== null) {
				$claim_id = yii::$app->request->post('claim_id');
			}
			//echo $id;exit;
			if($type != 3 && $type != 2)
			{
				$order_no = yii::$app->request->post('order_no');
			}
			
			
			$car_id = yii::$app->request->post('car_id');
			//$car_no = yii::$app->request->post('car_no');
			$fault_start_time = strtotime(yii::$app->request->post('fault_start_time'));
			//$feedback_time = strtotime(yii::$app->request->post('feedback_time'));
			$feedback_name = yii::$app->request->post('feedback_name');
			$tel = yii::$app->request->post('tel');
			$accept_name = yii::$app->request->post('accept_name');
			//$fault_report_time = strtotime(yii::$app->request->post('fault_report_time'));
			$fault_address = yii::$app->request->post('fault_address');
			$scene_desc = yii::$app->request->post('scene_desc');
			$scene_result = yii::$app->request->post('scene_result');
			$maintain_way = yii::$app->request->post('maintain_way');
			
			$road_situation = [];
			$road_situation = yii::$app->request->post('road_situation');
			$road_situation = json_encode($road_situation);
			
			$weather_situation = yii::$app->request->post('weather_situation');
			$temperature_situation = yii::$app->request->post('temperature_situation');
			$vehicle_speed = yii::$app->request->post('vehicle_speed');
			$current_mileage = yii::$app->request->post('current_mileage');
			$vehicle_launch = yii::$app->request->post('vehicle_launch');
			$is_trailer = yii::$app->request->post('is_trailer');
			$indicator_light = $this->_indicator_light_info(yii::$app->request->post('indicator_light'));
			$indicator_light = !empty($indicator_light) ? json_encode($indicator_light):'';
			
			
			$maintain_scene = '';
			$maintain_name = '';
			$maintain_tel = '';
			$maintain_no = '';
			$replace_car = '';
			$replace_way = '';
			$replace_start_time = '';
			$replace_end_time = '';
			$field_tolls = '';
			$parking = '';
			$maintain_worker = '';
			$maintain_worker_tel = '';
			if($maintain_way != 3)
			{
				$maintain_scene = yii::$app->request->post('maintain_scene');
				$maintain_name = yii::$app->request->post('maintain_name');
				$maintain_tel = yii::$app->request->post('maintain_tel');
				$maintain_no = yii::$app->request->post('maintain_no');
				$maintain_worker = yii::$app->request->post('maintain_worker');
				$maintain_worker_tel = yii::$app->request->post('maintain_worker_tel');
			}
			$into_factory_time = '';
			if($maintain_way == 1)
			{
				$into_factory_time = strtotime(yii::$app->request->post('into_factory_time'));
			}
			
			$expect_time = strtotime(yii::$app->request->post('expect_time'));
			$car_no_img = yii::$app->request->post('car_no_img');
			$dashboard_img = yii::$app->request->post('dashboard_img');
			$fault_scene_img = yii::$app->request->post('fault_scene_img');
			$fault_location_img = yii::$app->request->post('fault_location_img');
			$maintain_jieche_img = yii::$app->request->post('maintain_jieche_img');
			
			$fault_code =  yii::$app->request->post('fault_code');
			$car_img1 = yii::$app->request->post('car_img1');
			$car_img2 = yii::$app->request->post('car_img2');
			$car_img3 = yii::$app->request->post('car_img3');
			$db = \yii::$app->db;

			//查找维修时客户
			$let_record = (new \yii\db\Query())->select('cCustomer_id,pCustomer_id')->from('cs_car_let_record')
			->where("car_id=:car_id  AND (({$fault_start_time}>=let_time AND {$fault_start_time}<=back_time) or ({$fault_start_time}>=let_time and back_time=0)) AND is_del=0",[':car_id'=>$car_id])->orderBy('let_time DESC')->one();
			
			$cCustomer_id = 0;
			$pCustomer_id = 0;
			if($let_record)
			{
				//车牌租给了企业用户
				if($let_record['cCustomer_id'])
				{
					$cCustomer_id = $let_record['cCustomer_id'];
				}else{
					$pCustomer_id = $let_record['pCustomer_id'];
				}
			}else{
				//查询出车辆是否在试驾
				$fault_start_date = date('Y-m-d',$fault_start_time);
				
				$trial_record = (new \yii\db\Query())->select('ctpd_cCustomer_id,ctpd_pCustomer_id')->from('cs_car_trial_protocol_details')
				->where("ctpd_car_id=:ctpd_car_id  AND (('{$fault_start_date}'>=ctpd_deliver_date AND '{$fault_start_date}'<=ctpd_back_date) or ('{$fault_start_date}'>=ctpd_deliver_date and ctpd_back_date is null)) AND ctpd_is_del=0",[':ctpd_car_id'=>$car_id])->orderBy('ctpd_deliver_date DESC')->one();
				
				if($trial_record)
				{
					if($trial_record['ctpd_cCustomer_id'])
					{
						$cCustomer_id = $trial_record['ctpd_cCustomer_id'];
					}else{
						$pCustomer_id = $trial_record['ctpd_pCustomer_id'];
					}
				}
			}
			//查找维修时客户end
			
			$result = $db->createCommand()->insert('oa_car_maintain',
					[
					'car_id'			=> $car_id,
					'claim_id'			=> $claim_id,
					'order_no'			=> $order_no,
					'type'				=> $type,
					'fault_start_time' 	=> $fault_start_time,
					//'feedback_time'     => $feedback_time,
					'feedback_name' 	=> $feedback_name,
					'tel'       		=> $tel,
					'accept_name' 	 	=> $accept_name,
					//'fault_report_time' => $fault_report_time,
					'fault_address' 	=> $fault_address,
					'scene_desc'		=> $scene_desc,
					'scene_result'  	=> $scene_result,
					'maintain_way'  	=> $maintain_way,
					'maintain_scene'	=> $maintain_scene,
					'maintain_name'     => $maintain_name,
					'maintain_tel'  	=> $maintain_tel,
					'maintain_no'		=> $maintain_no,
					'expect_time'		=> $expect_time,
					'car_no_img'      	=> $car_no_img,
					'dashboard_img'  	=> $dashboard_img,
					'fault_scene_img'	=> $fault_scene_img,
					'fault_location_img'=> $fault_location_img,
					'maintain_jieche_img'=> $maintain_jieche_img,
					'status'			=> 5,
					'time'				=> time(),
					'create_time'		=> time(),
					'maintain_worker'	=> $maintain_worker,
					'maintain_worker_tel'=>$maintain_worker_tel,
					'fault_code'		=> $fault_code,
					'road_situation'	=> $road_situation,
					'weather_situation'	=> $weather_situation,
					'temperature_situation'	=> $temperature_situation,
					'vehicle_speed'		=> $vehicle_speed,
					'current_mileage'	=> $current_mileage,
					'indicator_light'	=> $indicator_light,
					'vehicle_launch'	=> $vehicle_launch,
					'is_trailer'		=> $is_trailer,
					'into_factory_time'	=> $into_factory_time,
					'car_img1'			=> $car_img1,
					'car_img2'			=> $car_img2,
					'car_img3'			=> $car_img3,
					'user_id'		=> $_SESSION['backend']['adminInfo']['id'],
					'operating_company_id'=>$_SESSION['backend']['adminInfo']['operating_company_id'],
					'cCustomer_id'=>$cCustomer_id,
					'pCustomer_id'=>$pCustomer_id
					])->execute();
			
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '登记成功！';
				
				$car_status = new CarStatus();
				$car_status->fault($car_id);
				$this->send_email('process', 'repair', 'maintain-reg', '车辆维修登记',$_SESSION['backend']['adminInfo']['operating_company_id']);
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '登记失败！';
			}

			return json_encode($returnArr);
		}
		// 维修站点
		//$maintain_scenes = (new \yii\db\Query())->from('cs_config_item')->where('belongs_id = 65 and is_del =0')->all();
		$maintain_scenes = (new \yii\db\Query())->select('site_name as text')->from('oa_service_site')->all();
		return $this->render('maintain-add',['maintain_scenes'=>$maintain_scenes]);
	}
	
	/**
	 * 修改维修登记
	 */
	public function actionMaintainEdit()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{		
			$id = yii::$app->request->post('id');
			//$car_no = yii::$app->request->post('car_no');
			$car_id = yii::$app->request->post('car_id');
			$claim_id = yii::$app->request->post('claim_id');
			$type = yii::$app->request->post('type');
			$fault_start_time = strtotime(yii::$app->request->post('fault_start_time'));
			//$feedback_time = strtotime(yii::$app->request->post('feedback_time'));
			$feedback_name = yii::$app->request->post('feedback_name');
			$tel = yii::$app->request->post('tel');
			$accept_name = yii::$app->request->post('accept_name');
			//$ = strtotime(yii::$app->request->post(''));
			$fault_address = yii::$app->request->post('fault_address');
			$scene_desc = yii::$app->request->post('scene_desc');
			$scene_result = yii::$app->request->post('scene_result');
			$maintain_way = yii::$app->request->post('maintain_way');
			
			
			$road_situation = [];
			$road_situation = yii::$app->request->post('road_situation');
			$road_situation = json_encode($road_situation);
			
			$weather_situation = yii::$app->request->post('weather_situation');
			$temperature_situation = yii::$app->request->post('temperature_situation');
			$vehicle_speed = yii::$app->request->post('vehicle_speed');
			$current_mileage = yii::$app->request->post('current_mileage');
			$vehicle_launch = yii::$app->request->post('vehicle_launch');
			$is_trailer = yii::$app->request->post('is_trailer');
			$indicator_light = $this->_indicator_light_info(yii::$app->request->post('indicator_light'));
			$indicator_light = !empty($indicator_light) ? json_encode($indicator_light):'';
			
			$maintain_scene = '';
			$maintain_name = '';
			$maintain_tel = '';
			$maintain_no = '';
			$replace_car = '';
			$replace_way = '';
			$replace_start_time = '';
			$replace_end_time = '';
			$field_tolls = '';
			$parking = '';
			$maintain_worker = '';
			$maintain_worker_tel = '';
			if($maintain_way != 3)
			{
				$maintain_scene = yii::$app->request->post('maintain_scene');
				$maintain_name = yii::$app->request->post('maintain_name');
				$maintain_tel = yii::$app->request->post('maintain_tel');
				$maintain_no = yii::$app->request->post('maintain_no');
				$maintain_worker = yii::$app->request->post('maintain_worker');
				$maintain_worker_tel = yii::$app->request->post('maintain_worker_tel');
			}
			
			$into_factory_time = '';
			if($maintain_way == 1)
			{
				$into_factory_time = strtotime(yii::$app->request->post('into_factory_time'));
			}
			
			$expect_time = strtotime(yii::$app->request->post('expect_time'));
			$car_no_img = yii::$app->request->post('car_no_img');
			$dashboard_img = yii::$app->request->post('dashboard_img');
			$fault_scene_img = yii::$app->request->post('fault_scene_img');
			$fault_location_img = yii::$app->request->post('fault_location_img');
			$maintain_jieche_img = yii::$app->request->post('maintain_jieche_img');
			
			$fault_code =  yii::$app->request->post('fault_code');
			
			$car_img1 = yii::$app->request->post('car_img1');
			$car_img2 = yii::$app->request->post('car_img2');
			$car_img3 = yii::$app->request->post('car_img3');
			/* if($type ==3)
			{
				//登记编号，格式：GZ+日期+3位数（即该登记是系统当天登记的第几个，第一个是001，第二个是002…）
				$todayNo = (new \yii\db\Query())->select('order_no')->from('oa_car_maintain')
				->where('time >=:start_time AND time <=:end_time  and order_no like "%WX%"',[':start_time'=>strtotime(date('Y-m-d')),':end_time'=>strtotime(date('Y-m-d'))+86400])
				->orderBy('time desc')->one();
				
				if($todayNo){
					if(preg_match('/^WX(\d+)/i',$todayNo['order_no'],$data))
					{
				
						$currentNo = $data[1]+1;
						$order_no = 'WX'.$currentNo;
					}else{
						$currentNo = str_pad(1,6,0,STR_PAD_LEFT);
						$order_no = 'WX' . date('Ymd') . $currentNo;
					}
				
				}else{
					$currentNo = str_pad(1,6,0,STR_PAD_LEFT);
					$order_no = 'WX' . date('Ymd') . $currentNo;
				}
			}else{
				$order_no = yii::$app->request->post('order_no');
			} */
			if($type == 1){
				$order_no = yii::$app->request->post('order_no');
			}
			$data =[
					'car_id'			=> $car_id,
					'claim_id'			=> $claim_id,
					'type'				=> $type,
					'fault_start_time' 	=> $fault_start_time,
					//'order_no'			=> $order_no,
					//'feedback_time'     => $feedback_time,
					'feedback_name' 	=> $feedback_name,
					'tel'       		=> $tel,
					'accept_name' 	 	=> $accept_name,
					//'fault_report_time' => $fault_report_time,
					'fault_address' 	=> $fault_address,
					'scene_desc'		=> $scene_desc,
					'scene_result'  	=> $scene_result,
					'maintain_way'  	=> $maintain_way,
					'maintain_scene'	=> $maintain_scene,
					'maintain_name'     => $maintain_name,
					'maintain_tel'  	=> $maintain_tel,
					'maintain_no'		=> $maintain_no,
					'expect_time'		=> $expect_time,
					'car_no_img'      	=> $car_no_img,
					'dashboard_img'  	=> $dashboard_img,
					'fault_scene_img'	=> $fault_scene_img,
					'fault_location_img'=> $fault_location_img,
					'maintain_jieche_img'=> $maintain_jieche_img,
					'time'				=>time(),
					'maintain_worker'	=> $maintain_worker,
					'maintain_worker_tel'=> $maintain_worker_tel,
					'fault_code'		=> $fault_code,
					
					'road_situation'	=> $road_situation,
					'weather_situation'	=> $weather_situation,
					'temperature_situation'	=> $temperature_situation,
					'vehicle_speed'		=> $vehicle_speed,
					'current_mileage'	=> $current_mileage,
					'indicator_light'	=> $indicator_light,
					'vehicle_launch'	=> $vehicle_launch,
					'is_trailer'		=> $is_trailer,
					'into_factory_time'	=> $into_factory_time,
					'car_img1'			=> $car_img1,
					'car_img2'			=> $car_img2,
					'car_img3'			=> $car_img3,
					];
			
			if($order_no){
				$data['order_no'] = $order_no;
			}
			
			$db = \yii::$app->db;
			
			$result = $db->createCommand()->update('oa_car_maintain',
					$data,'id=:id',[':id'=>$id])->execute();
			
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '修改成功！';
				
				$car_status = new CarStatus();
				$car_status->fault($car_id);
				
				
				$row = (new \yii\db\Query())->select('operating_company_id')->from("oa_car_maintain")->where('id=:id',[':id'=>$id])->one();
				$oc = $row['operating_company_id'];
				$this->send_email('process', 'repair', 'maintain-reg', '车辆维修登记',$oc);
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '修改失败！';
			}
			
			return json_encode($returnArr);
		}
		$id = yii::$app->request->get('id');
		$row = (new \yii\db\Query())->from('oa_car_maintain')->select('oa_car_maintain.*,{{%car_insurance_claim}}.number as claim_name')
		
		->leftJoin('{{%car_insurance_claim}}','{{%car_insurance_claim}}.id = oa_car_maintain.claim_id')
            
		->where('oa_car_maintain.id=:id',[':id'=>$id])->one();
		// 维修站点
		//$maintain_scenes = (new \yii\db\Query())->from('cs_config_item')->where('belongs_id = 65 and is_del =0')->all();
		$maintain_scenes = (new \yii\db\Query())->select('site_short_name as text')->from('oa_service_site')->all();
		//路面情况
		$row['road_situation'] = json_decode($row['road_situation'],true);
		
		//选择的故障指示灯
		$row['indicator_light_image'] = json_decode($row['indicator_light'],true);
		$str = '';
		if($row['indicator_light_image'])
		{
			foreach ($row['indicator_light_image'] as $k=>$val)
			{
				if($k == 0)
				{
					$str .= $val['id'];
				}else{
					$str .= ','.$val['id'];
				}
			}
		}

		$row['indicator_light'] = $str;
		
		$row['expect_time'] = !empty($row['expect_time']) ? date('Y-m-d H:i',$row['expect_time']) :'';
		$row['fault_start_time'] = !empty($row['fault_start_time']) ? date('Y-m-d H:i',$row['fault_start_time']) :'';
		$row['into_factory_time'] = !empty($row['into_factory_time']) ? date('Y-m-d H:i',$row['into_factory_time']) :'';
		//var_dump($row);
		return $this->render('maintain-edit',['maintain_scenes'=>$maintain_scenes,'row'=>$row]);
	}
	
	/**
	 * 维修结果登记
	 */
	public function actionMaintainReg()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$order_no = yii::$app->request->post('order_no');
			$car_no = yii::$app->request->post('car_no');
			$fault_result = yii::$app->request->post('fault_result');
			$fault_why = yii::$app->request->post('fault_why');
			$maintain_method = yii::$app->request->post('maintain_method');
			$leave_factory_time = strtotime(yii::$app->request->post('leave_factory_time'));
			$jieche_name = yii::$app->request->post('jieche_name');
			$return_replace_time = strtotime(yii::$app->request->post('return_replace_time'));
			$replace_jieche_name = yii::$app->request->post('replace_jieche_name');
			$is_maintain_cost = yii::$app->request->post('is_maintain_cost');
			$leave_jieche_img = yii::$app->request->post('leave_jieche_img');
			
			$accessories = yii::$app->request->post('accessories');
			$maintain_cost = '';
			if($is_maintain_cost)
			{
				$maintain_cost = yii::$app->request->post('maintain_cost');
			}
			
			$db = \yii::$app->db;
		
				
			//我方报修工单
			$transaction = $db->beginTransaction();
			try {
			
				$db->createCommand()->update('oa_car_maintain',['status'=>6],'order_no=:order_no',[':order_no'=>$order_no])->execute();
				
				$id	= yii::$app->request->post('id');
				if($id)
				{
					//已存在纪录，重新修改
					$db->createCommand()->update('oa_car_maintain_result',
							['order_no' 			=> $order_no,
							'car_no'				=> $car_no,
							'fault_result' 			=> $fault_result,
							'fault_why' 			=> $fault_why,
							'maintain_method' 		=> $maintain_method,
							'leave_factory_time' 	=> $leave_factory_time,
							'jieche_name' 			=> $jieche_name,
							'return_replace_time'	=> $return_replace_time,
							'replace_jieche_name' 	=> $replace_jieche_name,
							'is_maintain_cost' 		=> $is_maintain_cost,
							'maintain_cost' 		=> $maintain_cost,
							'time' 					=> time(),
							'leave_jieche_img'		=> $leave_jieche_img,
							'accessories'			=> $accessories,
							],'id=:id',[':id'=>$id])->execute();
				}else{
					//
					$db->createCommand()->insert('oa_car_maintain_result',
							['order_no' 			=> $order_no,
							'car_no'				=> $car_no,
							'fault_result' 			=> $fault_result,
							'fault_why' 			=> $fault_why,
							'maintain_method' 		=> $maintain_method,
							'leave_factory_time' 	=> $leave_factory_time,
							'jieche_name' 			=> $jieche_name,
							'return_replace_time'	=> $return_replace_time,
							'replace_jieche_name' 	=> $replace_jieche_name,
							'is_maintain_cost' 		=> $is_maintain_cost,
							'maintain_cost' 		=> $maintain_cost,
							'time' 					=> time(),
							'leave_jieche_img'		=> $leave_jieche_img,
							'accessories'			=> $accessories,
							])->execute();
				}
				
				
			
			
				//归还故障车辆替换自用备用、车辆
				if($return_replace_time)
				{
					//查找故障车辆
					$car_row = (new \yii\db\Query())->select('id')->from('cs_car')->where("plate_number=:plate_number AND is_del =0",[':plate_number'=>$car_no])->one();
					//被替换id
					$replace_car_id = $car_row['id'];
					
					//获取被替换车所属企业客户
					$let_record = $db->createCommand(
							"select cCustomer_id from cs_car_let_record where car_id={$replace_car_id} and back_time=0 and is_del=0 limit 1"
							)->queryOne();
					if(!$let_record){
						$let_record = $db->createCommand(
								"select ctpd_pCustomer_id from cs_car_trial_protocol_details where ctpd_car_id={$replace_car_id} and ctpd_back_date is null and ctpd_is_del=0 limit 1"
								)->queryOne();
						if(!$let_record){
							$returnArr['status'] = false;
							$returnArr['info'] = '被替换车辆无出租记录！';
							return json_encode($returnArr);
						}else {
							$c_customer_id = $let_record['ctpd_pCustomer_id'];
						}
					}else {
						$c_customer_id = $let_record['cCustomer_id'];
					}
					
					
					$repair = (new \yii\db\Query())->select('id,operating_company_id')->from('oa_repair')->where('order_no=:order_no',[':order_no'=>$order_no])->one();
					$field_record = '';
					if($repair)
					{
						$field_record = (new \yii\db\Query())->select('replace_car')->from('oa_field_record')->where('repair_id=:repair_id',[':repair_id'=>$repair['id']])->one();
					}

					if($field_record)
					{
						
						$car = (new \yii\db\Query())->select('id')->from('cs_car')->where("plate_number=:plate_number AND is_del =0",[':plate_number'=>$field_record['replace_car']])->one();
						//替换车辆id
						$car_id = $car['id'];
						//自用、备用车id
						$car_stock = (new \yii\db\Query())->select('id')->from('cs_car_stock')->where('car_id=:car_id',[':car_id'=>$car_id])->one();
						
						//修改车辆基本信息
						$db->createCommand()->update('cs_car',['car_status2'=>''],'id=:id AND is_del =0',[':id'=>$car_id])->execute();
						//修改自用备用车 状态
						$db->createCommand()->update('cs_car_stock',
								['car_status'=>2,
								'c_customer_id'=>0,
								'add_aid'=>$_SESSION['backend']['adminInfo']['id'],
								'add_time'=>date('Y-m-d H:i')],
								'id =:id',[':id'=>$car_stock['id']]
						)->execute();
						
						//替换记录
						$db->createCommand()->update('cs_car_stock_replace_log', [
								//'car_stock_id' => $car_stock['id'],
								//'car_id' => $car_id,
								//'c_customer_id' => $c_customer_id,
								//'replace_car_id' => $replace_car_id,
								'replace_desc' => '客户报修',
								'real_end_time' => yii::$app->request->post('return_replace_time'),
								'add_aid' => $_SESSION['backend']['adminInfo']['id'],
								],'c_customer_id =:c_customer_id AND car_stock_id=:car_stock_id AND replace_car_id=:replace_car_id',
								[':car_stock_id'=>$car_stock['id'],':c_customer_id'=>$c_customer_id,':replace_car_id'=>$replace_car_id]
								)->execute();
					}
					
				}
				
				
				$transaction->commit();
			
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！';
				
				$car_status = new CarStatus();
				$car = (new \yii\db\Query())->select('id')->from('cs_car')->where('plate_number=:plate_number and is_del=0',[':plate_number'=>$car_no])->one();
				$car_status->restore($car['id']);

				$repair = (new \yii\db\Query())->select('id,operating_company_id')->from('oa_repair')->where('order_no=:order_no',[':order_no'=>$order_no])->one();
				$oc = $repair['operating_company_id'];
				$this->send_email('process', 'repair', 'archive-confirm', '维修工单归档',$oc);
			} catch (Exception $e) {
				//回滚
				$transaction->rollBack();
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
			}

			return json_encode($returnArr);
			
		}
		$order_no 		= yii::$app->request->get('order_no');
		$car_no   		= yii::$app->request->get('car_no');
		$maintain_way	= yii::$app->request->get('maintain_way');
		
		$repair_result = (new \yii\db\Query())
			->select('oa_field_record.replace_car')
			->from('oa_repair')
			->leftJoin('oa_field_record','oa_repair.id = oa_field_record.repair_id')
			->where('oa_repair.order_no =:order_no',[':order_no'=>$order_no])->one();
		$replace_car = '';
		if($repair_result){
			$replace_car = $repair_result['replace_car'];
		}
		$maintain_result = (new \yii\db\Query())->from('oa_car_maintain_result')->where('order_no =:order_no',[':order_no'=>$order_no])->one();
		if($maintain_result)
		{
			$maintain_result['leave_factory_time'] = !empty($maintain_result['leave_factory_time']) ? date('Y-m-d H:i',$maintain_result['leave_factory_time']):'';
			$maintain_result['return_replace_time'] = !empty($maintain_result['return_replace_time']) ? date('Y-m-d H:i',$maintain_result['return_replace_time']):'';
		}
		
		return $this->render('maintain-reg',['replace_car'=>$replace_car,'order_no'=>$order_no,'car_no'=>$car_no,'maintain_way'=>$maintain_way,'maintain_result'=>$maintain_result]);
	}
	
	/**
	 * 维修结果详情
	 */
	public function actionMaintainInfo()
	{
		$order_no = yii::$app->request->get('order_no');
		$row = (new \yii\db\Query())->from('oa_car_maintain_result')->where('order_no = :order_no',[':order_no'=>$order_no])->one();
		
		
		//我方报修工单
		
		$query = (new \yii\db\Query())->select('oa_car_maintain.*,cs_car.plate_number,cs_car.vehicle_dentification_number')->from('oa_car_maintain')->join('LEFT JOIN','cs_car','cs_car.id=oa_car_maintain.car_id');
		
		$result = $query->where('oa_car_maintain.order_no=:order_no and is_del=0',[':order_no'=>$order_no])->one();
		if($result){
			switch ($result['type']){
				case 3:
					$result['type'] = '我方报修';
					break;
		
			}
		}
		
		return $this->render('maintain-info',['row'=>$row,'result'=>$result]);
	}
	
	
	public function actionMaintainDel()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$db = \yii::$app->db;
			//开启事物
			$transaction = $db->beginTransaction();
			try {
				$maintain_row = (new \yii\db\Query())->select('order_no')->from('oa_car_maintain')->where('id=:id',[':id'=>$id])->one();
				
				
				//1.车辆维修登记
				$db->createCommand()->delete('oa_car_maintain','id=:id',[':id'=>$id])->execute();
				
				
				if($maintain_row)
				{
					//2.车辆结果记录
					$db->createCommand()->delete('oa_car_maintain_result','order_no=:order_no',[':order_no'=>$maintain_row['order_no']])->execute();
				}
				
				//3.车辆故障记录
				$db->createCommand()->delete('oa_car_maintain_fault','maintain_id=:maintain_id',[':maintain_id'=>$id])->execute();
				
		
				$transaction->commit();
		
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！';
			} catch (Exception $e) {
				//回滚
				$transaction->rollBack();
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
			}
		
		
		
			return json_encode($returnArr);
		
		
		}
	}

/* 	public function actionArchive()   归档列表
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			$query = $db->select(
					'id,
					car_no,
					order_no,
					type,
					status,
					desc,
					time,
					assign_time,
					confirm_time,
					archive_time,
					archive_name,
					is_attendance
					'
			)->from('oa_repair')->where('status in(4,6,7)');
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			//按车牌号搜索
			$car_no = yii::$app->request->post('car_no');
			if($car_no)
			{
				$query->andWhere(['like','car_no',$car_no]);;
			}
	
			$order_no= yii::$app->request->post('order_no');
			if($order_no)
			{
				$query->andWhere(['like','order_no',$order_no]);
			}
			
			$start_archive_time= yii::$app->request->post('start_archive_time');
			if($start_archive_time)
			{
				$query->andWhere('archive_time >=:start_archive_time',[':start_archive_time'=>strtotime($start_archive_time)]);
			}
			
			$end_archive_time= yii::$app->request->post('end_archive_time');
			if($end_archive_time)
			{
				$query->andWhere('archive_time <=:end_archive_time',[':end_archive_time'=>strtotime($end_archive_time)+86400]);
			}
			
			$type = yii::$app->request->post('type');
			if($type)
			{
				$query->andWhere('type=:type',[':type'=>$type]);
			}
			
			$status = yii::$app->request->post('status');
			if($status)
			{

				$query->andWhere('status =:status',[':status'=>$status]);
				
			}
			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			$query1 = (new \yii\db\Query())->select(
					'id,
					car_no,
					order_no,
					type,
					status,
					scene_desc,
					time,
					assign_time,
					confirm_time,
					archive_time,
					archive_name,
					is_attendance
					'
					)->from('oa_car_maintain')->where('status >=6');
			
			//按车牌号搜索
			if($car_no)
			{
				$query1->andWhere('car_no=:car_no',[':car_no'=>$car_no]);
			}
			if($order_no)
			{
				$query1->andWhere('order_no=:order_no',[':order_no'=>$order_no]);
			}
			if($start_archive_time)
			{
				$query1->andWhere('archive_time >=:start_archive_time',[':start_archive_time'=>strtotime($start_archive_time)]);
			}
			if($end_archive_time)
			{
				$query1->andWhere('archive_time <=:end_archive_time',[':end_archive_time'=>strtotime($end_archive_time)+86400]);
			}
			if($type)
			{
				$query1->andWhere('type=:type',[':type'=>$type]);
			}
			if($status)
			{
			
				$query1->andWhere('status =:status',[':status'=>$status]);
			
			}
			
			$query2 = $query->union($query1);
			$total = $query2->count();
			//union后子查询
			$query3 = (new \yii\db\Query())->from(['a'=>$query2]);
			if($sort)
			{
				$query3->orderBy("{$sort} {$order}");
			}else{
				$query3->orderBy("time DESC");
			}
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
			//$result = $query->offset($pages->offset)->limit($pages->limit)->all();
			$result = $query3->offset($pages->offset)->limit($pages->limit)->all();
			$result = json_decode(json_encode($result));
			if($result){
				foreach ($result as $val)
				{
					switch ($val->type){
						case 1:
							$val->type = '客户报修';
							break;
						case 2:
							$val->type = '车辆出险';
							break;
						case 3:
							$val->type = '我方报修';
							break;
					}
		
					$val->time = !empty($val->time) ? date('Y-m-d H:i',$val->time) :'';
					$val->assign_time = !empty($val->assign_time) ? date('Y-m-d H:i',$val->assign_time) :'';
					$val->confirm_time = !empty($val->confirm_time) ? date('Y-m-d H:i',$val->confirm_time) :'';
					$val->archive_time = !empty($val->archive_time) ? date('Y-m-d H:i',$val->archive_time) :'';
					
					$val->is_attendance = !empty($val->is_attendance) ? '是':'否';
				}
			}
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
		$buttons = $this->getCurrentActionBtn();
		return $this->render('archive',['buttons'=>$buttons]);
	} */
	

	
	/**
	 * 服务工单归档
	 */
	public function actionServeArchive()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			$query = $db->select(
					'id,
					car_no,
					order_no,
					type,
					status,
					desc,
					time,
					assign_time,
					confirm_time,
					archive_time,
					archive_name,
					is_attendance,
					assign_name
					'
			)->from('oa_repair')->where('status in (4,5,7)');
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			$session = yii::$app->session;
			$session->open();
			$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
			$query->andWhere("oa_repair.operating_company_id in ({$ocs})");
			//按车牌号搜索
			$car_no = yii::$app->request->post('car_no');
			if($car_no)
			{
				$query->andWhere(['like','car_no',$car_no]);;
			}
		
			$order_no= yii::$app->request->post('order_no');
			if($order_no)
			{
				$query->andWhere(['like','order_no',$order_no]);
			}
		
			$start_archive_time= yii::$app->request->post('start_archive_time');
			if($start_archive_time)
			{
				$query->andWhere('archive_time >=:start_archive_time',[':start_archive_time'=>strtotime($start_archive_time)]);
			}
		
			$end_archive_time= yii::$app->request->post('end_archive_time');
			if($end_archive_time)
			{
				$query->andWhere('archive_time <=:end_archive_time',[':end_archive_time'=>strtotime($end_archive_time)+86400]);
			}
		
			$type = yii::$app->request->post('type');
			if($type)
			{
				$query->andWhere('type=:type',[':type'=>$type]);
			}
		
			$status = yii::$app->request->post('status');
			if($status)
			{
				if($status == 5)
				{
					$query->andWhere('status in (4,5)');
				}else{
					$query->andWhere('status =:status',[':status'=>$status]);
				}
				
		
			}
			$assign_name = yii::$app->request->post('assign_name');
			if($assign_name)
			{
				$query->andWhere(['like','assign_name',$assign_name]);
			}
			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			
			
			//与车辆基本信息表关联
			$query1 = (new \yii\db\Query())->select('a.*')->from(['a'=>$query])
			->join('LEFT JOIN','cs_car','cs_car.plate_number = a.car_no')->where('is_del=0');;
			
			$brand_id = yii::$app->request->post('brand_id');
			//查品牌，查父品牌时也会查出子品牌
			if($brand_id)
			{
				$brandQuery = (new \yii\db\Query())->select('id')->from('cs_car_brand')->where('id=:id OR pid=:pid',[':id'=>$brand_id,':pid'=>$brand_id]);
				$query1->andWhere(['brand_id' => $brandQuery]);
			}
			$total = $query1->count();
			
			if($sort)
			{
				$query1->orderBy("{$sort} {$order}");
			}else{
				$query1->orderBy("time DESC");
			}
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
			$result = $query1->offset($pages->offset)->limit($pages->limit)->all();
			$result = json_decode(json_encode($result));
			if($result){
				foreach ($result as $val)
				{
					switch ($val->type){
						case 1:
							$val->type = '客户报修';
							break;
						case 2:
							$val->type = '车辆出险';
							break;
						case 3:
							$val->type = '我方报修';
							break;
					}
		
					$val->time = !empty($val->time) ? date('Y-m-d H:i',$val->time) :'';
					$val->assign_time = !empty($val->assign_time) ? date('Y-m-d H:i',$val->assign_time) :'';
					$val->confirm_time = !empty($val->confirm_time) ? date('Y-m-d H:i',$val->confirm_time) :'';
					$val->archive_time = !empty($val->archive_time) ? date('Y-m-d H:i',$val->archive_time) :'';
		
					$val->is_attendance = !empty($val->is_attendance) ? '是':'否';
				}
			}
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
		$buttons = $this->getCurrentActionBtn();
		return $this->render('serve-archive',['buttons'=>$buttons]);
	}
	
	/**
	 * 维修工单归档
	 */
	public function actionMaintainArchive()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			$query =  (new \yii\db\Query())->select(
					'oa_car_maintain.id,
					oa_car_maintain.car_id,
					oa_car_maintain.order_no,
					oa_car_maintain.type,
					oa_car_maintain.status,
					oa_car_maintain.scene_desc,
					oa_car_maintain.time,
					oa_car_maintain.operating_company_id,
					oa_car_maintain.assign_time,
					oa_car_maintain.confirm_time,
					oa_car_maintain.archive_time,
					oa_car_maintain.archive_name,
					oa_car_maintain.is_attendance,
					oa_car_maintain.maintain_way,
					oa_car_maintain.fault_report_time,
					'
			)->from('oa_car_maintain')->where('status in(7,8)');
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			
			//按车牌号搜索
			$order_no= yii::$app->request->post('order_no');
			if($order_no)
			{
				$query->andWhere(['like','order_no',$order_no]);
			}
		
			$start_archive_time= yii::$app->request->post('start_archive_time');
			if($start_archive_time)
			{
				$query->andWhere('archive_time >=:start_archive_time',[':start_archive_time'=>strtotime($start_archive_time)]);
			}
		
			$end_archive_time= yii::$app->request->post('end_archive_time');
			if($end_archive_time)
			{
				$query->andWhere('archive_time <=:end_archive_time',[':end_archive_time'=>strtotime($end_archive_time)+86400]);
			}
		
			$type = yii::$app->request->post('type');
			if($type)
			{
				$query->andWhere('type=:type',[':type'=>$type]);
			}
		
			$status = yii::$app->request->post('status');
			if($status)
			{
		
				$query->andWhere('status =:status',[':status'=>$status]);
		
			}
			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			
			
			
			//与车辆基本信息表关联
			$query1 = (new \yii\db\Query())->select('a.*,cs_car.plate_number')->from(['a'=>$query])
				->join('LEFT JOIN','cs_car','cs_car.id = a.car_id')->where('is_del=0');
			
			$car_no = yii::$app->request->post('car_no');
			if($car_no)
			{
				$query1->andWhere(['like','cs_car.plate_number',$car_no]);;
			}
			
			$brand_id = yii::$app->request->post('brand_id');
			//查品牌，查父品牌时也会查出子品牌
			if($brand_id)
			{
				$brandQuery = (new \yii\db\Query())->select('id')->from('cs_car_brand')->where('id=:id OR pid=:pid',[':id'=>$brand_id,':pid'=>$brand_id]);
				$query1->andWhere(['brand_id' => $brandQuery]);
			}
			
			
			$query2 = (new \yii\db\Query())->select('b.*,leave_factory_time')->from(['b'=>$query1])
				->join('LEFT JOIN','oa_car_maintain_result','oa_car_maintain_result.order_no = b.order_no');
			
			$session = yii::$app->session;
			$session->open();
			$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
			$query2->andWhere("operating_company_id in ({$ocs})");
			
			$total = $query2->count();
			
			
			if($sort)
			{
				$query->orderBy("{$sort} {$order}");
			}else{
				$query->orderBy("time DESC");
			}
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
			$result = $query2->offset($pages->offset)->limit($pages->limit)->all();
			$result = json_decode(json_encode($result));
			if($result){
				foreach ($result as $val)
				{
					switch ($val->type){
						case 1:
							$val->type = '客户报修';
							break;
						case 2:
							$val->type = '车辆出险';
							break;
						case 3:
							$val->type = '我方报修';
							break;
					}
					
					switch ($val->maintain_way){
						case 1:
							$val->maintain_way = '进厂维修';
							break;
						case 0:
						case 2:
							$val->maintain_way = '现场维修';
							break;
						case 3:
							$val->maintain_way = '自修';
							break;
					}
		
					$val->time = !empty($val->time) ? date('Y-m-d H:i',$val->time) :'';
					$val->assign_time = !empty($val->assign_time) ? date('Y-m-d H:i',$val->assign_time) :'';
					$val->confirm_time = !empty($val->confirm_time) ? date('Y-m-d H:i',$val->confirm_time) :'';
					$val->archive_time = !empty($val->archive_time) ? date('Y-m-d H:i',$val->archive_time) :'';
		
					$val->is_attendance = !empty($val->is_attendance) ? '是':'否';
					
					
					
					
					//车辆是否是进厂维修?
					if($val->maintain_way == '进厂维修')
					{
						//车辆是否进行了维修登记?
						if($val->leave_factory_time)
						{
							//维修出厂时间与故障登记中的  故障上报时间  相差不到48小时
							if($val->leave_factory_time -$val->fault_report_time <= 48*3600)
							{
								$val->countdown = '及时';
							}else{
								$val->countdown = '超时';
							}
					
						}
					}
				}
			}
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
		$buttons = $this->getCurrentActionBtn();
		return $this->render('maintain-archive',['buttons'=>$buttons]);
	}
	
	
	/**
	 * 归档确认
	 * @return string
	 */
	public function actionArchiveConfirm()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			
			$session = yii::$app->session;
			$session->open();
			//归档人
			$archive_name = $_SESSION['backend']['adminInfo']['name'];
			
			//$order_no = yii::$app->request->post('order_no');
			
			$db = \yii::$app->db;
			
			$order_no = '';
			$id = '';
			if(isset($_COOKIE['order_no']))
			{
				$order_no = $_COOKIE['order_no'];
			}
			if(isset($_COOKIE['id']))
			{
				$id = $_COOKIE['id'];
			}
			
			if(preg_match('/^BX(\d+)/i',$order_no,$data) && empty($id))
			{
				//客户客户报修
				$result = $db->createCommand()->update('oa_repair',['status'=>7,'archive_time'=>time(),'archive_name'=>$archive_name],'order_no=:order_no',[':order_no'=>$order_no])->execute();
			}else{
				//我方报修
				$result = $db->createCommand()->update('oa_car_maintain',['status'=>7,'archive_time'=>time(),'archive_name'=>$archive_name],'id=:id',[':id'=>$id])->execute();
			}
			
			
			setcookie('id','',time()-3600);
			setcookie('order_no','',time()-3600);
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

		
		
	}
	
	
	/**
	 * 归档驳回
	 * @return string
	 */
	public function actionNoArchiveConfirm()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
	
			$session = yii::$app->session;
			$session->open();
			//归档人
			$archive_name = $_SESSION['backend']['adminInfo']['name'];
	
			//$order_no = yii::$app->request->post('order_no');
	
			$db = \yii::$app->db;
	
			$order_no = '';
			$id = '';
			if(isset($_COOKIE['order_no']))
			{
				$order_no = $_COOKIE['order_no'];
			}
			if(isset($_COOKIE['id']))
			{
				$id = $_COOKIE['id'];
			}
	
			if(preg_match('/^BX(\d+)/i',$order_no,$data) && empty($id))
			{
				
				$repair_row = (new \yii\db\Query())->from('oa_repair')->where('order_no=:order_no',[':order_no'=>$order_no])->one();
				//是否出外勤?
				if($repair_row['is_attendance'] == 1)
				{
					$status = 3;   //出外勤
				}else{
					$status = 2;   //不出外勤
				}
				
				
				//售后服务
				$result = $db->createCommand()->update('oa_repair',['status'=>$status],'order_no=:order_no',[':order_no'=>$order_no])->execute();
			}else{
				//车辆维修
				$result = $db->createCommand()->update('oa_car_maintain',['status'=>5],'id=:id',[':id'=>$id])->execute();
			}
	
	
			setcookie('id','',time()-3600);
			setcookie('order_no','',time()-3600);
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
	
	
	
	}
	
	
	
	public function actionExport()
	{
		//echo 1;exit();
		
		$category = yii::$app->request->get('category');
		$conditions = yii::$app->request->get();
/* 		echo 'category<pre>';
		
		echo $category;
		echo '<pre>';
		var_dump(yii::$app->request->get());exit(); */
		switch ($category){
			case 1:
				$this->export1($conditions);
				break;
			case 2:
				$this->export2($conditions);
				break;
			case 3:
				$this->export3($conditions);
				break;
			case 4:
				$this->export4($conditions);
				break;
			case 5:
				$this->export5($conditions);
// 				$this->export5_test($conditions);
				break;
			case 6:
				$this->export6($conditions);
				break;
		}
		
/* 		
		$filename = '抽检结果登记列表.csv'; //设置文件名
		$str = "检验批次编号,审批状态,审批结果,审批意见,车辆品牌,产品型号,计划提车数量,抽检数量,抽检负责人,验车时间,登记时间,登记人\n";
		$approve_status_arr = array(1=>'待审批',2=>'已审批',3=>'已确认');
		$approve_result_arr = array(0=>'',1=>'合格',2=>'不合格');
		foreach ($data as $row){
			$str .= "{$row['id']},{$approve_status_arr[$row['approve_status']]},{$approve_result_arr[$row['approve_result']]},{$row['approve_note']},{$row['car_brand']},{$row['car_model']},{$row['put_car_num']},{$row['inspection_num']},{$row['inspection_director_name']},{$row['validate_car_time']},{$row['add_time']},{$row['oper_user']}"."\n";
		}
		$str = mb_convert_encoding($str, "GBK", "UTF-8");
		$this->export_csv($filename,$str); //导出 */
	}
	
	/**
	 * 客户报修登记导列表导出
	 */
	public function export1($conditions)
	{
		$query = (new \yii\db\Query())->select(
				'car_no,
				order_no,
				type,
				desc,
				status,
				repair_name,
				tel,
				urgency,
				accept_name,
				time
				')->from('oa_repair');
		if($conditions['car_no'])
		{
			$query->andWhere('car_no=:car_no',[':car_no'=>$conditions['car_no']]);
		}
		if($conditions['repair_name'])
		{
			$query->andWhere('repair_name=:repair_name',[':repair_name'=>$conditions['repair_name']]);
		}
		if($conditions['tel'])
		{
			$query->andWhere('tel=:tel',[':tel'=>$conditions['tel']]);
		}
		if($conditions['order_no'])
		{
			$query->andWhere('order_no=:order_no',[':order_no'=>$conditions['order_no']]);
		}
		if($conditions['start_tel_time'])
		{
			$query->andWhere('tel_time >=:start_tel_time',[':start_tel_time'=>strtotime($conditions['start_tel_time'])]);
		}
		if($conditions['end_tel_time'])
		{
			$query->andWhere('tel_time <=:end_tel_time',[':end_tel_time'=>strtotime($conditions['end_tel_time'])+86400]);
		}
		if($conditions['type'])
		{
			$query->andWhere('type=:type',[':type'=>$conditions['type']]);
		}
		if($conditions['status'])
		{
			//已确认
			if($conditions['status'] == 3)
			{
				//有两种状态
				$query->andWhere('status in (3,4)');
			}else{
				$query->andWhere('status =:status',[':status'=>$conditions['status']]);
			}
		
		}
		$session = yii::$app->session;
		$session->open();
		$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
		$query->andWhere("operating_company_id in ({$ocs})");
		
		$result = $query->all();
		
		$filename = '客户报修登记列表.csv'; //设置文件名
		$str = "车牌号,工单号,工单类型,工单内容简述,工单状态,报修人,来电号码,紧急程度,受理人,登记时间\n";
		$type_arr = array(1=>'客户报修',2=>'车辆出险');
		$status_arr = array(1=>'工单已提交，等待指派',2=>'工单已指派，等待确认',3=>'工单已确认，出外勤中',4=>'工单已确认，等待归档',5=>'车辆维修中',6=>'故障已修复',7=>'已完结');
		$urgency_arr = array(1=>'一般紧急',2=>'比较紧急',3=>'非常紧急');
		foreach ($result as $row){
			$time = date('Y-m-d H:i',$row['time']);
			$str .="{$row['car_no']},{$row['order_no']},{$type_arr[$row['type']]},{$row['desc']},{$status_arr[$row['status']]},{$row['repair_name']},{$row['tel']},{$urgency_arr[$row['urgency']]},{$row['accept_name']},{$time}"."\n";
		}
		$str = mb_convert_encoding($str, "GBK", "UTF-8");
		$this->export_csv($filename,$str); //导出
	}
	
	/**
	 * 报修工单指派列表导出
	 */
	public function export2($conditions)
	{
		$query = (new \yii\db\Query())->select(
				'car_no,
				order_no,
				type,
				desc,
				status,
				assign_name,
				assign_time
				')->from('oa_repair');
		if($conditions['car_no'])
		{
			$query->andWhere('car_no=:car_no',[':car_no'=>$conditions['car_no']]);
		}
		if($conditions['assign_name'])
		{
			$query->andWhere('assign_name=:assign_name',[':assign_name'=>$conditions['assign_name']]);
		}
		if($conditions['order_no'])
		{
			$query->andWhere('order_no=:order_no',[':order_no'=>$conditions['order_no']]);
		}
		if($conditions['start_assign_time'])
		{
			$query->andWhere('assign_time >=:start_assign_time',[':start_assign_time'=>strtotime($conditions['start_assign_time'])]);
		}
		if($conditions['end_assign_time'])
		{
			$query->andWhere('assign_time <=:end_assign_time',[':end_assign_time'=>strtotime($conditions['end_assign_time'])+86400]);
		}
		
		if($conditions['type'])
		{
			$query->andWhere('type=:type',[':type'=>$conditions['type']]);
		}
		if($conditions['status'])
		{
			//已指派
			if($conditions['status'] == 2)
			{
				$query->andWhere('status >=2');
			}else{
				$query->andWhere('status =:status',[':status'=>$conditions['status']]);
			}
		
		}
		
		$session = yii::$app->session;
		$session->open();
		$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
		$query->andWhere("operating_company_id in ({$ocs})");
		
		$result = $query->all();
		
		$filename = '报修工单指派列表.csv'; //设置文件名
		$str = "车牌号,工单号,工单类型,工单内容简述,工单状态,指派对象,指派时间\n";
		$type_arr = array(1=>'客户报修',2=>'车辆出险');
		$urgency_arr = array(1=>'一般紧急',2=>'比较紧急',3=>'非常紧急');
		foreach ($result as $row){
			if($row['status'] >=2)
			{
				$status = '已指派';
			}else{
				$status = '未指派';
			}
			$assign_time = !empty($row['assign_time']) ? date('Y-m-d H:i',$row['assign_time']) : '';
			$str .="{$row['car_no']},{$row['order_no']},{$type_arr[$row['type']]},{$row['desc']},{$status},{$row['assign_name']},{$assign_time}"."\n";
		}
		$str = mb_convert_encoding($str, "GBK", "UTF-8");
		$this->export_csv($filename,$str); //导出
	}
	
	/**
	 * 报修工单确认列表导出
	 */
	public function export3($conditions)
	{
		$query = (new \yii\db\Query())->select(
				'car_no,
				order_no,
				type,
				desc,
				status,
				assign_name,
				assign_time,
				confirm_time
				')->from('oa_repair')->where('status >=2');
		if($conditions['car_no'])
		{
			$query->andWhere('car_no=:car_no',[':car_no'=>$conditions['car_no']]);
		}
		if($conditions['assign_name'])
		{
			$query->andWhere('assign_name=:assign_name',[':assign_name'=>$conditions['assign_name']]);
		}
		if($conditions['order_no'])
		{
			$query->andWhere('order_no=:order_no',[':order_no'=>$conditions['order_no']]);
		}
		if($conditions['start_confirm_time'])
		{
			$query->andWhere('confirm_time >=:start_confirm_time',[':start_confirm_time'=>strtotime($conditions['start_confirm_time'])]);
		}
		if($conditions['end_confirm_time'])
		{
			$query->andWhere('confirm_time <=:end_confirm_time',[':end_confirm_time'=>strtotime($conditions['end_confirm_time'])+86400]);
		}
		if($conditions['type'])
		{
			$query->andWhere('type=:type',[':type'=>$conditions['type']]);
		}
		if($conditions['status'])
		{
			//已指派
			if($conditions['status'] == 3)
			{
				$query->andWhere('status >=3');
			}else{
				$query->andWhere('status =:status',[':status'=>$conditions['status']]);
			}
	
		}
		
		$session = yii::$app->session;
		$session->open();
		$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
		$query->andWhere("operating_company_id in ({$ocs})");
		$result = $query->all();
	
		$filename = '报修工单确认列表.csv'; //设置文件名
		$str = "车牌号,工单号,工单类型,工单内容简述,工单状态,指派对象,指派时间,确认时间\n";
		$type_arr = array(1=>'客户报修',2=>'车辆出险');
		$urgency_arr = array(1=>'一般紧急',2=>'比较紧急',3=>'非常紧急');
		foreach ($result as $row){
			if($row['status'] >=3)
			{
				$status = '已确认';
			}else{
				$status = '未确认';
			}
			$assign_time = !empty($row['assign_time']) ? date('Y-m-d H:i',$row['assign_time']) : '';
			$confirm_time = !empty($row['confirm_time']) ? date('Y-m-d H:i',$row['confirm_time']) : '';
			$str .="{$row['car_no']},{$row['order_no']},{$type_arr[$row['type']]},{$row['desc']},{$status},{$row['assign_name']},{$assign_time},{$confirm_time}"."\n";
		}
		$str = mb_convert_encoding($str, "GBK", "UTF-8");
		$this->export_csv($filename,$str); //导出
	}
	
	/**
	 * 外勤服务登记列表导出
	 */
	public function export4($conditions)
	{
		$query = (new \yii\db\Query())->select(
				'oa_repair.car_no,
				oa_repair.order_no,
				oa_repair.type,
				oa_repair.desc,
				oa_repair.status,
				oa_repair.operating_company_id,
				oa_field_record.is_go_scene,
				oa_field_record.maintain_scene,
				oa_field_record.replace_car,
				oa_field_record.accept_name as record_user,
				oa_field_record.time as field_time'
		)->from('oa_repair')->where('is_attendance = 1 AND status IN (3,5,6,7)');
		$query->join('LEFT JOIN','oa_field_record','oa_field_record.repair_id = oa_repair.id');
		
		if($conditions['car_no'])
		{
			$query->andWhere('car_no=:car_no',[':car_no'=>$conditions['car_no']]);
		}
		if($conditions['order_no'])
		{
			$query->andWhere('order_no=:order_no',[':order_no'=>$conditions['order_no']]);
		}
		if($conditions['start_wq_time'])
		{
			$query->andWhere('oa_field_record.time >=:start_wq_time',[':start_wq_time'=>strtotime($conditions['start_wq_time'])]);
		}
		if($conditions['end_wq_time'])
		{
			$query->andWhere('oa_field_record.time <=:end_wq_time',[':end_wq_time'=>strtotime($conditions['end_wq_time'])+86400]);
		}
		if($conditions['type'])
		{
			$query->andWhere('type=:type',[':type'=>$conditions['type']]);
		}
		if($conditions['status'])
		{
			//已指派
			if($conditions['status'] == 5)
			{
				$query->andWhere('status >=5');
			}else{
				$query->andWhere('status =:status',[':status'=>$conditions['status']]);
			}
	
		}
		$session = yii::$app->session;
		$session->open();
		$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
		$query->andWhere("operating_company_id in ({$ocs})");
		//与车辆基本信息表关联
		$query1 = (new \yii\db\Query())->select('a.*')->from(['a'=>$query])
		->join('LEFT JOIN','cs_car','cs_car.plate_number = a.car_no');
		
		//查品牌，查父品牌时也会查出子品牌
		if($conditions['brand_id'])
		{
			$brandQuery = (new \yii\db\Query())->select('id')->from('cs_car_brand')->where('id=:id OR pid=:pid',[':id'=>$conditions['brand_id'],':pid'=>$conditions['brand_id']]);
			$query1->andWhere(['brand_id' => $brandQuery]);
		}
		
		
		$result = $query1->all();
	
		
		$filename = '外勤服务登记列表.csv'; //设置文件名
		$str = "车牌号,工单号,工单类型,工单内容简述,工单状态,是否进厂维修,维修站,是否替换车辆,替换车,受理人,登记时间\n";
		$type_arr = array(1=>'客户报修',2=>'车辆出险');
		$urgency_arr = array(1=>'一般紧急',2=>'比较紧急',3=>'非常紧急');
		foreach ($result as $row){
			if($row['status'] >=5)
			{
				$status = '已登记';
			}else{
				$status = '未登记';
			}

			$is_go_scene = $row['is_go_scene'] ? '是':'否';
			$is_replace_car = $row['replace_car'] ? '是':'否';
			
			$field_time = !empty($row['field_time']) ? date('Y-m-d H:i',$row['field_time']) : '';
			
			$str .="{$row['car_no']},{$row['order_no']},{$type_arr[$row['type']]},{$row['desc']},{$status},{$is_go_scene},{$row['maintain_scene']},{$is_replace_car},{$row['replace_car']},{$row['record_user']},{$field_time}"."\n";
		}
		$str = mb_convert_encoding($str, "GBK", "UTF-8");
		$this->export_csv($filename,$str); //导出
	}
	
	/**
	 * 车辆维修登记列表导出
	 */
	public function export5($conditions){
		$query = (new \yii\db\Query())->select(
				'oa_car_maintain.car_id,
				oa_car_maintain.id,
				oa_car_maintain.order_no,
				oa_car_maintain.type,
				oa_car_maintain.status,
				oa_car_maintain.scene_desc as desc,
				oa_car_maintain.maintain_way,
				oa_car_maintain.maintain_scene,
				oa_car_maintain.replace_car,
				oa_car_maintain.accept_name as record_user,
				oa_car_maintain.time,
				oa_car_maintain.current_mileage,
				oa_car_maintain.fault_start_time,
				oa_car_maintain.expect_time,
				oa_car_maintain.into_factory_time,
				cs_car.plate_number as car_no,
				cs_car.vehicle_dentification_number as car_vin,
				cs_car.brand_id,
				cs_car.car_model,
				cs_car.operating_company_id,
				cs_car_brand.name as brand_name,
				cs_customer_company.company_name,
				cs_customer_personal.id_name as personal_name,
				oa_car_maintain_result.fault_why,
				oa_car_maintain_result.maintain_method,
				oa_car_maintain_result.leave_factory_time,
				cs_operating_company.name as operating_company_name,
				oa_car_maintain_fault.category as fault_category,
				oa_car_maintain_fault.total_code as total_code
				')->from('oa_car_maintain')
				->join('LEFT JOIN','cs_car','cs_car.id = oa_car_maintain.car_id and cs_car.is_del=0')
				->join('LEFT JOIN','cs_car_brand','cs_car.brand_id = cs_car_brand.id and cs_car_brand.is_del=0')
				->join('LEFT JOIN','cs_customer_company','oa_car_maintain.cCustomer_id = cs_customer_company.id')
				->join('LEFT JOIN','cs_customer_personal','oa_car_maintain.pCustomer_id = cs_customer_personal.id')
				->join('LEFT JOIN','oa_car_maintain_result','oa_car_maintain.order_no = oa_car_maintain_result.order_no')
				->join('LEFT JOIN','cs_operating_company','oa_car_maintain.operating_company_id = cs_operating_company.id')
				->join('LEFT JOIN','oa_car_maintain_fault','oa_car_maintain.id = oa_car_maintain_fault.maintain_id');
		//查询条件开始
		if($conditions['car_no'])
		{
			$query->andWhere(['like','cs_car.plate_number',$conditions['car_no']]);
		}
		if($conditions['order_no'])
		{
			$query->andWhere(['like','oa_car_maintain.order_no',$conditions['order_no']]);
		}
		if($conditions['start_wx_time'])
		{
			$query->andWhere('oa_car_maintain.time >=:start_wx_time',[':start_wx_time'=>strtotime($conditions['start_wx_time'])]);
		}
		if($conditions['end_wx_time'])
		{
			$query->andWhere('oa_car_maintain.time <=:end_wx_time',[':end_wx_time'=>strtotime($conditions['end_wx_time'])+86400]);
		}
		if($conditions['type'])
		{
			$query->andWhere('oa_car_maintain.type=:type',[':type'=>$conditions['type']]);
		}
		if($conditions['status'])
		{
			//维修登记已结案状态 , 状态大于7即可
			if($conditions['status'] == 8)
			{
				$query->andWhere('status >=7');
			}else{
				$query->andWhere('status =:status',[':status'=>$conditions['status']]);
			}
		}
		$brand_id = $conditions['brand_id'];
		//查品牌，查父品牌时也会查出子品牌
		if($brand_id)
		{
			$query->andWhere(
					[
						'or',
						['cs_car_brand.id' => $brand_id],
						['cs_car_brand.pid' => $brand_id]
					]);
		}
		$start_gz_time= $conditions['start_gz_time'];
		if($start_gz_time)
		{
			$query->andWhere('oa_car_maintain.fault_start_time >=:start_gz_time',[':start_gz_time'=>strtotime($start_gz_time)]);
		}
		$end_gz_time= $conditions['end_gz_time'];
		if($end_gz_time)
		{
			$query->andWhere('oa_car_maintain.fault_start_time <=:end_gz_time',[':end_gz_time'=>strtotime($end_gz_time)+86400]);
		}
		$accept_name = $conditions['accept_name'];
		if($accept_name)
		{
			$query->andWhere(['like','oa_car_maintain.accept_name',$accept_name]);
		}
		$customer_name = $conditions['customer_name'];
		$query->andFilterWhere([
				'or',
				['like','{{%customer_company}}.`company_name`',$customer_name],
				['like','{{%customer_personal}}.`id_name`',$customer_name]
			]);
		$pid = $conditions['pid'];
		if($pid)
		{
			$query->andWhere(['like','oa_car_maintain_fault.tier_pid',','.$pid.',']);
		}
		$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
		$query->andWhere("oa_car_maintain.operating_company_id in ({$ocs})");
		//查询条件结束
// 		echo $query->createCommand()->getRawSql();exit;
		$result = $query->all();
		//获取配置数据
        $getConfigItem = [
            'car_model_name'
        ];
        $config = (new ConfigCategory)->getCategoryConfig($getConfigItem,'value');
		
		$filename = '车辆维修登记列表.csv'; //设置文件名
		$str = "车牌号,车架号,车辆品牌,产品型号,车辆名称,客户名称,客户类型,工单号,工单状态,送修人,故障描述,故障原因简述,故障处理方法,当时行驶里程,故障类别,故障编号,故障发生时间,预计完结时间,维修结束时间,维修方式,进厂时间,维修站,车辆运营公司,记录时间\n";
		$status_arr = array(5=>'维修中',6=>'已修复', 7=>'已完结',8=>'已结案');
		$maintain_way_map = [1=>'进厂维修',2=>'现场维修',3=>'自修'];
		foreach ($result as $row){
			$way = @$maintain_way_map[$row['maintain_way']];
			$row['desc'] = !empty($row['desc']) ? trim(str_replace(',', '，', $row['desc'])) :'';
			$row['desc'] = !empty($row['desc']) ? trim(str_replace(array("\r\n", "\r", "\n"), ' ', $row['desc'])) :'';
			$row['fault_why'] = !empty($row['fault_why']) ? trim(str_replace(',', '，',  $row['fault_why'])) :'';
			$row['fault_why'] = !empty($row['fault_why']) ? trim(str_replace(array("\r\n", "\r", "\n"), ' ', $row['fault_why'])) :'';
			$row['maintain_method'] = !empty($row['maintain_method']) ? trim(str_replace(',', '，', $row['maintain_method'])) :'';
			$row['maintain_method'] = !empty($row['maintain_method']) ? trim(str_replace(array("\r\n", "\r", "\n"), ' ', $row['maintain_method'])) :'';
			
			$time = !empty($row['time']) ? date('Y-m-d H:i',$row['time']) : '';
		
			$fault_start_time = !empty($row['fault_start_time']) ? date('Y-m-d H:i',$row['fault_start_time']):'';
			$expect_time = !empty($row['expect_time']) ? date('Y-m-d H:i',$row['expect_time']):'';
			$into_factory_time =  !empty($row['into_factory_time'])? date('Y-m-d H:i',$row['into_factory_time']):'';
			$leave_factory_time = !empty($row['leave_factory_time']) ? date('Y-m-d H:i:s',$row['leave_factory_time']):'';
			
			$car_model_name = @$config['car_model_name'][$val['car_model']]['text'];
			$customer_name='';
			$customer_type='';
			if($row['company_name']){
				$customer_name = $row['company_name'];
				$customer_type = '企业';
			}else if($row['personal_name']){
				$customer_name = $row['personal_name'];
				$customer_type = '个人';
			}
			
			$str .="{$row['car_no']},{$row['car_vin']},{$row['brand_name']},{$row['car_model']},{$car_model_name},{$customer_name},{$customer_type},{$row['order_no']},{$status_arr[$row['status']]},{$row['record_user']},{$row['desc']},{$row['fault_why']},{$row['maintain_method']},{$row['current_mileage']},{$row['fault_category']},{$row['total_code']},{$fault_start_time},{$expect_time},{$leave_factory_time},{$way},{$into_factory_time},{$row['maintain_scene']},{$row['operating_company_name']},{$time}"."\n";
			
		}
		
		$str = mb_convert_encoding($str, "GBK", "UTF-8");
		$this->export_csv($filename,$str); //导出
// 		echo $query->createCommand()->getRawSql();exit;
	}
	
	/**
	 * 归档列表导出
	 */
	public function export6($conditions)
	{
		
		//售后服务
		if(@$conditions['export_type'] != 3)
		{
			$query = (new \yii\db\Query())->select(
					'id,
					car_no,
					order_no,
					type,
					status,
					desc,
					time,
					assign_time,
					confirm_time,
					archive_time,
					archive_name,
					assign_name
					'
			)->from('oa_repair')->where('status in (4,5,7)');
			
			
			if($conditions['car_no'])
			{
				$query->andWhere('car_no=:car_no',[':car_no'=>$conditions['car_no']]);
			}
			
			if($conditions['status'])
			{
				if($conditions['status'] == 5)
				{
					$query->andWhere('status in (4,5)');
				}else{
					$query->andWhere('status =:status',[':status'=>$conditions['status']]);
				}
				
		
			}
			$session = yii::$app->session;
			$session->open();
			$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
			$query->andWhere("oa_repair.operating_company_id in ({$ocs})");
			
			//与车辆基本信息表关联
			$query1 = (new \yii\db\Query())->select('a.*')->from(['a'=>$query])
			->join('LEFT JOIN','cs_car','cs_car.plate_number = a.car_no')->where('is_del=0');
			
		}else{
			$query = (new \yii\db\Query())->select(
					'id,
					car_id,
					order_no,
					type,
					status,
					scene_desc as desc,
					time,
					assign_time,
					confirm_time,
					archive_time,
					archive_name,
					operating_company_id
					'
			)->from('oa_car_maintain')->where('status in(7,8)');
			
			
			if($conditions['status'])
			{
				$query->andWhere('status =:status',[':status'=>$conditions['status']]);
			}
			
			//与车辆基本信息表关联
			$query1 = (new \yii\db\Query())->select('a.*,plate_number as car_no')->from(['a'=>$query])
			->join('LEFT JOIN','cs_car','cs_car.id = a.car_id');
			$session = yii::$app->session;
			$session->open();
			$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
			$query->andWhere("oa_car_maintain.operating_company_id in ({$ocs})");
			
			if($conditions['car_no'])
			{
				$query1->andWhere('cs_car.plate_number=:car_no',[':car_no'=>$conditions['car_no']]);
			}
		}
		
		
		if($conditions['order_no'])
		{
			$query->andWhere('order_no=:order_no',[':order_no'=>$conditions['order_no']]);
		}
		if($conditions['start_archive_time'])
		{
			$query->andWhere('archive_time >=:start_archive_time',[':start_archive_time'=>strtotime($conditions['start_archive_time'])]);
		}
		if($conditions['end_archive_time'])
		{
			$query->andWhere('archive_time <=:end_archive_time',[':end_archive_time'=>strtotime($conditions['end_archive_time'])+86400]);
		}
		if($conditions['type'])
		{
			$query->andWhere('type=:type',[':type'=>$conditions['type']]);
		}
		

		
		//查品牌，查父品牌时也会查出子品牌
		if($conditions['brand_id'])
		{
			$brandQuery = (new \yii\db\Query())->select('id')->from('cs_car_brand')->where('id=:id OR pid=:pid',[':id'=>$conditions['brand_id'],':pid'=>$conditions['brand_id']]);
			$query1->andWhere(['brand_id' => $brandQuery]);
		}
		
		
		$result= $query1->all();
	

		$filename = '工单归档列表.csv'; //设置文件名
		$str = "车牌号,工单号,工单类型,工单内容简述,工单状态,工单创建时间,工单指派时间,工单确认时间,工单完结时间,归档人,指派人\n";
		$type_arr = array(1=>'客户报修',2=>'车辆出险',3=>'我方报修');
		$urgency_arr = array(1=>'一般紧急',2=>'比较紧急',3=>'非常紧急');
		foreach ($result as $row){
			if($row['status'] ==7)
			{
				$status = '已完结';
			}else{
				$status = '未归档';
			}
			$time = !empty($row['time']) ? date('Y-m-d H:i',$row['time']) : '';
			$assign_time = !empty($row['assign_time']) ? date('Y-m-d H:i',$row['assign_time']) : '';
			$confirm_time = !empty($row['confirm_time']) ? date('Y-m-d H:i',$row['confirm_time']) : '';
			$archive_time = !empty($row['archive_time']) ? date('Y-m-d H:i',$row['archive_time']) : '';
			$assign_name = !empty($row['assign_name']) ? $row['assign_name']:'';
			$type = @$type_arr[$row['type']];
			$str .="{$row['car_no']},{$row['order_no']},{$type},{$row['desc']},{$status},{$time},{$assign_time},{$confirm_time},{$archive_time},{$row['archive_name']},{$assign_name}"."\n";
		}
		$str = mb_convert_encoding($str, "GBK", "UTF-8");
		$this->export_csv($filename,$str); //导出
	}
	
	
	/**
	 * 工单归档导出
	 */
	public function actionArchiveExport()
	{	
		$order_no = yii::$app->request->get('order_no');
		$id=  intval(yii::$app->request->get('id'));
		$this->excel($order_no,$id);
		exit();
		
/* 		$conditions = yii::$app->request->get();
		
		$query = (new \yii\db\Query())->select(
				'id,
				car_no,
				order_no,
				type,
				status,
				desc,
				time,
				assign_time,
				confirm_time,
				archive_time,
				archive_name
				'
		)->from('oa_repair')->where('status >=6');
		
		
		$query1 = (new \yii\db\Query())->select(
				'id,
				car_no,
				order_no,
				type,
				status,
				scene_desc,
				time,
				assign_time,
				confirm_time,
				archive_time,
				archive_name
				'
		)->from('oa_car_maintain')->where('status >=6');
		
		if($conditions['car_no'])
		{
			$query->andWhere('car_no=:car_no',[':car_no'=>$conditions['car_no']]);
			$query1->andWhere('car_no=:car_no',[':car_no'=>$conditions['car_no']]);
		}
		if($conditions['order_no'])
		{
			$query->andWhere('order_no=:order_no',[':order_no'=>$conditions['order_no']]);
			$query1->andWhere('order_no=:order_no',[':order_no'=>$conditions['order_no']]);
		}
		if($conditions['start_archive_time'])
		{
			$query->andWhere('archive_time >=:start_archive_time',[':start_archive_time'=>strtotime($conditions['start_archive_time'])]);
			$query1->andWhere('archive_time >=:start_archive_time',[':start_archive_time'=>strtotime($conditions['start_archive_time'])]);
		}
		if($conditions['end_archive_time'])
		{
			$query->andWhere('archive_time <=:end_archive_time',[':end_archive_time'=>strtotime($conditions['end_archive_time'])+86400]);
			$query1->andWhere('archive_time <=:end_archive_time',[':end_archive_time'=>strtotime($conditions['end_archive_time'])+86400]);
		}
		if($conditions['type'])
		{
			$query->andWhere('type=:type',[':type'=>$conditions['type']]);
			$query1->andWhere('type=:type',[':type'=>$conditions['type']]);
		}
		if($conditions['status'])
		{
			$query->andWhere('status =:status',[':status'=>$conditions['status']]);
			$query1->andWhere('status =:status',[':status'=>$conditions['status']]);
		}
		$result = $query->union($query1)->all();
		
		
		$type_arr = array(1=>'客户报修',2=>'车辆出险',3=>'我方报修');
		$urgency_arr = array(1=>'一般紧急',2=>'比较紧急',3=>'非常紧急');
		$source_arr = array(1=>'400电话');
		$replace_way_arr = array(1=>'自提',2=>'送车上门');
		$filename = '工单详情.csv'; //设置文件名
		$str = "工单号,工单类型,工单来源,报修人姓名,来电号码,来电时间,紧急程度,车牌号,客户公司名称,故障地点,工单内容简述,来电内容记录,所需服务,派单对象,确认时间,已听取录音,已电话回访,需要出外勤,携带设备,需申请用车,外勤用车车牌号,抵达现场时间,现场勘查故障描述,现场处理结果,维修方联系人,联系电话,进厂维修单号,预计完结时间,是否进厂维修,维修场站,是否替换车辆,替换车,替换开始时间,预计归还时间,外勤过路费,外勤停车费,故障处理结果,故障引发原因,故障维修方法,维修出厂日期,出厂接车人员,替换车归还时间,还车方式\n";
				
		foreach ($result as $val)
		{
			if(preg_match('/^BX(\d+)/i',$val['order_no'],$data))
			{

				//客户报修工单
				$db = \Yii::$app->db;
				$row = $db->createCommand("SELECT a.*,
					oa_car_maintain_result.fault_result, 
					oa_car_maintain_result.fault_why, 
					oa_car_maintain_result.maintain_method, 
					oa_car_maintain_result.leave_factory_time, 
					oa_car_maintain_result.jieche_name, 
					oa_car_maintain_result.return_replace_time
					FROM (SELECT oa_repair.*,
					oa_field_record.arrive_time, 
					oa_field_record.scene_desc,
					oa_field_record.scene_result, 
					oa_field_record.maintain_name,
					oa_field_record.maintain_tel, 
					oa_field_record.maintain_no,
					oa_field_record.expect_time,
					oa_field_record.is_go_scene,
					oa_field_record.maintain_scene,
					oa_field_record.replace_car,
					oa_field_record.replace_start_time,
					oa_field_record.replace_end_time,
					oa_field_record.field_tolls,
					oa_field_record.parking,
					oa_field_record.replace_way
					FROM oa_repair  LEFT JOIN oa_field_record ON oa_field_record.repair_id = oa_repair.id ) AS a 
					LEFT JOIN oa_car_maintain_result ON oa_car_maintain_result.order_no = a.order_no where a.id ={$val['id']}")->queryOne();
				
				
				
				$tel_time = !empty($row['tel_time']) ? date('Y-m-d H:i',$row['tel_time']):'';
				$address = str_replace(',', '，', $row['address']).str_replace(',', '，', $row['bearing']);
				$desc = str_replace(',', '，', $row['desc']);
				$tel_content = str_replace(',', '，', $row['tel_content']);;
				$confirm_time = !empty($row['confirm_time']) ? date('Y-m-d H:i',$row['confirm_time']):'';
				$is_voice = !empty($row['is_voice']) ? '是':'否';
				$is_visit = !empty($row['is_visit']) ? '是':'否';
				$is_attendance = !empty($row['is_attendance']) ? '是':'否';
				$carry = str_replace(',', '，', $row['carry']);
				$is_use_car = !empty($row['is_use_car']) ? '是':'否';
				$arrive_time = !empty($row['arrive_time']) ? date('Y-m-d H:i',$row['arrive_time']):'';
				$scene_desc = str_replace(',', '，', $row['scene_desc']);
				$scene_result = str_replace(',', '，', $row['scene_result']);
				$expect_time = !empty($row['expect_time']) ? date('Y-m-d H:i',$row['expect_time']) :'';
				$is_go_scene = !empty($row['is_go_scene']) ? '是':'否';
				$is_replace_car= !empty($row['replace_car']) ? '是':'否';
				$replace_start_time = !empty($row['replace_start_time']) ? date('Y-m-d H:i',$row['replace_start_time']) :'';
				$replace_end_time = !empty($row['replace_end_time']) ? date('Y-m-d H:i',$row['replace_end_time']) :'';
				
				
				$fault_result = str_replace(',', '，', $row['fault_result']);
				$fault_why = str_replace(',', '，', $row['fault_why']);
				$maintain_method = str_replace(',', '，', $row['maintain_method']);
				$leave_factory_time = !empty($row['leave_factory_time']) ? date('Y-m-d H:i',$row['leave_factory_time']) :'';
				$return_replace_time = !empty($row['return_replace_time']) ? date('Y-m-d H:i',$row['return_replace_time']) :'';
				
				$str .="{$row['order_no']},{$type_arr[$row['type']]},{$source_arr[$row['source']]},{$row['repair_name']},{$row['tel']},{$tel_time},{$urgency_arr[$row['urgency']]},{$row['car_no']},{$row['tel']},{$address},{$desc},{$tel_content},{$row['need_serve']},{$row['assign_name']},{$confirm_time},{$is_voice},{$is_visit},{$is_attendance},{$carry},{$is_use_car},{$row['use_car_no']},{$arrive_time},{$scene_desc},{$scene_result},{$row['maintain_name']},{$row['maintain_tel']},{$row['maintain_no']},{$expect_time},{$is_go_scene},{$row['maintain_scene']},{$is_replace_car},{$row['replace_car']},{$replace_start_time},{$replace_end_time},{$row['field_tolls']},{$row['parking']},{$fault_result},{$fault_why},{$maintain_method},{$leave_factory_time},{$row['jieche_name']},{$return_replace_time},{$replace_way_arr[$row['replace_way']]}"."\n";
				
				
			}else{
				//我方报修工单
				$db = \Yii::$app->db;
				
				$row = $db->createCommand("SELECT oa_car_maintain.*,
						oa_car_maintain_result.fault_result, 
						oa_car_maintain_result.fault_why, 
						oa_car_maintain_result.maintain_method, 
						oa_car_maintain_result.leave_factory_time, 
						oa_car_maintain_result.jieche_name, 
						oa_car_maintain_result.return_replace_time
						from oa_car_maintain LEFT JOIN oa_car_maintain_result ON oa_car_maintain_result.order_no = oa_car_maintain.order_no WHERE oa_car_maintain.id ={$val['id']}")->queryOne();
				
				
				
				
				$address = str_replace(',', '，', $row['fault_address']);
				$desc    = str_replace(',', '，', $row['scene_desc']);
				
				
				$fault_result = str_replace(',', '，', $row['fault_result']);
				$fault_why = str_replace(',', '，', $row['fault_why']);
				$maintain_method = str_replace(',', '，', $row['maintain_method']);
				$leave_factory_time = !empty($row['leave_factory_time']) ? date('Y-m-d H:i',$row['leave_factory_time']) :'';
				$return_replace_time = !empty($row['return_replace_time']) ? date('Y-m-d H:i',$row['return_replace_time']) :'';
				
				
				$str .="{$row['order_no']},{$type_arr[$row['type']]},,,,,,{$row['car_no']},,{$address},{$desc},,,,,,,,,,,,,,,,,,,,,,,,,,{$fault_result},{$fault_why},{$maintain_method},{$leave_factory_time},{$row['jieche_name']},,"."\n";
				
				
			}
			
		}
		$str = mb_convert_encoding($str, "GBK", "UTF-8");
		$this->export_csv($filename,$str); //导出 */
		//exit();
		
		
	}
	
	
	public function excel($order_no,$id)
	{
		set_time_limit(0);
		//$order_no = yii::$app->request->get('order_no');
		if(preg_match('/^BX(\d+)/i',$order_no,$data) && empty($id))
		{
			$db = \Yii::$app->db;
			$result = $db->createCommand("SELECT a.*,
					oa_car_maintain_result.fault_result,
					oa_car_maintain_result.fault_why,
					oa_car_maintain_result.maintain_method,
					oa_car_maintain_result.leave_factory_time,
					oa_car_maintain_result.jieche_name,
					oa_car_maintain_result.return_replace_time
					FROM (SELECT oa_repair.*,
					oa_field_record.arrive_time,
					oa_field_record.scene_desc,
					oa_field_record.scene_result,
					oa_field_record.maintain_name,
					oa_field_record.maintain_tel,
					oa_field_record.maintain_no,
					oa_field_record.expect_time,
					oa_field_record.is_go_scene,
					oa_field_record.maintain_scene,
					oa_field_record.replace_car,
					oa_field_record.replace_start_time,
					oa_field_record.replace_end_time,
					oa_field_record.field_tolls,
					oa_field_record.parking,
					oa_field_record.replace_way
					FROM oa_repair  LEFT JOIN oa_field_record ON oa_field_record.repair_id = oa_repair.id ) AS a
					LEFT JOIN oa_car_maintain_result ON oa_car_maintain_result.order_no = a.order_no where a.order_no ='{$order_no}'")->queryOne();
		
					//return $this->render('archive-info',['result'=>$result]);
			}else{
		
					$db = \Yii::$app->db;

					$result = $db->createCommand("SELECT oa_car_maintain.*,
					oa_car_maintain_result.fault_result,
					oa_car_maintain_result.fault_why,
					oa_car_maintain_result.maintain_method,
					oa_car_maintain_result.leave_factory_time,
					oa_car_maintain_result.jieche_name,
					oa_car_maintain_result.return_replace_time
					from oa_car_maintain LEFT JOIN oa_car_maintain_result ON oa_car_maintain_result.order_no = oa_car_maintain.order_no WHERE oa_car_maintain.order_no ='{$order_no}'")->queryOne();
		
					//return $this->render('archive-info1',['result'=>$result]);
		
		}
		$type = $result['type'];
		$type_arr = array(1=>'客户报修',2=>'车辆出险',3=>'我方报修');
		$urgency_arr = array(1=>'一般紧急',2=>'比较紧急',3=>'非常紧急');
		$source_arr = array(1=>'400电话');
		$replace_way_arr = array(1=>'自提',2=>'送车上门');
		
		$result['type'] = !empty($result['type']) ? $type_arr[$result['type']] :'';
		$result['urgency'] = !empty($result['urgency']) ? $urgency_arr[$result['urgency']]:'';
		$result['source'] = !empty($result['source']) ? $source_arr[$result['source']]:'';
		$result['replace_way'] = !empty($result['replace_way']) ? $replace_way_arr[$result['replace_way']]:'';
		
		$result['tel_time'] = !empty($result['tel_time']) ? date('Y-m-d H:i',$result['tel_time']):'';
		$result['address'] = str_replace(',', '，', @$result['address']).str_replace(',', '，', @$result['bearing']);
		$result['desc'] = str_replace(',', '，', @$result['desc']);
		$result['tel_content'] = str_replace(',', '，', @$result['tel_content']);;
		$result['confirm_time'] = !empty($result['confirm_time']) ? date('Y-m-d H:i',@$result['confirm_time']):'';
		$result['is_voice'] = !empty($result['is_voice']) ? '是':'否';
		$result['is_visit'] = !empty($result['is_visit']) ? '是':'否';
		$result['is_attendance'] = !empty($result['is_attendance']) ? '是':'否';
		$result['carry'] = str_replace(',', '，', @$result['carry']);
		$result['is_use_car'] = !empty($result['is_use_car']) ? '是':'否';
		$result['arrive_time'] = !empty($result['arrive_time']) ? date('Y-m-d H:i',@$result['arrive_time']):'';
		$result['scene_desc'] = str_replace(',', '，', @$result['scene_desc']);
		$result['scene_result'] = str_replace(',', '，', @$result['scene_result']);
		//$result['expect_time'] = !empty($result['expect_time']) ? date('Y-m-d H:i',@$result['expect_time']) :'';
		$result['is_go_scene'] = !empty($result['is_go_scene']) ? '是':'否';
		$result['is_replace_car']= !empty($result['replace_car']) ? '是':'否';
		$result['replace_start_time'] = !empty($result['replace_start_time']) ? date('Y-m-d H:i',@$result['replace_start_time']) :'';
		$result['replace_end_time'] = !empty($result['replace_end_time']) ? date('Y-m-d H:i',@$result['replace_end_time']) :'';
		
		
		$fault_result = str_replace(',', '，', $result['fault_result']);
		$fault_why = str_replace(',', '，', $result['fault_why']);
		$maintain_method = str_replace(',', '，', $result['maintain_method']);
		$leave_factory_time = !empty($result['leave_factory_time']) ? date('Y-m-d H:i',$result['leave_factory_time']) :'';
		
		
		
		//根据车牌号查询 所属企业/个人  客户
		$cCustomer_id = '';
		$pCustomer_id = '';
		$car = (new \yii\db\Query())->select('id')->from('cs_car')->where('plate_number=:plate_number and is_del=0',[':plate_number'=>$result['car_no']])->one();
		//查询车辆是否在出租
		$let_record = (new \yii\db\Query())->select('cCustomer_id,pCustomer_id')->from('cs_car_let_record')
		->where('car_id=:car_id  AND let_time <=:let_time AND back_time =0 AND is_del=0',[':car_id'=>$car['id'],':let_time'=>time()])->orderBy('let_time DESC')->one();
		if($let_record)
		{
			//车牌租给了企业用户
			if($let_record['cCustomer_id'])
			{
				$cCustomer_id = $let_record['cCustomer_id'];
			}else{
				$pCustomer_id = $let_record['pCustomer_id'];
			}
		
		
		
		}else{
			//查询出车辆是否在试驾
			$trial_record = (new \yii\db\Query())->select('ctpd_cCustomer_id,ctpd_pCustomer_id')->from('cs_car_trial_protocol_details')
			->where('ctpd_car_id=:ctpd_car_id  AND ctpd_deliver_date <=:ctpd_deliver_date AND ctpd_back_date=0 AND ctpd_is_del=0',[':ctpd_car_id'=>$car['id'],':ctpd_deliver_date'=>date('Y-m-d',time())])->orderBy('ctpd_deliver_date DESC')->one();
		
		
		
			if($trial_record)
			{
				if($trial_record['ctpd_cCustomer_id'])
				{
					$cCustomer_id = $trial_record['ctpd_cCustomer_id'];
				}else{
					$pCustomer_id = $trial_record['ctpd_pCustomer_id'];
				}
			}
		}
		
		if($cCustomer_id)
		{
			$row = (new \yii\db\Query())->select('company_name as customer_name')->from('cs_customer_company')->where('id=:id AND is_del=0',[':id'=>$cCustomer_id])->one();
		}elseif($pCustomer_id){
			$row = (new \yii\db\Query())->select('id_name as customer_name')->from('cs_customer_personal')->where('id=:id  AND is_del=0',[':id'=>$pCustomer_id])->one();
		}
		$result['customer_name'] =  !empty($row) ? $row['customer_name'] :'';
		
		
/*  		echo '<pre>';
		var_dump($result);exit(); */
		
		if(empty($id))
		{
			$exportCarBaseInfoMap = [
			'type' => '工单类型',
			'source' => '工单来源',
			'repair_name' => '报修人姓名',
			'tel' => '来电号码',
			'tel_time' => '来电时间',
			'urgency' => '紧急程度',
			'car_no' => '车牌号',
			'customer_name' => '客户公司名称',
			'address' => '故障地点',
			'desc' => '工单内容简述',
			'tel_content' => '来电内容记录',
			'need_serve' => '所需服务'
			];
			
			
			$excel = new Excel();
			$excel->setHeader([
					'creator'=>'地上铁',
					'lastModifiedBy'=>'DST',
					]);
			$excel->addLineToExcel([[
					'content'=>'接单信息',
					'color'=>'00ff0000',
					'font-weight'=>true,
					'background-rgba'=>'004bacc6',
					'color'=>'00ffffff',
					'border-type'=>'thin',
					'border-color'=>'00ffffff',
					'font-size'=>'18',
					'colspan'=>10,
					'height'=>40,
					'valign'=>'center'
					]]);
			$lineData = [];
			$i = 0;
			
			if($type == 1 || $type == 2){
				foreach($exportCarBaseInfoMap as $key=>$val){
					if( ($i + 1) % 5 == 0){
						$excel->addLineToExcel($lineData);
						$lineData = [];
					}
					$lineData[] = ['content'=>$val,'width'=>24];
					if(isset($result[$key])){
						$lineData[] = ['content'=>$result[$key],'text-align'=>'left','width'=>30];
					}else{
						$lineData[] = ['content'=>'','width'=>30];
					}
					$i ++;
				}
				if(!empty($lineData)){
					$excel->addLineToExcel($lineData);
				}
			}
			
			//导出派单信息
			$excel->addLineToExcel([[
					'content'=>'派单信息',
					'color'=>'00ff0000',
					'font-weight'=>true,
					'background-rgba'=>'004bacc6',
					'color'=>'00ffffff',
					'border-type'=>'thin',
					'border-color'=>'00ffffff',
					'font-size'=>'18',
					'colspan'=>10,
					'height'=>40,
					'valign'=>'center'
					]]);
			$exportCarBaseInfoMap = [
			'confirm_name' => '派单对象',
			'confirm_time' => '确认时间',
			'is_voice' => '已听取录音',
			'is_visit' => '已电话回访',
			'is_attendance' => '需要出外勤',
			'carry' => '携带设备',
			'is_use_car' => '需申请用车',
			'use_car_no' => '外勤用车车牌号',
			];
			
			if($type == 1 || $type == 2){
			
				$i = 0;
				$lineData = [];
				foreach($exportCarBaseInfoMap as $key=>$val){
					if( ($i + 1) % 5 == 0){
						$excel->addLineToExcel($lineData);
						$lineData = [];
					}
					$lineData[] = ['content'=>$val];
					if(isset($result[$key])){
						$lineData[] = ['content'=>$result[$key],'text-align'=>'left'];
					}else{
						$lineData[] = ['content'=>'1'];
					}
					$i ++;
				}
				if(!empty($lineData)){
					$excel->addLineToExcel($lineData);
				}
			}
			
			
			
			//写入外勤服务信息
			$excel->addLineToExcel([[
					'content'=>'外勤服务信息',
					'color'=>'00ff0000',
					'font-weight'=>true,
					'background-rgba'=>'004bacc6',
					'color'=>'00ffffff',
					'border-type'=>'thin',
					'border-color'=>'00ffffff',
					'font-size'=>'18',
					'colspan'=>10,
					'height'=>40,
					'valign'=>'center'
					]]);
			$exportCarBaseInfoMap = [
			'arrive_time' => '抵达现场时间',
			'scene_desc' => '现场故障描述',
			'scene_result' => '现场处理结果',
			'maintain_name' => '维修方联系人',
			'maintain_tel' => '联系电话',
			'maintain_no' => '进厂维修单号',
			'expect_time' => '预计完结时间',
			'is_go_scene' => '是否进厂维修',
			'maintain_scene' => '维修场站',
			'replace_car' => '是否替换车辆',
			'replace_car' => '替换车',
			'replace_start_time' => '替换开始时间',
			'replace_end_time' => '预计归还时间',
			'field_tolls' => '外勤过路费',
			'parking' => '外勤停车费',
			];
			
			if($type == 1 || $type == 2){
			
				$i = 0;
				$lineData = [];
				foreach($exportCarBaseInfoMap as $key=>$val){
					if( ($i + 1) % 5 == 0){
						$excel->addLineToExcel($lineData);
						$lineData = [];
					}
					$lineData[] = ['content'=>$val];
					if(isset($result[$key])){
						$lineData[] = ['content'=>$result[$key],'text-align'=>'left'];
					}else{
						$lineData[] = ['content'=>'2'];
					}
					$i ++;
				}
				if(!empty($lineData)){
					$excel->addLineToExcel($lineData);
				}
				
			}
		}else{
		
			
			$result['fault_start_time'] = !empty($result['fault_start_time']) ? date('Y-m-d H:i',$result['fault_start_time']):'';
			$result['feedback_time'] = !empty($result['feedback_time']) ? date('Y-m-d H:i',$result['feedback_time']):'';
			
			$result['fault_report_time'] = !empty($result['fault_report_time']) ? date('Y-m-d H:i',$result['fault_report_time']):'';
			$result['maintain_way'] =  $result['maintain_way']==1?'进厂维修':'现场维修';
			$result['expect_time']  = !empty($result['expect_time']) ? date('Y-m-d H:i',$result['expect_time']):'';
			$result['return_replace_time'] = !empty($result['return_replace_time']) ? date('Y-m-d H:i',$result['return_replace_time']) :'';
			$excel = new Excel();
			$excel->setHeader([
					'creator'=>'地上铁',
					'lastModifiedBy'=>'DST',
					]);

			//写入外勤服务信息
			$excel->addLineToExcel([[
					'content'=>'维修登记信息',
					'color'=>'00ff0000',
					'font-weight'=>true,
					'background-rgba'=>'004bacc6',
					'color'=>'00ffffff',
					'border-type'=>'thin',
					'border-color'=>'00ffffff',
					'font-size'=>'18',
					'colspan'=>10,
					'height'=>40,
					'valign'=>'center'
					]]);
			$exportCarBaseInfoMap = [
			'type'=>'故障来源',
			'car_no'=>'故障车辆',
			'fault_start_time' => '故障发生时间',
			'feedback_time' => '故障反馈时间',
			'feedback_name' => '故障反馈人',
			'tel' => '联系电话',
			'accept_name' => '本方受理人',
			'fault_start_time' => '故障发生时间',
			'fault_address' => '故障地点',
			'scene_desc' => '现场勘查故障描述',
			'scene_result' => '现场处理结果',
			'maintain_way' => '维修方式',
			'maintain_scene' => '维修厂站',
			'maintain_name' => '维修方联系人',
			'maintain_tel' => '联系电话',
			'maintain_no' => '进厂维修单号',
			'expect_time' => '预计完成时间',
			];
			

			
				$i = 0;
				$lineData = [];
				foreach($exportCarBaseInfoMap as $key=>$val){
					if( ($i + 1) % 5 == 0){
						$excel->addLineToExcel($lineData);
						$lineData = [];
					}
					$lineData[] = ['content'=>$val];
					if(isset($result[$key])){
						$lineData[] = ['content'=>$result[$key],'text-align'=>'left'];
					}else{
						$lineData[] = ['content'=>'2'];
					}
					$i ++;
				}
				if(!empty($lineData)){
					$excel->addLineToExcel($lineData);
				}
				
				
				//写入维修结果信息
				$excel->addLineToExcel([[
						'content'=>'维修结果',
						'color'=>'00ff0000',
						'font-weight'=>true,
						'background-rgba'=>'004bacc6',
						'color'=>'00ffffff',
						'border-type'=>'thin',
						'border-color'=>'00ffffff',
						'font-size'=>'18',
						'colspan'=>10,
						'height'=>40,
						'valign'=>'center'
						]]);
				$exportCarBaseInfoMap = [
				'fault_result' => '故障处理结果',
				'fault_why' => '故障引发原因',
				'maintain_method' => '故障维修方法',
				'leave_factory_time' => '维修出厂日期',
				'jieche_name' => '出厂接车人员',
				'return_replace_time' => '替换车归还时间',
				//'replace_way' => '还车方式'
				];
				
				
				$i = 0;
				$lineData = [];
				foreach($exportCarBaseInfoMap as $key=>$val){
					if( ($i + 1) % 5 == 0){
						$excel->addLineToExcel($lineData);
						$lineData = [];
					}
					$lineData[] = ['content'=>$val];
					if(isset($result[$key])){
						$lineData[] = ['content'=>$result
				
						[$key],'text-align'=>'left'];
					}else{
						$lineData[] = ['content'=>''];
					}
					$i ++;
				}
				if(!empty($lineData)){
					$excel->addLineToExcel($lineData);
				}
				
				
		}
		
			
			
			
			
			

			
			//生成excel文件
			$objWriter = \PHPExcel_IOFactory::createWriter($excel->getPHPExcel(), 'Excel2007');
			$fileName = iconv('utf-8','gbk',$result['order_no'].'工单详情');
			//$excelFileName = dirname(getcwd())."/runtime/{$fileName}.xlsx";
			
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
			header('Cache-Control: max-age=0');
			
			$objWriter->save('php://output');
			exit();
	}
	
	public function actionArchiveInfo()
	{
		$order_no = yii::$app->request->get('order_no');
		$id = yii::$app->request->get('id');
		if(preg_match('/^BX(\d+)/i',$order_no,$data)  && empty($id))
		{
			setcookie('order_no',$order_no);
			
			$db = \Yii::$app->db;
			$result = $db->createCommand("SELECT a.*,
					oa_car_maintain_result.fault_result,
					oa_car_maintain_result.fault_why,
					oa_car_maintain_result.maintain_method,
					oa_car_maintain_result.leave_factory_time,
					oa_car_maintain_result.jieche_name,
					oa_car_maintain_result.return_replace_time
					FROM (SELECT oa_repair.*,
					oa_field_record.arrive_time,
					oa_field_record.scene_desc,
					oa_field_record.scene_result,
					oa_field_record.maintain_name,
					oa_field_record.maintain_tel,
					oa_field_record.maintain_no,
					oa_field_record.expect_time,
					oa_field_record.is_go_scene,
					oa_field_record.maintain_scene,
					oa_field_record.replace_car,
					oa_field_record.replace_start_time,
					oa_field_record.replace_end_time,
					oa_field_record.field_tolls,
					oa_field_record.parking,
					oa_field_record.replace_way
					FROM oa_repair  LEFT JOIN oa_field_record ON oa_field_record.repair_id = oa_repair.id ) AS a
					LEFT JOIN oa_car_maintain_result ON oa_car_maintain_result.order_no = a.order_no where a.order_no ='{$order_no}'")->queryOne();
			
			if($result){

				switch ($result['type']){
					case 1:
						$result['type'] = '客户报修';
						break;
					case 2:
						$result['type'] = '车辆出险';
						break;
				}
		
				switch ($result['source']){
					case 1:
						$result['source'] = '400电话';
						break;
				}
		
				switch ($result['urgency']){
					case 1:
						$result['urgency'] = '一般紧急';
						break;
					case 2:
						$result['urgency'] = '比较紧急';
						break;
					case 3:
						$result['urgency'] = '非常紧急';
						break;
				}
			}
			
			
			
			//根据车牌号查询 所属企业/个人  客户
			$cCustomer_id = '';
			$pCustomer_id = '';
			$car = (new \yii\db\Query())->select('id')->from('cs_car')->where('plate_number=:plate_number and is_del=0',[':plate_number'=>$result['car_no']])->one();
			//查询车辆是否在出租
			$let_record = (new \yii\db\Query())->select('cCustomer_id,pCustomer_id')->from('cs_car_let_record')
						 ->where('car_id=:car_id  AND let_time <=:let_time AND back_time =0 AND is_del=0',[':car_id'=>$car['id'],':let_time'=>time()])->orderBy('let_time DESC')->one();
			if($let_record)
			{
				//车牌租给了企业用户
				if($let_record['cCustomer_id'])
				{
					$cCustomer_id = $let_record['cCustomer_id'];
				}else{
					$pCustomer_id = $let_record['pCustomer_id'];
				}
				
				
			
			}else{
				//查询出车辆是否在试驾
				$trial_record = (new \yii\db\Query())->select('ctpd_cCustomer_id,ctpd_pCustomer_id')->from('cs_car_trial_protocol_details')
							  ->where('ctpd_car_id=:ctpd_car_id  AND ctpd_deliver_date <=:ctpd_deliver_date AND ctpd_back_date=0 AND ctpd_is_del=0',[':ctpd_car_id'=>$car['id'],':ctpd_deliver_date'=>date('Y-m-d',time())])->orderBy('ctpd_deliver_date DESC')->one();
				
				
				
				if($trial_record)
				{
					if($trial_record['ctpd_cCustomer_id'])
					{
						$cCustomer_id = $trial_record['ctpd_cCustomer_id'];
					}else{
						$pCustomer_id = $trial_record['ctpd_pCustomer_id'];
					}
				}
			}
			
			if($cCustomer_id)
			{
				$row = (new \yii\db\Query())->select('company_name as customer_name')->from('cs_customer_company')->where('id=:id AND is_del=0',[':id'=>$cCustomer_id])->one();
			}elseif($pCustomer_id){
				$row = (new \yii\db\Query())->select('id_name as customer_name')->from('cs_customer_personal')->where('id=:id AND is_del=0',[':id'=>$pCustomer_id])->one();
			}
			$result['customer_name'] =  !empty($row) ? $row['customer_name'] :'';
				
			return $this->render('archive-info',['result'=>$result]);
		}else{
			setcookie('id',$id);
			$db = \Yii::$app->db;
			
			$result = $db->createCommand("SELECT a.* ,cs_car.plate_number FROM (
							SELECT oa_car_maintain.*,
							oa_car_maintain_result.fault_result,
							oa_car_maintain_result.fault_why,
							oa_car_maintain_result.maintain_method,
							oa_car_maintain_result.leave_factory_time,
							oa_car_maintain_result.jieche_name,
							oa_car_maintain_result.return_replace_time
							from oa_car_maintain LEFT JOIN oa_car_maintain_result ON oa_car_maintain_result.order_no = oa_car_maintain.order_no
							) AS a  LEFT JOIN cs_car ON cs_car.id = a.car_id WHERE a.id={$id}")->queryOne();
			
			if($result){
			
				switch ($result['type']){
					case 1:
						$result['type'] = '客户报修';
						break;
					case 2:
						$result['type'] = '车辆出险';
						break;
					case 3:
						$result['type'] = '我方报修';
						break;
				}
			
				 
				$maintain_faults = (new \yii\db\Query())->from('oa_car_maintain_fault')->where('maintain_id = :maintain_id',[':maintain_id'=>$result['id']])->all();
				$maintain_faults = json_decode(json_encode($maintain_faults));
				foreach ($maintain_faults as $val)
				{
/* 					$tier_pid = substr($val->tier_pid, 0,-1);  //去掉最后的 “,”分号 */
					$tier_pid = trim($val->tier_pid,',');
				
					$categorys = (new \yii\db\Query())->from('oa_fault_category')->where("id in ({$tier_pid})")->all();
					if($categorys)
					{
						$val->big_category = @$categorys[0]['category'].' '. @$categorys[1]['category'].' '.@$categorys[2]['category'];
					}
				}
				
				
			}
			
			return $this->render('archive-info1',['result'=>$result,'maintain_faults'=>$maintain_faults]);
		
		}
		
	}
	
	function export_csv($filename,$data)
	{	
		$filename = mb_convert_encoding($filename, "GBK","UTF-8");
		//		header("Content-type: text/html; charset=utf-8");
  		header("Content-type:text/csv;charset=GBK");
		header("Content-Disposition:attachment;filename=".$filename);
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
	//	header("Content-Length:".strlen($data));
		echo $data;
	}
	
	/**
	 * 获取服务流程订单号
	 */
	public function actionGetBxOrder()
	{
		$type = yii::$app->request->post('type');
		$result = (new \yii\db\Query())->select('order_no')->from('oa_repair')->where('status >=4 and type=:type',[':type'=>$type])->all();
		
		echo json_encode($result);
		exit();
	}
	
	/**
	 * 获取服务流程 外勤登记数据
	 */
	public function actionGetBxData()
	{
		$order_no = yii::$app->request->post('order_no');
		
		$result = (new \yii\db\Query())->select('
				oa_repair.car_no,
				oa_repair.repair_name,
				oa_repair.tel,
				oa_repair.tel_time,
				oa_repair.address,
				oa_repair.bearing,
				oa_repair.fault_start_time,
				oa_repair.assign_name,
				oa_field_record.*
				'
				)->from('oa_repair')->join('LEFT JOIN','oa_field_record','oa_field_record.repair_id = oa_repair.id')->where('order_no=:order_no',[':order_no'=>$order_no])->one();
		
		if($result)
		{
			$caer_row = (new \yii\db\Query())->select('id')->from('cs_car')->where('plate_number=:plate_number and is_del=0',[':plate_number'=>$result['car_no']])->one();
			
			$result['car_id'] = $caer_row['id'];
			$result['tel_time']  = !empty($result['tel_time']) ? date('Y-m-d H:i',$result['tel_time']) :'';
			$result['time']  = !empty($result['time']) ? date('Y-m-d H:i',$result['time']) :'';
			$result['fault_start_time']  = !empty($result['fault_start_time']) ? date('Y-m-d H:i',$result['fault_start_time']) :'';
			switch ($result['is_go_scene']){
				case 0:
					$result['way'] = '现场维修';
					break;
				case 1:
					$result['way']= '进厂维修';
					break;
			}
			$result['expect_time']  = !empty($result['expect_time']) ? date('Y-m-d',$result['expect_time']) :'';
			
		}
		
		echo json_encode($result);
		exit();
	}
	
	/**
	 * 验车单照片上传窗口
	 */
	public function actionUploadWindow(){
		$columnName = yii::$app->request->get('columnName'); //判断上传哪种图片
		$isEdit = intval(yii::$app->request->get('isEdit')); //判断是否为修改图片上传
		$view = $isEdit > 0 ? 'upload-window-edit' : 'upload-window';
		return $this->render($view,[
				'columnName'=>$columnName
				]);
	}
	
	/**
	 * 上传验车单缩略图
	 */
	/*public function actionUploadThumb(){
		$columnName = yii::$app->request->post('columnName');
		$isEdit = intval(yii::$app->request->get('isEdit'));
		$upload = UploadedFile::getInstanceByName($columnName);
		$fileExt = $upload->getExtension();
		$allowExt = ['jpg','png','jpeg','gif'];
		$returnArr = [];
		$returnArr['status'] = false;
		$returnArr['info'] = '';
		$returnArr['columnName'] = $columnName;
		if(!in_array($fileExt,$allowExt)){
			$returnArr['info'] = '文件格式错误！';
			$oStr = $isEdit > 0 ? 'CarFaultEdit' : 'CarFaultRegister';
			return '<script>window.parent.'.$oStr.'.uploadComplete('.json_encode($returnArr).');</script>';
		}
		$fileName = uniqid().'.'.$fileExt;
		// 处理上传图片的储存路径，这里指定在与入口文件同级的uploads目录之下。
		$storePath = 'uploads/image/repair/';
		if(!is_dir($storePath)){
			mkdir($storePath, 0777, true);
		}
		$storePath .= $fileName;
		if($upload->saveAs($storePath)){
			$returnArr['status'] = true;
			$returnArr['info'] = $fileName;
			$returnArr['storePath'] = $storePath;
		}else{
			$returnArr['info'] = $upload->error;
		}
		$oStr = $isEdit > 0 ? 'ProcessRepairEdit' : 'ProcessRepairUpload';
		return '<script>window.parent.'.$oStr.'.uploadComplete('.json_encode($returnArr).');</script>';
	}*/
	public function actionUploadThumb(){
    	$columnName = yii::$app->request->post('columnName');
    	$isEdit = intval(yii::$app->request->get('isEdit'));
    	$returnArr = [];
		$returnArr['status'] = false;
		$returnArr['info'] = '';
    	$returnArr['columnName'] = $columnName;
    	
    	$resizeimage = new Resizeimage();
    	$r = $resizeimage->resizeImage($columnName,1024,768,'uploads/image/fault/');
    	if(!$r['url']){
    		$returnArr['info'] = $r['info'];
    	}else {
    		$returnArr['status'] = true;
    		$returnArr['info'] = $r['info'];
    		$returnArr['storePath'] = $r['url'];
            //$returnArr['storePath'] = explode("/", $r['url']);
            //$returnArr['storePath'] = $returnArr['storePath'][3];
    	}
    	$oStr = $isEdit > 0 ? 'ProcessRepairEdit' : 'ProcessRepairUpload';
    	return '<script>window.parent.'.$oStr.'.uploadComplete('.json_encode($returnArr).');</script>';
    }
	
	/**
	 * 故障指示灯
	 */
	public function actionIndicatorLight()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			$query = $db->select('*')->from('oa_indicator_light');
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			
			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			if($sort)
			{
				$query->orderBy("{$sort} {$order}");
			}else{
				$query->orderBy("id DESC");
			}
			$total = $query->count();
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
			$result = $query->offset($pages->offset)->limit($pages->limit)->all();
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
		$buttons = $this->getCurrentActionBtn();
		return $this->render('indicator-light',['buttons'=>$buttons]);
	}
	
	/**
	 * 增加故障指示灯
	 */
	public function actionIndicatorLightAdd()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$name 		= yii::$app->request->post('name');
			$image_url	= yii::$app->request->post('image_url');
						
			$db = \Yii::$app->db;
			$result = $db->createCommand()->insert('oa_indicator_light',
					 ['name'	=> $name,
					 'image_url'=> $image_url,
					 ])->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '新增成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '新增失败！';
			}
			
			
			return json_encode($returnArr);
		}
		return $this->render('indicator-light-add');
	}
	
	/**
	 * 修改故障指示灯
	 */
	public function actionIndicatorLightEdit()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$name 		= yii::$app->request->post('name');
			$image_url	= yii::$app->request->post('image_url');

			$id 		= yii::$app->request->post('id');
			$db = \Yii::$app->db;
			$result = $db->createCommand()->update('oa_indicator_light',
					 ['name'	=> $name,
					 'image_url'=> $image_url,
					 ],'id=:id',[':id'=>$id]
					)->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '修改成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '修改失败！';
			}
			
			
			return json_encode($returnArr);
		}
		$id = intval(yii::$app->request->get('id'));
		$result = (new \yii\db\Query())->from('oa_indicator_light')->where('id=:id',[':id'=>$id])->one();
		return $this->render('indicator-light-edit',['result'=>$result]);
	}
	
	
	public function actionIndicatorLightDel()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$db = \Yii::$app->db;
			$result = $db->createCommand()->delete('oa_indicator_light','id=:id',[':id'=>$id])->execute();
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
	 * 供AJAX调用
	 */
	public function actionAjaxIndicatorLight()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$indicator_light = yii::$app->request->post('indicator_light');
			if(!empty($indicator_light))
			{
				$indicator_ids = implode(',', $indicator_light);
				$result = (new \yii\db\Query())->from('oa_indicator_light')->where("id in({$indicator_ids})")->all();
			}else{
				$result = [];
			}
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '成功！';
				$returnArr['data'] = $result;
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '没有选择！';
				$returnArr['data'] = array();
			}
			
			return json_encode($returnArr);
		}
		$result = (new \yii\db\Query())->from('oa_indicator_light')->all();
		return $this->render('ajax-indicator-light',['result'=>$result]);
	}
	
	
	public function _indicator_light_info($indicator_light = null)
	{
		if(empty($indicator_light))
		{
			return null;
		}
		
		$result = (new \yii\db\Query())->from('oa_indicator_light')->where("id in ({$indicator_light})")->all();
		return $result;
	}
	
	/**
	 * 车辆维修登记页
	 */
	public function actionFault()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$maintain_id = yii::$app->request->get('maintain_id');
			
			$query = (new \yii\db\Query())->from('oa_car_maintain_fault')->where('maintain_id =:maintain_id',[':maintain_id'=>$maintain_id]);
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
		
			$total = $query->count();
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
			$result = $query->offset($pages->offset)->limit($pages->limit)->all();
			
			$result = json_decode(json_encode($result));
			foreach ($result as $val)
			{
/* 				$tier_pid = substr($val->tier_pid, 0,-1);  //去掉最后的 “,”分号 */
				$tier_pid = trim($val->tier_pid,',');
			
				$categorys = (new \yii\db\Query())->from('oa_fault_category')->where("id in ({$tier_pid})")->all();
				if($categorys)
				{
			
					$val->category1 = @$categorys[0]['category'];
					$val->code1		= @$categorys[0]['code'];
					$val->category2 = @$categorys[1]['category'];
					$val->code2		= @$categorys[1]['code'];
					$val->category3 = @$categorys[2]['category'];
					$val->code3		= @$categorys[2]['code'];
				}
			}
			
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
		
		$maintain_id = yii::$app->request->get('id');
		$maintain = (new \yii\db\Query())->select('oa_car_maintain.*,
				oa_car_maintain_result.fault_why,
				oa_car_maintain_result.maintain_method,oa_car_maintain_result.accessories')
				->from('oa_car_maintain')
				->join('LEFT JOIN','oa_car_maintain_result','oa_car_maintain_result.order_no = oa_car_maintain.order_no')
				->where('oa_car_maintain.id=:id',[':id'=>$maintain_id])->one();
		
		$buttons = $this->getCurrentActionBtn();
		return $this->render('fault',['buttons'=>$buttons,'maintain_id'=>$maintain_id,'maintain'=>$maintain]);
	}
	
	
	public function actionFaultAdd()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$maintain_id 	= yii::$app->request->post('maintain_id');
			$category_id	= yii::$app->request->post('category_id');
			
			
			$fault_category = (new \yii\db\Query())->from('oa_fault_category')->where('id=:id',[':id'=>$category_id])->one();
			$category = $fault_category['category'];
			$tier_pid =	$fault_category['tier_pid'];
			$total_code = $fault_category['total_code'];
			
			$db = \Yii::$app->db;
			$result = $db->createCommand()->insert('oa_car_maintain_fault',
					['maintain_id'	=> $maintain_id,
					'category'		=> $category,
					'tier_pid'		=> $tier_pid,
					'total_code'	=> $total_code,
					])->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '新增成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '新增失败！';
			}
			
			
			return json_encode($returnArr);
		}
		$maintain_id = yii::$app->request->get('maintain_id');
		$faults = (new \yii\db\Query())->from('oa_fault_category')->where('is_category=0')->all();
		
		return $this->render('fault-add',['faults'=>$faults,'maintain_id'=>$maintain_id]);
	}
	
	
	public function actionFaultDel()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$db = \Yii::$app->db;
			$result = $db->createCommand()->delete('oa_car_maintain_fault','id=:id',[':id'=>$id])->execute();
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
	 * 故障原因归档
	 */
	public function actionFaultArchive()
	{
		if($_SERVER['REQUEST_METHOD'] =='POST')
		{
			$id = yii::$app->request->post('id');
			$db = \Yii::$app->db;
			$result = $db->createCommand()->update('oa_car_maintain',
					['status'	=> 8],
					'id=:id',[':id'=>$id]
					)->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '故障原因归档成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '故障原因归档失败！';
			}
			
			
			return json_encode($returnArr);
		}
	}
	/**
	 * 拱AJAX调用
	 */
	public function actionAjaxGetFault()
	{
		$id = yii::$app->request->post('id');
		$query = (new \yii\db\Query())->from('oa_fault_category')->where('is_category=0');
		if($id)
		{
			$query->andWhere(['like','tier_pid',$id.',']);
		}
		$result = $query->all();
		return json_encode($result);
		exit();
		
	}
	
	/**
	 *	发送邮件
	 * @param unknown_type $module_code
	 * @param unknown_type $controller_code
	 * @param unknown_type $action_code
	 * @param unknown_type $body
	 * @param unknown_type $oc
	 */
	public function send_email($module_code,$controller_code,$action_code,$body,$oc,$name=null)
	{
		$sendto_email =[];
		if($action_code == 'affirm')
		{
			$admins = (new \yii\db\Query())->select('email')->from('cs_admin')->where('name=:name AND is_del=0',[':name'=>$name])->all();
			foreach ($admins as $admin)
			{
				$sendto_email[] = $admin['email'];
			}
			
		}else{
			$mca = (new \yii\db\Query())->select('id,name')->from('cs_rbac_mca')->where('module_code=:module_code AND controller_code=:controller_code AND action_code=:action_code',[':module_code'=>$module_code,':controller_code'=>$controller_code,':action_code'=>$action_code])->one();
			//获取拥有当前权限的角色
			$roles = (new \yii\db\Query())->select('role_id')->from('cs_rbac_role_mca')->where('mca_id=:mca_id  and role_id !=1',[':mca_id'=>$mca['id']])->all();
			if($roles)
			{
				foreach ($roles as $role){
					//查询出当前角色下用户
					$emails = (new \yii\db\Query())->select('email,operating_company_id,operating_company_ids')->from('cs_admin_role')
					->where('role_id=:role_id',[':role_id'=>$role['role_id']])
					->join('LEFT JOIN','cs_admin','cs_admin.id=cs_admin_role.admin_id')
					->all();
					if($emails)
					{
						foreach ($emails as $email)
						{
			
							$arr = !empty($email['operating_company_ids'])? explode(',', $email['operating_company_ids']): array($email['operating_company_id']);
							if(!empty($email['email']) && in_array($oc, $arr))
							{			
								$sendto_email[] = $email['email'];
							}
			
						}
					}
				}
			}
		}
		
		
		
		
/* 		
		echo '<pre>';
		echo 'mac=';
		var_dump($mca);
		echo 'roles=';
		
		var_dump($roles);
		var_dump($sendto_email);
		exit(); */
		$sendto_email = array_filter($sendto_email);
		if(!empty($sendto_email))
		{
			$mail = new Mail();
			$subject = '流程审批';
			$body = "你有一个待处理的事项：【{$body}】。请及时登录地上铁系统查看并处理该事项，以免影响工作进度。点击这里：<a href='http://xt.dstzc.com'>登录地上铁系统</a>，或者在浏览器中输入以下地址登录 ：xt.dstzc.com 。<br>如果对此有疑问和建议，请向系统开发部反馈。";
			$mail->send($sendto_email,$subject, $body);
		}
	}
}