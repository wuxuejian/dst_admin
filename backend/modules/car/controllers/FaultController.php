<?php
namespace backend\modules\car\controllers;
use backend\controllers\BaseController;
use backend\models\ConfigCategory;
use backend\models\CarFault;
use backend\models\CarFaultDisposeProgress;
use backend\models\Car;
use backend\models\CarLetRecord;
use backend\models\CarTrialProtocolDetails;
use backend\models\OperatingCompany;
use backend\models\CarBrand;
use common\models\Excel;
use backend\classes\UserLog;
use yii;
use yii\data\Pagination;
use yii\web\UploadedFile;
use common\classes\Resizeimage;
/**
 * 车辆故障管理控制器
 * @author wangmin
 * 
 */
class FaultController extends BaseController
{
    /**
     * 车辆故障管理首页
     */
    public function actionAllIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        $config = (new ConfigCategory())->getCategoryConfig(['fault_status','customer_type','car_model_name'],'value');
        return $this->render('all-index',[
            'config'=>$config,
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取车辆故障列表
     */
    public function actionGetAllList()
    {
        $activeRecord = CarFault::find()
            ->select([
                CarFault::tableName().'.*',
                '{{%car}}.`plate_number`',
                '{{%car}}.`vehicle_dentification_number`',
                '{{%car}}.`brand_id`',
                '{{%car}}.`car_model`',
                '{{%car}}.`operating_company_id`',
                '{{%admin}}.`username`',
                //'conNumber'=>'{{%car_let_contract}}.`number`',
                //'proNumber'=>'{{%car_trial_protocol}}.`ctp_number`',
                'cCustomer'=>'{{%customer_company}}.`company_name`',
                'pCustomer'=>'{{%customer_personal}}.`id_name`'
            ])
            ->joinWith('car',false,'LEFT JOIN')
            ->joinWith('admin',false,'LEFT JOIN')
            //->joinWith('carLetContract',false,'LEFT JOIN') //查出租或试用信息
            //->joinWith('carTrialProtocol',false,'LEFT JOIN')
            ->joinWith('customerCompany',false,'LEFT JOIN')
            ->joinWith('customerPersonal',false,'LEFT JOIN')
            ->andWhere(['{{%car_fault}}.`is_del`'=>0]);
        //////查询条件开始
        $activeRecord->andFilterWhere([ //车牌号/车架号
            'or',
            ['like','{{%car}}.`plate_number`',yii::$app->request->get('plate_number')],
            ['like','{{%car}}.`vehicle_dentification_number`',yii::$app->request->get('plate_number')]
        ]);
        $activeRecord->andFilterWhere([ //企业/个人客户名称
            'or',
            ['like','{{%customer_company}}.`company_name`',yii::$app->request->get('customer_name')],
            ['like','{{%customer_personal}}.`id_name`',yii::$app->request->get('customer_name')]
        ]);
        if(yii::$app->request->get('customer_type')){ //企业/个人客户类型
            if(yii::$app->request->get('customer_type') == 'COMPANY'){
                $activeRecord->andWhere("{{%car_fault}}.`cCustomer_id` > 0 ");
            }elseif(yii::$app->request->get('customer_type') == 'PERSONAL'){
                $activeRecord->andWhere("{{%car_fault}}.`pCustomer_id` > 0 ");
            }
        }
        $activeRecord->andFilterWhere(['like','{{%car_fault}}.`number`',yii::$app->request->get('number')]);
        $activeRecord->andFilterWhere(['like','{{%car_fault}}.`f_desc`',yii::$app->request->get('f_desc')]);
        $activeRecord->andFilterWhere(['=','{{%car_fault}}.`fault_status`',yii::$app->request->get('fault_status')]);
        $activeRecord->andFilterWhere(['=','{{%car_fault}}.`ap_name`',yii::$app->request->get('ap_name')]);
        $activeRecord->andFilterWhere(['=','{{%car}}.`brand_id`',yii::$app->request->get('brand_id')]);
        $activeRecord->andFilterWhere(['like','{{%car}}.`car_model`',yii::$app->request->get('car_model')]);
        $activeRecord->andFilterWhere(['=','{{%car}}.`operating_company_id`',yii::$app->request->get('operating_company_id')]);
        $activeRecord->andFilterWhere(['>=','{{%car_fault}}.`reg_datetime`',yii::$app->request->get('regDatetime_start')]);
        if(yii::$app->request->get('regDatetime_end')){
            $activeRecord->andFilterWhere(['<=','{{%car_fault}}.`reg_datetime`',yii::$app->request->get('regDatetime_end').' 23:59:59']);
        }
        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany();
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $activeRecord->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }
        //////查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        if($sortColumn){
            switch ($sortColumn) {
                case 'plate_number':
                    $orderBy = '{{%car}}.`plate_number` ';
                    break;
                case 'customer_name':
                    $orderBy = '{{%customer_company}}.`company_name` ';
                    break;
                case 'brand_id':
                    $orderBy = '{{%car}}.`brand_id` ';
                    break;
                case 'operating_company_id':
                    $orderBy = '{{%car}}.`operating_company_id` ';
                    break;
                case 'car_model':
                    $orderBy = '{{%car}}.`car_model` ';
                    break;
                case 'username':
                    $orderBy = '{{%admin}}.`username` ';
                    break;
                case 'countdown':
                    $orderBy = '{{%car_fault}}.`expect_end_date` ';
                    break;
                default:
                    $orderBy = '{{%car_fault}}.`'.$sortColumn.'` ';
                    break;
            }
            $orderBy .= $sortType;
        }else{
            $orderBy = '{{%car_fault}}.`id` DESC';
        }
        //排序结束
        $total = $activeRecord->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount'=>$total, 'pageSize'=>$pageSize]);
        $data = $activeRecord->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        //倒计时
        foreach($data as &$CFaultItem){
            $CFaultItem['countdown'] = '';
            if($CFaultItem['expect_end_date']){
                if($CFaultItem['fault_status'] != 'PROCESSED'){
                    if($CFaultItem['expect_end_date'] < date('Y-m-d')){
                        $CFaultItem['countdown'] = '<span style="color:red;">已过期</span>';
                    }else{
                        $sec = strtotime($CFaultItem['expect_end_date']) - strtotime(date('Y-m-d'));
                        $day = floor( $sec / (3600*24) ) + 1;
                        $CFaultItem['countdown'] = $day . '天';
                    }
                }
            }
        }
        //车辆运营公司
        $oCompany = OperatingCompany::getOperatingCompany();
        //车辆品牌
        $carBrand = CarBrand::getCarBrands();
        foreach($data as &$dataItem){
            if(isset($oCompany[$dataItem['operating_company_id']]) && $oCompany[$dataItem['operating_company_id']]){
                $dataItem['operating_company_id'] = $oCompany[$dataItem['operating_company_id']]['name'];
            }
            if(isset($carBrand[$dataItem['brand_id']]) && $carBrand[$dataItem['brand_id']]){
                $dataItem['brand_id'] = $carBrand[$dataItem['brand_id']]['name'];
            }
        }
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }


