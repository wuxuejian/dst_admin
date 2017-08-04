<?php
/**
 * 合同审批类
 * @author pengyl
 *
 */
namespace backend\modules\process\controllers;
use backend\classes\MyUploadFile;

use backend\classes\Approval;
use backend\models\ContractApproval;
use backend\models\Department;
use backend\controllers\BaseController;
use yii\web\UploadedFile;
use yii;
use yii\data\Pagination;
class ContractApprovalController extends BaseController
{
	
public function actionIndex()
	{
		//获取本页按钮
        $buttons = $this->getCurrentActionBtn(); 
        return $this->render('index',[
            'buttons'=>$buttons
        ]);
	}
	
	/**
	 * 获取【合同审批流程】列表
	 */
	public function actionGetList()
	{
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
		$query = ContractApproval::find()
		->select([
				'{{oa_contract_approval}}.*',
				'department_name'=>'{{%department}}.`name`',
				])->leftJoin('cs_department', 'oa_contract_approval.oper_department_id = cs_department.id');
		//////查询条件
		if(yii::$app->request->get('contract_no')){
			$query->andFilterWhere([
					'like',
					'{{oa_contract_approval}}.`contract_no`',
					yii::$app->request->get('contract_no')
					]);
		}
		if(yii::$app->request->get('contract_name')){
			$query->andFilterWhere([
					'like',
					'{{oa_contract_approval}}.`contract_name`',
					yii::$app->request->get('contract_name')
					]);
		}
		if(yii::$app->request->get('contract_type')){
			$query->andFilterWhere([
					'=',
					'{{oa_contract_approval}}.`contract_type`',
					yii::$app->request->get('contract_type')
					]);
		}
		//         exit($query->createCommand()->sql);
		
		//////排序
		$sortColumn = yii::$app->request->get('sort');
		$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
		$orderBy = '';
		if($sortColumn){
			switch ($sortColumn) {
				default:
					$orderBy = '{{oa_contract_approval}}.`'.$sortColumn.'` ';
				break;
			}
		}else{
			$orderBy = '{{oa_contract_approval}}.`id` ';
		}
		$orderBy .= $sortType;
		$total = $query->count();
		$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
		$data = $query->offset($pages->offset)->limit($pages->limit)
		->orderBy($orderBy)
		->asArray()->all();
		//加载审批状态
		if($data){
			$approval = new Approval();
			foreach ($data as $key=>$val)
			{
				$data[$key]['approval_time'] = ((strtotime($val['approval_end_time'])-strtotime($val['approval_start_time']))/86400).'天';
				//avg 当前url路由 ，id ，申请状态
				$data[$key]['current_status'] = $approval->approval_status('process/contract-approval/index',$val['id'],$val['is_cancel'],'oa_contract_approval');
// 				$val->count_down = $approval->count_down('process/contract-approval/index','oa_contract_approval');
			}
		}
		
		$returnArr = [];
		$returnArr['rows'] = $data;
		$returnArr['total'] = $total;
		echo json_encode($returnArr);
	}
	
