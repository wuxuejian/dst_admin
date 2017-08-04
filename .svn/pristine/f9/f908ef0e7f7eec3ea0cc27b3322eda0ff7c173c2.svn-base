<?php
/**
 * 
 * @author try
 */
namespace backend\modules\car\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\RbacRole;
use backend\models\ConfigCategory;
class InventoryController extends BaseController
{
	/**
	 * 车辆库存
	 */
    public function actionIndex()
    {		
    	/* if($_SERVER['REQUEST_METHOD'] == 'POST'){

    	} */
    	$searchFormOptions = [];
    	//车辆运营公司
    	$ret_data = $this->_oc();
    	$searchFormOptions['operating_company_id'] = !empty($ret_data['operating_company_id']) ? $ret_data['operating_company_id']:array();
    	return $this->render('index',['searchFormOptions'=>$searchFormOptions]);
    }
    
    
    public function actionGetList(){
    	$returnArr = array('rows'=>array(),'total'=>0);
    	$oc = 0; //默认值(不限大区)，查看所有数据
    	$regional = @$_REQUEST['regional'];
    	if($regional){
    		//当前大区下所有的运营公司
    		$ocs = @$_REQUEST['ocs'];
    		//选择的运营公司
    		$oc = @$_REQUEST['operating_company_id'];
    		$oc = !empty($oc) ? $oc : $ocs;
    	
    	}
    	//当前大区下没有任何公司
    	if($oc ===''){
    		return json_encode($returnArr);
    	}
    	
    	
    	//品牌车型
    	/* 	$brand_id = yii::$app->request->post('car_brand');
    	 $car_model_name = yii::$app->request->post('car_model_name');  */
    	$ret_data = $this->_car_brand_model();
    	$brand_model = $ret_data['brand_model'];
        //echo '<pre>';
        //var_dump($ret_data);exit;
        //var_dump($brand_model);exit;
    	if(empty($brand_model)) return $returnArr;
    	foreach ($brand_model as $v){
    		$arr = explode('-', $v);
    		$brand_id = $arr[0];
    		$car_model_name = $arr[1];
    		//当前车辆库存
    		$query = (new \yii\db\Query())->from('cs_car')->where('car_status=:car_status AND is_del=0',[':car_status'=>'STOCK']);
    		//退车数量
    		$query1 = (new \yii\db\Query())->from('cs_car')->where('car_status=:car_status AND is_del=0',[':car_status'=>'BACK']);
            //经销商数量
            $query2 = (new \yii\db\Query())->from('cs_car')->where('car_status=:car_status AND is_del=0',[':car_status'=>'DEALER']);
            //提车中数量
            $query3 = (new \yii\db\Query())->from('cs_car')->where('car_status=:car_status AND is_del=0',[':car_status'=>'PREPARE']);
    		if($oc !== 0){
    			$query->andWhere("operating_company_id in ({$oc})");
    			$query1->andWhere("operating_company_id in ({$oc})");
                $query2->andWhere("operating_company_id in ({$oc})");
                $query3->andWhere("operating_company_id in ({$oc})");
    			$row = (new \yii\db\Query())->from('cs_operating_company')->where('id=:id AND is_del=0',[':id'=>$oc])->one();
    			$oc_name = is_numeric($oc) ? $row['name'] :'-';
    		}
    		if($brand_id){
    			$query->andWhere("brand_id = {$brand_id}");
    			$query1->andWhere("brand_id = {$brand_id}");
                $query2->andWhere("brand_id = {$brand_id}");
                $query3->andWhere("brand_id = {$brand_id}");
    			$row = (new \yii\db\Query())->from('cs_car_brand')->where('id=:id',[':id'=>$brand_id])->one();
    			$car_brand_name = !empty($row['name']) ? $row['name'] :'';
    		}
    		if($car_model_name){
    			$modelQuery = (new \yii\db\Query())->select('value')->from('cs_config_item')->where('belongs_id=62 AND is_del=0 AND text=:text',[':text'=>$car_model_name]);
    			$query->andWhere(['car_model' => $modelQuery]);
    			$query1->andWhere(['car_model' => $modelQuery]);
                $query2->andWhere(['car_model' => $modelQuery]);
                $query3->andWhere(['car_model' => $modelQuery]);
    		}
    		$inventory_count = $query->count();
    		$back_count = $query1->count();
            $dealer_count = $query2->count();
            $prepare_count = $query3->count();
    		//查询出未填写租金（车辆状态为提车中）的申请
    		$template_now = (new \yii\db\Query())->select('id')->from('oa_process_template')->where('by_business=:by_business',[':by_business'=>'process/car/index'])->one();
    		$result_query = (new \yii\db\Query())->select('by_business_id')->from('oa_approval_result')
    		->where('template_id=:template_id AND event=:event AND event_status=:event_status',
    				[':template_id'=>$template_now['id'],
    				':event'=>'process/car/rent',
    				':event_status'=>0]
    		);
    		//未交车申请的提车需求数
    		$query = (new \yii\db\Query())->select('oa_extract_report.id,car_type,number')->from('oa_extract_report')->where(['oa_extract_report.id'=>$result_query,'oa_extract_report.is_del'=>1,'is_cancel'=>1]);
    		if($oc !== 0){
    			$query->join('LEFT JOIN','cs_admin','cs_admin.id = oa_extract_report.user_id');
    			$query->andWhere("cs_admin.operating_company_id in ({$oc})");
    		}
    		$extract_reports = $query->all();
    		//已进入提车需求数
    		$demand_count = 0;
    		//已备车数
    		$extract_car_count = 0;
    		if($extract_reports)
    		{
    			foreach ($extract_reports as $extract_report)
    			{
    				//指定品牌
    				if($brand_id){
    					$car_type = json_decode($extract_report['car_type'],true);
    					foreach ($car_type as $k=>$v)
    					{
    						if(empty($car_model_name)){                                              //没有指定车型
    							$pattern = "/{$car_brand_name}-/i";
    							if(preg_match($pattern, $k)){
    								$demand_count += $v;
    								$extract_car_query = (new \yii\db\Query())->from('oa_prepare_car')->where(['tc_receipts'=>$extract_report['id'],'is_jiaoche'=>1]);
    								$extract_car_query->join('LEFT JOIN','cs_car','cs_car.plate_number = oa_prepare_car.car_no');
    								$extract_car_query->andWhere('cs_car.is_del=0');
    								$extract_car_query->andWhere("cs_car.brand_id = {$brand_id}");
    								//$extract_car_query->andWhere(['cs_car.car_model' => $modelQuery]);
    								$extract_car_count += $extract_car_query->count();
    							}
    						}elseif($car_model_name && $k == $car_brand_name.'-'.$car_model_name) {   //指定车型
    							$demand_count += $v;
    							$extract_car_query = (new \yii\db\Query())->from('oa_prepare_car')->where(['tc_receipts'=>$extract_report['id'],'is_jiaoche'=>1]);
    							$extract_car_query->join('LEFT JOIN','cs_car','cs_car.plate_number = oa_prepare_car.car_no');
    							$extract_car_query->andWhere('cs_car.is_del=0');
    							$extract_car_query->andWhere("cs_car.brand_id = {$brand_id}");
    							$extract_car_query->andWhere(['cs_car.car_model' => $modelQuery]);
    							$extract_car_count += $extract_car_query->count();
    						}
    	
    					}
    	
    				}else{
    					$demand_count += $extract_report['number'];
    					$extract_car_query = (new \yii\db\Query())->from('oa_prepare_car')->where(['tc_receipts'=>$extract_report['id'],'is_jiaoche'=>1]);
    					$extract_car_count += $extract_car_query->count();
    				}
    			}
    		}
            //锁定库存
            $lock_count = $demand_count-$prepare_count;
    		$result[] = array(           //可用库存   = (库存-(提车需求数-已整备车辆))
    				'inventory_count' => $inventory_count-($demand_count-$extract_car_count) >0 ? $inventory_count-($demand_count-$extract_car_count):0,
    				'demand_count'	   => $demand_count,
    				'extract_car_count'=>$extract_car_count,
    				'car_brand_name' => !empty($brand_id) ? $car_brand_name :'-',
    				'car_model_name' => !empty($car_model_name) ? $car_model_name :'-',
    				'operating_company_name' => !empty($oc) ? $oc_name:'-',
    				'back_count'=>$back_count,
    				'regional'=>$this->_regional($regional),
                    'dealer_count'=>$dealer_count,
                    'prepare_count'=>$prepare_count,
                    'lock_count'=>$lock_count
    		);
    	
    	}
    	/* echo '<pre>';
    	 var_dump($result);exit(); */
    	
    	$returnArr['rows'] = $result;
    	$returnArr['total'] = count($result);
    	return json_encode($returnArr);
    }
    
    
    /**
     * 改装车查询
     */
    public function actionRefit()
    {
    	if($_SERVER['REQUEST_METHOD'] == 'POST')
    	{
    		//改装类型
    		$modified_car_type = yii::$app->request->post('modified_car_type');	
    		//运营公司
    		$oc = yii::$app->request->post('operating_company_id');
    		//品牌车型
    		$brand_id = yii::$app->request->post('car_brand');
    		$car_model_name = yii::$app->request->post('car_model_name');
    		$query= (new \yii\db\Query())->select('plate_number,modified_type,brand_id,car_model,operating_company_id')->from('cs_car')->where('is_del=0');
    		if($oc){
    			$query->andWhere("operating_company_id = {$oc}");
    			$row = (new \yii\db\Query())->from('cs_operating_company')->where('id=:id AND is_del=0',[':id'=>$oc])->one();
    			$oc_name = $row['name'];
    		}
    		if($brand_id){
    			$query->andWhere("brand_id = {$brand_id}");
    			$row = (new \yii\db\Query())->from('cs_car_brand')->where('id=:id',[':id'=>$brand_id])->one();
    			$car_brand_name = !empty($row['name']) ? $row['name'] :'';
    		}
    		if($car_model_name){
    			$modelQuery = (new \yii\db\Query())->select('value')->from('cs_config_item')->where('belongs_id=62 AND is_del=0 AND text=:text',[':text'=>$car_model_name]);
    			$query->andWhere(['car_model' => $modelQuery]);
    		}
    		if($modified_car_type){
    			foreach ($modified_car_type as $v){
    				$query->andWhere(['like','modified_type',$v]);
    			}    			
    		}else{
    				$query->andWhere("modified_type !=''");
    		}
    		$total = $query->count();
    		$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
    		$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
    		$result = $query->offset($pages->offset)->limit($pages->limit)->all();
    		foreach ($result as $key=>$val){
    			$brand_row = (new \yii\db\Query())->select('name')->from('cs_car_brand')->where('id=:id AND is_del=0',[':id'=>$val['brand_id']])->one();
    			$car_model_row = (new \yii\db\Query())->select('text')->from('cs_config_item')->where('value=:value AND is_del=0',[':value'=>$val['car_model']])->one();
    			$oc_row = (new \yii\db\Query())->select('name')->from('cs_operating_company')->where('id=:id AND is_del=0 ',[':id'=>$val['operating_company_id']])->one();
    			
    			$result[$key]['car_brand_name'] = !empty($brand_row) ? $brand_row['name']:'';
    			$result[$key]['car_model_name'] = !empty($car_model_row) ? $car_model_row['text']:'';
    			$result[$key]['oc_name'] = !empty($oc_row) ? $oc_row['name']:'';
    		} 
    		/* echo '<pre>';
    		var_dump($result);exit(); */
    		$returnArr['rows'] = $result;
    		$returnArr['total'] = $total;
    		return json_encode($returnArr);
    		
    		
    	}
    	$searchFormOptions = [];
    	$ret_data = $this->_car_brand_model();
    	$searchFormOptions['car_brand'] = $ret_data['car_brand'];
    	$searchFormOptions['car_model_name'] = $ret_data['car_model_name'];
    	$ret_data = $this->_oc();
    	$searchFormOptions['operating_company_id'] =  $ret_data['operating_company_id'];
    	
    	//改装类型
    	//获取配置数据
    	$configItems = ['modified_car_type'];
    	$configCategoryModel = new ConfigCategory();
    	$config = $configCategoryModel->getCategoryConfig($configItems,'value');
    	if($config['modified_car_type'])
    	{
    		$searchFormOptions['modified_car_type'] = [];
    		$searchFormOptions['modified_car_type'][] = ['value'=>'','text'=>''];
    		foreach($config['modified_car_type'] as $val){
    			$searchFormOptions['modified_car_type'][] = ['value'=>$val['text'],'text'=>$val['text']];
    	
    		}
    	}
    	return $this->render('refit',['searchFormOptions'=>$searchFormOptions]);
    }
    
    
    