    /**
     * 获取车辆 （故障登记窗口的combogrid）
     */
    public function actionGetCars()
    {
        $queryStr = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : ''; // 检索过滤字符串
        $carId = isset($_REQUEST['car_id']) ? intval($_REQUEST['car_id']) : 0; //修改时赋值用
        $query = Car::find()
            ->select(['id','plate_number','vehicle_dentification_number'])
            ->where(['is_del'=>0]);

        //检测是否要根据当前登录人员所属运营公司来显示列表数据-20160325
        $isLimitedArr = Car::isLimitedToShowByAdminOperatingCompany(true);
        if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
            $query->andWhere("{{%car}}.`operating_company_id` in ({$isLimitedArr['adminInfo_operatingCompanyId']})");
        }

        if($carId){
            // 修改时查询赋值
            $total = $query->andWhere(['id'=>$carId])->count();
        }elseif($queryStr){
            // 检索过滤时
            $total = $query->andWhere([
                    'or',
                    ['like', 'plate_number', $queryStr],
                    ['like', 'vehicle_dentification_number', $queryStr]
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
    }


    /**
     * 故障登记
     */
    public function actionRegister(){
        if(yii::$app->request->isPost){
            $model = new CarFault();
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            if($model->validate()){
                //故障编号，格式：GZ+日期+3位数（即该故障是系统当天登记的第几个，第一个是001，第二个是002…）
                $todayCount = CarFault::find()
                    ->where([
                        'and',
                        ['>=','reg_datetime',date('Y-m-d').' 00:00:00'],
                        ['<=','reg_datetime',date('Y-m-d').' 23:59:59'],
                    ])
                    ->count();
                $currentNo = str_pad($todayCount+1,3,0,STR_PAD_LEFT);
                $model->number = 'GZ' . date('Ymd') . $currentNo;
                $model->reg_datetime = date('Y-m-d H:i:s');
                $model->register_aid = $_SESSION['backend']['adminInfo']['id'];
                //查询该车当前是否被出租或试用，若是，则获取该企业/个人客户等信息
                $res = Car::checkCarIsLetingOrIntrial($model->car_id);
                if($res['status']){
                    $model->contract_id = $res['contract_id'];
                    $model->protocol_id = $res['protocol_id'];
                    $model->cCustomer_id = $res['cCustomer_id'];
                    $model->pCustomer_id = $res['pCustomer_id'];
                }
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '故障信息登记成功！';
                    //当登记的故障状态除了为'已完结'之外时，都得去同步更改车辆状态。
                    switch ($model->fault_status) {
                        case 'RECEIVED':   //已受理
                        case 'SENT':       //已送修(车辆故障)
//                             Car::updateAll(['car_status'=>'FAULT'],['id'=>$model->car_id]);
//                             //车辆状态变更记录
//                             yii::$app->db->createCommand()->insert('cs_car_status_change_log', [
//                             		'car_id' => $model->car_id,
//                             		'add_time' => date('Y-m-d H:i:s'),
//                             		'car_status' => 'FAULT',
//                             		'note' => '此车发生了故障：'.yii::$app->request->post('f_desc')
//                             		])->execute();
                            break;
                        case 'REPAIRING':  //维修中(车辆维修中)
//                             Car::updateAll(['car_status'=>'REPAIRING'],['id'=>$model->car_id]);
//                             //车辆状态变更记录
//                             yii::$app->db->createCommand()->insert('cs_car_status_change_log', [
//                             		'car_id' => $model->car_id,
//                             		'add_time' => date('Y-m-d H:i:s'),
//                             		'car_status' => 'REPAIRING',
//                             		'note' => '此车发生了故障：'.yii::$app->request->post('f_desc')
//                             		])->execute();
                            break;
                    }
                    // 添加日志
                    $logStr = "新增车辆故障【" . ($model->number) . "】！";
                    UserLog::log($logStr, 'sys');
                    //注意：因为登记故障时就指定了初次受理人，所以这里立马也应增加一条初始维修进度！
                    $ProgressModel = new CarFaultDisposeProgress();
                    $ProgressModel->fault_id = $model->id;
                    $ProgressModel->disposer = $model->ap_name;
                    $ProgressModel->dispose_date = date('Y-m-d');
                    $ProgressModel->fault_status = $model->fault_status;
                    $ProgressModel->progress_desc = '初次受理并登记故障信息';
                    $ProgressModel->create_time = date('Y-m-d H:i:s');
                    $ProgressModel->creator_id = $_SESSION['backend']['adminInfo']['id'];
                    if($ProgressModel->save(true)){
                        // 添加日志
                        $logStr = "为故障【" . ($model->number) . "】新增了维修进度！";
                        UserLog::log($logStr, 'sys');
                    }
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '故障信息登记失败';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $returnArr['info'] = join('',array_column($error,0));
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr);
        }else{
            $config = (new ConfigCategory())->getCategoryConfig(['fault_status']);
            return $this->render('register',[
                'config'=>$config
            ]);
        }
    }

    /**
     * 故障修改
     */
    public function actionEdit(){
        if(yii::$app->request->isPost){
            $id = intval(yii::$app->request->post('id')) or die('param id is required');
            $model = CarFault::findOne(['id'=>$id]);
            $formData = yii::$app->request->post();
            //print_r($formData);exit;
            //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
            $checkArr = Car::checkOperatingCompanyIsMatch($model->car_id);
            if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
                return json_encode(['status'=>false,'info'=>$checkArr['info']]);
            }
            $model->load($formData,'');
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '故障信息修改成功！';
                    //处理车辆状态
//                     Car::changeCarStatusNew($model->car_id, '', 'car/fault/edit', '修改车辆故障');
                    // 添加日志
                    $logStr = "修改车辆故障【" . ($model->number) . "】";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '故障信息修改失败！';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $arr = array_column($error,0);
                    $errorStr = join('',$arr);
                    $returnArr['info'] = $errorStr;
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr);
        }else{
            $id = intval(yii::$app->request->get('id')) or die('param id is required');
            $config = (new ConfigCategory())->getCategoryConfig(['fault_status']);
            $faultInfo = CarFault::find()->where(['id'=>$id])->asArray()->one() or die('读取数据失败！');
            return $this->render('edit',[
                'config'=>$config,
                'faultInfo'=>$faultInfo
            ]);
        }
    }

