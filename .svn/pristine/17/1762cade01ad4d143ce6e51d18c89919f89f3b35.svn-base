<?php
/**
 * 电池衰减检测 控制器
 * @author chengwk
 * @time   2016-04-13 11:15
 */
namespace backend\modules\carmonitor\controllers;
use yii;
use yii\data\Pagination;
use backend\controllers\BaseController;
use backend\models\ConfigCategory;
use backend\models\BatteryDetectCriteria;
use backend\models\BatteryDetection;
use backend\models\Car;
use backend\models\CarBrand;
use backend\models\TcpCarRealtimeData;
use common\classes\CarRealtimeDataAnalysis;
use backend\classes\UserLog;
use common\models\Excel;

class BatteryDetectionController extends BaseController
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
     * 获取检测记录列表
     */
    public function actionGetList()
    {
        $query = BatteryDetection::find()
            ->select([
                '{{%battery_detection}}.*',
                '{{%car}}.plate_number',
                '{{%car}}.brand_id',
                '{{%car}}.car_type',
            ])
            ->joinWith('car',false,'LEFT JOIN')
            ->where(['{{%car}}.is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['like','{{%car}}.plate_number',yii::$app->request->get('plate_number')]);
        $query->andFilterWhere(['=','{{%car}}.brand_id',yii::$app->request->get('brand_id')]);
        $query->andFilterWhere(['=','{{%car}}.car_type',yii::$app->request->get('car_type')]);
        $query->andFilterWhere(['like','{{%battery_detection}}.car_vin',yii::$app->request->get('car_vin')]);
        $query->andFilterWhere(['=','{{%battery_detection}}.battery_type',yii::$app->request->get('battery_type')]);
        $query->andFilterWhere(['=','{{%battery_detection}}.soc_deviation_status',yii::$app->request->get('soc_deviation_status')]);
        $query->andFilterWhere(['=','{{%battery_detection}}.capacitance_attenuation_status',yii::$app->request->get('capacitance_attenuation_status')]);
        $query->andFilterWhere(['=','{{%battery_detection}}.capacitance_deviation_status',yii::$app->request->get('capacitance_deviation_status')]);
        $query->andFilterWhere(['>=','detect_time',yii::$app->request->get('detect_time_start')]);
        if(yii::$app->request->get('detect_time_end')){
            $query->andWhere(['<=','detect_time',yii::$app->request->get('detect_time_end').' 23:59:59']);
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
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }


    /*****************************************************************
     * “设置参数”
     *****************************************************************/
    public function actionSetParams(){
        $configItems = ['battery_type'];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        $buttons = $this->getCurrentActionBtn();
        return $this->render('setParamsWin',[
            'config'=>$config,
            'buttons'=>$buttons
        ]);
    }

    /**
     * “设置参数”--获取列表
     */
    public function actionGetCriteriaList()
    {
        $query = BatteryDetectCriteria::find()
            ->select([
                '{{%battery_detect_criteria}}.*',
                'creator'=> '{{%admin}}.username'
            ])
            ->joinWith('admin',false,'LEFT JOIN')
            ->where(['{{%battery_detect_criteria}}.is_del'=>0]);
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
                    $orderStr = '{{%battery_detect_criteria}}.' . $sortField;
            }
        }else{
            $orderStr = '{{%battery_detect_criteria}}.id';
        }
        $orderStr .= ' ' . $sortDirect;
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

    /*
     * “设置参数”--新增标准
     */
    public function actionAddCriteria(){
        if(yii::$app->request->isPost){
            $model = new BatteryDetectCriteria();
            $formData = yii::$app->request->post();
            //检查是否重复
            $res = BatteryDetectCriteria::find()
                ->where(['battery_type'=>$formData['battery_type'],'is_del'=>0])
                ->asArray()->One();
            if($res){
                $returnArr['status'] = false;
                $returnArr['info'] = '该电池类型的判定标准已存在，不能重复设置！';
                return json_encode($returnArr);
            }
            $model->load($formData,'');
            $returnArr = [];
            if($model->validate()){
                $model->create_time = date('Y-m-d H:i:s');
                $model->creator_id = $_SESSION['backend']['adminInfo']['id'];
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '新增判定标准成功！';
                    // 添加日志
                    $logStr = "电池衰减检测-新增判定标准（id：" . ($model->id) . "）";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '新增判断标准失败！';
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
            //获取combo配置数据
            $configItems = ['battery_type'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            return $this->render('addCriteriaWin',[
                'config'=>$config
            ]);
        }
    }

    /*
     * “设置参数”--修改标准
     */
    public function actionEditCriteria(){
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            //检查是否重复
            $res = BatteryDetectCriteria::find()
                ->select(['id'])
                ->where(['battery_type'=>$formData['battery_type'],'is_del'=>0])
                ->asArray()->One();
            if($res && $res['id']!=$formData['id']){
                $returnArr['status'] = false;
                $returnArr['info'] = '该电池类型的判定标准已存在，不能重复设置！';
                return json_encode($returnArr);
            }
            $model = BatteryDetectCriteria::findOne($formData['id']);
            $model->load($formData,'');
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '修改判定标准成功！';
                    // 添加日志
                    $logStr = "电池衰减检测-修改判定标准（id：" . ($model->id) . "）";
                    UserLog::log($logStr, 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '修改判断标准失败！';
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
            //获取combo配置数据
            $configItems = ['battery_type'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            $id = intval(yii::$app->request->get('id')) or die("缺少参数ID");
            $criteriaInfo = BatteryDetectCriteria::find()->where(['id'=>$id])->asArray()->one();
            return $this->render('editCriteriaWin',[
                'config'=>$config,
                'criteriaInfo'=>$criteriaInfo
            ]);
        }
    }

    /**
     * “设置参数”--删除标准
     */
    public function actionRemoveCriteria()
    {
        $id = intval(yii::$app->request->get('id')) or die("缺少参数ID");
        $returnArr = [];
        if(BatteryDetectCriteria::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '删除判定标准成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '删除判定标准失败！';
        }
        return json_encode($returnArr);
    }

    /**
     * “设置参数”--查看详细
     */
    public function actionScanCriteriaDetails(){
        $id = intval(yii::$app->request->get('id')) or die("缺少参数ID");
        $criteriaInfo = BatteryDetectCriteria::find()->where(['id'=>$id])->asArray()->one();
        $configItems = ['battery_type'];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        if(isset($criteriaInfo['battery_type']) && $criteriaInfo['battery_type']){
            $criteriaInfo['battery_type'] = $config['battery_type'][$criteriaInfo['battery_type']]['text'];
        }
        return $this->render('scanCriteriaDetailsWin',[
            'criteriaInfo'=>$criteriaInfo
        ]);
    }



    /*****************************************************************
     * “执行检测”
     *****************************************************************/
    public function actionDetect(){
        if(yii::$app->request->isPost) {
            $brand_id = yii::$app->request->post('brand_id');
            $car_type = yii::$app->request->post('car_type');
            $car_vin = trim(yii::$app->request->post('car_vin'));
            //声明变量将保存所有检测失败的车辆及原因
            $failCarArr = [];
            //优先使用指定的车架号进行检测
            if($car_vin){
                $carVinStr = preg_replace('/\s+/', ' ', $car_vin);
                $carVinArr = explode(' ',$carVinStr);
                $cars = Car::find()
                    ->select([
                        'car_vin'=>'vehicle_dentification_number',
                        '{{%battery}}.battery_type',
                        '{{%battery}}.battery_model'
                    ])
                    ->joinWith('battery',false)
                    ->where(['{{%car}}.is_del'=>0,'vehicle_dentification_number'=>$carVinArr])
                    ->asArray()->all();
                //检查填写的车架号是否都真实存在
                $realCarVinArr = array_column($cars,'car_vin');
                $diffCars = array_values(array_diff($carVinArr,$realCarVinArr));
                if(!empty($diffCars)){
                    $failCarArr['fail1'] = ['failInfo'=>'该车架号不存在！','failCar'=>$diffCars];
                }
            }else{
                $cars = Car::find()
                    ->select([
                        'car_vin'=>'vehicle_dentification_number',
                        '{{%battery}}.battery_type',
                        '{{%battery}}.battery_model'
                    ])
                    ->joinWith('battery',false)
                    ->where(['{{%car}}.is_del'=>0])
                    ->andFilterWhere(['=','brand_id',$brand_id])
                    ->andFilterWhere(['=','car_type',$car_type])
                    ->asArray()->all();
            }
            if(count($cars) > 15){
                return json_encode(['status'=>false,'info'=>'为避免查询数据过量，请保证每次检测车辆在15辆以内！']);
            }
            //print_r($cars);exit;

            //获取所有检测标准，然后对车辆逐一进行检测
            $criteria = BatteryDetectCriteria::find()
                ->where(['is_del'=>0])
                ->indexBy('battery_type')
                ->asArray()->all();
            set_time_limit(0);
            foreach($cars as $car){
                if(!$car['battery_type']){
                    if(!isset($failCarArr['fail2']['failInfo'])){
                        $failCarArr['fail2']['failInfo'] = '该车电池型号不存在！';
                    }
                    $failCarArr['fail2']['failCar'][] = $car['car_vin'];
                    continue;
                }
                //--执行电池衰减检测--
                $res = BatteryDetection::executeSocDetect($criteria[$car['battery_type']],$car);
                if(!$res['status']){
                    if(!isset($failCarArr['fail3'][$res['errCode']])){
                        $failCarArr['fail3'][$res['errCode']]['failInfo'] = $res['info'];
                    }
                    $failCarArr['fail3'][$res['errCode']]['failCar'][] = $car['car_vin'];
                    continue;
                }
                //仅当检测成功时才向数据表中插入或更新纪录
                $model = BatteryDetection::findOne(['car_vin'=>$car['car_vin']]);
                if(!$model){
                    $model = new BatteryDetection();
                }
                $model->car_vin = $car['car_vin'];
                $model->battery_type = $car['battery_type'];
                $model->detect_time = date('Y-m-d H:i:s');
                $model->used_history_data = $res['detectData']['used_history_data'];
                $model->latest_notice_id = 0;   //清除“最新修正通知id”
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
                if(!$model->save(true)){
                    if(!isset($failCarArr['fail4']['failInfo'])){
                        $failCarArr['fail4']['failInfo'] = '保存检测记录失败！';
                        $failCarArr['fail4']['failError'] = $model->errors;
                    }
                    $failCarArr['fail4']['failCar'][] = $car['car_vin'];
                }
            }
            if(!empty($failCarArr)){
                return json_encode(['status'=>false, 'info'=>$failCarArr]);
            }else{
                return json_encode(['status'=>true, 'info'=>'检测执行完毕！']);
            }
        }else{
            $car_vin = yii::$app->request->get('car_vin','');
            $configItems = ['car_type'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            return $this->render('detectWin',[
                'config'=>$config,
                'car_vin'=>$car_vin
            ]);
        }
    }


    /**
     * 查看监控数据详细
     */
    public function actionScanOriginalData(){
        $carVin = yii::$app->request->get('car_vin');
        $detection = BatteryDetection::find()->where(['car_vin'=>$carVin])->asArray()->one();
        if(!$detection || !$detection['used_history_data']){
            return '查不到该车检测记录或数据表used_history_data字段为空！';
        }
        list($db,$tableName,$recId) = explode(',',$detection['used_history_data']);
        //获取监控历史数据
        $connection = ($db == 'db1') ? yii::$app->db1 : yii::$app->db2;
        $res = $connection->createCommand("SHOW TABLES LIKE '{$tableName}'")->queryAll();
        if(!$res){
            return "数据表（{$tableName}）不存在！";
        }
        $data = (new \yii\db\Query())
            ->select(['*'])
            ->from($tableName)
            ->andWhere(['id'=>$recId])
            ->one($connection);
        if(!$data){
            return '没有查到对应实时数据！';
        }
        //解析数据
        $analysisObj = new CarRealtimeDataAnalysis($data['data_hex']);
        $realtimeData = $analysisObj->getRealtimeData();
        if(!$realtimeData){
            return '对应实时数据解析错误！';
        }
        //---临时改动-20160329------------------
        $realtimeData['air_condition_temperature'] = '-';
        $realtimeData['battery_package_resistance_value'] = '-';
        //---临时改动-20160329------------------
        return $this->render('scanOriginalDataWin',[
            'carVin'=>$carVin,
            'data'=>$realtimeData,
            'attributeLabels'=>(new TcpCarRealtimeData)->attributeLabels(),
        ]);
    }


    /**
     * 导出检测记录
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
                ['content'=>'车牌号','font-weight'=>true,'width'=>'15'],
                ['content'=>'车架号','font-weight'=>true,'width'=>'25'],
                ['content'=>'车辆品牌','font-weight'=>true,'width'=>'15'],
                ['content'=>'车辆类型','font-weight'=>true,'width'=>'15'],
                ['content'=>'电池类型','font-weight'=>true,'width'=>'15'],
                ['content'=>'SOC偏移','font-weight'=>true,'width'=>'15'],
                ['content'=>'偏移量','font-weight'=>true,'width'=>'15'],
                ['content'=>'判定结果','font-weight'=>true,'width'=>'35'],
                ['content'=>'电池容量衰减','font-weight'=>true,'width'=>'15'],
                ['content'=>'压差偏移量','font-weight'=>true,'width'=>'15'],
                ['content'=>'判定结果','font-weight'=>true,'width'=>'35'],
                ['content'=>'电池容量偏差','font-weight'=>true,'width'=>'15'],
                ['content'=>'判定结果','font-weight'=>true,'width'=>'35'],
                ['content'=>'检测时间','font-weight'=>true,'width'=>'20']
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
                '{{%battery_detection}}.soc_deviation_val',
                '{{%battery_detection}}.soc_deviation_res',
                '{{%battery_detection}}.capacitance_attenuation_status',
                '{{%battery_detection}}.voltage_deviation_val',
                '{{%battery_detection}}.capacitance_attenuation_res',
                '{{%battery_detection}}.capacitance_deviation_status',
                '{{%battery_detection}}.capacitance_deviation_res',
                '{{%battery_detection}}.detect_time'
            ])
            ->joinWith('car',false,'LEFT JOIN')
            ->where(['{{%car}}.is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['like','{{%car}}.plate_number',yii::$app->request->get('plate_number')]);
        $query->andFilterWhere(['=','{{%car}}.brand_id',yii::$app->request->get('brand_id')]);
        $query->andFilterWhere(['=','{{%car}}.car_type',yii::$app->request->get('car_type')]);
        $query->andFilterWhere(['like','{{%battery_detection}}.car_vin',yii::$app->request->get('car_vin')]);
        $query->andFilterWhere(['=','{{%battery_detection}}.battery_type',yii::$app->request->get('battery_type')]);
        $query->andFilterWhere(['=','{{%battery_detection}}.soc_deviation_status',yii::$app->request->get('soc_deviation_status')]);
        $query->andFilterWhere(['=','{{%battery_detection}}.capacitance_attenuation_status',yii::$app->request->get('capacitance_attenuation_status')]);
        $query->andFilterWhere(['=','{{%battery_detection}}.capacitance_deviation_status',yii::$app->request->get('capacitance_deviation_status')]);
        $query->andFilterWhere(['>=','detect_time',yii::$app->request->get('detect_time_start')]);
        if(yii::$app->request->get('detect_time_end')){
            $query->andWhere(['<=','detect_time',yii::$app->request->get('detect_time_end').' 23:59:59']);
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