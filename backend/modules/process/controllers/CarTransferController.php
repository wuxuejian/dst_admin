<?php
/**
 * 车辆调拨控制器
 * time    2017/08/09 17:15
 * @author pengyl
 */
namespace backend\modules\process\controllers;

use backend\models\Car;

use backend\models\CarTransferDetails;

use backend\models\CarTransferList;

use backend\models\CarBrand;

use backend\controllers\BaseController;
use backend\models\CarTransfer;
use yii;
use yii\data\Pagination;

class CarTransferController extends BaseController
{
	/**
	 * 需求发起
	 */
    public function actionIndex1()
    {
    	$buttons = $this->getCurrentActionBtn();
        //加载站点end
    	return $this->render('index1',[
    			'buttons'=>$buttons
    			]);
    }
    
    /**
     * 需求发起列表
     */
    public function actionGetList1()
    {
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    	$connection = yii::$app->db;
    	$query = CarTransfer::find()
    		->select([
    			'{{%car_transfer}}.*',
    			'{{%operating_company}}.name originator_operating_company_name',
    			])
    		->leftJoin('{{%operating_company}}', '{{%car_transfer}}.`originator_operating_company_id` = {{%operating_company}}.`id`')
    		->andWhere(['=','{{%car_transfer}}.`is_del`',0]);
    	//查询条件
    	$dd_number = yii::$app->request->get('dd_number');
    	if($dd_number){
    		$query->andFilterWhere(
    				['like','{{%car_transfer}}.dd_number',$dd_number]);
    	}
    	$status = yii::$app->request->get('status');
    	if($status){
    		$query->andFilterWhere(
    				['=','{{%car_transfer}}.status',$status]);
    	}
    	if(yii::$app->request->get('start_add_time')){
    		$query->andFilterWhere(['>=','{{%car_transfer}}.`add_time`',strtotime(yii::$app->request->get('start_add_time'))]);
    	}
    	$end_add_time = yii::$app->request->get('end_add_time');
    	if($end_add_time){
    		$end_add_time = $end_add_time.' 23:59:59';
    		$query->andFilterWhere(['<=','{{%car_transfer}}.`add_time`',strtotime($end_add_time)]);
    	}
    	//检测是否要根据当前登录人员所属运营公司来显示列表数据
    	$isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
    	if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
    		$query->andWhere("{{%car_transfer}}.`originator_operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
    	}
    	//查询条件结束
    	//排序开始
    	$sortColumn = yii::$app->request->get('sort');
    	$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
    	$orderBy = '';
    	if($sortColumn){
    		switch ($sortColumn) {
    			default:
    				$orderBy = '`'.$sortColumn.'` ';
    			break;
    		}
    	}else{
    		$sortType = 'desc';
    		$orderBy = '{{%car_transfer}}.`add_time` ';
    	}
    	$orderBy .= $sortType;
    	//排序结束
    	
//     	echo $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->createCommand()->getRawSql();exit;
    	$total = $query->count();
    	$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
    	$data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
    	
    	$returnArr = [];
    	$returnArr['rows'] = $data;
    	$returnArr['total'] = $total;
    	echo json_encode($returnArr);
    }
    
    /**
     * 新增需求发起
     */
	public function actionAdd1(){
		$connection = yii::$app->db;
		if(yii::$app->request->isPost){
			$returnArr['status'] = false;
			$dd_number = yii::$app->request->post('dd_number');//钉钉审批号
			$originator = yii::$app->request->post('originator');//需求提报人
			$add_time = time();//发起日期
			$originator_operating_company_id = yii::$app->request->post('originator_operating_company_id');//提报人所属运营公司
			
			if(!@$_FILES['attachment']){
				$returnArr['info'] = '请选择流程附件！';
				exit(json_encode($returnArr));
			}
			//上传流程附件
			if(@$_FILES['attachment']){
				$file_path="uploads/cartransfer/";
				if(!is_dir($file_path)){
					mkdir($file_path);
				}
				$file_path .= date("Ymd").'/';
				if(!is_dir($file_path)){
					mkdir($file_path);
				}
				$ext_t = explode('.', $_FILES['attachment']['name']);
				$ext = strtolower(end($ext_t));
				if (!in_array($ext, array('pdf'))) {
					$returnArr['info'] = '文件格式不正确！';
					exit(json_encode($returnArr));
				}
				$attachment_url = $file_path.date("YmdHis").'.'.$ext;
				if(!move_uploaded_file($_FILES['attachment']['tmp_name'],$attachment_url)){
					$returnArr['info'] = '文件上传失败！';
					$returnArr['error'] = $_FILES['attachment']['error'];
					exit(json_encode($returnArr));
				}
			}
			
			$model = new CarTransfer;
			$model->dd_number = $dd_number;
			$model->add_time = $add_time;
			$model->attachment_url = $attachment_url;
			$model->originator = $originator;
			$model->originator_operating_company_id = $originator_operating_company_id;
			if($model->validate()){
				if($model->save(false)){
					$returnArr['status'] = true;
					$returnArr['info'] = '需求发起成功！';
				}else{
					@unlink ($attachment_url);
					$returnArr['status'] = false;
					$returnArr['info'] = '需求发起失败！';
				}
			}else{
				@unlink ($attachment_url);
				$returnArr['status'] = false;
				$returnArr['info'] = '数据验证错误！';
			}
			return json_encode($returnArr);
		}
		
		return $this->render('add1');
	}
	
