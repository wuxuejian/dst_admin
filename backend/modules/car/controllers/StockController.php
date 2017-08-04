<?php
/**
 * 自用备用车辆管理控制器
 * @author pengyl
 */
namespace backend\modules\car\controllers;
use backend\controllers\BaseController;
use backend\models\ConfigCategory;
use yii;
use yii\data\Pagination;
use backend\models\CarStock;
use backend\models\Department;
use yii\db\Query;
use backend\models\Car;
use backend\models\CustomerCompany;

class StockController extends BaseController
{
    public function actionIndex()
    {		
        //获取本页按钮
        $buttons = $this->getCurrentActionBtn();
        
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
        return $this->render('index',[
            'buttons'=>$buttons,
            'searchFormOptions'=>$searchFormOptions
        ]);
    }
    
    /**
     * 添加车辆
     */
    public function actionAdd(){
        //echo 'mh';exit;
    	$type = yii::$app->request->get('car_type');
    	if(yii::$app->request->isPost){
    		$type = yii::$app->request->post('car_type');
    		$car_id = yii::$app->request->post('car_id');
            $operating_company_id = yii::$app->request->post('operating_company_id');
            //var_dump($operating_company_id);exit;
    		//检查车辆状态
    		$car = Car::find()->where(['id'=>$car_id])->asArray()->one();
    		if(!$car || $car['car_status'] != 'STOCK'){
    			$returnArr['status'] = false;
    			$returnArr['info'] = '车辆无效！';
    			return json_encode($returnArr);
    		}
    		//添加车辆
    		$model = new CarStock();
    		$model->load(yii::$app->request->post(),'');
    		$returnArr = [];
    		if($model->validate()){
    			$model->add_aid = $_SESSION['backend']['adminInfo']['id'];
    			$model->add_time = date('Y-m-d H:i:s');
    			
    			//车辆状态变更条件验证
    			$statusCheckRet = Car::changeCarStatusNewCheck($car_id, 'DSTCAR', ['car_status'=>'STOCK']);
    			if(!$statusCheckRet['status']){
    				$returnArr['status'] = false;
    				$returnArr['info'] = '车辆状态变更条件验证失败！';
    				return json_encode($returnArr); 
    			}
    			
    			if((new Query())->from('cs_car_stock')->andWhere(['car_id'=>$car_id,'is_del'=>0])->count()>0){
    				$returnArr['status'] = false;
    				$returnArr['info'] = '车辆已存在！';
    			}else if($model->save(false)){
    				//更改车辆状态
    				$connection = yii::$app->db;
    				
    				$statusRet = Car::changeCarStatusNew($car_id, 'DSTCAR', 'car/stock/add', '添加自用备用车'.$type,['car_status'=>'STOCK']);
    				if($statusRet['status']){
    					$returnArr['status'] = true;
    					$returnArr['info'] = '添加车辆成功！';
    				}else {
    					$returnArr['status'] = false;
    					$returnArr['info'] = '添加车辆成功，状态更改失败！'.$statusRet['info'];
    				}
    			}else{
    				$returnArr['status'] = false;
    				$returnArr['info'] = '添加车辆失败！';
    			}
    		}else{
    			$returnArr['status'] = false;
    			$error = $model->getErrors();
    			if($error){
    				$returnArr['info'] = join('',array_column($error,0));
    			}else{
    				$returnArr['info'] = '未知错误';
    			}
    		}
    		return json_encode($returnArr);
    	}else{
    		if($type==1){	//添加自用车
    			$department = Department::find()
    			->select(['id','name'])
    			->where(['is_del'=>0])
    			->asArray()
    			->all();
    			return $this->render('add-dst',[
    					'department'=>$department
    					]);
    		}else if($type==2){	//添加备用车
    			return $this->render('add-backup',[]);
    		}
    	}
    }
    
    /**
     * 归还车辆
     */
    public function actionGiveBack(){
    	$id = intval(yii::$app->request->get('id')) or die('param id is required');
    	$returnArr = [];
    	$carStock = CarStock::findOne(['id'=>$id]);
    	if(CarStock::updateAll(['car_status'=>2,'c_customer_id'=>0],['id'=>$id])){
    		$car_stock = CarStock::find()->where(['id'=>$id])->asArray()->one();
    		$car_id = $car_stock['car_id'];
    		//更改车辆状态
    		$connection = yii::$app->db;
    		$sql = "update cs_car set car_status2='' where id={$car_id}";
    		$connection->createCommand($sql)->execute();
    		//更新替换车记录
    		$real_end_time = date('Y-m-d H:i:s');
    		$sql = "update cs_car_stock_replace_log set real_end_time='{$real_end_time}' where car_id={$car_id} and real_end_time is null";
    		$connection->createCommand($sql)->execute();
    		
    		//车辆状态变更记录
    		
    		$returnArr['status'] = true;
    		$returnArr['info'] = '车辆归还成功！';
    	}else{
    		$returnArr['status'] = false;
    		$returnArr['info'] = '车辆归还失败！';
    	}
    	echo json_encode($returnArr);
    }
    