	/**
	 * 新建合同
	 */
	public function actionAdd()
	{
		//data submit start
		if(yii::$app->request->isPost){
			$model = new ContractApproval;
			$model->load(yii::$app->request->post(),'');
			$returnArr = [];
			$returnArr['status'] = true;
			$returnArr['info'] = '';
			if($model->validate()){
				$model->user_id = $_SESSION['backend']['adminInfo']['id'];
				// 判断超全局数组$_FILES['contract']能否接收到上传的文件。
				if (!isset($_FILES['contract'])){
					$returnArr['status'] = false;
					$returnArr['info'] = '请上传有效合同！';
					echo json_encode($returnArr);
					return null;
				}
				$contract_url='';
				// 具体处理上传文件
				// print_r($_FILES['stationImage']);exit;
				// 注意：这里是一次上传多个文件。这里循环处理。
				$file["name"] = $_FILES['contract']['name'];            // 被上传文件的名称
				$file["type"] = $_FILES['contract']['type'];        // 被上传文件的类型，image/jpeg等
				$file["size"] = $_FILES['contract']['size'];        // 被上传文件的大小，以字节计
				$file["tmp_name"] = $_FILES['contract']['tmp_name']; // 存储在服务器的文件的临时副本的名称
				$file["error"] = $_FILES['contract']['error'];        // 由文件上传导致的错误代码,0表示正常
				
				//设置上传文件类型
				$upfile = explode('.',$file["name"]);
				list($mainType,$subType) = explode("/", $file["type"]);
				if($mainType != 'image'){
					$subType = $upfile[count($upfile)-1];
					$file["type"] = $mainType.'/'.$subType;
				}
				
				
				$res = (new MyUploadFile())->handleUploadFile($file, 'contract');
				if (!$res['error']) {
					$contract_url = $res['filePath'];
				}else {
					$returnArr['status'] = false;
					$returnArr['info'] = $res['msg'];
					echo json_encode($returnArr);
					return null;
				}
				
				$model->contract_url = $contract_url;
				if(yii::$app->request->post('business_way')=='other'){
					$model->business_way = yii::$app->request->post('other_business_way');
				}
				if($model->save(false)){
					$returnArr['status'] = true;
					$returnArr['info'] = '创建成功！';
				}else{
					$returnArr['status'] = false;
					$returnArr['info'] = '创建失败！';
				}
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
			if($returnArr['status']){
				if(isset($noBusinessInsuranceTip)){
					$returnArr['info'] .= '<br/>'.$noBusinessInsuranceTip;
				}
			}
			echo json_encode($returnArr);
			return null;
		}
		//获取部门数据
		$department = Department::find()
		->select(['id','name'])
		->where(['is_del'=>0])
		->asArray()
		->all();
		return $this->render('add',[
				'department'=>$department
				]);
	}
	
	/**
	 * 合同修改
	 */
	public function actionEdit()
	{
		if(yii::$app->request->isPost){
			$id = yii::$app->request->post('id') or die('param id is required');
			$model = ContractApproval::findOne(['id'=>$id]);
			$model or die('record not found');
			$model->load(yii::$app->request->post(),'');
			$returnArr = [];
			$returnArr['status'] = true;
			$returnArr['info'] = '';
			if($model->validate()){
				// 判断超全局数组$_FILES['contract']能否接收到上传的文件。
				if (isset($_FILES['contract'])){
					$file["name"] = $_FILES['contract']['name'];            // 被上传文件的名称
					$file["type"] = $_FILES['contract']['type'];        // 被上传文件的类型，image/jpeg等
					$file["size"] = $_FILES['contract']['size'];        // 被上传文件的大小，以字节计
					$file["tmp_name"] = $_FILES['contract']['tmp_name']; // 存储在服务器的文件的临时副本的名称
					$file["error"] = $_FILES['contract']['error'];        // 由文件上传导致的错误代码,0表示正常
					
					//设置上传文件类型
					$upfile = explode('.',$file["name"]);
					list($mainType,$subType) = explode("/", $file["type"]);
					if($mainType != 'image'){
						$subType = $upfile[count($upfile)-1];
						$file["type"] = $mainType.'/'.$subType;
					}
					
					$res = (new MyUploadFile())->handleUploadFile($file, 'contract');
					if (!$res['error']) {
						$contract_url = $res['filePath'];
					}else {
						$returnArr['status'] = false;
						$returnArr['info'] = $res['msg'];
						echo json_encode($returnArr);
						return null;
					}
					$model->contract_url = $contract_url;
				}
				
				if($model->save(false)){
					$returnArr['status'] = true;
					$returnArr['info'] = '修改成功！';
				}else{
					$returnArr['status'] = false;
					$returnArr['info'] = '修改失败！';
				}
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
			return json_encode($returnArr);
		}
		//data submit end
		$id = yii::$app->request->get('id') or die('param id is required');
		$model = ContractApproval::findOne(['id'=>$id]);
		$model or die('record not found');
	
		//获取部门数据
		$department = Department::find()
		->select(['id','name'])
		->where(['is_del'=>0])
		->asArray()
		->all();
		
		return $this->render('edit',[
				'obj'=>$model->getAttributes(),
				'department'=>$department
				]);
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
		$approval->approval_status('process/contract-approval/index',$id,$is_cancel,'oa_contract_approval');
		$returnArr['current_operation'] = $approval->approval_operation($is_cancel,$id,'oa_contract_approval');
		return json_encode($returnArr);
	}
	
	/**
	 * 查看
	 */
	public function actionDetail(){
		$id = yii::$app->request->get('id') or die('param id is required');
		$query = ContractApproval::find()
		->select([
				'{{oa_contract_approval}}.*',
				'department_name'=>'{{%department}}.`name`',
				])->leftJoin('cs_department', 'oa_contract_approval.oper_department_id = cs_department.id');
		$query->andFilterWhere([
				'=',
				'{{oa_contract_approval}}.`id`',
				$id
				]);
		$detail = $query->offset(0)->limit(1)->asArray()->one();
		return $this->render('detail',[
				'detail'=>$detail,
				]);
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
			$result = $db->createCommand()->delete('oa_contract_approval','id=:id',[':id'=>$id])->execute();
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
	 * 提交申请
	 */
	public function actionConfirm()
	{
		$approval = new Approval();
		$result = $approval->confirm('process/contract-approval/index', yii::$app->request->get('id'));
		if($result)
		{
			$returnArr['status'] = true;
			$returnArr['info'] = '提交申请成功！';
		}else{
			$returnArr['status'] = false;
			$returnArr['info'] = '提交申请失败！';
		}
	
		return json_encode($returnArr);
	}
	
	/**
	 * 取消申请
	 */
	public function actionCancel()
	{
		$id = yii::$app->request->get('id');
		$template_id = yii::$app->request->get('template_id');
		$approval = new Approval();
		$result = $approval->cancel('process/contract-approval/index', $id,'oa_contract_approval',$template_id,0);
		if($result)
		{
			$returnArr['status'] = true;
			$returnArr['info'] = '取消申请成功！';
		}else{
			$returnArr['status'] = false;
			$returnArr['info'] = '取消申请失败！';
		}
	
		return json_encode($returnArr);
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
			$result = $approval->no_pass($id, $step_id,'process/contract-approval/index','oa_contract_approval',$template_id,$remark);
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
		/*  		echo '<pre>';
		 var_dump($result);exit();  */
		return $this->render('no-pass',['result'=>$result]);
	}
}