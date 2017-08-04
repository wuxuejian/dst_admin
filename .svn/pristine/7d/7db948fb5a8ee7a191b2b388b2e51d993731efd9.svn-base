<?php
/**
 * 新车抽检单类
 * @author pengyl
 *
 */
namespace backend\modules\process\controllers;
use backend\classes\MyUploadFile;
use backend\controllers\BaseController;
use yii;
use backend\models\ConfigCategory;
use backend\models\Inspection;
use backend\models\InspectionCar;
use yii\data\Pagination;
use yii\web\UploadedFile;
class InspectionController extends BaseController
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
	 * 获取【新车抽检单】列表
	 */
	public function actionGetList()
	{
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
		$query = Inspection::find()
		->select([
				'{{oa_inspection}}.*',
				'car_brand'=>'{{%car_brand}}.`name`',
				])->leftJoin('cs_car_brand', 'oa_inspection.car_brand_id = cs_car_brand.id');
		//////查询条件
		if(yii::$app->request->get('car_brand_id')){
			$query->andFilterWhere([
					'=',
					'{{oa_inspection}}.`car_brand_id`',
					yii::$app->request->get('car_brand_id')
					]);
		}
		if(yii::$app->request->get('car_model')){
			$query->andFilterWhere([
					'=',
					'{{oa_inspection}}.`car_model`',
					yii::$app->request->get('car_model')
					]);
		}
		if(yii::$app->request->get('approve_status')){
			$query->andFilterWhere([
					'=',
					'{{oa_inspection}}.`approve_status`',
					yii::$app->request->get('approve_status')
					]);
		}
		if(yii::$app->request->get('approve_result')){
			$query->andFilterWhere([
					'=',
					'{{oa_inspection}}.`approve_result`',
					yii::$app->request->get('approve_result')
					]);
		}
		if(yii::$app->request->get('start_validate_car_time')){
			$query->andFilterWhere([
					'>=',
					'{{oa_inspection}}.`validate_car_time`',
					yii::$app->request->get('start_validate_car_time')
					]);
		}
		if(yii::$app->request->get('end_validate_car_time')){
			$query->andFilterWhere([
					'<=',
					'{{oa_inspection}}.`validate_car_time`',
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
					$orderBy = '{{oa_inspection}}.`username` ';
					break;
				default:
					$orderBy = '{{oa_inspection}}.`'.$sortColumn.'` ';
				break;
			}
		}else{
			$orderBy = '{{oa_inspection}}.`id` ';
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
	 * 新建新车抽检单
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
					$carInfo[$val]['note'] = $_POST['note'][$key];
				}
			}
			
			$model = new Inspection;
			$model->load(yii::$app->request->post(),'');
			$returnArr = [];
			$returnArr['status'] = true;
			$returnArr['info'] = '';
			//登记编号，格式：GZ+日期+3位数（即该登记是系统当天登记的第几个，第一个是001，第二个是002…）
			$todayCount = Inspection::find()
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
						$inspectionCarModel = new InspectionCar;
						$inspectionCarModel->inspection_id = $model->id;
						$inspectionCarModel->vehicle_dentification_number = $val['vehicle_dentification_number'];
						$inspectionCarModel->note = $val['note'];
						if(!$inspectionCarModel->validate()){
							//验证失败
							$errors = $inspectionCarModel->getErrors();
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
						if($inspectionCarModel->save(false)){
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
		return $this->render('add',[
				'config'=>$config,
				]);
	}
	
	/**
	 * 新车抽检单修改
	 */
	public function actionEdit()
	{
		if(yii::$app->request->isPost){
			$id = yii::$app->request->post('id') or die('param id is required');
			$model = Inspection::findOne(['id'=>$id]);
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
		$model = Inspection::findOne(['id'=>$id]);
		$model or die('record not found');
		
		//获取配置数据
		$configItems = ['car_model_name'];
		$config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
		
		return $this->render('edit',[
				'inspection'=>$model->getAttributes(),
				'config'=>$config,
				'inspectionId'=>$id
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
					$model = new InspectionCar;
					$model->setScenario('default');
				}else{
					$model = InspectionCar::findOne(['id'=>$val]);
					if($model){
						$model->setScenario('edit');
					}else{
						$model = new InspectionCar;
						$model->setScenario('default');
					}
				}
				$model->inspection_id = $data['inspection_id'];
				$model->note = $data['note'][$key];
				$model->vehicle_dentification_number = $data['vehicle_dentification_number'][$key];
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
		$inspectionId = yii::$app->request->get('inspectionId') or die('param inspectionId id required');
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
		$query = InspectionCar::find()
		->select([
				'{{oa_inspection_car}}.*'
				])
				->where([
						'{{oa_inspection_car}}.`inspection_id`'=>$inspectionId,
						]);
		//查询条件
		$data = $query->orderBy(InspectionCar::tableName().'.`id` desc')
		->asArray()->all();
		//查询条件结束
		//排序开始
		$sortColumn = yii::$app->request->get('sort');
		$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
		$orderBy = '';
		if($sortColumn){
			switch ($sortColumn) {
				default:
					$orderBy = InspectionCar::tableName().'.`'.$sortColumn.'` ';
				break;
			}
		}else{
			$orderBy = InspectionCar::tableName().'.`id` ';
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
	public function actionUploadThumb(){
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
		$storePath = 'uploads/image/inspection/';
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
		$oStr = $isEdit > 0 ? 'ProcessInspectionEdit' : 'ProcessInspectionAdd';
		return '<script>window.parent.'.$oStr.'.uploadComplete('.json_encode($returnArr).');</script>';
	}
	
	/**
	 * 审批
	 */
	public function actionApprove(){
		if(yii::$app->request->isPost){
			$id = yii::$app->request->post('id') or die('param id is required');
			$model = Inspection::findOne(['id'=>$id]);
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
		
		$id = yii::$app->request->get('id') or die('param id is required');
		return $this->render('approve',[
				'id'=>$id
				]);
	}
	
	/**
	 * 确认
	 */
	public function actionConfirm(){
		$id = yii::$app->request->get('id') or die('param id is required');
		$model = Inspection::findOne(['id'=>$id]);
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
		
		$query = Inspection::find()
		->select([
				'{{oa_inspection}}.*',
				'car_brand'=>'{{%car_brand}}.`name`',
				])->leftJoin('cs_car_brand', 'oa_inspection.car_brand_id = cs_car_brand.id');
		$query->andFilterWhere([
				'=',
				'{{oa_inspection}}.`id`',
				$id
				]);
		$detail = $query->offset(0)->limit(1)->asArray()->one();
		
		
		
		$query = InspectionCar::find()
		->select([
				'{{oa_inspection_car}}.*',
				]);
		$query->andFilterWhere([
				'=',
				'{{oa_inspection_car}}.`inspection_id`',
				$detail['id']
				]);
		$cars = $query->asArray()->all();
		return $this->render('detail',[
				'detail'=>$detail,
				'cars'=>$cars,
				]);
	}
	
	/**
	 * 按条件导出登记
	 */
	public function actionExportWidthCondition()
	{
		$query = Inspection::find()
		->select([
				'{{oa_inspection}}.*',
				'car_brand'=>'{{%car_brand}}.`name`',
				])->leftJoin('cs_car_brand', 'oa_inspection.car_brand_id = cs_car_brand.id');
		//////查询条件
		if(yii::$app->request->get('car_brand_id')){
			$query->andFilterWhere([
					'=',
					'{{oa_inspection}}.`car_brand_id`',
					yii::$app->request->get('car_brand_id')
					]);
		}
		if(yii::$app->request->get('car_model')){
			$query->andFilterWhere([
					'=',
					'{{oa_inspection}}.`car_model`',
					yii::$app->request->get('car_model')
					]);
		}
		if(yii::$app->request->get('approve_status')){
			$query->andFilterWhere([
					'=',
					'{{oa_inspection}}.`approve_status`',
					yii::$app->request->get('approve_status')
					]);
		}
		if(yii::$app->request->get('approve_result')){
			$query->andFilterWhere([
					'=',
					'{{oa_inspection}}.`approve_result`',
					yii::$app->request->get('approve_result')
					]);
		}
		if(yii::$app->request->get('start_validate_car_time')){
			$query->andFilterWhere([
					'>=',
					'{{oa_inspection}}.`validate_car_time`',
					yii::$app->request->get('start_validate_car_time')
					]);
		}
		if(yii::$app->request->get('end_validate_car_time')){
			$query->andFilterWhere([
					'<=',
					'{{oa_inspection}}.`validate_car_time`',
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
					$orderBy = '{{oa_inspection}}.`username` ';
					break;
				default:
					$orderBy = '{{oa_inspection}}.`'.$sortColumn.'` ';
				break;
			}
		}else{
			$orderBy = '{{oa_inspection}}.`id` ';
		}
		$orderBy .= $sortType;
		$data = $query->orderBy($orderBy)->asArray()->all();
		
		$filename = '抽检结果登记列表.csv'; //设置文件名
		$filename = mb_convert_encoding($filename, "GBK", "UTF-8");
		$str = "检验批次编号,审批状态,审批结果,审批意见,车辆品牌,产品型号,计划提车数量,抽检数量,抽检负责人,验车时间,登记时间,登记人\n";
		$approve_status_arr = array(1=>'待审批',2=>'已审批',3=>'已确认');
		$approve_result_arr = array(0=>'',1=>'合格',2=>'不合格');
		foreach ($data as $row){
			$str .= "{$row['id']},{$approve_status_arr[$row['approve_status']]},{$approve_result_arr[$row['approve_result']]},{$row['approve_note']},{$row['car_brand']},{$row['car_model']},{$row['put_car_num']},{$row['inspection_num']},{$row['inspection_director_name']},{$row['validate_car_time']},{$row['add_time']},{$row['oper_user']}"."\n";
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