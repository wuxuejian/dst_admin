<?php
/**
 * 保险控制器
 * time    2016/08/16 11:37
 * @author pengyl
 */
namespace backend\modules\car\controllers;
use backend\models\CarInsuranceOther;

use backend\models\CarDrivingLicense;

use backend\models\CarInsuranceCompulsory;

use backend\models\ConfigItem;

use backend\controllers\BaseController;
use backend\models\Car;
use backend\models\CarInsuranceBusiness;
use backend\models\ConfigCategory;
use common\models\Excel;
use common\models\File;
use yii;
use yii\data\Pagination;
use backend\models\OperatingCompany;
use backend\models\Owner;
class InsuranceController extends BaseController
{
    public function actionIndex()
    {
        //获取本页按钮
        $buttons = $this->getCurrentActionBtn(); 
        //获取配置数据
        $configItems = ['car_status','car_type','use_nature','car_color','car_model_name'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
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
        
        //车辆运营公司
        $oc = (new \yii\db\Query())->from('cs_operating_company')->where('is_del=0')->all();
        if($oc)
        {
        	$searchFormOptions['operating_company_id'] = [];
        	$searchFormOptions['operating_company_id'][] = ['value'=>'','text'=>'不限'];
        	foreach($oc as $val){
        		$searchFormOptions['operating_company_id'][] = ['value'=>$val['id'],'text'=>$val['name']];
        	}
        }
        
        return $this->render('index',[
            'buttons'=>$buttons,
            'config'=>$config,
            'searchFormOptions'=>$searchFormOptions,
        ]);
    }
    
    /**
     * 获取归属客户（车辆保险的combogrid）
     */
    public function actionGetCustomers()
    {
    	$queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
    	
    	$connection = yii::$app->db;
    	
    	//1.获取客户列表
//     	$query = $connection->createCommand(
//     			"select CONCAT('status_',value) value,text from cs_config_item where belongs_id=6 and text like :text and is_del!=1
//     				union all 
//     			select CONCAT('company_',id) value,company_name text from cs_customer_company where is_del!=1 and company_name like :company_name
//     				union all
//     			select CONCAT('personal_',id) value,id_name text from cs_customer_personal where is_del!=1 and id_name like :id_name
//     			"
//     		)->bindValues([':text'=>'%'.$queryStr.'%',':company_name'=>'%'.$queryStr.'%',':id_name'=>'%'.$queryStr.'%']);
		//去掉车辆当前状态
    	$query = $connection->createCommand(
    			"select CONCAT('company_',id) value,company_name text from cs_customer_company where is_del!=1 and company_name like :company_name
    			union all
    			select CONCAT('personal_',id) value,id_name text from cs_customer_personal where is_del!=1 and id_name like :id_name
    			"
    	)->bindValues([':company_name'=>'%'.$queryStr.'%',':id_name'=>'%'.$queryStr.'%']);
    	$customers = $query->queryAll();
    	
    	$returnArr = [];
    	$returnArr['rows'] = $customers;
    	//echo $query->getRawSql();exit;
    	return json_encode($returnArr);
    }

    /**
     * 获取列表
     */
    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = Car::find()
            ->select([
                '{{%car}}.`id`',
                '{{%car}}.`plate_number`',
            	'{{%car}}.`car_status`',
            	'{{%car}}.`car_model`',
				'{{%car}}.`operating_company_id`',
				'{{%car}}.`owner_id`',
                'transact_ic'=>'{{%car_insurance_compulsory}}.`id`',
            	'compulsory_start_date'=>'{{%car_insurance_compulsory}}.`start_date`',
            	'compulsory_end_date'=>'{{%car_insurance_compulsory}}.`end_date`',
            	//'_compulsory_end_date'=>'{{%car_insurance_compulsory}}.end_date', //“倒计时”占位
                'transact_ib'=>'{{%car_insurance_business}}.`id`',
            	'business_start_date'=>'{{%car_insurance_business}}.`start_date`',
            	'business_end_date'=>'{{%car_insurance_business}}.`end_date`',
            	//'_business_end_date'=>'{{%car_insurance_business}}.end_date', //“倒计时”占位
                '{{%car}}.`insurance_last_update_time`',
                'username'=>'{{%admin}}.`name`',
                /*
                */
                'transact_ic_f'=>'{{%car_insurance_compulsory}}.`append_urls`',//交强险附件
                'transact_ib_f'=>'{{%car_insurance_business}}.`append_urls`',//商业险附件
                'transact_ic_pd'=>'{{%car_insurance_compulsory_form}}.`id`',//交强险批单
                'transact_ib_pd'=>'{{%car_insurance_business_form}}.`id`',//商业险批单
                'use_nature'=>'{{%car_insurance_compulsory}}.`use_nature`',
                'use_nature_p'=>'{{%car_insurance_compulsory_form}}.`use_nature`',
                'car_model_name2'=>'{{%car_type}}.`car_model_name`',
            ])
            //->leftJoin('(select * from (select * from cs_car_insurance_compulsory where is_del=0 order by end_date desc) temp1 GROUP BY car_id) as {{%car_insurance_compulsory}}','{{%car}}.id = {{%car_insurance_compulsory}}.car_id')
            ->leftJoin('(select * from (select * from cs_car_insurance_compulsory where is_del=0  order by end_date desc) temp1 GROUP BY car_id) as {{%car_insurance_compulsory}}','{{%car}}.id = {{%car_insurance_compulsory}}.car_id')
//             ->joinWith('carInsuranceCompulsory',false)
//             ->joinWith('carInsuranceBusiness',false)
        	//->leftJoin('(select * from (select * from cs_car_insurance_business where is_del=0 order by end_date desc) temp2 GROUP BY car_id) as {{%car_insurance_business}}','{{%car}}.id = {{%car_insurance_business}}.car_id')
            ->leftJoin('(select * from (select * from cs_car_insurance_business where is_del=0 order by end_date desc) temp2 GROUP BY car_id) as {{%car_insurance_business}}','{{%car}}.id = {{%car_insurance_business}}.car_id')
            ->leftJoin('{{%admin}}', '{{%car}}.`insurance_add_aid` = {{%admin}}.`id`')
            /*
            */
            //->leftJoin('{{%car_insurance_compulsory_form}}', '{{%car_insurance_compulsory_form}}.`insurance_id` = {{%car_insurance_compulsory}}.`id`')
            ->leftJoin('(select * from (select * from cs_car_insurance_compulsory_form where is_del=0 order by add_datetime desc limit 1) temp3) as {{%car_insurance_compulsory_form}}','{{%car_insurance_compulsory_form}}.insurance_id = {{%car_insurance_compulsory}}.id')
            //->leftJoin('{{%car_insurance_business_form}}', '{{%car_insurance_business_form}}.`insurance_id` = {{%car_insurance_business}}.`id`')
            ->leftJoin('(select * from (select * from cs_car_insurance_business_form where is_del=0 order by add_datetime desc limit 1) temp4) as {{%car_insurance_business_form}}','{{%car_insurance_business_form}}.insurance_id = {{%car_insurance_business}}.id')
            ->leftJoin('cs_car_type', 'cs_car.car_type_id = cs_car_type.id')

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
		//按车型名称查
		if(yii::$app->request->get('car_model')){
			$car_model_query = ConfigItem::find()->select('value')
					->andWhere(['is_del'=>0,'belongs_id'=>62,'text'=>yii::$app->request->get('car_model')]);
			$car_models = $car_model_query->asArray()->all();
			$car_models_s = array();
			foreach($car_models as $item){
				array_push($car_models_s, $item['value']);
			}
			$query->andWhere(['in','{{%car}}.`car_model`',$car_models_s]);
		}
		//运营公司
		if(yii::$app->request->get('operating_company_id')){
			$query->andFilterWhere(['=','{{%car}}.`operating_company_id`',yii::$app->request->get('operating_company_id')]);
		}
		if(yii::$app->request->get('owner_id')){
        	$owner_id = yii::$app->request->get('owner_id');
        	$query->andFilterWhere(['{{%car}}.`owner_id`'=>$owner_id]);
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
        //是否办理交强险
        $transactRtc = intval(yii::$app->request->get('transact_ic'));
        if($transactRtc == 1){
            //查询已经办理
            $query->andWhere('{{%car_insurance_compulsory}}.`id` IS NOT NULL');
        }elseif($transactRtc == 2){
            //查询未办理
            $query->andWhere('{{%car_insurance_compulsory}}.`id` IS NULL');
        }
        //是否办理商业险
        $transactRtc = intval(yii::$app->request->get('transact_ib'));
        if($transactRtc == 1){
            //查询已经办理
            $query->andWhere('{{%car_insurance_business}}.`id` IS NOT NULL');
        }elseif($transactRtc == 2){
            //查询未办理
            $query->andWhere('{{%car_insurance_business}}.`id` IS NULL');
        }
        ////查询条件结束
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'transact_ic':
                    $orderBy = '{{%car_insurance_compulsory}}.`id` ';
                    break;
                case 'transact_ib':
                    $orderBy = '{{%car_insurance_business}}.`id` ';
                    break;
                case '_compulsory_end_date':
                    $orderBy = '{{%car_insurance_compulsory}}.`end_date` ';
                    break;
                case '_business_end_date':
                    $orderBy = '{{%car_insurance_business}}.`end_date` ';
                    break;
                default:
                    $orderBy = '{{%car}}.`'.$sortColumn.'` ';
                    break;
            }
        }else{
            $orderBy = '{{%car}}.`id` ';
        }
        
        $orderBy .= $sortType;
        
        $total = $query->groupBy('{{%car}}.`id`')->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $query = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy);
//         echo $query->createCommand()->getRawSql();exit;
        $data = $query->asArray()->all();
        //echo '<pre>';
        //var_dump($data);exit;
		
		//车辆运营公司
        $oCompany = OperatingCompany::getOperatingCompany();  
		
        //加载归属客户	
        $connection = yii::$app->db;
        $configItems = ['car_status'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
        foreach ($data as $index=>$row){
 
            $result1 = (new \yii\db\query())->from('cs_car_insurance_compulsory')->where(['car_id'=>$row['id'],'is_del'=>0])->orderBy('add_datetime desc')->limit(1)->one();
            $data[$index]['start_date_tic']= $result1['start_date'];
            $data[$index]['end_date_tic']= $result1['end_date'];
            $result2 = (new \yii\db\query())->from('cs_car_insurance_business')->where(['car_id'=>$row['id'],'is_del'=>0])->orderBy('add_datetime desc')->limit(1)->one();
            $data[$index]['start_date_bi']= $result2['start_date'];
            $data[$index]['end_date_bi']= $result2['end_date'];

			if(isset($oCompany[$row['operating_company_id']]) && $oCompany[$row['operating_company_id']]){
				$data[$index]['operating_company_id'] = $oCompany[$row['operating_company_id']]['name'];
			} 
			if (isset($row['owner_id'])) {
				$query_owner = Owner::find()->select(['owner_name'=>'name']);
				$query_owner->andFilterWhere(['`id`'=>$row['owner_id']]);
				$owner = $query_owner->asArray()->one();
				if($owner){
					$data[$index]['owner_name'] = $owner['owner_name'];
				}
			}			
			//var_dump($row);
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
    			$data[$index]['customer_name'] = @$config['car_status'][$row['car_status']]['text'];
    		}    

        }
