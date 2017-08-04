<?php
namespace backend\modules\carmonitor\controllers;
use backend\controllers\BaseController;
use backend\models\ConfigCategory;
use backend\models\CarAnomalyDetection;
use backend\models\Car;
use backend\models\CarLetRecord;
use backend\models\CarTrialProtocolDetails;
use backend\models\CustomerCompany;
use backend\models\CustomerPersonal;
use backend\models\CarAnomalyDetectionDeal;
use common\models\Excel;
use yii;
use yii\data\Pagination;
class ExceptionDealController extends BaseController
{
    public function actionList()
    {
        $buttons = $this->getCurrentActionBtn();
        $configItems = ['battery_type'];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        return $this->render('list',[
            'buttons'=>$buttons,
            'config'=>$config,
            'alertType'=>CarAnomalyDetection::$alertType,
        ]);
    }

    public function actionGetListData()
    {
        $returnArr = [
            'rows'=>[],
            'total'=>0,
        ];
        $query = CarAnomalyDetection::find()
            ->select([
                '{{%car_anomaly_detection}}.*',
                '{{%car}}.`plate_number`',
            ])->joinWith('car',false)
            ->andWhere(['{{%car}}.is_del'=>0]);
        $query->andFilterWhere(['like','{{%car}}.plate_number',yii::$app->request->get('plate_number')]);
        $query->andFilterWhere(['like','{{%car_anomaly_detection}}.car_vin',yii::$app->request->get('car_vin')]);
        $query->andFilterWhere(['{{%car_anomaly_detection}}.battery_type'=>yii::$app->request->get('battery_type')]);
        $query->andFilterWhere(['>=','{{%car_anomaly_detection}}.alert_datetime',yii::$app->request->get('alert_datetime_start')]);
        $query->andFilterWhere(['<=','{{%car_anomaly_detection}}.alert_datetime',yii::$app->request->get('alert_datetime_end')]);
        $query->andFilterWhere(['>=','{{%car_anomaly_detection}}.alert_level',yii::$app->request->get('alert_level_start')]);
        $query->andFilterWhere(['<=','{{%car_anomaly_detection}}.alert_level',yii::$app->request->get('alert_level_end')]);
        $query->andFilterWhere(['{{%car_anomaly_detection}}.status'=>yii::$app->request->get('status')]);
        $query->andFilterWhere(['>=','{{%car_anomaly_detection}}.deal_datetime',yii::$app->request->get('deal_datetime_start')]);
        $query->andFilterWhere(['<=','{{%car_anomaly_detection}}.deal_datetime',yii::$app->request->get('deal_datetime_end')]);
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'plate_number':
                    $orderBy = '{{%car}}.`'.$sortColumn.'` ';
                    break;
                default:
                    $orderBy = '{{%car_anomaly_detection}}.`'.$sortColumn.'` ';
                    break;
            }
            
        }else{
           $orderBy = '{{%car_anomaly_detection}}.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)
            ->orderBy($orderBy)->asArray()->all();
        if($data){
            $caddRecordIds = CarAnomalyDetectionDeal::find()
                ->select([
                    'id'=>'max(id)',
                ])->where([
                    '`is_del`'=>0,
                    '`cad_id`'=>array_column($data,'id'),
                ])->groupBy('cad_id')->asArray()->all();
            //var_dump($caddRecordIds);
            $caddRecord = CarAnomalyDetectionDeal::find()
                ->select([
                    '{{%car_anomaly_detection_deal}}.`cad_id`',
                    '{{%car_anomaly_detection_deal}}.`status`',
                    '{{%car_anomaly_detection_deal}}.`deal_way`',
                    '{{%admin}}.`username`'
                ])->joinWith('admin',false)
                ->where(['{{%car_anomaly_detection_deal}}.`id`'=>array_column($caddRecordIds,'id')])
                ->indexBy('cad_id')->asArray()->all();
            foreach($data as $key=>$val){
                if(isset($caddRecord[$val['id']])){
                    $data[$key] = array_merge($data[$key],$caddRecord[$val['id']]);
                }
            }
        }
        $returnArr['rows'] = $data; 
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }

    /**
     * 导出车辆报警记录
     * carmonitor/exception-deal/export-with-condition
     */
    public function actionExportWithCondition()
    {
        $query = CarAnomalyDetection::find()
            ->select([
                '{{%car_anomaly_detection}}.*',
                '{{%car}}.`plate_number`',
            ])->joinWith('car',false)
            ->andWhere(['{{%car}}.is_del'=>0]);
        $query->andFilterWhere(['like','{{%car}}.plate_number',yii::$app->request->get('plate_number')]);
        $query->andFilterWhere(['like','{{%car_anomaly_detection}}.car_vin',yii::$app->request->get('car_vin')]);
        $query->andFilterWhere(['{{%car_anomaly_detection}}.battery_type'=>yii::$app->request->get('battery_type')]);
        $query->andFilterWhere(['>=','{{%car_anomaly_detection}}.alert_datetime',yii::$app->request->get('alert_datetime_start')]);
        $query->andFilterWhere(['<=','{{%car_anomaly_detection}}.alert_datetime',yii::$app->request->get('alert_datetime_end')]);
        $query->andFilterWhere(['>=','{{%car_anomaly_detection}}.alert_level',yii::$app->request->get('alert_level_start')]);
        $query->andFilterWhere(['<=','{{%car_anomaly_detection}}.alert_level',yii::$app->request->get('alert_level_end')]);
        $query->andFilterWhere(['{{%car_anomaly_detection}}.status'=>yii::$app->request->get('status')]);
        $query->andFilterWhere(['>=','{{%car_anomaly_detection}}.deal_datetime',yii::$app->request->get('deal_datetime_start')]);
        $query->andFilterWhere(['<=','{{%car_anomaly_detection}}.deal_datetime',yii::$app->request->get('deal_datetime_end')]);
        //排序开始
        /*$sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'plate_number':
                    $orderBy = '{{%car}}.`'.$sortColumn.'` ';
                    break;
                default:
                    $orderBy = '{{%car_anomaly_detection}}.`'.$sortColumn.'` ';
                    break;
            }
            
        }else{
           $orderBy = '{{%car_anomaly_detection}}.`id` ';
        }
        $orderBy .= $sortType;*/
        //排序结束
        $total = $query->count();
        if($total >= 10000){
            return '<script>alert("数据过大，请分段导出！");</script>';
        }
        /*$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);*/
        $data = $query/*->offset($pages->offset)->limit($pages->limit)
            ->orderBy($orderBy)*/->asArray()->all();
        if($data){
            $caddRecordIds = CarAnomalyDetectionDeal::find()
                ->select([
                    'id'=>'max(id)',
                ])->where([
                    '`is_del`'=>0,
                    '`cad_id`'=>array_column($data,'id'),
                ])->groupBy('cad_id')->asArray()->all();
            //var_dump($caddRecordIds);
            $caddRecord = CarAnomalyDetectionDeal::find()
                ->select([
                    '{{%car_anomaly_detection_deal}}.`cad_id`',
                    '{{%car_anomaly_detection_deal}}.`status`',
                    '{{%car_anomaly_detection_deal}}.`deal_way`',
                    '{{%admin.username}}'
                ])->joinWith('admin',false)
                ->where(['{{%car_anomaly_detection_deal}}.`id`'=>array_column($caddRecordIds,'id')])
                ->indexBy('cad_id')->asArray()->all();
            //excel column
            $excelColumn = [
                'plate_number'=>'车牌号',
                'car_vin'=>'车架号',
                'battery_type'=>'电池型号',
                'alert_type'=>'报警项目',
                'alert_level'=>'报警级别',
                'alert_dispose'=>'报警处理方式',
                'alert_content'=>'报警内容',
                'alert_value'=>'告警值',
                'alert_datetime'=>'报警时间',
                'times'=>'报警次数',
                'status'=>'报警处理状态',
                'deal_date'=>'处理时间',
                'deal_way'=>'处理方法',
                'username'=>'操作人员'
            ];
            $excelObj = new Excel();
            $excelObj->setHeader([
                'creator'=>'皓峰通讯',
                'lastModifiedBy'=>'hao feng tong xun'
            ]);
            $lineData = [];
            foreach($excelColumn as $val){
                $lineData[] = ['content'=>$val,'width'=>15];
            }
            $excelObj->addLineToExcel($lineData);
            //获取配置项
            $configItems = ['battery_type'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            foreach($data as $key=>$val){
                if(isset($caddRecord[$val['id']])){
                    $val = array_merge($data[$key],$caddRecord[$val['id']]);
                    unset($val['cad_id']);
                }
                $val['battery_type'] = $config['battery_type'][$val['battery_type']]['text'];
                $val['alert_type'] = CarAnomalyDetection::$alertType[$val['alert_type']];
                switch($val['alert_dispose']){
                    case 0:
                        $val['alert_dispose'] = '不报警';
                        break;
                    case 1:
                        $val['alert_dispose'] = '后台报警';
                        break;
                    case 2:
                        $val['alert_dispose'] = '后台报警，短信报警';
                        break;
                }
                switch($val['status']){
                    case 'new':
                        $val['status'] = '未处理';
                        break;
                    case 'no_need':
                        $val['status'] = '无需处理';
                        break;
                    case 'acceptance':
                        $val['status'] = '已受理';
                        break;
                    case 'processing':
                        $val['status'] = '处理中';
                        break;
                    case 'end':
                        $val['status'] = '已完结';
                        break;
                }
                $lineData = [];
                foreach($excelColumn as $k=>$v){
                    if(isset($val[$k])){
                        $lineData[] = ['content'=>$val[$k]];
                    }else{
                        $lineData[] = ['content'=>''];
                    }
                }
                $excelObj->addLineToExcel($lineData);
            }
            //下载
            $objPHPExcel = $excelObj->getPHPExcel();
            header("Content-type: application/octet-stream"); 
            header("Accept-Ranges: bytes"); 
            //header("Accept-Length:".$fileSize); 
            header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','车辆异常报警列表').'.xls'); 
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        }else{
            return '<script>alert("无数据！");</script>';
        }
    }

    /**
     * 报警处理
     * carmonitor/exception-deal/alert-deal
     */
    public function actionAlertDeal()
    {   
        //post请求开始
        if(yii::$app->request->isPost){
            $returnArr = [
                'error'=>0,
                'msg'=>'操作成功！',
            ];
            $cadId = yii::$app->request->get('cad_id');
            if(empty($cadId)){
                $returnArr['error'] = 1;
                $returnArr['msg'] = '参数错误！';
                return json_encode($returnArr);
            }
            //删除处理项目
            if(empty(yii::$app->request->post())){
                CarAnomalyDetectionDeal::updateAll([
                    'is_del'=>1
                ],[
                    'cad_id'=>$cadId
                ]);   
            }else{
                $saveId = array_unique(yii::$app->request->post('id'));
                //var_dump($saveId);
                CarAnomalyDetectionDeal::updateAll([
                    'is_del'=>1
                ],[
                    'and',
                    ['=','cad_id',$cadId],
                    ['not in','id',$saveId]
                ]);
            }
            //修改或更新
            if(empty(yii::$app->request->post())){
                return json_encode($returnArr);
            }
            foreach(yii::$app->request->post('id') as $key=>$val){
                switch (yii::$app->request->post('status')[$key]) {
                    case '已受理':
                        $status = 'acceptance';
                        break;
                    case '处理中':
                        $status = 'processing';
                        break;
                    default:
                        $status = 'end';
                        break;
                }
                $dealDate = yii::$app->request->post('deal_date')[$key];
                if(empty($dealDate)){
                    continue;
                }
                if($val){
                    $model = CarAnomalyDetectionDeal::findOne(['id'=>$val]);
                    if(!$model){
                        continue;
                    }
                    $model->status = $status;
                    $model->deal_way = yii::$app->request->post('deal_way')[$key];
                    $model->deal_date = $dealDate;
                }else{
                    $model = new CarAnomalyDetectionDeal;
                    $model->cad_id = $cadId;
                    $model->status = $status;
                    $model->deal_way = yii::$app->request->post('deal_way')[$key];
                    $model->deal_date = $dealDate;
                    $model->reg_aid = $_SESSION['backend']['adminInfo']['id'];
                }
                $model->save();
                
            }
            //更新异常报警状态为最新记录状态
            $lastRecord = CarAnomalyDetectionDeal::find()
                ->select(['status','deal_date'])
                ->where(['cad_id'=>$cadId,'is_del'=>0])
                ->orderBy('id desc')->asArray()->one();
            if($lastRecord){
                CarAnomalyDetection::updateAll([
                    'status'=>$lastRecord['status'],
                    'deal_date'=>$lastRecord['deal_date']
                ],['id'=>$cadId]);
            }else{
                CarAnomalyDetection::updateAll([
                    'status'=>'new',
                    'deal_date'=>''
                ],['id'=>$cadId]);
            }
            return json_encode($returnArr);
        }
        //post请求结束
        $carVin = yii::$app->request->get('car_vin');
        $cadId = yii::$app->request->get('id');
        if(empty($carVin) || empty($cadId)){
            return '参数错误！';
        }
        //查询车辆信息
        $carInfo = Car::find()
            ->select(['id','plate_number','car_status'])
            ->where([
                'is_del'=>0,
                'vehicle_dentification_number'=>$carVin
            ])->asArray()->one();
        if(!$carInfo){
            return '车辆不存在！';
        }
        $carInfo['customer_name'] = '';
        $carInfo['director_name'] = '';
        $carInfo['director_mobile'] = '';
        $carInfo['car_vin'] = $carVin;
        $configItems = ['car_status'];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        if(isset($config['car_status'][$carInfo['car_status']])){
            $carInfo['car_status_text'] = $config['car_status'][$carInfo['car_status']]['text'];
        }else{
            $carInfo['car_status_text'] = '';
        }
        switch ($carInfo['car_status']){
            case 'LETING':
            case 'INTRIAL':
                $cCustomerId = 0;//个人客户id
                $pCustomerId = 0;//企业客户id
                if($carInfo['car_status'] == 'LETING'){
                    //出租中
                    $letRecord = CarLetRecord::find()
                        ->select(['cCustomer_id','pCustomer_id'])
                        ->where(['car_id'=>$carInfo['id']])
                        ->orderBy('id desc')
                        ->asArray()->one();
                    if(!$letRecord){
                        break;
                    }
                    $cCustomerId = $letRecord['cCustomer_id'];
                    $pCustomerId = $letRecord['pCustomer_id'];
                }else{
                    //试用中
                    $trialRecord  = CarTrialProtocolDetails::find()
                        ->select(['ctpd_cCustomer_id','ctpd_pCustomer_id'])
                        ->where(['ctpd_car_id'=>$carInfo['id']])
                        ->orderBy('ctpd_id desc')
                        ->asArray()->one();
                    $cCustomerId = $trialRecord['ctpd_cCustomer_id'];
                    $pCustomerId = $trialRecord['ctpd_pCustomer_id'];
                }
                if($cCustomerId){
                    //企业客户
                    $customerInfo = CustomerCompany::find()
                        ->select([
                            'customer_name'=>'company_name',
                            'director_name',
                            'director_mobile',
                        ])->where(['is_del'=>0,'id'=>$cCustomerId])
                        ->asArray()->one();
                }else{
                    //个人客户
                    $customerInfo = CustomerPersonal::find()
                        ->select([
                            'customer_name'=>'id_name',
                            'director_name'=>'id_name',
                            'director_mobile'=>'mobile',
                        ])->where(['is_del'=>0,'id'=>$pCustomerId])
                        ->asArray()->one();
                }
                if($customerInfo){
                    $carInfo = array_merge($carInfo,$customerInfo);
                }
                break;
        }
        //var_dump($carInfo);
        return $this->render('alert-deal',[
            'carInfo'=>$carInfo,
            'cadId'=>$cadId
        ]);
    }

    /**
     * 获取报警处理记录
     * carmonitor/exception-deal/get-deal-record
     */
    public function actionGetDealRecord()
    {
        $returnArr = [
            'rows'=>[],
            'total'=>0
        ];
        $cadId = yii::$app->request->get('cad_id');
        $data = CarAnomalyDetectionDeal::find()
            ->where(['cad_id'=>$cadId,'is_del'=>0])->asArray()->all();
        if($data){
            foreach($data as $key=>$val){
                switch($data[$key]['status']){
                    case 'new':
                        $data[$key]['status'] = '未处理';
                        break;
                    case 'acceptance':
                        $data[$key]['status'] = '已受理';
                        break;
                    case 'processing':
                        $data[$key]['status'] = '处理中';
                        break;
                    case 'end':
                        $data[$key]['status'] = '已完结';
                        break;
                }
            }
        }
        $returnArr['rows'] = $data;
        return json_encode($returnArr);
    }

    /**
     * 查看后台报警
     * carmonitor/exception-deal/back-alert
     */
    public function actionBackAlert()
    {
        if(yii::$app->request->isPost){
            $startId = yii::$app->request->post('start_id');
            $startId = $startId ? intval($startId) : 0;
            $query = CarAnomalyDetection::find()
                ->select([
                    '{{%car_anomaly_detection}}.`id`',
                    '{{%car_anomaly_detection}}.`car_vin`',
                    '{{%car_anomaly_detection}}.`alert_type`',
                    '{{%car_anomaly_detection}}.`alert_content`',
                    '{{%car_anomaly_detection}}.`alert_value`',
                    //'{{%car_anomaly_detection}}.`times`',
                    '{{%car}}.plate_number'
                ])->joinWith('car',false)
                ->andWhere(['{{%car}}.is_del'=>0])
                ->andWhere(['>','{{%car_anomaly_detection}}.`alert_dispose`',0])
                ->andWhere(['>','{{%car_anomaly_detection}}.`id`',$startId]);
            
            $data = $query->orderBy('{{%car_anomaly_detection}}.`id` asc')->asArray()->all();
            if($data){
                foreach($data as $k=>$v){
                    $data[$k]['alert_type'] = CarAnomalyDetection::$alertType[$data[$k]['alert_type']];
                }
            }
            return json_encode($data);
        }
        //返回该值表示用户有权限查看后台告警
        $returnArr = [
            'error'=>0,
            'max_id'=>0,
        ];
        $maxId = CarAnomalyDetection::find()
            ->select(['max_id'=>'max(id)'])->asArray()->limit(1)->one();
        if($maxId){
            $returnArr['max_id'] = $maxId['max_id'];
        }
        return json_encode($returnArr);
    } 

}