    /**
     * 故障删除
     */
    public function actionRemove()
    {
        $id = intval(yii::$app->request->get('id')) or die("Not pass 'id'.");
        $returnArr = [];
        $model = CarFault::findOne($id);
        //检查当前登录用户和要操作的车辆的所属运营公司是否匹配-20160325
        $checkArr = Car::checkOperatingCompanyIsMatch($model->car_id);
        if(is_array($checkArr) && isset($checkArr['status']) && !$checkArr['status']){
            return json_encode(['status'=>false,'info'=>$checkArr['info']]);
        }
        $model->is_del = 1;
        if($model->save()){
            $returnArr['status'] = true;
            $returnArr['info'] = '故障删除成功！';
            //处理车辆状态
//             Car::changeCarStatus($model->car_id);
            // 添加日志
            $logStr = "删除车辆故障【" . ($model->number) . "】";
            UserLog::log($logStr, 'sys');
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '故障删除失败！';
        }
        echo json_encode($returnArr);
    }


    /**
     * 故障处理
     */
    public function actionDispose()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = intval(yii::$app->request->post('id')) or die('param id is required');
            $model = CarFault::findOne(['id'=>$id]);
            $model or die('record not found!');
            $model->setScenario('dispose');
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    //处理车辆状态
//                     Car::changeCarStatus($model->car_id);
                    $returnArr['status'] = true;
                    $returnArr['info'] = '故障信息处理成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '故障信息处理失败！';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $errorStr = '';
                    foreach($error as $val){
                        $errorStr .= $val[0].'&';
                    }
                    $returnArr['info'] = rtrim($errorStr,'&');
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr);
        }
        //data submit end
        $id = intval($_GET['id']) or die('param id is required');
        $fault = CarFault::find()
            ->select([
                '{{%car_fault}}.*',
                '{{%car}}.`plate_number`',
                '{{%car}}.`vehicle_dentification_number`',
                '{{%admin}}.`username`'
            ])
            ->joinWith('car',false,'LEFT JOIN')
            ->joinWith('admin',false,'LEFT JOIN')
            ->where(['{{%car_fault}}.`id`'=>$id])
            ->asArray()->one();
        $config = (new ConfigCategory())->getCategoryConfig(['fault_status'],'value');
        return $this->render('dispose',[
            'fault'=>$fault,
            'config'=>$config
        ]);
    }


    /**
     * 查看故障详细
     */
    public function actionDetail()
    {
        $id = intval(yii::$app->request->get('id')) or die('param id is required');
        $fault = CarFault::find()
            ->select([
                '{{%car_fault}}.*',
                '{{%car}}.`plate_number`',
                '{{%car}}.`vehicle_dentification_number`',
                '{{%admin}}.`username`',
            ])
            ->joinWith('car',false,'LEFT JOIN')
            ->joinWith('admin',false,'LEFT JOIN')
            ->where(['{{%car_fault}}.`id`'=>$id])
            ->asArray()->one();
        $config = (new ConfigCategory())->getCategoryConfig(['fault_status'],'value');
        $fault['fault_status'] = $config['fault_status'][$fault['fault_status']]['text'];
        return $this->render('detail',[
            'config'=>$config,
            'fault'=>$fault,
        ]);
    }


    /**
     * 导出车辆故障
     */
    public function actionExportWithCondition()
    {
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'car_fault',
            'subject'=>'car_fault',
            'description'=>'car_fault',
            'keywords'=>'car_fault',
            'category'=>'car_fault'
        ]);
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'故障车辆','font-weight'=>true,'width'=>'10'],
                ['content'=>'车架号','font-weight'=>true,'width'=>'20'],
                ['content'=>'客户名称','font-weight'=>true,'width'=>'25'],
                ['content'=>'客户类型','font-weight'=>true,'width'=>'10'],
                ['content'=>'故障编号','font-weight'=>true,'width'=>'15'],
                ['content'=>'故障简述','font-weight'=>true,'width'=>'40'],
                ['content'=>'当前状态','font-weight'=>true,'width'=>'10'],
                ['content'=>'反馈时间','font-weight'=>true,'width'=>'10'],
                ['content'=>'预计完结时间','font-weight'=>true,'width'=>'15'],
                ['content'=>'倒计时','font-weight'=>true,'width'=>'10'],
                ['content'=>'车辆品牌','font-weight'=>true,'width'=>'10'],
                ['content'=>'车辆型号','font-weight'=>true,'width'=>'20'],
                ['content'=>'车辆运营公司','font-weight'=>true,'width'=>'30'],
                ['content'=>'登记时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'登记人员','font-weight'=>true,'width'=>'15']
            ]
        ];
        //---向excel添加表头---------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }

        // 查询数据，字段与导出的excel表头对应
        $activeRecord = CarFault::find()
            ->select([
                '{{%car}}.plate_number',
                '{{%car}}.vehicle_dentification_number',
                //'conNumber'=>'{{%car_let_contract}}.`number`',
                //'proNumber'=>'{{%car_trial_protocol}}.`ctp_number`',
                'cCustomer'=>'{{%customer_company}}.`company_name`',
                'pCustomer'=>'{{%customer_personal}}.`id_name`',
                '{{%car_fault}}.number',
                '{{%car_fault}}.f_desc',
                '{{%car_fault}}.fault_status',
                '{{%car_fault}}.fb_date',
                '{{%car_fault}}.expect_end_date',
                'countdown'=>'{{%car_fault}}.expect_end_date', //‘倒计时’占位
                '{{%car}}.brand_id',
                '{{%car}}.car_model',
                '{{%car}}.`operating_company_id`',
                '{{%car_fault}}.reg_datetime',
                '{{%admin}}.`username`'
            ])
            ->joinWith('car',false,'LEFT JOIN')
            ->joinWith('admin',false,'LEFT JOIN')
            //->joinWith('carLetContract',false,'LEFT JOIN') //查出租或试用信息
            //->joinWith('carTrialProtocol',false,'LEFT JOIN')
            ->joinWith('customerCompany',false,'LEFT JOIN')
            ->joinWith('customerPersonal',false,'LEFT JOIN')
            ->andWhere(['{{%car_fault}}.`is_del`'=>0]
        );
        //////查询条件开始
        //注意：这里优先导出指定行记录
        if(yii::$app->request->get('idStr')){
            $idStr = yii::$app->request->get('idStr');
            $ids = explode(',', $idStr);
            $activeRecord->andFilterWhere(['{{%car_fault}}.id'=>$ids]);
        }else{
            $activeRecord->andFilterWhere([ //车牌号/车架号
                'or',
                ['like','{{%car}}.`plate_number`',yii::$app->request->get('plate_number')],
                ['like','{{%car}}.`vehicle_dentification_number`',yii::$app->request->get('plate_number')]
            ]);
            $activeRecord->andFilterWhere([ //企业/个人客户名称
                'or',
                ['like','{{%customer_company}}.`company_name`',yii::$app->request->get('customer_name')],
                ['like','{{%customer_personal}}.`id_name`',yii::$app->request->get('customer_name')]
            ]);
            if(yii::$app->request->get('customer_type')){ //企业/个人客户类型
                if(yii::$app->request->get('customer_type') == 'COMPANY'){
                    $activeRecord->andWhere("{{%car_fault}}.`cCustomer_id` > 0 ");
                }elseif(yii::$app->request->get('customer_type') == 'PERSONAL'){
                    $activeRecord->andWhere("{{%car_fault}}.`pCustomer_id` > 0 ");
                }
            }
            $activeRecord->andFilterWhere(['like','{{%car_fault}}.`number`',yii::$app->request->get('number')]);
            $activeRecord->andFilterWhere(['like','{{%car_fault}}.`f_desc`',yii::$app->request->get('f_desc')]);
            $activeRecord->andFilterWhere(['=','{{%car_fault}}.`fault_status`',yii::$app->request->get('fault_status')]);
            $activeRecord->andFilterWhere(['=','{{%car_fault}}.`ap_name`',yii::$app->request->get('ap_name')]);
            $activeRecord->andFilterWhere(['=','{{%car}}.`brand_id`',yii::$app->request->get('brand_id')]);
            $activeRecord->andFilterWhere(['like','{{%car}}.`car_model`',yii::$app->request->get('car_model')]);
            $activeRecord->andFilterWhere(['=','{{%car}}.`operating_company_id`',yii::$app->request->get('operating_company_id')]);
            $activeRecord->andFilterWhere(['>=','{{%car_fault}}.`reg_datetime`',yii::$app->request->get('regDatetime_start')]);
            if(yii::$app->request->get('regDatetime_end')){
                $activeRecord->andFilterWhere(['<=','{{%car_fault}}.`reg_datetime`',yii::$app->request->get('regDatetime_end').' 23:59:59']);
            }
        }
        //////查询条件结束
        $data = $activeRecord->orderBy('{{%car_fault}}.id DESC')->asArray()->all();
        //print_r($data);exit;
        if($data){
            $configItems = ['fault_status'];
            $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            //车辆运营公司
            $oCompany = OperatingCompany::getOperatingCompany();
            //车辆品牌
            $carBrand = CarBrand::getCarBrands();
            //---向excel添加具体数据------------
            foreach($data as $item){
                //企业/个人客户
                if($item['cCustomer'] && !$item['pCustomer']){
                    $item['pCustomer'] = '企业';
                }else if(!$item['cCustomer'] && $item['pCustomer']){
                    $item['cCustomer'] = $item['pCustomer'];
                    $item['pCustomer'] = '个人';
                }
                //倒计时
                $item['countdown'] = '';
                if($item['expect_end_date']){
                    if($item['fault_status'] != 'PROCESSED'){
                        if($item['expect_end_date'] < date('Y-m-d')){
                            $item['countdown'] = '已过期';
                        }else{
                            $sec = strtotime($item['expect_end_date']) - strtotime(date('Y-m-d'));
                            $day = floor( $sec / (3600*24) ) + 1;
                            $item['countdown'] = $day . '天';
                        }
                    }
                }
                //车辆运营公司
                if($item['operating_company_id']){
                    if(isset($oCompany[$item['operating_company_id']]) && $oCompany[$item['operating_company_id']]) {
                        $item['operating_company_id'] = $oCompany[$item['operating_company_id']]['name'];
                    }
                }
                //车辆品牌
                if($item['brand_id']){
                    if(isset($carBrand[$item['brand_id']]) && $carBrand[$item['brand_id']]) {
                        $item['brand_id'] = $carBrand[$item['brand_id']]['name'];
                    }
                }
                // 各combox配置项以txt代替val
                foreach($configItems as $conf) {
                    if(isset($item[$conf]) && $item[$conf]) {
                        $item[$conf] = $configs[$conf][$item[$conf]]['text'];
                    }
                }
                $lineData = [];
                foreach($item as $k=>$v) {
                    $lineData[] = ['content'=>$v];
                }
                $excel->addLineToExcel($lineData);
            }
            unset($data);
        }

        $objPHPExcel = $excel->getPHPExcel();
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        //header("Accept-Length:".$fileSize);
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','车辆故障列表.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }


    /**
     * 车辆故障照片上传窗口
     */
    public function actionUploadWindow(){
        $columnName = yii::$app->request->get('columnName'); //判断上传哪种图片
        $isEdit = intval(yii::$app->request->get('isEdit')); //判断是否为修改图片上传
        $view = $isEdit > 0 ? 'upload-window-edit' : 'upload-window';
        return $this->render($view,[
            'columnName'=>$columnName
        ]);
    }