	/**
	 * 修改需求发起
	 */
	public function actionEdit1(){
		$connection = yii::$app->db;
		if(yii::$app->request->isPost){
			$returnArr['status'] = false;
			$id = yii::$app->request->post('id') or die('param id is required');
            $model = CarTransfer::findOne(['id'=>$id]);
            $model or die('record not found');
	
			$model->setScenario('edit');
			$model->load(yii::$app->request->post(),'');
			
			//上传流程附件
			if(@$_FILES['attachment']){
				$file_path="uploads/cartransfer/";
				if(!is_dir($file_path)){
					mkdir($file_path);
				}
				$file_path .= date("Ymd").'/';
				if(!is_dir($file_path)){
					mkdir($file_path);
				}
				$ext_t = explode('.', $_FILES['attachment']['name']);
				$ext = strtolower(end($ext_t));
				if (!in_array($ext, array('pdf'))) {
					$returnArr['info'] = '文件格式不正确！';
					exit(json_encode($returnArr));
				}
				$attachment_url = $file_path.date("YmdHis").'.'.$ext;
				if(!move_uploaded_file($_FILES['attachment']['tmp_name'],$attachment_url)){
					$returnArr['info'] = '文件上传失败！';
					$returnArr['error'] = $_FILES['attachment']['error'];
					exit(json_encode($returnArr));
				}
				$model->attachment_url = $attachment_url;
			}
			if($model->validate()){
				if($model->save(false)){
					$returnArr['status'] = true;
					$returnArr['info'] = '操作成功！';
				}else{
					if(@$attachment_url){
						@unlink ($attachment_url);
					}
					$returnArr['status'] = false;
					$returnArr['info'] = '操作失败！';
				}
			}else{
				if(@$attachment_url){
					@unlink ($attachment_url);
				}
				$returnArr['status'] = false;
				$returnArr['info'] = '数据验证错误！';
			}
			return json_encode($returnArr);
		}
	
		$id = yii::$app->request->get('id') or die('param id is required');
		$model = CarTransfer::findOne(['id'=>$id]);
		$model or die('record not found');
		
		return $this->render('edit1',[
				'carTransferInfo'=>$model->getAttributes()
				]);
	}
	
	/**
	 * 删除流程
	 */
	public function actionRemove1(){
		$id = intval(yii::$app->request->get('id')) or die('param id is required');
		
		$returnArr = [];
		if(CarTransfer::updateAll(['is_del'=>1],['id'=>$id])){
			$returnArr['status'] = true;
			$returnArr['info'] = '删除成功！';
		}else{
			$returnArr['status'] = false;
			$returnArr['info'] = '删除失败！';
		}
		echo json_encode($returnArr);
	}
	
	/**
	 * 进入下一流程
	 */
	public function actionToNextStatus(){
		$next_status = yii::$app->request->get('next_status');
		$id = intval(yii::$app->request->get('id')) or die('param id is required');
		$connection = yii::$app->db;
		if($next_status == 1){	//第1步（需求发起提交）
			//1.计算需求车辆台数
			$car_number = 0;	//需求车辆台数
			$transfer_list = CarTransferList::find()
					->select([
					'sum({{%car_transfer_list}}.number) car_number'
					])
					->where([
							'{{%car_transfer_list}}.`is_del`'=>0,
							'{{%car_transfer_list}}.`transfer_id`'=>$id
							])
					->asArray()->one();
			if($transfer_list){
				$car_number = $transfer_list['car_number'];
			}
			if(!$car_number){
				$returnArr['status'] = false;
				$returnArr['info'] = '请添加需求车辆！';
				exit(json_encode($returnArr));
			}
			//2.更新
			$result = CarTransfer::updateAll(
					[
						'status'=>$next_status,
						'car_number'=>$car_number
					],
					'id=:id and status=:status-1',[':id'=>$id,':status'=>$next_status]);
			if($result){
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！';
			}else {
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
			}
			exit(json_encode($returnArr));
		}else if($next_status == 2){	//第2步(车辆满足提交)
			//1.状态变更条件判断
			$car_ids = array();	//需要变更状态的车辆ID
			$query = CarTransferList::find()
			->select([
					'{{%car_transfer_list}}.id,
					{{%car_transfer_list}}.number,
					{{%car_transfer_list}}.car_brand_id'
					])
					->where([
							'{{%car_transfer_list}}.`is_del`'=>0,
							'{{%car_transfer_list}}.`transfer_id`'=>$id
							]);
			$transferLists = $query->asArray()->all();
			foreach ($transferLists as $row){
				$detailsList = CarTransferDetails::find()
				->select([
						'{{%car_transfer_details}}.car_id'
						])
						->where([
								'{{%car_transfer_details}}.`transfer_list_id`'=>$row['id'],
								'{{%car_transfer_details}}.`is_del`'=>0
								])->asArray()->all();
				foreach ($detailsList as $details){
					if(in_array($details['car_id'], $car_ids)){
						$returnArr['status'] = false;
						$returnArr['info'] = "操作失败！有重复车辆！";
						exit(json_encode($returnArr));
					}
					array_push($car_ids, $details['car_id']);
				}
				if(count($detailsList) != $row['number']){
					$car_brand = CarBrand::findOne(['id'=>$row['car_brand_id']]);
					$returnArr['status'] = false;
					$returnArr['info'] = "操作失败！{$car_brand['name']}品牌车辆信息不全！";
					exit(json_encode($returnArr));
				}
			}
			//2.车辆一级状态变更
			$transaction = $connection->beginTransaction();
			
			$result = CarTransfer::updateAllCounters(['status'=>1],'id=:id and status=:status-1',[':id'=>$id,':status'=>$next_status]);
			$statusRet = Car::changeCarStatusNew($car_ids, 'TRANSFER', 'process/car-transfer/to-next-status', '调拨流程',['car_status'=>'STOCK','is_del'=>0]);
			
			if($result && ($statusRet?$statusRet['status']:true)){
				$transaction->commit();  //提交事务
// 				$transaction->rollback(); //回滚事务
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！';
				echo json_encode($returnArr);
			}else {
				$transaction->rollback(); //回滚事务
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败，请确认车辆当前状态！';
				echo json_encode($returnArr);
			}
		}else {
			$returnArr = [];
			if(CarTransfer::updateAllCounters(['status'=>1],'id=:id and status=:status-1',[':id'=>$id,':status'=>$next_status])){
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
			}
			echo json_encode($returnArr);
		}
	}
	
