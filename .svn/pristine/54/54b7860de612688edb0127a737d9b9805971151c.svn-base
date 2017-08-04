<?php
/**
 * 车辆保养控制器
 * time    2016/10/18 11:37
 * @author pengyl
 */
namespace backend\modules\car\controllers;
use backend\models\MaintainRecord;
use backend\models\MaintainType;
use backend\models\ServiceSite;

use backend\models\AdminRole;

use backend\models\ConfigItem;

use common\classes\CarRealtimeDataAnalysis;
use backend\controllers\BaseController;
use backend\models\Car;
use backend\models\ConfigCategory;
use common\models\Excel;
use common\models\File;
use yii;
use yii\data\Pagination;
use backend\classes\wz;
use backend\models\Admin;

class MaintainRecordController extends BaseController
{
    public function actionIndex()
    {
    	$buttons = $this->getCurrentActionBtn();
    	$config = (new ConfigCategory)->getCategoryConfig(['car_type','INSURANCE_COMPANY','car_model_name'],'value');
    	$insurerCompany = [
    	['value'=>'','text'=>'不限']
    	];
    	if($config['INSURANCE_COMPANY']){
    		foreach($config['INSURANCE_COMPANY'] as $val){
    			$insurerCompany[] = ['value'=>$val['value'],'text'=>$val['text']];
    		}
    	}
    	//查询表单select选项
    	$searchFormOptions = [];
    	if($config['car_model_name']){
    		$searchFormOptions['car_model_name'] = [];
    		$searchFormOptions['car_model_name'][] = ['value'=>'','text'=>'不限'];
    		foreach($config['car_model_name'] as $val){
    			$isexist = false;
    			foreach ($searchFormOptions['car_model_name'] as $obj){	//去重
    				if($obj['value'] == $val['text']){
    					$isexist = true;
    					break;
    				}
    			}
    			if(!$isexist){
    				$searchFormOptions['car_model_name'][] = ['value'=>$val['text'],'text'=>$val['text']];
    			}
    		}
    	}
		 //车辆类型
        if($config['car_type'])
        {
        	$searchFormOptions['car_type'] = [];
        	$searchFormOptions['car_type'][] = ['value'=>'','text'=>'不限'];
        	foreach($config['car_type'] as $val){
        		$searchFormOptions['car_type'][] = ['value'=>$val['value'],'text'=>$val['text']];
        	}
        }
        
    	return $this->render('index',[
    			'buttons'=>$buttons,
    			'config'=>$config,
    			'searchFormOptions'=>$searchFormOptions
    			]);
    }
	//TODO车辆保养分类管理		
	 public function actionMaintainType(){
        $buttons = $this->getCurrentActionBtn();
        $config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY','car_model_name'],'value');
       
        //查询表单select选项
        $searchFormOptions = [];
        if($config['car_model_name']){
            $searchFormOptions['car_model_name'] = [];
            $searchFormOptions['car_model_name'][] = ['value'=>'','text'=>'不限'];
            foreach($config['car_model_name'] as $val){
                $isexist = false;
                foreach ($searchFormOptions['car_model_name'] as $obj){ //去重
                    if($obj['value'] == $val['text']){
                        $isexist = true;
                        break;
                    }
                }
                if(!$isexist){
                    $searchFormOptions['car_model_name'][] = ['value'=>$val['text'],'text'=>$val['text']];
                }
            }
        }
        
        return $this->render('maintain-type',[
                'buttons'=>$buttons,                
                'config'=>$config,
                'searchFormOptions'=>$searchFormOptions
                ]);
    }
	 public function actionGetMaintainTypeList() {
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    
        $query = MaintainType::find()
            ->select([
                '{{%maintain_type}}.id',              
                '{{%maintain_type}}.maintain_type',              
                '{{%maintain_type}}.maintain_des',              
                '{{%maintain_type}}.maintain_des',              
                '{{%config_item}}.text'             
			
            ])
            ->leftJoin('{{%config_item}}','{{%maintain_type}}.car_model_name = {{%config_item}}.value and {{%config_item}}.belongs_id=62')
            ->andWhere(['{{%maintain_type}}.`is_del`'=>0]);
        
        //TODO车型查询条件

        ////查询条件结束
        // $sortColumn = yii::$app->request->get('sort');
        // $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        // $orderBy = '';
        // if($sortColumn){
            // switch ($sortColumn) {
                // case 'transact_ic':
                    // break;
                // default:
                    // $orderBy = '{{%car}}.`'.$sortColumn.'` ';
                    // break;
            // }
        // }else{
            // $orderBy = '{{%car}}.`id` ';
        // }
        
       // $orderBy .= $sortType;
        
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        // $query = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy);
        $query = $query->offset($pages->offset)->limit($pages->limit);
        // echo $query->createCommand()->getRawSql();exit;
        $data = $query->asArray()->all();
       	  
        $returnArr = [];
        $returnArr['rows'] = $data; 
        $returnArr['total'] = $total;
        //echo '<pre>';
        //var_dump($returnArr);
        //die;
        return json_encode($returnArr);
	}
	 /**
     * 添加保养类型记录
     */
    public function actionMaintainAdd()
    {
        if(yii::$app->request->isPost){
           
			// $add_time = yii::$app->request->post('add_time');
			// var_dump(yii::$app->request->post());
			// exit;
			$maintain_type = yii::$app->request->post('maintain_type');
			$maintain_des = yii::$app->request->post('maintain_des');			
			$car_model_name = yii::$app->request->post('car_model_name');
			
			$returnArr = [];
			$returnArr['status'] = false;
			$returnArr['info'] = '';
								
            $model = new MaintainType();
			$formData = yii::$app->request->post();	
			
            $model->load($formData,'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
			
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '添加成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '数据保存失败！';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    foreach($error as $val){
                        $returnArr['info'] .= $val[0];
                    }
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            echo json_encode($returnArr);
            return null;
        }
        //data sbumit end        
		$config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY','car_model_name'],'value');
       
        //查询表单select选项       
        return $this->render('maintain-add',[               
                'config'=>$config
                ]);
    }
	 /**
     * 修改保养分类
     */
    public function actionMaintainEdit(){
        //data submit start
        if(yii::$app->request->isPost){
			//var_dump(yii::$app->request->post());
            $id = yii::$app->request->post('id') or die('param id is required for edit');
            $model = MaintainType::findOne(['id'=>$id]);
            $model or die('record not found');
            
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                //if(floor($model->amount/10000)%2 == 0){
                //    $type = 1;  //1A保
               // }else {
                //    $type = 2;  //2B保
               // }
              //  $model->type = $type;
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '修改成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '数据保存失败！';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    foreach($error as $val){
                        $returnArr['info'] .= $val[0];
                    }
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr);
        }
        //data submit end
        $id = yii::$app->request->get('id');    
  
        if(!$id){
            die('param id is required');
        }
        if($id){
            $model = MaintainType::find()->where(['id' => $id,'is_del'=>0])->one();
        }        
        $model or die('record not found');
		$config = (new ConfigCategory)->getCategoryConfig(['car_type','INSURANCE_COMPANY','car_model_name'],'value');
    	
        return $this->render('maintain-edit',[
                'info'=>$model,
                'id'=>$id,
                'config'=>$config,
                ]);
    }

    //车辆保养记录管理
    public function actionMain(){
        //echo '1231';exit;
        $buttons = $this->getCurrentActionBtn();
        $config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY','car_model_name'],'value');
        $insurerCompany = [
        ['value'=>'','text'=>'不限']
        ];
        if($config['INSURANCE_COMPANY']){
            foreach($config['INSURANCE_COMPANY'] as $val){
                $insurerCompany[] = ['value'=>$val['value'],'text'=>$val['text']];
            }
        }
        //查询表单select选项
        $searchFormOptions = [];
        if($config['car_model_name']){
            $searchFormOptions['car_model_name'] = [];
            $searchFormOptions['car_model_name'][] = ['value'=>'','text'=>'不限'];
            foreach($config['car_model_name'] as $val){
                $isexist = false;
                foreach ($searchFormOptions['car_model_name'] as $obj){ //去重
                    if($obj['value'] == $val['text']){
                        $isexist = true;
                        break;
                    }
                }
                if(!$isexist){
                    $searchFormOptions['car_model_name'][] = ['value'=>$val['value'],'text'=>$val['text']];
                }
            }
        }
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
       // $carId = yii::$app->request->get('carId');
        return $this->render('main',[
                'buttons'=>$buttons,
               // 'carId'=>$carId,
                'config'=>$config,
                'searchFormOptions'=>$searchFormOptions
                ]);
    }

    /**
     * 修改指定车辆保养记录
     */
    public function actionEdit(){
        //data submit start
        if(yii::$app->request->isPost){
			//var_dump(yii::$app->request->post());
            $id = yii::$app->request->post('id') or die('param id is required');
            $model = MaintainRecord::findOne(['id'=>$id]);
            $model or die('record not found');
             //验证保养里程
            $car_id = yii::$app->request->post('car_id');
			$driving_mileage = yii::$app->request->post('driving_mileage');

            $car_driving_mileage2 = MaintainRecord::find()
            ->select(['id','driving_mileage'])
            ->where(['car_id' => $car_id,'is_del'=>0])
            ->asArray()->all();
            //echo '<pre>';
            //var_dump($car_driving_mileage2);exit;
            $driving_mileages = [];
            foreach ($car_driving_mileage2 as $key => $value) {
                //echo '<pre>';
                //var_dump($value['id']);
                //echo '----';
                //var_dump($id);
                $driving_mileages[] = $value['driving_mileage'];
                if($value['id'] == $id) {
                    if((intval($value['driving_mileage']) == $driving_mileage) || !in_array($driving_mileage,$driving_mileages)) {
                        continue;
                    } else {
                        exit('记录重复1！');
                    }
                } else {
                    //echo 'ma';
                    if(intval($value['driving_mileage']) == $driving_mileage) {
                        exit('记录重复2！');
                    }
                }
                
            }
            //echo '<pre>';
            //var_dump($driving_mileages);exit;
            /*if() {
                echo '456';exit('m1');
            }
            e*/
            //exit('记录重复3');
            //$model = new MaintainRecord();
            $add_id = $model->add_id = $_SESSION['backend']['adminInfo']['id'];
            $formData = yii::$app->request->post();
            $formData['add_id'] = $add_id;
           // var_dump($formData);exit;
        
            $model->load($formData,'');
            //echo '<pre>';
            //var_dump($model->load($formData,''));exit;
            //var_dump($driving_mileage);exit;
            //$maintain_type = yii::$app->request->post('maintain_type');
           // $model->load(yii::$app->request->post(),'');

            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                //if(floor($model->amount/10000)%2 == 0){
                //    $type = 1;  //1A保
               // }else {
               //     $type = 2;  //2B保
               // }
                //$model->type = $maintain_type;
				//var_dump(yii::$app->request->post());
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '修改成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '数据保存失败！';
                }
            }else{
				
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    foreach($error as $val){
                        $returnArr['info'] .= $val[0];
                    }
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr);
        }
        //data submit end
        $id = yii::$app->request->get('id');
        //$carId = yii::$app->request->get('carId');
        if(!$id){
            die('param id is required');
        }
        if($id){
            $model = MaintainRecord::find()			
			
			 ->select([
                '{{%maintain_record}}.id',
                '{{%maintain_record}}.in_car_img',
                '{{%maintain_record}}.out_car_img',
                '{{%maintain_record}}.maintain_img',
                '{{%maintain_record}}.car_id',
                '{{%maintain_record}}.add_time',
                '{{%maintain_record}}.driving_mileage',
                '{{%maintain_record}}.maintenance_shop',
                '{{%maintain_record}}.amount',
                '{{%maintain_record}}.type',
                '{{%maintain_record}}.out_time',
                '{{%car}}.`plate_number`',
                '{{%maintain_record}}.`add_id`'
              
               
          
                
            ])
            ->leftJoin('{{%car}}','{{%maintain_record}}.car_id = {{%car}}.id')
            ->where(['{{%maintain_record}}.id' => $id,'{{%maintain_record}}.is_del'=>0])->one();

			
			
       // }else {
        //    $model = MaintainRecord::find()->where(['car_id' => $carId,'is_del'=>0])->orderBy('add_time desc')->one();
        }
       
		//echo '<pre>';
		//var_dump($model->getOldAttributes());exit;
        $model or die('record not found');	
        return $this->render('edit',[
                'row'=>$model->getOldAttributes()
                ]);
    }

	
	
	//2016/1/13 获取保养厂记录
    public function actionGetMaintainServices(){
		$queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
        //$SiteId = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0; //修改时赋值用
        $SiteId = yii::$app->request->get('id');
        $query = ServiceSite::find()
            ->select(['id','site_name'])
            //->where(['is_del'=>0])
			;
          //  var_dump($SiteId);exit;
      //  if ($SiteId == "") {
       //     exit;           
       // }
        //**********************************************

        //********************************************
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        // $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany(true);
        // if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            // $query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        // }

      //  if($carId){
            // 修改时查询赋值
         //   $total = $query->andWhere(['id'=>$carId])->count();
       // }elseif($queryStr){
            // 检索过滤时
        //    $total = $query->andWhere([
          //          'or',
           //         ['like', 'plate_number', $queryStr],
           //         ['like', 'vehicle_dentification_number', $queryStr]
           //     ])
          //      ->count();
       // }else{
            // 默认查询
           // $total = $query->count();
      //  }

        if($SiteId){
            // 修改时查询赋值
            $total = $query->andWhere(['id'=>$SiteId])->count();
        }elseif($queryStr){
            // 检索过滤时
            $total = $query->andWhere([
                    'or',
                    ['like', 'site_name', $queryStr]
                    
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
		
		
		
		
		
    	//$connection = yii::$app->db;
		    	//$id = yii::$app->request->get('id');
		//var_dump($id);exit;
		//if (!isset($id) || $id == "") {
		//	exit;			
		//}
    	//$sql = 'SELECT oa_service_site.id,oa_service_site.site_name					   
		//		FROM oa_service_site';
		
		//$data = $connection->createCommand($sql)->queryAll();
				
		// var_dump($data);exit;
    	//echo json_encode($data);
    }
    /**
     * 添加保养记录
     */
    public function actionAdd()
    {
        if(yii::$app->request->isPost){
			//var_dump(yii::$app->request->post());exit;
            $car_id = yii::$app->request->post('car_id');
			$add_time = yii::$app->request->post('add_time');
			$out_time = yii::$app->request->post('out_time');
			$maintenance_shop = yii::$app->request->post('maintenance_shop');
			$amount = yii::$app->request->post('amount');
           
            //var_dump($amount);
            //验证保养金额 整数（可以保留两位小数）
            if(!is_numeric($amount)) {
                exit;
            }
            //$amount = round($amount,2);
            //echo '654321';
            //exit;
			$driving_mileage = yii::$app->request->post('driving_mileage');
            
            //验证保养里程
            $car_driving_mileage2 = MaintainRecord::find()
            ->select(['driving_mileage'])
            ->where(['car_id' => $car_id,'is_del'=>0])
            ->asArray()->all();
            foreach ($car_driving_mileage2 as $key => $value) {
               if(intval($value['driving_mileage']) == $driving_mileage) {
                exit;
               }
            }
            //$model = new MaintainRecord();
            
            //var_dump($add_id);exit;


			$maintain_type = yii::$app->request->post('maintain_type');
			// if(floor($amount/10000)%2 == 0){
				// $type = 1;	//1A保
			// }else {
				// $type = 2;	//2B保
			// }
			
			$returnArr = [];
			$returnArr['status'] = false;
			$returnArr['info'] = '';
			
			//按时间获取车辆里程数
			// if(!$driving_mileage){
				// $car = yii::$app->db->createCommand(
						// "select vehicle_dentification_number from cs_car where is_del=0 and id=".$car_id)->queryOne();
				// $carVin = $car['vehicle_dentification_number'];
				
				// $startTimeStamp = strtotime($add_time);
				// if(date('Ym',$startTimeStamp) == date('Ym')){
					//$connection = yii::$app->db1;//本地数据库
				// }else{
					// $connection = yii::$app->db2;//备份数据库
				// }
				// $tables = $connection->createCommand('show tables')->queryAll();
				// if(!$tables){
					// $returnArr['status'] = false;
					// $returnArr['info'] = '不存在该数据表';
					// return json_encode($returnArr);
				// }
				// $tables = array_column($tables,'Tables_in_car_monidata');
				// $nowTable = 'cs_tcp_car_history_data_'.date('Ym',$startTimeStamp).'_'.substr($carVin,-1);
				
				// if(array_search($nowTable,$tables) === false){
					//不存在该数据表
					// $returnArr['status'] = false;
					// $returnArr['info'] = '不存在该数据表1';
					// return json_encode($returnArr);
				// }
				// $moniDataItem = $connection->createCommand("select data_hex from {$nowTable} where car_vin='{$carVin}' and collection_datetime>={$startTimeStamp} limit 1")->queryOne();
				
				// $dataAnalysisObj = new CarRealtimeDataAnalysis($moniDataItem['data_hex']);
				// $realtimeData = $dataAnalysisObj->getRealtimeData();
				// if($realtimeData){
					// $driving_mileage = $realtimeData['total_driving_mileage'];
				// }else {
					// $driving_mileage = 0;
				// }
				//按时间获取车辆里程数结束
			// }
			
            $model = new MaintainRecord();
            $add_id = $model->add_id = $_SESSION['backend']['adminInfo']['id'];
			$formData = yii::$app->request->post();
            $formData['add_id'] = $add_id;
        
            $model->load($formData,'');
			//var_dump($formData);exit;
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
				$model->type = $maintain_type;
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '添加成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '数据保存失败！';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    foreach($error as $val){
                        $returnArr['info'] .= $val[0];
                    }
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            echo json_encode($returnArr);
            return null;
        }
        //data sbumit end
        //$carId = yii::$app->request->get('carId') or die('param carId is required');
        $config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY']);
        $config['INSURANCE_COMPANY'] = array_values($config['INSURANCE_COMPANY']);
        //echo '<pre>';
        //var_dump($config);exit;
        return $this->render('add',[
                //'carId'=>$carId,
                'config'=>$config
                ]);
    }
   
    /**
     * 获取列表
     */
    public function actionGetList()
    {
		//echo '123';exit;
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = Car::find()
            ->select([
                '{{%car}}.`id`',
                '{{%car}}.`plate_number`',
                '{{%car}}.`vehicle_dentification_number`',
                '{{%car}}.`engine_number`',
                '{{%car}}.`car_status`',
                'maintain_time'=>'{{%maintain_record}}.`add_time`',
                'maintain_driving_mileage'=>'{{%maintain_record}}.`driving_mileage`',
                'maintain_maintenance_shop'=>'{{%maintain_record}}.maintenance_shop',
                'maintain_type'=>'{{%maintain_record}}.type',
                'maintain_amount'=>'{{%maintain_record}}.amount',
                
            ])
            ->leftJoin('(select * from (select * from cs_maintain_record where is_del=0 order by add_time desc) temp1 GROUP BY car_id) as {{%maintain_record}}','{{%car}}.id = {{%maintain_record}}.car_id')
            ->andWhere(['{{%car}}.`is_del`'=>0]);

        ////其他查询条件
        // $car_no = yii::$app->request->get('plate_number');
        // if($car_no)
        // {
            // $query->andFilterWhere([
                    // 'or',
                    // ['like','{{%car}}.plate_number',$car_no],
                    // ['like','{{%car}}.vehicle_dentification_number',$car_no],
                    // ['like','{{%car}}.engine_number',$car_no]
                // ]);
        // }
        //按归属客户查
        // if(yii::$app->request->get('customer')){
            // $tdata = explode("_",yii::$app->request->get('customer'));
            // if($tdata[0] == 'status'){
                // $query->andWhere(['=','{{%car}}.`car_status`',$tdata[1]]);
            // }else{
                // $query->leftJoin('{{%car_let_record}}', '{{%car}}.`id` = {{%car_let_record}}.`car_id` and ({{%car_let_record}}.`back_time` > '.time().' or {{%car_let_record}}.`back_time` = 0)');
                // if($tdata[0] == 'company'){
                    // $query->andWhere(['=','{{%car_let_record}}.`cCustomer_id`',$tdata[1]]);
                // }else {
                    // $query->andWhere(['=','{{%car_let_record}}.`pCustomer_id`',$tdata[1]]);
                // }
            // }
        // }
        
        // $type = intval(yii::$app->request->get('type'));
        // if($type){
            // $query->andWhere(['=','{{%maintain_record}}.`type`',$type]);
        // }
        // if(yii::$app->request->get('start_add_time')){
            // $query->andFilterWhere(['>=','{{%maintain_record}}.`add_time`',strtotime(yii::$app->request->get('start_add_time'))]);
        // }
        // if(yii::$app->request->get('end_add_time')){
            // $query->andFilterWhere(['<=','{{%maintain_record}}.`add_time`',strtotime(yii::$app->request->get('end_add_time'))]);
        // }
        // $maintenance_shop = intval(yii::$app->request->get('maintenance_shop'));
        // if($maintenance_shop){
            // $query->andWhere(['=','{{%maintain_record}}.`maintenance_shop`',$maintenance_shop]);
        // }
        // $next_type = intval(yii::$app->request->get('next_type'));
        // if($next_type){
            // $query->andWhere(['=','{{%maintain_record}}.`type`',$next_type==1?2:1]);
        // }

        ////查询条件结束
        // $sortColumn = yii::$app->request->get('sort');
        // $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        // $orderBy = '';
        // if($sortColumn){
            // switch ($sortColumn) {
                // case 'transact_ic':
                    // break;
                // default:
                    // $orderBy = '{{%car}}.`'.$sortColumn.'` ';
                    // break;
            // }
        // }else{
            // $orderBy = '{{%car}}.`id` ';
        // }
       
        // $orderBy .= $sortType;
        
        $total = $query->groupBy('{{%car}}.`id`')->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $query = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy);
//         echo $query->createCommand()->getRawSql();exit;
        $data = $query->asArray()->all();
		
        //加载归属客户    
        // $connection = yii::$app->db;
        // $configItems = ['car_status'];
        // $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
		
        // foreach ($data as $index=>$row){
            // $query = $connection->createCommand(
                // "select cCustomer_id,pCustomer_id,cs_customer_company.company_name,cs_customer_personal.id_name
                 // from cs_car_let_record
                // left join cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id 
                // left join cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
                 // where (back_time>".time()." or back_time=0) and car_id=".$row['id']
            // );
            // $customer = $query->queryOne();
			
            // if($customer){
                // if($customer['company_name']){
                    // $data[$index]['customer_name'] = $customer['company_name'];
                // }else if($customer['id_name']){
                    // $data[$index]['customer_name'] = $customer['id_name'];
                // }
            // }else {
                // $data[$index]['customer_name'] = $config['car_status'][$row['car_status']]['text'];
            // }
            
            //加载当前里程
            // $realtime_data = yii::$app->db1->createCommand(
                // "select * from cs_tcp_car_realtime_data where car_vin='".$row['vehicle_dentification_number']."'"
            // )->queryOne();
			
            
            // $data[$index]['total_driving_mileage'] = $realtime_data['total_driving_mileage'];
        // }
        $returnArr = [];
        $returnArr['rows'] = $data; 
        $returnArr['total'] = $total;
        //echo '<pre>';
        //var_dump($returnArr);
        //die;
		
        return json_encode($returnArr);
    }

	//2016/12/28 根据车id获取车型保养类别
    public function actionGetTypeByCarId(){
    	$connection = yii::$app->db;
		    	$id = yii::$app->request->get('id');
		//var_dump($id);exit;
		if (!isset($id) || $id == "") {
			exit;			
		}
		//,cs_car.id as cid,car_model_name 
    	$sql = 'select cs_maintain_type.id, cs_maintain_type.maintain_type				
				from cs_maintain_type
				left join cs_car on cs_car.car_model=cs_maintain_type.car_model_name
				where cs_maintain_type.is_del=0 AND cs_car.id='.$id;		
		
		$data = $connection->createCommand($sql)->queryAll();
				
		// var_dump($data);exit;
    	echo json_encode($data);
    }
    /**
     * 获取保养列表type
     */
    public function actionGetMainList()
    {
        //echo '456';exit;
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        //$carId = yii::$app->request->get('carId');
        $plate_number = yii::$app->request->get('plate_number');
        $reg_name = yii::$app->request->get('reg_name');
        $maintain_type = yii::$app->request->get('maintain_type');
        $maintenance_shop = trim(yii::$app->request->get('maintenance_shop'));
        //var_dump($maintenance_shop);exit;
        //$id_ms = ServiceSite::find()->select(['id'])->andWhere(['site_name'=>$maintenance_shop])->asArray()->one();
        //var_dump($id_ms['id']);exit;
        $query = MaintainRecord::find()
            ->select([
                '{{%maintain_record}}.*',
                '{{%car}}.plate_number',
                '{{%car}}.car_model as car_type',
                '{{%maintain_type}}.maintain_type as type',
                'oa_service_site.site_name',
                '{{%admin}}.name'
            ])
            ->leftJoin('{{%car}}','{{%maintain_record}}.car_id = {{%car}}.id')
            ->leftJoin('oa_service_site','oa_service_site.id = {{%maintain_record}}.maintenance_shop')
            ->leftJoin('{{%maintain_type}}','{{%maintain_type}}.id = {{%maintain_record}}.type')
            ->leftJoin('{{%admin}}','{{%admin}}.id = {{%maintain_record}}.add_id')
            ->andWhere([
			//'{{%maintain_record}}.`car_id`'=>$carId,
			'{{%maintain_record}}.`is_del`'=>0]);

        //按归属客户查
        // if(yii::$app->request->get('customer')){
            // $tdata = explode("_",yii::$app->request->get('customer'));
            // if($tdata[0] == 'status'){
                // $query->andWhere(['=','{{%car}}.`car_status`',$tdata[1]]);
            // }else{
                // $query->leftJoin('{{%car_let_record}}', '{{%car}}.`id` = {{%car_let_record}}.`car_id` and ({{%car_let_record}}.`back_time` > '.time().' or {{%car_let_record}}.`back_time` = 0)');
                // if($tdata[0] == 'company'){
                    // $query->andWhere(['=','{{%car_let_record}}.`cCustomer_id`',$tdata[1]]);
                   
                // }else {
                    // $query->andWhere(['=','{{%car_let_record}}.`pCustomer_id`',$tdata[1]]);
                // }
            // }
        // }
        
        // $type = intval(yii::$app->request->get('type'));
        // if($type){
            // $query->andWhere(['=','{{%maintain_record}}.`type`',$type]);
        // }
        // if(yii::$app->request->get('start_add_time')){
            // $query->andFilterWhere(['>=','{{%maintain_record}}.`add_time`',strtotime(yii::$app->request->get('start_add_time'))]);
        // }
        // if(yii::$app->request->get('end_add_time')){
            // $query->andFilterWhere(['<=','{{%maintain_record}}.`add_time`',strtotime(yii::$app->request->get('end_add_time'))]);
        // }
        // $maintenance_shop = intval(yii::$app->request->get('maintenance_shop'));
        // if($maintenance_shop){
            // $query->andWhere(['=','{{%maintain_record}}.`maintenance_shop`',$maintenance_shop]);
        // }

        ////查询条件结束
         $sortColumn = yii::$app->request->get('sort');
         $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
         $orderBy = '';
         if($sortColumn){
             switch ($sortColumn) {
                 case 'transact_ic':
                     break;
                 default:
                     $orderBy = '{{%car}}.`'.$sortColumn.'` ';
                     break;
         }
         }else{
             $orderBy = '{{%maintain_record}}.`id` ';
         }
        
         $orderBy .= $sortType;
         //var_dump($orderBy);exit;
        if($plate_number){
            $query->andFilterWhere([
                    'like',
                    '{{%car}}.plate_number',
                    $plate_number
                    ]);
        }
        if($reg_name){
            //echo '123';exit;
            $query->andFilterWhere([
                    'like',
                    '{{%admin}}.name',
                    $reg_name
                    ]);
        }
        //保养类型查询
        if($maintain_type){
            //echo '123';exit;
            $query->andFilterWhere([
                    'like',
                    '{{%maintain_record}}.type',
                    $maintain_type
                    ]);
        }
        //保养厂
       /* if($id_ms['id']){
            //echo '123';exit;
            $query->andFilterWhere([
                    '=',
                    '{{%maintain_record}}.maintenance_shop',
                    $id_ms['id']
                    ]);
        }*/
        if($maintenance_shop){
            //echo '123';exit;
            $query->andFilterWhere([
            'like',
           'oa_service_site.site_name',
            $maintenance_shop
            ]);
        }
        //保养时间
        $start_add_time= yii::$app->request->get('start_add_time');
       // var_dump($start_add_time);exit;
        if($start_add_time)
        {
            $query->andWhere('{{%maintain_record}}.add_time >=:start_add_time',[':start_add_time'=>$start_add_time]);
        }
        
        $end_add_time= yii::$app->request->get('end_add_time');
        if($end_add_time)
        {
            $query->andWhere('{{%maintain_record}}.add_time <=:end_add_time',[':end_add_time'=>$end_add_time]);
        }


        $query->andFilterWhere(['=','{{%car}}.`operating_company_id`',yii::$app->request->get('operating_company_id')]);
       /* if($car_type){
            $query->andFilterWhere([
                    'like',
                    '{{%car}}.car_model',
                    $car_type
                    ]);
        }*/
        //按车型名称查
        if(yii::$app->request->get('car_model')){
           /* $car_model_query = ConfigItem::find()->select('value')
                    ->andWhere(['is_del'=>0,'belongs_id'=>62,'text'=>yii::$app->request->get('car_model')]);
            $car_models = $car_model_query->asArray()->all();
            $car_models_s = array();
            foreach($car_models as $item){
                array_push($car_models_s, $item['value']);
            }*/
            //$query->andWhere(['in','{{%car}}.`car_model`',$car_models_s]);
            $query->andWhere(['in','{{%car}}.`car_model`',yii::$app->request->get('car_model')]);
        }

        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $query = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy);
        // echo $query->createCommand()->getRawSql();exit;
        $data = $query->asArray()->all();
        
        foreach($data as $key => $val) {
           
             $add_ids = Admin::find()
            ->select(['name'])
            ->where(['id'=>$val['add_id']])
            ->asArray()->one();
            
            $data[$key]['reg_name'] = $add_ids['name'];
            

        }
        

        //var_dump($reg_name);exit;
        //echo '<pre>';
            //var_dump($data);exit;

       /* foreach($data as $key => $value){
            //$value
            echo '<pre>';
            var_dump($value['car_type']);exit;
        }*/
       // 加载归属客户    
        $connection = yii::$app->db;
        $configItems = ['car_status'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');

        foreach ($data as $index=>$row){
            $query = $connection->createCommand(
                "select cCustomer_id,pCustomer_id,cs_customer_company.company_name,cs_customer_personal.id_name
                 from cs_car_let_record
                left join cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id 
                left join cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
                 where (back_time>".time()." or back_time=0) and car_id=".$row['car_id']
            );
            $customer = $query->queryOne();
            if($customer){
                if($customer['company_name']){
                    $data[$index]['customer_name'] = $customer['company_name'];
                }else if($customer['id_name']){
                    $data[$index]['customer_name'] = $customer['id_name'];
                }
            }else {
                $data[$index]['customer_name'] = $config['car_status'][$row['car_status']]['text'];
            }
        }
        $returnArr = [];
        $returnArr['rows'] = $data; 
        $returnArr['total'] = $total;
        //echo '<pre>';
        //var_dump($returnArr);
        //die;
        return json_encode($returnArr);
    }
    
	//查看保养记录详情
    public function actionScan(){
    	$id = yii::$app->request->get('id') or die('param id is required');
    	
    	$connection = yii::$app->db;
    	$record = $connection->createCommand('
			SELECT m.id,c.plate_number,m.add_time,m.out_time,
				   t.maintain_type,m.driving_mileage,
				   m.amount,
				   s.site_name,
				   m.in_car_img,m.out_car_img,m.maintain_img
			FROM cs_maintain_record AS m
			LEFT JOIN cs_car AS c ON c.id=m.car_id
			LEFT JOIN cs_maintain_type AS t ON t.id=m.type
			LEFT JOIN oa_service_site AS s ON s.id=m.maintenance_shop
			where m.id='.$id)->queryOne();

    	return $this->render('scan',[
    			'result'=>$record
    			]);
    }
	
//查看详情
    public function actionMaintainScan(){
    	$id = yii::$app->request->get('id') or die('param id is required');
    	
    	$connection = yii::$app->db;
    	$car_back = $connection->createCommand('select a.*,b.company_name,c.id_name from cs_car_back a
            left join cs_customer_company b on a.c_customer_id=b.id
            left join cs_customer_personal c on a.`p_customer_id`=c.id where a.id='.$id)->queryOne();

        if($car_back['car_ids']){
            $car_back['cars'] = $connection->createCommand('select plate_number from cs_car where id in('.$car_back['car_ids'].')')->queryAll();   
         }else {
            $data['cars']= array();
         }
         if($car_back['storage_car_ids']){
            $car_back['storage_cars'] = $connection->createCommand('select plate_number from cs_car where id in('.$car_back['storage_car_ids'].')')->queryAll();   
         }else {
            $data['storage_cars']= array();
         }

    	// print_r($car_back);
     //    exit;
    	return $this->render('scan',[
    			'obj'=>$car_back
    			]);
    }
	/**
     * 删除保养类型
     */
    public function actionMaintainRemove()
    {
		//echo "hi";
    	$id = yii::$app->request->get('id') or die('param id is required');
    	$model = MaintainType::findOne(['id'=>$id]);
    	$model or die('record not found');
    	$returnArr = [];
    	$returnArr['status'] = true;
    	$returnArr['info'] = '';
    	if(MaintainType::updateAll(['is_del'=>1],['id'=>$id])){
    		$returnArr['status'] = true;
    		$returnArr['info'] = '记录删除成功！';
    	}else{
    		$returnArr['status'] = false;
    		$returnArr['info'] = '记录删除失败！';
    	}
    	echo json_encode($returnArr);
    }
    
	/**
     * 删除保养记录
     */
    public function actionRemove()
    {
    	$id = yii::$app->request->get('id') or die('param id is required');
    	$model = MaintainRecord::findOne(['id'=>$id]);
    	$model or die('record not found');
    	$returnArr = [];
    	$returnArr['status'] = true;
    	$returnArr['info'] = '';
    	if(MaintainRecord::updateAll(['is_del'=>1],['id'=>$id])){
    		$returnArr['status'] = true;
    		$returnArr['info'] = '记录删除成功！';
    	}else{
    		$returnArr['status'] = false;
    		$returnArr['info'] = '记录删除失败！';
    	}
    	echo json_encode($returnArr);
    }
    
    /**
     * 导出所选择出险理赔记录
     */
    public function actionExportChoose()
    {
    	$ids = yii::$app->request->get('id') or die('param id is requried');
    	if(!$ids){
    		die('no data to export!');
    	}
    	$ids = substr($ids, 0, strlen($ids)-1);
    	
    	$query = Car::find()
    	->select([
    			'{{%car}}.`id`',
    			'{{%car}}.`plate_number`',
    			'{{%car}}.`vehicle_dentification_number`',
    			'{{%car}}.`engine_number`',
    			'{{%car}}.`car_status`',
    			'maintain_time'=>'{{%maintain_record}}.`add_time`',
    			'maintain_driving_mileage'=>'{{%maintain_record}}.`driving_mileage`',
    			'maintain_maintenance_shop'=>'{{%maintain_record}}.maintenance_shop',
    			'maintain_type'=>'{{%maintain_record}}.type',
    			'maintain_amount'=>'{{%maintain_record}}.amount',
    	
    			])
    			->leftJoin('(select * from (select * from cs_maintain_record order by add_time desc) temp1 GROUP BY car_id) as {{%maintain_record}}','{{%car}}.id = {{%maintain_record}}.car_id')
    			->andWhere(['{{%car}}.`is_del`'=>0])
    			->andWhere(['in','{{%car}}.`id`',explode(',',$ids)]);
    	$data = $query->asArray()->all();
        //加载归属客户    
        $connection = yii::$app->db;
        $configItems = ['car_status'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
        foreach ($data as $index=>$row){
            $query = $connection->createCommand(
                "select cCustomer_id,pCustomer_id,cs_customer_company.company_name,cs_customer_personal.id_name
                 from cs_car_let_record
                left join cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id 
                left join cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
                 where (back_time>".time()." or back_time=0) and car_id=".$row['id']
            );
            $customer = $query->queryOne();
            if($customer){
                if($customer['company_name']){
                    $data[$index]['customer_name'] = $customer['company_name'];
                }else if($customer['id_name']){
                    $data[$index]['customer_name'] = $customer['id_name'];
                }
            }else {
                $data[$index]['customer_name'] = $config['car_status'][$row['car_status']]['text'];
            }
            
            //加载当前里程
            $realtime_data = yii::$app->db1->createCommand(
                "select * from cs_tcp_car_realtime_data where car_vin='".$row['vehicle_dentification_number']."'"
            )->queryOne();
            
            $data[$index]['total_driving_mileage'] = $realtime_data['total_driving_mileage'];
        }
        
        $filename = '车辆保养记录.csv'; //设置文件名
        $str = "车牌号,车架号,发动机号,归属客户,上次修改时间,上次保养里程,上次保养厂,上次保养类型,上次保养费用,当前总里程,下次保养里程,剩余里程,下次类型\n";
        
        foreach ($data as $row){
        	$maintain_types = array('A保','B保');
        	$maintain_type = $row['maintain_type']?$maintain_types[$row['maintain_type']-1]:'';
        	$next_maintain_driving_mileage = ceil($row['maintain_driving_mileage']/10000)*10000;
        	$overplus_maintain_driving_mileage = $next_maintain_driving_mileage - $row['total_driving_mileage'];
        	$next_maintain_type = '';
        	if($row['maintain_type'] == 1){
        		$next_maintain_type = 'B保';
        	}else if($row['maintain_type'] == 2){
        		$next_maintain_type = 'A保';
        	}
        	$str .= "{$row['plate_number']},{$row['vehicle_dentification_number']},{$row['engine_number']},{$row['customer_name']},{$row['maintain_time']},{$row['maintain_driving_mileage']},{$row['maintain_maintenance_shop']},{$maintain_type},{$row['maintain_amount']},{$row['total_driving_mileage']},{$next_maintain_driving_mileage},{$overplus_maintain_driving_mileage},{$next_maintain_type}"."\n";
        	
        }
        $str = mb_convert_encoding($str, "GBK", "UTF-8");
        $this->export_csv($filename,$str); //导出
    }
    
    /**
     * 导出保养列表
     */
    public function actionExportWidthCondition()
    {
    	$query = Car::find()
            ->select([
                '{{%car}}.`id`',
                '{{%car}}.`plate_number`',
                '{{%car}}.`vehicle_dentification_number`',
                '{{%car}}.`engine_number`',
                '{{%car}}.`car_status`',
                'maintain_time'=>'{{%maintain_record}}.`add_time`',
                'maintain_driving_mileage'=>'{{%maintain_record}}.`driving_mileage`',
                'maintain_maintenance_shop'=>'{{%maintain_record}}.maintenance_shop',
                'maintain_type'=>'{{%maintain_record}}.type',
                'maintain_amount'=>'{{%maintain_record}}.amount',
                
            ])
            ->leftJoin('(select * from (select * from cs_maintain_record order by add_time desc) temp1 GROUP BY car_id) as {{%maintain_record}}','{{%car}}.id = {{%maintain_record}}.car_id')
            ->andWhere(['{{%car}}.`is_del`'=>0]);

        ////其他查询条件
        $car_no = yii::$app->request->get('plate_number');
        if($car_no)
        {
            $query->andFilterWhere([
                    'or',
                    ['like','{{%car}}.plate_number',$car_no],
                    ['like','{{%car}}.vehicle_dentification_number',$car_no],
                    ['like','{{%car}}.engine_number',$car_no]
                ]);
        }
        //按归属客户查
        if(yii::$app->request->get('customer')){
            $tdata = explode("_",yii::$app->request->get('customer'));
            if($tdata[0] == 'status'){
                $query->andWhere(['=','{{%car}}.`car_status`',$tdata[1]]);
            }else{
                $query->leftJoin('{{%car_let_record}}', '{{%car}}.`id` = {{%car_let_record}}.`car_id` and ({{%car_let_record}}.`back_time` > '.time().' or {{%car_let_record}}.`back_time` = 0)');
                if($tdata[0] == 'company'){
                    $query->andWhere(['=','{{%car_let_record}}.`cCustomer_id`',$tdata[1]]);
                }else {
                    $query->andWhere(['=','{{%car_let_record}}.`pCustomer_id`',$tdata[1]]);
                }
            }
        }
        
        $type = intval(yii::$app->request->get('type'));
        if($type){
            $query->andWhere(['=','{{%maintain_record}}.`type`',$type]);
        }
        if(yii::$app->request->get('start_add_time')){
            $query->andFilterWhere(['>=','{{%maintain_record}}.`add_time`',strtotime(yii::$app->request->get('start_add_time'))]);
        }
        if(yii::$app->request->get('end_add_time')){
            $query->andFilterWhere(['<=','{{%maintain_record}}.`add_time`',strtotime(yii::$app->request->get('end_add_time'))]);
        }
        $maintenance_shop = intval(yii::$app->request->get('maintenance_shop'));
        if($maintenance_shop){
            $query->andWhere(['=','{{%maintain_record}}.`maintenance_shop`',$maintenance_shop]);
        }
        $next_type = intval(yii::$app->request->get('next_type'));
        if($next_type){
            $query->andWhere(['=','{{%maintain_record}}.`type`',$next_type==1?2:1]);
        }
        // echo $query->createCommand()->getRawSql();exit;
        //查询条件结束

    	$data = $query->asArray()->all();
    	$filename = '车辆保养记录.csv'; //设置文件名
    	$str = "车牌号,车架号,发动机号,上次修改时间,上次保养里程,上次保养厂,上次保养类型,上次保养费用,下次保养里程,剩余里程,下次类型\n";
        
    	foreach ($data as $row){
            $maintain_types = array('A保','B保');
            $maintain_type = $row['maintain_type']?$maintain_types[$row['maintain_type']-1]:'';
            $next_maintain_driving_mileage = ceil($row['maintain_driving_mileage']/10000)*10000;
            $overplus_maintain_driving_mileage = $next_maintain_driving_mileage - $row['maintain_driving_mileage'];
            $next_maintain_type = '';
            if($row['maintain_type'] == 1){
                $next_maintain_type = 'B保';
            }else if($row['maintain_type'] == 2){
                $next_maintain_type = 'A保';
            }
    		$str .= "{$row['plate_number']},{$row['vehicle_dentification_number']},{$row['engine_number']},{$row['maintain_time']},{$row['maintain_driving_mileage']},{$row['maintain_maintenance_shop']},{$maintain_type},{$row['maintain_amount']},{$next_maintain_driving_mileage},{$overplus_maintain_driving_mileage},{$next_maintain_type}"."\n";
    	}
    	$str = mb_convert_encoding($str, "GBK", "UTF-8");
    	$this->export_csv($filename,$str); //导出
    }
    
    //export-choose
    
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

    public function actionCheck1() {
        $car_ids = MaintainRecord::find()
        ->select(['id','car_id'])
        ->asArray()->all();
        //var_dump($car_ids);exit;
        $car_ids_arr = [];
        foreach ($car_ids as $key1 => $value1) {
            # code...
            $car_ids_arr[] = $value1['car_id'];
        }
        //$car_ids = array('123','456');
        //$car_ids = '123';
        return  json_encode($car_ids_arr);

    }

    public function actionCheck2() {
        
        //$connection = yii::$app->db;
        $id = yii::$app->request->post('id');
        $car_driving_mileage = MaintainRecord::find()
        ->select(['driving_mileage'])
        ->where(['car_id' => $id,'is_del'=>0])
        ->asArray()->all();
        /*if (!isset($id) || $id == "") {
            exit;           
        }*/
    
        return json_encode($car_driving_mileage);

    }
    //车辆类型与保养类型联动
    public function actionCheck3() {
        //$configItems = ['car_model_name'];
        //$config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
        

        $car_model_name = yii::$app->request->post('car_model_name');
        
        /*$c_m_arr = MaintainType::find()
        ->select(['id','maintain_type'])
        ->where(['car_model_name' => $car_model_name,'is_del'=>0])
        ->asArray()->all();
        echo '<pre>';
        var_dump($car_model_name);
        var_dump($c_m_arr);exit;*/


        $car_model_arr = MaintainType::find()
        ->select(['id','maintain_type'])
        ->where(['car_model_name' => $car_model_name,'is_del'=>0])
        ->asArray()->all();

        return json_encode($car_model_arr);
    }
}