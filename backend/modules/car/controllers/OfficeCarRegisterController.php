<?php
/**
 * 公务车派车登记控制器
 * @author 2.20
 */
namespace backend\modules\car\controllers;
use backend\controllers\BaseController;
use backend\models\Car;
use backend\models\ConfigCategory;
use backend\models\CarFault;
use backend\models\CarDrivingLicense;
use backend\models\CarRoadTransportCertificate;
use backend\models\CarSecondMaintenance;
use backend\models\CarInsuranceCompulsory;
use backend\models\CarInsuranceBusiness;
use backend\models\CarBrand;
use backend\models\Owner;
use backend\models\OperatingCompany;
use backend\models\Battery;
use backend\models\Motor;
use backend\models\MotorMonitor;
use backend\classes\UserLog;
use common\models\Excel;
use common\models\File;
use yii;
use yii\base\Object;
use yii\data\Pagination;
use backend\models\Admin;
use backend\models\CarStock;
use yii\db\Query;
use backend\models\Department;
use backend\models\CarOfficeRegister;

class OfficeCarRegisterController extends BaseController
{

	public function actionIndex()
	{	
		//echo '111';exit;
		$buttons = $this->getCurrentActionBtn();
		//var_dump($buttons);exit; 
		//echo '111';exit;
		return $this->render('index',[
            'buttons'=>$buttons,
            //'config'=>$config,
            //'searchFormOptions'=>$searchFormOptions,
        ]);
	}

