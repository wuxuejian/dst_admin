<?php
/**
 * 客户订单管理控制器
 * time: 2015/10/12 17:03
 * @author wangmin
 */
namespace backend\modules\customer\controllers;
use backend\controllers\BaseController;
use backend\models\CustomerCompany;
use backend\models\CarInsuranceBusiness;
use backend\models\Car;
use backend\models\CarLetContract;
use backend\models\CarLetRecord;
use backend\models\CarLetContractRenewRecord;
use backend\models\Admin;
use backend\classes\UserLog;//日志类
use common\models\Excel;
use yii;
use yii\data\Pagination;
use backend\models\ConfigCategory;

class ContractRecordController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        $configItems = ['customer_type'];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        return $this->render('index',[
            'buttons'=>$buttons,
            'config'=>$config
        ]);
    }

    /**
     * 获取【企业客户合同订单】列表
     */
    public function actionGetList()
    {
        //echo 'm2';exit;
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CarLetContract::find()
                ->select([
                    '{{%car_let_contract}}.*',
                    '_end_time'=>'{{%car_let_contract}}.end_time', //“倒计时”占位
                    'customer_name'=>'{{%customer_company}}.`company_name`',
                    '{{%admin}}.`username`',
                    'operating_company'=>'{{%operating_company}}.`name`',
                	'car_let_record_num'=>'count({{%car_let_record}}.`id`)',
                ])
                ->joinWith('customerCompany',false,'LEFT JOIN')
                ->joinWith('admin',false,'LEFT JOIN')
                ->joinWith('operatingCompany',false,'LEFT JOIN')
                ->leftJoin('{{%car_let_record}}', '{{%car_let_contract}}.`id` = {{%car_let_record}}.`contract_id` and {{%car_let_record}}.`is_del`=0 and {{%car_let_record}}.`back_time`=0')
//                 ->joinWith('carLetRecord',false,'LEFT JOIN')->onCondition(['{{%car_let_record}}.back_time' => 0])
                ->andWhere([
                    '{{%car_let_contract}}.customer_type'=>'COMPANY', //客户类型为“企业客户”
                    '{{%car_let_contract}}.`is_del`'=>0,
                ])->groupBy(['{{%car_let_contract}}.id']);
        
        //////查询条件
        $query->andFilterWhere([
            'like',
            '{{%car_let_contract}}.`number`',
            yii::$app->request->get('number')
        ]);
        $query->andFilterWhere([
            'like',
            '{{%customer_company}}.`company_name`',
            yii::$app->request->get('customer_name')
        ]);

        //$contract_type = yii::$app->request->get('contract_type');
        //var_dump($contract_type);exit;
        
        $query->andFilterWhere([
            'like',
            '{{%car_let_contract}}.`contract_type`',
            yii::$app->request->get('contract_type') 
        ]);
        //var_dump(yii::$app->request->get('contract_type'));exit;
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160326
        $isLimitedArr = CarLetContract::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car_let_contract}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //////查询条件
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'customer_name':
                    $orderBy = '{{%customer_company}}.`company_name` ';
                    break;
                case 'username':
                    $orderBy = '{{%admin}}.`username` ';
                    break;
                default:
                    $orderBy = '{{%car_let_contract}}.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = '{{%car_let_contract}}.`id` ';
        }