	/**
	 * 需求车辆管理
	 */
	public function actionList1(){
		$connection = yii::$app->db;
		if(yii::$app->request->isPost){
			$returnArr['status'] = false;
			$id = yii::$app->request->post('id') or die('param id is required');
			$model = CarTransfer::findOne(['id'=>$id]);
			$model or die('record not found');
	
			$model->setScenario('edit');
			$model->load(yii::$app->request->post(),'');
	
			//上传流程附件
			if(@$_FILES['attachment']){
				$file_path="uploads/cartransfer/";
				if(!is_dir($file_path)){
					mkdir($file_path);
				}
				$file_path .= date("Ymd").'/';
				if(!is_dir($file_path)){
					mkdir($file_path);
				}
				$ext_t = explode('.', $_FILES['attachment']['name']);
				$ext = strtolower(end($ext_t));
				if (!in_array($ext, array('pdf'))) {
					$returnArr['info'] = '文件格式不正确！';
					exit(json_encode($returnArr));
				}
				$attachment_url = $file_path.date("YmdHis").'.'.$ext;
				if(!move_uploaded_file($_FILES['attachment']['tmp_name'],$attachment_url)){
					$returnArr['info'] = '文件上传失败！';
					$returnArr['error'] = $_FILES['attachment']['error'];
					exit(json_encode($returnArr));
				}
				$model->attachment_url = $attachment_url;
			}
			if($model->validate()){
				if($model->save(false)){
					$returnArr['status'] = true;
					$returnArr['info'] = '操作成功！';
				}else{
					if(@$attachment_url){
						@unlink ($attachment_url);
					}
					$returnArr['status'] = false;
					$returnArr['info'] = '操作失败！';
				}
			}else{
				if(@$attachment_url){
					@unlink ($attachment_url);
				}
				$returnArr['status'] = false;
				$returnArr['info'] = '数据验证错误！';
			}
			return json_encode($returnArr);
		}
	
		$id = yii::$app->request->get('id') or die('param id is required');
		
		$car_transfer = $connection->createCommand(
				'select a.*,b.name originator_operating_company_name
				from cs_car_transfer a
				left join cs_operating_company b on a.originator_operating_company_id=b.id
				where a.id='.$id)->queryOne();
		$car_transfer or die('record not found');
	
		return $this->render('list1',[
				'transfer_id'=>$id,
				'carTransferInfo'=>$car_transfer
				]);
	}
	
	/**
	 * 获取指定调拨清单列表
	 */
	public function actionGetList1Data()
	{
		$transfer_id = yii::$app->request->get('transfer_id') or die('param transfer_id id required');
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
		$query = CarTransferList::find()
			->select([
				'{{%car_transfer_list}}.*',
				'{{%car_brand}}.name car_brand_name',
				'{{%car_type}}.car_model_name',
				'{{%pre_operating_company}}.name pre_operating_company_name',
				'{{%after_operating_company}}.name after_operating_company_name',	
				'{{%owner}}.name after_owner_name',
				'count({{%car_transfer_details}}.id) details_number'
				])
				->leftJoin('{{%car_brand}}', '{{%car_transfer_list}}.`car_brand_id` = {{%car_brand}}.`id`')
				->leftJoin('{{%car_type}}', '{{%car_transfer_list}}.`car_type_id` = {{%car_type}}.`id`')
				->leftJoin('{{%operating_company}} as {{%pre_operating_company}}', '{{%car_transfer_list}}.`pre_operating_company_id` = {{%pre_operating_company}}.`id`')
				->leftJoin('{{%operating_company}} as {{%after_operating_company}}', '{{%car_transfer_list}}.`after_operating_company_id` = {{%after_operating_company}}.`id`')
				->leftJoin('{{%owner}}', '{{%car_transfer_list}}.`after_owner_id` = {{%owner}}.`id`')
				->leftJoin('{{%car_transfer_details}}', '{{%car_transfer_list}}.`id` = {{%car_transfer_details}}.`transfer_list_id` and {{%car_transfer_details}}.is_del=0')
				->where([
						'{{%car_transfer_list}}.`is_del`'=>0,
						'{{%car_transfer_list}}.`transfer_id`'=>$transfer_id
						])->groupBy('{{%car_transfer_list}}.id');
// 		echo $query->createCommand()->getRawSql();exit;
		
		$data = $query->asArray()->all();
		$total = count($data);
		$returnArr = [];
		$returnArr['rows'] = $data;
		$returnArr['total'] = $total;
		echo json_encode($returnArr);
	}
	
	/**
	 * 新增车辆调拨清单
	 */
	public function actionList1Add(){
		$connection = yii::$app->db;
		if(yii::$app->request->isPost){
			$returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            
			$model = new CarTransferList();
			$post = yii::$app->request->post();
			$model->load($post,'');
			
			$transfer = CarTransfer::findOne(['id'=>$post['transfer_id']]);
			if($transfer['status']){
				$returnArr['status'] = false;
				$returnArr['info'] = '需求已提交，不可修改！';
				return json_encode($returnArr);
			}
	
			if($model->validate()){
				if($model->save(false)){
					$returnArr['status'] = true;
					$returnArr['info'] = '保存成功！';
				}else{
					$returnArr['status'] = false;
					$returnArr['info'] = '保存失败！';
				}
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '数据验证错误！';
			}
			return json_encode($returnArr);
		}
		$transfer_id = yii::$app->request->get('transfer_id') or die('param id is required');
	
		return $this->render('list1-add',
				['transfer_id'=>$transfer_id]
				);
	}
	
	/**
	 * 修改车辆调拨清单
	 */
	public function actionList1Edit(){
		$connection = yii::$app->db;
		if(yii::$app->request->isPost){
			$returnArr = [];
			$returnArr['status'] = true;
			$returnArr['info'] = '';
			
			$id = yii::$app->request->post('id') or die('param id is required');
			$model = CarTransferList::findOne(['id'=>$id]);
			$post = yii::$app->request->post();
			$model->load($post,'');
	
			$transfer = CarTransfer::findOne(['id'=>$model['transfer_id']]);
			if($transfer['status']){
				$returnArr['status'] = false;
				$returnArr['info'] = '需求已提交，不可修改！';
				return json_encode($returnArr);
			}
			
			if($model->validate()){
				if($model->save(false)){
					$returnArr['status'] = true;
					$returnArr['info'] = '保存成功！';
				}else{
					$returnArr['status'] = false;
					$returnArr['info'] = '保存失败！';
				}
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '数据验证错误！';
			}
			return json_encode($returnArr);
		}
		$id = yii::$app->request->get('id') or die('param id is required');
		$model = CarTransferList::findOne(['id'=>$id]);
		if($model){
			$listInfo = $model->getOldAttributes();
		}else{
			$listInfo = [];
		}
		return $this->render('list1-edit',
				[
					'listInfo'=>$listInfo
				]
		);
	}
	
	/**
	 * 车辆调拨清单删除
	 */
	public function actionList1Remove()
	{
		$id = intval(yii::$app->request->get('id')) or die('param id is required');
		
		$transfer_list = CarTransferList::findOne(['id'=>$id]);
		$transfer = CarTransfer::findOne(['id'=>$transfer_list['transfer_id']]);
		if($transfer['status']){
			$returnArr['status'] = false;
			$returnArr['info'] = '需求已提交，不可修改！';
			return json_encode($returnArr);
		}
		
		$returnArr = [];
		if(CarTransferList::updateAll(['is_del'=>1],['id'=>$id])){
			$returnArr['status'] = true;
			$returnArr['info'] = '数据删除成功！';
		}else{
			$returnArr['status'] = false;
			$returnArr['info'] = '数据删除失败！';
		}
		echo json_encode($returnArr);
	}
	
	
	
	//第二步，需求满足
	public function actionIndex2()
	{
		$buttons = $this->getCurrentActionBtn();
		//加载站点end
		return $this->render('index2',[
				'buttons'=>$buttons
				]);
	}
	
	/**
	 * 需求满足列表
	 */
	public function actionGetList2()
	{
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
		$connection = yii::$app->db;
		$query = CarTransfer::find()
		->select([
				'{{%car_transfer}}.*',
				'{{%operating_company}}.name originator_operating_company_name',
				'{{%car_transfer_list}}.pre_operating_company_id',
				])
				->leftJoin('{{%operating_company}}', '{{%car_transfer}}.`originator_operating_company_id` = {{%operating_company}}.`id`')
				->leftJoin('{{%car_transfer_list}}', '{{%car_transfer}}.`id` = {{%car_transfer_list}}.`transfer_id` and {{%car_transfer_list}}.is_del=0')
				->andWhere(['=','{{%car_transfer}}.`is_del`',0]);
		//查询条件
		$query->andFilterWhere(['>','{{%car_transfer}}.status',0]);
		$dd_number = yii::$app->request->get('dd_number');
		if($dd_number){
			$query->andFilterWhere(
					['like','{{%car_transfer}}.dd_number',$dd_number]);
		}
		$status = yii::$app->request->get('status');
		if($status){
			$query->andFilterWhere(
					['=','{{%car_transfer}}.status',$status]);
		}
		if(yii::$app->request->get('start_add_time')){
			$query->andFilterWhere(['>=','{{%car_transfer}}.`add_time`',strtotime(yii::$app->request->get('start_add_time'))]);
		}
		$end_add_time = yii::$app->request->get('end_add_time');
		if($end_add_time){
			$end_add_time = $end_add_time.' 23:59:59';
			$query->andFilterWhere(['<=','{{%car_transfer}}.`add_time`',strtotime($end_add_time)]);
		}
		//检测是否要根据当前登录人员所属运营公司来显示列表数据
		$isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
		if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
			$query->andWhere("{{%car_transfer_list}}.`pre_operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
		}
		//查询条件结束
		//排序开始
		$sortColumn = yii::$app->request->get('sort');
		$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
		$orderBy = '';
		if($sortColumn){
			switch ($sortColumn) {
				default:
					$orderBy = '`'.$sortColumn.'` ';
				break;
			}
		}else{
			$sortType = 'desc';
			$orderBy = '{{%car_transfer}}.`add_time` ';
		}
		$orderBy .= $sortType;
		//排序结束
		
		$total = $query->groupBy('{{%car_transfer}}.`id`')->count();
		
// 		echo $query->createCommand()->getRawSql();exit;
		$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
		$data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
	
		$returnArr = [];
		$returnArr['rows'] = $data;
		$returnArr['total'] = $total;
		echo json_encode($returnArr);
	}
	
	/**
	 * 获取指定调拨清单列表（第二步）
	 */
	public function actionGetList2Data()
	{
		$transfer_id = yii::$app->request->get('transfer_id') or die('param transfer_id id required');
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
		$query = CarTransferList::find()
		->select([
				'{{%car_transfer_list}}.*',
				'{{%car_brand}}.name car_brand_name',
				'{{%car_type}}.car_model_name',
				'{{%pre_operating_company}}.name pre_operating_company_name',
				'{{%after_operating_company}}.name after_operating_company_name',
				'{{%owner}}.name after_owner_name',
				'count({{%car_transfer_details}}.id) details_number'
				])
				->leftJoin('{{%car_brand}}', '{{%car_transfer_list}}.`car_brand_id` = {{%car_brand}}.`id`')
				->leftJoin('{{%car_type}}', '{{%car_transfer_list}}.`car_type_id` = {{%car_type}}.`id`')
				->leftJoin('{{%operating_company}} as {{%pre_operating_company}}', '{{%car_transfer_list}}.`pre_operating_company_id` = {{%pre_operating_company}}.`id`')
				->leftJoin('{{%operating_company}} as {{%after_operating_company}}', '{{%car_transfer_list}}.`after_operating_company_id` = {{%after_operating_company}}.`id`')
				->leftJoin('{{%owner}}', '{{%car_transfer_list}}.`after_owner_id` = {{%owner}}.`id`')
				->leftJoin('{{%car_transfer_details}}', '{{%car_transfer_list}}.`id` = {{%car_transfer_details}}.`transfer_list_id` and {{%car_transfer_details}}.is_del=0')
				->where([
						'{{%car_transfer_list}}.`is_del`'=>0,
						'{{%car_transfer_list}}.`transfer_id`'=>$transfer_id
						]);
		//检测是否要根据当前登录人员所属运营公司来显示列表数据
		$isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
		if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
			$query->andWhere("{{%car_transfer_list}}.`pre_operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
		}
		$query = $query->groupBy('{{%car_transfer_list}}.id');
// 		echo $query->createCommand()->getRawSql();exit;
	
		$data = $query->asArray()->all();
		$total = count($data);
		$returnArr = [];
		$returnArr['rows'] = $data;
		$returnArr['total'] = $total;
		echo json_encode($returnArr);
	}
	
	/**
	 * 需求车辆明细管理 
	 */
	public function actionCarDetails(){
		$connection = yii::$app->db;
		$transfer_list_id = yii::$app->request->get('transfer_list_id') or die('param id is required');
		
		$carTransferListInfo = $connection->createCommand(
				'select a.*,b.dd_number,b.add_time,b.originator,c.name as car_brand_name,d.car_model_name
				from cs_car_transfer_list a
				left join cs_car_transfer b on a.transfer_id=b.id
				left join cs_car_brand c on a.car_brand_id=c.id
				left join cs_car_type d on a.car_type_id=d.id
				where a.id='.$transfer_list_id)->queryOne();
		$carTransferListInfo or die('record not found');
		return $this->render('car-details',[
				'transfer_list_id'=>$transfer_list_id,
				'carTransferListInfo'=>$carTransferListInfo
				]);
	}
	
	/**
	 * 需求车辆明细列表
	 */
	public function actionGetDetailsData(){
		$transfer_list_id = yii::$app->request->get('transfer_list_id');
		$transfer_id = yii::$app->request->get('transfer_id');
		if($transfer_list_id && $transfer_id){
			die('param id required');
		}
		$transfer_list_ids = array();
		if($transfer_id){
			$transferLists = CarTransferList::find()
				->select(['{{%car_transfer_list}}.id'])
				->where([
						'{{%car_transfer_list}}.`is_del`'=>0,
						'{{%car_transfer_list}}.`transfer_id`'=>$transfer_id
						])
				->asArray()->all();
			foreach ($transferLists as $row){
				array_push($transfer_list_ids, $row['id']);
			}
		}else {
			$transfer_list_ids = [$transfer_list_id];
		}
		
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
		$query = CarTransferDetails::find()
		->select([
				'{{%car_transfer_details}}.*',
				'{{%car}}.plate_number',
				'{{%car}}.vehicle_dentification_number',
				'{{%car_brand}}.name car_brand_name',
				'{{%car_type}}.car_model_name'
				])
				->leftJoin('{{%car}}', '{{%car_transfer_details}}.`car_id` = {{%car}}.`id`')
				->leftJoin('{{%car_transfer_list}}', '{{%car_transfer_details}}.`transfer_list_id` = {{%car_transfer_list}}.`id`')
				->leftJoin('{{%car_brand}}', '{{%car_transfer_list}}.`car_brand_id` = {{%car_brand}}.`id`')
				->leftJoin('{{%car_type}}', '{{%car_transfer_list}}.`car_type_id` = {{%car_type}}.`id`')
				->where([
						'{{%car_transfer_details}}.`is_del`'=>0,
						'{{%car_transfer_details}}.`transfer_list_id`'=>$transfer_list_ids
						]);
// 		echo $query->createCommand()->getRawSql();exit;
		$total = $query->count();
		$data = $query->asArray()->all();
		$returnArr = [];
		$returnArr['rows'] = $data;
		$returnArr['total'] = $total;
		echo json_encode($returnArr);
	}
	
	/**
	 * 新增车辆明细
	 */
	public function actionDetailsAdd(){
		$connection = yii::$app->db;
		if(yii::$app->request->isPost){
			$returnArr = [];
			$returnArr['status'] = true;
			$returnArr['info'] = '';
	
			$model = new CarTransferDetails();
			$post = yii::$app->request->post();
			$post['start_time'] = strtotime($post['start_time']);
			$model->load($post,'');
			
			$transferList = CarTransferList::findOne(['id'=>$post['transfer_list_id']]);
			$transfer = CarTransfer::findOne(['id'=>$transferList['transfer_id']]);
			if($transfer['status'] != 1){
				$returnArr['status'] = false;
				$returnArr['info'] = '需求满足已提交，不可修改！';
				return json_encode($returnArr);
			}
			//车辆上限判断
			$carNumber = CarTransferDetails::find()
			->select([
					'count({{%car_transfer_details}}.*) carNumber'
					])
					->where([
							'{{%car_transfer_details}}.`transfer_list_id`'=>$post['transfer_list_id'],
							'{{%car_transfer_details}}.`is_del`'=>0
							])->count();
			if($carNumber >= $transferList['number']){
				$returnArr['status'] = false;
				$returnArr['info'] = '车型车辆上限！';
				return json_encode($returnArr);
			}
	
			if($model->validate()){
				if($model->save(false)){
					$returnArr['status'] = true;
					$returnArr['info'] = '保存成功！';
				}else{
					$returnArr['status'] = false;
					$returnArr['info'] = '保存失败！';
				}
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '数据验证错误！';
			}
			return json_encode($returnArr);
		}
		$transfer_list_id = yii::$app->request->get('transfer_list_id') or die('param id is required');
		$carTransferListInfo = $connection->createCommand(
				'select a.*,c.name as car_brand_name,d.car_model_name
				from cs_car_transfer_list a
				left join cs_car_brand c on a.car_brand_id=c.id
				left join cs_car_type d on a.car_type_id=d.id
				where a.id='.$transfer_list_id)->queryOne();
		$carTransferListInfo or die('record not found');
		
		
		return $this->render('details-add',
				[
					'transfer_list_id'=>$transfer_list_id,
					'carTransferListInfo'=>$carTransferListInfo
				]
		);
	}
	
	/**
	 * 修改车辆明细
	 */
	public function actionDetailsEdit(){
		$connection = yii::$app->db;
		if(yii::$app->request->isPost){
			$returnArr = [];
			$returnArr['status'] = true;
			$returnArr['info'] = '';
	
			$id = yii::$app->request->post('id') or die('param id is required');
			$model = CarTransferDetails::findOne(['id'=>$id]);
			$post = yii::$app->request->post();
			$post['start_time'] = strtotime($post['start_time']);
			if(!is_numeric($post['car_id'])){
				unset($post['car_id']);
			}
			$model->load($post,'');
			
			$transferList = CarTransferList::findOne(['id'=>$model->transfer_list_id]);
			$transfer = CarTransfer::findOne(['id'=>$transferList['transfer_id']]);
			if($transfer['status'] != 1){
				$returnArr['status'] = false;
				$returnArr['info'] = '需求满足已提交，不可修改！';
				return json_encode($returnArr);
			}
	
			if($model->validate()){
				if($model->save(false)){
					$returnArr['status'] = true;
					$returnArr['info'] = '保存成功！';
				}else{
					$returnArr['status'] = false;
					$returnArr['info'] = '保存失败！';
				}
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '数据验证错误！';
			}
			return json_encode($returnArr);
		}
		$id = yii::$app->request->get('id') or die('param id is required');
		$model = CarTransferDetails::findOne(['id'=>$id]);
		if($model){
			$detailsInfo = $model->getOldAttributes();
			$carTransferListInfo = $connection->createCommand(
					'select a.id,c.name as car_brand_name,d.car_model_name
					from cs_car_transfer_list a
					left join cs_car_brand c on a.car_brand_id=c.id
					left join cs_car_type d on a.car_type_id=d.id
					where a.id='.$detailsInfo['transfer_list_id'])->queryOne();
			$car = Car::findOne(['id'=>$model['car_id']]);
			$detailsInfo['car_brand_name'] = $carTransferListInfo['car_brand_name'];
			$detailsInfo['car_model_name'] = $carTransferListInfo['car_model_name'];
			$detailsInfo['plate_number'] = $car['plate_number'];
		}else{
			$detailsInfo = [];
		}
		return $this->render('details-edit',
				[
				'detailsInfo'=>$detailsInfo
				]
		);
	}
	
	/**
	 * 车辆明细删除
	 */
	public function actionDetailsRemove()
	{
		$id = intval(yii::$app->request->get('id')) or die('param id is required');
	
		$transfer_details = CarTransferDetails::findOne(['id'=>$id]);
		$transfer_list = CarTransferList::findOne(['id'=>$transfer_details['transfer_list_id']]);
		$transfer = CarTransfer::findOne(['id'=>$transfer_list['transfer_id']]);
		if($transfer['status'] != 1){
			$returnArr['status'] = false;
			$returnArr['info'] = '需求满足已提交，不可修改！';
			return json_encode($returnArr);
		}
	
		$returnArr = [];
		if(CarTransferDetails::updateAll(['is_del'=>1],['id'=>$id])){
			$returnArr['status'] = true;
			$returnArr['info'] = '数据删除成功！';
		}else{
			$returnArr['status'] = false;
			$returnArr['info'] = '数据删除失败！';
		}
		echo json_encode($returnArr);
	}
	
	//第三步，调拨到车确认
	public function actionIndex3()
	{
		$buttons = $this->getCurrentActionBtn();
		//加载站点end
		return $this->render('index3',[
				'buttons'=>$buttons
				]);
	}
	
	/**
	 * 到车确认列表
	 */
	public function actionGetList3()
	{
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
		$connection = yii::$app->db;
		$query = CarTransfer::find()
		->select([
				'{{%car_transfer}}.*',
				'{{%operating_company}}.name originator_operating_company_name',
				'{{%car_transfer_list}}.after_operating_company_id',
				])
				->leftJoin('{{%operating_company}}', '{{%car_transfer}}.`originator_operating_company_id` = {{%operating_company}}.`id`')
				->leftJoin('{{%car_transfer_list}}', '{{%car_transfer}}.`id` = {{%car_transfer_list}}.`transfer_id` and {{%car_transfer_list}}.is_del=0')
				->andWhere(['=','{{%car_transfer}}.`is_del`',0]);
		//查询条件
		$query->andFilterWhere(['>','{{%car_transfer}}.status',1]);
		$dd_number = yii::$app->request->get('dd_number');
		if($dd_number){
			$query->andFilterWhere(
					['like','{{%car_transfer}}.dd_number',$dd_number]);
		}
		$status = yii::$app->request->get('status');
		if($status){
			$query->andFilterWhere(
					['=','{{%car_transfer}}.status',$status]);
		}
		if(yii::$app->request->get('start_add_time')){
			$query->andFilterWhere(['>=','{{%car_transfer}}.`add_time`',strtotime(yii::$app->request->get('start_add_time'))]);
		}
		$end_add_time = yii::$app->request->get('end_add_time');
		if($end_add_time){
			$end_add_time = $end_add_time.' 23:59:59';
			$query->andFilterWhere(['<=','{{%car_transfer}}.`add_time`',strtotime($end_add_time)]);
		}
		//检测是否要根据当前登录人员所属运营公司来显示列表数据
		$isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
		if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
			$query->andWhere("{{%car_transfer_list}}.`after_operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
		}
		//查询条件结束
		//排序开始
		$sortColumn = yii::$app->request->get('sort');
		$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
		$orderBy = '';
		if($sortColumn){
			switch ($sortColumn) {
				default:
					$orderBy = '`'.$sortColumn.'` ';
				break;
			}
		}else{
			$sortType = 'desc';
			$orderBy = '{{%car_transfer}}.`add_time` ';
		}
		$orderBy .= $sortType;
		
// 		echo $query->createCommand()->getRawSql();exit;
		//排序结束
		$total = $query->groupBy('{{%car_transfer}}.`id`')->count();
		$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
		$data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
	
		$returnArr = [];
		$returnArr['rows'] = $data;
		$returnArr['total'] = $total;
		echo json_encode($returnArr);
	}
	
	/**
	 * 需求车辆明细列表（第三步）
	 */
	public function actionGetDetailsData3(){
		$transfer_list_id = yii::$app->request->get('transfer_list_id');
		$transfer_id = yii::$app->request->get('transfer_id');
		if($transfer_list_id && $transfer_id){
			die('param id required');
		}
		$transfer_list_ids = array();
		if($transfer_id){
			$transferLists = CarTransferList::find()
			->select(['{{%car_transfer_list}}.id'])
			->where([
					'{{%car_transfer_list}}.`is_del`'=>0,
					'{{%car_transfer_list}}.`transfer_id`'=>$transfer_id
					])
					->asArray()->all();
			foreach ($transferLists as $row){
				array_push($transfer_list_ids, $row['id']);
			}
		}else {
			$transfer_list_ids = [$transfer_list_id];
		}
	
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
		$query = CarTransferDetails::find()
		->select([
				'{{%car_transfer_details}}.*',
				'{{%car}}.plate_number',
				'{{%car}}.vehicle_dentification_number',
				'{{%car_brand}}.name car_brand_name',
				'{{%car_type}}.car_model_name'
				])
				->leftJoin('{{%car}}', '{{%car_transfer_details}}.`car_id` = {{%car}}.`id`')
				->leftJoin('{{%car_transfer_list}}', '{{%car_transfer_details}}.`transfer_list_id` = {{%car_transfer_list}}.`id`')
				->leftJoin('{{%car_brand}}', '{{%car_transfer_list}}.`car_brand_id` = {{%car_brand}}.`id`')
				->leftJoin('{{%car_type}}', '{{%car_transfer_list}}.`car_type_id` = {{%car_type}}.`id`')
				->where([
						'{{%car_transfer_details}}.`is_del`'=>0,
						'{{%car_transfer_details}}.`transfer_list_id`'=>$transfer_list_ids
						]);
		$isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
		if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
			$query->andWhere("{{%car_transfer_list}}.`after_operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
		}
// 				echo $query->createCommand()->getRawSql();exit;
		$total = $query->count();
		$data = $query->asArray()->all();
		$returnArr = [];
		$returnArr['rows'] = $data;
		$returnArr['total'] = $total;
		echo json_encode($returnArr);
	}
	
	
	/**
	 * 车辆提交
	 */
	public function actionCarSubmit(){
		if(yii::$app->request->isPost){
			$id = yii::$app->request->post('id');
			$end_time = yii::$app->request->post('end_time');		//实际到车时间
			$credentials_status = yii::$app->request->post('credentials_status');	//证件是否齐全
			$abnormal_note = yii::$app->request->post('abnormal_note');	//车辆异常情况
			if(!$end_time || !$credentials_status){
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败，缺少阐述！';
				exit(json_encode($returnArr));
			}
			$end_time = strtotime($end_time);
			
			//1.查找车辆调入运营公司
			$query = CarTransferDetails::find()
				->select([
						'{{%car_transfer_details}}.car_id',
						'{{%car_transfer_list}}.after_operating_company_id',
						'{{%car_transfer}}.id transfer_id',
						'{{%car_transfer}}.car_number',
						'{{%car_transfer}}.car_ok_number'
						])
				->leftJoin('{{%car_transfer_list}}', '{{%car_transfer_details}}.`transfer_list_id` = {{%car_transfer_list}}.`id`')
				->leftJoin('{{%car_transfer}}', '{{%car_transfer_list}}.`transfer_id` = {{%car_transfer}}.`id`')
				->where([
						'{{%car_transfer_details}}.`id`'=>$id,
						'{{%car_transfer_details}}.`is_del`'=>0
						]);
	// 				echo $query->createCommand()->getRawSql();exit;
			$details = $query->asArray()->one();
	
			//2.车辆一级状态变更
			$connection = yii::$app->db;
			$transaction = $connection->beginTransaction();
	
			$result = CarTransferDetails::updateAll(
					[
						'is_confirm'=>1,
						'end_time'=>$end_time,
						'credentials_status'=>$credentials_status,
						'abnormal_note'=>$abnormal_note
					],
					['id'=>$id]
				);
			$result1 = CarTransfer::updateAllCounters(['car_ok_number'=>1],'id=:id',[':id'=>$details['transfer_id']]);
			if($details['car_number'] == $details['car_ok_number']+1){
				CarTransfer::updateAll(['status'=>3],'id=:id',[':id'=>$details['transfer_id']]);
			}
			$result2 = Car::updateAll(['operating_company_id'=>$details['after_operating_company_id']],['id'=>$details['car_id']]);
			$statusRet = Car::changeCarStatusNew($details['car_id'], 'STOCK', 'process/car-transfer/to-next-status', '调拨流程',['car_status'=>'TRANSFER','is_del'=>0]);
			
			if($result1 && ($statusRet?$statusRet['status']:true)){
				$transaction->commit();  //提交事务
				// 				$transaction->rollback(); //回滚事务
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！';
				exit(json_encode($returnArr));
			}else {
				$transaction->rollback(); //回滚事务
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败，请确认车辆当前状态！';
				exit(json_encode($returnArr));
			}
		}
		$id = yii::$app->request->get('id') or die('param id is required');
		
		$query = CarTransferDetails::find()
		->select([
				'{{%car_transfer_details}}.*',
				'{{%car}}.plate_number',
				'{{%car}}.vehicle_dentification_number',
				'{{%car_brand}}.name car_brand_name',
				'{{%car_type}}.car_model_name',
				'{{%car_transfer_list}}.pre_operating_company_id',
				'{{%car_transfer_list}}.after_operating_company_id',
				'{{%pre_operating_company}}.name pre_operating_company_name',
				'{{%after_operating_company}}.name after_operating_company_name'
				])
				->leftJoin('{{%car}}', '{{%car_transfer_details}}.`car_id` = {{%car}}.`id`')
				->leftJoin('{{%car_transfer_list}}', '{{%car_transfer_details}}.`transfer_list_id` = {{%car_transfer_list}}.`id`')
				->leftJoin('{{%car_brand}}', '{{%car_transfer_list}}.`car_brand_id` = {{%car_brand}}.`id`')
				->leftJoin('{{%car_type}}', '{{%car_transfer_list}}.`car_type_id` = {{%car_type}}.`id`')
				->leftJoin('{{%operating_company}} as {{%pre_operating_company}}', '{{%car_transfer_list}}.`pre_operating_company_id` = {{%pre_operating_company}}.`id`')
				->leftJoin('{{%operating_company}} as {{%after_operating_company}}', '{{%car_transfer_list}}.`after_operating_company_id` = {{%after_operating_company}}.`id`')
				->where([
						'{{%car_transfer_details}}.`is_del`'=>0,
						'{{%car_transfer_details}}.`id`'=>$id
						]);
		$transferDetailsInfo = $query->asArray()->one();
		
		return $this->render('car-submit',[
				'transferDetailsInfo'=>$transferDetailsInfo
				]);
	}
}