	//列表显示
	public function actionGetList()
	{

		//echo '222';exit;
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    	$plate_number = yii::$app->request->get('plate_number');
    	$car_type = yii::$app->request->get('car_type');
    	$status = yii::$app->request->get('status');
    	$username = yii::$app->request->get('username');
    	//获取配置数据
        $configItems = ['car_model_name'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
        //echo '<pre>';
        //$car['car_model_name'] = $config['car_model_name'][$car['car_model']]['text'];
        //var_dump($config['car_model_name']);exit;
        //var_dump($username);exit;
      	
    	$query = (new \yii\db\Query())->select([
    			'a.*',
    			'a.address',
    			'a.start_time',
    			'd.name username',
    			'c.name department_name',
    			'e.plate_number',
    			'e.car_model',
    			'f.name car_brand',
    			'd2.name reg_name',

    			])->from('cs_car_office_register a')
    			//->leftJoin('cs_customer_company b', 'a.c_customer_id = b.id')
    			->leftJoin('cs_department c', 'a.department_id = c.id')
    			->leftJoin('cs_admin d', 'a.username_id = d.id')
    			->leftJoin('cs_admin d2', 'a.add_id = d2.id')
    			->leftJoin('cs_car e', 'a.car_id = e.id')
    			->leftJoin('cs_car_brand f','e.brand_id = f.id')
    			//->andWhere(['a.is_del'=>0])
    			;
    	$query->andFilterWhere(['<>','a.`is_office`',2]);
        $query->andFilterWhere(['=','a.`is_del`',0]);
    	
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
    	if($status){
    		$query->andFilterWhere([
    				'=',
    				'a.status',
    				$status
    				]);
    	}
    	if($username){
    		$query->andFilterWhere([
    				'like',
    				'd.name',
    				$username
    				]);
    	}
    	/*if($car_model){
    		$query->andFilterWhere([
    				'=',
    				'a.car_model',
    				$car_model
    				]);
    	}*/
    	//echo 'h2';exit;
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("e.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }

    	$sortColumn = yii::$app->request->get('sort');
    	$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
    	$orderBy = '';
        //var_dump($sortColumn);exit;
        //echo 'h3';exit;
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
    	//echo 'h4';exit;
    	$orderBy .= $sortType;
    	$total = $query->count();
    	//var_dump($total);exit;
    	$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
    	//$data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->all();
    	//$data = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
    	$data = $query->offset($pages->offset)->limit($pages->limit)->all();
    	//echo '<pre>';
    	//var_dump($data);exit;
    	//var_dump($data[5]['total_distance']);exit;
    	//$i = 0;
    	foreach ($data as $key => $car_mod) {
    		//echo $i;
    		//echo '<pre>';
    		//var_dump($key);exit;
    		//1、计算用车距离
    		$total_distance = $car_mod['total_distance'];
    		$return_distance = $car_mod['return_distance'];
    		$use_distance = $return_distance-$total_distance;
    		//var_dump($use_distance);exit;
    		$data[$key]['use_distance']=$use_distance;
    		//2、计算用车时间
    		$return_time = $car_mod['return_time'];
    		$start_time = $car_mod['start_time'];
    		//var_dump($start_time);exit;
    		//$use_time = date('Y-m-d H:i:s',$return_time-$start_time);
    		//$use_time = date('h',$return_time-$start_time);
    		$use_time = strtotime($return_time-$start_time)/3600;
    		$use_time = round((date('s',$use_time)+date('m',$use_time)*60+date('h',$use_time)*60*60)/3600,1);
    		$data[$key]['use_time']=$use_time;
    		//var_dump($start_time);exit;

    		//3、车辆类型
    		$car_m = $car_mod['car_model'];
    		$data[$key]['car_model'] = $config['car_model_name'][$car_m]['text'];
            //echo '<pre>';
    		//var_dump($car_mod);exit;
            //var_dump($data[$key]['is_return']);exit;
            if($data[$key]['is_return'] == 1 && $data[$key]['remain_distance_return'] == 0 ){
                $data[$key]['remain_distance'] = 0;
            }
            if($data[$key]['is_return'] == 1 && $data[$key]['remain_distance_return'] != 0 ){
                $data[$key]['remain_distance'] = $data[$key]['remain_distance_return'];
            }
            //还车后，用车人 用车部门 登记人为空
             if($data[$key]['is_return'] == 1) {
                $data[$key]['username'] = '';
                $data[$key]['department_name'] = '';
                $data[$key]['reg_name'] = '';
            }
    	}
    	//echo '<pre>';
    	//var_dump($data[$key]['car_model']);exit;
    	//var_dump($data);exit;
  
    	$returnArr = [];
    	$returnArr['rows'] = $data;
    	//echo '<pre>';
    	//var_dump($returnArr['rows']);exit;
    	$returnArr['total'] = $total;
    	//     	exit($query->createCommand()->sql);
    	return json_encode($returnArr);

	}

	//派车登记
	public function actionAdd()
	{	
		date_default_timezone_set('PRC');
		//echo 'mm.';exit;
		$connection = yii::$app->db;
		$model = new CarOfficeRegister();
		$add_id = $model->add_id = $_SESSION['backend']['adminInfo']['id'];
		$reg_time = $model->reg_time = date('Y-m-d H:i:s');
		//var_dump($reg_time);exit;
		//var_dump($model->reg_time);exit;
		//echo 'm1';exit;
		if(yii::$app->request->isPost){
			//echo 'm2';exit;
			$id = yii::$app->request->post('car_id');
			//var_dump($car_id);exit;
			$department_id = yii::$app->request->post('department_id');
			$username_id = yii::$app->request->post('username_id');
			$start_time = yii::$app->request->post('start_time');
			$end_time = yii::$app->request->post('end_time');
			$reason = yii::$app->request->post('reason');
			$address = yii::$app->request->post('address');
			$total_distance = yii::$app->request->post('total_distance');
			$remain_distance = yii::$app->request->post('remain_distance');
            if($remain_distance == '') {
                $remain_distance = 0; 
            }
            if($total_distance == '') {
                $total_distance = 0; 
            }
			$note = yii::$app->request->post('note');
			//----------------------
			$car_lend = CarOfficeRegister::find()->where(['id'=>$id])->asArray()->one();
    		$car_id = $car_lend['car_id'];
    		//echo '<pre>';
			//var_dump($department_id);exit;

			//记录操作人，操作时间
    		if($car_lend['is_return'] == 1){
    			$reg_record = $connection->createCommand()->insert('cs_car_office_register', [
						'car_id' => $car_id,
						//'reg_time' => date('Y-m-d H:i:s'),
						'is_office'=>1,
    					'department_id' =>$department_id,
    					'username_id' =>$username_id,
    					'start_time' =>$start_time,
    					'end_time' =>$end_time,
    					'reason' =>$reason,
    					'address' =>$address,
    					'total_distance' =>$total_distance,
    					'remain_distance' =>$remain_distance,
    					'status' =>'out_car',
    					'add_id' =>$add_id,
    					'reg_time' =>$reg_time				
						])->execute();
    			$sql = "update cs_car_office_register set is_office = 2 where id={$id}";
				$reg_record = $connection->createCommand($sql)->execute();
    		} else{
    			$sql = "update cs_car_office_register set department_id = $department_id,username_id = $username_id,start_time = '$start_time',end_time = '$end_time',reason = '$reason',address = '$address',total_distance = $total_distance,remain_distance = $remain_distance,note = '$note',status = 'out_car',add_id = '$add_id',reg_time = '$reg_time' where car_id={$car_id}";
				$reg_record = $connection->createCommand($sql)->execute();
    		}
			
			//var_dump($reg_record);exit;
			
			if($reg_record){
				$returnArr['status'] = true;
	    		$returnArr['info'] = '派车成功!';
			} else {
				$returnArr['status'] = false;
	    		$returnArr['info'] = '派车失败!';	
			}
			return json_encode($returnArr);
		} else {

			$id = intval(yii::$app->request->get('id'));
    		$car_stock = CarOfficeRegister::find()->where(['id'=>$id])->asArray()->one();
    		$car_id = $car_stock['car_id'];
    		$car = Car::find()->where(['id'=>$car_id])->asArray()->one();
			

			//查询部门
			$query = Department::find()
			->select(['id','name'])
			->where(['is_del'=>0])
			//->asArray()
			//->all()
            ;
            //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
            $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
            if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
                $query->andWhere("`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
            }

            $dep_uer = $query->asArray()->all();
            //echo '<pre>';
            //var_dump($dep_uer);exit;


			//查询用车人
			/*$admin = Admin::find()
			->select(['id','name'])
			->where(['is_del'=>0])
			->asArray()
			->all();*/

            //查询部门与对应的人
			/*$dep_uer = Admin::find()
			->select([
				'cs_admin.id',
				'cs_admin.name username',
				'cs_department.name department',
                'cs_department.pid',
				])
			->where(['cs_admin.is_del'=>0])
			->leftJoin('cs_department', 'cs_admin.department_id = cs_department.id')
			->asArray()
			->all();*/
            //echo '<pre>';
            //var_dump($dep_uer);exit;

			return $this->render('add',['dep_uer'=>$dep_uer,'car_id'=>$car_id,'id'=>$id,'car'=>$car]);
		}
			
	}

	//添加公务车辆
	public function actionAdd2()
	{	
		/*$type = yii::$app->request->get('car_type');
		if(yii::$app->request->isPost){
			$type = yii::$app->request->post('car_type');
    		$car_id = yii::$app->request->post('car_id');
    		//检查车辆状态
    		$carstock = CarStock::find()->where(['car_id'=>$car_id])->asArray()->one();
    		//var_dump($carstock);exit;
    		if(!$carstock || $carstock['car_type'] != 1){
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
    			//var_dump($model->add_aid);exit;
    			if((new Query())->from('cs_car_stock')->andWhere(['car_id'=>$car_id,'is_del'=>0])->count()>1){
    				$returnArr['status'] = false;
    				$returnArr['info'] = '车辆已存在！';
    			} elseif($model->save(false)) {
    				//更改车辆状态(is_office)
    				$connection = yii::$app->db;
    				$sql = "update cs_car_stock set is_office='1' where car_id={$car_id}";
    				$connection->createCommand($sql)->execute();
    				$returnArr['status'] = true;
    				$returnArr['info'] = '添加车辆成功！';
    			} else {
    				$returnArr['status'] = false;
    				$returnArr['info'] = '添加车辆失败！';
    			}
    		} else {
    			$returnArr['status'] = false;
    			$error = $model->getErrors();
    			if($error){
    				$returnArr['info'] = join('',array_column($error,0));
    			}else{
    				$returnArr['info'] = '未知错误';
    			}
    		}
    		return json_encode($returnArr);
		} else {
			return $this->render('add2');
		}*/

		/*$type = yii::$app->request->get('car_type');
		date_default_timezone_set('PRC');

		$car_id = yii::$app->request->post('car_id');
		if(yii::$app->request->isPost){
			//$type = yii::$app->request->post('car_type');
    		//$car_id = yii::$app->request->post('car_id');
    		//检查车辆状态
    		/*$carstock = CarStock::find()->where(['car_id'=>$car_id])->asArray()->one();*/
    		//var_dump($carstock);exit;
    		/*if(!$carstock || $carstock['car_type'] != 1){
    			$returnArr['status'] = false;
    			$returnArr['info'] = '车辆无效！';
    			return json_encode($returnArr);
    		}*/
    		//添加车辆
    		/*$model = new CarStock();
    		$model->load(yii::$app->request->post(),'');
    		$returnArr = [];
    		if($model->validate()){
    			$model->add_aid = $_SESSION['backend']['adminInfo']['id'];
    			$model->add_time = date('Y-m-d H:i:s');
    			//var_dump($model->add_aid);exit;
    			if((new Query())->from('cs_car_stock')->andWhere(['car_id'=>$car_id,'is_del'=>0])->count()>1){
    				$returnArr['status'] = false;
    				$returnArr['info'] = '车辆已存在！';
    			} elseif($model->save(false)) {
    				//更改车辆状态(is_office)
    				$connection = yii::$app->db;
    				$sql = "update cs_car_stock set is_office='2' where car_id={$car_id}";//2为已经从自用车添加为公务车，不再显示
    				$connection->createCommand()->insert('cs_car_office_register', [
    							'car_id' => $car_id,
    							'reg_time' => date('Y-m-d H:i:s'),
    							'is_office'=>1
    							//'car_status' => 'DSTCAR',
    							//'note' => '此车作为'.$department->name.'的部门用车'
    							])->execute();
    				$connection->createCommand($sql)->execute();
    				$returnArr['status'] = true;
    				$returnArr['info'] = '添加车辆成功！';
    			} else {
    				$returnArr['status'] = false;
    				$returnArr['info'] = '添加车辆失败！';
    			}
    		} else {
    			$returnArr['status'] = false;
    			$error = $model->getErrors();
    			if($error){
    				$returnArr['info'] = join('',array_column($error,0));
    			}else{
    				$returnArr['info'] = '未知错误';
    			}
    		}
    		return json_encode($returnArr);
		} else {
			return $this->render('add2');
		}*/
		//....................................................................
		date_default_timezone_set('PRC');
		//echo 'mm.';exit;
		$connection = yii::$app->db;
		$model = new CarOfficeRegister();
		$add_id = $model->add_id = $_SESSION['backend']['adminInfo']['id'];
		$reg_time = $model->reg_time = date('Y-m-d H:i:s');
		$car_id = yii::$app->request->post('car_id');
		//var_dump($reg_time);exit;
		//var_dump($model->reg_time);exit;
		if(yii::$app->request->isPost){
			//$car_id = yii::$app->request->post('car_id');

			
			//var_dump($reason);exit;
			//记录操作人，操作时间

			/*$sql = "update cs_car_office_register set department_id = $department_id,username_id = $username_id,start_time = '$start_time',end_time = '$end_time',reason = '$reason',address = '$address',total_distance = $total_distance,remain_distance = $remain_distance,note = '$note',status = 'out_car',add_id = '$add_id',reg_time = '$reg_time' where car_id={$car_id}";*/
			
			$sql = "update cs_car_stock set is_office=2 where car_id={$car_id}";//2为已经从自用车添加为公务车，不再显示
			$query = $connection->createCommand($sql)->execute();
			//var_dump($connection->createCommand($sql)->execute());exit;
			//echo $query->createCommand()->getRawSql();exit;
			$reg_record = $connection->createCommand()->insert('cs_car_office_register', [
						'car_id' => $car_id,
						'reg_time' => date('Y-m-d H:i:s'),
						//'is_office'=>1


						])->execute();
			//$reg_record = $connection->createCommand($sql)->execute();
			//var_dump($reg_record);exit;
			if($reg_record && $query){
				$returnArr['status'] = true;
	    		$returnArr['info'] = '添加成功!';
			} else {
				$returnArr['status'] = false;
	    		$returnArr['info'] = '添加失败!';	
			}
			return json_encode($returnArr);
		} else {
			return $this->render('add2');
		}

	}


	public function actionGetCarsByAdd()
    {
    	//echo '5555';exit;
    	$queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
        //var_dump($queryStr);exit;
    	$carId = isset($_REQUEST['car_id']) ? intval($_REQUEST['car_id']) : 0; //修改时赋值用
    	$query = CarStock::find()
    	->select(['{{%car}}.id','plate_number'])
    	->leftJoin('{{%car}}','{{cs_car_stock}}.car_id = {{%car}}.id and {{%car}}.is_del=0')
    	->leftJoin('{{%car_office_register}}','{{cs_car_stock}}.car_id = {{cs_car_office_register}}.car_id')
    	->where(['{{cs_car_stock}}.car_type'=>1,'{{cs_car_stock}}.is_del'=>0]);
    	$query->andFilterWhere(['<>','{{%car_stock}}.`is_office`',2]);
    	//echo $query->createCommand()->getRawSql();exit;
    	//echo '<pre>';
        //var_dump($query);exit;
    	if($carId){
    		// 修改时查询赋值
    		$total = $query->andWhere(['id'=>$carId])->count();
    	}elseif($queryStr){
    		// 检索过滤时
    		$total = $query->andWhere([
    				'or',
    				['like', 'plate_number', $queryStr]
    				
    				])
    				->count();
    	}else{
    		// 默认查询
    		$total = $query->count();
    	}
    	//var_dump($total);exit;
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) :10;
    	$pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
    	$data = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
    	$returnArr = [];
    	$returnArr['rows'] = $data;
    	$returnArr['total'] = $total;
    	return json_encode($returnArr);
    	
    }

    //还车登记
    public function actionReturn()
    {	
    	date_default_timezone_set('PRC');
        $model = new CarOfficeRegister();
        $reg_time = $model->reg_time = date('Y-m-d H:i:s');
    	$connection = yii::$app->db;
    	//echo 'm1';exit;
		if(yii::$app->request->isPost){
			//echo 'm2';exit;
			//$car_id = yii::$app->request->post('car_id');
			$car_id = intval(yii::$app->request->post('car_id'));
			//var_dump($car_id);exit;
			//echo 'm2';exit;
			$return_time = yii::$app->request->post('return_time');
			$return_distance = yii::$app->request->post('return_distance');
			$remain_distance_return = yii::$app->request->post('remain_distance_return');


            if($remain_distance_return == '') {
                $remain_distance_return = 0;
            }
            if($return_distance == '') {
                $return_distance = 0;
            }

			$note_return = yii::$app->request->post('note_return');
			//var_dump($return_time);exit;

			$sql = "update cs_car_office_register set return_time = '$return_time',return_distance = $return_distance,remain_distance_return = $remain_distance_return,status='available',note_return='$note_return',is_return=1,reg_time='$reg_time' where id={$car_id}";
			$ret_record = $connection->createCommand($sql)->execute();
			//var_dump($ret_record);exit;
			if($ret_record){
				$returnArr['status'] = true;
	    		$returnArr['info'] = '还车成功!';
			} else {
				$returnArr['status'] = false;
	    		$returnArr['info'] = '还车失败!';	
			}
			return json_encode($returnArr);
		} else {
			$id = intval(yii::$app->request->get('id'));
			//var_dump($id);exit;
    		$car_stock = CarOfficeRegister::find()->where(['id'=>$id])->asArray()->one();
    		$car_id = $car_stock['car_id'];
    		$car = Car::find()->where(['id'=>$car_id])->asArray()->one();
			return $this->render('return',['car_id'=>$car_id,'id'=>$id]);
		}
    	
    }

    //删除车辆
    public function actionRemove()
    {	
        $connection = yii::$app->db;
    	$id = intval(yii::$app->request->get('id')) or die('param id is required');
        //获取ID
        $car_of = CarOfficeRegister::find()->where(['id'=>$id])->asArray()->one();
        if($car_of['status'] == 'out_car'){
        	$returnArr['status'] = false;
        	$returnArr['info'] = '状态为出车中的车辆不允许移除！';
        	exit(json_encode($returnArr));
        }
        $car_id_of = $car_of['car_id'];
        $sql = "update cs_car_office_register set is_del = 1 where id={$id}";
        $carofficeregister = $connection->createCommand($sql)->execute();


        $sqla = "update cs_car_stock set is_office = 0 where cs_car_stock.car_id={$car_id_of}";
        $carofficeregister = $connection->createCommand($sqla)->execute();
        //CarStock::updateAll(['is_del'=>1],['id'=>$id,'car_status'=>2])
		$returnArr = [];
		if($carofficeregister){
			$returnArr['status'] = true;
			$returnArr['info'] = '公务车数据删除成功';
		}else{
			$returnArr['status'] = false;
			$returnArr['info'] = '公务车数据删除失败';			
		}
		echo json_encode($returnArr);	
    }

    //按照条件导出
    public function actionExportWidthCondition()
    {
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    	$plate_number = yii::$app->request->get('plate_number');
    	//$car_type = yii::$app->request->get('car_type');
    	$status = yii::$app->request->get('status');
        $username = yii::$app->request->get('username');

        //配置信息
        $configItems = ['car_model_name'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
    
    	$query = (new \yii\db\Query())->select([
    			'a.*',
    			'a.address',
    			'a.start_time',
    			'd.name username',
    			//'address',
    			//'b.company_name',
    			'c.name department_name',
    			//'d.username',
    			'e.plate_number',
    			'e.car_model',
    			'f.name car_brand',
    			'd2.name reg_name',

    			])->from('cs_car_office_register a')
    			//->leftJoin('cs_customer_company b', 'a.c_customer_id = b.id')
    			->leftJoin('cs_department c', 'a.department_id = c.id')
    			->leftJoin('cs_admin d', 'a.username_id = d.id')
    			->leftJoin('cs_admin d2', 'a.add_id = d2.id')
    			->leftJoin('cs_car e', 'a.car_id = e.id')
    			->leftJoin('cs_car_brand f','e.brand_id = f.id')
    			//->andWhere(['a.is_del'=>0])
    			;
    	$query->andFilterWhere(['=','a.`is_del`',0]);
        $query->andFilterWhere(['<>','a.`is_office`',2]);
    	//查询条件
    	if($plate_number){
    		$query->andFilterWhere([
    				'like',
    				'e.plate_number',
    				$plate_number
    				]);
    	}
    	if($status){
    		$query->andFilterWhere([
    				'=',
    				'a.status',
    				$status
    				]);
    	}
    	if($username){
    		$query->andFilterWhere([
    				'like',
    				'd.name',
    				$username
    				]);
    	}
    	$data = $query->all();
        foreach ($data as $key => $car_mod) {
            //var_dump($start_time);exit;
            //车辆类型
            $car_m = $car_mod['car_model'];
            $data[$key]['car_model'] = $config['car_model_name'][$car_m]['text'];
            if($data[$key]['is_return'] == 1 && $data[$key]['remain_distance_return'] == 0 ){
                $data[$key]['remain_distance'] = 0;
            }
            if($data[$key]['is_return'] == 1 && $data[$key]['remain_distance_return'] != 0 ){
                $data[$key]['remain_distance'] = $data[$key]['remain_distance_return'];
            }
            if($data[$key]['is_return'] == 1) {
                $data[$key]['username'] = '';
                $data[$key]['department_name'] = '';
                $data[$key]['reg_name'] = '';
            }
           
        }

    	//echo '<pre>';
    	//var_dump($data);exit;
    	$filename = '自用备用车列表.csv'; //设置文件名
    	$str = "车牌号,车辆品牌,车型名称,使用状态,剩余续航里程,用车部门,用车人,登记人,登记时间\n";
    	$car_status_arr = array('available'=>'可用','out_car'=>'出车','repair'=>'维修');
    	//$car_status_arr = array(1=>'已替换',2=>'未替换');
        //var_dump($data);exit;
    	foreach ($data as $row){
    		$str .= "{$row['plate_number']},{$row['car_brand']},{$row['car_model']},{$car_status_arr[$row['status']]},{$row['remain_distance']},{$row['department_name']},{$row['username']},{$row['reg_name']},{$row['reg_time']}"."\n";
    	}
    	$str = mb_convert_encoding($str, "GBK", "UTF-8");
    	$this->export_csv($filename,$str); //导出
    }

     function export_csv($filename,$data)
    {
    	//		header("Content-type: text/html; charset=utf-8");
    	header("Content-type:text/csv;charset=GBK");
    	//header("Content-Disposition:attachment;filename=".$filename);
        header("Content-Disposition:attachment;filename=".iconv('utf-8','gbk',$filename));

    	header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    	header('Expires:0');
    	header('Pragma:public');
    	echo $data;
    }

    public function actionCheck() {

        //通过部门id查询出这个人
        //return json_encode('123');

        $id = yii::$app->request->post('id');
        $dep_uer = Admin::find()
            ->select([
                'cs_admin.id as value',
                'cs_admin.name as text',
                //'cs_department.name department',
                ])
            ->where(['cs_admin.is_del'=>0,'cs_admin.department_id'=>$id])
            ->asArray()
            ->all();
            //echo '<pre>';
           /* foreach ($dep_uer as $key => $value) {
                //var_dump($dep_uer[$key]['id']);exit;
                $id = $dep_uer[$key]['id'];
            }*/
            //var_dump($dep_uer);exit;
        //var_dump($dep_uer['id']);exit;
       // return $this->render('add',['dep_uer'=>$dep_uer]);
            //echo '<pre>';
        //var_dump($dep_uer);exit;

        //$returnArr = [];
        //$returnArr['rows'] = $dep_uer;
    
        //return json_encode($returnArr);
        return json_encode($dep_uer);

    }
}