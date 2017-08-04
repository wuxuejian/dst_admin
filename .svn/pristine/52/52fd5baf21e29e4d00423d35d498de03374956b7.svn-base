<?php
/**
 * 新车全检单类
 * @author pengyl
 *
 */
namespace backend\modules\process\controllers;
use backend\classes\MyUploadFile;
use backend\controllers\BaseController;
use yii;
use backend\models\ConfigCategory;
use backend\models\Car;
use backend\models\InspectionAll;
use backend\models\InspectionAllCar;
use yii\data\Pagination;
use yii\web\UploadedFile;
class InspectionAllController extends BaseController
{
	public function actionIndex()
	{
		//获取本页按钮
        $buttons = $this->getCurrentActionBtn(); 
        //获取配置数据
        $configItems = ['car_model_name'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
        //查询表单select选项
        $searchFormOptions = [];
        if($config['car_model_name']){
        	$searchFormOptions['car_model_name'] = [];
        	$searchFormOptions['car_model_name'][] = ['value'=>'','text'=>'不限'];
        	foreach($config['car_model_name'] as $val){
        		$searchFormOptions['car_model_name'][] = ['value'=>$val['value'],'text'=>$val['value']];
        	}
        }
        
        return $this->render('index',[
            'buttons'=>$buttons,
            'config'=>$config,
        	'searchFormOptions'=>$searchFormOptions,
        ]);
	}
	
	/**
	 * 获取【新车全检单】列表
	 */
	public function actionGetList()
	{
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
		$query = InspectionAll::find()
		->select([
				'{{oa_inspection_all}}.*',
				'car_brand'=>'{{%car_brand}}.`name`',
				'off_grade_num'=>'count(oa_inspection_all_car.id)'
				])->leftJoin('cs_car_brand', 'oa_inspection_all.car_brand_id = cs_car_brand.id')
				->leftJoin('oa_inspection_all_car', 'oa_inspection_all.id = oa_inspection_all_car.inspection_id and oa_inspection_all_car.inspection_result=2');
		$query = $query->groupBy('{{oa_inspection_all}}.`id`');
		$query->andFilterWhere([
				'!=',
				'{{oa_inspection_all}}.`is_del`',
				1
				]);
		//////查询条件
		if(yii::$app->request->get('car_brand_id')){
			$query->andFilterWhere([
					'=',
					'{{oa_inspection_all}}.`car_brand_id`',
					yii::$app->request->get('car_brand_id')
					]);
		}
		if(yii::$app->request->get('car_model')){
			$query->andFilterWhere([
					'=',
					'{{oa_inspection_all}}.`car_model`',
					yii::$app->request->get('car_model')
					]);
		}
		if(yii::$app->request->get('start_validate_car_time')){
			$query->andFilterWhere([
					'>=',
					'{{oa_inspection_all}}.`validate_car_time`',
					yii::$app->request->get('start_validate_car_time')
					]);
		}
		if(yii::$app->request->get('end_validate_car_time')){
			$query->andFilterWhere([
					'<=',
					'{{oa_inspection_all}}.`validate_car_time`',
					yii::$app->request->get('end_validate_car_time')
					]);
		}
// 		        exit($query->createCommand()->sql);
		
		//////排序
		$sortColumn = yii::$app->request->get('sort');
		$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
		$orderBy = '';
		if($sortColumn){
			switch ($sortColumn) {
				case 'username':
					$orderBy = '{{oa_inspection_all}}.`username` ';
					break;
				default:
					$orderBy = '{{oa_inspection_all}}.`'.$sortColumn.'` ';
				break;
			}
		}else{
			$orderBy = '{{oa_inspection_all}}.`id` ';
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
	 * 新建新车全检单
	 */
	public function actionAdd()
	{
		//data submit start
		if(yii::$app->request->isPost){
			//重组车辆数据
			$carInfo = [];
			if(isset($_POST['vehicle_dentification_number'])){
				foreach($_POST['vehicle_dentification_number'] as $key=>$val){
					$carInfo[$val]['vehicle_dentification_number'] = $val;
					$carInfo[$val]['inspection_result'] = $_POST['inspection_result'][$key];
					$carInfo[$val]['is_put'] = $_POST['is_put'][$key];
					$carInfo[$val]['car_note'] = $_POST['car_note'][$key];
				}
			}
			
			$model = new InspectionAll;
			$model->load(yii::$app->request->post(),'');
			$returnArr = [];
			$returnArr['status'] = true;
			$returnArr['info'] = '';
			//登记编号，格式：GZ+日期+3位数（即该登记是系统当天登记的第几个，第一个是001，第二个是002…）
			$todayCount = InspectionAll::find()
			->where([
					'and',
					['>=','add_time',date('Y-m-d').' 00:00:00'],
					['<=','add_time',date('Y-m-d').' 23:59:59'],
					])
					->count();
			$currentNo = str_pad($todayCount+1,3,0,STR_PAD_LEFT);
			$model->id = 'CJ' . date('Ymd') . $currentNo;
			
			if($model->validate()){
				$model->oper_user = $_SESSION['backend']['adminInfo']['username'];
				$model->add_time = date('Y-m-d H:i:s');
				if($model->save(false)){
					$returnArr['status'] = true;
					$returnArr['info'] = '创建成功！';
					foreach($carInfo as $val){
						$inspectionAllCarModel = new InspectionAllCar;
						$inspectionAllCarModel->inspection_id = $model->id;
						$inspectionAllCarModel->vehicle_dentification_number = $val['vehicle_dentification_number'];
						$inspectionAllCarModel->inspection_result = $val['inspection_result'];
						$inspectionAllCarModel->is_put = $val['is_put'];
						$inspectionAllCarModel->car_note = $val['car_note'];
						
						if(!$inspectionAllCarModel->validate()){
							//验证失败
							$errors = $inspection_allCarModel->getErrors();
							if($errors){
								foreach($errors as $val){
									$returnArr['info'] .= "车辆：{$val['vehicle_dentification_number']}登记失败,{$val[0]}！";
								}
							}else{
								$returnArr['info'] = "车辆：{$val['vehicle_dentification_number']}登记失败，未知错误！";
							}
							continue;
						}
						
						//验证通过
						if($inspectionAllCarModel->save(false)){
							$returnArr['info'] .= "车辆：{$val['vehicle_dentification_number']}登记成功！";
						}else{
							$returnArr['info'] .= "车辆：{$val['vehicle_dentification_number']}登记失败,数据保存出错！";
						}
					}
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
		//获取配置数据
		$configItems = ['car_model_name'];
		$config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
		//data submit end
		//获取行编辑时combox中可选的库存车辆-20160325
		$stockCars = Car::getAvailableStockCarsVin();
		return $this->render('add',[
				'config'=>$config,
				'car'=>$stockCars
				]);
	}
	
	/**
	 * 新车全检单修改
	 */
	public function actionEdit()
	{
		if(yii::$app->request->isPost){
			$id = yii::$app->request->post('id') or die('param id is required');
			$model = InspectionAll::findOne(['id'=>$id]);
			$model or die('record not found');
			$model->load(yii::$app->request->post(),'');
			$returnArr = [];
			$returnArr['status'] = true;
			$returnArr['info'] = '';
			if($model->validate()){
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
		$model = InspectionAll::findOne(['id'=>$id]);
		$model or die('record not found');
		
		//获取配置数据
		$configItems = ['car_model_name'];
		$config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
		//data submit end
		//获取行编辑时combox中可选的库存车辆-20160325
		$stockCars = Car::getAvailableStockCarsVin();
		return $this->render('edit',[
				'inspection_all'=>$model->getAttributes(),
				'config'=>$config,
				'inspection_id'=>$id,
				'car'=>$stockCars
				]);
	}
	
	/**
	 * 批量添加或修改登记车辆
	 */
	public function actionAddEditCar()
	{
		$data = yii::$app->request->post();
		$returnArr = [];
		$returnArr['status'] = true;
		$returnArr['info'] = '';
		
		
		if(isset($data['id']) && is_array($data['id'])){
			foreach($data['id'] as $key=>$val){
				if($val == 0){
					$model = new InspectionAllCar;
					$model->setScenario('default');
				}else{
					$model = InspectionAllCar::findOne(['id'=>$val]);
					if($model){
						$model->setScenario('edit');
					}else{
						$model = new InspectionAllCar;
						$model->setScenario('default');
					}
				}
				$model->inspection_id = $data['inspection_id'];
				$model->car_note = $data['car_note'][$key];
				$model->vehicle_dentification_number = $data['vehicle_dentification_number'][$key];
				$model->inspection_result = $data['inspection_result'][$key];
				$model->is_put = $data['is_put'][$key];
				
				if($model->validate()){
					//新加车辆时防止并发操作导致同一辆车被加入两个登记单中
					if($model->scenario == 'default'){
						//新加车辆
						$carSave = $model->save(false);
						if($carSave){
						}else{
							$returnArr['info'] = '车辆：'.$data['vehicle_dentification_number'][$key].'登记信息修改失败！';
						}
					}else{
						//修改车辆
						if($model->save(false)){
							
						}else{
							//修改失败
							$returnArr['info'] = $data['vehicle_dentification_number'][$key].'登记信息修改失败！';
						}
					}
				}else{
					$errors = $model->getErrors();
					$returnArr['info'] .= $data['vehicle_dentification_number'][$key].'操作失败，错误原因：';
					if($errors){
						$returnArr['info'] .= join('',array_column($errors,0));
					}else{
						$returnArr['info'] .= '未知错误！';
					}
				}
			}
			$returnArr['info'] = $returnArr['info'] ? $returnArr['info'] : '操作成功！';
		}else{
			$returnArr['status'] = false;
			$returnArr['info'] = '没有数据被添加或修改！';
		}
		if($returnArr['status']){
			if(isset($noBusinessInsuranceTip)){
				$returnArr['info'] .= '<br/>'.$noBusinessInsuranceTip;
			}
		}
		echo json_encode($returnArr);
	}
	
	/**
	 * 获取指定登记单车辆列表
	 */
	public function actionGetCarList()
	{
		$inspection_id = yii::$app->request->get('inspection_id') or die('param inspection_id id required');
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
		$query = InspectionAllCar::find()
		->select([
				'{{oa_inspection_all_car}}.*',
				'{{%car}}.`vehicle_dentification_number`'
				])
		->leftJoin('cs_car', 'oa_inspection_all_car.car_id = cs_car.id')
		->where([
				'{{oa_inspection_all_car}}.`inspection_id`'=>$inspection_id,
				]);
		//查询条件
		//////查询条件
		if(yii::$app->request->get('vehicle_dentification_number')){
			$query->andFilterWhere([
	            'like',
	            Car::tableName().'.`vehicle_dentification_number`',
	            yii::$app->request->get('vehicle_dentification_number')
	        ]);
		}
		if(yii::$app->request->get('inspection_result')){
			$query->andFilterWhere([
					'=',
					'{{oa_inspection_all_car}}.`inspection_result`',
					yii::$app->request->get('inspection_result')
					]);
		}
		if(yii::$app->request->get('is_put')){
			$query->andFilterWhere([
					'=',
					'{{oa_inspection_all_car}}.`is_put`',
					yii::$app->request->get('is_put')
					]);
		}
		$data = $query->orderBy(InspectionAllCar::tableName().'.`id` desc')
		->asArray()->all();
		//查询条件结束
		//排序开始
		$sortColumn = yii::$app->request->get('sort');
		$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
		$orderBy = '';
		if($sortColumn){
			switch ($sortColumn) {
				default:
					$orderBy = InspectionAllCar::tableName().'.`'.$sortColumn.'` ';
				break;
			}
		}else{
			$orderBy = InspectionAllCar::tableName().'.`id` ';
		}
		$orderBy .= $sortType;
		//排序结束
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
	 * 删除
	 */
	public function actionDelete()
	{
		if(yii::$app->request->isPost){
			$id = yii::$app->request->post('id') or die('param id is required');
			$model = InspectionAll::findOne(['id'=>$id]);
			$model or die('record not found');
			
			$model->setScenario('del');
			$model->load(yii::$app->request->post(),'');
			$model->is_del = 1;
			$returnArr = [];
			$returnArr['status'] = true;
			$returnArr['info'] = '';
			if($model->validate()){
				if($model->save(false)){
					$returnArr['status'] = true;
					$returnArr['info'] = '操作成功！';
				}else{
					$returnArr['status'] = false;
					$returnArr['info'] = '操作失败！';
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
	}
	
	/**
	 * 提交审批
	 */
	public function actionApprove(){
		if(yii::$app->request->isPost){
			$id = yii::$app->request->post('id') or die('param id is required');
			$model = InspectionAll::findOne(['id'=>$id]);
			$model or die('record not found');
			
			$model->setScenario('approve');
			$model->load(yii::$app->request->post(),'');
			$model->approve_status = 2;
			$returnArr = [];
			$returnArr['status'] = true;
			$returnArr['info'] = '';
			if($model->validate()){
				if($model->save(false)){
					$returnArr['status'] = true;
					$returnArr['info'] = '操作成功！';
				}else{
					$returnArr['status'] = false;
					$returnArr['info'] = '操作失败！';
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
	}
	
	/**
	 * 确认
	 */
	public function actionConfirm(){
		$id = yii::$app->request->get('id') or die('param id is required');
		$model = InspectionAll::findOne(['id'=>$id]);
		$model or die('record not found');
		
		$model->setScenario('approve');
		$model->id = $id;
		$model->approve_status = 3;
		$returnArr = [];
		$returnArr['status'] = true;
		$returnArr['info'] = '';
		if($model->validate()){
			if($model->save(false)){
				$returnArr['status'] = true;
				$returnArr['info'] = '操作成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败！';
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
	
	/**
	 * 查看
	 */
	public function actionDetail(){
		$id = yii::$app->request->get('id') or die('param id is required');
		
		$query = InspectionAll::find()
		->select([
				'{{oa_inspection_all}}.*',
				'car_brand'=>'{{%car_brand}}.`name`',
				])->leftJoin('cs_car_brand', 'oa_inspection_all.car_brand_id = cs_car_brand.id');
		$query->andFilterWhere([
				'=',
				'{{oa_inspection_all}}.`id`',
				$id
				]);
		$detail = $query->offset(0)->limit(1)->asArray()->one();
		return $this->render('detail',[
				'detail'=>$detail,
				'inspection_id'=>$id,
				]);
	}
	//导出指定检验单车辆列表
	public function actionExportCarsWidthCondition(){
		$query = InspectionAllCar::find()
		->select([
				'{{oa_inspection_all_car}}.*',
				'{{%car}}.`vehicle_dentification_number`'
				])
		->leftJoin('cs_car', 'oa_inspection_all_car.car_id = cs_car.id');
		//////查询条件
		$query->andFilterWhere([
				'=',
				'{{oa_inspection_all_car}}.`inspection_id`',
				yii::$app->request->get('inspection_id')
				]);
		//         exit($query->createCommand()->sql);
		
		//////排序
		$sortColumn = yii::$app->request->get('sort');
		$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
		$orderBy = '';
		if($sortColumn){
			switch ($sortColumn) {
				default:
					$orderBy = '{{oa_inspection_all_car}}.`'.$sortColumn.'` ';
				break;
			}
		}else{
			$orderBy = '{{oa_inspection_all_car}}.`id` ';
		}
		$orderBy .= $sortType;
		$data = $query->orderBy($orderBy)->asArray()->all();
		
		$filename = '全检结果登记车辆列表.csv'; //设置文件名
		$str = "车架号,检验结果,是否提车,备注\n";
		$inspection_result_arr = array(1=>'合格',2=>'不合格');
		$is_put_arr = array(0=>'',1=>'已提车',2=>'未提车');
		foreach ($data as $row){
			$str .= "{$row['vehicle_dentification_number']},{$inspection_result_arr[$row['inspection_result']]},{$is_put_arr[$row['is_put']]},{$row['car_note']}"."\n";
		}
		$str = mb_convert_encoding($str, "GBK", "UTF-8");
		$this->export_csv($filename,$str); //导出
	}
	/**
	 * 按条件导出登记
	 */
	public function actionExportWidthCondition()
	{
		$query = InspectionAll::find()
		->select([
				'{{oa_inspection_all}}.*',
				'car_brand'=>'{{%car_brand}}.`name`',
				])->leftJoin('cs_car_brand', 'oa_inspection_all.car_brand_id = cs_car_brand.id');
		$query->andFilterWhere([
				'!=',
				'{{oa_inspection_all}}.`is_del`',
				1
				]);
		//////查询条件
		if(yii::$app->request->get('car_brand_id')){
			$query->andFilterWhere([
					'=',
					'{{oa_inspection_all}}.`car_brand_id`',
					yii::$app->request->get('car_brand_id')
					]);
		}
		if(yii::$app->request->get('car_model')){
			$query->andFilterWhere([
					'=',
					'{{oa_inspection_all}}.`car_model`',
					yii::$app->request->get('car_model')
					]);
		}
		if(yii::$app->request->get('approve_status')){
			$query->andFilterWhere([
					'=',
					'{{oa_inspection_all}}.`approve_status`',
					yii::$app->request->get('approve_status')
					]);
		}
		if(yii::$app->request->get('approve_result')){
			$query->andFilterWhere([
					'=',
					'{{oa_inspection_all}}.`approve_result`',
					yii::$app->request->get('approve_result')
					]);
		}
		if(yii::$app->request->get('start_validate_car_time')){
			$query->andFilterWhere([
					'>=',
					'{{oa_inspection_all}}.`validate_car_time`',
					yii::$app->request->get('start_validate_car_time')
					]);
		}
		if(yii::$app->request->get('end_validate_car_time')){
			$query->andFilterWhere([
					'<=',
					'{{oa_inspection_all}}.`validate_car_time`',
					yii::$app->request->get('end_validate_car_time')
					]);
		}
		//         exit($query->createCommand()->sql);
		
		//////排序
		$sortColumn = yii::$app->request->get('sort');
		$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
		$orderBy = '';
		if($sortColumn){
			switch ($sortColumn) {
				case 'username':
					$orderBy = '{{oa_inspection_all}}.`username` ';
					break;
				default:
					$orderBy = '{{oa_inspection_all}}.`'.$sortColumn.'` ';
				break;
			}
		}else{
			$orderBy = '{{oa_inspection_all}}.`id` ';
		}
		$orderBy .= $sortType;
		$data = $query->orderBy($orderBy)->asArray()->all();
		
		$filename = '全检结果登记列表.csv'; //设置文件名
		$str = "检验批次编号,状态,车辆品牌,产品型号,计划提车数量,实际提车数量,验车负责人,验车时间,登记时间,登记人\n";
		$approve_status_arr = array(1=>'未提交审批',2=>'等待确认',3=>'已完结');
		foreach ($data as $row){
			$str .= "{$row['id']},{$approve_status_arr[$row['approve_status']]},{$row['car_brand']},{$row['car_model']},{$row['car_num']},{$row['real_car_num']},{$row['inspection_director_name']},{$row['validate_car_time']},{$row['add_time']},{$row['oper_user']}"."\n";
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