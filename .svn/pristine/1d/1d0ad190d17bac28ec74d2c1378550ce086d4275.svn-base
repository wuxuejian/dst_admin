<?php
/**
 * 电池衰减告警 控制器
 * @author chengwk
 * @time   2016-05-01 11:35
 */
namespace backend\modules\carmonitor\controllers;
use yii;
use yii\data\Pagination;
use backend\controllers\BaseController;
use backend\models\ConfigCategory;
use backend\models\BatteryDetectCriteria;
use backend\models\BatteryDetection;
use backend\models\BatteryCorrectVerify;
use backend\models\Car;
use backend\models\CarBrand;
use backend\classes\UserLog;
use common\models\Excel;

class BatteryAlertController extends BaseController
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
     * 获取告警列表
     */
    public function actionGetList()
    {
        $query = BatteryCorrectVerify::find()
            ->select([
                '{{%battery_correct_verify}}.*',
                '{{%car}}.plate_number',
                '{{%car}}.brand_id',
                '{{%car}}.car_type',
            ])
            ->joinWith('car',false,'LEFT JOIN')
            ->where([
                '{{%car}}.is_del'=>0,
                '{{%battery_correct_verify}}.verify_res'=>'ABNORMAL' //仅显示修正结果为‘异常’的记录
            ]);
        //查询条件
        $query->andFilterWhere(['like','{{%car}}.plate_number',yii::$app->request->get('plate_number')]);
        $query->andFilterWhere(['=','{{%car}}.brand_id',yii::$app->request->get('brand_id')]);
        $query->andFilterWhere(['=','{{%car}}.car_type',yii::$app->request->get('car_type')]);
        $query->andFilterWhere(['like','{{%battery_correct_verify}}.car_vin',yii::$app->request->get('car_vin')]);
        $query->andFilterWhere(['=','{{%battery_correct_verify}}.battery_type',yii::$app->request->get('battery_type')]);
        $query->andFilterWhere(['=','{{%battery_correct_verify}}.process_status',yii::$app->request->get('process_status')]);
        $query->andFilterWhere(['>=','process_time',yii::$app->request->get('process_time_start')]);
        if(yii::$app->request->get('process_time_end')){
            $query->andWhere(['<=','process_time',yii::$app->request->get('process_time_end').' 23:59:59']);
        }
        $query->andFilterWhere(['=','{{%battery_correct_verify}}.recheck_res',yii::$app->request->get('recheck_res')]);
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
                default:
                    $orderStr = '{{%battery_correct_verify}}.' . $sortField;
            }
        }else{
            $orderStr = '{{%battery_correct_verify}}.verify_time';
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
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }


    /*****************************************************************
     * “标记已处理”
     *****************************************************************/
    public function actionProcess(){
        if(yii::$app->request->isPost){
            $postData = yii::$app->request->post();
            $model = BatteryCorrectVerify::findOne($postData['id']);
            if(!$model){
               return json_encode(['status'=>false,'info'=>'未找到对应记录！']);
            }
            $model->load($postData, '');
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '处理成功！';
                    //记录日志
                    UserLog::log("电池衰减告警-标记处理车辆【".$model->car_vin."】",'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '处理失败！';
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
            $car_vin = yii::$app->request->get('car_vin','');
            $recInfo = BatteryCorrectVerify::find()
                ->select(['id','process_time','process_way','process_status'])
                ->where(['car_vin'=>$car_vin])
                ->asArray()->one();
            if(!$recInfo['process_time']){
                $recInfo['process_time'] = date('Y-m-d H:i:s');
            }
            return $this->render('processWin',[
                'recInfo'=>$recInfo
            ]);
        }
    }


    /*****************************************************************
     * “复检”
     *****************************************************************/
    public function actionRecheck(){
        $car_vin = yii::$app->request->get('car_vin','');
        $isDetectNew = yii::$app->request->get('isDetectNew',0);
        if(!$isDetectNew){
            return $this->render('recheckWin',['car_vin'=>$car_vin]);
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
        //仅当检测成功时才更新纪录
        $recheckInfo = [];
        foreach($res['detectData'] as $key=>$item){
            switch($key){
                case 'detect1':
                    $recheckInfo['soc_deviation_status'] = $item['resStatus'];
                    $recheckInfo['soc_deviation_res'] = $item['resInfo'];
                    $recheckInfo['soc_deviation_val'] = $item['socDiff'];
                    break;
                case 'detect2':
                    $recheckInfo['capacitance_attenuation_status'] = $item['resStatus'];
                    $recheckInfo['capacitance_attenuation_res'] = $item['resInfo'];
                    $recheckInfo['voltage_deviation_val'] = $item['volDiff'];
                    break;
                case 'detect3':
                    $recheckInfo['capacitance_deviation_status'] = $item['resStatus'];
                    $recheckInfo['capacitance_deviation_res'] = $item['resInfo'];
                    break;
            }
        }
        $model = BatteryCorrectVerify::findOne(['car_vin'=>$car['car_vin']]);
        if($recheckInfo['soc_deviation_status'] == 'NORMAL' && $recheckInfo['capacitance_attenuation_status'] == 'NORMAL' && $recheckInfo['capacitance_deviation_status'] == 'NORMAL'){
            $model->recheck_res = 'NORMAL'; //仅当3种算法都“正常”复检结果才显示“正常”
        }else{
            $model->recheck_res = 'ABNORMAL';
        }
        $model->recheck_time = date('Y-m-d H:i:s');
        $model->recheck_aid = $_SESSION['backend']['adminInfo']['id'];
        if(!$model->save(true)){
            return json_encode(['status'=>false,'info'=>'保存复检结果失败！']);
        }else{
            return json_encode(['status'=>true,'recheckInfo'=>$recheckInfo]);
        }
    }


    /**
     * 导出告警记录
     */
    public function actionExportGridData(){
        $excel = new Excel();
        $excel->setHeader([
            'creator'=>'皓峰通讯',
            'lastModifiedBy'=>'hao feng tong xun',
            'title'=>'battery_correct_verify',
            'subject'=>'battery_correct_verify',
            'description'=>'battery_correct_verify',
            'keywords'=>'battery_correct_verify',
            'category'=>'battery_correct_verify'
        ]);
        // 构建导出的excel表头
        $excHeaders = [
            [
                ['content'=>'车牌号','font-weight'=>true,'width'=>'15'],
                ['content'=>'车架号','font-weight'=>true,'width'=>'25'],
                ['content'=>'车辆品牌','font-weight'=>true,'width'=>'15'],
                ['content'=>'车辆类型','font-weight'=>true,'width'=>'15'],
                ['content'=>'电池类型','font-weight'=>true,'width'=>'15'],
                ['content'=>'SOC偏移','font-weight'=>true,'width'=>'15'],
                ['content'=>'偏移量','font-weight'=>true,'width'=>'15'],
                ['content'=>'电池容量衰减','font-weight'=>true,'width'=>'15'],
                ['content'=>'压差偏移量','font-weight'=>true,'width'=>'15'],
                ['content'=>'电池容量偏差','font-weight'=>true,'width'=>'15'],
                ['content'=>'验证时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'处理时间','font-weight'=>true,'width'=>'20'],
                ['content'=>'处理状态','font-weight'=>true,'width'=>'15'],
                ['content'=>'复检结果','font-weight'=>true,'width'=>'15'],
            ]
        ];
        //---向excel添加表头--------------------
        foreach($excHeaders as $lineData){
            $excel->addLineToExcel($lineData);
        }

        //查询数据，查询字段与导出的excel表头对应
        $query = BatteryCorrectVerify::find()
            ->select([
                '{{%car}}.plate_number',
                '{{%battery_correct_verify}}.car_vin',
                '{{%car}}.brand_id',
                '{{%car}}.car_type',
                '{{%battery_correct_verify}}.battery_type',
                '{{%battery_correct_verify}}.soc_deviation_status',
                '{{%battery_correct_verify}}.soc_deviation_val',
                '{{%battery_correct_verify}}.capacitance_attenuation_status',
                '{{%battery_correct_verify}}.voltage_deviation_val',
                '{{%battery_correct_verify}}.capacitance_deviation_status',
                '{{%battery_correct_verify}}.verify_time',
                '{{%battery_correct_verify}}.process_time',
                '{{%battery_correct_verify}}.process_status',
                '{{%battery_correct_verify}}.recheck_res'
            ])
            ->joinWith('car',false,'LEFT JOIN')
            ->where(['{{%car}}.is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['like','{{%car}}.plate_number',yii::$app->request->get('plate_number')]);
        $query->andFilterWhere(['=','{{%car}}.brand_id',yii::$app->request->get('brand_id')]);
        $query->andFilterWhere(['=','{{%car}}.car_type',yii::$app->request->get('car_type')]);
        $query->andFilterWhere(['like','{{%battery_correct_verify}}.car_vin',yii::$app->request->get('car_vin')]);
        $query->andFilterWhere(['=','{{%battery_correct_verify}}.battery_type',yii::$app->request->get('battery_type')]);
        $query->andFilterWhere(['=','{{%battery_correct_verify}}.process_status',yii::$app->request->get('process_status')]);
        $query->andFilterWhere(['>=','process_time',yii::$app->request->get('process_time_start')]);
        if(yii::$app->request->get('process_time_end')){
            $query->andWhere(['<=','process_time',yii::$app->request->get('process_time_end').' 23:59:59']);
        }
        $query->andFilterWhere(['=','{{%battery_correct_verify}}.recheck_res',yii::$app->request->get('recheck_res')]);
        $data = $query->orderBy('{{%battery_correct_verify}}.verify_time DESC')->asArray()->all();
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
                if($item['recheck_res']){
                    $item['recheck_res'] = $statusArr[$item['recheck_res']];
                }
                if($item['process_status']){
                    switch ($item['process_status']) {
                        case 'PROCESSED':
                            $item['process_status'] = '已处理'; break;
                        case 'UNPROCESSED':
                            $item['process_status'] = '未处理'; break;
                        case 'WAITFOLLOW':
                            $item['process_status'] = '待跟进'; break;
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
        header("Content-Disposition: attachment; filename=".iconv('utf-8','gbk','电池衰减检测列表导出.xls'));
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }



}