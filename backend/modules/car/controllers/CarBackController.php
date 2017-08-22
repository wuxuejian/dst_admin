<?php
/**
 * 退车控制器
 * time    2016/09/22 11:37
 * @author pengyl
 */
namespace backend\modules\car\controllers;
use backend\models\CarBack;

use backend\models\CarBrand;

use backend\models\CarInsuranceOther;

use backend\models\CarDrivingLicense;

use backend\models\AdminRole;
use backend\models\CarInsuranceCompulsory;
use backend\models\RbacMca;
use backend\models\ConfigItem;

use backend\controllers\BaseController;
use backend\models\Car;
use backend\models\CarInsuranceBusiness;
use backend\models\ConfigCategory;
use common\models\Excel;
use common\models\File;
use yii;
use yii\data\Pagination;
use backend\classes\wz;

use backend\classes\Mail;
use backend\models\CarLetRecord;
use backend\models\CarLetContract;


class CarBackController extends BaseController
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
        //400=>51,销售=>52,领导=>53,售后=>54,商务=>55,车管=>56,黄总=>59,财务=>60
//         $roleStates = array(    //角色ID对应待办状态(退车状态,1已登记,2沟通已确认,3领导已审批,4售后已验车,5车辆入库已确认,6押金已结算,7 黄总已审批,8合同已终止,9车辆已入库,20客户取消退车,21领导（退车申请被驳回）,22黄总驳回)
//             51=>'1',
//             52=>'1',
//             53=>'2',
//             54=>'3',
//             55=>'4,8',
//             56=>'3',
//             59=>'6',
//             60=>'7'
//             );    
//         $db_num = 0;    //待办数
//         if($roleIds){
//             $roleIds = array_column($roleIds,'role_id');
//             $db_states = '';
//             foreach ($roleIds as $roleId) {
//                 if(@!$roleStates[$roleId]){
//                     continue;
//                 }
//                 $db_states .= $roleStates[$roleId].',';
//             }
//             if($db_states){
//                 $db_states = substr($db_states,0,strlen($db_states)-1);
//             }
//             $connection = yii::$app->db;
//             if($db_states){
//                 $data = $connection->createCommand(
//                         "select count(*) cnt from cs_car_back where state in (:state)"
//                 )->bindValues([':state'=>$db_states])
//                 ->queryOne();
//                 $db_num = $data['cnt'];
//                 //
//                 $data = $connection->createCommand(
//                         "select count(*) cnt from cs_car_back where is_reject=2 and state=10 and
//                         (oper_user1 = :oper_user or oper_user2 = :oper_user)"
//                 )->bindValues([':oper_user'=>$_SESSION['backend']['adminInfo']['name']])
//                 ->queryOne();
//                 $db_num += $data['cnt'];    //驳回代办数
//             }
//         }
        $db_states = $this->db_states();
        $db_states = implode(",",$db_states);
        $connection = yii::$app->db;
        
        $isLimitedArr = CarBack::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
        	$data = $connection->createCommand(
        			"select count(*) cnt from cs_car_back where is_del=0 and state in ({$db_states}) and operating_company_id in (0,{$isLimitedArr['adminInfo_operatingCompanyId']})"
        			)->queryOne();
        }else {
        	$data = $connection->createCommand(
        			"select count(*) cnt from cs_car_back where is_del=0 and state in ({$db_states})"
        			)->queryOne();
        }
        
        $db_num = $data['cnt'];
        
        //加载所有站点及负责人
        $extract_car_sites = $connection->createCommand(
        		"select a.parent_id,a.tel,b.name from oa_extract_car_site a left join cs_admin b on a.user_id=b.id where a.is_del=0 and a.parent_id>0"
        		)->queryAll();
        foreach ($extract_car_sites as $row){
        	if(!isset($extract_car_site_map[$row['parent_id']])){
        		$extract_car_site_map[$row['parent_id']] = array();
        	}
        	array_push($extract_car_site_map[$row['parent_id']], array('name'=>$row['name'],'tel'=>$row['tel']));
        }
        //加载站点end
    	return $this->render('index',[
    			'buttons'=>$buttons,
    			'config'=>$config,
    			'insurerCompany'=>$insurerCompany,
    			'searchFormOptions'=>$searchFormOptions,
                'db_num'=>$db_num,
    			'extract_car_site_map'=>$extract_car_site_map
    			]);
    }
    
    //上传图片
    public function actionUploadImg(){
    	$_file = @$_FILES['img'];
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	//附件
    	if($_file){
    		$file_path="uploads/carback/";
    		if(!is_dir($file_path)){
    			mkdir($file_path);
    		}
    		$file_path .= date("Ymd").'/';
    		if(!is_dir($file_path)){
    			mkdir($file_path);
    		}
    
    		$file_name = date("YmdHis").'_'.$_file['name']; //加个时间戳防止重复文件上传后被覆盖
    		move_uploaded_file($_file['tmp_name'],$file_path.$file_name);
    		$img_url = $file_path.$file_name;
    		$returnArr['status'] = true;
    		$returnArr['info'] = '';
    		$returnArr['img_url'] = $img_url;
    	}
    	exit(json_encode($returnArr));
    }
    
    function db_states(){
    	//对应待办状态(退车状态,1已登记,2沟通已确认,3领导已审批,4售后已验车,5车辆入库已确认,6押金已结算,7 黄总已审批,8合同已终止,9车辆已入库,20客户取消退车,21领导（退车申请被驳回）,22黄总驳回)
    	$db_states = array();	//待办状态
    	array_push($db_states, 0);
    	if($this->isAccess(2)){
    		array_push($db_states, 1);
    		array_push($db_states, 21);
    	}
    	if($this->isAccess(3)){
    		array_push($db_states, 2);
    	}
    	if($this->isAccess(4)){
    		array_push($db_states, 3);
    	}
    	if($this->isAccess(5)){
    		array_push($db_states, 4);
    	}
    	if($this->isAccess(6)){
    		array_push($db_states, 22);
    		array_push($db_states, 5);
    	}
    	if($this->isAccess(7)){
    		array_push($db_states, 6);
    	}
    	if($this->isAccess(8)){
    		array_push($db_states, 7);
    	}
    	if($this->isAccess(9)){
    		array_push($db_states, 8);
    	}
    	return $db_states;
    }
    
    public function actionRbacAccess(){
    	$index = yii::$app->request->get('index');
    	if(!$index){
    		echo 'false';
    		exit;
    	}
		echo $this->isAccess($index)?'true':'false';
    }
    
    function isAccess($index){
    	if(self::$isSuperman){
    		return true;
    	}
    	$mcaId = RbacMca::find()
    	->select(['id'])
    	->where([
    			'module_code'=>'car',
    			'controller_code'=>'car-back',
    			'action_code'=>'add'.$index
    			])
    			->asArray()
    			->one();
    	return in_array($mcaId['id'],$_SESSION['backend']['accessActionIds']);
    }
    //根据Mca获取用户列表
    function getUserMailByMca($m, $c, $a){
    	$connection = yii::$app->db;
    	//1.获取mca_id
    	$sql = "select id from cs_rbac_mca where module_code='{$m}' and controller_code='{$c}' and action_code='{$a}'";
    	$mca = $connection->createCommand($sql)->queryOne();
    	$mca_id = $mca['id'];
    	//2.根据mca_id获取角色
    	$sql = "select role_id from cs_rbac_role_mca where mca_id={$mca_id}";
    	$rbac_role = $connection->createCommand($sql)->queryAll();
    	$role_ids = '';
    	foreach ($rbac_role as $row){
    		$role_ids .= $row['role_id'].',';
    	}
    	if(!$role_ids){
    		return array();
    	}
    	$role_ids = substr($role_ids, 0, strlen($role_ids)-1);
    	//3.根据role_id获取用户列表
    	$sql = "select admin_id from cs_admin_role where role_id in ({$role_ids})";
    	$admin = $connection->createCommand($sql)->queryAll();
    	$admin_ids = '';
    	foreach ($admin as $row){
    		$admin_ids .= $row['admin_id'].',';
    	}
    	if(!$admin_ids){
    		return array();
    	}
    	$admin_ids = substr($admin_ids, 0, strlen($admin_ids)-1);
    	//4.获取用户邮箱
    	$sql = "select username,email from cs_admin where id in ({$admin_ids})";
    	$user = $connection->createCommand($sql)->queryAll();
    	$emails = array();
    	foreach ($user as $row){
    		if($row['email']){
    			array_push($emails, $row['email']);
    		}
    	}
    	return $emails;
    }
	
    public function actionGetModelList(){
    	$connection = yii::$app->db;
    	$sql = 'select value,text from cs_config_item where belongs_id=62';
    	$data = $connection->createCommand($sql)->queryAll();
    	echo json_encode($data);
    }
    
    //根据客户获取合同列表
    public function actionGetContractList(){
    	$id = yii::$app->request->get('id');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	
    	$connection = yii::$app->db;
    	$sql = 'select c_customer_id,p_customer_id from cs_car_back where id='.$id;
    	$data = $connection->createCommand($sql)->queryOne();
    	$customer_id = 0;
    	if($data['c_customer_id']){
    		$customer_id = $data['c_customer_id'];
    	}
    	if($data['p_customer_id']){
    		$customer_id = $data['p_customer_id'];
    	}
    	if(!$customer_id){
    		$returnArr['info'] = '客户不存在！';
    		exit(json_encode($returnArr));
    	}
    	
    	$data = $connection->createCommand(
    			"select number from cs_car_let_contract where cCustomer_id=:customer_id or pCustomer_id=:customer_id"
    	)->bindValues([':customer_id'=>$customer_id])
    	->queryAll();
    	echo json_encode($data);
    }
    
    //根据合同获取车辆列表MARK
    public function actionGetContractCars(){
    	$number = isset($_REQUEST['number']) ? trim($_REQUEST['number']) : ''; // 检索过滤字符串
    	$is_all = yii::$app->request->get('is_all');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	if(!$number){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$connection = yii::$app->db;
    	$sql = 'select id,end_time from cs_car_let_contract where number="'.$number.'"';
    	$data['contract'] = $connection->createCommand($sql)->queryOne();
    	if(!$data['contract']){
    		$returnArr['info'] = '合同不存在';
    		exit(json_encode($returnArr));
    	}
    	$contract_id = $data['contract']['id'];
    	$sql = "select b.plate_number,b.car_model,b.car_status 
			    from cs_car_let_record a left join cs_car b on a.car_id=b.id and b.is_del=0 
				where contract_id=".$contract_id;
    	if(!$is_all){
    		$sql .= ' and a.back_time=0';
    	}
    	$data['cars'] = $connection->createCommand($sql)->queryAll();
    	
    	echo json_encode($data);
    }
    
    //获取合同时间
    public function actionGetContractTime(){
        $number = isset($_REQUEST['number']) ? trim($_REQUEST['number']) : ''; // 检索过滤字符串
        $connection = yii::$app->db;
        $data = $connection->createCommand(
                "select end_time from cs_car_let_contract where number=:number"
        )->bindValues([':number'=>$number])
        ->queryOne();
        if(!$data){
            $data['end_time'] = "0";
        }
        $data['end_time'] = date('Y-m-d',$data['end_time']);
        echo json_encode($data);  
    }

    //获取客户列表
    public function actionGetCustomers()
    {
        $queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
        $connection = yii::$app->db;
        $data = $connection->createCommand(
                "(select company_name customer_name from cs_customer_company 
                where is_del=0 and company_name like :customer_name 
                group by company_name limit 10)
                union all
                (select id_name from cs_customer_personal 
                where is_del=0 and id_name like :customer_name 
                group by id_name limit 10)
                "
        )->bindValues([':customer_name'=>'%'.$queryStr.'%'])
        ->queryAll();
        echo json_encode($data);
    }

    //获取车辆
    public function actionGetCars()
    {
        $queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
        $connection = yii::$app->db;
        // $car_models = $connection->createCommand(
        //         "select value,text from cs_config_item where belongs_id=62"
        // )
        // ->queryAll();
        // print_r($car_models);
        // exit;
        $data = $connection->createCommand(
                "select a.plate_number,a.car_model,b.text car_model_name from cs_car a
                left join cs_config_item b on a.car_model=b.value where a.plate_number like :plate_number"
        )->bindValues([':plate_number'=>'%'.$queryStr.'%'])
        ->queryAll();
        foreach ($data as $index => $value) {
            $data[$index]['car_model'] = $data[$index]['car_model'];
        }
        echo json_encode($data);
    }

    //1.  客户退车意愿登记
    public function actionAdd1(){
        $id = yii::$app->request->post('id');
        $other_customer_name = yii::$app->request->post('other_customer_name');
        $customer_name = yii::$app->request->post('customer_name');
        $customer_tel = yii::$app->request->post('customer_tel');
        $returnArr['status'] = false;
        $returnArr['info'] = '';

        $connection = yii::$app->db;
        $c_customer_id = 0;
        $p_customer_id = 0;
        $sql = 'select id from cs_customer_company where is_del=0 and company_name="'.$customer_name.'"';
        $data = $connection->createCommand($sql)->queryOne();
        if($data){
            $c_customer_id = $data['id'];
        }else {
            $sql = 'select id from cs_customer_personal where is_del=0 and id_name="'.$customer_name.'"';
            $data = $connection->createCommand($sql)->queryOne();
            if($data){
                $p_customer_id = $data['id'];
            }
        }
        
        if($id){
            $r = $connection->createCommand()->update('cs_car_back', [
                    'c_customer_id' => $c_customer_id,
                    'p_customer_id' => $p_customer_id,
                    'other_customer_name' => $other_customer_name,
                    'customer_tel' => $customer_tel,
                    'oper_user1' => $_SESSION['backend']['adminInfo']['name'],
                    'oper_time1' => time(),
                    'last_update_user' => $_SESSION['backend']['adminInfo']['name'],
                    'last_update_time' => time()
                    ],
                    'id=:id', 
                    array(':id'=>$id)
                    )->execute();
            $returnArr['status'] = true;
        }else{
            //退车编号，格式：TC+日期+3位数（即该故障是系统当天登记的第几个，第一个是001，第二个是002…）
            /* $sql = 'select count(*) count from cs_car_back
                where add_time>="'.date('Y-m-d').' 00:00:00" and add_time<="'.date('Y-m-d').' 23:59:59"'; */
            $sql = 'select count(*) count from cs_car_back
            	where add_time>="'.strtotime(date('Y-m-d')).'" and add_time<="'.strtotime(date('Y-m-d 23:59:59')).'"';
            
            $data = $connection->createCommand($sql)->queryOne();
            $todayCount = $data['count'];
            $currentNo = str_pad($todayCount+1,3,0,STR_PAD_LEFT);
            $number = 'TC' . date('Ymd') . $currentNo;  
            $connection->createCommand()->insert('cs_car_back', [
                    'state' =>1,
                    'c_customer_id' => $c_customer_id,
                    'p_customer_id' => $p_customer_id,
                    'other_customer_name' => $other_customer_name,
                    'customer_tel' => $customer_tel,
                    'number' => $number,
                    'oper_user1' => $_SESSION['backend']['adminInfo']['name'],
                    'oper_time1' => time(),
                    'add_time' => time(),
                    'last_update_user' => $_SESSION['backend']['adminInfo']['name'],
                    'last_update_time' => time()
                    ])->execute();
            $id = $connection->getLastInsertID();
            if($id){
                $returnArr['status'] = true;
                $returnArr['id'] = $id;
            }
        }
        $returnArr['status'] = true;
        $returnArr['info'] = '';
        echo json_encode($returnArr);
    }

    public function actionGet1(){
        $id = yii::$app->request->get('id');
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        if(!$id){
            $returnArr['info'] = '缺少参数';
            exit(json_encode($returnArr));
        }
        $connection = yii::$app->db;
        $sql = 'select a.*,b.company_name,c.id_name from cs_car_back a 
                left join cs_customer_company b on a.c_customer_id=b.id
                left join cs_customer_personal c on a.p_customer_id=c.id where a.id='.$id;
        $data = $connection->createCommand($sql)->queryOne();
        $returnArr['status'] = true;
        $data['wz_text'] = json_decode($data['wz_text']);
        $data['oper_time1'] = $data['oper_time1']?date("Y-m-d H:i:s", $data['oper_time1']):'';
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
    }
    
    //车辆加解锁
    function car_lock($connection, $car_ids, $car_back_id, $oper){
    	if(!$car_ids){
    		return false;
    	}
    	if($oper=='lock'){
    		$connection->createCommand($sql)->execute();
    		foreach ($car_ids as $car_id){
    			try {
    				$r = $connection->createCommand()->insert('cs_car_lock', [
    						'car_id' =>$car_id,
    						'car_back_id' =>$car_back_id
    						])->execute();
    			} catch (yii\db\Exception $e) {
    				return false;
    			}
    		}
    	}else {
    		$connection->createCommand('delete from cs_car_lock where car_id in('.implode(",", $car_ids).')')->execute();
    	}
    	return true;
    }

    //2.    销售沟通确认
    public function actionAdd2(){
        $id = yii::$app->request->post('id');
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        $connection = yii::$app->db;
        if(!$id){
            $returnArr['info'] = '缺少参数';
            exit(json_encode($returnArr));
        }
        $customer_addr = yii::$app->request->post('customer_addr'); //客户地址
        $back_cause = yii::$app->request->post('back_cause');   //退车原因
        $back_time = yii::$app->request->post('back_time'); //预计还车时间
        $cancel_back_cause = yii::$app->request->post('cancel_back_cause'); //取消退车原因
        $back_type = yii::$app->request->post('back_type');	//退车类型
        $extract_car_site_id = yii::$app->request->post('extract_car_site_id');	//提车站点ID
        $note2 = yii::$app->request->post('note2');
        if(!$back_time){
        	$returnArr['info'] = '请选择预计还车时间';
        }
        if(!$back_type){
        	$returnArr['info'] = '请选择退车类型';
        }
        if(!$extract_car_site_id){
        	$returnArr['info'] = '请选择还车站场';
        }
        if(!$back_cause){
        	$returnArr['info'] = '请选择退车原因';
        }
        if($returnArr['info']){
        	exit(json_encode($returnArr));
        }
        
        //合同text
        $contracts = array();
        $contract_text = '';
        $ids = array();	//所有传递过来的勾选车辆	
		$t_all_contract_car_ids = array();	//所有合同已经勾选的退车车辆
		//查出退车流程中勾选的要退车辆
		$sql = 'SELECT contract_text FROM cs_car_back WHERE id="'.$id.'" AND is_del=0';
		$the_contract = $connection->createCommand($sql)->queryOne();
		if ($the_contract && $the_contract['contract_text'] != null && $the_contract['contract_text'] != ''){
			$the_contract = json_decode($the_contract['contract_text']);
			foreach ($the_contract as $contract_key => $contract_value) {				
				if (isset($contract_value->car_ids) && $contract_value->car_ids != '') {
					$t_ids_arr = explode(',',$contract_value->car_ids); //每个合同对应已经勾选的要退车辆id						
					$t_all_contract_car_ids = array_merge($t_all_contract_car_ids,$t_ids_arr);			
				}
			}
		}
				
        for($i=1; $i<10; $i++){
        	$contract_number = yii::$app->request->post('contract_number'.$i);
        	if(!$contract_number){
        		continue;
        	}
        	$car_nos = yii::$app->request->post('car_no'.$i);
        	$car_ids = '';
        	if(!$car_nos){
        		$car_nos = array();
        	}
        	foreach ($car_nos as $row) {
        		$sql = 'select id from cs_car where plate_number="'.$row.'" and is_del=0';
        		$data = $connection->createCommand($sql)->queryOne();
        		$car_ids .= $data['id'].',';
        		array_push($ids, $data['id']);
        	}
        	if($car_ids){
        		$car_ids = substr($car_ids, 0, strlen($car_ids)-1);
        	}
        	$break_contract_type = yii::$app->request->post('break_contract_type'.$i);
        	$contract_time = yii::$app->request->post('contract_time'.$i);
        	$contract_end_time = yii::$app->request->post('contract_end_time'.$i);	//合同止租时间
        	$break_contract_money = yii::$app->request->post('break_contract_money'.$i);
        	if(!$break_contract_type){
        		$returnArr['info'] = '请选择合同违约情况';
        		exit(json_encode($returnArr));
        	}
        				
			array_push($contracts, array(
                        'contract_number'=>$contract_number,
        				'car_ids'=>$car_ids,
                        'break_contract_type'=>$break_contract_type,
	        			'contract_time'=>$contract_time,
	        			'break_contract_money'=>$break_contract_money,
						'contract_end_time'=>$contract_end_time
                    ));
        }
        $operating_company_id = $_SESSION['backend']['adminInfo']['operating_company_id'];
        
        if($cancel_back_cause){ //取消退车
        	$transaction = $connection->beginTransaction();
            $result = $connection->createCommand()->update('cs_car_back', [
                    'state' => 20,
                    'cancel_back_cause' => $cancel_back_cause,
                    'oper_user2' => $_SESSION['backend']['adminInfo']['name'],
            		'operating_company_id' => $operating_company_id,
                    'oper_time2' => time()
                    ],
                    'id=:id',
                    array(':id'=>$id)
            )->execute();
			
			//所有都勾选掉了的情况,所有旧的变回租赁中（状态变更改到第4步）
// 			if ($t_all_contract_car_ids) {
// 				$statusRet = Car::changeCarStatusNew($t_all_contract_car_ids, 'LETING', 'car/car-back/add2', '退车流程销售沟通确认，取消退车',['car_status'=>'BACK','is_del'=>0]);
// 			}
			
			if($result){
				$transaction->commit();  //提交事务
			}else {
				$transaction->rollback(); //回滚事务
				$returnArr['status'] = false;
				$returnArr['info'] = '操作失败，请确认车辆当前状态！';
				exit(json_encode($returnArr));
			}
        }else { //退车进入下一流程
        	//验证车辆是否存在其它流程
	        if($connection->createCommand('select count(*) cnt from cs_car_lock where car_id in ('.implode(",", $ids).')')->queryOne()['cnt'] > 0){
	        	$returnArr['status'] = false;
	        	$returnArr['info'] = '操作失败，请确认车辆是否存在其它流程！';
	        	exit(json_encode($returnArr));
	        }
        	if(!$contracts){
        		$returnArr['status'] = false;
        		$returnArr['info'] = '请选择合同编号并勾选要退还的车辆！';
        		exit(json_encode($returnArr));
        	}
        	foreach ($contracts as $contract){
        		if(!$contract['car_ids']){
        			$returnArr['status'] = false;
        			$returnArr['info'] = '请选择合同编号并勾选要退还的车辆！';
        			exit(json_encode($returnArr));
        		}
//         		if(!$contract['contract_end_time']){
//         			$returnArr['status'] = false;
//         			$returnArr['info'] = '请选择合同实际止租时间！';
//         			exit(json_encode($returnArr));
//         		}
        	}
        	//新的合同信息
        	$contract_text = json_encode($contracts);
        	//上传附件
        	$append_url1 = yii::$app->request->post('append_url1');
        	if(@$_FILES['append1']){
        		$file_path="uploads/carback/";
        		if(!is_dir($file_path)){
        			mkdir($file_path);
        		}
        		$file_path .= date("Ymd").'/';
        		if(!is_dir($file_path)){
        			mkdir($file_path);
        		}
        	
        		$_FILES['append1']['name'] = date("YmdHis").'_'.$_FILES['append1']['name']; //加个时间戳防止重复文件上传后被覆盖
        		move_uploaded_file($_FILES['append1']['tmp_name'],$file_path.$_FILES['append1']['name']);
        		$append_url1 = $file_path.$_FILES['append1']['name'];
        	}
        	
			//这里更新新的退车车辆		
            $result = $connection->createCommand()->update('cs_car_back', [
                    'state' => 2,
                    'note2' => $note2,
            		'contract_text' => $contract_text,
//                     'car_ids' => $car_ids,
                    'append_url1' => $append_url1,
                    'customer_addr' => $customer_addr,
                    'back_cause' => $back_cause,
            		'back_type' => $back_type,
            		'extract_car_site_id' => $extract_car_site_id,
                    'back_time' => $back_time,
            		'operating_company_id' => $operating_company_id,
                    'oper_user2' => $_SESSION['backend']['adminInfo']['name'],
                    'oper_time2' => time(),
                    'last_update_user' => $_SESSION['backend']['adminInfo']['name'],
                    'last_update_time' => time()
                    ],
                    'id=:id',
                    array(':id'=>$id)
            )->execute();
           
            if(!$result){
            	$returnArr['status'] = false;
            	$returnArr['info'] = '操作失败，请确认车辆当前状态！';
            	exit(json_encode($returnArr));
            }
			
            //邮件通知领导审批
            $mail = new Mail();
            $subject = '退车申请审批';
            $body ="你有一个待处理的事项：【退车申请审批】。该退车申请销售与客户沟通已确认退车。请及时登录地上铁系统进行审批，以免影响工作进度。点击这里：<a href='http://xt.dstzc.com'>登录地上铁系统</a>，或者在浏览器中输入以下地址登录 ：xt.dstzc.com 。如果对此有疑问和建议，请向系统开发部反馈。";
            
            $user_emails = $this->getUserMailByMca('car','car-back','add3');
            $mail->send($user_emails,$subject, $body);
        }
        $returnArr['status'] = true;
        echo json_encode($returnArr);
    }
    public function actionGet2(){
        $id = yii::$app->request->get('id');
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        if(!$id){
            $returnArr['info'] = '缺少参数';
            exit(json_encode($returnArr));
        }
        $connection = yii::$app->db;

        $sql = 'select a.*,b.company_name,b.company_addr,c.id_name,c.id_address,d.name extract_car_site_name from cs_car_back a 
                left join cs_customer_company b on a.c_customer_id=b.id
                left join cs_customer_personal c on a.p_customer_id=c.id
                left join oa_extract_car_site d on a.extract_car_site_id=d.id
        		 where a.id='.$id;
        $data = $connection->createCommand($sql)->queryOne();
//         if($data['car_ids']){
//             $data['cars'] = $connection->createCommand('select plate_number from cs_car where id in('.$data['car_ids'].')')->queryAll();   
//          }else {
//             $data['cars']= array();
//          }
         
         $data['contract_text'] = json_decode($data['contract_text']);
         if(!$data['contract_text']){
         	$data['contract_text'] = array();
         }
         foreach ($data['contract_text'] as $index=>$row){
         	if(!$data['contract_text'][$index]->car_ids){
         		continue;
         	}
         	$sql = 'select plate_number,car_model from cs_car where id in ('.$data['contract_text'][$index]->car_ids.')';
         	$cars = $connection->createCommand($sql)->queryAll();
         	$data['contract_text'][$index]->plate_numbers = $cars;
         }
         $data['oper_time2'] = $data['oper_time2']?date("Y-m-d H:i:s", $data['oper_time2']):'';
         $data['oper_time3'] = $data['oper_time3']?date("Y-m-d H:i:s", $data['oper_time3']):'';

        $returnArr['status'] = true;
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
    }

    /**
     * 违章查询接口
     */
    function wz_query($carno,$engineno,$classno,$car_type){
        $wz = new wz();
        $data = $wz->query('GD_SZ',$carno,$engineno,$classno,$car_type);
        return $data;
    }
    //3.    领导审批
    public function actionAdd3(){
        $id = yii::$app->request->post('id');
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        $connection = yii::$app->db;
        if(!$id){
            $returnArr['info'] = '缺少参数';
            exit(json_encode($returnArr));
        }
        $is_reject = yii::$app->request->post('is_reject');
        $reject_cause = yii::$app->request->post('reject_cause'); //驳回原因
       
        if($is_reject == 2){ //驳回
            $connection->createCommand()->update('cs_car_back', [
                    'state' => 21,
                    'is_reject' => 2,
                    'reject_cause' => $reject_cause,
                    'oper_user3' => $_SESSION['backend']['adminInfo']['name'],
                    'oper_time3' => time()
                    ],
                    'id=:id',
                    array(':id'=>$id)  
            )->execute();
        }else { //进入下一流程
        	//1.获取已选中车辆ID
        	$car_back_data = $connection->createCommand('select contract_text from cs_car_back where id='.$id)->queryOne();
        	$contracts = json_decode($car_back_data['contract_text']);
        	$car_ids = [];
        	foreach ($contracts as $contract) {
        		if ($contract->car_ids && $contract->car_ids != '') {						
					$car_ids = array_merge($car_ids,explode(',',$contract->car_ids));
        		}
        	}
        	
        	$transaction = $connection->beginTransaction();
        	$lock_r = $this->car_lock($connection, $car_ids, $id, 'lock');
            $r = $connection->createCommand()->update('cs_car_back', [
                    'state' => 3,
                    'is_reject' => 1,
                    'oper_user3' => $_SESSION['backend']['adminInfo']['name'],
                    'oper_time3' => time(),
                    'last_update_user' => $_SESSION['backend']['adminInfo']['name'],
                    'last_update_time' => time()
                    ],
                    'id=:id',
                    array(':id'=>$id)
                )->execute();
            //（状态变更改到第4步）
            if($r && $lock_r){
            	$transaction->commit();  //提交事务
            	$this->wzRefresh($id);
            }else {
            	$transaction->rollback(); //回滚事务
            	$returnArr['status'] = false;
            	if(!$lock_r){
            		$returnArr['info'] = '操作失败，请确认车辆是否存在其它流程！';
            	}
            	exit(json_encode($returnArr));
            }
        }
        $returnArr['status'] = true;
        echo json_encode($returnArr);
    }
    
    //更新违章信息(老版本，废弃)
    function wzRefresh_1($id){
    	$connection = yii::$app->db;
    	//查询违章
    	$car_back_data = $connection->createCommand('select contract_text from cs_car_back where id='.$id)->queryOne();
    	if(!$car_back_data['contract_text']){
    		return false;
    	}
    	$contracts = json_decode($car_back_data['contract_text']);
    	$car_ids = '';
    	foreach ($contracts as $row){
    		$car_ids .= $row->car_ids.',';
    	}
    	if($car_ids){
    		$car_ids = substr($car_ids, 0, strlen($car_ids)-1);
    	}else {
    		return false;
    	}
    	$cars = $connection->createCommand('select plate_number,vehicle_dentification_number,engine_number,car_type from cs_car where id in('.$car_ids.')')->queryAll();
    	set_time_limit(0);
    	//error_reporting(0);
    	$wzs = array();
    	foreach ($cars as $row){
    		if(strlen($row['plate_number'])<5){
    			continue;
    		}
    		$data = $this->wz_query($row['plate_number'],$row['engine_number'],$row['vehicle_dentification_number'],$row['car_type']);
    		if($data['resultcode'] == 200){ //成功返回
    			$wz_list = $data['result'];
    			//foreach ($wz_list as $row1) {
    			//....$row['fen'],$row['date'],$row['area'],$data['city'],$data['province'],$row['code'],$row['act'],$row['money'],$data['hphm'],$data['hpzl'],$row['handled']
    	
    			array_push($wzs, $wz_list);
    			//}
    		}else {
    			array_push($wzs,
    					array(
    							'hphm'=>$row['plate_number'],
    							'lists'=>array()
    					)
    			);
    		}
    	}
    	$wz_text = json_encode($wzs);
    	//更新违章信息
    	$connection->createCommand()->update('cs_car_back', [
    			'wz_text' => $wz_text
    			],
    			'id=:id',
    			array(':id'=>$id)
    	)->execute();
    }
    public function actionWzRefresh(){
    	$id = yii::$app->request->get('id');
    	$returnArr['status'] = false;
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$this->wzRefresh($id);
    	$returnArr['status'] = true;
    	echo json_encode($returnArr);
    }
	//违章复核
	public function actionWzRefreshNew(){
    	$id = yii::$app->request->get('id');
    	$returnArr['status'] = false;
		//var_dump($id);exit;
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$this->wzRefreshNew($id);
    	$returnArr['status'] = true;
    	echo json_encode($returnArr);
    }
	//格式化违章信息
	function formatWz($wz_text) {
		$result = array();
		if ($wz_text != '' && $wz_text != '[]') {
			$wz_text = json_decode($wz_text);
			foreach ($wz_text as $wz) {
				if (count($wz->lists) != 0) {
					foreach ($wz->lists as $one) {
						$key = $wz->hphm."_".$one->date;
						$result[$key] = $one->code;
					}
				}				
			}
			return $result;
		}
		return array();
	}
	
	//新版违章更新代码
	 function wzRefresh($id){		
    	$connection = yii::$app->db;
    	//查询退车流程中的合同内容
    	$car_back_data = $connection->createCommand('select contract_text,wz_text from cs_car_back where id='.$id)->queryOne();
    	if(!$car_back_data['contract_text']){
    		return false;
    	}
    	$contracts = json_decode($car_back_data['contract_text']); 
		set_time_limit(0);
		$wzs = array();		
		//遍历合同
		foreach ($contracts as $row){
			//根据每个合同的number查到合同的有效期			
			$contracts_time_range = $connection->createCommand("select start_time,end_time from cs_car_let_contract where number='".$row->contract_number."'")->queryOne();
			//然后拿每个退车合同中要退的车，查到违章查询需要的字段
			$cars = $connection->createCommand('select plate_number,vehicle_dentification_number,engine_number,car_type from cs_car where id in('.$row->car_ids.')')->queryAll();			
			//一条一条查询违章
			foreach ($cars as $car_row){
				if(strlen($car_row['plate_number'])<5){
					continue;
				}
				$data = $this->wz_query($car_row['plate_number'],$car_row['engine_number'],$car_row['vehicle_dentification_number'],$car_row['car_type']);				
				// var_dump($data);
				//成功返回违章记录
				if($data['resultcode'] == 200){ 				
					$wz_list = $data['result'];						
					if ($wz_list && !empty($wz_list['lists'])){
						// var_dump($wz_list['lists']);
						//查询车辆的每条违章是否在合同期内
						foreach ($wz_list['lists'] as $wz_key => $the_one) {
							$start_time = date("Y-m-d h:i:s",$contracts_time_range['start_time']);
							$end_time =date("Y-m-d h:i:s",$contracts_time_range['end_time']);
							$the_time = $the_one->date;							
							// var_dump($start_time);
							// var_dump($end_time);
							// var_dump($the_time);
							if ( $start_time <= $the_time && $the_time <= $end_time) {																	
							} else {
								//var_dump($wz_list['lists'][$wz_key]);
								unset($wz_list['lists'][$wz_key]);
							}							
						}
						if (!empty($wz_list['lists'])){ 
							// var_dump($wz_list);
							$wz_list['lists'] = array_values($wz_list['lists']);
							array_push($wzs, $wz_list);						
						}
					}					
				}				
			}			
		}		
    	$wz_text = json_encode($wzs);
		// var_dump($wz_text);
    	//更新新违章信息
    	$connection->createCommand()->update('cs_car_back', [
    			'wz_text' => $wz_text    			
    			],
    			'id=:id',
    			array(':id'=>$id)
    	)->execute();
    }

	//更新违章复核信息
    function wzRefreshNew($id){		
    	$connection = yii::$app->db;
    	//查询退车流程中的合同内容
    	$car_back_data = $connection->createCommand('select contract_text,wz_text from cs_car_back where id='.$id)->queryOne();
    	if(!$car_back_data['contract_text']){
    		return false;
    	}
    	$contracts = json_decode($car_back_data['contract_text']);    	
		//格式化违章信息成【车牌号_时间】的格式
		$wz_text = $this->formatWz($car_back_data['wz_text']);
		// var_dump(json_decode($car_back_data['wz_text'])[7]);
		// var_dump(json_decode($car_back_data['wz_text'])[19]);
		// var_dump($wz_text);
		// exit;
		
		set_time_limit(0);
		$wzs = array();
		// var_dump($contracts);exit;
		//遍历合同
		foreach ($contracts as $row){
			//根据每个合同的number查到合同的有效期			
			$contracts_time_range = $connection->createCommand("select start_time,end_time from cs_car_let_contract where number='".$row->contract_number."'")->queryOne();
			//然后拿每个退车合同中要退的车，查到违章查询需要的字段
			$cars = $connection->createCommand('select plate_number,vehicle_dentification_number,engine_number,car_type from cs_car where id in('.$row->car_ids.')')->queryAll();
			// var_dump($cars);
			//一条一条查询违章
			foreach ($cars as $car_row){
				if(strlen($car_row['plate_number'])<5){
					continue;
				}
				$data = $this->wz_query($car_row['plate_number'],$car_row['engine_number'],$car_row['vehicle_dentification_number'],$car_row['car_type']);
				// var_dump($data);exit;
				//成功返回违章记录
				if($data['resultcode'] == 200){ 
				// var_dump($data['result']);exit;
					$wz_list = $data['result'];	
					// var_dump($wz_list);exit;
					if ($wz_list && !empty($wz_list['lists'])){
						//查询车辆的每条违章是否在合同期内
						// var_dump($wz_list['lists'] );exit;
						
						foreach ($wz_list['lists'] as $wz_key => $the_one) {
							$start_time = date("Y-m-d h:i:s",$contracts_time_range['start_time']);
							$end_time =date("Y-m-d h:i:s",$contracts_time_range['end_time']);
							$the_time = $the_one->date;
							
							// var_dump($start_time);
							// var_dump($end_time);
							// var_dump($the_time);
							// var_dump($start_time <= $the_time && $the_time <= $end_time);
							// var_dump("----");
							
							if ( $start_time <= $the_time && $the_time <= $end_time) {								
								if ($wz_text) {
									// var_dump($wz_list);
									// var_dump($wz_text);
									// var_dump($wz_list['hphm'].'_'.$the_one->date);
									// var_dump(!isset($wz_text[$wz_list['hphm'].'_'.$the_one->date]));
									// var_dump($wz_list);
									// var_dump($wz_text[$wz_list->hphm.'_'.$the_one->date]);
									// 在违章记录中找不到对应记录，则是新增违章
									if (isset($wz_text[$wz_list['hphm'].'_'.$the_one->date])) {
										// array_push($wzs, $wz_list);
										// var_dump($wz_list['lists'][$wz_key]);
										unset($wz_list['lists'][$wz_key]);
									}
								} //else {//之前没有保存违章记录，查出来的都是新增违章
									//array_push($wzs, $wz_list);
									
								//}
							} else {
								unset($wz_list['lists'][$wz_key]);
							}
							
						}
						if (!empty($wz_list['lists'])){ 
							// var_dump($wz_list);
							$wz_list['lists'] = array_values($wz_list['lists']);										
							array_push($wzs, $wz_list);
						}
						// var_dump($wzs);
						// exit;
					}					
				}				
			}			
		}
		//判断这条违章是否已经存在于wz_text中，没有存在则是新增违章    	
		// var_dump($wzs);exit;
		//$wz_text $wzs
		
		// if ($wzs) {
			//查询出的违章如果存在于之前的违章记录中则不处理。否则算是新增违章
			// foreach ($wzs as $k =>$wz) {
				// foreach ($wz_text as $old_wz) {
					// if ($wz->['hphm'] == $old_wz['hphm']) {						
						// unset($wzs[$k]);
					// }
				// }				
			// }
		// }		
		
    	$wz_text = json_encode($wzs);
    	//更新新违章信息
    	$connection->createCommand()->update('cs_car_back', [
    			'wz_text_new' => $wz_text,
    			// 'wz_text_new' => $car_back_data['wz_text'],
    			'is_wz_review' => 1
    			],
    			'id=:id',
    			array(':id'=>$id)
    	)->execute();
    }

    public function actionGet3(){
        $id = yii::$app->request->get('id');
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        if(!$id){
            $returnArr['info'] = '缺少参数';
            exit(json_encode($returnArr));
        }
        $connection = yii::$app->db;
        $sql = 'select * from cs_car_back where id='.$id;
        $data = $connection->createCommand($sql)->queryOne();

        $returnArr['status'] = true;
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
    }
	
    //4.    售后验车
    public function actionAdd4(){
        $id = yii::$app->request->post('id');
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        $connection = yii::$app->db;
        if(!$id){
            $returnArr['info'] = '缺少参数';
            exit(json_encode($returnArr));
        }
        //场站负责人验证
        $car_back = $connection->createCommand(
        		'select * from cs_car_back where id = '.$id
        )->queryOne();
        if($car_back['extract_car_site_id']){
        	$ok_user_ids = array();
        	$oa_extract_car_site = $connection->createCommand(
        			'select user_id from oa_extract_car_site where parent_id = '.$car_back['extract_car_site_id']
        	)->queryAll();
        	foreach ($oa_extract_car_site as $row){
        		array_push($ok_user_ids, $row['user_id']);
        	}
        	if(!in_array($_SESSION['backend']['adminInfo']['id'], $ok_user_ids)){
        		$returnArr['info'] = '场站负责人验证失败！';
        		exit(json_encode($returnArr));
        	}
        }        
        //场站负责人验证end
        $back_time2 = yii::$app->request->post('back_time2');   //退车日期
        $insurance_confirm_state = yii::$app->request->post('insurance_confirm_state');   //保险确认状态
        $insurance_note = yii::$app->request->post('insurance_note');   //保险备注
        $wz_confirm_state = yii::$app->request->post('wz_confirm_state');   //违章确认状态
//         $car_confirm_state = yii::$app->request->post('car_confirm_state');   //车辆入库状态
        $car_confirm_note = yii::$app->request->post('car_confirm_note');   //车辆入库备注
        
        $car_nos = yii::$app->request->post('car_no');   			//车牌
        $damage_moneys = yii::$app->request->post('damage_money'); //定损报价
        $positions = yii::$app->request->post('position');   		//损失部位
        $img_urls = yii::$app->request->post('img_url');   		//附件
        $damage_peoples = yii::$app->request->post('damage_people'); //验车人
        
        $is_backs = yii::$app->request->post('is_back'); //1，车辆是否收回
        $no_storages = yii::$app->request->post('no_storage'); //1，车辆无法入库
        $into_times = yii::$app->request->post('into_time'); //进场时间
        
        //定损text
        $damages = array();
        $damage_text = '';
        if(!$car_nos){
        	$car_nos = array();
        }
        $back_num = 0;	//车辆回收数量
        foreach ($car_nos as $index=>$car_no){
        	$sql = 'select id from cs_car where plate_number="'.$car_no.'" and is_del=0';
        	$car = $connection->createCommand($sql)->queryOne();
        	if(!$car){
        		continue;
        	}
        	$car_id = $car['id'];
        	$damage_money = $damage_moneys[$index];
            if($damage_money == ''){
                $damage_money = 0;
            }
        	$position = $positions[$index];
        	$img_url = @$img_urls[$index];
        	$damage_people = @$damage_peoples[$index];
        	$is_back = @$is_backs[$index];
        	if($is_back == 1){
        		$back_num++;
        	}
        	$no_storage = @$no_storages[$index];
        	$into_time = @$into_times[$index];
        	
        	array_push($damages, array(
        			'car_id'=>$car_id,
        			'damage_money'=>$damage_money,
        			'position'=>$position,
        			'img_url'=>$img_url,
        			'damage_people'=>$damage_people,
        			'is_back'=>$is_back,
        			'no_storage'=>$no_storage,
        			'into_time'=>$into_time
        	));
        }
        $damage_text = json_encode($damages);
        
        $transaction = $connection->beginTransaction();
        //////状态变更处理start
        $tmp_car_storage = json_decode($car_back['car_storage_text']);	//上一次入库车辆
        $tmp_car_storage_ids = [];
        if($tmp_car_storage){
        	foreach ($tmp_car_storage as $row){
        		array_push($tmp_car_storage_ids, $row->car_id);
        	}
        }
        $old_damage = json_decode($car_back['damage_text']);
        //1.将所有已回收、未入库车辆状态变更为租赁中
        if($old_damage){
        	$tmp_car_ids = array(); 
        	foreach ($old_damage as $row){
        		if($row->is_back && !in_array($row->car_id, $tmp_car_storage_ids)){
        			array_push($tmp_car_ids, $row->car_id);
        		}
        	}
        	if($tmp_car_ids){
        		$statusRet1 = Car::changeCarStatusNew($tmp_car_ids, 'LETING', 'car/car-back/add4', '退车流程售后验车，车辆回收',['car_status'=>'BACK','is_del'=>0]);
        	}
        }
        //2.这里把传递过来的变退车中状态，包括新增的，原有的，不包括已入库车辆
        $tmp_car_ids = array();
        foreach ($damages as $row){
        	if($row['is_back'] && !in_array($row['car_id'], $tmp_car_storage_ids)){
        		array_push($tmp_car_ids, $row['car_id']);
        	}else if(!$row['is_back'] && in_array($row['car_id'], $tmp_car_storage_ids)){
        		$returnArr['info'] = '有已入库车辆，不能取消回收';
        		exit(json_encode($returnArr));
        	}
        }
        if($tmp_car_ids){
        	$statusRet2 = Car::changeCarStatusNew($tmp_car_ids, 'BACK', 'car/car-back/add4', '退车流程售后验车，车辆回收',['car_status'=>'LETING','is_del'=>0]);
        }		
		
		//每条违章记录的车牌号+日期=作为违章价格单价的key
        $fenjia = yii::$app->request->post('fenjia');//每条违章记录的的代办费单价
		//查询出退车记录的wz_text解析成数组，
		$sql = 'select wz_text from cs_car_back where id='.$id;
        $the_car_back = $connection->createCommand($sql)->queryOne();
        if($the_car_back){
			$wz_text = json_decode($the_car_back['wz_text']);
		}
		
		//遍历代办费单价数组
		if ($fenjia) {
			foreach($fenjia as $key => $value) {
				$the_key_exp = explode('_',$key);//截取出代办费的车牌号和时间
				$the_hphm = $the_key_exp[0];
				$the_date = $the_key_exp[1];
				
				$i = 0;
				foreach ($wz_text as $wz_key =>$wz) {
						
					if ($wz->hphm == $the_hphm) {
						foreach ($wz->lists as $list_key => $the_list) {
							if ($the_list->date == $the_date) {
								$wz_text[$wz_key]->lists[$list_key]->fenjia = $value;
							}
							
						}
					}
					
				}				
			}	
		}
		$wz_text = json_encode($wz_text);
        //////状态变更处理end
        $result = $connection->createCommand()->update('cs_car_back', [
                'insurance_confirm_state' => $insurance_confirm_state,
                'insurance_note' => $insurance_note,
        		'wz_confirm_state' => $wz_confirm_state,
//                 'car_confirm_state' => $car_confirm_state,
                'car_confirm_note' => $car_confirm_note,
//                 'state' => ($wz_confirm_state==1 && $insurance_confirm_state==1)?4:3,
        		'state' => $back_num>0?4:3,
                'damage_text' => $damage_text,
                'wz_text' => $wz_text,
                'back_time2' => $back_time2,
                'oper_user4' => $_SESSION['backend']['adminInfo']['name'],
                'oper_time4' => time(),
                'last_update_user' => $_SESSION['backend']['adminInfo']['name'],
                'last_update_time' => time()
                ],
                'id=:id',
                array(':id'=>$id)
        )->execute();
        
        if($result && ($statusRet1?$statusRet1['status']:true) && ($statusRet2?$statusRet2['status']:true)){
        	$transaction->commit();  //提交事务
        }else {
        	$transaction->rollback(); //回滚事务
        	$returnArr['status'] = false;
        	$returnArr['info'] = '操作失败，请确认车辆当前状态！';
        	exit(json_encode($returnArr));
        }
//         echo $query->createCommand()->getRawSql();exit;
        
        $returnArr['status'] = true;
        echo json_encode($returnArr);
    }
    public function actionGet4(){
        $id = yii::$app->request->get('id');
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        if(!$id){
            $returnArr['info'] = '缺少参数';
            exit(json_encode($returnArr));
        }
        $connection = yii::$app->db;
        $sql = 'select b.name extract_car_site,b.id extract_car_site_user,state,extract_car_site_id,oper_user4,oper_time4,back_time2,wz_text,damage_text,contract_text,wz_confirm_state,insurance_confirm_state,insurance_note,car_confirm_note 
        from cs_car_back a left join oa_extract_car_site b on a.extract_car_site_id=b.id where a.id='.$id;
        $data = $connection->createCommand($sql)->queryOne();

        $returnArr['status'] = true;
        //加载站点负责人
        $sql = 'select b.name from oa_extract_car_site a left join cs_admin b on a.user_id=b.id where a.parent_id<>0 and a.parent_id = '.$data['extract_car_site_id'];
        $extract_car_site_user = $connection->createCommand($sql)->queryAll();
        $data['extract_car_site_user'] = $extract_car_site_user;
        //违章列表
        $data['wz_text'] = $data['wz_text']?json_decode($data['wz_text']):array();
        
        $data['damage_text'] = json_decode($data['damage_text']);
        if(!$data['damage_text']){
        	$data['damage_text'] = array();
        }
        foreach ($data['damage_text'] as $index=>$row){
        	if(!$data['damage_text'][$index]->car_id){
        		continue;
        	}
        	$sql = 'select plate_number from cs_car where id = '.$data['damage_text'][$index]->car_id;
        	$car = $connection->createCommand($sql)->queryOne();
        	$data['damage_text'][$index]->plate_number = $car['plate_number'];
        }
        
        //出险理赔信息列表
        $data['contract_text'] = json_decode($data['contract_text']);
        $insurance_claims = array();
        $plate_numbers = array();
        foreach ($data['contract_text'] as $index=>$row){
        	if(!$data['contract_text'][$index]->car_ids){
        		continue;
        	}
        	$sql = 'select id,plate_number,car_model from cs_car where id in ('.$data['contract_text'][$index]->car_ids.')';
        	$cars = $connection->createCommand($sql)->queryAll();
        	foreach ($cars as $index1=>$car){
        		$carInsuranceClaim = $this->getCarInsuranceClaim($connection, $car['id'], $row->contract_number, $car['plate_number']);
        		if($carInsuranceClaim){
//         			$cars[$index1]['insurance'] = $carInsuranceClaim;
        			array_push($insurance_claims, $carInsuranceClaim);
        		}
        		array_push($plate_numbers, $car['plate_number']);
        	}
        	$data['contract_text'][$index]->plate_numbers = $cars;
        }
        unset($data['contract_text']);
//         $data['contract_text'] = array();
        $data['insurance_claims'] = $insurance_claims;
        $data['plate_numbers'] = $plate_numbers;
        $data['oper_time4'] = $data['oper_time4']?date("Y-m-d H:i:s", $data['oper_time4']):'';
        
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
    }
    function getCarInsuranceClaim($connection, $car_id, $contract_number,$plate_number){
        $data = $connection->createCommand('select * from cs_car_let_contract where is_del=0 and number="'.$contract_number.'"')->queryOne();
        //根据合同起始时间获取出现理赔记录
        if($data){
            $sql = 'select number,oper_user1,oper_user2,oper_user3,oper_user4,oper_user5,oper_user6,oper_user7 from cs_car_insurance_claim where danger_date>="'.date('Y-m-d',$data['start_time']).'" and danger_date <= "'.date('Y-m-d',$data['end_time']).'" and is_del=0 and car_id='.$car_id;
            $insurance_claim_data = $connection->createCommand($sql)->queryAll();

            $r_data = array();
            foreach ($insurance_claim_data as $index => $row) {
                $insurance_status = '';
                if($row['oper_user7']){
                    $insurance_status = '7.已结案';
                }else if($row['oper_user6']){
                    $insurance_status = '6.已请款，等待结案';
                }else if($row['oper_user5']){
                    $insurance_status = '5.已理赔，保险请款';
                }else if($row['oper_user4']){
                    $insurance_status = '4.维修中，等待理赔';
                }else if($row['oper_user3']){
                    $insurance_status = '3.已定损，维修中';
                }else if($row['oper_user2']){
                    $insurance_status = '2.已查勘，等待定损';
                }else if($row['oper_user1']){
                    $insurance_status = '1.已报案，等待查勘';
                }
                array_push($r_data, array(
                        'plate_number'=>$plate_number,
                        'number'=>$row['number'],
                        'insurance_status'=>$insurance_status,
                    ));
            }
            return $r_data;
        }
        return false;
        //print_r($data);
        // print_r(array($car_no, $contract_number));
    }
    
    //5.车辆确认入库MARK2
    public function actionAdd5(){
    	$id = yii::$app->request->post('id');
    	$returnArr['status'] = false;
    	$returnArr['info'] = '';
    	$connection = yii::$app->db;
    	if(!$id){
    		$returnArr['info'] = '缺少参数';
    		exit(json_encode($returnArr));
    	}
    	$car_back_data = $connection->createCommand('select contract_text,car_storage_text from cs_car_back where id='.$id)->queryOne();
    	$contracts = json_decode($car_back_data['contract_text']);
    	$contract_car_num = 0;	//合同退车数量
    	foreach ($contracts as $row){
    		if($row->car_ids){
    			$contract_car_num += count(explode(',',$row->car_ids));
    		}
    	}
    	
    	$car_nos = yii::$app->request->post('car_no');   			//车牌
    	if(!$car_nos){
    		$car_nos = array();
    	}
    	$car_storages = array();
		$car_ids_for = "";
		$tmp_car_storage = json_decode($car_back_data['car_storage_text']);	//上一次入库车辆
		$tmp_car_storage_ids = [];
		if($tmp_car_storage){
			foreach ($tmp_car_storage as $row){
				array_push($tmp_car_storage_ids, $row->car_id);
			}
		}
    	foreach ($car_nos as $index=>$car_no){
    		$sql = 'select id from cs_car where plate_number="'.$car_no.'" and is_del=0';
    		$car = $connection->createCommand($sql)->queryOne();
    		if(!$car){
    			continue;
    		}
    		$car_id = $car['id'];
    		//移除上一次入库车辆
    		if(!in_array($car_id, $tmp_car_storage_ids)){
    			$car_ids_for .= $car['id'].",";
    		}
    		array_push($car_storages, array(
    				'car_id'=>$car_id
    		));
    	}
    	
		$car_ids_for = trim($car_ids_for,",");
    	$car_storage_text = json_encode($car_storages);
    	
    	//开始事务
    	$transaction = $connection->beginTransaction();
    	$result = $connection->createCommand()->update('cs_car_back', [
    			'state' => $contract_car_num == count($car_nos)?5:4,
    			'car_storage_text' => $car_storage_text,
    			'oper_user4_1' => $_SESSION['backend']['adminInfo']['name'],
    			'oper_time4_1' => time(),
    			'last_update_user' => $_SESSION['backend']['adminInfo']['name'],
    			'last_update_time' => time()
    			],
    			'id=:id',
    			array(':id'=>$id)
    	)->execute();
    	
    	if($contract_car_num == count($car_nos)){	//进入第6步，解锁车辆
    		$tmp_contracts = json_decode($car_back_data['contract_text']);
    		$tmp_car_ids = [];
    		foreach ($tmp_contracts as $tmp_contract) {
    			if ($tmp_contract->car_ids && $tmp_contract->car_ids != '') {
    				$tmp_car_ids = array_merge($tmp_car_ids,explode(',',$tmp_contract->car_ids));
    			}
    		}
    		$this->car_lock($connection, $tmp_car_ids, $id, 'unlock');
    	}
    	
        #完成退车时间的操作 1.11 
       
       // var_dump($contracts);exit;
        //$car_ids = '';
        if($car_ids_for){
    		foreach($contracts as $key=>$value){
    			//合同
    			$contractnumber = $value->contract_number;
    		
    			//查询出合同的id(多个id)
    			$carletcontract = CarLetContract::find()
    			->select([
    					'{{%car_let_contract}}.id'
    					])->andFilterWhere(['{{%car_let_contract}}.`number`'=>$contractnumber])->asArray()->limit(1)->one();
    		
    			$connection->createCommand()->update('cs_car_let_record',['back_time'=>time()],'contract_id = '.$carletcontract['id'].' and car_id in ('.$car_ids_for.')')
    			->execute();
    		}
    	}
        

    	//确认是否选中车辆
// 		$car_back_data2 = $connection->createCommand('select contract_text,car_storage_text from cs_car_back where id='.$id)->queryOne();    	
//     	if(!$car_back_data2['car_storage_text']){
//     		$returnArr['status'] = false;
// 			$returnArr['info'] = '未选中任何车辆';
//     		exit(json_encode($returnArr));
//     	}
// 		$car_ids = $car_ids_for;
//     	if(!$car_ids){    		
//     		$returnArr['status'] = true;
// 			$returnArr['info'] = '未选中任何车辆';
//     		exit(json_encode($returnArr));
//     	}
    	
    	//1.所有旧的变回退车中
//     	if($car_back_data['car_storage_text']){
//     		$tmp_ids = array();
//     		$car_storage_list = json_decode($car_back_data['car_storage_text']);
//     		foreach ($car_storage_list as $row){
//     			array_push($tmp_ids, $row->car_id);
//     		}
//     		$statusRet1 = Car::changeCarStatusNew($tmp_ids, 'BACK', 'car/car-back/add5', '退车流程车辆入库',['car_status'=>'STOCK','is_del'=>0]);
//     	}
    	//2.这里把当次传递过来的变库存状态
    	if($car_ids_for){
    		$statusRet2 = Car::changeCarStatusNew(explode(",",$car_ids_for), 'STOCK', 'car/car-back/add5', '退车流程车辆入库',['car_status'=>'BACK','is_del'=>0]);
    	}
    	
    	if($result && ($statusRet1?$statusRet1['status']:true) && ($statusRet2?$statusRet2['status']:true)){
    		$transaction->commit();  //提交事务
    	}else {
    		$transaction->rollback(); //回滚事务
    		$returnArr['status'] = false;
    		$returnArr['info'] = '操作失败，请确认车辆当前状态！';
    		exit(json_encode($returnArr));
    	}
    	
    	//         echo $query->createCommand()->getRawSql();exit;
    	$returnArr['status'] = true;
    	echo json_encode($returnArr);
    }
	public function actionGet5(){
        $id = yii::$app->request->get('id');
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        if(!$id){
            $returnArr['info'] = '缺少参数';
            exit(json_encode($returnArr));
        }
        $connection = yii::$app->db;
        $sql = 'select state,wz_text,damage_text,contract_text,car_storage_text,oper_user4_1,oper_time4_1 from cs_car_back where id='.$id;
        $data = $connection->createCommand($sql)->queryOne();
        $returnArr['status'] = true;
        
        $data['contract_text'] = json_decode($data['contract_text']);
        foreach ($data['contract_text'] as $index=>$row){
        	if(!$data['contract_text'][$index]->car_ids){
        		continue;
        	}
        	$sql = 'select plate_number,car_model from cs_car where id in ('.$data['contract_text'][$index]->car_ids.')';
        	$cars = $connection->createCommand($sql)->queryAll();
        	$data['contract_text'][$index]->plate_numbers = $cars;
        }
        $data['car_storage_text'] = json_decode($data['car_storage_text']);
        if(!$data['car_storage_text']){
        	$data['car_storage_text'] = array();
        }
        foreach ($data['car_storage_text'] as $index=>$row){
        	if(!$data['car_storage_text'][$index]->car_id){
        		continue;
        	}
        	$sql = 'select plate_number,car_model from cs_car where id = '.$data['car_storage_text'][$index]->car_id;
        	$car = $connection->createCommand($sql)->queryOne();
        	$data['car_storage_text'][$index]->plate_number = $car['plate_number'];
        	$data['car_storage_text'][$index]->car_model = $car['car_model'];
        }
        $data['damage_text'] = json_decode($data['damage_text']);
        if(!$data['damage_text']){
        	$data['damage_text'] = array();
        }
        foreach ($data['damage_text'] as $index=>$row){
        	if(!$data['damage_text'][$index]->car_id){
        		continue;
        	}
        	$sql = 'select plate_number from cs_car where id = '.$data['damage_text'][$index]->car_id;
        	$car = $connection->createCommand($sql)->queryOne();
        	$data['damage_text'][$index]->plate_number = $car['plate_number'];
        }
        $data['wz_text'] = json_decode($data['wz_text']);
        $data['oper_time4_1'] = $data['oper_time4_1']?date("Y-m-d H:i:s", $data['oper_time4_1']):'';
        if(!$data['wz_text']){
        	$data['wz_text'] = array();
        }
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
    }

    //6.    押金结算
    public function actionAdd6(){
        $id = yii::$app->request->post('id');
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        $connection = yii::$app->db;
        if(!$id){
            $returnArr['info'] = '缺少参数';
            exit(json_encode($returnArr));
        }
        $penalty_money = yii::$app->request->post('penalty_money'); //违约金
        $foregift_money = yii::$app->request->post('foregift_money');   //押金
        $back_money = yii::$app->request->post('back_money');   //结算退还金额
        $back_time3 = yii::$app->request->post('back_time3');   //退还时间
        $note5 = yii::$app->request->post('note5');
        $charge_card = yii::$app->request->post('charge_card');   //充电卡
        $arrear_date = yii::$app->request->post('arrear_date');	//租金欠款日期
        $arrear_money = yii::$app->request->post('arrear_money');	//租金欠款金额
        $payment_money = yii::$app->request->post('payment_money');	//实际定损赔付
        
        //租金欠款text
        $arrears = array();
        if(!$arrear_date){
        	$arrear_date = array();
        }
        foreach ($arrear_date as $index=>$value){
        	array_push($arrears, array('date'=>$arrear_date[$index], 'money'=>$arrear_money[$index]));
        }
        $arrear_text = json_encode($arrears);
        //end
        
        $sql = 'select * from cs_car_back where id='.$id;
        $data = $connection->createCommand($sql)->queryOne();
        $wzs = json_decode($data['wz_text']);
        $wzs_new = json_decode($data['wz_text_new']);		
		
		//每条违章记录的车牌号+日期=作为违章价格单价的key
        $fenjia = yii::$app->request->post('fenjia');//每条违章记录的的代办费单价		
		//遍历代办费单价数组
		if ($fenjia) {
			foreach($fenjia as $key => $value) {
				$the_key_exp = explode('_',$key);//截取出代办费的车牌号和时间
				$the_hphm = $the_key_exp[0];
				$the_date = $the_key_exp[1];
				// var_dump($key);exit;
				$i = 0;
				foreach ($wzs_new as $wz_key =>$wz) {
					// var_dump($wz);exit;			
					if ($wz->hphm == $the_hphm) {
						foreach ($wz->lists as $list_key => $the_list) {
							if ($the_list->date == $the_date) {
								$wzs_new[$wz_key]->lists[$list_key]->fenjia = $value;
							}
							//var_dump($the_list);exit;
						}
					}
					// var_dump($wz_text[$wz_key]);exit;
				}		
				// var_dump($value);exit;
			}	
		}			
		$wz_text_new = json_encode($wzs_new);
		
        if(!$wzs){
        	$wzs = array();
        }
		if(!$wzs_new){
        	$wzs_new = array();
        }
        $wz_money = 0;	//违章总额
       	foreach ($wzs as $wz){
       		foreach ($wz->lists as $wz1){
       			$wz_money += (($wz1->fen * $wz1->fenjia) + $wz1->money);
       		}
       	}
	foreach ($wzs_new as $wz){
       		foreach ($wz->lists as $wz1){
       			$wz_money += (($wz1->fen * $wz1->fenjia) + $wz1->money);
       		}
       	}
				
       	//计算定损金额
       	$data['damage_text'] = json_decode($data['damage_text']);
       	if(!$data['damage_text']){
       		$data['damage_text'] = array();
       	}
       	$damage_money = 0;
       	foreach ($data['damage_text'] as $index=>$row){
       		$damage_money += $data['damage_text'][$index]->damage_money;
       	}
       	//违约金+违章总额+定损总额＞押金的50% 时，需要黄总审核
       	if(($penalty_money+$wz_money+$damage_money)>$foregift_money/2){
       		$state = 6;
       	}else {
       		$state = 7;
       	}
        
        $connection->createCommand()->update('cs_car_back', [
                'state' => $state,
                'note5' => $note5,
                'penalty_money' => $penalty_money,
                'foregift_money' => $foregift_money,
        		'payment_money' => $payment_money,
                'back_money' => $back_money,
                'back_time3' => $back_time3,
        		'wz_text_new' => $wz_text_new,
        		'arrear_text' => $arrear_text,
        		'charge_card' => $charge_card,
                'oper_user5' => $_SESSION['backend']['adminInfo']['name'],
                'oper_time5' => time(),
                'last_update_user' => $_SESSION['backend']['adminInfo']['name'],
                'last_update_time' => time()
                ],
                'id=:id',
                array(':id'=>$id)
        )->execute();
        if($state == 6){	//邮件通知领导审批
        	$mail = new Mail();
        	$subject = '退车结算异常审批';
        	$body = "你有一个待处理的事项：【退车结算异常审批】。该退车流程验车结束，商务金额结算完毕。请及时登录地上铁系统进行审批，以免影响工作进度。点击这里：<a href='http://xt.dstzc.com'>登录地上铁系统</a>，或者在浏览器中输入以下地址登录 ：xt.dstzc.com 。如果对此有疑问和建议，请向系统开发部反馈。";
        	//获取权限用户
        	$user_emails = $this->getUserMailByMca('car','car-back','add7');
        	$mail->send($user_emails,$subject, $body);
        }
        $returnArr['status'] = true;
        echo json_encode($returnArr);
    }
    public function actionGet6(){
        $id = yii::$app->request->get('id');
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        if(!$id){
            $returnArr['info'] = '缺少参数';
            exit(json_encode($returnArr));
        }
        $connection = yii::$app->db;
        $sql = 'select * from cs_car_back where id='.$id;
        $data = $connection->createCommand($sql)->queryOne();
		
        $data['wz_text'] = json_decode($data['wz_text']);
	if ($data['wz_text_new'] == null || $data['wz_text_new'] == '') {
			$data['wz_text_new'] = '[]';
		}
        $data['wz_text_new'] = json_decode($data['wz_text_new']);
        $data['damage_text'] = json_decode($data['damage_text']);
        $data['arrear_text'] = json_decode($data['arrear_text']);
        //计算违约金
        $data['contract_text'] = json_decode($data['contract_text']);
        if(!$data['contract_text']){
        	$data['contract_text'] = array();
        }
        $break_contract_money = 0;
        foreach ($data['contract_text'] as $index=>$row){
        	$break_contract_money += $data['contract_text'][$index]->break_contract_money;
        }
        $data['break_contract_money'] = $break_contract_money;
        $data['oper_time5'] = $data['oper_time5']?date("Y-m-d H:i:s", $data['oper_time5']):'';
        
        $returnArr['status'] = true;
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
    }

    //7.    黄总审批
    public function actionAdd7(){
        $id = yii::$app->request->post('id');
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        $connection = yii::$app->db;
        if(!$id){
            $returnArr['info'] = '缺少参数';
            exit(json_encode($returnArr));
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据
        $isLimitedArr = CarBack::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
        	$sql = "select count(*) cnt from cs_car_back where id={$id} and operating_company_id in (0,{$isLimitedArr['adminInfo_operatingCompanyId']})";
        	$cnt = $connection->createCommand($sql)->queryOne()['cnt'];
        	if($cnt == 0){
        		$returnArr['info'] = '无数据权限！';
        		exit(json_encode($returnArr));
        	}
        }
        //end
        
        $is_reject2 = yii::$app->request->post('is_reject2');
        $reject_cause2 = yii::$app->request->post('reject_cause2'); //驳回原因
       
        if($is_reject2 == 2){ //驳回
            $connection->createCommand()->update('cs_car_back', [
                    'state' => 22,
                    'is_reject2' => 2,
                    'reject_cause2' => $reject_cause2,
                    'oper_user6' => $_SESSION['backend']['adminInfo']['name'],
                    'oper_time6' => time()
                    ],
                    'id=:id',
                    array(':id'=>$id)
            )->execute();
        }else { //进入下一流程
            $r = $connection->createCommand()->update('cs_car_back', [
                    'state' => 7,
                    'is_reject2' => 1,
                    'oper_user6' => $_SESSION['backend']['adminInfo']['name'],
                    'oper_time6' => time(),
                    'last_update_user' => $_SESSION['backend']['adminInfo']['name'],
                    'last_update_time' => time()
                    ],
                    'id=:id',
                    array(':id'=>$id)
                )->execute();
        }
        $returnArr['status'] = true;
        echo json_encode($returnArr);
    }

    public function actionGet7(){
        $id = yii::$app->request->get('id');
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        if(!$id){
            $returnArr['info'] = '缺少参数';
            exit(json_encode($returnArr));
        }
        $connection = yii::$app->db;
        $sql = 'select * from cs_car_back where id='.$id;
        $data = $connection->createCommand($sql)->queryOne();
		//租金欠款text
        $data['arrear_text'] = json_decode($data['arrear_text']);
        //违章金额计算
        $data['wz_text'] = $data['wz_text']?json_decode($data['wz_text']):array();
        $data['wz_text_new'] = $data['wz_text_new']?json_decode($data['wz_text_new']):array();
        $money = 0;
        foreach ($data['wz_text'] as $row){
        	foreach ($row->lists as $row1){
        		// $money += $row1->money;
				$money += (($row1->fen * $row1->fenjia) + $row1->money);
        	}
        }
	foreach ($data['wz_text_new'] as $row){
        	foreach ($row->lists as $row1){        		
				$money += (($row1->fen * $row1->fenjia) + $row1->money);
        	}
			$data['wz_text'][] = $row;
        }
		// var_dump($money);exit;
        $data['wz_money'] = $money;
        
        //定损金额计算
        $data['damage_text'] = $data['damage_text']?json_decode($data['damage_text']):array();
        $damage_money = 0;
        foreach ($data['damage_text'] as $index=>$row){
        	$damage_money += $row->damage_money;
        	
        	if(!$data['damage_text'][$index]->car_id){
        		continue;
        	}
        	$sql = 'select plate_number from cs_car where id = '.$data['damage_text'][$index]->car_id;
        	$car = $connection->createCommand($sql)->queryOne();
        	$data['damage_text'][$index]->plate_number = $car['plate_number'];
        }
        $data['damage_money'] = $damage_money;
        
        //出险理赔信息列表
        $data['contract_text'] = json_decode($data['contract_text']);
        $insurance_claims = array();
        $plate_numbers = array();
        foreach ($data['contract_text'] as $index=>$row){
        	if(!$data['contract_text'][$index]->car_ids){
        		continue;
        	}
        	$sql = 'select id,plate_number,car_model from cs_car where id in ('.$data['contract_text'][$index]->car_ids.')';
        	$cars = $connection->createCommand($sql)->queryAll();
        	foreach ($cars as $index1=>$car){
        		$carInsuranceClaim = $this->getCarInsuranceClaim($connection, $car['id'], $row->contract_number, $car['plate_number']);
        		if($carInsuranceClaim){
        			//         			$cars[$index1]['insurance'] = $carInsuranceClaim;
        			array_push($insurance_claims, $carInsuranceClaim);
        		}
        		array_push($plate_numbers, $car['plate_number']);
        	}
        	$data['contract_text'][$index]->plate_numbers = $cars;
        }
        unset($data['contract_text']);
        $data['insurance_claims'] = $insurance_claims;
        $data['oper_time6'] = $data['oper_time6']?date("Y-m-d H:i:s", $data['oper_time6']):'';
        
        $returnArr['status'] = true;
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
    }

    //8.    签订合同终止书
    public function actionAdd8(){
        $id = yii::$app->request->post('id');
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        $connection = yii::$app->db;
        if(!$id){
            $returnArr['info'] = '缺少参数';
            exit(json_encode($returnArr));
        }
        $note7 = yii::$app->request->post('note7'); //备注
        
        //上传附件
        $append_url3 = yii::$app->request->post('append_url3');
        if(@$_FILES['append3']){
            $file_path="uploads/carback/";
            if(!is_dir($file_path)){
                mkdir($file_path);
            }
            $file_path .= date("Ymd").'/';
            if(!is_dir($file_path)){
                mkdir($file_path);
            }

            $_FILES['append3']['name'] = date("YmdHis").'_'.$_FILES['append3']['name']; //加个时间戳防止重复文件上传后被覆盖
            move_uploaded_file($_FILES['append3']['tmp_name'],$file_path.$_FILES['append3']['name']);
            $append_url3 = $file_path.$_FILES['append3']['name'];
        }

        $connection->createCommand()->update('cs_car_back', [
                'state' => 8,
                'note7' => $note7,
                'append_url3' => $append_url3,
                'oper_user7' => $_SESSION['backend']['adminInfo']['name'],
                'oper_time7' => time(),
                'last_update_user' => $_SESSION['backend']['adminInfo']['name'],
                'last_update_time' => time()
                ],
                'id=:id',
                array(':id'=>$id)
        )->execute();
        $returnArr['status'] = true;
        echo json_encode($returnArr);
    }
    public function actionGet8(){
        $id = yii::$app->request->get('id');
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        if(!$id){
            $returnArr['info'] = '缺少参数';
            exit(json_encode($returnArr));
        }
        $connection = yii::$app->db;
        $sql = 'select * from cs_car_back where id='.$id;
        $data = $connection->createCommand($sql)->queryOne();
		
        //租金欠款text
        $data['arrear_text'] = json_decode($data['arrear_text']);
        //违章金额计算
        $data['wz_text'] = $data['wz_text']?json_decode($data['wz_text']):array();		
        $data['wz_text_new'] = $data['wz_text_new']?json_decode($data['wz_text_new']):array();
        $money = 0;
        foreach ($data['wz_text'] as $row){
        	foreach ($row->lists as $row1){        		
				$money += (($row1->fen * $row1->fenjia) + $row1->money);
        	}
        }
	foreach ($data['wz_text_new'] as $row){
        	foreach ($row->lists as $row1){        		
				$money += (($row1->fen * $row1->fenjia) + $row1->money);
        	}
			$data['wz_text'][] = $row;
        }		
        $data['wz_money'] = $money;
        
        //定损金额计算
        $data['damage_text'] = $data['damage_text']?json_decode($data['damage_text']):array();
       	$damage_money = 0;
        foreach ($data['damage_text'] as $index=>$row){
        	$damage_money += $row->damage_money;
        	
        	if(!$data['damage_text'][$index]->car_id){
        		continue;
        	}
        	$sql = 'select plate_number from cs_car where id = '.$data['damage_text'][$index]->car_id;
        	$car = $connection->createCommand($sql)->queryOne();
        	$data['damage_text'][$index]->plate_number = $car['plate_number'];
        }
        $data['damage_money'] = $damage_money;
        $data['oper_time7'] = $data['oper_time7']?date("Y-m-d H:i:s", $data['oper_time7']):'';
        
        $returnArr['status'] = true;
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
    }

    //9.  确认车辆入库
    public function actionAdd9(){
        $id = yii::$app->request->post('id');
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        $connection = yii::$app->db;
        if(!$id){
            $returnArr['info'] = '缺少参数';
            exit(json_encode($returnArr));
        }
        //上传转账凭证
        $append_url4 = yii::$app->request->post('append_url4');
        if(@$_FILES['append4']){
            $file_path="uploads/carback/";
            if(!is_dir($file_path)){
                mkdir($file_path);
            }
            $file_path .= date("Ymd").'/';
            if(!is_dir($file_path)){
                mkdir($file_path);
            }
            $ext_t = explode('.', $_FILES['append4']['name']);
            $ext = strtolower(end($ext_t));
            if (!in_array($ext, array('jpeg', 'jpg',  'png',  'gif', 'pdf'))) {
            	$returnArr['info'] = '文件格式不正确！';
            	exit(json_encode($returnArr));
            }
            $_FILES['append4']['name'] = date("YmdHis").'_'.$_FILES['append4']['name']; //加个时间戳防止重复文件上传后被覆盖
            if(!move_uploaded_file($_FILES['append4']['tmp_name'],$file_path.$_FILES['append4']['name'])){
            	$returnArr['info'] = '文件上传失败！';
            	$returnArr['error'] = $_FILES['append4']['error'];
            	exit(json_encode($returnArr));
            }
            $append_url4 = $file_path.$_FILES['append4']['name'];
        }
        // $car_ids = yii::$app->request->post('car_id'); //违约金
        $note8 = yii::$app->request->post('note8'); //违约金
//         $storage_car_ids = '';
        // foreach ($car_ids as $key => $value) {
        //     $storage_car_ids .= $value.',';
        // }
        // if($storage_car_ids){
        //     $storage_car_ids = substr($storage_car_ids, 0, strlen($storage_car_ids)-1);
        // }
        $connection->createCommand()->update('cs_car_back', [
                'state' => 9,
                'note8' => $note8,
                'append_url4' => $append_url4,
//                 'storage_car_ids' => $storage_car_ids,
                'oper_user8' => $_SESSION['backend']['adminInfo']['name'],
                'oper_time8' => time(),
                'last_update_user' => $_SESSION['backend']['adminInfo']['name'],
                'last_update_time' => time()
                ],
                'id=:id',
                array(':id'=>$id)
        )->execute();
        $returnArr['status'] = true;
        echo json_encode($returnArr);
    }
    public function actionGet9(){
        $id = yii::$app->request->get('id');
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        if(!$id){
            $returnArr['info'] = '缺少参数';
            exit(json_encode($returnArr));
        }
        $connection = yii::$app->db;
        $sql = 'select * from cs_car_back where id='.$id;
        $data = $connection->createCommand($sql)->queryOne();
        $data['oper_time8'] = $data['oper_time8']?date("Y-m-d H:i:s", $data['oper_time8']):'';

        $returnArr['status'] = true;
        $returnArr['data'] = $data;
        echo json_encode($returnArr);
    }
   
    /**
     * 获取列表MARK
     */
    public function actionGetList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $connection = yii::$app->db;
        $query = CarBack::find()
        ->select([
        		'{{%car_back}}.*,
        		{{%customer_company}}.company_name,
                {{%customer_company}}.type company_type,
        		{{%customer_personal}}.id_name,
        		{{oa_extract_car_site}}.name extract_car_site_name,
        		{{oa_extract_car_site}}.id extract_car_site_id
        		'
        		])
        ->leftJoin('{{%customer_company}}', '{{%car_back}}.`c_customer_id` = {{%customer_company}}.`id`')
        ->leftJoin('{{%customer_personal}}', '{{%car_back}}.`p_customer_id` = {{%customer_personal}}.`id`')
        ->leftJoin('{{oa_extract_car_site}}', '{{%car_back}}.`extract_car_site_id` = {{oa_extract_car_site}}.`id`')
        ->andWhere(['=','{{%car_back}}.`is_del`',0]);
        //查询条件
        $is_db = yii::$app->request->get('is_db');
        if($is_db){ //是否待办
        	$db_states = $this->db_states();
			$query->andFilterWhere(['in','{{%car_back}}.state',$db_states]);
        }
        $plate_number = yii::$app->request->get('plate_number');
        if($plate_number){
        	$car = $connection->createCommand(
        			'select id from cs_car where plate_number like "%'.$plate_number.'%" limit 1'
        	)->queryOne();
        	if($car){
        		$query->andWhere("
        			{{%car_back}}.contract_text like '%\"{$car['id']}\"%'
        			or 
        			{{%car_back}}.contract_text like '%\"{$car['id']},%'
        			or 
        			{{%car_back}}.contract_text like '%,{$car['id']}\"%'
        			or 
        			{{%car_back}}.contract_text like '%,{$car['id']},%'
        		");
        	}else {
        		$returnArr = [];
        		$returnArr['rows'] = [];
        		$returnArr['total'] = 0;
        		exit(json_encode($returnArr));
        	}
        }
        $number = yii::$app->request->get('number');
        if($number)
        {
        	$query->andFilterWhere(
        			['like','{{%car_back}}.number',$number]);
        }
        $customer_tel = yii::$app->request->get('customer_tel');
        if($customer_tel)
        {
        	$query->andFilterWhere(
        			['like','{{%car_back}}.customer_tel',$customer_tel]);
        }
        $state = yii::$app->request->get('state');
        if($state)
        {
        	$query->andFilterWhere(
        			['=','{{%car_back}}.state',$state]);
        }
        
        $customer_name = yii::$app->request->get('customer_name');
        if($customer_name)
        {
            $customer_companys = $connection->createCommand(
                'select id 
                from cs_customer_company 
                where company_name like "%'.$customer_name.'%" limit 20'
                )->queryAll();
            $customer_company_ids = array();
            foreach ($customer_companys as $row) {
                array_push($customer_company_ids, $row['id']);
            }
            if($customer_company_ids){
                $query->andFilterWhere(
                     ['in','{{%car_back}}.c_customer_id',$customer_company_ids]
                );
            }
        }
        $customer_type = yii::$app->request->get('customer_type');
        if($customer_type)
        {
            if($customer_type==4){  //个人客户
                $query->andFilterWhere(
                         ['>','{{%car_back}}.p_customer_id',0]
                    );
            }else { //企业客户类型
                $customer_companys = $connection->createCommand(
                    'select id 
                    from cs_customer_company 
                    where type = '.$customer_type.' limit 20'
                    )->queryAll();
                $customer_company_ids = array();
                foreach ($customer_companys as $row) {
                    array_push($customer_company_ids, $row['id']);
                }
                if($customer_company_ids){
                    $query->andFilterWhere(
                         ['in','{{%car_back}}.c_customer_id',$customer_company_ids]
                    );
                }
            }
        }
        $repair_type = yii::$app->request->get('repair_type');
        if($repair_type)
        {
            $query->andFilterWhere(
                    ['like','{{%car_back}}.repair_type',$repair_type]);
        }
        
        if(yii::$app->request->get('start_add_time')){
            $query->andFilterWhere(['>=','{{%car_back}}.`add_time`',strtotime(yii::$app->request->get('start_add_time'))]);
        }
        if(yii::$app->request->get('end_add_time')){
            $query->andFilterWhere(['<=','{{%car_back}}.`add_time`',strtotime(yii::$app->request->get('end_add_time'))]);
        }
        
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = CarBack::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
        	$query->andWhere("{{%car_back}}.`operating_company_id` in (0,{$isLimitedArr['adminInfo_operatingCompanyId']})");
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
        			$orderBy = '{{%car_back}}.`'.$sortColumn.'` ';
        		break;
        	}
        }else{
        	$sortType = 'asc';
        	$orderBy = '{{%car_back}}.`state` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();

        //添加退车倒计时       
        foreach ($data as $key => $value){
//var_dump($data);
			//查询合同数量 还车数量
            $id = $data[$key]['id']; 
            $car_back_data = $connection->createCommand('select state,contract_text,car_storage_text from cs_car_back where id='.$id)->queryOne();
			
			//客户取消退车
			if ($car_back_data['state'] == 20) {
				 $data[$key]['time_status'] = '<span style="background-color:#666666;color:#fff;padding:2px 5px;">客户取消<font>';            
			} else {
				//合同
				$contracts = json_decode($car_back_data['contract_text']);
				$contract_car_num = 0;  //合同退车数量
				//var_dump($contracts);exit;
				if($contracts){
					foreach($contracts as $row){
				//var_dump($row->car_ids);exit;
						if($row->car_ids){
						$contract_car_num += count(explode(',',$row->car_ids));
						}
					}
				}
				//var_dump($contract_car_num);exit;
				//存入数量
				$car_storage = json_decode($car_back_data['car_storage_text']);
				$car_storage_text = 0;
				//var_dump($car_storage);
				if($car_storage) {
					foreach ($car_storage as $row){
						if($row->car_id){
							$car_storage_text += count(explode(',',$row->car_id));
						}
					}
				}
				#var_dump($car_storage_text);exit;
				#测试
				/*if(intval($remain/86400) > 7){
					$data[$key]['time_status'] =$car_storage;
				}*/
				// $data[$key]['time_status'] = $contract_car_num."|".$car_storage_text;	
				if($contract_car_num != 0 && $car_storage_text != 0 && ($contract_car_num == $car_storage_text)) {
					$data[$key]['time_status'] ='<span style="background-color:#228B22;color:#fff;padding:2px 5px;">已入库<font>';
					// $data[$key]['time_status'] = $contract_car_num."|".$car_storage_text;
				} else {
					
				#1、倒计时还没未开始，此时还未提出退车请求
				//echo $data[$key]['oper_time4'];
					if(!$data[$key]['oper_time3']) {
						$data[$key]['time_status'] = '<span style="background-color:grey;color:#fff;padding:2px 5px;">未开始<font>';
					}else{
						#2、提出退车请求，7天倒计时开始，
						$data[$key]['time_status'] = '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">倒计时7天<font>';
					
						//date_default_timezone_set('PRC');
						$startDateStr = $data[$key]['oper_time3'];
						$now = strtotime(date('Y-m-d',time()));
						//$now = '2016-12-27';
						$remain = $now-$startDateStr;
					
						if($data[$key]['oper_time3'] && $remain>1) {
							$data[$key]['time_status'] = '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">倒计时'.(7-intval($remain/86400)-1).'天'.'<font>';
						//测试
							//$data[$key]['time_status'] = $remain;
						}
						#4、倒计时3天时，给权限5的角色发送邮件
						/*if((7-intval($remain/86400))==3) {
						   $mail = new Mail();
							$subject = '退车入库提醒';
							$body ="你有一个待处理的事项：【退车流程，车辆入库】。客户退车,售后和车管需在【7日】之内对所退车辆整备完毕并确认入库。请及时登录系统进行【车辆入库】,以免影响工作进度。点击这里：<a href='http://xt.dstzc.com'>登录地上铁系统</a>,或者在浏览器中输入以下地址登录 ：xt.dstzc.com 。如果对此有疑问和建议，请向系统开发部反馈。"; 
							$user_emails = $this->getUserMailByMca('car','car-back','add5');
							$mail->send($user_emails,$subject, $body); 
						}*/
						#3、显示超时 ,倒计时7天结束
						if(intval($remain/86400) > 7){
							$data[$key]['time_status'] = '<span style="background-color:#FF4040;color:#fff;padding:2px 5px;">超时'.intval($remain /86400-7).'天'.'<font>';
						}	
					}	
				
				}
			}		
        }
        
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }
    
    function getDamageCars($id){
    	$connection = yii::$app->db;
    	$car_ids = '';
    	$car_back_data = $connection->createCommand('select damage_text from cs_car_back where id='.$id)->queryOne();
    	if(!$car_back_data['damage_text']){
    		return '';
    	}
    	$damages = json_decode($car_back_data['damage_text']);
    	$car_ids = '';
    	foreach ($damages as $row){
    		$car_ids .= $row->car_id.',';
    	}
    	if($car_ids){
    		$car_ids = substr($car_ids, 0, strlen($car_ids)-1);
    	}
    	return $car_ids;
    }
    
    function getCarIds($contract_text){
    	$connection = yii::$app->db;
    	$car_ids = '';
	   	//$car_back_data = $connection->createCommand('select contract_text from cs_car_back where id='.$id)->queryOne();
    	//if(!$car_back_data['contract_text']){
    	//	return '';
    	//}
		if(!$contract_text){
			return '';
		}
    	$contracts = json_decode($contract_text);
    	$car_ids = '';
    	foreach ($contracts as $row){
    		$car_ids .= $row->car_ids.',';
    	}
    	if($car_ids){
    		$car_ids = substr($car_ids, 0, strlen($car_ids)-1);
    	}
    	return $car_ids;
    }
    