//echo '<pre>';
//var_dump($data);exit;

        $returnArr = [];
        $returnArr['rows'] = $data; 
        $returnArr['total'] = $total;
        //echo '<pre>';
        //var_dump($returnArr);
        //die;
        return json_encode($returnArr);
    }
    //导出车辆保险信息
	public function actionExportWidthCondition()
    {
    	$query = Car::find()
    	->select([
    			'{{%car}}.`id`',
    			'{{%car}}.`plate_number`',
    			'{{%car}}.`car_status`',
    			'{{%car}}.`car_model`',
    			'transact_ic'=>'{{%car_insurance_compulsory}}.`id`',
    			'compulsory_start_date'=>'{{%car_insurance_compulsory}}.`start_date`',
    			'compulsory_end_date'=>'{{%car_insurance_compulsory}}.`end_date`',
    			'_compulsory_end_date'=>'{{%car_insurance_compulsory}}.end_date', //“倒计时”占位
    			'compulsory_insurer_company'=>'{{%car_insurance_compulsory}}.insurer_company',
    			'compulsory_money_amount'=>'{{%car_insurance_compulsory}}.money_amount',
    			'transact_ib'=>'{{%car_insurance_business}}.`id`',
    			'business_start_date'=>'{{%car_insurance_business}}.`start_date`',
    			'business_end_date'=>'{{%car_insurance_business}}.`end_date`',
    			'_business_end_date'=>'{{%car_insurance_business}}.end_date', //“倒计时”占位
    			'business_insurer_company'=>'{{%car_insurance_business}}.insurer_company',
    			'business_money_amount'=>'{{%car_insurance_business}}.money_amount',
    			'{{%car}}.`operating_company_id`',
				'{{%car}}.`owner_id`',
				'{{%car}}.`insurance_last_update_time`',
    			'username'=>'{{%admin}}.`name`',
    			])
    			->leftJoin('(select * from (select * from cs_car_insurance_compulsory order by end_date desc) temp1 GROUP BY car_id) as {{%car_insurance_compulsory}}','{{%car}}.id = {{%car_insurance_compulsory}}.car_id')
    			//             ->joinWith('carInsuranceCompulsory',false)
    			//             ->joinWith('carInsuranceBusiness',false)
    			->leftJoin('(select * from (select * from cs_car_insurance_business order by end_date desc) temp2 GROUP BY car_id) as {{%car_insurance_business}}','{{%car}}.id = {{%car_insurance_business}}.car_id')
    			->leftJoin('{{%admin}}', '{{%car}}.`insurance_add_aid` = {{%admin}}.`id`')
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
		$query->andFilterWhere(['=','{{%car}}.`operating_company_id`',yii::$app->request->get('operating_company_id')]);
		if(yii::$app->request->get('owner_id')){
        	$owner_id = yii::$app->request->get('owner_id');
        	$query->andFilterWhere(['{{%car}}.`owner_id`'=>$owner_id]);
        }
    	//按车型名称查
    	if(yii::$app->request->get('car_model')){
    		$car_model_query = ConfigItem::find()->select('value')
    		->andWhere(['is_del'=>0,'belongs_id'=>62,'text'=>yii::$app->request->get('car_model')]);
    		$car_models = $car_model_query->asArray()->all();
    		$car_models_s = array();
    		foreach($car_models as $item){
    			array_push($car_models_s, $item['value']);
    		}
    		$query->andWhere(['in','{{%car}}.`car_model`',$car_models_s]);
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
    	//是否办理交强险
    	$transactRtc = intval(yii::$app->request->get('transact_ic'));
    	if($transactRtc == 1){
    		//查询已经办理
    		$query->andWhere('{{%car_insurance_compulsory}}.`id` IS NOT NULL');
    	}elseif($transactRtc == 2){
    		//查询未办理
    		$query->andWhere('{{%car_insurance_compulsory}}.`id` IS NULL');
    	}
    	//是否办理商业险
    	$transactRtc = intval(yii::$app->request->get('transact_ib'));
    	if($transactRtc == 1){
    		//查询已经办理
    		$query->andWhere('{{%car_insurance_business}}.`id` IS NOT NULL');
    	}elseif($transactRtc == 2){
    		//查询未办理
    		$query->andWhere('{{%car_insurance_business}}.`id` IS NULL');
    	}
//     	echo $query->createCommand()->getRawSql();exit;
    	////查询条件结束
    	$sortColumn = yii::$app->request->get('sort');
    	$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
    	$orderBy = '{{%car}}.`id` ';
    	$orderBy .= $sortType;
    	$query = $query->orderBy($orderBy);
    	$data = $query->asArray()->all();
    	$filename = '车辆保险信息.csv'; //设置文件名
    	$str = "车牌号,车型名称,交强险,保险公司,费用,开始时间,结束时间,交强险倒计时,商业险,保险公司,费用,开始时间,结束时间,商业险倒计时,车辆运营公司,机动车所有人,上次修改时间,操作人员\n";
    	$car_type_arr = array(1=>'自用车',2=>'备用车');
    	$car_status_arr = array(1=>'已替换',2=>'未替换');
    	//车辆运营公司
        $oCompany = OperatingCompany::getOperatingCompany();       
		foreach ($data as $row){
		
			if(isset($oCompany[$row['operating_company_id']]) && $oCompany[$row['operating_company_id']]){
				$row['operating_company_id'] = $oCompany[$row['operating_company_id']]['name'];
				$operating_company_id = $row['operating_company_id'];
			} 
			
			if (isset($row['owner_id'])) {
				$query_owner = Owner::find()->select(['owner_name'=>'name']);
				$query_owner->andFilterWhere(['`id`'=>$row['owner_id']]);
				$owner = $query_owner->asArray()->one();
				if($owner){
					$row['owner_name'] = $owner['owner_name'];
					$owner_name= $row['owner_name'];
				}
			}
			
			
    		$plate_number = $row['plate_number'];
    		$transact_ic = $row['transact_ic']>=1?'已购买':'未购买';
    		$compulsory_start_date = date('Y-m-d',$row['compulsory_start_date']);
    		$compulsory_end_date = date('Y-m-d',$row['compulsory_end_date']);
    		$_compulsory_end_date = $row['_compulsory_end_date'];
    		if($_compulsory_end_date){
    			if($_compulsory_end_date+86400 < time()){
    				$_compulsory_end_date = '已过期';
    			}else{
    				$diff = $_compulsory_end_date - strtotime(date('Y-m-d',time())); //年月日
    				$days = floor($diff/(3600*24)) + 1; //+1包含今日在内
    				$_compulsory_end_date = $days.'天';
    			}
    		}else{
    			$_compulsory_end_date = '';
    		}
    		$transact_ib = $row['transact_ib']>=1?'已购买':'未购买';
    		$business_start_date = date('Y-m-d',$row['business_start_date']);
    		$business_end_date = date('Y-m-d',$row['business_end_date']);
    		$_business_end_date = $row['_business_end_date'];
    		if($_business_end_date){
    			if($_business_end_date+86400 < time()){
    				$_business_end_date = '已过期';
    			}else{
    				$diff = $_business_end_date - strtotime(date('Y-m-d',time())); //年月日
    				$days = floor($diff/(3600*24)) + 1; //+1包含今日在内
    				$_business_end_date = $days.'天';
    			}
    		}else{
    			$_business_end_date = '';
    		}
    		$insurance_last_update_time = date('Y-m-d',$row['insurance_last_update_time']);
    		$username = $row['username'];
    		$config = (new ConfigCategory)->getCategoryConfig(['car_model_name'],'value');
    		$car_model = '';
    		if(@$config['car_model_name'][$row['car_model']]){
    			$car_model = @$config['car_model_name'][$row['car_model']]['text'];
    		}
    		$str .= "{$plate_number},{$car_model},{$transact_ic},{$row['compulsory_insurer_company']},{$row['compulsory_money_amount']},{$compulsory_start_date},{$compulsory_end_date},{$_compulsory_end_date},{$transact_ib},{$row['business_insurer_company']},{$row['business_money_amount']},{$business_start_date},{$business_end_date},{$_business_end_date},{$operating_company_id},{$owner_name},{$insurance_last_update_time},{$username}"."\n";
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
    
    //查看车辆数据
    public function actionScan(){
    	$id = yii::$app->request->get('id') or die('param id is required');
    	
    	$connection = yii::$app->db;
    	$sql = 'select id,plate_number,brand_id,car_model,car_status from cs_car where id='.$id;
    	$data = $connection->createCommand($sql)->queryOne();
        //var_dump($data);exit;
    	//加载品牌
    	$brand = $connection->createCommand('select name from cs_car_brand where id='.$data['brand_id'])->queryOne();
    	$data['brand_name'] = $brand['name'];
    	//加载车型
    	$car_model = $connection->createCommand('select text from cs_config_item where value="'.$data['car_model'].'"')->queryOne();
    	$data['car_model_name'] = $car_model['text'];
    	//加载归属客户
    	$configItems = ['car_status','INSURANCE_COMPANY'];
    	$config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
    	$query = $connection->createCommand(
    			"select cCustomer_id,pCustomer_id,cs_customer_company.company_name,cs_customer_personal.id_name
    			from cs_car_let_record
    			left join cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id
    			left join cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
    			where (back_time>".time()." or back_time=0) and car_id=".$data['id']
    	);
    	$customer = $query->queryOne();
        //var_dump($customer);exit;
    	if($customer){
    		if($customer['company_name']){
    			$data['customer_name'] = $customer['company_name'];
    		}else if($customer['id_name']){
    			$data['customer_name'] = $customer['id_name'];
    		}
    	}else {
    		$data['customer_name'] = $config['car_status'][$data['car_status']]['text'];
    	}
        //var_dump($data['customer_name']);exit;
    	//加载保险信息
    	$insurance_compulsory = $connection->createCommand(
    			'select id,money_amount,insurer_company,start_date,end_date,note 
    			from cs_car_insurance_compulsory 
    			where car_id='.$data['id'].' order by end_date desc limit 1'
    			)->queryOne();
    	if($insurance_compulsory){
	    	$data['insurance_compulsory'] = $insurance_compulsory;
	    	$data['insurance_compulsory']['insurer_company_name'] = @$config['INSURANCE_COMPANY'][$insurance_compulsory['insurer_company']]['text'];
		}else {
			$data['insurance_compulsory'] = '';
	    	$data['insurance_compulsory']['insurer_company_name'] = '';
		}
		$insurance_business = $connection->createCommand(
				'select id,money_amount,insurer_company,start_date,end_date,note,insurance_text 
				from cs_car_insurance_business 
				where car_id='.$data['id'].' order by end_date desc limit 1'
			)->queryOne();
		if($insurance_business){
			$data['insurance_business'] = $insurance_business;
			$data['insurance_business']['insurer_company_name'] = $config['INSURANCE_COMPANY'][$insurance_business['insurer_company']]['text'];
    	}else {
			$data['insurance_business'] = '';
	    	$data['insurance_business']['insurer_company_name'] = '';
		}
    	//加载出险信息
    	$insurance_claim = $connection->createCommand(
    			'select *
    			from cs_car_insurance_claim
    			where car_id='.$data['id'].' order by danger_date desc limit 1'
    	)->queryOne();
		if($insurance_claim){
			$claim_car = $connection->createCommand('select plate_number from cs_car where id='.$insurance_claim['car_id'])->queryOne();
			$insurance_claim['claim_car'] = $claim_car['plate_number'];
			$data['insurance_claim'] = $insurance_claim;
			$insurance_claim_state = '';//出险状态
			if($insurance_claim['oper_user7']){
				$insurance_claim_state = '转账结案';
			}else if($insurance_claim['oper_user6']){
				$insurance_claim_state = '保险请款';
			}else if($insurance_claim['oper_user5']){
				$insurance_claim_state = '保险理赔';
			}else if($insurance_claim['oper_user4']){
				$insurance_claim_state = '车辆维修';
			}else if($insurance_claim['oper_user3']){
				$insurance_claim_state = '保险定损';
			}else if($insurance_claim['oper_user2']){
				$insurance_claim_state = '查勘结论';
			}else if($insurance_claim['oper_user1']){
				$insurance_claim_state = '报案出险';
			}
			$data['insurance_claim_state'] = $insurance_claim_state;
		}else {
			$insurance_claim['claim_car'] = '';
			$data['insurance_claim'] = '';
			$data['insurance_claim_state'] = '';
		}
    	//加载出险时归属客户
    	$query = $connection->createCommand(
    			"select cCustomer_id,pCustomer_id,cs_customer_company.company_name,cs_customer_personal.id_name
    			from cs_car_let_record
    			left join cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id
    			left join cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
    			where (back_time>".(@$insurance_claim['danger_date']?strtotime($insurance_claim['danger_date']):0)." or back_time=0) and car_id=".$data['id']
    	);
		//echo $query->getRawSql();exit;
    	$customer = $query->queryOne();
    	if($customer){
    		if($customer['company_name']){
    			$data['claim_customer_name'] = $customer['company_name'];
    		}else if($customer['id_name']){
    			$data['claim_customer_name'] = $customer['id_name'];
    		}
    	}else {
    		$data['claim_customer_name'] = '无';
    	}
    	return $this->render('scan',[
    			'obj'=>$data
    			]);
    }
    
    /**
     * 车辆交强险管理
     */
    public function actionTrafficCompulsoryInsurance()
    {
    	$carId = yii::$app->request->get('carId') or die('param carId is required');
    	$buttons = $this->getCurrentActionBtn();
    	$config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY'],'value');
    	$insurerCompany = [
    	['value'=>'','text'=>'不限']
    	];
    	if($config['INSURANCE_COMPANY']){
    		foreach($config['INSURANCE_COMPANY'] as $val){
    			$insurerCompany[] = ['value'=>$val['value'],'text'=>$val['text']];
    		}
    	}
    	return $this->render('traffic-compulsory-insurance',[
    			'carId'=>$carId,
    			'buttons'=>$buttons,
    			'config'=>$config,
    			'insurerCompany'=>$insurerCompany,
    			]);
    }
    /**
     * 获取指定车辆交强险列表
     */
    public function actionTciGetList()
    {
    	$carId = yii::$app->request->get('carId') or die('param carId is required');
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    	$query = CarInsuranceCompulsory::find()
    	->select(['{{%car_insurance_compulsory}}.*','{{%admin}}.`username`'])
    	->joinWith('admin',false,'LEFT JOIN')
    	->andWhere(['=','{{%car_insurance_compulsory}}.`is_del`',0])
    	->andWhere(['=','{{%car_insurance_compulsory}}.`car_id`',$carId]);
    	//查询条件
    	$query->andFilterWhere(['=','{{%car_insurance_compulsory}}.`insurer_company`',yii::$app->request->get('insurer_company')]);
    	$query->andFilterWhere(['like','{{%car_insurance_compulsory}}.`number`',yii::$app->request->get('number')]);
    	if(yii::$app->request->get('start_date')){
    		$query->andFilterWhere(['>=','{{%car_insurance_compulsory}}.`start_date`',strtotime(yii::$app->request->get('start_date'))]);
    	}
    	if(yii::$app->request->get('end_date')){
    		$query->andFilterWhere(['<=','{{%car_insurance_compulsory}}.`end_date`',strtotime(yii::$app->request->get('end_date'))]);
    	}
    	//查询条件结束
    	$total = $query->count();
    	$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
    	//排序开始
    	$sortColumn = yii::$app->request->get('sort');
    	$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
    	$orderBy = '';
    	if($sortColumn){
    		switch ($sortColumn) {
    			case 'username':
    				$orderBy = '{{%admin}}.`'.$sortColumn.'` ';
    				break;
    			default:
    				$orderBy = '{{%car_insurance_compulsory}}.`'.$sortColumn.'` ';
    			break;
    		}
    	}else{
    		$orderBy = '{{%car_insurance_compulsory}}.`id` ';
    	}
    	$orderBy .= $sortType;
    	//排序结束
    	$data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
    	$returnArr = [];
    	$returnArr['rows'] = $data;
    	$returnArr['total'] = $total;
    	echo json_encode($returnArr);
    }
    
    /**
     * 导出交强险列表
     */
    public function actionTciExport()
    {
    	$carId = yii::$app->request->get('carId') or die('param carId is required');
    	$query = CarInsuranceCompulsory::find()
    	->select(['{{%car_insurance_compulsory}}.*','{{%admin}}.`username`'])
    	->joinWith('admin',false,'LEFT JOIN')
    	->andWhere(['=','{{%car_insurance_compulsory}}.`is_del`',0])
    	->andWhere(['=','{{%car_insurance_compulsory}}.`car_id`',$carId]);
    	//查询条件
    	$query->andFilterWhere(['=','{{%car_insurance_compulsory}}.`insurer_company`',yii::$app->request->get('insurer_company')]);
    	$query->andFilterWhere(['like','{{%car_insurance_compulsory}}.`number`',yii::$app->request->get('number')]);
    	if(yii::$app->request->get('start_date')){
    		$query->andFilterWhere(['>=','{{%car_insurance_compulsory}}.`start_date`',strtotime(yii::$app->request->get('start_date'))]);
    	}
    	if(yii::$app->request->get('end_date')){
    		$query->andFilterWhere(['<=','{{%car_insurance_compulsory}}.`end_date`',strtotime(yii::$app->request->get('end_date'))]);
    	}
    	//查询条件结束
    	$sortColumn = yii::$app->request->get('sort');
    	$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
    	$orderBy = '{{%car_insurance_compulsory}}.`id` ';
    	$orderBy .= $sortType;
    	$data = $query->asArray()->all();
    	$filename = '交强险列表.csv'; //设置文件名
    	$str = "保单号,保险公司,保险金额,开始时间,结束时间,备注\n";
    	foreach ($data as $row){
    		$number = $row['number'];
    		$money_amount = $row['money_amount'];
    		$start_date = date('Y-m-d',$row['start_date']);
    		$end_date = date('Y-m-d',$row['end_date']);
    		$note = $row['note'];
    		$config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY'],'value');
    		$insurer_company = @$config['INSURANCE_COMPANY'][$row['insurer_company']]['text'];
    		$str .= "{$number},{$insurer_company},{$money_amount},{$start_date},{$end_date},{$note}"."\n";
    	}
    	$str = mb_convert_encoding($str, "GBK", "UTF-8");
    	$this->export_csv($filename,$str); //导出
    }
    
    /**
     * 添加指定车辆交强险记录
     */
    public function actionTciAdd()
    {
    	//data submit start
    	if(yii::$app->request->isPost){
    		//上传保单附件

    		$append_urls_arr = array();
    		if(@$_FILES['append']){
                
    			$file_path="uploads/tci/";
    			if(!is_dir($file_path)){
    				mkdir($file_path);
    			}
    			$file_path .= date("Ymd").'/';
    			if(!is_dir($file_path)){
    				mkdir($file_path);
    			}
	    		for($i=0;$i<count($_FILES['append']['name']);$i++){
	    			$_FILES['append']['name'][$i] = date("YmdHis").'_'.$_FILES['append']['name'][$i]; //加个时间戳防止重复文件上传后被覆盖
	    		}
	    		$filename=$_FILES['append']['name'];
                //var_dump($filename);exit;
                $arr_jpg=array('jpg','png','pdf');//验证上传文件的格式
                foreach ($filename as $k1 => $v1) {
                    $file_res = explode('.',$v1);
                    if(!in_array($file_res[1], $arr_jpg))
                    {
                        $returnArr['status'] = false;
                        $returnArr['info'] = "上传文件仅支持'jpg,png,pdf'格式!";
                        return json_encode($returnArr); 
                    }
                       
                }

	    		$filet=$_FILES['append']['tmp_name']; 
	    		for($i=0;$i<count($filename);$i++){     //循环上传文件的数组
	    			//move_uploaded_file($filet[$i],$file_path.iconv("UTF-8","gb2312", $filename[$i]));
                    move_uploaded_file($filet[$i],$file_path.$filename[$i]);
	    			array_push($append_urls_arr, $file_path.$filename[$i]);
	    		}
    		}
    		$append_urls = json_encode($append_urls_arr);
    		
    		$formData = yii::$app->request->post();
           
            $tci_numbers = (new \yii\db\query())->select(['number'])->from('cs_car_insurance_compulsory')->where(['is_del'=>0])->all();
            foreach ($tci_numbers as $k => $v) {
                if($v['number']){
                    if($formData['number']==$v['number']){
                        $returnArr['status'] = false;
                        $returnArr['info'] = '交强险单号已存在!';
                        return json_encode($returnArr);
                    }
                }
            }
            //验证开始时间不能大于结束时间
            if($formData['start_date'] > $formData['end_date']){
                $returnArr['status'] = false;
                $returnArr['info'] = '开始时间不能大于结束时间!';
                return json_encode($returnArr); 
            }


    		//检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
    		$checkArr = Car::checkOperatingCompanyIsMatch($formData['car_id']);
    		if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
    			return json_encode(['status'=>false,'info'=>$checkArr['info']]);
    		}
    		$model = new CarInsuranceCompulsory();
    		$model->load($formData,'');
    		$returnArr = [];
    		$returnArr['status'] = true;
    		$returnArr['info'] = '';
    		if($model->validate()){
    			$model->add_datetime = time();
    			$model->add_aid = $_SESSION['backend']['adminInfo']['id'];
    			$model->append_urls = $append_urls;
    			if($model->save(false)){
    				$returnArr['status'] = true;
    				$returnArr['info'] = '车辆强制保险记录添加成功！';
    				$carId = $model->car_id;
    				//检查行驶证和交强险是否齐全，若是则更改车辆状态
    				$this->checkDrivingLicenseAndTrafficCompulsoryInsurance($carId);
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
    	//data sbumit end
    	//获取配置
    	$config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY']);
    	$config['INSURANCE_COMPANY'] = array_values($config['INSURANCE_COMPANY']);
    	$carId = yii::$app->request->get('carId') or die('param carId is required');
    	//var_dump($carId);exit;
    	//获取上一次保险记录
    	$tciInfo = CarInsuranceCompulsory::find()->select(['*'])->limit(1)->orderBy('id desc')->asArray()->one();
    	return $this->render('tci-add',[
    			'tciInfo'=>$tciInfo,
    			'carId'=>$carId,
    			'config'=>$config
    			]);
    }
    /**
     * 修改指定车辆交强险记录
     */
    public function actionTciEdit(){
    	//data submit start

    	if(yii::$app->request->isPost){
            $connection = yii::$app->db;
    		$id = yii::$app->request->post('id') or die('param id is required');

            //删除批单
            $del_ids = yii::$app->request->post('del_ids');
            $id_forms = (new \yii\db\query())->from('cs_car_insurance_compulsory_form')->select(['id','insurance_id'])->where(['insurance_id'=>$id,'is_del'=>0])->all();

            if($del_ids == null && $id_forms != null){//删除修改页面的批单记录。判断有批单且全部删除
                foreach($id_forms as $k5 => $v5){
                    $sql = "update cs_car_insurance_compulsory_form set is_del=1 where insurance_id={$v5['insurance_id']}";
                    $result = $connection->createCommand($sql)->execute();
                }   
            } else {
               foreach(@$id_forms as $k =>$v){
                    foreach(@$del_ids as $k1 =>$v1){
                         if(!in_array($v['id'], @$del_ids)){
                            $sql = "update cs_car_insurance_compulsory_form set is_del=1 where id={$v['id']}";
                            $result = $connection->createCommand($sql)->execute();
                         }
                    }
                }    
            }
                                                    
    		$model = CarInsuranceCompulsory::findOne(['id'=>$id]);
    		$model or die('record not found');

            //验证开始时间不能大于结束时间
            $start_date = yii::$app->request->post('start_date');
            $end_date = yii::$app->request->post('end_date');
            if($start_date > $end_date){
                $returnArr['status'] = false;
                $returnArr['info'] = '开始时间不能大于结束时间!';
                return json_encode($returnArr); 
            }

    		//上传保单附件
    		if(yii::$app->request->post('append_url')){
    			$append_urls_arr = yii::$app->request->post('append_url');
    		}else {
    			$append_urls_arr = array();
    		}
    		
    		if(@$_FILES['append']){
    			$file_path="uploads/tci/";
    			if(!is_dir($file_path)){
    				mkdir($file_path);
    			}
    			$file_path .= date("Ymd").'/';
    			if(!is_dir($file_path)){
    				mkdir($file_path);
    			}
    			
    			for($i=0;$i<count($_FILES['append']['name']);$i++){
    				$_FILES['append']['name'][$i] = date("YmdHis").'_'.$_FILES['append']['name'][$i]; //加个时间戳防止重复文件上传后被覆盖
    			}
    			$filename=$_FILES['append']['name'];

                $arr_jpg=array('jpg','png','pdf');//验证上传文件的格式
                foreach ($filename as $k1 => $v1) {
                    $file_res = explode('.',$v1);
                    if(!in_array($file_res[1], $arr_jpg))
                    {
                        $returnArr['status'] = false;
                        $returnArr['info'] = "上传文件仅支持'jpg,png,pdf'格式!";
                        return json_encode($returnArr); 
                    }
                       
                }

    			$filet=$_FILES['append']['tmp_name'];
    			for($i=0;$i<count($filename);$i++){     //循环上传文件的数组
    				//move_uploaded_file($filet[$i],$file_path.iconv("UTF-8","gb2312", $filename[$i]));
                    move_uploaded_file($filet[$i],$file_path.$filename[$i]);
    				array_push($append_urls_arr, $file_path.$filename[$i]);
    			}
    		}
    		$append_urls = json_encode($append_urls_arr);
    		//
    		
           
          

           //var_dump($del_ids);exit;
    		//检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
    		$checkArr = Car::checkOperatingCompanyIsMatch($model->car_id);
    		if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
    			return json_encode(['status'=>false,'info'=>$checkArr['info']]);
    		}
    		$model->load(yii::$app->request->post(),'');
    		$returnArr = [];
    		$returnArr['status'] = true;
    		$returnArr['info'] = '';
    		if($model->validate()){
    			$model->add_datetime = time();
    			$model->add_aid = $_SESSION['backend']['adminInfo']['id'];
    			$model->append_urls = $append_urls;
    			if($model->save(false)){
    				$returnArr['status'] = true;
    				$returnArr['info'] = '车辆强制保险记录修改成功！';
    				$carId = $model->car_id;
    				//检查行驶证和交强险是否齐全，若是则更改车辆状态
    				$this->checkDrivingLicenseAndTrafficCompulsoryInsurance($carId);
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
    	//data submit end
    	$id = yii::$app->request->get('id') or die('param id is required');
    	$model = CarInsuranceCompulsory::findOne(['id'=>$id]);
        //echo '<pre>';
        //var_dump($model->getOldAttributes());exit;
    	$model or die('record not found');
    	$config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY']);
       
        //查询批单信息
       // var_dump($id);exit;
        $pdinfo = (new \yii\db\query())->from('cs_car_insurance_compulsory_form')->select(['id','number'])->where(['insurance_id'=>$id,'is_del'=>0])->all();
        //echo '<pre>';
        //var_dump($pdinfo);exit;
    	return $this->render('tci-edit',[
    			'tciInfo'=>$model->getOldAttributes(),
    			'config'=>$config,
                'pdinfo'=>$pdinfo
    			]);
    }
    
    /**
     * 删除指定车辆交强险记录
     */
    public function actionTciRemove()
    {
    	$id = yii::$app->request->get('id') or die('param id is required');
    	$model = CarInsuranceCompulsory::findOne(['id'=>$id]);
    	$model or die('record not found');
    	//检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
    	$checkArr = Car::checkOperatingCompanyIsMatch($model->car_id);
    	if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
    		return json_encode(['status'=>false,'info'=>$checkArr['info']]);
    	}
    	$returnArr = [];
    	$returnArr['status'] = true;
    	$returnArr['info'] = '';
    	if(CarInsuranceCompulsory::updateAll(['is_del'=>1],['id'=>$id])){
    		$statusRet = Car::changeCarStatusNew($model->car_id, 'NAKED', 'car/insurance/tci-remove', '删除交强险',['car_status'=>'STOCK']);
    		$returnArr['status'] = true;
    		$returnArr['info'] = '车辆强制保险记录删除成功！';
    	}else{
    		$returnArr['status'] = false;
    		$returnArr['info'] = '车辆强制保险记录删除失败！';
    	}
    	echo json_encode($returnArr);
    }
    
    /**
     * 下载附近
     */
    public function actionDownload()
    {
    	//echo yii::$app->request->baseUrl;exit;
    	$id = yii::$app->request->get('id') or die('param id is requried');
    	$model = CarInsuranceCompulsory::findOne(['id'=>$id]);
    	if(@$model->append_urls && json_decode($model->append_urls)){
    		$append_urls = json_decode($model->append_urls);
    		foreach ($append_urls as $append_url){
//     			$file[] = file(yii::$app->request->hostInfo.yii::$app->request->baseUrl.'/'.$append_url);
    			$file[] = dirname(getcwd()).'/web/'.iconv("UTF-8","gb2312", $append_url);
    		}
    		header("Content-type: text/html; charset=gbk");
    		$zipFile = dirname(getcwd()).'/runtime/'.time().'.zip';
    		File::filesToZip($file,$zipFile);
    		File::fileDownload($zipFile);
//     		foreach($file as $val){
//     			@unlink($val);
//     		}
    		@unlink($zipFile);
    	}else {
    		echo '无';
    	}
    }
    
    /**
     * 指定车辆商业保险管理
     */
    public function actionBusinessInsurance()
    {
    	$carId = yii::$app->request->get('carId') or die('param carId is required');
    	$buttons = $this->getCurrentActionBtn();
    	$config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY'],'value');
    	$insurerCompany = [
    	['value'=>'','text'=>'不限']
    	];
    	if($config['INSURANCE_COMPANY']){
    		foreach($config['INSURANCE_COMPANY'] as $val){
    			$insurerCompany[] = ['value'=>$val['value'],'text'=>$val['text']];
    		}
    	}
    	return $this->render('business-insurance',[
    			'carId'=>$carId,
    			'buttons'=>$buttons,
    			'config'=>$config,
    			'insurerCompany'=>$insurerCompany,
    			]);
    }
    
    /**
     * 获取指定车辆商业保险列表
     */
    public function actionBiGetList()
    {
    	$carId = yii::$app->request->get('carId') or die('param carId is required');
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    	$query = CarInsuranceBusiness::find()
    	->select(['{{%car_insurance_business}}.*','{{%admin}}.`username`'])
    	->joinWith('admin',false,'LEFT JOIN')
    	->andWhere(['=','{{%car_insurance_business}}.`is_del`',0])
    	->andWhere(['=','{{%car_insurance_business}}.`car_id`',$carId]);
    	//查询条件
    	$query->andFilterWhere(['=','{{%car_insurance_business}}.`insurer_company`',yii::$app->request->get('insurer_company')]);
    	$query->andFilterWhere(['like','{{%car_insurance_business}}.`number`',yii::$app->request->get('number')]);
    	if(yii::$app->request->get('start_date')){
    		$query->andFilterWhere(['>=','{{%car_insurance_business}}.`start_date`',strtotime(yii::$app->request->get('start_date'))]);
    	}
    	if(yii::$app->request->get('end_date')){
    		$query->andFilterWhere(['<=','{{%car_insurance_business}}.`end_date`',strtotime(yii::$app->request->get('end_date'))]);
    	}
    	
//     	echo $query->createCommand()->getRawSql();exit;
    	//查询条件结束
    	//排序开始
    	$sortColumn = yii::$app->request->get('sort');
    	$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
    	$orderBy = '';
    	if($sortColumn){
    		switch ($sortColumn) {
    			case 'username':
    				$orderBy = '{{%admin}}.`'.$sortColumn.'` ';
    				break;
    			default:
    				$orderBy = '{{%car_insurance_business}}.`'.$sortColumn.'` ';
    			break;
    		}
    	}else{
    		$orderBy = '{{%car_insurance_business}}.`id` ';
    	}
    	$orderBy .= $sortType;
    	//排序结束
    	$total = $query->count();
    	$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
    	$data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
    	$returnArr = [];
    	$returnArr['rows'] = $data;
    	$returnArr['total'] = $total;
    	echo json_encode($returnArr);
    }
    
    /**
     * 导出商业险列表
     */
    public function actionBiExport()
    {
    	$carId = yii::$app->request->get('carId') or die('param carId is required');
    	$query = CarInsuranceBusiness::find()
    	->select(['{{%car_insurance_business}}.*','{{%admin}}.`username`'])
    	->joinWith('admin',false,'LEFT JOIN')
    	->andWhere(['=','{{%car_insurance_business}}.`is_del`',0])
    	->andWhere(['=','{{%car_insurance_business}}.`car_id`',$carId]);
    	//查询条件
    	$query->andFilterWhere(['=','{{%car_insurance_business}}.`insurer_company`',yii::$app->request->get('insurer_company')]);
    	$query->andFilterWhere(['like','{{%car_insurance_business}}.`number`',yii::$app->request->get('number')]);
    	if(yii::$app->request->get('start_date')){
    		$query->andFilterWhere(['>=','{{%car_insurance_business}}.`start_date`',strtotime(yii::$app->request->get('start_date'))]);
    	}
    	if(yii::$app->request->get('end_date')){
    		$query->andFilterWhere(['<=','{{%car_insurance_business}}.`end_date`',strtotime(yii::$app->request->get('end_date'))]);
    	}
    	//查询条件结束
    	$sortColumn = yii::$app->request->get('sort');
    	$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
    	$orderBy = '{{%car_insurance_business}}.`id` ';
    	$orderBy .= $sortType;
    	$data = $query->asArray()->all();
    	$filename = '商业险列表.csv'; //设置文件名
    	$str = "保单号,保险公司,险种,保险金额,开始时间,结束时间,备注\n";
    	foreach ($data as $row){
    		$number = $row['number'];
    		$money_amount = $row['money_amount'];
    		$start_date = date('Y-m-d',$row['start_date']);
    		$end_date = date('Y-m-d',$row['end_date']);
    		$note = $row['note'];
    		$config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY'],'value');
    		$insurer_company = @$config['INSURANCE_COMPANY'][$row['insurer_company']]['text'];
			$insurer_str = '';
			$insurances = json_decode($row['insurance_text']);
			foreach ($insurances as $row1){
				$insurer_str .= $row1[0].'('.$row1[1].')，';
			}
    		$str .= "{$number},{$insurer_company},{$insurer_str},{$money_amount},{$start_date},{$end_date},{$note}"."\n";
    	}
    	$str = mb_convert_encoding($str, "GBK", "UTF-8");
    	$this->export_csv($filename,$str); //导出
    }
    
    /**
     * 指定车辆添加商业保险记录
     */
    public function actionBiAdd()
    {
    	//data submit start
    	if(yii::$app->request->isPost){
    		//上传保单附件
    		$append_urls_arr = array();
    		if(@$_FILES['append']){
    			$file_path="uploads/bi/";
    			if(!is_dir($file_path)){
    				mkdir($file_path);
    			}
    			$file_path .= date("Ymd").'/';
    			if(!is_dir($file_path)){
    				mkdir($file_path);
    			}
    		
    			for($i=0;$i<count($_FILES['append']['name']);$i++){
    				$_FILES['append']['name'][$i] = date("YmdHis").'_'.$_FILES['append']['name'][$i]; //加个时间戳防止重复文件上传后被覆盖
    			}
    			$filename=$_FILES['append']['name'];

                $arr_jpg=array('jpg','png','pdf');//验证上传文件的格式
                foreach ($filename as $k1 => $v1) {
                    $file_res = explode('.',$v1);
                    if(!in_array($file_res[1], $arr_jpg))
                    {
                        $returnArr['status'] = false;
                        $returnArr['info'] = "上传文件仅支持'jpg,png,pdf'格式!";
                        return json_encode($returnArr); 
                    }
                       
                }

    			$filet=$_FILES['append']['tmp_name'];
    			for($i=0;$i<count($filename);$i++){     //循环上传文件的数组
    				//move_uploaded_file($filet[$i],$file_path.iconv("UTF-8","gb2312", $filename[$i]));
                    move_uploaded_file($filet[$i],$file_path.$filename[$i]);
    				array_push($append_urls_arr, $file_path.$filename[$i]);
    			}
    		}
    		$append_urls = json_encode($append_urls_arr);
    		//组织险种字段
    		$insurance_text_arr = array();
    		$types = yii::$app->request->post('type');
    		$moneys = yii::$app->request->post('money');
    		$money_amount=0;
    		if(@$types){
    			foreach ($types as $index=>$value){
    				$money_amount += $moneys[$index];
    				array_push($insurance_text_arr, array($value,$moneys[$index]));
    			}
    		}
    		$insurance_text = json_encode($insurance_text_arr);
    		//
    		
    		$formData = yii::$app->request->post();
            //验证保单唯一性
            $bi_numbers = (new \yii\db\query())->select(['number'])->from('cs_car_insurance_business')->where(['is_del'=>0])->all();
            foreach ($bi_numbers as $k => $v) {
                if($v['number']){
                    if($formData['number']==$v['number']){
                        $returnArr['status'] = false;
                        $returnArr['info'] = '商业险单号已存在!';
                        return json_encode($returnArr);
                    }
                }
            }
             //验证开始时间不能大于结束时间
            if($formData['start_date'] > $formData['end_date']){
                $returnArr['status'] = false;
                $returnArr['info'] = '开始时间不能大于结束时间!';
                return json_encode($returnArr); 
            }
    		//检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
    		$checkArr = Car::checkOperatingCompanyIsMatch($formData['car_id']);
    		if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
    			return json_encode(['status'=>false,'info'=>$checkArr['info']]);
    		}
    		$model = new CarInsuranceBusiness();
    		$model->load($formData,'');
    		$returnArr = [];
    		$returnArr['status'] = true;
    		$returnArr['info'] = '';
    		if($model->validate()){
    			$model->add_datetime = time();
    			$model->add_aid = $_SESSION['backend']['adminInfo']['id'];
    			$model->append_urls = $append_urls;
    			$model->insurance_text = $insurance_text;
    			$model->money_amount = $money_amount;
    			if($model->save(false)){
    				$returnArr['status'] = true;
    				$returnArr['info'] = '车辆商业保险记录添加成功！';
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
    	$carId = yii::$app->request->get('carId') or die('param carId is required');
    	
    	$config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY']);
    	$config['INSURANCE_COMPANY'] = array_values($config['INSURANCE_COMPANY']);
    	
    	//获取上一次保险记录
    	$biInfo = CarInsuranceBusiness::find()->select(['*'])->limit(1)->orderBy('id desc')->asArray()->one();
    	return $this->render('bi-add',[
    			'biInfo'=>$biInfo,
    			'carId'=>$carId,
    			'config'=>$config
    			]);
    }
    
    /**
     * 修改指定车辆商业保险记录
     */
    public function actionBiEdit(){
    	//data submit start
    	if(yii::$app->request->isPost){
            $connection = yii::$app->db;
    		$id = yii::$app->request->post('id') or die('param id is required');

            //删除批单
            $del_ids = yii::$app->request->post('del_ids');
            $id_forms = (new \yii\db\query())->from('cs_car_insurance_business_form')->select(['id','insurance_id'])->where(['insurance_id'=>$id,'is_del'=>0])->all();
            if($del_ids == null && $id_forms != null){//删除修改页面的批单记录。判断有批单且全部删除
                foreach($id_forms as $k4 => $v4){
                    $sql = "update cs_car_insurance_business_form set is_del=1 where insurance_id={$v4['insurance_id']}";
                    $result = $connection->createCommand($sql)->execute();
                }   
            } else {
               foreach(@$id_forms as $k2 =>$v2){
                //var_dump($v2);exit;
                    foreach(@$del_ids as $k3 =>$v3){
                         if(!in_array($v2['id'], @$del_ids)){
                            //echo '12';exit;
                            $sql = "update cs_car_insurance_business_form set is_del=1 where id={$v2['id']}";
                            $result = $connection->createCommand($sql)->execute();
                         }
                    }
                }      
            }
              
    		$model = CarInsuranceBusiness::findOne(['id'=>$id]);
    		$model or die('record not found');
    	   
            //验证开始时间不能大于结束时间
            $start_date = yii::$app->request->post('start_date');
            $end_date = yii::$app->request->post('end_date');
            if($start_date > $end_date){
                $returnArr['status'] = false;
                $returnArr['info'] = '开始时间不能大于结束时间!';
                return json_encode($returnArr); 
            }
    		//上传保单附件
    		if(yii::$app->request->post('append_url')){
    			$append_urls_arr = yii::$app->request->post('append_url');
    		}else {
    			$append_urls_arr = array();
    		}
    		if(@$_FILES['append']){
    			$file_path="uploads/bi/";
    			if(!is_dir($file_path)){
    				mkdir($file_path);
    			}
    			$file_path .= date("Ymd").'/';
    			if(!is_dir($file_path)){
    				mkdir($file_path);
    			}
    		
    			for($i=0;$i<count($_FILES['append']['name']);$i++){
    				$_FILES['append']['name'][$i] = date("YmdHis").'_'.$_FILES['append']['name'][$i]; //加个时间戳防止重复文件上传后被覆盖
    			}
    			$filename=$_FILES['append']['name'];

                $arr_jpg=array('jpg','png','pdf');//验证上传文件的格式
                foreach ($filename as $k1 => $v1) {
                    $file_res = explode('.',$v1);
                    if(!in_array($file_res[1], $arr_jpg))
                    {
                        $returnArr['status'] = false;
                        $returnArr['info'] = "上传文件仅支持'jpg,png,pdf'格式!";
                        return json_encode($returnArr); 
                    }
                       
                }

    			$filet=$_FILES['append']['tmp_name'];
    			for($i=0;$i<count($filename);$i++){     //循环上传文件的数组
    				//move_uploaded_file($filet[$i],$file_path.iconv("UTF-8","gb2312", $filename[$i]));
                    move_uploaded_file($filet[$i],$file_path.$filename[$i]);
    				array_push($append_urls_arr, $file_path.$filename[$i]);
    			}
    		}
    		$append_urls = json_encode($append_urls_arr);
    		//组织险种字段
    		$insurance_text_arr = array();
    		$types = yii::$app->request->post('type');
    		$moneys = yii::$app->request->post('money');
    		$money_amount = 0;
    		if(@$types){
    			foreach ($types as $index=>$value){
    				$money_amount += $moneys[$index];
    				array_push($insurance_text_arr, array($value,$moneys[$index]));
    			}
    		}
    		$insurance_text = json_encode($insurance_text_arr);
    		//
    		
    		//检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
    		$checkArr = Car::checkOperatingCompanyIsMatch($model->car_id);
    		if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
    			return json_encode(['status'=>false,'info'=>$checkArr['info']]);
    		}
    		$model->load(yii::$app->request->post(),'');
    		$returnArr = [];
    		$returnArr['status'] = true;
    		$returnArr['info'] = '';
    		if($model->validate()){
    			$model->add_datetime = time();
    			$model->add_aid = $_SESSION['backend']['adminInfo']['id'];
    			$model->append_urls = $append_urls;
    			$model->insurance_text = $insurance_text;
    			$model->money_amount = $money_amount;
    			if($model->save(false)){
    				$returnArr['status'] = true;
    				$returnArr['info'] = '车辆商业保险记录修改成功！';
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
    	$id = yii::$app->request->get('id') or die('param id is required');

    	$model = CarInsuranceBusiness::findOne(['id'=>$id]);
    	$model or die('record not found');
    	$config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY']);
//var_dump($id);exit;
        $pdbuinfo = (new \yii\db\query())->from('cs_car_insurance_business_form')->select(['id','number'])->where(['insurance_id'=>$id,'is_del'=>0])->all();
        //var_dump($pdbuinfo);exit;
        //echo '<pre>';
        //var_dump($pdinfo);exit;
        /*return $this->render('tci-edit',[
                'tciInfo'=>$model->getOldAttributes(),
                'config'=>$config,
                'pdinfo'=>$pdinfo
                ]);*/
        
        //echo '<pre>';
        //var_dump($model->getOldAttributes());exit;
    	return $this->render('bi-edit',[
    			'biInfo'=>$model->getOldAttributes(),
    			'config'=>$config,
                'pdbuinfo'=>$pdbuinfo
    			]);
    }
    
    /**
     * 删除指定车辆商业保险记录
     */
    public function actionBiRemove()
    {
    	$id = yii::$app->request->get('id') or die('param id is required');
    	$model = CarInsuranceBusiness::findOne(['id'=>$id]);
    	$model or die('record not found');
    	//检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
    	$checkArr = Car::checkOperatingCompanyIsMatch($model->car_id);
    	if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
    		return json_encode(['status'=>false,'info'=>$checkArr['info']]);
    	}
    	$returnArr = [];
    	$returnArr['status'] = true;
    	$returnArr['info'] = '';
    	if(CarInsuranceBusiness::updateAll(['is_del'=>1],['id'=>$id])){
    		$returnArr['status'] = true;
    		$returnArr['info'] = '车辆商业保险记录删除成功！';
    	}else{
    		$returnArr['status'] = false;
    		$returnArr['info'] = '车辆商业保险记录删除失败！';
    	}
    	return json_encode($returnArr);
    }
    
    /**
     * 下载商业险附件
     */
    public function actionBiDownload()
    {
    	//echo yii::$app->request->baseUrl;exit;
    	$id = yii::$app->request->get('id') or die('param id is requried');
    	$model = CarInsuranceBusiness::findOne(['id'=>$id]);
    	if(@$model->append_urls && json_decode($model->append_urls)){
    		$append_urls = json_decode($model->append_urls);
    		foreach ($append_urls as $append_url){
    			//     			$file[] = file(yii::$app->request->hostInfo.yii::$app->request->baseUrl.'/'.$append_url);
    			$file[] = dirname(getcwd()).'/web/'.iconv("UTF-8","gb2312", $append_url);
    		}
    		header("Content-type: text/html; charset=gbk");
    		$zipFile = dirname(getcwd()).'/runtime/'.time().'.zip';
    		File::filesToZip($file,$zipFile);
    		File::fileDownload($zipFile);
    		//     		foreach($file as $val){
    		//     			@unlink($val);
    		//     		}
    		@unlink($zipFile);
    	}else {
    		echo '无';
    	}
    }
    
    /**
     * 指定车辆其它险管理
     */
    public function actionOtherInsurance()
    {
    	$carId = yii::$app->request->get('carId') or die('param carId is required');
    	$buttons = $this->getCurrentActionBtn();
    	$config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY'],'value');
    	$insurerCompany = [
    	['value'=>'','text'=>'不限']
    	];
    	if($config['INSURANCE_COMPANY']){
    		foreach($config['INSURANCE_COMPANY'] as $val){
    			$insurerCompany[] = ['value'=>$val['value'],'text'=>$val['text']];
    		}
    	}
    	return $this->render('other-insurance',[
    			'carId'=>$carId,
    			'buttons'=>$buttons,
    			'config'=>$config,
    			'insurerCompany'=>$insurerCompany,
    			]);
    }
    
    /**
     * 获取指定车辆其他险列表
     */
    public function actionOtherGetList()
    {
    	$carId = yii::$app->request->get('carId') or die('param carId is required');
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    	$query = CarInsuranceOther::find()
    	->select(['{{%car_insurance_other}}.*','{{%admin}}.`username`'])
    	->joinWith('admin',false,'LEFT JOIN')
    	->andWhere(['=','{{%car_insurance_other}}.`is_del`',0])
    	->andWhere(['=','{{%car_insurance_other}}.`car_id`',$carId]);
    	//查询条件
    	$query->andFilterWhere(['=','{{%car_insurance_other}}.`insurer_company`',yii::$app->request->get('insurer_company')]);
    	$query->andFilterWhere(['like','{{%car_insurance_other}}.`number`',yii::$app->request->get('number')]);
    	if(yii::$app->request->get('start_date')){
    		$query->andFilterWhere(['>=','{{%car_insurance_other}}.`start_date`',strtotime(yii::$app->request->get('start_date'))]);
    	}
    	if(yii::$app->request->get('end_date')){
    		$query->andFilterWhere(['<=','{{%car_insurance_other}}.`end_date`',strtotime(yii::$app->request->get('end_date'))]);
    	}
    	//查询条件结束
    	//排序开始
    	$sortColumn = yii::$app->request->get('sort');
    	$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
    	$orderBy = '';
    	if($sortColumn){
    		switch ($sortColumn) {
    			case 'username':
    				$orderBy = '{{%admin}}.`'.$sortColumn.'` ';
    				break;
    			default:
    				$orderBy = '{{%car_insurance_other}}.`'.$sortColumn.'` ';
    			break;
    		}
    	}else{
    		$orderBy = '{{%car_insurance_other}}.`id` ';
    	}
    	$orderBy .= $sortType;
    	//排序结束
    	$total = $query->count();
    	$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
    	$data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
    	$returnArr = [];
    	$returnArr['rows'] = $data;
    	$returnArr['total'] = $total;
    	echo json_encode($returnArr);
    }
    
    /**
     * 导出其它险列表
     */
    public function actionOtherExport()
    {
    	$carId = yii::$app->request->get('carId') or die('param carId is required');
    	$query = CarInsuranceOther::find()
    	->select(['{{%car_insurance_other}}.*','{{%admin}}.`username`'])
    	->joinWith('admin',false,'LEFT JOIN')
    	->andWhere(['=','{{%car_insurance_other}}.`is_del`',0])
    	->andWhere(['=','{{%car_insurance_other}}.`car_id`',$carId]);
    	//查询条件
    	$query->andFilterWhere(['=','{{%car_insurance_other}}.`insurer_company`',yii::$app->request->get('insurer_company')]);
    	$query->andFilterWhere(['like','{{%car_insurance_other}}.`number`',yii::$app->request->get('number')]);
    	if(yii::$app->request->get('start_date')){
    		$query->andFilterWhere(['>=','{{%car_insurance_other}}.`start_date`',strtotime(yii::$app->request->get('start_date'))]);
    	}
    	if(yii::$app->request->get('end_date')){
    		$query->andFilterWhere(['<=','{{%car_insurance_other}}.`end_date`',strtotime(yii::$app->request->get('end_date'))]);
    	}
    	//查询条件结束
    	$sortColumn = yii::$app->request->get('sort');
    	$sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
    	$orderBy = '{{%car_insurance_other}}.`id` ';
    	$orderBy .= $sortType;
    	$data = $query->asArray()->all();
    	$filename = '其它险列表.csv'; //设置文件名
    	$str = "保单号,保险公司,险种,保险金额,开始时间,结束时间,备注\n";
    	foreach ($data as $row){
    		$number = $row['number'];
    		$money_amount = $row['money_amount'];
    		$start_date = date('Y-m-d',$row['start_date']);
    		$end_date = date('Y-m-d',$row['end_date']);
    		$note = $row['note'];
    		$config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY'],'value');
    		$insurer_company = @$config['INSURANCE_COMPANY'][$row['insurer_company']]['text'];
			$insurer_str = '';
			$insurances = json_decode($row['insurance_text']);
			foreach ($insurances as $row1){
				$insurer_str .= $row1[0].'('.$row1[1].')，';
			}
    		$str .= "{$number},{$insurer_company},{$insurer_str},{$money_amount},{$start_date},{$end_date},{$note}"."\n";
    	}
    	$str = mb_convert_encoding($str, "GBK", "UTF-8");
    	$this->export_csv($filename,$str); //导出
    }
    
    /**
     * 指定车辆添加其它险记录
     */
    public function actionOtherAdd()
    {
    	//data submit start
    	if(yii::$app->request->isPost){
    		//上传保单附件
    		$append_urls_arr = array();
    		if(@$_FILES['append']){
    			$file_path="uploads/other/";
    			if(!is_dir($file_path)){
    				mkdir($file_path);
    			}
    			$file_path .= date("Ymd").'/';
    			if(!is_dir($file_path)){
    				mkdir($file_path);
    			}
    
    			for($i=0;$i<count($_FILES['append']['name']);$i++){
    				$_FILES['append']['name'][$i] = date("YmdHis").'_'.$_FILES['append']['name'][$i]; //加个时间戳防止重复文件上传后被覆盖
    			}
    			$filename=$_FILES['append']['name'];
                $arr_jpg=array('jpg','png','pdf');//验证上传文件的格式
                foreach ($filename as $k1 => $v1) {
                    $file_res = explode('.',$v1);
                    if(!in_array($file_res[1], $arr_jpg))
                    {
                        $returnArr['status'] = false;
                        $returnArr['info'] = "上传文件仅支持'jpg,png,pdf'格式!";
                        return json_encode($returnArr); 
                    }
                       
                }
    			$filet=$_FILES['append']['tmp_name'];
    			for($i=0;$i<count($filename);$i++){     //循环上传文件的数组
    				//move_uploaded_file($filet[$i],$file_path.iconv("UTF-8","gb2312", $filename[$i]));
                    move_uploaded_file($filet[$i],$file_path.$filename[$i]);
    				array_push($append_urls_arr, $file_path.$filename[$i]);
    			}
    		}
    		$append_urls = json_encode($append_urls_arr);
    		//组织险种字段
    		$insurance_text_arr = array();
    		$types = yii::$app->request->post('type');
    		$moneys = yii::$app->request->post('money');
    		$money_amount=0;
    		if(@$types){
    			foreach ($types as $index=>$value){
    				$money_amount += $moneys[$index];
    				array_push($insurance_text_arr, array($value,$moneys[$index]));
    			}
    		}
    		$insurance_text = json_encode($insurance_text_arr);
    		//
    
    		$formData = yii::$app->request->post();
            //验证开始时间不能大于结束时间
            if($formData['start_date'] > $formData['end_date']){
                $returnArr['status'] = false;
                $returnArr['info'] = '开始时间不能大于结束时间!';
                return json_encode($returnArr); 
            }

             //验证保单唯一性
            $other_numbers = (new \yii\db\query())->select(['number'])->from('cs_car_insurance_other')->where(['is_del'=>0])->all();
            foreach ($other_numbers as $k => $v) {
                if($v['number']){
                    if($formData['number']==$v['number']){
                        $returnArr['status'] = false;
                        $returnArr['info'] = '其他险单号已存在!';
                        return json_encode($returnArr);
                    }
                }
            }

    		//检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
    		$checkArr = Car::checkOperatingCompanyIsMatch($formData['car_id']);
    		if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
    			return json_encode(['status'=>false,'info'=>$checkArr['info']]);
    		}
    		$model = new CarInsuranceOther();
    		$model->load($formData,'');
    		$returnArr = [];
    		$returnArr['status'] = true;
    		$returnArr['info'] = '';
    		if($model->validate()){
    			$model->add_datetime = time();
    			$model->add_aid = $_SESSION['backend']['adminInfo']['id'];
    			$model->append_urls = $append_urls;
    			$model->insurance_text = $insurance_text;
    			$model->money_amount = $money_amount;
    			if($model->save(false)){
    				$returnArr['status'] = true;
    				$returnArr['info'] = '车辆其它险记录添加成功！';
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
    	$carId = yii::$app->request->get('carId') or die('param carId is required');
    	$config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY']);
    	$config['INSURANCE_COMPANY'] = array_values($config['INSURANCE_COMPANY']);
    	
    	//获取上一次保险记录
    	$otherInfo = CarInsuranceOther::find()->select(['*'])->limit(1)->orderBy('id desc')->asArray()->one();
    	return $this->render('other-add',[
    			'otherInfo'=>$otherInfo,
    			'carId'=>$carId,
    			'config'=>$config
    			]);
    }
    
    /**
     * 修改指定车辆其它险记录
     */
    public function actionOtherEdit(){
    	//data submit start
    	if(yii::$app->request->isPost){
    		$id = yii::$app->request->post('id') or die('param id is required');
    		$model = CarInsuranceOther::findOne(['id'=>$id]);
    		$model or die('record not found');
            
            //验证开始时间不能大于结束时间
            $start_date = yii::$app->request->post('start_date');
            $end_date = yii::$app->request->post('end_date');
            if($start_date > $end_date){
                $returnArr['status'] = false;
                $returnArr['info'] = '开始时间不能大于结束时间!';
                return json_encode($returnArr); 
            }
    		//上传保单附件
    		if(yii::$app->request->post('append_url')){
    			$append_urls_arr = yii::$app->request->post('append_url');
    		}else {
    			$append_urls_arr = array();
    		}
    		if(@$_FILES['append']){
    			$file_path="uploads/other/";
    			if(!is_dir($file_path)){
    				mkdir($file_path);
    			}
    			$file_path .= date("Ymd").'/';
    			if(!is_dir($file_path)){
    				mkdir($file_path);
    			}
    
    			for($i=0;$i<count($_FILES['append']['name']);$i++){
    				$_FILES['append']['name'][$i] = date("YmdHis").'_'.$_FILES['append']['name'][$i]; //加个时间戳防止重复文件上传后被覆盖
    			}
    			$filename=$_FILES['append']['name'];
                $arr_jpg=array('jpg','png','pdf');//验证上传文件的格式
                foreach ($filename as $k1 => $v1) {
                    $file_res = explode('.',$v1);
                    if(!in_array($file_res[1], $arr_jpg))
                    {
                        $returnArr['status'] = false;
                        $returnArr['info'] = "上传文件仅支持'jpg,png,pdf'格式!";
                        return json_encode($returnArr); 
                    }
                       
                }
    			$filet=$_FILES['append']['tmp_name'];
    			for($i=0;$i<count($filename);$i++){     //循环上传文件的数组
    				//move_uploaded_file($filet[$i],$file_path.iconv("UTF-8","gb2312", $filename[$i]));
                    move_uploaded_file($filet[$i],$file_path.$filename[$i]);
    				array_push($append_urls_arr, $file_path.$filename[$i]);
    			}
    		}
    		$append_urls = json_encode($append_urls_arr);
    		//组织险种字段
    		$insurance_text_arr = array();
    		$types = yii::$app->request->post('type');
    		$moneys = yii::$app->request->post('money');
    		$money_amount = 0;
    		if(@$types){
    			foreach ($types as $index=>$value){
    				$money_amount += $moneys[$index];
    				array_push($insurance_text_arr, array($value,$moneys[$index]));
    			}
    		}
    		$insurance_text = json_encode($insurance_text_arr);
    		//
    
    		//检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
    		$checkArr = Car::checkOperatingCompanyIsMatch($model->car_id);
    		if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
    			return json_encode(['status'=>false,'info'=>$checkArr['info']]);
    		}
    		$model->load(yii::$app->request->post(),'');
    		$returnArr = [];
    		$returnArr['status'] = true;
    		$returnArr['info'] = '';
    		if($model->validate()){
    			$model->add_datetime = time();
    			$model->add_aid = $_SESSION['backend']['adminInfo']['id'];
    			$model->append_urls = $append_urls;
    			$model->insurance_text = $insurance_text;
    			$model->money_amount = $money_amount;
    			if($model->save(false)){
    				$returnArr['status'] = true;
    				$returnArr['info'] = '车辆其它险记录修改成功！';
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
    	$id = yii::$app->request->get('id') or die('param id is required');
    	$model = CarInsuranceOther::findOne(['id'=>$id]);
    	$model or die('record not found');
    	$config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY']);
    	return $this->render('other-edit',[
    			'otherInfo'=>$model->getOldAttributes(),
    			'config'=>$config,
    			]);
    }
    
    /**
     * 删除指定车辆其它险记录
     */
    public function actionOtherRemove()
    {
    	$id = yii::$app->request->get('id') or die('param id is required');
    	$model = CarInsuranceOther::findOne(['id'=>$id]);
    	$model or die('record not found');
    	//检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
    	$checkArr = Car::checkOperatingCompanyIsMatch($model->car_id);
    	if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
    		return json_encode(['status'=>false,'info'=>$checkArr['info']]);
    	}
    	$returnArr = [];
    	$returnArr['status'] = true;
    	$returnArr['info'] = '';
    	if(CarInsuranceOther::updateAll(['is_del'=>1],['id'=>$id])){
    		$returnArr['status'] = true;
    		$returnArr['info'] = '车辆其它险记录删除成功！';
    	}else{
    		$returnArr['status'] = false;
    		$returnArr['info'] = '车辆其它险记录删除失败！';
    	}
    	return json_encode($returnArr);
    }
    
    /**
     * 下载其它险附件
     */
    public function actionOtherDownload()
    {
    	//echo yii::$app->request->baseUrl;exit;
    	$id = yii::$app->request->get('id') or die('param id is requried');
    	$model = CarInsuranceOther::findOne(['id'=>$id]);
    	if(@$model->append_urls && json_decode($model->append_urls)){
    		$append_urls = json_decode($model->append_urls);
    		foreach ($append_urls as $append_url){
    			//     			$file[] = file(yii::$app->request->hostInfo.yii::$app->request->baseUrl.'/'.$append_url);
    			$file[] = dirname(getcwd()).'/web/'.iconv("UTF-8","gb2312", $append_url);
    		}
    		header("Content-type: text/html; charset=gbk");
    		$zipFile = dirname(getcwd()).'/runtime/'.time().'.zip';
    		File::filesToZip($file,$zipFile);
    		File::fileDownload($zipFile);
    		//     		foreach($file as $val){
    		//     			@unlink($val);
    		//     		}
    		@unlink($zipFile);
    	}else {
    		echo '无';
    	}
    }
    
    /*
     * 检查行驶证和交强险是否齐全，若是则更改车辆状态由“裸车”变更为“库存”（不管是否到期）。
    */
    protected  function checkDrivingLicenseAndTrafficCompulsoryInsurance($carId){
    	$DrivingLicense = CarDrivingLicense::find()->select(['id'])->where(['car_id'=>$carId])->asArray()->one();
    	$InsuranceCompulsory = CarInsuranceCompulsory::find()->select(['id'])->where(['car_id'=>$carId,'is_del'=>0])->asArray()->one();
    	if(!empty($DrivingLicense) && !empty($InsuranceCompulsory)){
    		$statusRet = Car::changeCarStatusNew($carId, 'STOCK', 'car/insurance/checkDrivingLicenseAndTrafficCompulsoryInsurance', '检查行驶证和交强险是否齐全',['car_status'=>'NAKED']);
    	}
    }

    //交强险批单添加
    public function actionPdAdd()
    {
        $connection = yii::$app->db;
        if(yii::$app->request->isPost)
        {
            $number             = yii::$app->request->post('number_p');//批单号
            $start_date         = strtotime(yii::$app->request->post('start_date'));//开始时间
            $end_date           = strtotime(yii::$app->request->post('end_date'));//结束时间
            $money_amount_add   = yii::$app->request->post('money_amount_add');//批增金额
            $money_amount_minus = yii::$app->request->post('money_amount_minus');//批减金额
            $money_amount       = yii::$app->request->post('money_amount');//批改后保险金额
            $use_nature         = yii::$app->request->post('use_nature');//使用性质
            $note               = yii::$app->request->post('note');//批改原因
            $insurance_id             = yii::$app->request->post('id');//保单id
            if($money_amount_add == null){
                $money_amount_add = 0;
            }
            if($money_amount_minus == null){
                $money_amount_minus = 0;
            }
            if($money_amount == null){
                $money_amount = 0;
            }

           
            $pci_times = (new \yii\db\query())->select(['start_date','end_date'])->from('cs_car_insurance_compulsory')->where(['is_del'=>0,'id'=>$insurance_id])->one();
            if($start_date < $pci_times['start_date'] || $end_date > $pci_times['end_date']){
                    $returnArr['status'] = false;
                    $returnArr['info'] = '批单时间必须在保单时间范围内!';
                    return json_encode($returnArr);
            }
            //echo '<pre>';
            //var_dump($pci_times);exit;

            //验证批单唯一性
            $pci_numbers = (new \yii\db\query())->select(['number'])->from('cs_car_insurance_compulsory_form')->where(['is_del'=>0])->all();
            foreach ($pci_numbers as $k => $v) {
                if($v['number']){
                    if($number==$v['number']){
                        $returnArr['status'] = false;
                        $returnArr['info'] = '交强险批单已存在!';
                        return json_encode($returnArr);
                    }
                }
            }
            //验证开始时间不能大于结束时间
            if($start_date > $end_date){
                $returnArr['status'] = false;
                $returnArr['info'] = '开始时间不能大于结束时间!';
                return json_encode($returnArr); 
            }

            $append_urls_arr = array();//保单附件上传
            if(@$_FILES['append']){
                $file_path="uploads/insurance/";
                if(!is_dir($file_path)){
                    mkdir($file_path);
                }
                $file_path .= date("Ymd").'/';
                if(!is_dir($file_path)){
                    mkdir($file_path);
                }
                for($i=0;$i<count($_FILES['append']['name']);$i++){
                    $_FILES['append']['name'][$i] = date("YmdHis").'_'.$_FILES['append']['name'][$i]; //加个时间戳防止重复文件上传后被覆盖
                }
                $filename=$_FILES['append']['name'];
               // var_dump($filename);exit;
               
                $arr_jpg=array('jpg','png','pdf');//验证上传文件的格式
                foreach ($filename as $k1 => $v1) {
                    $file_res = explode('.',$v1);
                    if(!in_array($file_res[1], $arr_jpg))
                    {
                        $returnArr['status'] = false;
                        $returnArr['info'] = "上传文件仅支持'jpg,png,pdf'格式!";
                        return json_encode($returnArr); 
                    }
                       
                }

                $filet=$_FILES['append']['tmp_name']; 
                for($i=0;$i<count($filename);$i++){     //循环上传文件的数组
                    //move_uploaded_file($filet[$i],$file_path.iconv("UTF-8","gb2312", $filename[$i]));
                    move_uploaded_file($filet[$i],$file_path.$filename[$i]);
                    array_push($append_urls_arr, $file_path.$filename[$i]);
                }
            }
            $append_urls = json_encode($append_urls_arr);

            $reg_record = $connection->createCommand()->insert('cs_car_insurance_compulsory_form',
                          [
                          //'insurance_id'//保险单id
                          'number'=>$number,
                          'start_date'=>$start_date,
                          'end_date'=>$end_date,
                          'money_amount_add'=>$money_amount_add,
                          'money_amount_minus'=>$money_amount_minus,
                          'money_amount'=>$money_amount,
                          'use_nature'=>$use_nature,
                          'note'=>$note,
                          'use_nature'=>$use_nature,
                          'append_urls'=>$append_urls,
                          'insurance_id'=>$insurance_id,
                          'add_datetime'=>time(),//系统添加时间
                          'add_aid' => $_SESSION['backend']['adminInfo']['id']
                          ])
                        ->execute();
            if($reg_record){
                $returnArr['status'] = true;
                $returnArr['info'] = '添加成功';
            } else {
                $returnArr['status'] = false;
                $returnArr['info'] = '添加失败';
            }
            return json_encode($returnArr);
        }


        $insurance_id = yii::$app->request->get('id');
        $result = (new \yii\db\query())->from('cs_car_insurance_compulsory')->where(['id'=>$insurance_id,'is_del'=>0])->one();
        //var_dump($insurance_id);
        //var_dump($result);//exit;
        //var_dump($id);
        //exit;
        return $this->render('pd-add',['insurance_id'=>$insurance_id,'result'=>$result]);
    }

    //交强险批单修改
    public function actionPdEdit()
    {
        $connection = yii::$app->db;
        if(yii::$app->request->isPost)
        {
           $id = yii::$app->request->post('id');//批单号
            
            $number             = yii::$app->request->post('number');//批单号
            $start_date         = strtotime(yii::$app->request->post('start_date'));//开始时间
            $end_date           = strtotime(yii::$app->request->post('end_date'));//结束时间
            $money_amount_add   = yii::$app->request->post('money_amount_add');//批增金额
            $money_amount_minus = yii::$app->request->post('money_amount_minus');//批减金额
            $money_amount       = yii::$app->request->post('money_amount');//批改后保险金额
            $use_nature         = yii::$app->request->post('use_nature');//使用性质
            $note               = yii::$app->request->post('note');//批改原因
            if($money_amount_add == null){
                $money_amount_add = 0;
            }
            if($money_amount_minus == null){
                $money_amount_minus = 0;
            }
            if($money_amount == null){
                $money_amount = 0;
            }
            
            //验证批单唯一性
            //var_dump($id);
            $pci_number = (new \yii\db\query())->select(['number','insurance_id'])->from('cs_car_insurance_compulsory_form')->where(['is_del'=>0,'id'=>$id])->one();
            $pci_numbers = (new \yii\db\query())->select(['number'])->from('cs_car_insurance_compulsory_form')->where(['is_del'=>0])->all();
            //echo '<pre>';
            //var_dump($pci_number);
            //var_dump($pci_number['insurance_id']);
            //var_dump($pci_numbers);
            //exit;
            foreach ($pci_numbers as $k => $v) {
                if($number != $pci_number['number'] &&  $number == $v['number'])
                {
                //if($v['number']){
                    //if($number==$v['number']){
                        $returnArr['status'] = false;
                        $returnArr['info'] = '交强险批单已存在!';
                        return json_encode($returnArr);
                    //}
                //}
                }
            }

            //验证开始时间不能大于结束时间
            if($start_date > $end_date){
                $returnArr['status'] = false;
                $returnArr['info'] = '开始时间不能大于结束时间!';
                return json_encode($returnArr); 
            }

            $pci_times = (new \yii\db\query())->select(['start_date','end_date'])->from('cs_car_insurance_compulsory')->where(['is_del'=>0,'id'=>$pci_number['insurance_id']])->one();
            if($start_date < $pci_times['start_date'] || $end_date > $pci_times['end_date']){
                    $returnArr['status'] = false;
                    $returnArr['info'] = '批单时间必须在保单时间范围内!';
                    return json_encode($returnArr);
            }

            //$append_urls_arr = array();//保单附件上传
            //上传保单附件
            if(yii::$app->request->post('append_url')){
                $append_urls_arr = yii::$app->request->post('append_url');
            }else {
                $append_urls_arr = array();
            }
            if(@$_FILES['append']){
                $file_path="uploads/insurance/";
                if(!is_dir($file_path)){
                    mkdir($file_path);
                }
                $file_path .= date("Ymd").'/';
                if(!is_dir($file_path)){
                    mkdir($file_path);
                }
                for($i=0;$i<count($_FILES['append']['name']);$i++){
                    $_FILES['append']['name'][$i] = date("YmdHis").'_'.$_FILES['append']['name'][$i]; //加个时间戳防止重复文件上传后被覆盖
                }
                $filename=$_FILES['append']['name'];

                $arr_jpg=array('jpg','png','pdf');//验证上传文件的格式
                foreach ($filename as $k1 => $v1) {
                    $file_res = explode('.',$v1);
                    if(!in_array($file_res[1], $arr_jpg))
                    {
                        $returnArr['status'] = false;
                        $returnArr['info'] = "上传文件仅支持'jpg,png,pdf'格式!";
                        return json_encode($returnArr); 
                    }
                       
                }

                $filet=$_FILES['append']['tmp_name']; 
                for($i=0;$i<count($filename);$i++){     //循环上传文件的数组
                    //move_uploaded_file($filet[$i],$file_path.iconv("UTF-8","gb2312", $filename[$i]));
                    move_uploaded_file($filet[$i],$file_path.$filename[$i]);
                    array_push($append_urls_arr, $file_path.$filename[$i]);
                }
            }
            $append_urls = json_encode($append_urls_arr);

            //echo '<pre>';
            //var_dump($append_urls);
            //exit;
            $reg_record = $connection->createCommand()->update('cs_car_insurance_compulsory_form',
                          [
                          //'insurance_id'//保险单id
                          'number'=>$number,
                          'start_date'=>$start_date,
                          'end_date'=>$end_date,
                          'money_amount_add'=>$money_amount_add,
                          'money_amount_minus'=>$money_amount_minus,
                          'money_amount'=>$money_amount,
                          'use_nature'=>$use_nature,
                          'note'=>$note,
                          'use_nature'=>$use_nature,
                          'append_urls'=>$append_urls,
                          'add_datetime'=>time(),//系统修改时间
                          'add_aid' => $_SESSION['backend']['adminInfo']['id'],
                          //'insurance_id'=>$insurance_id
                          ],'id=:id',[':id'=>$id])
                        ->execute();
            if($reg_record){
                $returnArr['status'] = true;
                $returnArr['info'] = '修改成功';
            } else {
                $returnArr['status'] = false;
                $returnArr['info'] = '修改失败';
            }
            return json_encode($returnArr);
        }

        $id = yii::$app->request->get('id');
        $pdInfo = (new \yii\db\query())->from('cs_car_insurance_compulsory_form')->where(['id'=>$id])->one();
        //var_dump($pdInfo);exit;
        $tci_number = (new \yii\db\query())->from('cs_car_insurance_compulsory')->where(['id'=>$pdInfo['insurance_id']])->one();
        return $this->render('pd-edit',['pdInfo'=>$pdInfo,'tci_number'=>$tci_number['number']]);
    }

    //商业险批单添加
    public function actionPdBuAdd()
    {
        $connection = yii::$app->db;
        if(yii::$app->request->isPost)
        {
            $insurance_id = yii::$app->request->post('id');//(商业险id)
            $number = yii::$app->request->post('number_p');//批单号
            //验证批单唯一性
            $pbi_numbers = (new \yii\db\query())->select(['number'])->from('cs_car_insurance_business_form')->where(['is_del'=>0])->all();
            foreach ($pbi_numbers as $k => $v) {
                if($v['number']){
                    if($number==$v['number']){
                        $returnArr['status'] = false;
                        $returnArr['info'] = '商业险批单已存在!';
                        return json_encode($returnArr);
                    }
                }
            }
            $start_date = strtotime(yii::$app->request->post('start_date'));//开始时间
            $end_date = strtotime(yii::$app->request->post('end_date'));//结束时间
            $use_nature = yii::$app->request->post('use_nature');//使用性质
            $note = yii::$app->request->post('note');//备注

            $types = yii::$app->request->post('type');//险种
            $moneys = yii::$app->request->post('money');//金额
            $middles = yii::$app->request->post('middle');//修改的金额
            $calculates = yii::$app->request->post('calculate');//运算符


            $pci_times = (new \yii\db\query())->select(['start_date','end_date'])->from('cs_car_insurance_business')->where(['is_del'=>0,'id'=>$insurance_id])->one();
            if($start_date < $pci_times['start_date'] || $end_date > $pci_times['end_date']){
                    $returnArr['status'] = false;
                    $returnArr['info'] = '批单时间必须在保单时间范围内!';
                    return json_encode($returnArr);
            }

            //验证开始时间不能大于结束时间
            if($start_date > $end_date){
                $returnArr['status'] = false;
                $returnArr['info'] = '开始时间不能大于结束时间!';
                return json_encode($returnArr); 
            }

            //组织险种字段
            $insurance_text_arr = array();
            $money_amount=0;
            if(@$types){
                foreach ($types as $index=>$value){
                    $money_amount += $moneys[$index];
                    array_push($insurance_text_arr, array($value,$calculates[$index],$middles[$index],$moneys[$index]));
                }
            }
            $insurance_text = json_encode($insurance_text_arr);
            $append_urls_arr = array();//保单附件上传
            if(@$_FILES['append']){
                $file_path="uploads/insurance/";
                if(!is_dir($file_path)){
                    mkdir($file_path);
                }
                $file_path .= date("Ymd").'/';
                if(!is_dir($file_path)){
                    mkdir($file_path);
                }
                for($i=0;$i<count($_FILES['append']['name']);$i++){
                    $_FILES['append']['name'][$i] = date("YmdHis").'_'.$_FILES['append']['name'][$i]; //加个时间戳防止重复文件上传后被覆盖
                }
                $filename=$_FILES['append']['name'];

                $arr_jpg=array('jpg','png','pdf');//验证上传文件的格式
                foreach ($filename as $k1 => $v1) {
                    $file_res = explode('.',$v1);
                    if(!in_array($file_res[1], $arr_jpg))
                    {
                        $returnArr['status'] = false;
                        $returnArr['info'] = "上传文件仅支持'jpg,png,pdf'格式!";
                        return json_encode($returnArr); 
                    }
                       
                }

                $filet=$_FILES['append']['tmp_name']; 
                for($i=0;$i<count($filename);$i++){     //循环上传文件的数组
                    //move_uploaded_file($filet[$i],$file_path.iconv("UTF-8","gb2312", $filename[$i]));
                    move_uploaded_file($filet[$i],$file_path.$filename[$i]);
                    array_push($append_urls_arr, $file_path.$filename[$i]);
                }
            }
            $append_urls = json_encode($append_urls_arr);
            $reg_record = $connection->createCommand()->insert('cs_car_insurance_business_form',
                          [
                          //'insurance_id'//保险单id
                          'insurance_id'=>$insurance_id,
                          'number'=>$number,
                          'start_date'=>$start_date,
                          'end_date'=>$end_date,
                          'use_nature'=>$use_nature,
                          'note'=>$note,
                          'insurance_text'=>$insurance_text,
                          'money_amount'=>$money_amount,
                          'add_datetime'=>time(),//系统修改时间
                          'add_aid' => $_SESSION['backend']['adminInfo']['id'],
                          'append_urls'=>$append_urls,

                          ])
                        ->execute();
            if($reg_record){
                $returnArr['status'] = true;
                $returnArr['info'] = '添加成功';
            } else {
                $returnArr['status'] = false;
                $returnArr['info'] = '添加失败';
            }
            return json_encode($returnArr);

        }



        $id = yii::$app->request->get('id');
        //var_dump($id);exit;
        //$biInfo = CarInsuranceBusiness::find()->select(['*'])->limit(1)->orderBy('id desc')->asArray()->one();
        //$biInfo = CarInsuranceBusiness::find()->select(['*'])->where(['id'=>$id])->asArray()->one();
        $biInfo = (new \yii\db\query())->from('cs_car_insurance_business')->where(['id'=>$id])->one();
        //echo '<pre>';
        //var_dump($biInfo['id']);exit;
        return $this->render('pd-bu-add',['business_id'=>$id,'biInfo'=>$biInfo]);
    }

     //修改险批单添加
    public function actionPdBuEdit()
    {
        $connection = yii::$app->db;
        if(yii::$app->request->isPost)
        {
            $id = yii::$app->request->post('id');

            $number = yii::$app->request->post('number');//批单号
            $start_date = strtotime(yii::$app->request->post('start_date'));//开始时间
            $end_date = strtotime(yii::$app->request->post('end_date'));//结束时间
            $use_nature = yii::$app->request->post('use_nature');//使用性质
            $note = yii::$app->request->post('note');//备注

            $types = yii::$app->request->post('type');//险种
            $moneys = yii::$app->request->post('money');//金额
            $middles = yii::$app->request->post('middle');//修改的金额
            $calculates = yii::$app->request->post('calculate');//运算符

            //验证批单唯一性
            $pci_number = (new \yii\db\query())->select(['number','insurance_id'])->from('cs_car_insurance_business_form')->where(['is_del'=>0,'id'=>$id])->one();
            $pci_numbers = (new \yii\db\query())->select(['number'])->from('cs_car_insurance_business_form')->where(['is_del'=>0])->all();
            foreach ($pci_numbers as $k => $v) {
                if($number != $pci_number['number'] &&  $number == $v['number'])
                {
                    $returnArr['status'] = false;
                    $returnArr['info'] = '商业险批单已存在!';
                    return json_encode($returnArr);
                }
            }

            //验证开始时间不能大于结束时间
            if($start_date > $end_date){
                $returnArr['status'] = false;
                $returnArr['info'] = '开始时间不能大于结束时间!';
                return json_encode($returnArr); 
            }

            $pci_times = (new \yii\db\query())->select(['start_date','end_date'])->from('cs_car_insurance_business')->where(['is_del'=>0,'id'=>$pci_number['insurance_id']])->one();
            if($start_date < $pci_times['start_date'] || $end_date > $pci_times['end_date']){
                    $returnArr['status'] = false;
                    $returnArr['info'] = '批单时间必须在保单时间范围内!';
                    return json_encode($returnArr);
            }

            //组织险种字段
            $insurance_text_arr = array();
            $money_amount=0;
            if(@$types){
                foreach ($types as $index=>$value){
                    $money_amount += $moneys[$index];
                    array_push($insurance_text_arr, array($value,$calculates[$index],$middles[$index],$moneys[$index]));
                }
            }
            $insurance_text = json_encode($insurance_text_arr);
            //$append_urls_arr = array();//保单附件上传
            if(yii::$app->request->post('append_url')){
                $append_urls_arr = yii::$app->request->post('append_url');
            }else {
                $append_urls_arr = array();
            }
            if(@$_FILES['append']){
                $file_path="uploads/insurance/";
                if(!is_dir($file_path)){
                    mkdir($file_path);
                }
                $file_path .= date("Ymd").'/';
                if(!is_dir($file_path)){
                    mkdir($file_path);
                }
                for($i=0;$i<count($_FILES['append']['name']);$i++){
                    $_FILES['append']['name'][$i] = date("YmdHis").'_'.$_FILES['append']['name'][$i]; //加个时间戳防止重复文件上传后被覆盖
                }
                $filename=$_FILES['append']['name'];

                $arr_jpg=array('jpg','png','pdf');//验证上传文件的格式
                foreach ($filename as $k1 => $v1) {
                    $file_res = explode('.',$v1);
                    if(!in_array($file_res[1], $arr_jpg))
                    {
                        $returnArr['status'] = false;
                        $returnArr['info'] = "上传文件仅支持'jpg,png,pdf'格式!";
                        return json_encode($returnArr); 
                    }
                       
                }

                $filet=$_FILES['append']['tmp_name']; 
                for($i=0;$i<count($filename);$i++){     //循环上传文件的数组
                    //move_uploaded_file($filet[$i],$file_path.iconv("UTF-8","gb2312", $filename[$i]));
                    move_uploaded_file($filet[$i],$file_path.$filename[$i]);
                    array_push($append_urls_arr, $file_path.$filename[$i]);
                }
            }
            $append_urls = json_encode($append_urls_arr);
            $reg_record = $connection->createCommand()->update('cs_car_insurance_business_form',
                          [
                          //'insurance_id'//保险单id
                          //'insurance_id'=>$insurance_id,
                          'number'=>$number,
                          'start_date'=>$start_date,
                          'end_date'=>$end_date,
                          'use_nature'=>$use_nature,
                          'note'=>$note,
                          'insurance_text'=>$insurance_text,
                          'money_amount'=>$money_amount,
                          'add_datetime'=>time(),//系统修改时间
                          'add_aid' => $_SESSION['backend']['adminInfo']['id'],
                          'append_urls'=>$append_urls,
                          ],'id=:id',[':id'=>$id]
                          )->execute();

            if($reg_record){
                $returnArr['status'] = true;
                $returnArr['info'] = '修改成功';
            } else {
                $returnArr['status'] = false;
                $returnArr['info'] = '修改失败';
            }
            return json_encode($returnArr);
        }
        $id = yii::$app->request->get('id');
        $buInfo = (new \yii\db\query())->from('cs_car_insurance_business_form')->where(['id'=>$id])->one();
        $bu_number = (new \yii\db\query())->from('cs_car_insurance_business')->where(['id'=>$buInfo['insurance_id']])->one();
        //var_dump($buInfo);exit;
        //echo '<pre>';
        //var_dump($buInfo);
        //var_dump($id);
        //exit;
         
        return $this->render('pd-bu-edit',['buInfo'=>$buInfo,'bu_number'=>$bu_number]);
    }
}