    public function actionExportExcel()
    {
    	$json_result = $this->actionGetList();
    	$ret_data = json_decode($json_result,true);
    	$filename = '库存查询.csv'; //设置文件名
    	$str = "大区,运营公司,品牌,车型,已进入提车流程数量,已整备数量,退车中数量,可用库存\n";
    	if($ret_data['rows']){
    		foreach ($ret_data['rows'] as $row){
    			$str .="{$row['regional']},{$row['operating_company_name']},{$row['car_brand_name']},{$row['car_brand_name']},{$row['demand_count']},{$row['extract_car_count']},{$row['back_count']},{$row['inventory_count']}"."\n";
    		}
    	}
    	
    	$str = mb_convert_encoding($str, "GBK", "UTF-8");
    	$this->export_csv($filename,$str); //导出
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
     * 车辆品牌、车型
     * @return array()
     */
    private function _car_brand_model()
    {
    	$searchFormOptions = [];
    	//品牌车型
    	$query = (new \yii\db\Query())->select('brand_id,car_model,cs_config_item.text')->from('cs_car')->groupBy('brand_id,car_model');
    	//1、查询车辆型号
    	$query->join('INNER JOIN','cs_config_item','cs_config_item.value = cs_car.car_model');
    	$query->andWhere("cs_car.car_model != ''");
    	$query->andWhere("cs_car.is_del = 0");
    	//2、查询车辆品牌
    	$r = (new \yii\db\Query())->select('a.*,name')->from(['a'=>$query])->join('LEFT JOIN','cs_car_brand','a.brand_id = cs_car_brand.id')->all();
    	foreach ($r as $val){
    	
    		$brand_type[] = $val['brand_id'].'-'.$val['name'];
    		$car_model[] = $val['brand_id'].'-'.$val['text'];   
    	
    	}
    	$brand_type = array_unique($brand_type);
    	$car_model_name = array_unique($car_model);
    	$searchFormOptions['brand_model'] = $car_model_name; //品牌ID-车型名称
    	
    	if($brand_type){
    		$searchFormOptions['car_brand'][] = array('value'=>'','text'=>'不限');
    		foreach ($brand_type as $v){
    			$arr = explode('-', $v);
    			$searchFormOptions['car_brand'][] = array('value'=>$arr[0],'text'=>$arr[1]);
    		}
    	}
    	if($car_model_name){
    		$searchFormOptions['car_model_name'] = [];
    		//$searchFormOptions['car_model_name'][] = ['value'=>'','text'=>'不限'];
    		foreach ($car_model_name as $v){
    			$arr = explode('-', $v);
    			if(!array_key_exists($arr[0], $searchFormOptions['car_model_name']))
    			{
    				$searchFormOptions['car_model_name'][$arr[0]][] = array('value'=>'','text'=>'不限');
    			}
    			$searchFormOptions['car_model_name'][$arr[0]][] = array('value'=>$arr[1],'text'=>$arr[1]);
    		}
    	}
    	
    	/* echo '<pre>';
    	 var_dump($searchFormOptions['brand_model']);
    	exit(); */
    	return $searchFormOptions;
    	
    }
    
    /**
     * 车辆运营公司    
     * @return array()
     */
    private function _oc()
    {
    	$searchFormOptions = [];
    	//车辆运营公司
    	$oc = (new \yii\db\Query())->from('cs_operating_company')->where('is_del=0')->all();
    	/* if($oc)
    	{
    		$searchFormOptions['operating_company_id'] = [];
    		$searchFormOptions['operating_company_id'][] = ['value'=>'','text'=>'不限'];
    		foreach($oc as $val){
    			$searchFormOptions['operating_company_id'][] = ['value'=>$val['id'],'text'=>$val['name']];
    	
    		}
    	}
    	return $searchFormOptions; */
    	$searchFormOptions['operating_company_id'] = $oc;
    	return $searchFormOptions;
    }
    
    /**
     * 大区  云林说大区是写死的，方志华说就按写死的来  by 2016/12/7 
     */
    private function _regional($regional)
    {
   		 switch ($regional){
				case '1':
					$value = '华南大区';
					break;
				case '2':
					$value = '华北大区';
					break;
				case '3':
					$value = '华东大区';
					break;
				case '4':
					$value = '华中大区';
					break;
				case '5':
					$value = '西南大区';
					break;
				default :
					$value = '-';
					break;
		}
		return $value;		
    }
    
}