    /**
     * 删除车辆
     */
    public function actionRemove()
    {
    	$id = intval(yii::$app->request->get('id')) or die('param id is required');
    	$returnArr = [];
    	if(CarStock::updateAll(['is_del'=>1],['id'=>$id,'car_status'=>2])){
    		$car_stock = CarStock::find()->where(['id'=>$id])->asArray()->one();
    		$car_id = $car_stock['car_id'];
    		
    		//车辆状态变更条件验证
    		$statusCheckRet = Car::changeCarStatusNewCheck($car_id, 'STOCK', ['car_status'=>'DSTCAR']);
    		if(!$statusCheckRet['status']){
    			$returnArr['status'] = false;
    			$returnArr['info'] = '车辆状态变更条件验证失败！';
    			return json_encode($returnArr);
    		}
    		
    		//更改车辆状态
    		$statusRet = Car::changeCarStatusNew($car_id, 'STOCK', 'car/stock/remove', '删除自用备用车'.$car_stock['car_type'],['car_status'=>'DSTCAR']);
    		if($statusRet['status']){
    			$returnArr['status'] = true;
    			$returnArr['info'] = '车辆删除成功！';
    		}else {
    			$returnArr['status'] = false;
    			$returnArr['info'] = '车辆删除失败，状态更改失败！'.$statusRet['info'];
    		}
    		
    		$returnArr['status'] = true;
    		$returnArr['info'] = '车辆删除成功！';
    	}else{
    		$returnArr['status'] = false;
    		$returnArr['info'] = '车辆删除失败！';
    	}
    	echo json_encode($returnArr);
    }
    
