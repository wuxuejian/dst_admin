<?php
/**
 * 电池维护管理 控制器
 * @author chengwk
 * @time   2016-04-25 17:25
 */
namespace backend\modules\carmonitor\controllers;
use yii;
use yii\data\Pagination;
use backend\controllers\BaseController;
use backend\models\ConfigCategory;
use backend\models\CarLetRecord;
use backend\models\CarTrialProtocolDetails;
use backend\models\BatteryDetectCriteria;
use backend\models\BatteryDetection;
use backend\models\BatteryCorrectVerify;
use backend\models\BatteryCorrectNotice;
use backend\models\Car;
use backend\models\CarBrand;
use backend\classes\UserLog;
use common\models\Excel;

class BatteryMaintainController extends BaseController
{
    public function actionIndex()
    {
        $configItems = ['car_type','car_type','battery_type'];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'config'=>$config,
            'buttons'=>$buttons
        ]);
    }

    /**
     * 获取维护列表
     */
    public function actionGetList()
    {
        $query = BatteryDetection::find()
            ->select([
                '{{%battery_detection}}.*',
                '{{%car}}.plate_number',
                '{{%car}}.brand_id',
                '{{%car}}.car_type',
                '{{%battery_correct_notice}}.contact_name',
                '{{%battery_correct_notice}}.notice_time',
                '{{%battery_correct_notice}}.is_corrected',
                '{{%battery_correct_verify}}.verify_res',
            ])
            ->joinWith('car',false,'LEFT JOIN')
            ->joinWith('latestNotice',false,'LEFT JOIN') //获取“最新修正通知”
            ->joinWith('batteryCorrectVerify',false,'LEFT JOIN')
            ->where([
                'and',
                ['{{%car}}.is_del'=>0],
                [
                    'or',
                    ['{{%battery_detection}}.soc_deviation_status'=>'ABNORMAL'],
                    ['{{%battery_detection}}.capacitance_attenuation_status'=>'ABNORMAL'],
                    ['{{%battery_detection}}.capacitance_deviation_status'=>'ABNORMAL']
                ]
            ]);
        //查询条件
        $query->andFilterWhere(['like','{{%car}}.plate_number',yii::$app->request->get('plate_number')]);
        $query->andFilterWhere(['=','{{%car}}.brand_id',yii::$app->request->get('brand_id')]);
        $query->andFilterWhere(['=','{{%car}}.car_type',yii::$app->request->get('car_type')]);
        $query->andFilterWhere(['like','{{%battery_detection}}.car_vin',yii::$app->request->get('car_vin')]);
        $query->andFilterWhere(['=','{{%battery_detection}}.battery_type',yii::$app->request->get('battery_type')]);
        $query->andFilterWhere(['=','{{%battery_correct_verify}}.verify_res',yii::$app->request->get('verify_res')]);
        if(yii::$app->request->get('notice_time_start')){
            $query->andWhere(['>=','{{%battery_correct_notice}}.notice_time',yii::$app->request->get('notice_time_start')]);
        }
        if(yii::$app->request->get('notice_time_end')){
            $query->andWhere(['<=','{{%battery_correct_notice}}.notice_time',yii::$app->request->get('notice_time_end').' 23:59:59']);
        }
        if(yii::$app->request->get('contact_name')){
            switch(yii::$app->request->get('contact_name')){
                case 'YES':
                    $query->andWhere('{{%battery_correct_notice}}.contact_name IS NOT NULL'); break;
                case 'NO':
                    $query->andWhere('{{%battery_correct_notice}}.contact_name IS NULL'); break;
            }
        }
        if(yii::$app->request->get('is_corrected')){
            switch(yii::$app->request->get('is_corrected')){
                case 'YES':
                    $query->andWhere('{{%battery_correct_notice}}.is_corrected = 1'); break;
                case 'NO':
                    $query->andWhere('{{%battery_correct_notice}}.is_corrected = 0 OR {{%battery_correct_notice}}.is_corrected IS NULL'); break;
            }
        }
        $total = $query->count();
        //排序
        $sortField = yii::$app->request->get('sort','');
        $sortDirect = yii::$app->request->get('order','desc');
        if($sortField){
            switch($sortField){
                case 'plate_number':
                case 'brand_id':
                case 'car_type':
                    $orderStr = '{{%car}}.' . $sortField;
                    break;
                case 'contact_name':
                case 'notice_time':
                case 'is_corrected':
                    $orderStr = '{{%battery_correct_notice}}.' . $sortField;
                    break;
                case 'countdown':
                    $orderStr = '{{%battery_correct_notice}}.notice_time';
                    break;
                default:
                    $orderStr = '{{%battery_detection}}.' . $sortField;
            }
        }else{
            $orderStr = '{{%battery_detection}}.detect_time';
        }
        $orderStr .= ' ' . $sortDirect;
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
            ->orderBy($orderStr)->asArray()->all();
        //车辆品牌
        $carBrand = CarBrand::getCarBrands();
        foreach($data as &$dataItem){
            if(isset($carBrand[$dataItem['brand_id']]) && $carBrand[$dataItem['brand_id']]){
                $dataItem['brand_id'] = $carBrand[$dataItem['brand_id']]['name'];
            }
        }
        //倒计时
        foreach($data as &$CGetListItem){
            if($CGetListItem['notice_time']){
                $endTime = strtotime($CGetListItem['notice_time']) + 3600*24*15; // 15天观察期
                $today = strtotime(date('Y-m-d'));
                if($endTime < $today){
                    $CGetListItem['countdown'] = '已过期';
                }else{
                    $CGetListItem['countdown'] = floor( ($endTime - $today) / (3600*24) ) . '天';
                }
            }
        }
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }


    /*****************************************************************
     * “通知用户修正”
     *****************************************************************/
    public function actionNoticeCorrect(){
        $car_vin = yii::$app->request->get('car_vin','');
        if(!$car_vin){
            return '参数（car_vin）缺失！';
        }
        //查车辆信息
        $carInfo = Car::find()
            ->select(['id','plate_number','car_vin'=>'vehicle_dentification_number','car_status'])
            ->where(['vehicle_dentification_number'=>$car_vin, 'is_del'=>0])
            ->asArray()
            ->one();
        if(!$carInfo){
            return "查询不到车辆{$car_vin}！";
        }
        //查租车或试用客户信息
        $customerInfo = ['customer'=>'—', 'contact_name'=>'—', 'contact_mobile'=>'—'];
        if($carInfo['car_status']){
            switch($carInfo['car_status']){
                case 'LETING':
                    $res = CarLetRecord::find()
                        ->select([
                            'contract_id',
                            'cCustomer_name'=>'{{%customer_company}}.`company_name`',
                            '{{%customer_company}}.`contact_name`',
                            '{{%customer_company}}.`contact_mobile`',
                            'pCustomer_name'=>'{{%customer_personal}}.`id_name`',
                            '{{%customer_personal}}.`mobile`',
                        ])
                        ->joinWith('customerCompany',false,'LEFT JOIN')  //查企业客户
                        ->joinWith('customerPersonal',false,'LEFT JOIN') //查个人客户
                        ->where(['car_id'=>$carInfo['id']])
                        ->orderBy('{{%car_let_record}}.id DESC')
                        ->asArray()->one();
                case 'INTRIAL':
                    $res = CarTrialProtocolDetails::find()
                        ->select([
                            'ctpd_protocol_id',
                            'cCustomer_name'=>'{{%customer_company}}.`company_name`',
                            '{{%customer_company}}.`contact_name`',
                            '{{%customer_company}}.`contact_mobile`',
                            'pCustomer_name'=>'{{%customer_personal}}.`id_name`',
                            '{{%customer_personal}}.`mobile`',
                        ])
                        ->joinWith('customerCompany',false,'LEFT JOIN')  //查企业客户
                        ->joinWith('customerPersonal',false,'LEFT JOIN') //查个人客户
                        ->where(['ctpd_car_id'=>$carInfo['id']])
                        ->orderBy('ctpd_id DESC')
                        ->asArray()->one();
            }
            if(isset($res) && $res){
                if($res['cCustomer_name']){
                    $customerInfo = [
                        'customer'=>$res['cCustomer_name'],
                        'contact_name'=>$res['contact_name'],
                        'contact_mobile'=>$res['contact_mobile']
                    ];
                }else{
                    $customerInfo = [
                        'customer'=>$res['pCustomer_name'],
                        'contact_name'=>$res['pCustomer_name'],
                        'contact_mobile'=>$res['mobile']
                    ];
                }
            }
            $configItems = ['car_status'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            $carInfo['car_status'] = $config['car_status'][$carInfo['car_status']]['text'];
        }
        $carInfo = array_merge($carInfo, $customerInfo);
        $buttons = $this->getCurrentActionBtn();
        return $this->render('noticeCorrectWin',[
            'buttons'=>$buttons,
            'carInfo'=>$carInfo
        ]);
    }

    /**
     * 获取修正通知列表
     */
    public function actionGetNoticeList()
    {
        $car_vin = yii::$app->request->get('car_vin','');
        if(!$car_vin){
            return json_encode(['rows'=>[],'total'=>0]);
        }
        $query = BatteryCorrectNotice::find()
            ->select([
                '{{%battery_correct_notice}}.*',
                'creator'=> '{{%admin}}.username'
            ])
            ->joinWith('admin',false,'LEFT JOIN')
            ->where([
                '{{%battery_correct_notice}}.car_vin'=>$car_vin,
                '{{%battery_correct_notice}}.is_del'=>0
            ]);
        $total = $query->count();
        //排序
        $sortField = yii::$app->request->get('sort','');
        $sortDirect = yii::$app->request->get('order','desc');
        if($sortField){
            switch($sortField){
                case 'creator':
                    $orderStr = '{{%admin}}.username';
                    break;
                default:
                    $orderStr = '{{%battery_correct_notice}}.' . $sortField;
            }
            $orderStr .= ' ' . $sortDirect;
        }else{ //默认按通知时间和更新时间降序
            $orderStr = "{{%battery_correct_notice}}.notice_time {$sortDirect},{{%battery_correct_notice}}.modify_time {$sortDirect}";
        }
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query
            ->offset($pages->offset)->limit($pages->limit)
            ->orderBy($orderStr)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }

    /**
     * 保存修正通知
     */
    public function actionSaveNotice()
    {
        $postData = yii::$app->request->post();
        $info = '';
        if(isset($postData['insertRows']) && $postData['insertRows']){
            $insertNum = 0;
            foreach($postData['insertRows'] as $row){
                $model = new BatteryCorrectNotice();
                unset($row['id']);
                $model->load($row, '');
                $model->car_vin = $postData['car_vin'];
                $model->modify_time = date('Y-m-d H:i:s');
                $model->modify_aid = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(true)){
                    $insertNum++;
                }
            }
            $info .= "新增了{$insertNum}条修正通知！<br>";
        }
        if(isset($postData['updateRows']) && $postData['updateRows']){
            $updateNum = 0;
            foreach($postData['updateRows'] as $row){
                $model = BatteryCorrectNotice::findOne($row['id']);
                $model->load($row, '');
                $model->modify_time = date('Y-m-d H:i:s');
                $model->modify_aid = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(true)){
                    $updateNum++;
                }
            }
            $info .= "修改了{$updateNum}条修正通知！<br>";
        }
        if(isset($postData['deleteRows']) && $postData['deleteRows']){
            $deleteNum = 0;
            $ids = array_column($postData['deleteRows'],'id');
            if(BatteryCorrectNotice::updateAll(['is_del'=>1],['id'=>$ids])){
                $deleteNum = count($ids);
            }
            $info .= "删除了{$deleteNum}条修正通知！<br>";
        }
        //记录日志
        UserLog::log("电池维护管理-为车辆【{$postData['car_vin']}】".$info,'sys');

        //判断更新检测记录的最新修正通知id。
        //查该车最新修正通知（按通知时间和更新时间降序）与该车检测记录进行时间先后的比较：
        //若该车检测记录的检测时间<=通知时间则更新最新修正通知id为该通知id，否则该通知是以前检测记录的通知并更新成0。
        $latestNotice = BatteryCorrectNotice::find()
            ->select(['id','notice_time','modify_time'])
            ->where(['car_vin'=>$postData['car_vin'],'is_del'=>0])
            ->orderBy('notice_time DESC,modify_time DESC')
            ->asArray()->one();
        $detection = BatteryDetection::find()
            ->select(['id','detect_time'])
            ->where(['car_vin'=>$postData['car_vin']])
            ->asArray()->one();
        if(substr($detection['detect_time'],0,10) <= $latestNotice['notice_time'] && $detection['detect_time'] <= $latestNotice['modify_time']){
            BatteryDetection::updateAll(['latest_notice_id'=>$latestNotice['id']],['car_vin'=>$postData['car_vin']]);
        }else{
            BatteryDetection::updateAll(['latest_notice_id'=>0],['car_vin'=>$postData['car_vin']]);
        }
        return json_encode(['status'=>true,'info'=>$info]);
    }



    /*****************************************************************
     * “验证修正结果”
     *****************************************************************/
    public function actionVerifyCorrect(){
        $car_vin = yii::$app->request->get('car_vin','');
        $isDetectNew = yii::$app->request->get('isDetectNew',0);
        if(!$isDetectNew){
            return $this->render('verifyCorrectWin',['car_vin'=>$car_vin]);
        }
        //进行最新的电池衰减检测
        $car = Car::find()
            ->select([
                'car_vin'=>'vehicle_dentification_number',
                '{{%battery}}.battery_type',
                '{{%battery}}.battery_model'
            ])
            ->joinWith('battery',false)
            ->where(['{{%car}}.is_del'=>0,'vehicle_dentification_number'=>$car_vin])
            ->asArray()->one();
        if(!$car || !$car['battery_type']){
            return json_encode(['status'=>false,'info'=>'该车不存在或该车电池型号不存在！']);
        }
        set_time_limit(0);
        //--获取检测标准--
        $criterion = BatteryDetectCriteria::find()
            ->where(['battery_type'=>$car['battery_type'],'is_del'=>0])
            ->asArray()->one();
        //--执行电池衰减检测--
        $res = BatteryDetection::executeSocDetect($criterion,$car);
        if(!$res['status']){
            return json_encode(['status'=>false,'info'=>$res['info']]);
        }
        //仅当检测成功时才向验证修正结果表中插入或更新纪录
        $model = BatteryCorrectVerify::findOne(['car_vin'=>$car['car_vin']]);
        if(!$model){
            $model = new BatteryCorrectVerify();
        }
        $model->car_vin = $car['car_vin'];
        $model->battery_type = $car['battery_type'];
        foreach($res['detectData'] as $key=>$item){
            switch($key){
                case 'detect1':
                    $model->soc_deviation_status = $item['resStatus'];
                    $model->soc_deviation_res = $item['resInfo'];
                    $model->soc_deviation_val = $item['socDiff'];
                    break;
                case 'detect2':
                    $model->capacitance_attenuation_status = $item['resStatus'];
                    $model->capacitance_attenuation_res = $item['resInfo'];
                    $model->voltage_deviation_val = $item['volDiff'];
                    break;
                case 'detect3':
                    $model->capacitance_deviation_status = $item['resStatus'];
                    $model->capacitance_deviation_res = $item['resInfo'];
                    break;
            }
        }
        if($model->soc_deviation_status == 'NORMAL' && $model->capacitance_attenuation_status == 'NORMAL' && $model->capacitance_deviation_status == 'NORMAL'){
            $model->verify_res = 'NORMAL'; //仅当3种算法都“正常”验证修正结果才显示“正常”
        }else{
            $model->verify_res = 'ABNORMAL';
        }
        $model->verify_time = date('Y-m-d H:i:s');
        $model->verify_aid = $_SESSION['backend']['adminInfo']['id'];
        $model->process_status = 'UNPROCESSED'; //标记为“未处理”
        if(!$model->save(true)){
            return json_encode(['status'=>false,'info'=>'保存验证修正结果失败！']);
        }else{
            return json_encode(['status'=>true,'verifyInfo'=>$model->getAttributes()]);
        }
    }


    /**
     * 导出维护列表
     */
    public function actionExportGridData(){
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'battery_detection',
            'subject'=>'battery_detection',
            'description'=>'battery_detection',
            'keywords'=>'battery_detection',
            'category'=>'battery_detection'
        ]);
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'车牌号','font-weight'=>true,'width'=>'10'],
                ['content'=>'车架号','font-weight'=>true,'width'=>'25'],
                ['content'=>'车辆品牌','font-weight'=>true,'width'=>'15'],
                ['content'=>'车辆类型','font-weight'=>true,'width'=>'15'],
                ['content'=>'电池类型','font-weight'=>true,'width'=>'15'],
                ['content'=>'SOC偏移','font-weight'=>true,'width'=>'10'],
                ['content'=>'电池容量衰减','font-weight'=>true,'width'=>'15'],
                ['content'=>'电池容量偏差','font-weight'=>true,'width'=>'15'],
                ['content'=>'检测时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'通知用户','font-weight'=>true,'width'=>'10'],
                ['content'=>'通知时间','font-weight'=>true,'width'=>'10'],
                ['content'=>'倒计时','font-weight'=>true,'width'=>'10'],
                ['content'=>'执行慢充修正','font-weight'=>true,'width'=>'15'],
                ['content'=>'验证修正结果','font-weight'=>true,'width'=>'15'],
            ]
        ];
        //---向excel添加表头--------------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }

        //查询数据，查询字段与导出的excel表头对应
        $query = BatteryDetection::find()
            ->select([
                '{{%car}}.plate_number',
                '{{%battery_detection}}.car_vin',
                '{{%car}}.brand_id',
                '{{%car}}.car_type',
                '{{%battery_detection}}.battery_type',
                '{{%battery_detection}}.soc_deviation_status',
                '{{%battery_detection}}.capacitance_attenuation_status',
                '{{%battery_detection}}.capacitance_deviation_status',
                '{{%battery_detection}}.detect_time',
                '{{%battery_correct_notice}}.contact_name',
                '{{%battery_correct_notice}}.notice_time',
                'countdown'=>'{{%battery_correct_notice}}.notice_time',
                '{{%battery_correct_notice}}.is_corrected',
                '{{%battery_correct_verify}}.verify_res',
            ])
            ->joinWith('car',false,'LEFT JOIN')
            ->joinWith('latestNotice',false,'LEFT JOIN') //获取“最新修正通知”
            ->joinWith('batteryCorrectVerify',false,'LEFT JOIN')
            ->where([
                'and',
                ['{{%car}}.is_del'=>0],
                [
                    'or',
                    ['{{%battery_detection}}.soc_deviation_status'=>'ABNORMAL'],
                    ['{{%battery_detection}}.capacitance_attenuation_status'=>'ABNORMAL'],
                    ['{{%battery_detection}}.capacitance_deviation_status'=>'ABNORMAL']
                ]
            ]);
        //查询条件
        $query->andFilterWhere(['like','{{%car}}.plate_number',yii::$app->request->get('plate_number')]);
        $query->andFilterWhere(['=','{{%car}}.brand_id',yii::$app->request->get('brand_id')]);
        $query->andFilterWhere(['=','{{%car}}.car_type',yii::$app->request->get('car_type')]);
        $query->andFilterWhere(['like','{{%battery_detection}}.car_vin',yii::$app->request->get('car_vin')]);
        $query->andFilterWhere(['=','{{%battery_detection}}.battery_type',yii::$app->request->get('battery_type')]);
        $query->andFilterWhere(['=','{{%battery_correct_verify}}.verify_res',yii::$app->request->get('verify_res')]);
        if(yii::$app->request->get('notice_time_start')){
            $query->andWhere(['>=','{{%battery_correct_notice}}.notice_time',yii::$app->request->get('notice_time_start')]);
        }
        if(yii::$app->request->get('notice_time_end')){
            $query->andWhere(['<=','{{%battery_correct_notice}}.notice_time',yii::$app->request->get('notice_time_end').' 23:59:59']);
        }
        if(yii::$app->request->get('contact_name')){
            switch(yii::$app->request->get('contact_name')){
                case 'YES':
                    $query->andWhere('{{%battery_correct_notice}}.contact_name IS NOT NULL'); break;
                case 'NO':
                    $query->andWhere('{{%battery_correct_notice}}.contact_name IS NULL'); break;
            }
        }
        if(yii::$app->request->get('is_corrected')){
            switch(yii::$app->request->get('is_corrected')){
                case 'YES':
                    $query->andWhere('{{%battery_correct_notice}}.is_corrected = 1'); break;
                case 'NO':
                    $query->andWhere('{{%battery_correct_notice}}.is_corrected = 0 OR {{%battery_correct_notice}}.is_corrected IS NULL'); break;
            }
        }
        $data = $query->orderBy('{{%battery_detection}}.detect_time DESC')->asArray()->all();
        //print_r($data);exit;
        if($data){
            $configItems = ['car_type','battery_type'];
            $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            //车辆品牌
            $carBrand = CarBrand::getCarBrands();
            //---向excel添加具体数据---------------------------
            foreach($data as $item){
                $lineData = [];
                $statusArr = ['NORMAL'=>'正常','ABNORMAL'=>'异常','INVALID'=>'无效'];
                if($item['soc_deviation_status']){
                    $item['soc_deviation_status'] = $statusArr[$item['soc_deviation_status']];
                }
                if($item['capacitance_attenuation_status']){
                    $item['capacitance_attenuation_status'] = $statusArr[$item['capacitance_attenuation_status']];
                }
                if($item['capacitance_deviation_status']){
                    $item['capacitance_deviation_status'] = $statusArr[$item['capacitance_deviation_status']];
                }
                if($item['verify_res']){
                    $item['verify_res'] = $statusArr[$item['verify_res']];
                }
                if($item['contact_name']){
                    $item['contact_name'] = '是';
                }else{
                    $item['contact_name'] = '否';
                }
                if($item['is_corrected'] === 0){
                    $item['is_corrected'] = '是';
                }else{
                    $item['is_corrected'] = '否';
                }
                if($item['notice_time']){
                    $endTime = strtotime($item['notice_time']) + 3600*24*15; // 15天观察期
                    $today = strtotime(date('Y-m-d'));
                    if($endTime < $today){
                        $item['countdown'] = '已过期';
                    }else{
                        $item['countdown'] = floor( ($endTime - $today) / (3600*24) ) . '天';
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
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','电池维护管理列表导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }



}