//         echo $query->createCommand()->getRawSql();exit;
        $orderBy .= $sortType;
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy($orderBy)
                ->asArray()->all();
        $configItems = ['contract_type'];
        $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
        //echo '<pre>';
        //var_dump($config);exit;
        //foreach($data as $key =>$value3) {
            //echo '<pre>';
            //var_dump($value3);exit;
            //var_dump($value3['contract_type']);exit;
            //var_dump($config['contract_type'][$value3['contract_type']]['text']);exit;
            //$data[$key]['contract_type']=$config['contract_type'][$value3['contract_type']]['text'];
            //var_dump($data[$key]);exit;
       // }
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /*
     * 检查新增的各车辆是否为“库存”状态。若是库存则再检查是否已添加商业险信息（在有效期内）。
     * （出租合同新增、修改、续签时都得检查）
     */
    protected function checkCarStockStatusAndBusinessInsurance($cars,$ids=[]){
        $notStockCars = [];
        $noBusinessInsuranceCars = [];
        foreach ($cars as $k=>$plate_number){
            $car = Car::find()->select(['id','car_status'])->where(['plate_number' => $plate_number,'is_del'=>0])->asArray()->one();
            if(!empty($ids) && !$ids[$k]){ //只检查新签约车辆
                if ($car['car_status'] == 'STOCK') {
                    //若是'库存'则再检验商业险信息
                    $insurance = CarInsuranceBusiness::find()
                        ->select(['id'])
                        ->where([
                            'and',
                            ['car_id' => $car['id']],
                            ['>', 'end_date', strtotime(time())]
                        ])
                        ->asArray()->one();
                    if (empty($insurance)) {
                        $noBusinessInsuranceCars[] = $plate_number;
                    }
                } else {
                    $notStockCars[] = $plate_number;
                }
            }
        }
        if(!empty($notStockCars)) {
            $returnArr = [];
            $returnArr['status'] = false;
            $returnArr['info'] = "下列车辆处于“<span style='color:red'>非库存</span>”状态，不能出租：<span style='color:red'>" . implode(',', $notStockCars) . "</span>";
            return $returnArr;
        }
        if(!empty($noBusinessInsuranceCars)) {
            //注意：与上面的非库存车辆不同，商业险只是提示，没有商业险时也允许新增！
            $noBusinessInsuranceTip = "下列车辆尚未完善商业险信息，出租前请确认已购买商业险：<span style='color:red'>" . implode(',', $noBusinessInsuranceCars) . "</span>";
            return $noBusinessInsuranceTip;
        }
        return true;
    }


    /**
     * 新建合同
     */
    public function actionAdd()
    {
        //echo 'm12';exit;
        //data submit start
        if(yii::$app->request->isPost){
            /* if (!empty($_POST['plate_number'])) {
                // 检查新增的各车辆是否为“库存”状态。若是库存则再检查是否已添加商业险信息（在有效期内）。
                $returnData = $this->checkCarStockStatusAndBusinessInsurance($_POST['plate_number']);
                if(is_array($returnData) && !empty($returnData)){
                    return json_encode($returnData); //不是库存车辆，直接退出
                }elseif(is_string($returnData) && $returnData != ''){
                    $noBusinessInsuranceTip = $returnData; //提示车辆无商业险
                }
            } */
            //重组车辆数据
            $carInfo = [];
            if(isset($_POST['plate_number'])){
                foreach($_POST['plate_number'] as $key=>$val){
                    $carInfo[$val]['plate_number'] = $val;
                    $carInfo[$val]['month_rent'] = $_POST['month_rent'][$key];
                    $carInfo[$val]['car_note'] = $_POST['car_note'][$key];
                    $carInfo[$val]['let_time'] = $_POST['let_time'][$key];
                }
            }
            $model = new CarLetContract;
            $model->number = yii::$app->request->post('number');
            $model->customer_type = yii::$app->request->post('customer_type');
            $model->cCustomer_id = yii::$app->request->post('cCustomer_id'); //保存的是'企业客户ID'
            $model->start_time = yii::$app->request->post('start_time');
            $model->end_time = yii::$app->request->post('end_time');
            $model->due_time = yii::$app->request->post('due_time');
            $model->bail = yii::$app->request->post('bail');
            $model->note = yii::$app->request->post('note');
            $model->sign_date = yii::$app->request->post('sign_date');
            $model->salesperson = yii::$app->request->post('salesperson');
            $model->contract_type = yii::$app->request->post('contract_type');
            $model->operating_company_id = $_SESSION['backend']['adminInfo']['operating_company_id'];
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                $model->reg_time = time();
                $model->last_modify_datetime = time();
                $model->modify_aid = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '合同创建成功！';
                    //开启事务
                    $transaction = yii::$app->db->beginTransaction();
                    $transaction_ok = true;
                    foreach($carInfo as $val){
                        $carLetRecordModel = new CarLetRecord;
                        $carLetRecordModel->contract_id = $model->id;
                        $carLetRecordModel->cCustomer_id = $model->cCustomer_id;
                        $carLetRecordModel->operating_company_id = $model->operating_company_id;
                        $carLetRecordModel->plate_number = $val['plate_number'];
                        $carLetRecordModel->month_rent = $val['month_rent'];
                        $carLetRecordModel->note = $val['car_note'];
                        $carLetRecordModel->let_time = $val['let_time'];
                        if(!$carLetRecordModel->validate()){
                            //验证失败
                            $errors = $carLetRecordModel->getErrors();
                            if($errors){
                                foreach($errors as $v){
                                    $returnArr['info'] .= "车辆：{$val['plate_number']}签约失败,{$v[0]}！";
                                }
                            }else{
                                $returnArr['info'] = "车辆：{$val['plate_number']}签约失败，未知错误！";
                            }
                            $returnArr['info'] = "车辆：{$val['plate_number']}验证异常！";
                            $transaction_ok = false;
                            break;
                            continue;
                        }
                        //验证通过
                        if($carLetRecordModel->save(false)){
                            //更新车辆状态出租中
                        	$statusRet = Car::changeCarStatusNew($carLetRecordModel->getAttribute('car_id'), 'LETING', 'customer/contract-record/add', '新建合同',['car_status'=>'STOCK','is_del'=>0]);
                        	if(!$statusRet['status']){
                        		$returnArr['info'] .= "车辆：{$val['plate_number']}状态验证失败！";
                        		$transaction_ok = false;
                        		break;
                        	}
                        }else{
                        	$returnArr['info'] .= "车辆：{$val['plate_number']}出车记录保存失败！";
                        	$transaction_ok = false;
                        	break;
                        }
                    }
                    if($transaction_ok){
                    	$transaction->commit();//提交事务
                    	$returnArr['status'] = true;
                    	$returnArr['info'] = '合同创建成功！';
                    }else {
                    	$transaction->rollback();//回滚事务
                    	$returnArr['status'] = false;
                    }
                    //合同创建成功后添加日志（无论车辆是否签约成功都记录）
                    UserLog::log("新建客户合同(合同编号:".$model->number.")",'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '合同创建失败！';
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
        } else {
            $configItems = ['contract_type'];
            $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
            //echo '<pre>';
            //var_dump($config);exit;
            $contract_type = array();
            foreach($config as $value) {
                //echo '<pre>';
                //var_dump($config['contract_type']);exit;
                //var_dump($value);exit;
                foreach($value as $value2) {
                    //echo '<pre>';
                   //var_dump($value2);exit;
                   $contract_type[] =$value2;
                   //var_dump($contract_type);exit;
                }
            }
            
        }
        //echo '<pre>';
       //var_dump($contract_type);exit;
        //echo $contract_type[0];exit;
        //data submit end
        //获取行编辑时combox中可选的库存车辆-20160325
        $stockCars = Car::getAvailableStockCars();
        return $this->render('add',[
            'car'=>$stockCars,'contract_type'=>$contract_type
        ]);
    }

    /**
     * 新建合同获取单位客户列表
     */
    public function actionGetCustomerList()
    {
        $customer = CustomerCompany::find()
                    ->select(['id','company_name'])
                    ->where(['is_del'=>0])
                    ->asArray()->all();
        return json_encode($customer);
    }

    /**
     * 合同修改
     */
    public function actionEdit()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            $model = CarLetContract::findOne(['id'=>$id]);
            $model or die('record not found');
            //检查当前登录用户和要操作的合同的所属运营公司是否匹配-20160326
            $checkArr = CarLetContract::checkOperatingCompanyIsMatch($id);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $old_number = $model->number;
            $new_number = yii::$app->request->post('number');
            $model->setScenario('edit');
            $model->contract_type = yii::$app->request->post('contract_type');
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                $model->last_modify_datetime = time();
                $model->modify_aid = $_SESSION['backend']['adminInfo']['id'];
                $connection = yii::$app->db;
                $transaction = $connection->beginTransaction();
                if($new_number != $old_number){
                //关联合同编号变更start
                	//1.退车流程
                	$sql = 'select id,contract_text from cs_car_back where contract_text like \'%"'.$old_number.'"%\'';
                	
                	$data = $connection->createCommand($sql)->queryAll();
                	foreach ($data as $row){
                		$connection->createCommand()->update('cs_car_back', [
                					'contract_text' => str_replace('"'.$old_number.'"','"'.$new_number.'"',$row['contract_text'])
                				],
                				'id=:id',
                				array(':id'=>$row['id'])
                		)->execute();
                	}
                	//2.提车流程
                	$sql = "select id,contract_number from oa_extract_report where contract_number = '{$old_number}'";
                	$data = $connection->createCommand($sql)->queryAll();
                	foreach ($data as $row){
                		$connection->createCommand()->update('oa_extract_report', [
                					'contract_number' => $new_number
                				],
                				'id=:id',
                				array(':id'=>$row['id'])
                		)->execute();
                	}
                //end
                }
                
                if($model->save(false)){
                	$transaction->commit(); //提交事务
                    $returnArr['status'] = true;
                    $returnArr['info'] = '合同修改成功！';
                    //添加合同修改记录日志
                    UserLog::log("修改客户合同(合同编号:".$model->number.")",'sys');
                    //更新出车记录所属客户ID
                    $r = $connection->createCommand()->update('cs_car_let_record', [
                    'cCustomer_id' => yii::$app->request->post('cCustomer_id')
                    ],
                    'contract_id=:id', 
                    array(':id'=>$id)
                    )->execute();
                    //cCustomer_id
                }else{
                	$transaction->rollback(); //回滚事务
                    $returnArr['status'] = false;
                    $returnArr['info'] = '合同修改失败！';
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
        } else {
            $configItems = ['contract_type'];
            $config = (new ConfigCategory)->getCategoryConfig($configItems,'value');
            $contract_type = array();
            foreach($config as $value) {
                foreach($value as $value2) {
                    //echo '<pre>';
                   //var_dump($value2);exit;
                   $contract_type[] =$value2;
                   //var_dump($contract_type);exit;
                }
            }   
        }
        //data submit end
        $id = yii::$app->request->get('id') or die('param id is required');
        $model = CarLetContract::findOne(['id'=>$id]);
        $model or die('record not found');
        $companyCustomer = CustomerCompany::find()
                            ->select(['id','company_name'])
                            ->where(['is_del'=>0])
                            ->asArray()->all();
        //查询当前合同签约的所有没有被归还的车辆
        $letCars = CarLetRecord::find()->where(['contract_id'=>$id,'back_time'=>0])->asArray()->all();
        //获取行编辑时combox中可选的库存车辆-20160325
        $stockCars = Car::getAvailableStockCars();
        //获取当前页面按钮
        $buttons = $this->getCurrentActionBtn();
        return $this->render('edit',[
            'contractId'=>$id,
            'contractInfo'=>$model->getAttributes(),
            'companyCustomer'=>$companyCustomer,
            'letCars'=>$letCars,
            'stockCars'=>$stockCars,
            'buttons'=>$buttons,
            'contract_type'=>$contract_type
        ]);
    }

    /**
     * 批量添加或修改签约车辆
     * 修改时无法修改车辆车牌号
     */
    public function actionAddEditCar()
    {
        $data = yii::$app->request->post();
        //检查当前登录用户和要操作的合同的所属运营公司是否匹配-20160326
        $checkArr = CarLetContract::checkOperatingCompanyIsMatch($data['contract_id']);
        if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
            return json_encode(['status'=>false,'info'=>$checkArr['info']]);
        }
        $returnArr = [];
        $returnArr['status'] = true;
        $returnArr['info'] = '';
        /* if (!empty($data['plate_number'])) {
            // 检查新增的各车辆是否为“库存”状态。若是库存则再检查是否已添加商业险信息（在有效期内）。
            $returnData = $this->checkCarStockStatusAndBusinessInsurance($data['plate_number'],$data['id']);
            if(is_array($returnData) && !empty($returnData)){
                return json_encode($returnData); //不是库存车辆，直接退出
            }elseif(is_string($returnData) && $returnData != ''){
                $noBusinessInsuranceTip = $returnData; //提示车辆无商业险
            }
        } */
        $contract = CarLetContract::find()->select(['operating_company_id'])->where(['id'=>$data['contract_id']])->asArray()->one();
        if(isset($data['id']) && is_array($data['id'])){
            foreach($data['id'] as $key=>$val){
                if($val == 0){
                    $model = new CarLetRecord;
                    $model->setScenario('default');
                    $model->plate_number = $data['plate_number'][$key];
                    $model->cCustomer_id = $data['cCustomer_id'];
                    $model->operating_company_id = $contract['operating_company_id'];
                }else{
                    $model = CarLetRecord::findOne(['id'=>$val]);
                    if($model){
                        $model->setScenario('edit');
                    }else{
                        $model = new CarLetRecord;
                        $model->setScenario('default');
                        $model->plate_number = $data['plate_number'][$key];
                    }
                }
                $model->contract_id = $data['contract_id'];
                $model->month_rent = $data['month_rent'][$key];
                $model->let_time = $data['let_time'][$key];
                $model->note = $data['note'][$key];
                if($model->validate()){
                    //新加车辆时防止并发操作导致同一辆车被加入两个合同中
                    if($model->scenario == 'default'){
                        //新加车辆
                        $transaction = yii::$app->db->beginTransaction();
                        $carLetRecordSave = $model->save(false);
                        
                        $statusRet = Car::changeCarStatusNew($model->car_id, 'LETING', 'customer/contract-record/add-edit-car', '批量添加或修改签约车辆',['car_status'=>'STOCK','is_del'=>0]);
                        
//                         $carUpdateStatus = Car::updateAll(['car_status'=>'LETING'],['id'=>$model->car_id]);
                        
                        if($carLetRecordSave && $statusRet['status']){
                            $transaction->commit();//提交事务
                            //添加车辆签约日志
                            UserLog::log("签约车辆(车牌号：{$data['plate_number'][$key]})",'sys');
                        }else{
                            $transaction->rollback();//回滚事务
                            $returnArr['info'] = '车辆：'.$data['plate_number'][$key].'签约信息修改失败！';
                        }
                    }else{
                        //修改合同信息
                        if($model->save(false)){
                            //修改成功
                            //添加签约车辆被修改日志
                            UserLog::log("修改签约车辆(车牌号：{$data['plate_number'][$key]})",'sys');
                        }else{
                            //修改失败
                            $returnArr['info'] = $data['plate_number'][$key].'签约信息修改失败！';
                        }
                    }
                }else{
                    $errors = $model->getErrors();
                    $returnArr['info'] .= $data['plate_number'][$key].'操作失败，错误原因：';
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
     * 合同签约车辆管理
     */
    public function actionCarManage()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        //查询合同信息
        $contractInfo = CarLetContract::find()
            ->select(['id','cCustomer_id'])
            ->where(['id'=>intval($id)])
            ->asArray()->one();
        $contractInfo or die('record not found');
        //查询本页面按钮
        $buttons = $this->getCurrentActionBtn();
        //获取行编辑时combox中可选的库存车辆-20160325
        $stockCars = Car::getAvailableStockCars();
        return $this->render('car-manage',[
            'contractInfo'=>$contractInfo,
            'buttons'=>$buttons,
            'stockCars'=>$stockCars
        ]);
    }

    /**
     * 获取指定合同签约车辆列表
     */
    public function actionGetCarList()
    {
        $contractId = yii::$app->request->get('contractId') or die('param contractId id required');
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CarLetRecord::find()
                ->select([
                    '{{%car_let_record}}.*',
                    '{{%car}}.`plate_number`'
                ])->joinWith('car',false,'LEFT JOIN')
                ->where([
                    '{{%car_let_record}}.`contract_id`'=>$contractId,
                    '{{%car_let_record}}.`back_time`'=>0,
                ]);
        //查询条件
        $query->andFilterWhere([
            'like',
            Car::tableName().'.`plate_number`',
            yii::$app->request->get('plate_number')
        ]);
        $data = $query->orderBy(CarLetRecord::tableName().'.`id` desc')
                ->asArray()->all();
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'plate_number':
                    $orderBy = Car::tableName().'.`plate_number` ';
                    break;
                default:
                    $orderBy = CarLetRecord::tableName().'.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = CarLetRecord::tableName().'.`id` ';
        }
        $orderBy .= $sortType;
        
//         echo $query->createCommand()->getRawSql();exit;
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
     * 签约车辆归还
     */
    public function actionCarBack()
    {
        $id = yii::$app->request->post('id') or die('param id is required');
        $id = rtrim($id,',');
        $id = explode(',',$id);
        $carLetRecord = CarLetRecord::find()
                        ->select(['car_id','contract_id','cCustomer_id'])
                        ->where(['in','id',$id])
                        ->andwhere(['back_time'=>0])
                        ->asArray()->all();
        $returnArr = [];
        $returnArr['status'] = true;
        $returnArr['info'] = '';
        
        $back_time = yii::$app->request->post('back_time');
        if($back_time){
        	$back_time = strtotime($back_time); 
        }else {
        	$back_time = time();
        }
        if($carLetRecord){
            //检查当前登录用户和要操作的合同的所属运营公司是否匹配-20160326
            $checkArr = CarLetContract::checkOperatingCompanyIsMatch($carLetRecord[0]['contract_id']);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }

            $carIds = array_column($carLetRecord,'car_id');
            //更新车辆状态是只将状态为‘出租中‘的车
            //辆状态改成库存，否则当车辆状态为‘故障状态‘时将导致状态错乱
            $statusRet = Car::changeCarStatusNew($carIds, 'STOCK', 'customer/contract-record/back-car', '签约车辆归还',['car_status'=>'LETING']);
            if(!$statusRet['status']){
            	return json_encode(['status'=>false,'info'=>$statusRet['info']]);
            }
            CarLetRecord::updateAll(['back_time'=>$back_time],['in','id',$id]);
            //添加车辆归还日志
            $contractId = $carLetRecord[0]['contract_id'];
            $CarLetContract = CarLetContract::findOne($contractId);
            $carInfo = Car::find()
                ->select(['plate_number'])
                ->where(['in','id',$carIds])
                ->asArray()->all();
            $carStr = join(',',array_column($carInfo,'plate_number'));
            $logInfo = '出租合同【'.($CarLetContract->number).'】-归还车辆:' . $carStr;
            UserLog::log($logInfo,'sys');
            $returnArr['status'] = true;
            $returnArr['info'] = "车辆归还成功！<br/>". $carStr;
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '所选车辆已经归还，本次操作无归还车辆！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 续费管理
     */
    public function actionRenewManage()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $buttons = $this->getCurrentActionBtn();
        return $this->render('renew-manage',[
            'contractId'=>$id,
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取合同续费列表
     */
    public function actionGetRenewList()
    {
        $contractId = yii::$app->request->get('contractId') or die('param contractId id required');
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CarLetContractRenewRecord::find()
                ->select([
                    CarLetContractRenewRecord::tableName().'.*',
                    'admin_name'=>Admin::tableName().'.`name`'
                ])->joinWith('admin',false,'LEFT JOIN')
                ->where([
                    CarLetContractRenewRecord::tableName().'.`contract_id`'=>$contractId
                ]);
        //查询条件
        $actionTimeStart = yii::$app->request->get('action_time_start');
        if(!empty($actionTimeStart)){
            $query->andFilterWhere([
                '>=',
                CarLetContractRenewRecord::tableName().'.`action_time`',
                strtotime($actionTimeStart)
            ]);
        }
        $actionTimeEnd = yii::$app->request->get('action_time_end');
        if(!empty($actionTimeEnd)){
            $query->andFilterWhere([
                '<=',
                CarLetContractRenewRecord::tableName().'.`action_time`',
                strtotime($actionTimeEnd)
            ]);
        }
        //查询条件
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
                ->orderBy(CarLetContractRenewRecord::tableName().'.`id` desc')
                ->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }
    
    /**
     * 归还车辆window
     */
    public function actionBackCarWindow()
    {
    	return $this->render('back-car-window');
    }

    /**
     * 新增续费记录
     */
    public function actionRenewAdd()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $contractId = yii::$app->request->post('contract_id') or die('param contractId is required');
            $contractInfo = CarLetContract::find()
                ->select(['number','cCustomer_id'])
                ->where(['id'=>$contractId])
                ->asArray()->one();
            if(!$contractInfo){
                echo json_encode(['status'=>false,'info'=>'未找到对应的合同记录！']);
                die;
            }
            //检查当前登录用户和要操作的合同的所属运营公司是否匹配-20160326
            $checkArr = CarLetContract::checkOperatingCompanyIsMatch($contractId);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $letCar = CarLetRecord::find()
                    ->select(['month_rent'])
                    ->where(['back_time'=>0,'contract_id'=>$contractId])
                    ->asArray()->all();
            $shouldMoney = 0.00;
            if($letCar){
                $shouldMoney = array_sum(array_column($letCar,'month_rent'));
            }
            $model = new CarLetContractRenewRecord();
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if($model->validate()){
                //更新合同续费时间
                //这里可能会使用到事务处理(待定...)
                if(!CarLetContract::updateAll(['cost_expire_time'=>$model->cost_expire_time],['id'=>$model->contract_id])){
                    $returnArr['status'] = false;
                    $returnArr['info'] = '更新合同续费时间失败！';
                }else{
                    $model->admin_id = $_SESSION['backend']['adminInfo']['id'];
                    $model->should_money = $shouldMoney;
                    $model->action_time = time();
                    $model->cCustomer_id = $contractInfo['cCustomer_id'];
                    if($model->save(false)){
                        $returnArr['status'] = true;
                        $returnArr['info'] = '合同续费记录添加成功！';
                        //添加操作日志
                        $logInfo = '新增续费记录(合同编号：'
                            . $contractInfo['number'] .
                            ')';
                        UserLog::log($logInfo,'sys');
                    }else{
                        $returnArr['status'] = false;
                        $returnArr['info'] = '合同续费记录添加失败！';
                    }
                }
            }else{
                $returnArr['status'] = false;
                $errors = $model->getErrors();
                if($errors){
                    foreach($errors as $v){
                        $returnArr['info'] .= $v[0];
                    }
                }else{
                    $returnArr['info'] = '未知错误！';
                }
            }
            echo json_encode($returnArr);
            return null;
        }
        //data submit end
        $contractId = yii::$app->request->get('contractId') or die('param contractId is required');
        $letCar = CarLetRecord::find()
                    ->select([
                        CarLetRecord::tableName().'.`month_rent`',
                        CarLetRecord::tableName().'.`let_time`',
                        CarLetRecord::tableName().'.`note`',
                        Car::tableName().'.`plate_number`'
                    ])->joinWith('car',false,'LEFT JOIN')
                    ->andwhere([
                        '=',
                        CarLetRecord::tableName().'.`back_time`',
                        0
                    ])
                    ->andwhere([
                        '=',
                        CarLetRecord::tableName().'.`contract_id`',
                        $contractId
                    ])->asArray()->all();
        $shouldMoney = 0.00;
        if($letCar){
            $shouldMoney = array_sum(array_column($letCar,'month_rent'));
        }
        return $this->render('renew-add',[
            'contractId'=>$contractId,
            'letCar'=>$letCar,
            'shouldMoney'=>$shouldMoney
        ]);
    }

    /**
     * 按条件导出合同
     */
    public function actionExportWidthCondition()
    {
        $query = CarLetContract::find()
                ->select([
                    '{{%car_let_contract}}.`number`',
                    '{{%car_let_contract}}.`contract_type`',
                    '{{%customer_company}}.`company_name`',
                    '{{%car_let_contract}}.`customer_type`',
                    '{{%car_let_contract}}.`sign_date`',
                    '{{%car_let_contract}}.`start_time`',
                    '{{%car_let_contract}}.`end_time`',
                    '{{%car_let_contract}}.`due_time`',
                    '{{%car_let_contract}}.`bail`',
                    '{{%car_let_contract}}.`cost_expire_time`',
                    '{{%car_let_contract}}.`note`',
                    'operating_company'=>'{{%operating_company}}.`name`',
                    '{{%car_let_contract}}.`reg_time`',
                    '{{%car_let_contract}}.`last_modify_datetime`',
                    '{{%admin}}.`username`',
                    'car_let_record_num'=>'count({{%car_let_record}}.`id`)',
                ])
                ->joinWith('customerCompany',false,'LEFT JOIN')
                ->joinWith('admin',false,'LEFT JOIN')
                ->joinWith('operatingCompany',false,'LEFT JOIN')
                ->leftJoin('{{%car_let_record}}', '{{%car_let_contract}}.`id` = {{%car_let_record}}.`contract_id` and {{%car_let_record}}.`is_del`=0 and {{%car_let_record}}.`back_time`=0')
//                 ->joinWith('carLetRecord',false,'LEFT JOIN')
                ->andWhere([
                    '{{%car_let_contract}}.customer_type'=>'COMPANY', //客户类型为“企业客户”
                    '{{%car_let_contract}}.`is_del`'=>0
                ])->groupBy(['{{%car_let_contract}}.id']);
        //////查询条件
        $query->andFilterWhere([
            '=',
            '{{%car_let_contract}}.`number`',
            yii::$app->request->get('number')
        ]);
        $query->andFilterWhere([
            'like',
            '{{%customer_company}}.`company_name`',
            yii::$app->request->get('customer_name')
        ]);
        $query->andFilterWhere([
            'like',
            '{{%car_let_contract}}.`contract_type`',
            yii::$app->request->get('contract_type') 
        ]);
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160326
        $isLimitedArr = CarLetContract::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car_let_contract}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //////查询条件
        $data = $query->asArray()->all();
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'contract record',
            'subject'=>'contract record',
            'description'=>'contract record list',
            'keywords'=>'contract record list',
            'category'=>'contract record list'
        ]);
        $lineData = [
            ['content'=>'合同编号','font-weight'=>true,'width'=>'20'],
            ['content'=>'合同类型','font-weight'=>true,'width'=>'20'],
            ['content'=>'客户名称','font-weight'=>true,'width'=>'20'],
            ['content'=>'客户类型','font-weight'=>true,'width'=>'15'],
            ['content'=>'签订时间','font-weight'=>true,'width'=>'15'],
            ['content'=>'开始时间','font-weight'=>true,'width'=>'15'],
            ['content'=>'结束时间','font-weight'=>true,'width'=>'15'],
            ['content'=>'合同期限','font-weight'=>true,'width'=>'15'],
            ['content'=>'保证金','font-weight'=>true,'width'=>'15'],
            ['content'=>'费用到期时间','font-weight'=>true,'width'=>'15'],
            ['content'=>'备注','font-weight'=>true,'width'=>'30'],
            ['content'=>'所属运营公司','font-weight'=>true,'width'=>'25'],
            ['content'=>'登记时间','font-weight'=>true,'width'=>'15'],
            ['content'=>'上次操作时间','font-weight'=>true,'width'=>'20'],
            ['content'=>'操作账号','font-weight'=>true,'width'=>'15'],
            ['content'=>'车辆数量','font-weight'=>true,'width'=>'15'],
        ];
        $excel->addLineToExcel($lineData);

        $configItems = ['customer_type'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');

        foreach($data as $val){
            $val['due_time'] = $val['due_time'] ? date('Y-m-d',$val['due_time']) : '';
            $val['start_time'] = $val['start_time'] ? date('Y-m-d',$val['start_time']) : '';
            $val['end_time'] = $val['end_time'] ? date('Y-m-d',$val['end_time']) : '';
            $val['cost_expire_time'] = $val['cost_expire_time'] ? date('Y-m-d',$val['cost_expire_time']) : '未启动';
            $val['reg_time'] = $val['reg_time'] ? date('Y-m-d',$val['reg_time']) : '';
            $val['sign_date'] = $val['sign_date'] ? date('Y-m-d',$val['sign_date']) : '';
            $val['last_modify_datetime'] = $val['last_modify_datetime'] ? date('Y-m-d H:i:s',$val['last_modify_datetime']) : '';
            $lineData = [];
            // 各combox配置项以txt代替val
            foreach($configItems as $conf) {
                if(isset($val[$conf]) && $val[$conf]) {
                    $val[$conf] = $configs[$conf][$val[$conf]]['text'];
                }
            }
            foreach($val as $k=>$v){
                if($k == 'bail'){
                    $lineData[] = ['content'=>$v,'align'=>'right'];
                }else{
                    $lineData[] = ['content'=>$v,'align'=>'left'];
                }
            }
            $excel->addLineToExcel($lineData);
        }
        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream"); 
        header("Accept-Ranges: bytes"); 
        //header("Accept-Length:".$fileSize); 
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','企业客户合同订单列表.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        //添加导出日志
        UserLog::log('按条件导出合同','sys');
    }

    /**
     * 删除合同
     */
    public function actionRemove()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        //检查当前登录用户和要操作的合同的所属运营公司是否匹配-20160326
        $checkArr = CarLetContract::checkOperatingCompanyIsMatch($id);
        if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
            return json_encode(['status'=>false,'info'=>$checkArr['info']]);
        }
        $returnArr = [];
        $returnArr['status'] = true;
        $returnArr['info'] = '';

        // 先检查该合同是否允许删除？（按理是不是合同一旦建立就都不允许被删除？！）
        $noBackNums = carLetRecord::find()
            ->where(['contract_id'=>$id,'back_time'=>0])
            ->count();
        if($noBackNums){
            $returnArr['status'] = false;
            $returnArr['info'] = '该合同有明细车辆"尚未归还"，不允许删除！';
            echo json_encode($returnArr); exit;
        }

        // 删除掉合同
        $contract = CarLetContract::findOne($id);
        $contract->is_del = 1;
        if($contract->save(false)){
            $returnArr['status'] = true;
            $returnArr['info'] = '合同删除成功！';
            // 同时删除掉明细车辆，以确保不会显示在出租记录列表中。
            // carLetRecord::updateAll(['is_del'=>1],['contract_id'=>$id]);
            // 添加日志
            $logInfo = '删除合同(合同编号:'. $contract->number . ')';
            UserLog::log($logInfo,'sys');
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '合同删除失败！';
        }
        echo json_encode($returnArr);
    }
    
   	/**
     * 终止合同
     */
    public function actionStop(){
    	if(yii::$app->request->isPost){
	    	$id = yii::$app->request->post('id') or die('param id is required');
	    	//检查当前登录用户和要操作的合同的所属运营公司是否匹配-20160326
	    	$checkArr = CarLetContract::checkOperatingCompanyIsMatch($id);
	    	if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
	    		return json_encode(['status'=>false,'info'=>$checkArr['info']]);
	    	}
	    	$returnArr = [];
	    	$returnArr['status'] = true;
	    	$returnArr['info'] = '';
	    	
	    	// 先检查该合同是否允许删除？（按理是不是合同一旦建立就都不允许被删除？！）
	//     	$noBackNums = carLetRecord::find()
	//     	->where(['contract_id'=>$id,'back_time'=>0])
	//     	->count();
	//     	if($noBackNums){
	//     		$returnArr['status'] = false;
	//     		$returnArr['info'] = '该合同有明细车辆"尚未归还"，不允许删除！';
	//     		echo json_encode($returnArr); exit;
	//     	}
	    	
	    	// 删除掉合同
	    	$contract = CarLetContract::findOne($id);
	    	$contract->is_stop = 1;
	    	$contract->stop_type = yii::$app->request->post('stop_type');
	    	$contract->stop_cause = yii::$app->request->post('stop_cause');
	    	if($contract->save(false)){
	    		$returnArr['status'] = true;
	    		$returnArr['info'] = '合同终止成功！';
	    	}else{
	    		$returnArr['status'] = false;
	    		$returnArr['info'] = '合同终止失败！';
	    	}
	    	echo json_encode($returnArr);
	    	exit;
    	}
    	$id = yii::$app->request->get('id') or die('param id is required');
    	$model = CarLetContract::findOne(['id'=>$id]);
    	$model or die('record not found');
    	
    	return $this->render('stop',[
	    			'contractId'=>$id,
	    			'contractInfo'=>$model->getAttributes()
    			]);
    }
    
    /**
     * 获取合同终止类型
     */
    public function actionGetStopType(){
    	$data = [
    				['id'=>0,'text'=>''],
    				['id'=>1,'text'=>'合同到期自动终止'],
    				['id'=>2,'text'=>'我方原因提前终止'],
    				['id'=>3,'text'=>'客户原因提前终止']
    			];
    	return json_encode($data);
    }
    /**
     * 获取合同终止原因
     */
    public function actionGetStopCause(){
    	$stop_type = yii::$app->request->get('stop_type');
    	switch ($stop_type){
    		case 2:
    			$data = [
	    			['id'=>0,'text'=>''],
	    			['id'=>1,'text'=>'客户欠费'],
	    			['id'=>2,'text'=>'未按时处理违章'],
	    			['id'=>3,'text'=>'未按时处理年检']
    			];
    			break;
    		case 3:
    			$data = [
    				['id'=>0,'text'=>''],
    				['id'=>4,'text'=>'运维服务保障差'],
    				['id'=>5,'text'=>'车型不符'],
    				['id'=>6,'text'=>'车辆质量'],
    				['id'=>7,'text'=>'路权政策']
    			];
    			break;
    		default:
		    	$data = [
			    	['id'=>0,'text'=>'无']
		    	];
    	}
    	return json_encode($data);
    }
}