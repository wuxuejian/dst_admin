<?php
/**
 * 公务车派车记录控制器
 * @author 
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
use backend\models\CarOfficeRegister;

class OfficeCarRecordController extends BaseController
{
	public function actionIndex()
	{	
		
		$buttons = $this->getCurrentActionBtn();
		
		return $this->render('index',[
            'buttons'=>$buttons,
            
        ]);
	}

	//列表显示
	public function actionGetList()
	{	
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
    	$query->andFilterWhere(['<>','a.`username_id`',0]);
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
        
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("e.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
    	//echo 'h2';exit;
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
    		if($car_mod['return_distance'] != 0) {
    			$return_distance = $car_mod['return_distance'];
    			$use_distance = $return_distance-$total_distance;
    			$data[$key]['use_distance']=$use_distance;
    		}
    		//var_dump($use_distance);exit;
    		//2、计算用车时间
    		$return_time = $car_mod['return_time'];
    		$start_time = $car_mod['start_time'];
    		//$start_time = date("d",$start_time);
    		//var_dump($start_time);exit;
    		//$use_time = date('Y-m-d H:i:s',$return_time-$start_time);
    		//$use_time = date('h',$return_time-$start_time);
    		if($car_mod['return_time'] != 0) {

	    		$use_time = strtotime($return_time)-strtotime($start_time);
	    		//echo strtotime($return_time).' ';
	    		//echo $return_time.'  '.$start_time;
	    		//var_dump($use_time);exit;
	    		//$start_time = date('h',$start_time);
	    		//$return_time = date('h',$return_time);
	    		$use_time = round($use_time/(60*60),1);
	    		//var_dump($use_time);exit;
	    		//$use_time = round((date('d',$use_time)*24*60*60+date('s',$use_time)+date('m',$use_time)*60+date('h',$use_time)*60*60)/3600,0);
	    		//;
	    		$data[$key]['use_time']=$use_time;
    		}
    		//var_dump($start_time);exit;
    		//3、车辆类型
    		$car_m = $car_mod['car_model'];
    		$data[$key]['car_model'] = $config['car_model_name'][$car_m]['text'];
    	   //echo '<pre>';
           //var_dump($car_mod);exit;
            //还车后，用车人 用车部门 登记人为空
            /*if($data[$key]['is_return'] == 1) {
                $data[$key]['username'] = '';
                $data[$key]['department_name'] = '';
                $data[$key]['reg_name'] = '';
            }*/


    	}
    	//echo '<pre>';
    	//var_dump($data[$key]['car_model']);exit;
    	//var_dump($data);exit;
    	$returnArr = [];
    	$returnArr['rows'] = $data;
    	//echo '<pre>';
    	//var_dump($returnArr['rows']);exit;
    	$returnArr['total'] = $total;
    	//exit($query->createCommand()->sql);
    	return json_encode($returnArr);

    	
	}

	//查看详情
	public function actionScan()
	{	
		$id = yii::$app->request->get('id') or die('param id is required');
		//查询详细信息
		$carofficeregister = CarOfficeRegister::find()
		->select(['{{cs_car_office_register}}.*',
		'cs_department.name department_name',
		'cs_admin.name username',
		'cs_car.plate_number',
		'cs_admin.name reg_name',
		'd.name reg_name',

		//'cs_car_brand.name car_brand'
		])
		->leftJoin('cs_department', 'cs_car_office_register.department_id = cs_department.id')
		->leftJoin('cs_admin', 'cs_car_office_register.username_id = cs_admin.id')
		->leftJoin('cs_admin d', 'cs_car_office_register.add_id = d.id')
		->leftJoin('cs_car', 'cs_car_office_register.car_id = cs_car.id')
		//->leftJoin('cs_car_brand','cs_car.brand_id = cs_car_brand.id')
		->where(['cs_car_office_register.id'=>$id])->asArray()->one();
		//echo '<pre>';print_r($driver);exit;
		if(empty($carofficeregister)){
			return false;
		}
        //echo '<pre>';
       //var_dump($carofficeregister['is_return']);exit;
        //foreach ($carofficeregister as $key => $car_mod) {

            //还车后，用车人 用车部门 登记人为空
       /* if($carofficeregister['is_return'] == 1) {
            $carofficeregister['username'] = '';
            $carofficeregister['department_name'] = '';
            $carofficeregister['reg_name'] = '';
        }*/
            //var_dump($car_mod);exit;
        //}

		//echo '<pre>';
		//var_dump($carofficeregister);exit;
		return $this->render('scan',[
			'carofficeregister'=>$carofficeregister,		
		],true);
		//return $this->render('scan');
		
	}

	//按照条件导出
    public function actionExportWidthCondition()
    {
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    	$plate_number = yii::$app->request->get('plate_number');
    	//$car_type = yii::$app->request->get('car_type');
    	$username = yii::$app->request->get('username');
        //配置信息
        $configItems = ['car_model_name'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
    
    	$query = (new \yii\db\Query())->select([
    			'a.*',
    			'a.address',
    			'a.start_time',
    			'd.name username',
    			'c.name department_name',
    			'e.plate_number',
    			'e.car_model',
    			'f.name car_brand',
    			'd2.name reg_name'

    			])->from('cs_car_office_register a')
    			//->leftJoin('cs_customer_company b', 'a.c_customer_id = b.id')
    			->leftJoin('cs_department c', 'a.department_id = c.id')
    			->leftJoin('cs_admin d', 'a.username_id = d.id')
    			->leftJoin('cs_admin d2', 'a.add_id = d2.id')
    			->leftJoin('cs_car e', 'a.car_id = e.id')
    			->leftJoin('cs_car_brand f','e.brand_id = f.id')
    			//->andWhere(['a.is_del'=>0])
    			;
    	$query->andFilterWhere(['<>','a.`username_id`',0]);
    	//查询条件
    	if($plate_number){
    		$query->andFilterWhere([
    				'like',
    				'e.plate_number',
    				$plate_number
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
    		
    		$total_distance = $car_mod['total_distance'];
    		//$return_distance = $car_mod['return_distance'];
    	 	//$total_distance = $car_mod['total_distance'];
    		if($car_mod['return_distance'] != 0) {
    			$return_distance = $car_mod['return_distance'];
    			$use_distance = $return_distance-$total_distance;
    			$data[$key]['use_distance']=$use_distance;
    		}
	    		//var_dump($use_distance);exit;
	    		//2、计算用车时间
    		$return_time = $car_mod['return_time'];
    		$start_time = $car_mod['start_time'];
    		if($car_mod['return_time'] != 0) {
	    		$use_time = strtotime($return_time)-strtotime($start_time);
	    		//echo strtotime($return_time).' ';
	    		$use_time = round($use_time/(60*60),1);
	    	    $data[$key]['use_time']=$use_time;
    		}
    		//$data[$key]['use_time']=$use_time;
    		//var_dump($start_time);exit;
    		//3、车辆类型
    		$car_m = $car_mod['car_model'];
            //var_dump($car_m);exit;
    		$data[$key]['car_model_r'] = $config['car_model_name'][$car_m]['text'];
            /*if($data[$key]['is_return'] == 1) {
                $data[$key]['username'] = '';
                $data[$key]['department_name'] = '';
                $data[$key]['reg_name'] = '';
            }*/

    	
    	}
    	//echo '<pre>';
    	//var_dump($data);exit;
    	$filename = '自用备用车列表.csv'; //设置文件名
    	$str = "车牌号,车辆品牌,车型名称,申请部门,用车人,开始用车时间,还车时间,用车时长(小时),用车里程(KM),登记人,登记时间\n";
    	//$car_type_arr = array(1=>'自用车',2=>'备用车');
    	//$car_status_arr = array(1=>'已替换',2=>'未替换');
        //echo '<pre>';
        //var_dump($data);exit;
    	foreach ($data as $row){
    		//echo '<pre>';
    		//var_dump($row);exit;
    		$str .= "{$row['plate_number']},{$row['car_brand']},{$row['car_model_r']},{$row['department_name']},{$row['username']},{$row['start_time']},{$row['return_time']},{$row['use_time']},{$row['use_distance']},{$row['reg_name']},{$row['reg_time']}"."\n";
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