//     /**
//      * 上传故障缩略图
//      */
//     public function actionUploadThumb(){
//         $columnName = yii::$app->request->post('columnName');
//         $isEdit = intval(yii::$app->request->get('isEdit'));
//         $upload = UploadedFile::getInstanceByName($columnName);
//         $fileExt = $upload->getExtension();
//         $allowExt = ['jpg','png','jpeg','gif'];
//         $returnArr = [];
//         $returnArr['status'] = false;
//         $returnArr['info'] = '';
//         $returnArr['columnName'] = $columnName;
//         if(!in_array($fileExt,$allowExt)){
//             $returnArr['info'] = '文件格式错误！';
//             $oStr = $isEdit > 0 ? 'CarFaultEdit' : 'CarFaultRegister';
//             return '<script>window.parent.'.$oStr.'.uploadComplete('.json_encode($returnArr).');</script>';
//         }
//         $fileName = uniqid().'.'.$fileExt;
//         // 处理上传图片的储存路径，这里指定在与入口文件同级的uploads目录之下。
//         $storePath = 'uploads/image/fault/';
//         if(!is_dir($storePath)){
//             mkdir($storePath, 0777, true);
//         }
//         $storePath .= $fileName;
//         if($upload->saveAs($storePath)){
//             $returnArr['status'] = true;
//             $returnArr['info'] = $fileName;
//             $returnArr['storePath'] = $storePath;
//         }else{
//             $returnArr['info'] = $upload->error;
//         }
//         $oStr = $isEdit > 0 ? 'CarFaultEdit' : 'CarFaultRegister';
//         return '<script>window.parent.'.$oStr.'.uploadComplete('.json_encode($returnArr).');</script>';
//     }
    
    /**
     * 上传故障缩略图（带压缩）
     */
    public function actionUploadThumb(){
    	$columnName = yii::$app->request->post('columnName');
    	$isEdit = intval(yii::$app->request->get('isEdit'));
    	$returnArr = [];
		$returnArr['status'] = false;
		$returnArr['info'] = '';
    	$returnArr['columnName'] = $columnName;
    	
    	$resizeimage = new Resizeimage();
    	$r = $resizeimage->resizeImage($columnName,1024,768,'uploads/image/fault/');
    	if(!$r['url']){
    		$returnArr['info'] = $r['error'];
    	}else {
    		$returnArr['status'] = true;
    		$returnArr['info'] = $r['name'];
    		$returnArr['storePath'] = $r['url'];
            $returnArr['storePath'] = explode("/", $r['url']);
            $returnArr['storePath'] = $returnArr['storePath'][3];
    	}
    	$oStr = $isEdit > 0 ? 'CarFaultEdit' : 'CarFaultRegister';
    	return '<script>window.parent.'.$oStr.'.uploadComplete('.json_encode($returnArr).');</script>';
    }
}