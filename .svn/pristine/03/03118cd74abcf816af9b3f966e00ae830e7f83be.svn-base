<?php
/**
 * 出险理赔记录控制器
 * time    2016/08/24 11:37
 * @author pengyl
 */
namespace backend\modules\car\controllers;
use backend\models\CarInsuranceClaim;

use backend\models\CarBrand;

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
class InsuranceClaimLogController extends BaseController
{
    public function actionIndex()
    {
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
    			'config'=>$config,
    			'insurerCompany'=>$insurerCompany,
    			'searchFormOptions'=>$searchFormOptions,
    			]);
    }
    
   
    /**
     * 获取列表
     */
    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CarInsuranceClaim::find()
        ->select([
        		'{{%car_insurance_claim}}.*,
        		{{%car_insurance_claim}}.insurance_text _insurance_text,
        		{{%car_insurance_claim}}.claim_text _claim_text,
				
        		{{%car}}.plate_number,
        		{{%car}}.car_model
        		'
        		])
        ->leftJoin('{{%car}}', '{{%car_insurance_claim}}.`car_id` = {{%car}}.`id`')
        ->andWhere(['=','{{%car_insurance_claim}}.`is_del`',0]);
        //查询条件
		$query->andFilterWhere(['=','{{%car}}.`operating_company_id`',yii::$app->request->get('operating_company_id')]);
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
        $wreckers = yii::$app->request->get('wreckers');//三者信息
        if($wreckers){
            $query->andFilterWhere(['like','{{%car_insurance_claim}}.`responsibility_text`','"responsibility_object":"'.$wreckers.'"']);
        }
        if(yii::$app->request->get('claim_amount_start')){
            $query->andFilterWhere(['>=','{{%car_insurance_claim}}.`claim_amount`',yii::$app->request->get('claim_amount_start')]);
        }
        if(yii::$app->request->get('claim_amount_end')){
            $query->andFilterWhere(['<=','{{%car_insurance_claim}}.`claim_amount`',yii::$app->request->get('claim_amount_end')]);
        }
        
        $insurer_type = yii::$app->request->get('insurer_type');
        if($insurer_type){
        	$query->andFilterWhere(['like','{{%car_insurance_claim}}.`insurance_text`',json_encode(yii::$app->request->get('insurer_type'))]);
        }
        if(yii::$app->request->get('claim_time')){
        	$query->andFilterWhere(['like','{{%car_insurance_claim}}.`claim_text`',json_encode(yii::$app->request->get('claim_time'))]);
        }
        if(yii::$app->request->get('transfer_time')){
        	$query->andFilterWhere(['like','{{%car_insurance_claim}}.`transfer_text`',json_encode(yii::$app->request->get('transfer_time'))]);
        }
		if(yii::$app->request->get('number')){
        	$query->andFilterWhere(['like','{{%car_insurance_claim}}.`number`',yii::$app->request->get('number')]);
        }
        $query->andFilterWhere(['like','{{%car_insurance_claim}}.`insurance_text`',yii::$app->request->get('insurer_company')]);
        $query->andFilterWhere(['like','{{%car_insurance_claim}}.`people`',yii::$app->request->get('people')]);
        $query->andFilterWhere(['like','{{%car_insurance_claim}}.`tel`',yii::$app->request->get('tel')]);
        if(yii::$app->request->get('start_danger_date')){
        	$query->andFilterWhere(['>=','{{%car_insurance_claim}}.`danger_date`',yii::$app->request->get('start_danger_date')]);
        }
        if(yii::$app->request->get('end_danger_date')){
        	$query->andFilterWhere(['<=','{{%car_insurance_claim}}.`danger_date`',yii::$app->request->get('end_danger_date')]);
        }
        $status = yii::$app->request->get('status');
        if($status){
        	if($status==7){
        		$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user7`','']);
        	}else if($status==6){
        		$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user6`','']);
        		$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user7', '']);
        	}else if($status==5){
        		$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user5`','']);
        		$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user6', '']);
        	}else if($status==4){
        		$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user4`','']);
        		$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user5', '']);
        	}else if($status==3){
        		$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user3`','']);
        		$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user4', '']);
        	}else if($status==2){
        		$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user2`','']);
        		$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user3', '']);
        	}else if($status==1){
        		$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user1`','']);
        		$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user2', '']);
        	}
        }
		//按归属客户查
		if(yii::$app->request->get('customer')){
			$tdata = explode("_",yii::$app->request->get('customer'));
			if($tdata[0] == 'status'){
				$query->andWhere(['=','{{%car}}.`car_status`',$tdata[1]]);
			}else{
				$query->leftJoin('{{%car_let_record}}', '{{%car}}.`id` = {{%car_let_record}}.`car_id` and
						(
							({{%car_let_record}}.`back_time` >= UNIX_TIMESTAMP({{%car_insurance_claim}}.danger_date) and {{%car_let_record}}.`let_time` <= UNIX_TIMESTAMP({{%car_insurance_claim}}.danger_date))
						 	or 
							(UNIX_TIMESTAMP({{%car_insurance_claim}}.danger_date) >= {{%car_let_record}}.`let_time` and {{%car_let_record}}.`back_time` = 0)
						)
					');
// 				$query->leftJoin('{{%car_let_record}}', '{{%car}}.`id` = {{%car_let_record}}.`car_id` and 
// 				({{%car_let_record}}.`back_time` > UNIX_TIMESTAMP({{%car_insurance_claim}}.danger_date) or {{%car_let_record}}.`back_time` = 0)');
				if($tdata[0] == 'company'){
					$query->andWhere(['=','{{%car_let_record}}.`cCustomer_id`',$tdata[1]]);
				}else {
					$query->andWhere(['=','{{%car_let_record}}.`pCustomer_id`',$tdata[1]]);
				}
			}
		}
	
//             	echo $query->createCommand()->getRawSql();exit;
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
        			$orderBy = '{{%car_insurance_claim}}.`'.$sortColumn.'` ';
        		break;
        	}
        }else{
        	$orderBy = '{{%car_insurance_claim}}.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        //$data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
		$data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->createCommand()->queryAll();
       
        $connection = yii::$app->db;
        foreach ($data as $index=>$row){ 
        	//加载出现状态
            //已报案，等待查勘 2. 已查勘，等待定损 3. 已定损，维修中 4. 维修中，等待理赔 5. 已理赔，保险请款 6. 已请款，等待结案 7. 已结案
        	if($row['oper_user7']){
        		$data[$index]['status'] = '7.已结案';
				$data[$index]['step'] = 8;
        	}else if($row['oper_user6']){
        		$data[$index]['status'] = '6.已请款，等待结案';
				$data[$index]['step'] = 7;
        	}else if($row['oper_user5']){
        		$data[$index]['status'] = '5.已理赔，保险请款';
				$data[$index]['step'] = 6;
        	}else if($row['oper_user4']){
        		$data[$index]['status'] = '4.维修中，等待理赔';
				$data[$index]['step'] = 5;
        	}else if($row['oper_user3']){
        		$data[$index]['status'] = '3.已定损，维修中';
				$data[$index]['step'] = 4;
        	}else if($row['oper_user2']){
        		$data[$index]['status'] = '2.已查勘，等待定损';
				$data[$index]['step'] = 3;
        	}else if($row['oper_user1']){
        		$data[$index]['status'] = '1.已报案，等待查勘';
				$data[$index]['step'] = 2;
        	}else {
        		$data[$index]['status'] = '';
				$data[$index]['step'] = 1;
        	}

            if($row['is_logon'] == 1){
                $data[$index]['status'] = '已注销';
            }
        	//加载出险时归属客户
			$danger_time = strtotime($row['danger_date']);
        	$query = $connection->createCommand(
        			"select cCustomer_id,pCustomer_id,cs_customer_company.company_name,cs_customer_personal.id_name
        			from cs_car_let_record
        			left join cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id
        			left join cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
					where (
							 ({$danger_time} >= let_time and {$danger_time} <= back_time)
						  or ({$danger_time} >=let_time and back_time=0) 
						  )
						  and car_id=".$row['car_id']										
        	);
        	$customer = $query->queryOne();
        	if($customer){
        		if($customer['company_name']){
        			$data[$index]['claim_customer_name'] = $customer['company_name'];
        		}else if($customer['id_name']){
        			$data[$index]['claim_customer_name'] = $customer['id_name'];
        		}
        	}else {
        		$data[$index]['claim_customer_name'] = '无';
        	}
        }
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }
    
//查看详情
    public function actionScan(){
    	$id = yii::$app->request->get('id') or die('param id is required');
    	
    	$connection = yii::$app->db;
    	$insurance_claim = $connection->createCommand('select * from cs_car_insurance_claim where id='.$id)->queryOne();
    	$car_id = $insurance_claim['car_id'];
    	$sql = 'select id,plate_number,brand_id,car_model,car_status from cs_car where id='.$car_id;
    	$data = $connection->createCommand($sql)->queryOne();
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
    	if($customer){
    		if($customer['company_name']){
    			$data['customer_name'] = $customer['company_name'];
    		}else if($customer['id_name']){
    			$data['customer_name'] = $customer['id_name'];
    		}
    	}else {
    		$data['customer_name'] = $config['car_status'][$data['car_status']]['text'];
    	}
    	//加载保险信息
    	$insurance_compulsory = $connection->createCommand(
    			'select id,money_amount,insurer_company,start_date,end_date,note 
    			from cs_car_insurance_compulsory 
    			where car_id='.$data['id'].' order by end_date desc limit 1'
    			)->queryOne();
    	$data['insurance_compulsory'] = $insurance_compulsory;
    	$data['insurance_compulsory']['insurer_company_name'] = $config['INSURANCE_COMPANY'][$insurance_compulsory['insurer_company']]['text'];
    	$insurance_business = $connection->createCommand(
    			'select id,money_amount,insurer_company,start_date,end_date,note,insurance_text 
    			from cs_car_insurance_business 
    			where car_id='.$data['id'].' order by end_date desc limit 1'
    		)->queryOne();
    	$data['insurance_business'] = $insurance_business;
    	$data['insurance_business']['insurer_company_name'] = $config['INSURANCE_COMPANY'][$insurance_business['insurer_company']]['text'];
    	//加载出险信息
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
    	//加载出险时归属客户
    	$query = $connection->createCommand(
    			"select cCustomer_id,pCustomer_id,cs_customer_company.company_name,cs_customer_personal.id_name
    			from cs_car_let_record
    			left join cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id
    			left join cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
    			where (back_time>".strtotime($insurance_claim['danger_date'])." or back_time=0) and car_id=".$data['id']
    	);
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
     * 导出所选择出险理赔记录
     */
    public function actionExportChoose()
    {
        $id = yii::$app->request->get('id') or die('param id is requried');
        $id = trim($id,',');
        $ids = explode(',',$id);
        if(empty($ids)){
            die('no data to export!');
        }
        $excelFile = [];
        foreach($ids as $val){
            $excelFile[] = $this->exportSingle($val);
        }
        $zipFile = dirname(getcwd()).'/runtime/'.time().'.zip';
        File::filesToZip($excelFile,$zipFile);
        File::fileDownload($zipFile);
        foreach($excelFile as $val){
            @unlink($val);
        }
        @unlink($zipFile);
    }

    /**
     * 导出车辆基本信息数据
     */
    protected function exportSingle($id){
    	$connection = yii::$app->db;
    	$claim = $connection->createCommand('select car_id from cs_car_insurance_claim where id='.$id)->queryOne();
    	$car_id = $claim['car_id'];
    	$sql = 'select id,plate_number,brand_id,car_model,car_status from cs_car where id='.$car_id;
    	$data = $connection->createCommand($sql)->queryOne();
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
    	if($customer){
    		if($customer['company_name']){
    			$data['customer_name'] = $customer['company_name'];
    		}else if($customer['id_name']){
    			$data['customer_name'] = $customer['id_name'];
    		}
    	}else {
    		$data['customer_name'] = $config['car_status'][$data['car_status']]['text'];
    	}
    	//加载保险信息
    	$insurance_compulsory = $connection->createCommand(
    			'select id,money_amount,insurer_company,start_date,end_date,note 
    			from cs_car_insurance_compulsory 
    			where car_id='.$data['id'].' order by end_date desc limit 1'
    			)->queryOne();
    	$data['insurance_compulsory'] = $insurance_compulsory;
    	$data['insurance_compulsory']['insurer_company_name'] = $config['INSURANCE_COMPANY'][$insurance_compulsory['insurer_company']]['text'];
    	$insurance_business = $connection->createCommand(
    			'select id,money_amount,insurer_company,start_date,end_date,note,insurance_text 
    			from cs_car_insurance_business 
    			where car_id='.$data['id'].' order by end_date desc limit 1'
    		)->queryOne();
    	$data['insurance_business'] = $insurance_business;
    	$data['insurance_business']['insurer_company_name'] = $config['INSURANCE_COMPANY'][$insurance_business['insurer_company']]['text'];
    	//加载出险信息
    	$insurance_claim = $connection->createCommand(
    			'select *
    			from cs_car_insurance_claim
    			where car_id='.$data['id'].' order by danger_date desc limit 1'
    	)->queryOne();
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
    	//加载出险时归属客户
    	$query = $connection->createCommand(
    			"select cCustomer_id,pCustomer_id,cs_customer_company.company_name,cs_customer_personal.id_name
    			from cs_car_let_record
    			left join cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id
    			left join cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
    			where (back_time>".strtotime($insurance_claim['danger_date'])." or back_time=0) and car_id=".$data['id']
    	);
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

		//生成excel文件
		$excel = new Excel();
        $excel->setHeader([
            'creator'=>'DST',
            'lastModifiedBy'=>'dst',
        ]);
		//导出出险记录详情
        $excel->addLineToExcel([[
            'content'=>'出险记录详情',
            'color'=>'00ff0000',
            'font-weight'=>true,
            'background-rgba'=>'004bacc6',
            'color'=>'00ffffff',
            'border-type'=>'thin',
            'border-color'=>'00ffffff',
            'font-size'=>'14',
            'colspan'=>10,
            'height'=>30,
            'valign'=>'center'
        ]]);
        $lineData = array(
						array('content'=>'车辆品牌','width'=>'24'),
						array('content'=>$data['brand_name'],'width'=>'24'),
						array('content'=>'车辆型号','width'=>'24'),
						array('content'=>$data['car_model_name'],'width'=>'24'),
						array('content'=>'车辆状态','width'=>'24'),
						array('content'=>@$data['customer_name'],'width'=>'24')
					);
		$excel->addLineToExcel($lineData);
		$lineData = array(
						array('content'=>'出险单号','width'=>'24'),
						array('content'=>$data['insurance_claim']['number'],'width'=>'24'),
						array('content'=>'出险状态','width'=>'24'),
						array('content'=>$data['insurance_claim_state'],'width'=>'24'),
						array('content'=>'归属客户','width'=>'24'),
						array('content'=>@$data['claim_customer_name'],'width'=>'24')
					);
		$excel->addLineToExcel($lineData);
		//1.导出报案出险
        $excel->addLineToExcel([[
            'content'=>'报案出险',
            'color'=>'00ff0000',
            'font-weight'=>true,
            'background-rgba'=>'004bacc6',
            'color'=>'00ffffff',
            'border-type'=>'thin',
            'border-color'=>'00ffffff',
            'font-size'=>'14',
            'colspan'=>10,
            'height'=>30,
            'valign'=>'center'
        ]]);
		$lineData = array(
						array('content'=>'车牌号','width'=>'24'),
						array('content'=>$data['insurance_claim']['claim_car'],'width'=>'24'),
						array('content'=>'出险日期','width'=>'24'),
						array('content'=>$data['insurance_claim']['danger_date'],'width'=>'24')
					);
		$excel->addLineToExcel($lineData);
		$lineData = array(
						array('content'=>'报案人','width'=>'24'),
						array('content'=>$data['insurance_claim']['people'],'width'=>'24'),
						array('content'=>'报案人电话','width'=>'24'),
						array('content'=>$data['insurance_claim']['tel'],'width'=>'24'),
						array('content'=>'出险地址','width'=>'24'),
						array('content'=>$data['insurance_claim']['area_detail'],'width'=>'24')
					);
		$excel->addLineToExcel($lineData);
		$lineData = array(
						array('content'=>'上一次操作人员','width'=>'24'),
						array('content'=>$data['insurance_claim']['oper_user1'],'width'=>'24')
					);
		$excel->addLineToExcel($lineData);
		//2.导出查勘结论
        $excel->addLineToExcel([[
            'content'=>'查勘结论',
            'color'=>'00ff0000',
            'font-weight'=>true,
            'background-rgba'=>'004bacc6',
            'color'=>'00ffffff',
            'border-type'=>'thin',
            'border-color'=>'00ffffff',
            'font-size'=>'14',
            'colspan'=>10,
            'height'=>30,
            'valign'=>'center'
        ]]);
		$type_of_survey_arr = array(1=>'保险公司查勘',2=>'快处快赔',3=>'交警查勘',4=>'公估公司',5=>'互碰自赔');
		$lineData = array(
						array('content'=>'查看类型','width'=>'24'),
						array('content'=>$data['insurance_claim']['type_of_survey']?$type_of_survey_arr[$data['insurance_claim']['type_of_survey']]:$data['insurance_claim']['type_detail'],'width'=>'24')
					);
		$excel->addLineToExcel($lineData);
		$responsibilitys = json_decode($data['insurance_claim']['responsibility_text']);
		if(!$responsibilitys){
			$responsibilitys = array();
		}
		foreach ($responsibilitys as $row){
			if($row->responsibility_object==1){	//标的车
				$lineData = array(
						array('content'=>'责任对象','width'=>'24'),
						array('content'=>'标的车','width'=>'24'),
						array('content'=>'','width'=>'24'),
						array('content'=>'','width'=>'24'),
						array('content'=>'责任比重','width'=>'24'),
						array('content'=>$row->specific_gravity,'width'=>'24'),
						array('content'=>'受损情况','width'=>'24'),
						array('content'=>$row->damage_condition,'width'=>'24')
					);
				$excel->addLineToExcel($lineData);
			}else if($row->responsibility_object==2){	//三者车
				$lineData = array(
						array('content'=>'责任对象','width'=>'24'),
						array('content'=>'三者车','width'=>'24'),
						array('content'=>'车牌号','width'=>'24'),
						array('content'=>$row->plate_number,'width'=>'24'),
						array('content'=>'责任比重','width'=>'24'),
						array('content'=>$row->specific_gravity,'width'=>'24'),
						array('content'=>'受损情况','width'=>'24'),
						array('content'=>$row->damage_condition,'width'=>'24')
					);   
				$excel->addLineToExcel($lineData);
			}else if($row->responsibility_object==3){	//三者物
				$lineData = array(
						array('content'=>'责任对象','width'=>'24'),
						array('content'=>'三者物','width'=>'24'),
						array('content'=>'物体名称','width'=>'24'),
						array('content'=>$row->object_name,'width'=>'24'),
						array('content'=>'责任比重','width'=>'24'),
						array('content'=>$row->specific_gravity,'width'=>'24'),
						array('content'=>'受损情况','width'=>'24'),
						array('content'=>$row->damage_condition,'width'=>'24')
					);  
				$excel->addLineToExcel($lineData);
			}else if($row->responsibility_object==4){	//三者人
				$lineData = array(
						array('content'=>'责任对象','width'=>'24'),
						array('content'=>'三者人','width'=>'24'),
						array('content'=>'姓名','width'=>'24'),
						array('content'=>$row->full_name,'width'=>'24'),
						array('content'=>'责任比重','width'=>'24'),
						array('content'=>$row->specific_gravity,'width'=>'24'),
						array('content'=>'受损情况','width'=>'24'),
						array('content'=>$row->damage_condition,'width'=>'24')
					);
				$excel->addLineToExcel($lineData);
			}
		}
		$lineData = array(
						array('content'=>'上一次操作人员','width'=>'24'),
						array('content'=>$data['insurance_claim']['oper_user2'],'width'=>'24')
					);
		$excel->addLineToExcel($lineData);

		//3.导出保险定损
        $excel->addLineToExcel([[
            'content'=>'保险定损',
            'color'=>'00ff0000',
            'font-weight'=>true,
            'background-rgba'=>'004bacc6',
            'color'=>'00ffffff',
            'border-type'=>'thin',
            'border-color'=>'00ffffff',
            'font-size'=>'14',
            'colspan'=>10,
            'height'=>30,
            'valign'=>'center'
        ]]);
		$damageds = json_decode($data['insurance_claim']['damaged_text']);
		$index = 0;
		foreach ($responsibilitys as $row){
			if($row->responsibility_object==1 || $row->responsibility_object==2 || $row->responsibility_object==3){
				if($row->responsibility_object==1){	//标的车
					$lineData = array(
						array('content'=>'标的车定损','width'=>'24'),
						array('content'=>@$damageds[$index]->damaged_money,'width'=>'24'),
						array('content'=>'定损时间','width'=>'24'),
						array('content'=>@$damageds[$index]->damaged_date,'width'=>'24')
					);
					$excel->addLineToExcel($lineData);
				}else if($row->responsibility_object==2){	//三者车
					$lineData = array(
						array('content'=>$row->plate_number.'定损','width'=>'24'),
						array('content'=>@$damageds[$index]->damaged_money,'width'=>'24'),
						array('content'=>'定损时间','width'=>'24'),
						array('content'=>@$damageds[$index]->damaged_date,'width'=>'24')
					);
					$excel->addLineToExcel($lineData);
				}else if($row->responsibility_object==3){	//三者物
					$lineData = array(
						array('content'=>$row->object_name.'定损','width'=>'24'),
						array('content'=>@$damageds[$index]->damaged_money,'width'=>'24'),
						array('content'=>'定损时间','width'=>'24'),
						array('content'=>@$damageds[$index]->damaged_date,'width'=>'24')
					);
					$excel->addLineToExcel($lineData);
				}
				$index++;
			}
		}
		$lineData = array(
						array('content'=>'上一次操作人员','width'=>'24'),
						array('content'=>$data['insurance_claim']['oper_user3'],'width'=>'24')
					);
		$excel->addLineToExcel($lineData);

		//4.导出车辆维修
        $excel->addLineToExcel([[
            'content'=>'车辆维修',
            'color'=>'00ff0000',
            'font-weight'=>true,
            'background-rgba'=>'004bacc6',
            'color'=>'00ffffff',
            'border-type'=>'thin',
            'border-color'=>'00ffffff',
            'font-size'=>'14',
            'colspan'=>10,
            'height'=>30,
            'valign'=>'center'
        ]]);
		$maintenances = json_decode($data['insurance_claim']['maintenance_text']);
		$index=0;
		foreach ($responsibilitys as $row){
			if($row->responsibility_object==1 || $row->responsibility_object==2){
				if($row->responsibility_object==1){	//标的车
					$lineData = array(
						array('content'=>'标的维修厂','width'=>'24'),
						array('content'=>@$maintenances[$index]->maintenance_shop,'width'=>'24'),
						array('content'=>'维修情况','width'=>'24'),
						array('content'=>@$maintenances[$index]->maintenance_condition,'width'=>'24'),
						array('content'=>'维修时间','width'=>'24'),
						array('content'=>@$maintenances[$index]->maintenance_time,'width'=>'24')
					);
					$excel->addLineToExcel($lineData);
				}else if($row->responsibility_object==2){	//三者车
					$lineData = array(
						array('content'=>$row->plate_number.'维修厂','width'=>'24'),
						array('content'=>@$maintenances[$index]->maintenance_shop,'width'=>'24'),
						array('content'=>'维修情况','width'=>'24'),
						array('content'=>@$maintenances[$index]->maintenance_condition,'width'=>'24'),
						array('content'=>'维修时间','width'=>'24'),
						array('content'=>@$maintenances[$index]->maintenance_time,'width'=>'24')
					);
					$excel->addLineToExcel($lineData);
				}
				$index++;
			}
		}
		$lineData = array(
						array('content'=>'上一次操作人员','width'=>'24'),
						array('content'=>$data['insurance_claim']['oper_user4'],'width'=>'24')
					);
		$excel->addLineToExcel($lineData);

		//5.导出保险理赔
        $excel->addLineToExcel([[
            'content'=>'保险理赔',
            'color'=>'00ff0000',
            'font-weight'=>true,
            'background-rgba'=>'004bacc6',
            'color'=>'00ffffff',
            'border-type'=>'thin',
            'border-color'=>'00ffffff',
            'font-size'=>'14',
            'colspan'=>10,
            'height'=>30,
            'valign'=>'center'
        ]]);
		$claims = json_decode($data['insurance_claim']['claim_text']);
		$index = 0;
		$claim_amount = 0;
		foreach ($responsibilitys as $row){
			if($row->responsibility_object==1 || $row->responsibility_object==2 || $row->responsibility_object==3){
				if($row->responsibility_object==1){	//标的车
					$claims[$index] = $claims[$index]?$claims[$index]:array();
					foreach ($claims[$index] as $sub){
						$lineData = array(
							array('content'=>'标的车理赔','width'=>'24'),
							array('content'=>'理赔类型','width'=>'24'),
							array('content'=>$sub->claim_type,'width'=>'24'),
							array('content'=>'保险公司','width'=>'24'),
							array('content'=>$sub->insurance_company,'width'=>'24'),
							array('content'=>'理赔时间','width'=>'24'),
							array('content'=>$sub->claim_time,'width'=>'24'),
							array('content'=>'理赔金额','width'=>'24'),
							array('content'=>$sub->claim_amount,'width'=>'24')
						);
						$claim_amount += $sub->claim_amount;
						$excel->addLineToExcel($lineData);
					}
				}else if($row->responsibility_object==2){	//三者车
					$claims[$index] = @$claims[$index]?$claims[$index]:array();
					foreach ($claims[$index] as $sub){
						$lineData = array(
							array('content'=>$row->plate_number.'理赔','width'=>'24'),
							array('content'=>'理赔类型','width'=>'24'),
							array('content'=>$sub->claim_type,'width'=>'24'),
							array('content'=>'保险公司','width'=>'24'),
							array('content'=>$sub->insurance_company,'width'=>'24'),
							array('content'=>'理赔时间','width'=>'24'),
							array('content'=>$sub->claim_time,'width'=>'24'),
							array('content'=>'理赔金额','width'=>'24'),
							array('content'=>$sub->claim_amount,'width'=>'24')
						);
						$claim_amount += $sub->claim_amount;
						$excel->addLineToExcel($lineData);
					}
				}else if($row->responsibility_object==3){	//三者物
					$claims[$index] = @$claims[$index]?$claims[$index]:array();
					foreach ($claims[$index] as $sub){
						$lineData = array(
							array('content'=>$row->object_name.'理赔','width'=>'24'),
							array('content'=>'理赔类型','width'=>'24'),
							array('content'=>$sub->claim_type,'width'=>'24'),
							array('content'=>'保险公司','width'=>'24'),
							array('content'=>$sub->insurance_company,'width'=>'24'),
							array('content'=>'理赔时间','width'=>'24'),
							array('content'=>$sub->claim_time,'width'=>'24'),
							array('content'=>'理赔金额','width'=>'24'),
							array('content'=>$sub->claim_amount,'width'=>'24')
						);
						$excel->addLineToExcel($lineData);
						$lineData = array(
							array('content'=>'赔付对象','width'=>'24'),
							array('content'=>$sub->claim_customer,'width'=>'24'),
							array('content'=>'赔付账户','width'=>'24'),
							array('content'=>$sub->claim_account,'width'=>'24')
						);
						$excel->addLineToExcel($lineData);
						$claim_amount += $sub->claim_amount;
					}
				}
				$index++;
			}
		}
		$lineData = array(
						array('content'=>'上一次操作人员','width'=>'24'),
						array('content'=>$data['insurance_claim']['oper_user5'],'width'=>'24')
					);
		$excel->addLineToExcel($lineData);
		//6.导出保险请款
        $excel->addLineToExcel([[
            'content'=>'保险请款',
            'color'=>'00ff0000',
            'font-weight'=>true,
            'background-rgba'=>'004bacc6',
            'color'=>'00ffffff',
            'border-type'=>'thin',
            'border-color'=>'00ffffff',
            'font-size'=>'14',
            'colspan'=>10,
            'height'=>30,
            'valign'=>'center'
        ]]);
		$pays = json_decode($data['insurance_claim']['pay_text']);
		if(!$pays){$pays=array();}
		$transfer_amount = 0;
		foreach ($pays as $row){
			$lineData = array(
				array('content'=>'客户名称','width'=>'24'),
				array('content'=>$row->customer_name,'width'=>'24'),
				array('content'=>'开户银行','width'=>'24'),
				array('content'=>$row->bank_account,'width'=>'24'),
				array('content'=>'账户名','width'=>'24'),
				array('content'=>$row->account_name,'width'=>'24'),
				array('content'=>'开户帐号','width'=>'24'),
				array('content'=>$row->account_opening,'width'=>'24')
			);
			$excel->addLineToExcel($lineData);
			$lineData = array(
				array('content'=>'转账金额','width'=>'24'),
				array('content'=>$row->transfer_amount,'width'=>'24'),
				array('content'=>'抵押金额','width'=>'24'),
				array('content'=>$row->rent_amount,'width'=>'24'),
				array('content'=>'请款用途','width'=>'24'),
				array('content'=>$row->please_use,'width'=>'24')
			);
			$excel->addLineToExcel($lineData);
			$transfer_amount += $row->transfer_amount;
		}
		$lineData = array(
			array('content'=>'转账总额','width'=>'24'),
			array('content'=>$transfer_amount,'width'=>'24'),
			array('content'=>'理赔余额','width'=>'24'),
			array('content'=>$claim_amount,'width'=>'24')
		);
		$excel->addLineToExcel($lineData);
		$lineData = array(
						array('content'=>'上一次操作人员','width'=>'24'),
						array('content'=>$data['insurance_claim']['oper_user6'],'width'=>'24')
					);
		$excel->addLineToExcel($lineData);
		//7.导出转账结案
        $excel->addLineToExcel([[
            'content'=>'转账结案',
            'color'=>'00ff0000',
            'font-weight'=>true,
            'background-rgba'=>'004bacc6',
            'color'=>'00ffffff',
            'border-type'=>'thin',
            'border-color'=>'00ffffff',
            'font-size'=>'14',
            'colspan'=>10,
            'height'=>30,
            'valign'=>'center'
        ]]);
		$transfers = json_decode($data['insurance_claim']['transfer_text']);
		foreach ($pays as $index=>$row){
			if(!@$transfers[$index]){
				continue;
			}
			$lineData = array(
				array('content'=>'客户名称','width'=>'24'),
				array('content'=>$row->customer_name,'width'=>'24'),
				array('content'=>'转账时间','width'=>'24'),
				array('content'=>$transfers[$index]->transfer_time,'width'=>'24'),
				array('content'=>'转账凭证','width'=>'24'),
				array('content'=>$transfers[$index]->append_url,'width'=>'24')
			);
			$excel->addLineToExcel($lineData);
		}
		$lineData = array(
						array('content'=>'上一次操作人员','width'=>'24'),
						array('content'=>$data['insurance_claim']['oper_user7'],'width'=>'24')
					);
		$excel->addLineToExcel($lineData);


		//$excel->addLineToExcel($lineData);

		//print_r($lineData);
		//exit;


        $objWriter = \PHPExcel_IOFactory::createWriter($excel->getPHPExcel(), 'Excel2007');
        if(!empty($data['plate_number'])){
            $fileName = iconv('utf-8','gbk',$data['plate_number']);
        }else{
            $fileName = uniqid();
        }
        $excelFileName = dirname(getcwd())."/runtime/{$fileName}.xlsx";
        $objWriter->save($excelFileName);
        return $excelFileName;

		//print_r($data);
		//exit;
    }
    
    /**
     * 下载附近
     */
    public function actionDownload()
    {
    	//echo yii::$app->request->baseUrl;exit;
    	$id = yii::$app->request->get('id') or die('param id is requried');
    	$type = yii::$app->request->get('type') or die('param type is required');
    	if($type==1){	//交强险
    		$model = CarInsuranceCompulsory::findOne(['id'=>$id]);
    	}else if($type==2){	//商业险
    		$model = CarInsuranceBusiness::findOne(['id'=>$id]);
    	}else if($type==3){	//其它险
    		$model = CarInsuranceOther::findOne(['id'=>$id]);
    	}
    	
    	if(@$model->append_urls && @json_decode($model->append_urls)){
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
     * 删除出险理赔记录
     */
    public function actionRemove()
    {
    	$id = yii::$app->request->get('id') or die('param id is required');
    	$model = CarInsuranceClaim::findOne(['id'=>$id]);
    	$model or die('record not found');
    	$returnArr = [];
    	$returnArr['status'] = true;
    	$returnArr['info'] = '';
    	if(CarInsuranceClaim::updateAll(['is_del'=>1],['id'=>$id])){
    		$returnArr['status'] = true;
    		$returnArr['info'] = '记录删除成功！';
    	}else{
    		$returnArr['status'] = false;
    		$returnArr['info'] = '记录删除失败！';
    	}
    	echo json_encode($returnArr);
    }

    /**
     * 注销出险理赔记录
     */
    public function actionLogon()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $model = CarInsuranceClaim::findOne(['id'=>$id]);
        $model or die('record not found');
        $returnArr = [];
        $returnArr['status'] = true;
        $returnArr['info'] = '';
        if(CarInsuranceClaim::updateAll(['is_logon'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '记录注销成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '记录注销失败！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 取消注销出险理赔记录
     */
    public function actionCancelLogon()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $model = CarInsuranceClaim::findOne(['id'=>$id]);
        $model or die('record not found');
        $returnArr = [];
        $returnArr['status'] = true;
        $returnArr['info'] = '';
        if(CarInsuranceClaim::updateAll(['is_logon'=>0],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '记录取消注销成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '记录取消注销失败！';
        }
        echo json_encode($returnArr);
    }
    
    /**
     * 导出出险理赔列表
     */
    public function actionExportWidthCondition()
    {
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
    	$query = CarInsuranceClaim::find()
    	->select([
    			'{{%car_insurance_claim}}.*,
    			{{%car_insurance_claim}}.insurance_text _insurance_text,
    			{{%car_insurance_claim}}.claim_text _claim_text,
    			{{%car}}.plate_number,
    			{{%car}}.car_model'
    			])
    			->leftJoin('{{%car}}', '{{%car_insurance_claim}}.`car_id` = {{%car}}.`id`')
    			->andWhere(['=','{{%car_insurance_claim}}.`is_del`',0]);
    	//查询条件
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
		
        $wreckers = yii::$app->request->get('wreckers');//三者信息
        if($wreckers){
            $query->andFilterWhere(['like','{{%car_insurance_claim}}.`responsibility_text`','"responsibility_object":"'.$wreckers.'"']);
        }
        if(yii::$app->request->get('claim_amount_start')){
            $query->andFilterWhere(['>=','{{%car_insurance_claim}}.`claim_amount`',yii::$app->request->get('claim_amount_start')]);
        }
        if(yii::$app->request->get('claim_amount_end')){
            $query->andFilterWhere(['<=','{{%car_insurance_claim}}.`claim_amount`',yii::$app->request->get('claim_amount_end')]);
        }
    	$insurer_type = yii::$app->request->get('insurer_type');
    	if($insurer_type){
    		$query->andFilterWhere(['like','{{%car_insurance_claim}}.`insurance_text`',json_encode(yii::$app->request->get('insurer_type'))]);
    	}
    	if(yii::$app->request->get('claim_time')){
    		$query->andFilterWhere(['like','{{%car_insurance_claim}}.`claim_text`',json_encode(yii::$app->request->get('claim_time'))]);
    	}
    	if(yii::$app->request->get('transfer_time')){
    		$query->andFilterWhere(['like','{{%car_insurance_claim}}.`transfer_text`',json_encode(yii::$app->request->get('transfer_time'))]);
    	}
    	$query->andFilterWhere(['like','{{%car_insurance_claim}}.`insurance_text`',yii::$app->request->get('insurer_company')]);
    	$query->andFilterWhere(['like','{{%car_insurance_claim}}.`people`',yii::$app->request->get('people')]);
    	$query->andFilterWhere(['like','{{%car_insurance_claim}}.`tel`',yii::$app->request->get('tel')]);
    	if(yii::$app->request->get('start_danger_date')){
    		$query->andFilterWhere(['>=','{{%car_insurance_claim}}.`danger_date`',yii::$app->request->get('start_danger_date')]);
    	}
    	if(yii::$app->request->get('end_danger_date')){
    		$query->andFilterWhere(['<=','{{%car_insurance_claim}}.`danger_date`',yii::$app->request->get('end_danger_date')]);
    	}
    	$status = yii::$app->request->get('status');
    	if($status){
    		if($status==7){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user7`','']);
    		}else if($status==6){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user6`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user7', '']);
    		}else if($status==5){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user5`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user6', '']);
    		}else if($status==4){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user4`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user5', '']);
    		}else if($status==3){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user3`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user4', '']);
    		}else if($status==2){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user2`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user3', '']);
    		}else if($status==1){
    			$query->andWhere(['<>','{{%car_insurance_claim}}.`oper_user1`','']);
    			$query->andWhere(['=', '{{%car_insurance_claim}}.oper_user2', '']);
    		}
    	}
    	//按归属客户查
    	if(yii::$app->request->get('customer')){
    		$tdata = explode("_",yii::$app->request->get('customer'));
    		if($tdata[0] == 'status'){
    			$query->andWhere(['=','{{%car}}.`car_status`',$tdata[1]]);
    		}else{
    			$query->leftJoin('{{%car_let_record}}', '{{%car}}.`id` = {{%car_let_record}}.`car_id` and
    					(
    					({{%car_let_record}}.`back_time` >= UNIX_TIMESTAMP({{%car_insurance_claim}}.danger_date) and {{%car_let_record}}.`let_time` <= UNIX_TIMESTAMP({{%car_insurance_claim}}.danger_date))
    					or
    					(UNIX_TIMESTAMP({{%car_insurance_claim}}.danger_date) >= {{%car_let_record}}.`let_time` and {{%car_let_record}}.`back_time` = 0)
    			)
    					');
    			// 				$query->leftJoin('{{%car_let_record}}', '{{%car}}.`id` = {{%car_let_record}}.`car_id` and
    			// 				({{%car_let_record}}.`back_time` > UNIX_TIMESTAMP({{%car_insurance_claim}}.danger_date) or {{%car_let_record}}.`back_time` = 0)');
    			if($tdata[0] == 'company'){
    				$query->andWhere(['=','{{%car_let_record}}.`cCustomer_id`',$tdata[1]]);
    			}else {
    				$query->andWhere(['=','{{%car_let_record}}.`pCustomer_id`',$tdata[1]]);
    			}
    		}
    	}
		$query->andFilterWhere(['=','{{%car}}.`operating_company_id`',yii::$app->request->get('operating_company_id')]);
		
    	$data = $query->asArray()->all();
		//var_dump($data);
		//保险公司名称和车型名称
        $config = (new ConfigCategory)->getCategoryConfig(['INSURANCE_COMPANY','car_model_name'],'value');
    	$filename = '出险理赔记录.csv'; //设置文件名
    	$str = "出险单号,车牌号,车型名称,所属客户,标的车定损金额,出险日期,报案人,报案电话,保险公司,赔付险种,赔付到账时间,赔付金额,财务转账时间,出险状态,上次修改时间,操作账号\n";   
		
    	$connection = yii::$app->db;
    	foreach ($data as $index => $row){
			
    		$car_mode_name = @$config['car_model_name'][$row['car_model']]['text'];
			$insurance_company = "";	//保险公司
			$insurance = "";			//赔付险种
			$claim_time = "";			//赔付到账时间
			$claim_amount = 0;			//赔付总金额
			$transfer_time = "";		//财务转账时间
			//保险相关数据
			if (!empty($row['insurance_text'])){				
				$insurance_company = json_decode($row['insurance_text'])[0]->insurance_company;
				$insurance = json_decode($row['insurance_text'])[0]->insurance;		
				if (!empty($insurance_company)){
					$insurance_company = $config['INSURANCE_COMPANY'][$insurance_company]['text'];
				}	
				if (!empty($insurance)){
					$insurance = implode("、",$insurance);
				}					
			}
			//赔付相关数据
			if (!empty($row['claim_text'])){	
				$claim_arr = json_decode($row['claim_text']);
				foreach ($claim_arr as $k => $claim_obj) {
					foreach ($claim_obj as $k2 => $amount) {						
						$claim_time .= $amount->claim_time."、";
						$claim_amount += $amount->claim_amount;						
					}
				}								
			}
			$claim_time = ($claim_time=='、')? trim($claim_time,"、"):$claim_time;
			
			//转账结案相关数据
			if (!empty($row['transfer_text'])){	
				$transfer_arr = json_decode($row['transfer_text']);
				$transfer_time = $transfer_arr[0]->transfer_time;
			}
			//var_dump($row);
			//已报案，等待查勘 2. 已查勘，等待定损 3. 已定损，维修中 4. 维修中，等待理赔 5. 已理赔，保险请款 6. 已请款，等待结案 7. 已结案
        	if($row['oper_user7']){
        		$data[$index]['status'] = '7.已结案';
				$data[$index]['step'] = 8;
        	}else if($row['oper_user6']){
        		$data[$index]['status'] = '6.已请款，等待结案';
				$data[$index]['step'] = 7;
        	}else if($row['oper_user5']){
        		$data[$index]['status'] = '5.已理赔，保险请款';
				$data[$index]['step'] = 6;
        	}else if($row['oper_user4']){
        		$data[$index]['status'] = '4.维修中，等待理赔';
				$data[$index]['step'] = 5;
        	}else if($row['oper_user3']){
        		$data[$index]['status'] = '3.已定损，维修中';
				$data[$index]['step'] = 4;
        	}else if($row['oper_user2']){
        		$data[$index]['status'] = '2.已查勘，等待定损';
				$data[$index]['step'] = 3;
        	}else if($row['oper_user1']){
        		$data[$index]['status'] = '1.已报案，等待查勘';
				$data[$index]['step'] = 2;
        	}else {
        		$data[$index]['status'] = '';
				$data[$index]['step'] = 1;
        	}
            if($row['is_logon'] == 1){
                $data[$index]['status'] = '已注销';
            }
			//var_dump($data[$index]);
            //加载出险时归属客户
            $danger_time = strtotime($row['danger_date']);
            $claim_customer_name = '无';
            $query = $connection->createCommand(
            		"select cCustomer_id,pCustomer_id,cs_customer_company.company_name,cs_customer_personal.id_name
            		from cs_car_let_record
            		left join cs_customer_company on cs_car_let_record.cCustomer_id=cs_customer_company.id
            		left join cs_customer_personal on cs_car_let_record.pCustomer_id=cs_customer_personal.id
            		where (
            				({$danger_time} >= let_time and {$danger_time} <= back_time)
            				or ({$danger_time} >=let_time and back_time=0)
						)
            			and car_id=".$row['car_id']);
            $customer = $query->queryOne();
            if($customer){
				if($customer['company_name']){
            		$claim_customer_name = $customer['company_name'];
            	}else if($customer['id_name']){
            		$claim_customer_name = $customer['id_name'];
            	}
            }
			//var_dump($row);
            //标的车定损金额
            $damaged_money = 0;
            $responsibilitys = json_decode($row['responsibility_text']);
            if(!$responsibilitys){
            	$responsibilitys = array();
            }
            if (!empty($row['damaged_text'])){
            	$damageds = json_decode($row['damaged_text']);
            	foreach ($responsibilitys as $index2=>$row1){
            		if(!@$damageds[$index2]){
            			continue;
            		}
            		if($row1->responsibility_object==1){
            			$damaged_money = $damageds[$index2]->damaged_money;
            		}
            	}
            }
			
			if (!isset($data[$index]['status'])) {
				 $data[$index]['status'] = '';
			}
			$row['tel'] = trim($row['tel'],"\n");
    		$str .= "{$row['number']},{$row['plate_number']},{$car_mode_name},{$claim_customer_name},{$damaged_money},{$row['danger_date']},{$row['people']},{$row['tel']},{$insurance_company},{$insurance},{$claim_time},{$claim_amount},{$transfer_time},{$data[$index]['status']},{$row['last_update_time']},{$row['last_update_user']}\n";
			
		}
		//var_dump($str);
    	$str = mb_convert_encoding($str, "GBK", "UTF-8");
    	$this->export_csv($filename,$str); //导出
		

    }

    public function actionTest(){
        $file = fopen('E:\workspace\DST\test1.csv','r'); 
        $list = array();

        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            $list[] = $data;
        }
        $connection = yii::$app->db;
        $validateData = array();
        foreach ($list as $index=>$row) {
            if($index==0){
                continue;
            }
            $r = $this->validateTest($row);
            if($r){
            	array_push($validateData, $r);
            }
        }
        if($validateData){
        	foreach ($validateData as $row){
        		echo "行号：{$row[0]}，车牌：{$row[1]}，出险时间:{$row[2]}<br/>";
        	}
        }else {
        	foreach ($list as $index=>$row) {
        		if($index==0){
        			continue;
        		}
        		$this->add($connection,$row);
        	}
        }
        

        fclose($file);
    }
    function validateTest($obj){
    	$damaged_money1 = $obj[11]; //标地车定损金额
    	$damaged_money2 = $obj[13]; //三者定损金额
    	$damaged_money = $obj[15]; //实际赔付金额
    	
    	if($damaged_money == $damaged_money1+$damaged_money2){
    		$claim_amount1 = $damaged_money1;
    		$claim_amount2 = $damaged_money2;
    	}else {
    		return array($obj[0],mb_convert_encoding($obj[1], "UTF-8", "GBK"),$obj[8]);
    	}
    }
    function add($connection,$obj){
        $car_num = mb_convert_encoding($obj[1], "UTF-8", "GBK");
        $sql = 'select id from cs_car where plate_number="'.$car_num.'"';
        $data = $connection->createCommand($sql)->queryOne();
        $car_id = $data['id'];
        if(!$car_id){
            return false;
        }
        //1.报案出险
        $danger_date = $obj[8];                //出险日期
        $people = mb_convert_encoding($obj[6], "UTF-8", "GBK");     //报案人
        $tel = $obj[7];                                             //报案电话
        $area_detail = '';//出险地点
        $oper_user1 = $_SESSION['backend']['adminInfo']['name'];
        //2.查勘结论
        //responsibility_object：1标的车2三者车3三者物4三者人
        //责任数据
        $responsibilitys = array();
        array_push($responsibilitys, array( //标的
            'responsibility_object'=>'1',
            'plate_number'=>'',
            "full_name"=>"",
            "object_name"=>"",
            "medical_treatment"=>"",
            "disability_rating"=>"",
            "specific_gravity"=>"",
            "damage_condition"=>""
        ));
        $three = mb_convert_encoding($obj[2], "UTF-8", "GBK");     //三者
        $a=preg_match('/['.chr(0xa1).'-'.chr(0xff).']/', $three);   //包含汉字
        $b=preg_match('/[0-9]/', $three);   //包含数字
        $c=preg_match('/[a-zA-Z]/', $three);    //包含英文
        if($a && $b){    //三者车
            $responsibility_object = '2';
        }else if(strstr($three, '人')){  //三者人
            $responsibility_object = '4';
        }else if($three != '无'){  //三者物
            $responsibility_object = '3';
        }
        if(@$responsibility_object){
            array_push($responsibilitys, array(  //三者
                'responsibility_object'=>$responsibility_object,
                'plate_number'=>$responsibility_object=='2'?$three:'',
                "full_name"=>$responsibility_object=='4'?$three:'',
                "object_name"=>$responsibility_object=='3'?$three:'',
                "medical_treatment"=>"",
                "disability_rating"=>"",
                "specific_gravity"=>"",
                "damage_condition"=>""
            ));
        }
        
        //保险数据
        $insurances = array();
        $insurance_company_tmps = array(
        		'人保财'=>'中国人民财产保险有限公司',
        		'人保'=>'中国人民财产保险有限公司',
        		'中国人寿财险'=>'中国人寿财产保险股份有限公司深圳市分公司',
        		'人寿'=>'中国人寿财产保险股份有限公司深圳市分公司',
        		'太平洋'=>'中国太平洋财产保险股份有限公司深圳分公司',
        		'太保'=>'中国太平洋财产保险股份有限公司深圳分公司'
        		);
//         $compulsory_insurance_company = mb_convert_encoding($obj[3], "UTF-8", "GBK");     //交强险保险公司
//         $compulsory_insurance_company = @$insurance_company_tmps[$compulsory_insurance_company]?@$insurance_company_tmps[$compulsory_insurance_company]:$compulsory_insurance_company;
//         $business_insurance_company = mb_convert_encoding($obj[4], "UTF-8", "GBK");     //商业险保险公司
//         $business_insurance_company = @$insurance_company_tmps[$business_insurance_company]?@$insurance_company_tmps[$business_insurance_company]:$business_insurance_company;
        $compulsory_insurance_company = '';
        $business_insurance_company = '';
        
        $sql = 'select value from cs_config_item where text="'.$compulsory_insurance_company.'"';
        $data = $connection->createCommand($sql)->queryOne();
        $compulsory_insurance_company = $data['value'];
        $sql = 'select value from cs_config_item where text="'.$business_insurance_company.'"';
        $data = $connection->createCommand($sql)->queryOne();
        $business_insurance_company = $data['value'];
        array_push($insurances,
            array(
                    'insurance_company'=>$compulsory_insurance_company,
                    'insurance'=>array('交强险')
                )
        );
        if($business_insurance_company != $compulsory_insurance_company){
            array_push($insurances,
                array(
                        'insurance_company'=>$business_insurance_company,
                        'insurance'=>array()
                    )
            );
        }

        $responsibility_text = json_encode($responsibilitys);  //责任text，json格式
        $insurance_text = json_encode($insurances);    //保险text，json格式
        $oper_user2 = $_SESSION['backend']['adminInfo']['name'];
        //3.保险定损
        $damageds = array();
        $damaged_money1 = $obj[11]; //标地车定损金额
        $damaged_money2 = $obj[13]; //三者定损金额
        $damaged_money = $obj[15]; //实际赔付金额
        if($damaged_money1>0 || $damaged_money2>0){
            $oper_user3 = $_SESSION['backend']['adminInfo']['name'];
        }else {
            $oper_user3 = '';
        }
        array_push($damageds,
            array(
                'damaged_money'=>$damaged_money1,
                'damaged_date'=>'',
            ),
            array(
                'damaged_money'=>$damaged_money2,
                'damaged_date'=>'',
            )
        );
        $damaged_text = json_encode($damageds);    //保险定损text，json格式
        //4.车辆维修
        $oper_user4 = $_SESSION['backend']['adminInfo']['name'];
        //5.保险理赔
        $claims = array();
        $claim_type = $obj[14]=='是'?'直赔':'地上铁'; //理赔类型
        $sub_claims = array();
        if($damaged_money == $damaged_money1+$damaged_money2 || $damaged_money == ""){
        	$claim_amount1 = $damaged_money1;
        	$claim_amount2 = $damaged_money2;
        }else {
        	$claim_amount1 = $damaged_money1;
        	$claim_amount2 = 0;
        }
        array_push($sub_claims,
            array(
                'insurance_company'=>$compulsory_insurance_company,
                'claim_type'=>$claim_type,
                'claim_customer'=>'',
                'claim_account'=>'',
                'claim_time'=>mb_convert_encoding($obj[17], "UTF-8", "GBK"),
                'claim_amount'=>$claim_amount1,
                )
        );
        array_push($claims, $sub_claims);
        $sub2_claims = array();
        array_push($sub2_claims, array(
                'insurance_company'=>$compulsory_insurance_company,
                'claim_type'=>$claim_type,
                'claim_customer'=>'',
                'claim_account'=>'',
                'claim_time'=>mb_convert_encoding($obj[17], "UTF-8", "GBK"),
                'claim_amount'=>$claim_amount2,
                ));
        array_push($claims, $sub2_claims);
        
        $oper_user5 = $_SESSION['backend']['adminInfo']['name'];
        $claim_text = json_encode($claims);    //理赔text，json格式
		
        
        $pays = array();
        array_push($pays, array(
        		'customer_name'=>mb_convert_encoding($obj[19], "UTF-8", "GBK"),
        		'customer_name_details'=>'',
        		'bank_account'=>'',
        		'account_name'=>'',
        		'account_opening'=>'',
        		'transfer_amount'=>'',
        		'rent_amount'=>'',
        		'please_use'=>''
        ));
        $pay_text = json_encode($pays);    //保险请款text，json格式
        $transfers = array();
        array_push($transfers, array(
        		'transfer_time'=>mb_convert_encoding($obj[20], "UTF-8", "GBK"),
        		'append_url'=>''
        ));
        $transfer_text = json_encode($transfers);    //转账结案text，json格式
//         echo $pay_text;exit;
        $oper_user6 = '';
        $oper_user7 = '';
        // $oper_user6 = $_SESSION['backend']['adminInfo']['name'];
        // $oper_user7 = $_SESSION['backend']['adminInfo']['name'];

        //理赔编号，格式：LP+日期+3位数（即该故障是系统当天登记的第几个，第一个是001，第二个是002…）
        $sql = 'select count(*) count from cs_car_insurance_claim 
            where add_time>="'.date('Y-m-d').' 00:00:00" and add_time<="'.date('Y-m-d').' 23:59:59"';
        $data = $connection->createCommand($sql)->queryOne();
        $todayCount = $data['count'];
        $currentNo = str_pad($todayCount+1,3,0,STR_PAD_LEFT);
        $number = 'LP' . date('Ymd') . $currentNo;  
        $connection->createCommand()->insert('cs_car_insurance_claim', [
                'car_id' => $car_id,
                'danger_date' => $danger_date,
                'people' => $people,
                'tel' => $tel,
                'area_detail' => $area_detail,
                'number' => $number,
                'add_time' => date('Y-m-d H:i:s'),
                'responsibility_text' => $responsibility_text,
                'insurance_text' => $insurance_text,
                'damaged_text' => $damaged_text,
                'claim_text' => $claim_text,
        		'transfer_text' => $transfer_text,
        		'pay_text' => $pay_text,
                'oper_user1' => $oper_user1,
                'oper_user2' => $oper_user2,
                'oper_user3' => $oper_user3,
                'oper_user4' => $oper_user4,
                'oper_user5' => $oper_user5,
                'oper_user6' => $oper_user6,
                'oper_user7' => $oper_user7,
                'last_update_time' => date('Y-m-d H:i:s'),
                'last_update_user' => $_SESSION['backend']['adminInfo']['name']
                ])->execute();
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
}