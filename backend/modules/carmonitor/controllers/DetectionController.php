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
use backend\models\Battery;
use backend\models\Car;
use backend\models\TcpCarRealtimeData;
use common\classes\CarRealtimeDataAnalysis;
use backend\classes\UserLog;
use common\models\Excel;

class DetectionController extends BaseController
{
    public function actionIndex()
    {
        $configItems = ['car_type','car_brand','car_type','battery_type'];
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
                '{{%car}}.car_brand',
                '{{%car}}.car_type',
            ])
            ->joinWith('car',false,'LEFT JOIN')
            ->where(['{{%car}}.is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['like','{{%car}}.plate_number',yii::$app->request->get('plate_number')]);
        $query->andFilterWhere(['=','{{%car}}.car_brand',yii::$app->request->get('car_brand')]);
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
                case 'car_brand':
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
            $car_brand = yii::$app->request->post('car_brand');
            $car_type = yii::$app->request->post('car_type');
            $car_vin = trim(yii::$app->request->post('car_vin'));
            /*if(!$car_brand && !$car_type && !$car_vin){
                return json_encode(['status'=>false,'info'=>'表单字段不能全部为空！']);
            }*/
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
                    $failCarArr['fail1_noCarVin'] = ['failInfo'=>'该车架号不存在！','failCar'=>$diffCars];
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
                    ->andFilterWhere(['=','car_brand',$car_brand])
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
                    if(!isset($failCarArr['fail2_noBatteryType']['failInfo'])){
                        $failCarArr['fail2_noBatteryType']['failInfo'] = '该车电池型号不存在！';
                    }
                    $failCarArr['fail2_noBatteryType']['failCar'][] = $car['car_vin'];
                    continue;
                }
                //执行电池衰减检测
                $res = $this->executeSocDetect($criteria[$car['battery_type']],$car);
                if(!$res['status']){
                    if(!isset($failCarArr['fail3_noRealtimeData']['failInfo'])){
                        $failCarArr['fail3_noRealtimeData']['failInfo'] = $res['info'];
                    }
                    $failCarArr['fail3_noRealtimeData']['failCar'][] = $car['car_vin'];
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
                $model->used_history_data = $res['used_history_data'];
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
                    if(!isset($failCarArr['fail4_noSaveSuccess']['failInfo'])){
                        $failCarArr['fail4_noSaveSuccess']['failInfo'] = '保存检测记录失败！';
                        $failCarArr['fail4_noSaveSuccess']['failError'] = $model->errors;
                    }
                    $failCarArr['fail4_noSaveSuccess']['failCar'][] = $car['car_vin'];
                }
            }
            if(!empty($failCarArr)){
                return json_encode(['status'=>false, 'info'=>$failCarArr]);
            }else{
                return json_encode(['status'=>true, 'info'=>'检测执行完毕！']);
            }
        }else{
            $car_vin = yii::$app->request->get('car_vin','');
            $configItems = ['car_type','car_brand'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            return $this->render('detectWin',[
                'config'=>$config,
                'car_vin'=>$car_vin
            ]);
        }
    }

    /**
     * 依据检测标准和算法对车辆执行电池SOC检测
     * @$criterion: 某型号电池的SOC检测标准
     * @$car: 将要被检测的车辆
     */
    protected function executeSocDetect($criterion, $car){
        $carVin = $car['car_vin'];
        $batteryModel = $car['battery_model'];
        //下面共需要执行3种算法：
        //=== （一）、获取最新一次电池监控数据，执行算法1和算法2 ====================================
        //(1)优先从本月的监控数据里查找
        $connection = yii::$app->db1;
        $db = 'db1';
        $tableName = 'cs_tcp_car_history_data_'.date('Ym').'_'.substr($carVin,-1);
        $tabRes = $connection->createCommand("SHOW TABLES LIKE '{$tableName}'")->queryAll();
        if($tabRes){
            $query = (new \yii\db\Query())
                ->select(['*'])
                ->from($tableName)
                ->andWhere(['car_vin'=>$carVin])
                ->orderBy('collection_datetime DESC')
                ->limit(1);
            $dataA = $query->one($connection);
        }
        //(2)若本月数据没有再尝试从历史备份数据库中去查找
        if(!isset($dataA) || !$dataA){
            $connection = yii::$app->db2;
            $db = 'db2';
            $tabRes = $connection->createCommand("SHOW TABLES LIKE 'cs_tcp_car_history_data_%'")->queryAll();
            if($tabRes){
                rsort($tabRes); //表名降序排序
                foreach($tabRes as $item){
                    foreach($item as $val){
                        $tableName = $val;
                        $query = (new \yii\db\Query())
                            ->select(['*'])
                            ->from($tableName)
                            ->andWhere(['car_vin'=>$carVin])
                            ->orderBy('collection_datetime DESC')
                            ->limit(1);
                        $dataA = $query->one($connection);
                    }
                    if($dataA){
                        break;
                    }
                }
            }
        }
        if(!isset($dataA) || !$dataA){
            return ['status'=>false,'info'=>"查询电池实时数据错误！"];
        }
        //解析数据
        $analysisObj = new CarRealtimeDataAnalysis($dataA['data_hex']);
        $realtimeData = $analysisObj->getRealtimeData();
        if(!$realtimeData){
            return ['status'=>false,'info'=>"查询电池实时数据错误！"];
        }
        //保存本次检测所依赖的历史数据，以便读取原始监控数据详细使用（格式：数据库,数据表,记录id）
        $used_history_data = $db.','.$tableName.','.$dataA['id'];

        $returnArr = [];
        //---【算法1】-------------------------
        //求电池单体电压平均值
        $avg = ($realtimeData['battery_single_hv_value'] + $realtimeData['battery_single_lv_value']) / 2;
        //取出电池单体电压平均值V对应的SOC范围值Y
        if($avg >= $criterion['V1_S'] && $avg < $criterion['V1_E']){
            $YRange = [$criterion['Y1_S'], $criterion['Y1_E']];
        }elseif($avg >= $criterion['V2_S'] && $avg < $criterion['V2_E']){
            $YRange = [$criterion['Y2_S'], $criterion['Y2_E']];
        }elseif($avg >= $criterion['V3_S'] && $avg < $criterion['V3_E']){
            $YRange = [$criterion['Y3_S'], $criterion['Y3_E']];
        }else{
            //不在任何V范围内时略过
        }
        if(!isset($YRange)){
            $returnArr['detect1'] = [
                'resInfo'=>'电池监控数据异常',
                'resStatus'=>'INVALID',
                'socDiff'=>'—'
            ];
        }else{
            $soc = $realtimeData['battery_package_soc'];
            if($soc >= $YRange[0] && $soc <= $YRange[1]){
                $returnArr['detect1'] = [
                    'resInfo'=>'SOC值偏移量处于正常范围',
                    'resStatus'=>'NORMAL',
                    'socDiff'=>'0'       //正常时偏差值显示什么？
                ];
            }else{
                if($soc < $YRange[0]){
                    $socDiff = $soc - $YRange[0];
                }else{
                    $socDiff = $soc - $YRange[1];
                }
                $returnArr['detect1'] = [
                    'resInfo'=>'SOC值偏移量超出正常范围，需校准',
                    'resStatus'=>'ABNORMAL',
                    'socDiff'=>$socDiff . '%'
                ];
            }
        }
        //---【算法2】----------------------------
        //取出电池单体电压平均值V对应的单体压差阀值A
        if($avg >= $criterion['V4_S'] && $avg < $criterion['V4_E']){
            $A = $criterion['A1'];
        }elseif($avg >= $criterion['V5_S'] && $avg < $criterion['V5_E']){
            $A = $criterion['A2'];
        }elseif($avg >= $criterion['V6_S'] && $avg < $criterion['V6_E']){
            $A = $criterion['A3'];
        }else{
            //不在任何V范围内时略过
        }
        if(!isset($A)){
            $returnArr['detect2'] = [
                'resInfo'=>'电池监控数据异常',
                'resStatus'=>'INVALID',
                'socDiff'=>'—'
            ];
        }else{
            //求电池最高单体与最低单体电压差
            $diff = $realtimeData['battery_single_hv_value'] - $realtimeData['battery_single_lv_value'];
            if($diff <= $A){
                $returnArr['detect2'] = [
                    'resInfo'=>'有效电能正常，车辆续航里程正常',
                    'resStatus'=>'NORMAL',
                    'volDiff'=>"0"
                ];
            }else{
                $volDiff = $diff - $A;
                $returnArr['detect2'] = [
                    'resInfo'=>'有效电能可能减少，车辆续航里程可能减少',
                    'resStatus'=>'ABNORMAL',
                    'volDiff'=>"$volDiff"
                ];
            }
        }

        //=== （二）、获取最新一次充电记录，执行算法3 ========================================================
        //算法3判断依据： |( C1 / 电池额定Ah) *100% - (SOC_B –SOC_A)| < X, 为正常，否则为电池有衰减可能。
        // 开始充电$SOC_A、结束充电$SOC_B、充电电流积分值C1
        $SOC_A = 0; $SOC_B = 0; $C1 = 0;
        //SOC容量偏差百分比X
        $X = $criterion['X'];
        //查该型号电池额定容量：$Ah
        $batteryInfo = Battery::find()->where(['battery_model'=>$batteryModel])->asArray()->one();
        $Ah = $batteryInfo['single_capacity'];
        if($Ah <= 0){
            $detect3Tip = "车辆{$carVin}对应的电池型号的额定容量不能设置为0；";
        }else{
            //查最后一次的充电过程
            foreach($tabRes as $item){
                foreach($item as $val){
                    $tableName = $val;
                    //查找出充电的最后一次上报记录时间，以便下面限制查找一天内的充电记录
                    $lastRec = (new \yii\db\Query())
                        ->select(['id','car_vin','collection_datetime'])
                        ->from($tableName)
                        ->where(
                            'car_vin = :car_vin and `collection_datetime` and car_current_status = 2',
                            [
                                ':car_vin'=>$carVin
                            ]
                        )
                        ->orderBy('collection_datetime DESC')
                        ->one($connection);
                    if($lastRec){
                        $dataB = (new \yii\db\Query())
                            ->select(['id','car_vin','collection_datetime'])
                            ->from($tableName)
                            ->where(
                                'car_vin = :car_vin and `collection_datetime` >= :collection_datetime_s and `collection_datetime` <= :collection_datetime_e and car_current_status = 2',
                                [
                                    ':car_vin'=>$carVin,
                                    ':collection_datetime_s'=>$lastRec['collection_datetime'] - 3600*24, //一次充电过程认定在24小时之内
                                    ':collection_datetime_e'=>$lastRec['collection_datetime'],
                                ]
                            )
                            ->orderBy('collection_datetime asc')
                            ->all($connection);
                    }
                }
                if(isset($dataB) && $dataB){
                    break;
                }
            }
            if(!isset($dataB) || !$dataB){
                $detect3Tip = "车辆{$carVin}查不到任何充电上报记录；";
            }else{
                //1.筛选出同一次充电过程的所有上报记录。
                //注意：因为一次充电过程会不断的上报数据，所以上报间隔固定秒数内都视为同一次充电过程。
                $recIds = [];
                $seconds = 300;  //上报间隔秒数
                foreach($dataB as $key=>$val){
                    //先判断当前记录的前一条记录是否存在并且与当前记录上报时间间隔在范围内，若否，则当前记录为某一次充电开始记录；
                    if(isset($dataB[$key-1]) && ($val['collection_datetime'] - $dataB[$key-1]['collection_datetime']) < $seconds){
                        array_push($recIds[count($recIds)-1],$val['id']);
                    }else{
                        $recIds[] = [$val['id']];
                    }
                }
                unset($dataB);
                //print_r($recIds);exit;
                //2.取出最近一次的有效的充电过程的所有上报记录id
                if($recIds){
                    $lastChargeIds = [];
                    for($i=count($recIds)-1; $i>=0; $i--){
                        if(count($recIds[$i]) >= 2){ //一次有效充电过程至少有2条上报记录（开始和结束）
                            $lastChargeIds = $recIds[$i];
                            break;
                        }
                    }
                    //print_r($lastChargeIds);exit;
                    //3.查最近一次充电过程的所有上报记录
                    if(!$lastChargeIds){
                        $detect3Tip = "车辆{$carVin}查不到最近一次有效的充电记录；";
                    }else{
                        $dataC = (new \yii\db\Query())
                            ->select(['id','car_vin','collection_datetime','battery_package_soc','data_hex'])
                            ->from($tableName)
                            ->where(['id'=>$lastChargeIds])
                            ->orderBy('collection_datetime asc')
                            ->all($connection);
                        //print_r($dataC);exit;
                        $chargeTime = $dataC[count($dataC)-1]['collection_datetime'] - $dataC[0]['collection_datetime'];
                        $T1 = $criterion['T1'];
                        if($chargeTime > $T1*60){
                            //开始充电SOC和结束充电SOC：$SOC_A、$SOC_B
                            $SOC_A = $dataC[0]['battery_package_soc'];
                            $SOC_B = $dataC[count($dataC)-1]['battery_package_soc'];
                            //计算充电电流积分值：C1 = I1* N1/3600 + I2* N2/3600 + … IX* NX/3600安时（Ah）
                            //注意：每个子项都表示N秒内车辆充电的电量，电池包电流I1* N1/3600（秒换算成小时）
                            for($i=0; $i<count($dataC)-1; $i++){
                                //解析数据
                                $analysisObj1 = new CarRealtimeDataAnalysis($dataC[$i]['data_hex']);
                                $realtimeData1 = $analysisObj1->getRealtimeData();
                                $analysisObj2 = new CarRealtimeDataAnalysis($dataC[$i+1]['data_hex']);
                                $realtimeData2 = $analysisObj2->getRealtimeData();
                                if(!$realtimeData1 || !$realtimeData2){
                                    continue;
                                }
                                $I = $realtimeData2['battery_package_current'];
                                $N = $realtimeData2['collection_datetime'] - $realtimeData1['collection_datetime'];
                                $C1 += $I * $N/3600;
                            }
                        }
                    }
                }
            }
        }
        if(!$SOC_A || !$SOC_B || ($SOC_B - $SOC_A)<0  || !$C1){
            $returnArr['detect3'] = [
                'resInfo'=>'电池监控数据异常',
                'resStatus'=>'INVALID',
            ];
            if(isset($detect3Tip)){
                $returnArr['detect3']['detect3Tip'] = $detect3Tip;
            }
        }else{
            $C1Ah = abs( ( $C1 / $Ah) *100% - ($SOC_B - $SOC_A) );
            if($C1Ah < $X){
                $returnArr['detect3'] = [
                    'resInfo'=>'电池容量正常',
                    'resStatus'=>'NORMAL'
                ];
            }else{
                $returnArr['detect3'] = [
                    'resInfo'=>'电池有衰减可能',
                    'resStatus'=>'NORMAL'
                ];
            }
        }

        return [
            'status'=>true,
            'detectData'=>$returnArr,
            //本次检测所依赖的历史数据（格式：数据库,数据表,记录id）
            'used_history_data'=>$used_history_data
        ];
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
        if(!$connection){
            return '数据库连接失败，请重新打开此窗口！';
        }
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
                '{{%car}}.car_brand',
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
        $query->andFilterWhere(['=','{{%car}}.car_brand',yii::$app->request->get('car_brand')]);
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
            $configItems = ['car_brand','car_type','battery_type'];
            $configs = (new ConfigCategory())->getCategoryConfig($configItems,'value');
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