//查看详情
    public function actionScan(){
    	$id = yii::$app->request->get('id') or die('param id is required');
    	
    	$connection = yii::$app->db;
    	$car_back = $connection->createCommand('select a.*,b.company_name,c.id_name from cs_car_back a
            left join cs_customer_company b on a.c_customer_id=b.id
            left join cs_customer_personal c on a.`p_customer_id`=c.id where a.id='.$id)->queryOne();
		$car_ids = $this->getCarIds($car_back['contract_text']);
		$damage_car_ids = $this->getDamageCars($id);
        if($car_ids){
        	$sql = 'select plate_number,car_model from cs_car where id in('.$car_ids.')';
            $car_back['cars'] = $connection->createCommand($sql)->queryAll();   
         }else {
            $car_back['cars']= array();
         }
    	if($damage_car_ids){
        	$sql = 'select id,plate_number from cs_car where id in('.$damage_car_ids.')';
            $car_back['damage_cars'] = $connection->createCommand($sql)->queryAll();   
         }else {
            $car_back['damage_cars']= array();
         }
         $config = (new ConfigCategory)->getCategoryConfig(['car_model_name'],'value');
         $car_back['oper_time1'] = date('Y-m-d H:i:s',$car_back['oper_time1']);
         $car_back['oper_time2'] = date('Y-m-d H:i:s',$car_back['oper_time2']);
         $car_back['oper_time3'] = date('Y-m-d H:i:s',$car_back['oper_time3']);
         $car_back['oper_time4'] = date('Y-m-d H:i:s',$car_back['oper_time4']);
         $car_back['oper_time5'] = date('Y-m-d H:i:s',$car_back['oper_time5']);
         $car_back['oper_time6'] = date('Y-m-d H:i:s',$car_back['oper_time6']);
         $car_back['oper_time7'] = date('Y-m-d H:i:s',$car_back['oper_time7']);
         $car_back['oper_time8'] = date('Y-m-d H:i:s',$car_back['oper_time8']);
         $car_back['oper_time4_1'] = date('Y-m-d H:i:s',$car_back['oper_time4_1']);
         $car_back['add_time'] = date('Y-m-d H:i:s',$car_back['add_time']);
         $car_back['last_update_time'] = date('Y-m-d H:i:s',$car_back['last_update_time']);
    	return $this->render('scan',[
    			'config'=>$config,
    			'obj'=>$car_back
    			]);
    }
    
	/**
     * 删除退车记录
     */
    public function actionRemove()
    {
    	$id = yii::$app->request->get('id') or die('param id is required');
    	$model = CarBack::findOne(['id'=>$id]);
    	$model or die('record not found');
    	$returnArr = [];
    	$returnArr['status'] = true;
    	$returnArr['info'] = '';
    	if(CarBack::updateAll(['is_del'=>1],['id'=>$id])){
    		$returnArr['status'] = true;
    		$returnArr['info'] = '记录删除成功！';
    	}else{
    		$returnArr['status'] = false;
    		$returnArr['info'] = '记录删除失败！';
    	}
    	echo json_encode($returnArr);
    }
    
    /**
     * 导出退车列表
     */
    public function actionExport()
    {
    	$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
		$connection = yii::$app->db;
    	$query = CarBack::find()
    	->select([
    			'{{%car_back}}.*,
    			{{%customer_company}}.company_name,
    			{{%customer_personal}}.id_name'
    			])
    			->leftJoin('{{%customer_company}}', '{{%car_back}}.`c_customer_id` = {{%customer_company}}.`id`')
                ->leftJoin('{{%customer_personal}}', '{{%car_back}}.`p_customer_id` = {{%customer_personal}}.`id`')
    			->andWhere(['=','{{%car_back}}.`is_del`',0]);
    	//查询条件
        $is_db = yii::$app->request->get('is_db');
        if($is_db){ //是否待办
            $db_states = $this->db_states();
			$query->andFilterWhere(['in','{{%car_back}}.state',$db_states]);
        }
		$plate_number = yii::$app->request->get('plate_number');
        if($plate_number){
        	$car = $connection->createCommand(
        			'select id from cs_car where plate_number like "%'.$plate_number.'%" limit 1'
        	)->queryOne();
        	if($car){
				$query->andWhere("
        			{{%car_back}}.contract_text like '%\"{$car['id']}\"%'
        			or 
        			{{%car_back}}.contract_text like '%\"{$car['id']},%'
        			or 
        			{{%car_back}}.contract_text like '%,{$car['id']}\"%'
        			or 
        			{{%car_back}}.contract_text like '%,{$car['id']},%'
        		");
        	}else {
        		$query->andFilterWhere(
        				['=','{{%car_back}}.id',0]);
        	}
        }
        $number = yii::$app->request->get('number');
        if($number)
        {
        	$query->andFilterWhere(
        			['like','{{%car_back}}.number',$number]);
        }
        $customer_tel = yii::$app->request->get('customer_tel');
        if($customer_tel)
        {
        	$query->andFilterWhere(
        			['like','{{%car_back}}.customer_tel',$customer_tel]);
        }
        $state = yii::$app->request->get('state');
        if($state)
        {
        	$query->andFilterWhere(
        			['=','{{%car_back}}.state',$state]);
        }
        
        $customer_name = yii::$app->request->get('customer_name');
        if($customer_name)
        {
            $customer_companys = $connection->createCommand(
                'select id 
                from cs_customer_company 
                where company_name like "%'.$customer_name.'%" limit 20'
                )->queryAll();
            $customer_company_ids = array();
            foreach ($customer_companys as $row) {
                array_push($customer_company_ids, $row['id']);
            }
            if($customer_company_ids){
                $query->andFilterWhere(
                     ['in','{{%car_back}}.c_customer_id',$customer_company_ids]
                );
            }
        }
        $customer_type = yii::$app->request->get('customer_type');
        if($customer_type)
        {
            if($customer_type==4){  //个人客户
                $query->andFilterWhere(
                         ['>','{{%car_back}}.p_customer_id',0]
                    );
            }else { //企业客户类型
                $customer_companys = $connection->createCommand(
                    'select id 
                    from cs_customer_company 
                    where type = '.$customer_type.' limit 20'
                    )->queryAll();
                $customer_company_ids = array();
                foreach ($customer_companys as $row) {
                    array_push($customer_company_ids, $row['id']);
                }
                if($customer_company_ids){
                    $query->andFilterWhere(
                         ['in','{{%car_back}}.c_customer_id',$customer_company_ids]
                    );
                }
            }
        }
        $repair_type = yii::$app->request->get('repair_type');
        if($repair_type)
        {
            $query->andFilterWhere(
                    ['like','{{%car_back}}.repair_type',$repair_type]);
        }
        
        if(yii::$app->request->get('start_add_time')){
            $query->andFilterWhere(['>=','{{%car_back}}.`add_time`',strtotime(yii::$app->request->get('start_add_time'))]);
        }
        if(yii::$app->request->get('end_add_time')){
            $query->andFilterWhere(['<=','{{%car_back}}.`add_time`',strtotime(yii::$app->request->get('end_add_time'))]);
        }
        // echo $query->createCommand()->getRawSql();exit;
        //查询条件结束

    	$data = $query->asArray()->all();
    	$filename = '退车记录.csv'; //设置文件名
    	$str = "退车编号,状态,客户名称,客户电话,客户地址,退车数量,退车原因,预计还车时间,合同信息,取消退车原因,领导审批,定损报价,维修类型,退车日期,违约金,押金,结算退还金额,退还时间,备注\n";
        $states = array(
                    0=>'',1=>'1.客户退车，等待销售沟通',2=>'2.确定退车，等待领导审批',3=>'3.同意退车，等待售后验车',4=>'4.已验车，等待入库',5=>'5.已入库，等待商务核算',6=>'6.已核算，等待审批',7=>'7.核算审批通过，等待财务确认',8=>'8.财务确认，终止合同书',9=>'9.已归档',20=>'2.客户取消退车',21=>'3.退车申请被驳回',22=>'7.核算驳回');
        $break_contract_types = array('','合同未到期','合同已到期');
        $is_rejects = array('','同意','驳回');
        $repair_types = array('','客户自修','公司修理','无需维修');
    	foreach ($data as $row){
            $customer_name = '';
            if($row['company_name']){
                $customer_name = $row['company_name'];
            }else if($row['id_name']){
                $customer_name = $row['id_name'];
            }else {
                $customer_name = $row['other_customer_name'];
            }
            $car_ids = $this->getCarIds($row['contract_text']);
            if($car_ids){
                $cars_num = count(explode(',',$car_ids));   
            }else {
                $cars_num = 0;
            }
            //合同违约情况,合同时间,违约金金额
            $contract_info = '';
            $tmp_damage_money = @$row['damage_money'];
            $tmp_repair_type = @$row['repair_type'];
            $tmp_repair_type = @$repair_types[$tmp_repair_type]; 
            
    		$str .= "{$row['number']},{$states[$row['state']]},{$customer_name},{$row['customer_tel']},{$row['customer_addr']},{$cars_num},{$row['back_cause']},{$row['back_time']},{$contract_info},{$row['cancel_back_cause']},{$is_rejects[$row['is_reject']]},{$tmp_damage_money},{$tmp_repair_type},{$row['back_time2']},{$row['penalty_money']},{$row['foregift_money']},{$row['back_money']},{$row['back_time3']},{$row['note8']}"."\n";
//     		$str .= "{$row['number']},{$states[$row['state']]},{$customer_name},{$row['customer_tel']},{$row['customer_addr']},{$cars_num},{$row['back_cause']},{$row['back_time']},{$break_contract_types[$row['break_contract_type']]},{$row['contract_time']},{$row['break_contract_money']},{$row['cancel_back_cause']},{$is_rejects[$row['is_reject']]},{$row['damage_money']},{$repair_types[$row['repair_type']]},{$row['back_time2']},{$row['penalty_money']},{$row['foregift_money']},{$row['back_money']},{$row['back_time3']},{$row['note']}"."\n";
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
}