    /**
     * 替换车辆
     */
    public function actionReplace(){
    	if(yii::$app->request->isPost){
    		$id = intval(yii::$app->request->post('id')) or die('param id is required');
    		//提交参数验证
    		$replace_car_id = yii::$app->request->post('replace_car_id');
    		$replace_start_time = yii::$app->request->post('replace_start_time');
    		$replace_end_time = yii::$app->request->post('replace_end_time');
    		$replace_desc = yii::$app->request->post('replace_desc');
    		if(!$replace_car_id || !$replace_start_time || !$replace_end_time || !$replace_desc){
    			$returnArr['status'] = false;
    			$returnArr['info'] = '缺少参数！';
    			return json_encode($returnArr);
    		}
    		//检查替换车辆状态
    		$car_stock = CarStock::find()->where(['id'=>$id])->asArray()->one();
    		$car_id = $car_stock['car_id'];
    		if($car_stock['car_status'] == 1){
    			$returnArr['status'] = false;
    			$returnArr['info'] = '替换车辆无效！';
    			return json_encode($returnArr);
    		}
    		$connection = yii::$app->db;
    		//获取被替换车所属企业客户
    		$let_record = $connection->createCommand(
    				"select cCustomer_id from cs_car_let_record where car_id={$replace_car_id} and back_time=0 and is_del=0 limit 1"
    			)->queryOne();
    		if(!$let_record){
    			$let_record = $connection->createCommand(
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
    		//开启事务替换
    		$transaction = $connection->beginTransaction();
    		try {
    			//1.更新自用备用车状态
    			$sql1 = "update cs_car_stock set car_status=1,c_customer_id={$c_customer_id} where id={$id}";
    			//2.更新车辆状态
    			$sql2 = "update cs_car set car_status2='REPLACE' where id={$car_id}";
    			$connection->createCommand($sql1)->execute();
    			$connection->createCommand($sql2)->execute();
    			//3.替换记录
    			$connection->createCommand()->insert('cs_car_stock_replace_log', [
    					'car_stock_id' => $id,
    					'car_id' => $car_id,
    					'c_customer_id' => $c_customer_id,
    					'replace_car_id' => $replace_car_id,
    					'replace_desc' => $replace_desc,
    					'replace_start_time' => $replace_start_time,
    					'replace_end_time' => $replace_end_time,
    					'add_aid' => $_SESSION['backend']['adminInfo']['id'],
    					'add_time' => date('Y-m-d H:i:s')
    					])->execute();
    			//车辆状态变更记录
//     			$cCustomerModel = CustomerCompany::findOne(['id'=>$c_customer_id]);
//     			$replace_car = Car::findOne(['id'=>$replace_car_id]);
//     			$connection->createCommand()->insert('cs_car_status_change_log', [
//     					'car_id' => $car_id,
//     					'add_time' => date('Y-m-d H:i:s'),
//     					'car_status' => 'REPLACE',
//     					'note' => "此车从{$replace_start_time}到{$replace_end_time}替换{$cCustomerModel->company_name}租赁的车辆：{$replace_car->plate_number}"
//     					])->execute();
    			$transaction->commit();
    			$returnArr['status'] = true;
    			$returnArr['info'] = '替换成功！';
    			return json_encode($returnArr);
    		} catch(Exception $e) {
    			$transaction->rollBack();
    		}
    		$returnArr['status'] = false;
    		$returnArr['info'] = '替换失败！';
    		return json_encode($returnArr);
    	}else{
    		$id = intval(yii::$app->request->get('id')) or die('param id is required');
    		$car_stock = CarStock::find()->where(['id'=>$id])->asArray()->one();
    		$car_id = $car_stock['car_id'];
    		$car = Car::find()->where(['id'=>$car_id])->asArray()->one();
			return $this->render('replace',['car'=>$car,'id'=>$id]);
    	}
    }
    
    /**
     * 获取车辆 （添加自用、备用车窗口的combogrid）
     */
    public function actionGetCarsByAdd()
    {
    	$queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
    	$carId = isset($_REQUEST['car_id']) ? intval($_REQUEST['car_id']) : 0; //修改时赋值用
    	$operating_company_id = isset($_REQUEST['operating_company_id']) ? intval($_REQUEST['operating_company_id']) : 0;
    	$query = Car::find()
    	->select(['id','plate_number','vehicle_dentification_number'])
    	->where(['is_del'=>0,'car_status'=>'STOCK']);
    	if($operating_company_id){
    		$query->andWhere(['operating_company_id'=>$operating_company_id]);
    	}
    	if($carId){
    		// 修改时查询赋值
    		$total = $query->andWhere(['id'=>$carId])->count();
    	}elseif($queryStr){
    		// 检索过滤时
    		$total = $query->andWhere([
    				'or',
    				['like', 'plate_number', $queryStr],
    				['like', 'vehicle_dentification_number', $queryStr]
    				])
    				->count();
    	}else{
    		// 默认查询
    		$total = $query->count();
    	}
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    	$pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
    	$data = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
    	$returnArr = [];
    	$returnArr['rows'] = $data;
    	$returnArr['total'] = $total;
    	return json_encode($returnArr);
    }
    
    /**
     * 获取车辆 （替换车辆窗口的combogrid）
     */
    public function actionGetCarsByReplace()
    {
    	$queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
    	$carId = isset($_REQUEST['car_id']) ? intval($_REQUEST['car_id']) : 0; //修改时赋值用
    	$query = Car::find()
    	->select(['id','plate_number','vehicle_dentification_number'])
    	->where(['is_del'=>0]);
    	if($carId){
    		// 修改时查询赋值
    		$total = $query->andWhere(['id'=>$carId])->count();
    	}elseif($queryStr){
    		// 检索过滤时
    		$total = $query->andWhere([
    				'or',
    				['like', 'plate_number', $queryStr],
    				['like', 'vehicle_dentification_number', $queryStr]
    				])
    				->count();
    	}else{
    		// 默认查询
    		$total = $query->count();
    	}
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    	$pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
    	$data = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
    	$returnArr = [];
    	$returnArr['rows'] = $data;
    	$returnArr['total'] = $total;
    	return json_encode($returnArr);
    }
    
    /**
     * 获取车辆列表
     */
    public function actionGetList()
    {
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    	$plate_number = yii::$app->request->get('plate_number');
    	$car_type = yii::$app->request->get('car_type');
    	$car_status = yii::$app->request->get('car_status');
    	$operating_company_id = yii::$app->request->get('operating_company_id');
    
    	$query = (new \yii\db\Query())->select([
    			'a.*',
    			'b.company_name',
    			'c.name department_name',
    			'd.username',
    			'e.plate_number'
    			])->from('cs_car_stock a')
    			->leftJoin('cs_customer_company b', 'a.c_customer_id = b.id')
    			->leftJoin('cs_department c', 'a.department_id = c.id')
    			->leftJoin('cs_admin d', 'a.add_aid = d.id')
    			->leftJoin('cs_car e', 'a.car_id = e.id')
    			->andWhere(['a.is_del'=>0]);
    	//查询条件
    	if($operating_company_id){
    		$query->andFilterWhere(['=','e.`operating_company_id`',$operating_company_id]);
    	}
    	if($plate_number){
    		$query->andFilterWhere([
    				'like',
    				'e.plate_number',
    				$plate_number
    				]);
    	}
    	if($car_type){
    		$query->andFilterWhere([
    				'=',
    				'a.car_type',
    				$car_type
    				]);
    	}
    	if($car_status){
    		$query->andFilterWhere([
    				'=',
    				'a.car_status',
    				$car_status
    				]);
    	}
    	$sortColumn = yii::$app->request->get('sort');
    	$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
    	$orderBy = '';
    	if($sortColumn){
    		switch ($sortColumn) {
    			case 'plate_number':
    				$orderBy = 'e.`'.$sortColumn.'` ';
    				break;
    			case 'car_type':
    				$orderBy = 'a.`car_type` ';
    				break;
    			case 'car_status':
    				$orderBy = 'a.`car_status` ';
    				break;
    			case 'department_name':
    				$orderBy = 'c.`name` ';
    				break;
    			case 'company_name':
    				$orderBy = 'b.`company_name` ';
    				break;
    			case 'username':
    				$orderBy = 'd.`username` ';
    				break;
    			default:
    				$orderBy = 'a.`'.$sortColumn.'` ';
    			break;
    		}
    	}else{
    		$orderBy = 'a.`id` ';
    	}
    	$orderBy .= $sortType;
    	$total = $query->count();
    	$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
    	$data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->all();
    	$returnArr = [];
    	$returnArr['rows'] = $data;
    	$returnArr['total'] = $total;
    
    	//     	exit($query->createCommand()->sql);
    	return json_encode($returnArr);
    }
    
    /**
     * 按条件导出车辆列表
     */
    public function actionExportWidthCondition()
    {
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    	$plate_number = yii::$app->request->get('plate_number');
    	$car_type = yii::$app->request->get('car_type');
    	$car_status = yii::$app->request->get('car_status');
    
    	$query = (new \yii\db\Query())->select([
    			'a.*',
    			'b.company_name',
    			'c.name department_name',
    			'd.username',
    			'e.plate_number'
    			])->from('cs_car_stock a')
    			->leftJoin('cs_customer_company b', 'a.c_customer_id = b.id')
    			->leftJoin('cs_department c', 'a.department_id = c.id')
    			->leftJoin('cs_admin d', 'a.add_aid = d.id')
    			->leftJoin('cs_car e', 'a.car_id = e.id')
    			->andWhere(['a.is_del'=>0]);
    	//查询条件
    	if($plate_number){
    		$query->andFilterWhere([
    				'like',
    				'e.plate_number',
    				$plate_number
    				]);
    	}
    	if($car_type){
    		$query->andFilterWhere([
    				'=',
    				'a.car_type',
    				$car_type
    				]);
    	}
    	if($car_status){
    		$query->andFilterWhere([
    				'=',
    				'a.car_status',
    				$car_status
    				]);
    	}
    	$data = $query->all();
    	$filename = '自用备用车列表.csv'; //设置文件名
    	$str = "车牌号,车辆类型,替换状态,用车部门,客户,操作人,操作时间\n";
    	$car_type_arr = array(1=>'自用车',2=>'备用车');
    	$car_status_arr = array(1=>'已替换',2=>'未替换');
    	foreach ($data as $row){
    		$str .= "{$row['plate_number']},{$car_type_arr[$row['car_type']]},{$car_status_arr[$row['car_status']]},{$row['department_name']},{$row['company_name']},{$row['username']},{$row['add_time']}"."\n";
    	}
    	$str = mb_convert_encoding($str, "GBK", "UTF-8");
    	$this->export_csv($filename,$str); //导出
    }
    
    function export_csv($filename,$data)
    {
    	//		header("Content-type: text/html; charset=utf-8");
    	header("Content-type:text/csv;charset=GBK");
    	header("Content-Disposition:attachment;filename=".$filename);
    	header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    	header('Expires:0');
    	header('Pragma:public');
    	echo $data;
    }
}