<?php
/**
 * 个人客户试用协议-控制器
 * date: 2015-12-03
 * @author chengwk
 */
namespace backend\modules\customer\controllers;
use backend\controllers\BaseController;
use backend\models\CarTrialProtocol;
use backend\models\CarTrialProtocolDetails;
use backend\models\customerPersonal;
use backend\models\CarInsuranceBusiness;
use backend\models\Car;
use backend\classes\UserLog;//日志类
use common\models\Excel;
use yii;
use yii\data\Pagination;
use backend\models\ConfigCategory;

class PersonalProtocolController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
		$configItems = ['customer_type'];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        return $this->render('index', [
            'buttons'=>$buttons,
            'config'=>$config
        ]);
    }

    /**
     * 获取车辆试用协议列表
     */
    public function actionGetList()
    {
        $query = CarTrialProtocol::find()
            ->select([
                '{{%car_trial_protocol}}.*',
                'customer_name' => '{{%customer_personal}}.`id_name`',
                '{{%admin}}.`username`',
                'operating_company' => '{{%operating_company}}.`name`'
            ])
            ->joinWith('customerPersonal', false, 'LEFT JOIN')
            ->joinWith('admin', false, 'LEFT JOIN')
            ->joinWith('operatingCompany', false, 'LEFT JOIN')
            ->andWhere([
                'ctp_customer_type'=>'PERSONAL', //客户类型为“个人客户”
                'ctp_is_del'=>0
            ]);
        //查询条件
        $query->andFilterWhere(['like', 'ctp_number', yii::$app->request->get('ctp_number')]);
        $query->andFilterWhere(['like', '{{%customer_personal}}.`id_name`', yii::$app->request->get('customer_name')]);
        $query->andFilterWhere(['>=','ctp_start_date',yii::$app->request->get('ctp_start_date')]); //试用日期
        $query->andFilterWhere(['<=','ctp_end_date',yii::$app->request->get('ctp_end_date')]);
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160326
        $isLimitedArr = CarTrialProtocol::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car_trial_protocol}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //排序
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if ($sortColumn) {
            switch ($sortColumn) {
                case 'customer_name':
                    $orderBy = '{{%customer_personal}}.`id_name` ';
                    break;
                default:
                    $orderBy = '{{%car_trial_protocol}}.`' . $sortColumn . '` ';
                    break;
            }
        } else {
            $orderBy = 'ctp_id ';
        }
        $orderBy .= $sortType;
        $total = $query->count();
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
            ->orderBy($orderBy)
            ->asArray()->all();

        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /*
     * 检查新增的各车辆是否为“库存”状态。若是库存则再检查是否已添加商业险信息（在有效期内）。
     * （试用协议新增、修改、续签时都得检查）
     */
    protected function checkCarStockStatusAndBusinessInsurance($gridData){
        $notStockCars = [];
        $noBusinessInsuranceCars = [];
        foreach ($gridData as $val){
            if (!$val['ctpd_id']) { //只检查新签约车辆
                $plate_number = $val['plate_number'];
                $car = Car::find()->select(['id','car_status'])->where(['plate_number' => $plate_number, 'is_del'=>0])->asArray()->one();
                if ($car['car_status'] == 'STOCK') {
                    //若是'库存'则再检验商业险信息
                    $insurance = CarInsuranceBusiness::find()
                        ->select(['id'])
                        ->where([
                            'and',
                            ['car_id'=>$car['id']],
                            ['>','end_date',strtotime(time())]
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
            $returnArr['info'] = "下列车辆处于“<span style='color:red'>非库存</span>”状态，不能新增为试用车：<span style='color:red'>" . implode(',', $notStockCars) . "</span>";
            return $returnArr;
        }
        if(!empty($noBusinessInsuranceCars)) {
            //注意：与上面的非库存车辆不同，商业险只是提示，没有商业险时也允许新增！
            $noBusinessInsuranceTip = "下列车辆尚未完善商业险信息，试用前请确认已购买商业险：<span style='color:red'>" . implode(',', $noBusinessInsuranceCars) . "</span>";
            return $noBusinessInsuranceTip;
        }
        return true;
    }


    // 新建试用协议（同时保存协议明细车辆）
    public function actionAdd()
    {
        if (yii::$app->request->isPost){
            $returnArr = ['status'=>false,'info'=>''];
            $postData = yii::$app->request->post();
			$formData = $postData['formData']; 
			$gridData = $postData['gridData'];
            // 1.先检查要保存的各明细车辆数据
            if (!empty($gridData)){
                // 检查新增的各车辆是否为“库存”状态。若是库存则再检查是否已添加商业险信息（在有效期内）。
                $returnData = $this->checkCarStockStatusAndBusinessInsurance($gridData);
                if(is_array($returnData) && !empty($returnData)){
                    return json_encode($returnData); //不是库存车辆，直接退出
                }elseif(is_string($returnData) && $returnData != ''){
                    $noBusinessInsuranceTip = $returnData; //提示车辆无商业险
                }
            }
//             else{
//                 $returnArr['status'] = false;
//                 $returnArr['info'] = "没有任何车辆被添加！";
//                 return json_encode($returnArr);
//             }
            // 2.检查通过再新增协议
            parse_str($formData,$protocolArr); //parse_str()把查询字符串解析成数组并存入变量
			//print_r($protocolArr);exit;
            $protocolModel = new CarTrialProtocol;
            $protocolModel->load($protocolArr,'');
            $protocolModel->operating_company_id = $_SESSION['backend']['adminInfo']['operating_company_id'];
            if ($protocolModel->validate()){
                $protocolModel->ctp_systime = time();
                $protocolModel->ctp_sysuserid = $_SESSION['backend']['adminInfo']['id'];
                $protocolModel->ctp_sysuser = $_SESSION['backend']['adminInfo']['username'];
                $protocolModel->ctp_last_modify_datetime = time();  // 最后一次修改时间
                $protocolModel->ctp_modify_aid = $_SESSION['backend']['adminInfo']['id'];
                if ($protocolModel->save(false)){
                    $ctp_number = $protocolModel->ctp_number;
                    $baseTipStr = "新建了试用协议【" . $ctp_number . "】：";
                    $returnArr['status'] = true;
                    $returnArr['info'] = $logStr =  $baseTipStr . "新增了基本信息但未新增明细车辆！";
					// 3.继续保存该协议的明细车辆
                    if (!empty($gridData)){
                        $ctp_id = $protocolModel->ctp_id;
                        $ctp_pCustomer_id = $protocolModel->ctp_pCustomer_id; //保存的是'个人客户ID'
                        $operating_company_id = $protocolModel->operating_company_id; //所属运营公司ID
                        $addCar_success = [];    // 新增成功的车辆
                        $addCar_failure = [];    // 新增失败的车辆
                        foreach ($gridData as $val){
                            $plate_number = $val['plate_number'];
                            $car = Car::find()->select(['id','car_status2'])->where(['plate_number' => $plate_number,'is_del'=>0])->asArray()->one();
                            $carId = $car['id'];
                            $protocolDetailsModel = new CarTrialProtocolDetails;
                            $protocolDetailsModel->ctpd_protocol_id = $ctp_id;
                            $protocolDetailsModel->ctpd_pCustomer_id = $ctp_pCustomer_id;
                            $protocolDetailsModel->operating_company_id = $operating_company_id;
                            $protocolDetailsModel->ctpd_car_id = $carId;
                            $protocolDetailsModel->ctpd_deliver_date = $val['ctpd_deliver_date'];
                            $protocolDetailsModel->ctpd_note = $val['ctpd_note'];
                            //=====事务begin 防止并发操作导致同一辆车被加入多个协议中(表要改成InnoDB)
                            $transaction = yii::$app->db->beginTransaction();
                            $isSavedNewCar = $protocolDetailsModel->save(false);
//                             $carUpdateStatus = Car::updateAll(['car_status' => 'INTRIAL'], ['id' => $protocolDetailsModel->ctpd_car_id, 'car_status' => 'STOCK','is_del'=>0]); //改为“试用中”
                            $statusRet = Car::changeCarStatusNew($protocolDetailsModel->ctpd_car_id, 'INTRIAL', 'customer/personal-protocol/add', '新建试用协议（同时保存协议明细车辆），个人客户',['car_status'=>'STOCK','is_del'=>0]);
                            if ($isSavedNewCar && $statusRet['status']) {
                            	if($car['car_status2'] == 'REPAIRING'){
                            		$repairing = true;
                            	}
                                $transaction->commit();  //提交事务
                                $addCar_success[] = $plate_number;
                            } else {
                                $transaction->rollback(); //回滚事务
                                $addCar_failure[] = $plate_number;
                            }
                            //=====事务end==============================================================
                        }
                        // 提示信息
                        $tipStr_success = @$repairing?'目前该车有正在维修的记录，请注意确认！':'';
                        $tipStr_failure = '';
                        if(!empty($addCar_success)){
                            $tipStr_success .= '<br/>@新增成功-' . count($addCar_success) . '辆车：' . implode(',',$addCar_success);
                        }
                        if(!empty($addCar_failure)){
                            $tipStr_failure .= '<br/>@新增失败-' . count($addCar_failure) . '辆车：' . implode(',',$addCar_failure);
                        }
                        if ($tipStr_success){ // 若保存过明细车辆则再更新协议自身
                            $tmpStr = "新增了明细车辆：" . $tipStr_success . "<span style='color:red'>" . $tipStr_failure . "</span>";
                            $protocolModel->ctp_car_nums = count($addCar_success);   // 真正的试用车数量
                            if ($protocolModel->save(false)){
                                $returnArr['status'] = true;
                                $returnArr['info'] = $tmpStr;
                                $logStr = $baseTipStr . "新增了基本信息和明细车辆。" . $tipStr_success . $tipStr_failure;
                            }else{
                                $returnArr['status'] = false;
                                $returnArr['info'] = $tmpStr . "<br/><span style='color:red'>但是回头更新协议试用车辆数量时出错！</span>";
                                $logStr = $baseTipStr . "新增了基本信息和明细车辆。" . $tipStr_success . $tipStr_failure . "<br/>但是回头更新协议试用车辆数量时出错！";
                            }
                        }elseif($tipStr_failure){
                            $returnArr['status'] = false;
                            $returnArr['info'] = "新增明细车辆失败！". $tipStr_failure;
                            $logStr = $baseTipStr . "新增了基本信息但新增明细车辆失败！". $tipStr_failure;
                        }
                    }
                    // 添加日志
                    UserLog::log($logStr, 'sys');
                    if($returnArr['status']){
                        if(isset($noBusinessInsuranceTip)){
                            $returnArr['info'] .= '<br/>'.$noBusinessInsuranceTip;
                        }
                    }
                    return json_encode($returnArr); exit;
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '新建协议保存协议基本信息时出错！';
                    return json_encode($returnArr); exit;
                }
            }else{
                $returnArr['status'] = false;
                $errors = $protocolModel->getErrors();
                if ($errors){
                    foreach ($errors as $val) {
                        $returnArr['info'] .= $val[0];
                    }
                }else{
                    $returnArr['info'] = '未知错误！';
                }
            }
            return json_encode($returnArr); exit;
        }else{
            //获取行编辑时combox中可选的库存车辆-20160325
            $stockCars = Car::getAvailableStockCars();
            return $this->render('addWin', [
                'initDatas' => [
                    'cars' => $stockCars
                ]
            ]);
        }
    }

	/**
     *  修改试用协议（同时保存协议明细车辆，但车辆表格中只提交了发生改变的车）
     */
    public function actionEdit()
    {
        if (yii::$app->request->isPost){
            $returnArr = ['status'=>false,'info'=>''];
            $formData = yii::$app->request->post('formData');       // 协议信息
			$changedRows = yii::$app->request->post('changedRows'); // 发生改变的明细车辆
            parse_str($formData,$protocolArr); //parse_str()把查询字符串解析成数组并存入变量

            //检查当前登录用户和要操作的协议的所属运营公司是否匹配-20160326
            $checkArr = CarTrialProtocol::checkOperatingCompanyIsMatch($protocolArr['ctp_id']);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }

            // 1.先检查要保存的各明细车辆数据
            if (!empty($changedRows)){
                // 检查新增的各车辆是否为“库存”状态。若是库存则再检查是否已添加商业险信息（在有效期内）。
                $returnData = $this->checkCarStockStatusAndBusinessInsurance($changedRows);
                if(is_array($returnData) && !empty($returnData)){
                    return json_encode($returnData); //不是库存车辆，直接退出
                }elseif(is_string($returnData) && $returnData != ''){
                    $noBusinessInsuranceTip = $returnData; //提示车辆无商业险
                }
            }
            // 2.检查通过再更新协议
            $protocolModel = CarTrialProtocol::findOne($protocolArr['ctp_id']);
            $protocolModel->load($protocolArr,'');
            if ($protocolModel->validate()){
                $protocolModel->ctp_last_modify_datetime = time();
                $protocolModel->ctp_modify_aid = $_SESSION['backend']['adminInfo']['id'];
                if ($protocolModel->save(false)){
                    $ctp_number = $protocolModel->ctp_number;
                    $baseTipStr = "修改了试用协议【" . $ctp_number . "】：";
                    $returnArr['status'] = true;
                    $returnArr['info'] = $logStr = $baseTipStr . "更新了基本信息但未改动明细车辆！";
					// 3.继续保存该协议的明细车辆
					if (!empty($changedRows)){
                        $addCar_success = []; 	// 新增成功的车辆
                        $addCar_failure = [];	// 新增失败的车辆
                        $editCar_success = [];	// 修改成功的车辆
                        $editCar_failure = [];	// 修改失败的车辆
                        $ctp_id = $protocolModel->ctp_id;
                        $ctp_pCustomer_id = $protocolModel->ctp_pCustomer_id;
						foreach ($changedRows as $val){
                            $plate_number = $val['plate_number'];
							$car = Car::find()->select(['id'])->where(['plate_number'=>$plate_number,'is_del'=>0])->asArray()->one();
                            $carId = $car['id'];
							// 若是新增明细车辆
							if (!$val['ctpd_id']){
								$protocolDetailsModel = new CarTrialProtocolDetails;
								$protocolDetailsModel->ctpd_protocol_id = $ctp_id;
                                $protocolDetailsModel->ctpd_pCustomer_id = $ctp_pCustomer_id;
                                $protocolDetailsModel->ctpd_car_id = $carId;
                                $protocolDetailsModel->ctpd_deliver_date = $val['ctpd_deliver_date'];
                                $protocolDetailsModel->ctpd_note = $val['ctpd_note'];
								//=====事务begin 防止并发操作导致同一辆车被加入多个协议中(表要改成InnoDB)
								$transaction = yii::$app->db->beginTransaction();
								$isSavedNewCar = $protocolDetailsModel->save(false);
// 								$carUpdateStatus = Car::updateAll(['car_status'=>'INTRIAL'],['id'=>$carId,'car_status'=>'STOCK','is_del'=>0]); //改为“试用中”
								$statusRet = Car::changeCarStatusNew($carId, 'INTRIAL', 'customer/personal-protocol/edit', '编辑试用协议（同时保存协议明细车辆），个人客户',['car_status'=>'STOCK','is_del'=>0]);
								if($isSavedNewCar && $statusRet['status']){
									$transaction->commit();  //提交事务
                                    $addCar_success[] = $plate_number;
								}else{
                                    $transaction->rollback(); //回滚事务
                                    $addCar_failure[] = $plate_number;
								}
								//=====事务end============================================================
							}else{ // 若是修改明细车辆，旧车辆不给修改！
								$protocolDetailsModel = CarTrialProtocolDetails::findOne($val['ctpd_id']);
                                $protocolDetailsModel->ctpd_pCustomer_id = $ctp_pCustomer_id;
                                $protocolDetailsModel->ctpd_deliver_date = $val['ctpd_deliver_date'];
                                $protocolDetailsModel->ctpd_note = $val['ctpd_note'];
								$isSavedNewCar = $protocolDetailsModel->save(false);
                                if($isSavedNewCar){
                                    $editCar_success[] = $plate_number;
                                }else {
                                    $editCar_failure[] = $plate_number;
                                }
							}
						}
                        // 提示信息
                        $tipStr_success = '';  $tipStr_failure = '';
                        if(!empty($addCar_success)){
                            $tipStr_success .= '<br/>@新增成功-' . count($addCar_success) . '辆车：' . implode(',',$addCar_success);
                        }
                        if(!empty($editCar_success)){
                            $tipStr_success .= '<br/>@修改成功-' . count($editCar_success) . '辆车：' . implode(',',$editCar_success);
                        }
                        if(!empty($addCar_failure)){
                            $tipStr_failure .= '<br/>@新增失败-' . count($addCar_failure) . '辆车：' . implode(',',$addCar_failure);
                        }
                        if(!empty($editCar_failure)){
                            $tipStr_failure .= '<br/>@修改失败-' . count($editCar_failure) . '辆车：' . implode(',',$editCar_failure);
                        }
                        if ($tipStr_success){ // 若保存过明细车辆则再更新协议自身
                            $tmpStr = "新增/修改了明细车辆：" . $tipStr_success . "<span style='color:red'>" . $tipStr_failure . "</span>";
                            $realTrialCarNums = CarTrialProtocolDetails::find()->where(['ctpd_protocol_id' => $ctp_id, 'ctpd_is_del' => 0])->count();
                            $protocolModel->ctp_car_nums = $realTrialCarNums;   // 真正的试用车数量
                            if ($protocolModel->save(false)){
                                $returnArr['status'] = true;
                                $returnArr['info'] = $tmpStr;
                                $logStr = $baseTipStr . "更新了基本信息并新增/修改了明细车辆。" . $tipStr_success . $tipStr_failure;
                            }else{
                                $returnArr['status'] = false;
                                $returnArr['info'] = $tmpStr . "<br/><span style='color:red'>但是再回头更新协议试用车辆数量时出错！</span>";
                                $logStr = $baseTipStr . "更新了基本信息并新增/修改了明细车辆。" . $tipStr_success . $tipStr_failure . "<br/>但是再回头更新协议试用车辆数量时出错！";
                            }
                        }elseif($tipStr_failure){
                            $returnArr['status'] = false;
                            $returnArr['info'] = "新增/修改明细车辆失败！". $tipStr_failure;
                            $logStr = $baseTipStr . "更新了基本信息但新增/修改明细车辆失败！". $tipStr_failure;
                        }
					}
                    // 添加日志
                    UserLog::log($logStr, 'sys');
                    if($returnArr['status']){
                        if(isset($noBusinessInsuranceTip)){
                            $returnArr['info'] .= '<br/>'.$noBusinessInsuranceTip;
                        }
                    }
                    return json_encode($returnArr); exit;
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '修改协议保存协议基本信息时出错！';
                    return json_encode($returnArr); exit;
                }
            }else{
                $returnArr['status'] = false;
                $errors = $protocolModel->getErrors();
                if ($errors){
                    foreach ($errors as $val){
                        $returnArr['info'] .= $val[0];
                    }
                }else{
                    $returnArr['info'] = '未知错误！';
                }
            }
            return json_encode($returnArr); exit;
        }else{
            $id = yii::$app->request->get('id') or die("Param 'id' is needed");
            $carTrialProtocol = CarTrialProtocol::find()->where(['ctp_id' => $id])->asArray()->one();
            //获取行编辑时combox中可选的库存车辆-20160325
            $stockCars = Car::getAvailableStockCars();
            return $this->render('editWin', [
                'initDatas' => [
                    'cars' => $stockCars,
                    'carTrialProtocol' => $carTrialProtocol
                ]
            ]);
        }
    }

    /**
     *  获取某协议的明细车辆（修改协议窗口、试用车管理窗口）
     */
    public function actionGetProtocolDetails()
    {
        $ctp_id = yii::$app->request->get('ctp_id');
        if (!$ctp_id) {
            echo json_encode(['rows' => [], 'total' => 0]);
            exit;
        }
        $query = CarTrialProtocolDetails::find()
            ->select(['{{%car_trial_protocol_details}}.*','{{%car}}.plate_number'])
            ->joinWith('car', false, 'LEFT JOIN')
            ->where(['ctpd_protocol_id' => $ctp_id, 'ctpd_is_del' => 0]);

		// 若是在试用车管理窗口：列表只显示未归还车辆；可能依据车牌作查询。
		if(yii::$app->request->get('inManageWin')){
			$query->andWhere(['ctpd_back_date'=>NULL]);
			$query->andFilterWhere(['like', '{{%car}}.plate_number', yii::$app->request->get('plate_number')]);
		}

        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        //排序
        if (yii::$app->request->get('sort')) {
            $field = yii::$app->request->get('sort');        //field
            $direction = yii::$app->request->get('order');  //asc or desc
            $orderStr = $field . ' ' . $direction;
        } else {
            $orderStr = 'ctpd_id DESC';
        }
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderStr)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
        exit;
    }

    /**
     * 删除试用协议
     */
    public function actionRemove()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        //检查当前登录用户和要操作的协议的所属运营公司是否匹配-20160326
        $checkArr = CarTrialProtocol::checkOperatingCompanyIsMatch($id);
        if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
            return json_encode(['status'=>false,'info'=>$checkArr['info']]);
        }
        $returnArr = ['status'=>false,'info'=>''];
        // 先检查该试用协议是否允许删除？（按理是不是协议一旦建立就都不允许被删除？！）
        $noBackNums = CarTrialProtocolDetails::find()
            ->where(['ctpd_protocol_id'=>$id,'ctpd_is_del'=>0,'ctpd_back_date'=>NULL])
            ->count();
        if($noBackNums){
            $returnArr['status'] = false;
            $returnArr['info'] = '该协议有明细车辆"尚未归还"，不允许删除！';
            echo json_encode($returnArr); exit;
        }
        // 删除掉协议
        $protocol = CarTrialProtocol::findOne($id);
        $protocol->ctp_is_del = 1;
        if($protocol->save(false)){
            $returnArr['status'] = true;
            $returnArr['info'] = '试用协议删除成功！';
            // 同时删除掉明细车辆，以确保不会显示在试用记录列表中。
            // CarTrialProtocolDetails::updateAll(['ctpd_is_del'=>1],['ctpd_protocol_id'=>$id]);
            // 添加日志
            $logInfo = '删除试用协议【' . $protocol->ctp_number .'】';
            UserLog::log($logInfo,'sys');
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '试用协议删除失败！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 试用车辆管理
     */
    public function actionManageCars(){
		$id = yii::$app->request->get('id') or die("Param 'id' is needed");
        //获取行编辑时combox中可选的库存车辆-20160325
        $stockCars = Car::getAvailableStockCars();
		return $this->render('manageCarsWin', [
			'initDatas' => [
				'cars' => $stockCars,
				'ctp_id' => $id
			]
		]);
	}

    /**
     * 确定保存车辆 （修改协议窗口、试用车管理窗口）
     */
    public function actionSaveCars()
    {
        if (yii::$app->request->isPost){
            $returnArr = ['status'=>false,'info'=>''];
            $ctp_id = yii::$app->request->post('ctp_id');           // 协议ID
            $changedRows = yii::$app->request->post('changedRows'); // 发生改变的明细车辆

            //检查当前登录用户和要操作的协议的所属运营公司是否匹配-20160326
            $checkArr = CarTrialProtocol::checkOperatingCompanyIsMatch($ctp_id);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }

            // 1.先检查要保存的各明细车辆数据
            if (!empty($changedRows)){
                // 检查新增的各车辆是否为“库存”状态。若是库存则再检查是否已添加商业险信息（在有效期内）。
                $returnData = $this->checkCarStockStatusAndBusinessInsurance($changedRows);
                if(is_array($returnData) && !empty($returnData)){
                    return json_encode($returnData); //不是库存车辆，直接退出
                }elseif(is_string($returnData) && $returnData != ''){
                    $noBusinessInsuranceTip = $returnData; //提示车辆无商业险
                }
            }else{
                $returnArr['status'] = false;
                $returnArr['info'] = "没有任何车辆被添加或修改！";
                return json_encode($returnArr);
            }
            // 2.检查通过再保存明细车辆
            $addCar_success = []; 	// 新增成功的车辆
            $addCar_failure = [];	// 新增失败的车辆
            $editCar_success = [];	// 修改成功的车辆
            $editCar_failure = [];	// 修改失败的车辆
            $protocolModel = CarTrialProtocol::findOne($ctp_id);
            $ctp_pCustomer_id = $protocolModel->ctp_pCustomer_id;
            $operating_company_id = $protocolModel->operating_company_id;
            foreach ($changedRows as $val){
                $plate_number = $val['plate_number'];
                $car = Car::find()->select(['id','car_status2'])->where(['plate_number'=>$plate_number,'is_del'=>0])->asArray()->one();
                $carId = $car['id'];
                // 若是新增明细车辆
                if (!$val['ctpd_id']){
                    $protocolDetailsModel = new CarTrialProtocolDetails;
                    $protocolDetailsModel->ctpd_protocol_id = $ctp_id;
                    $protocolDetailsModel->ctpd_pCustomer_id = $ctp_pCustomer_id;
                    $protocolDetailsModel->operating_company_id = $operating_company_id;
                    $protocolDetailsModel->ctpd_car_id = $carId;
                    $protocolDetailsModel->ctpd_deliver_date = $val['ctpd_deliver_date'];
                    $protocolDetailsModel->ctpd_note = $val['ctpd_note'];
                    //=====事务begin 防止并发操作导致同一辆车被加入多个协议中(表要改成InnoDB)
                    $transaction = yii::$app->db->beginTransaction();
                    $isSavedNewCar = $protocolDetailsModel->save(false);
//                     $carUpdateStatus = Car::updateAll(['car_status'=>'INTRIAL'],['id'=>$carId,'car_status'=>'STOCK','is_del'=>0]); //改为“试用中”
                    $statusRet = Car::changeCarStatusNew($carId, 'INTRIAL', 'customer/personal-protocol/save-cars', '确定保存车辆 （修改协议窗口、试用车管理窗口），个人客户',['car_status'=>'STOCK','is_del'=>0]);
                    if($isSavedNewCar && $statusRet['status']){
                    	if($car['car_status2'] == 'REPAIRING'){
                    		$repairing = true;
                    	}
                        $transaction->commit();  //提交事务
                        $addCar_success[] = $plate_number;
                    }else{
                        $transaction->rollback(); //回滚事务
                        $addCar_failure[] = $plate_number;
                    }
                    //=====事务end============================================================
                }else{ // 若是修改明细车辆，旧车辆不给修改！
                	if($car['car_status2'] == 'REPAIRING'){
                		$repairing = true;
                	}
                    $protocolDetailsModel = CarTrialProtocolDetails::findOne($val['ctpd_id']);
                    $protocolDetailsModel->ctpd_pCustomer_id = $ctp_pCustomer_id;
                    $protocolDetailsModel->ctpd_deliver_date = $val['ctpd_deliver_date'];
                    $protocolDetailsModel->ctpd_note = $val['ctpd_note'];
                    $isSavedNewCar = $protocolDetailsModel->save(false);
                    if($isSavedNewCar){
                        $editCar_success[]= $plate_number;
                    }else{
                        $editCar_failure[] = $plate_number;
                    }
                }
            }
            // 3.提示信息
            $tipStr_success = @$repairing?'目前该车有正在维修的记录，请注意确认！':'';
            $tipStr_failure = '';
            if(!empty($addCar_success)){
                $tipStr_success .= '<br/>@新增成功-' . count($addCar_success) . '辆车：' . implode(',',$addCar_success);
            }
            if(!empty($editCar_success)){
                $tipStr_success .= '<br/>@修改成功-' . count($editCar_success) . '辆车：' . implode(',',$editCar_success);
            }
            if(!empty($addCar_failure)){
                $tipStr_failure .= '<br/>@新增失败-' . count($addCar_failure) . '辆车：' . implode(',',$addCar_failure);
            }
            if(!empty($editCar_failure)){
                $tipStr_failure .= '<br/>@修改失败-' . count($editCar_failure) . '辆车：' . implode(',',$editCar_failure);
            }
            if ($tipStr_success){ // 若保存过明细车辆则再更新协议自身
                $tmpStr = "新增/修改了明细车辆：" . $tipStr_success . "<span style='color:red'>" . $tipStr_failure . "</span>";
                $realTrialCarNums = CarTrialProtocolDetails::find()->where(['ctpd_protocol_id' => $ctp_id, 'ctpd_is_del' => 0])->count();
                $protocolModel->ctp_car_nums = $realTrialCarNums;   // 真正的试用车数量
                $protocolModel->ctp_last_modify_datetime = time();  // 最后一次修改时间
                $protocolModel->ctp_modify_aid = $_SESSION['backend']['adminInfo']['id'];
                $ctp_number = $protocolModel->ctp_number;
                if ($protocolModel->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = $tmpStr;
                    $returnArr['protocol'] = $protocolModel->getAttributes(); // 给前端表单赋值
                    $logStr = "修改了试用协议【" . $ctp_number . "】明细车辆：" . $tipStr_success . $tipStr_failure;
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = $tmpStr . "<br/><span style='color:red'>但是再回头更新协议试用车辆数量等时出错！</span>";
                    $logStr = "修改了试用协议【" . $ctp_number . "】明细车辆：" . $tipStr_success . $tipStr_failure . "<br/>但是再回头更新协议试用车辆数量等时出错！";
                }
                // 添加日志
                UserLog::log($logStr, 'sys');
                if($returnArr['status']){
                    if(isset($noBusinessInsuranceTip)){
                        $returnArr['info'] .= '<br/>'.$noBusinessInsuranceTip;
                    }
                }
                return json_encode($returnArr); exit;
            }elseif($tipStr_failure){
                $returnArr['status'] = false;
                $returnArr['info'] = '新增/修改明细车辆失败！'. $tipStr_failure;
                return json_encode($returnArr); exit;
            }
        }else{
            $id = yii::$app->request->get('id') or die("Param 'id' is needed");
            $carTrialProtocol = CarTrialProtocol::find()->where(['ctp_id' => $id])->asArray()->one();
            //获取行编辑时combox中可选的库存车辆-20160325
            $stockCars = Car::getAvailableStockCars();
            return $this->render('editWin', [
                'initDatas' => [
                    'cars' => $stockCars,
                    'carTrialProtocol' => $carTrialProtocol
                ]
            ]);
        }
        echo json_encode($returnArr); exit;
	}	
	
    /**
     * 归还车辆 （修改协议窗口、试用车管理窗口）
     */
    public function actionBackCar()
    {	
        $idStr = yii::$app->request->post('idStr') or die('param "idStr" is required');
        $idStr = rtrim($idStr,',');
        $ids = explode(',',$idStr);
        $protocolDetails = CarTrialProtocolDetails::find()
                        ->select(['ctpd_id','ctpd_protocol_id','ctpd_car_id'])
                        ->where(['in','ctpd_id',$ids])
                        ->andwhere(['ctpd_back_date'=>NUll])
                        ->asArray()->all();
        $returnArr = ['status'=>false,'info'=>''];
        if($protocolDetails){
            //检查当前登录用户和要操作的协议的所属运营公司是否匹配-20160326
            $checkArr = CarTrialProtocol::checkOperatingCompanyIsMatch($protocolDetails[0]['ctpd_protocol_id']);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $carIds = array_column($protocolDetails,'ctpd_car_id');
            //更新车辆状态,只将状态为"试用中"的车辆状态改成"库存"，否则当车辆状态为"故障"时将导致状态错乱
            Car::changeCarStatusNew($carIds, 'STOCK', 'customer/car-trial-protocol/back-car', '归还车辆 （修改协议窗口、试用车管理窗口），个人客户',['car_status'=>'INTRIAL']);
//             Car::updateAll(['car_status'=>'STOCK'],['id'=>$carIds,'car_status'=>'INTRIAL']); // 将“试用中”车辆状态改为“库存”
            CarTrialProtocolDetails::updateAll(['ctpd_back_date'=>date('Y-m-d')],['in','ctpd_id',$ids]); // 还车时间
			//添加日志
			$protocolId = $protocolDetails[0]['ctpd_protocol_id'];
			$CarTrialProtocol = CarTrialProtocol::findOne($protocolId);
            $carInfo = Car::find()
                ->select(['plate_number'])
                ->where(['in','id',$carIds])
                ->asArray()->all();
            $carStr = join(',',array_column($carInfo,'plate_number'));
            $logInfo = '试用协议【'.($CarTrialProtocol->ctp_number).'】-归还车辆:' . $carStr;
            UserLog::log($logInfo,'sys');
            $returnArr['status'] = true;
            $returnArr['info'] = "归还车辆成功！<br/>". $carStr;
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '所选车辆都已归还！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 按条件导出Excel
     */
    public function actionExportWidthCondition()
    {
        // 构建excel表头（这里有2行表头）
        $excHeaders = [
            [
                ['content'=>'协议编号','font-weight'=>true,'width'=>'30'],
                ['content'=>'试用客户','font-weight'=>true,'width'=>'30'],
				['content'=>'客户类型','font-weight'=>true,'width'=>'15'],
                ['content'=>'签订日期','font-weight'=>true,'width'=>'15'],
                ['content'=>'开始时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'结束时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'车辆数量','font-weight'=>true,'width'=>'15'],
                ['content'=>'登记时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'操作人员','font-weight'=>true,'width'=>'15'],
                ['content'=>'备注','font-weight'=>true,'width'=>'30']
            ]
        ];
        // 要查的字段，与导出的excel表头对应
        $selectArr = [
            'ctp_number',
            'customer_name' => '{{%customer_personal}}.id_name',
			'ctp_customer_type',
            'ctp_sign_date','ctp_start_date','ctp_end_date','ctp_car_nums',
            'add_date'=>'LEFT(FROM_UNIXTIME(`ctp_systime`),10)',
            'ctp_sysuser','ctp_note',
            'operating_company' => '{{%operating_company}}.name'
        ];
        $query = CarTrialProtocol::find()
            ->select($selectArr)
            ->joinWith('customerPersonal', false, 'LEFT JOIN')
            ->joinWith('admin', false, 'LEFT JOIN')
            ->joinWith('operatingCompany', false, 'LEFT JOIN')
            ->andWhere([
                'ctp_customer_type'=>'PERSONAL', //客户类型为“个人客户”
                'ctp_is_del'=>0
            ]);
        //查询条件
        $query->andFilterWhere(['like', 'ctp_number', yii::$app->request->get('ctp_number')]);
        $query->andFilterWhere(['like', '{{%customer_personal}}.`id_name`', yii::$app->request->get('customer_name')]);
        $query->andFilterWhere(['>=','ctp_start_date',yii::$app->request->get('ctp_start_date')]); //试用日期
        $query->andFilterWhere(['<=','ctp_end_date',yii::$app->request->get('ctp_end_date')]);
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160326
        $isLimitedArr = CarTrialProtocol::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car_trial_protocol}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        $data = $query->asArray()->all();
        //print_r($data);exit;

        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'car_trial_protocol',
            'subject'=>'car_trial_protocol',
            'description'=>'car_trial_protocol list',
            'keywords'=>'car_trial_protocol list',
            'category'=>'car_trial_protocol list'
        ]);
        // excel表头
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }
		
		$configItems = ['customer_type'];
        $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');

        foreach($data as $val){
			$lineData = [];
            // 各combox配置项以txt代替val
            foreach($configItems as $conf) {
                if(isset($val['ctp_'.$conf]) && $val['ctp_'.$conf]) {
                    $val['ctp_'.$conf] = $configs[$conf][$val['ctp_'.$conf]]['text'];
                }
            }
            foreach($val as $v){
                if(!is_array($v)){
                    $lineData[] = ['content'=>$v,'align'=>'left'];
                }
            }
            $excel->addLineToExcel($lineData);
        }
        unset($data);

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','个人客户试用协议列表.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }



}