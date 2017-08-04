<?php
/**
 * 车辆电池衰减检测记录 模型
 */
namespace backend\models;
use yii;
use yii\db\Exception;
use common\classes\CarRealtimeDataAnalysis;

class BatteryDetection extends \common\models\BatteryDetection
{

    /**
     * 关联【车辆】表
     */
    public function getCar()
    {
        return $this->hasOne(Car::className(), [
            'vehicle_dentification_number' => 'car_vin'
        ]);
    }

    /**
     * 关联获取“最新修正通知（按通知时间和更新时间降序）”
     */
    public function getLatestNotice()
    {
        return $this->hasOne(BatteryCorrectNotice::className(), ['id' => 'latest_notice_id'])
        ->andOnCondition('{{%battery_correct_notice}}.`is_del` = 0 OR {{%battery_correct_notice}}.`id` IS NULL'); //增加其他on条件
    }

    /**
     * 关联【电池验证修正结果】表
     */
    public function getBatteryCorrectVerify()
    {
        return $this->hasOne(BatteryCorrectVerify::className(), [
            'car_vin' => 'car_vin'
        ]);
    }


    /**
     * 依据检测标准和算法对车辆执行电池SOC检测（3种算法都仅基于“本月监控数据”作分析）
     * @$criterion: 某型号电池的检测标准
     * @$car: 将要被检测的车辆
     */
    static function executeSocDetect($criterion, $car)
    {
        $carVin = $car['car_vin'];
        $returnArr = [];
        $returnArr['detect1'] = [
            'resInfo' => '没有找到有效数据样本',
            'resStatus' => 'INVALID',
            'socDiff' => '—'
        ];
        $returnArr['detect2'] = [
            'resInfo' => '没有找到有效数据样本',
            'resStatus' => 'INVALID',
            'volDiff' => '—'
        ];
        $returnArr['used_history_data'] = '';
        $returnArr['detect3'] = [
            'resInfo' => '没有找到有效数据样本',
            'resStatus' => 'INVALID',
        ];
        //下面共需要执行3种算法，都是基于“本月监控数据”作分析的。
        $connection = yii::$app->db1;
        $db = 'db1';
        $tableName = 'cs_tcp_car_history_data_' . date('Ym') . '_' . substr($carVin, -1);
        //$tableName = 'cs_tcp_car_history_data_201604_' . substr($carVin, -1); //测试用
        $tabRes = $connection->createCommand("SHOW TABLES LIKE '{$tableName}'")->queryAll();
        if (!$tabRes) {
            return ['status'=>false,'info'=>'该车对应的本月监控数据表尚不存在！','errCode'=>'ERR01'];
        }
        //===（一）【算法1】和【算法2】尝试查找本月监控数据直到找到符合算法1和2的电池单体电压平均值V范围的那一帧数据用作分析=========
        //分批按时间倒序获取该车辆的监控数据：
        //若本月找不到该车监控数据则直接退出循；或者循环查找直到找到符合算法1和2的电池单体电压平均值V范围的那一帧数据则也退出循环。
        $page = 1; $size = 50;
        while(1){
            $offset = ($page - 1) * $size;
            $page++;
            $dataA = (new \yii\db\Query())
                ->select(['id', 'car_vin', 'collection_datetime', 'data_hex'])
                ->from($tableName)
                ->where('car_vin = :car_vin',[':car_vin' => $carVin])
                ->orderBy('collection_datetime DESC')
                ->limit($size)->offset($offset)
                ->all($connection);
            if (!$dataA) {
                break; //本月找不到该车监控数据则直接退出while循环。
            }
            foreach($dataA as $dataARow){
                $analysisObj = new CarRealtimeDataAnalysis($dataARow['data_hex']); //解析数据
                $realtimeData = $analysisObj->getRealtimeData();
                if (!$realtimeData || !isset($realtimeData['battery_single_hv_value'])  || !isset($realtimeData['battery_single_lv_value'])) {
                    continue;
                }
                //求电池单体电压平均值V
                $stdClassObject = json_decode($realtimeData['battery_package_voltage']);
                $battteryVoltage = $stdClassObject->batteryPackage[0]->battteryVoltage;
                $validVoltage = [];
                foreach($battteryVoltage as $val){
                    if($val){
                        $validVoltage[] = $val;
                    }
                }
                if(!$validVoltage){
                    continue;
                }
                $avgV = round( (array_sum($validVoltage) / count($validVoltage)), 3); //电压保留3位小数
                //算法1：取出电压平均值V对应的SOC范围值Y
                if($realtimeData['battery_package_current'] < $criterion['I1']){ //充放电电流 < I1
                    if ($avgV >= $criterion['V1_S']/1000 && $avgV < $criterion['V1_E']/1000) {
                        $Y = [$criterion['Y1_S'], $criterion['Y1_E']];
                    } elseif ($avgV >= $criterion['V2_S']/1000 && $avgV < $criterion['V2_E']/1000) {
                        $Y = [$criterion['Y2_S'], $criterion['Y2_E']];
                    } elseif ($avgV >= $criterion['V3_S']/1000 && $avgV < $criterion['V3_E']/1000) {
                        $Y = [$criterion['Y3_S'], $criterion['Y3_E']];
                    } else {
                        continue;  //跳过循环而去分析下一帧
                    }
                }else{
                    continue;  //跳过循环而去分析下一帧
                }
                //算法2：取出电压平均值V对应的单体压差阀值A
                if ($avgV >= $criterion['V4_S']/1000 && $avgV < $criterion['V4_E']/1000) {
                    $A = $criterion['A1']/1000;
                } elseif ($avgV >= $criterion['V5_S']/1000 && $avgV < $criterion['V5_E']/1000) {
                    $A = $criterion['A2']/1000;
                } elseif ($avgV >= $criterion['V6_S']/1000 && $avgV < $criterion['V6_E']/1000) {
                    $A = $criterion['A3']/1000;
                } else {
                    continue;  //跳过循环而去分析下一帧
                }
                if (isset($Y) && isset($A)) {
                    //算法1：比较SOC值
                    $soc = $realtimeData['battery_package_soc'];
                    if ($soc >= $Y[0] && $soc <= $Y[1]) {
                        $returnArr['detect1'] = [
                            'resInfo' => 'SOC值偏移量处于正常范围',
                            'resStatus' => 'NORMAL',
                            'socDiff' => '0'
                        ];
                    } else {
                        if ($soc < $Y[0]) {
                            $socDiff = round( ($soc - $Y[0]), 2 );
                        } else {
                            $socDiff = round( ($soc - $Y[1]), 2 );
                        }
                        $returnArr['detect1'] = [
                            'resInfo' => 'SOC值偏移量超出正常范围，需校准',
                            'resStatus' => 'ABNORMAL',
                            'socDiff' => $socDiff . '%'
                        ];
                    }
                    //算法2：比较电池单体最高最低电压差与阈值
                    $diffV = round( ($realtimeData['battery_single_hv_value'] - $realtimeData['battery_single_lv_value'])); //电压保留3位小数
                    if ($diffV <= $A) {
                        $returnArr['detect2'] = [
                            'resInfo' => '有效电能正常，车辆续航里程正常',
                            'resStatus' => 'NORMAL',
                            'volDiff' => "0"
                        ];
                    } else {
                        $volDiff = round( ($diffV - $A), 3 );
                        $returnArr['detect2'] = [
                            'resInfo' => '有效电能可能减少，车辆续航里程可能减少',
                            'resStatus' => 'ABNORMAL',
                            'volDiff' => "$volDiff"
                        ];
                    }
                    //保存本次检测所依赖的原始历史数据，以便查看原始监控数据详情（格式：数据库,数据表,记录id）
                    $returnArr['used_history_data'] = $db . ',' . $tableName . ',' . $dataARow['id'];
                }
            }
        }
        //print_r($returnArr);exit;

        //===（二）【算法3】尝试查找本月监控数据找到最新一次有效的充电记录用作分析=======================================
        //查该型号电池额定容量$Ah
        $batteryInfo = Battery::find()->where(['battery_model' => $car['battery_model']])->asArray()->one();
        $Ah = $batteryInfo['system_capacity'];
        if ($Ah <= 0) {
            return ['status'=>false,'info'=>'该车对应的电池系统额定容量必须设置大于0！','errCode'=>'ERR02'];
        }
        foreach ($tabRes as $tableInDb) {
            foreach ($tableInDb as $tableName) {
                //获取最后一次充电记录并进行相关计算
                $result = self::calculateLastCharge($criterion,$carVin,$connection,$tableName);
            }
        }
        if ($result) {
            //判断依据：当 |( C1 / 电池额定Ah) *100% - (SOC_B –SOC_A)| < X 为正常，否则为电池有衰减可能。
            $SOC_A = $result['SOC_A'];  //达到T1分钟时获取充电电流$SOC_A
            $SOC_B = $result['SOC_B'];  //经过T2分钟后获取充电电流$SOC_B
            $C1 = $result['C1'];        //充电电流积分值C1
            $X = $criterion['X'];       //SOC容量偏差百分比X
            if (abs( ($C1 / $Ah) - ($SOC_B - $SOC_A) ) < $X) {
                $returnArr['detect3'] = [
                    'resInfo' => '电池容量正常',
                    'resStatus' => 'NORMAL'
                ];
            } else {
                $returnArr['detect3'] = [
                    'resInfo' => '电池有衰减可能',
                    'resStatus' => 'ABNORMAL'
                ];
            }
        }
        return [
            'status' => true,
            'detectData' => $returnArr
        ];
    }

    /*
     *  获取最后一次有效充电记录并进行相关计算
     * @$criterion: 电池检测标准
     * @$carVin: 被检测车辆
     * @$connection: 监控数据库连接
     * @$tableName： 监控数据表
     */
    static function calculateLastCharge($criterion,$carVin,$connection,$tableName){
        //查找出该表中的充电的最后一次上报记录时间
        $lastRec = (new \yii\db\Query())
            ->select(['collection_datetime'])
            ->from($tableName)
            ->where('car_vin = :car_vin AND car_current_status = 2',[':car_vin' => $carVin])
            ->orderBy('collection_datetime DESC')
            ->limit(1)
            ->one($connection);
        if ($lastRec) {
            //查找出该表中的充电的第一次上报记录时间
            $firstRec = (new \yii\db\Query())
                ->select(['collection_datetime'])
                ->from($tableName)
                ->where('car_vin = :car_vin AND car_current_status = 2',[':car_vin' => $carVin])
                ->orderBy('collection_datetime ASC')
                ->limit(1)
                ->one($connection);
            //循环，每次往前查一天内的充电上报记录
            $firstRecStartTime = strtotime( date('Y-m-d 00:00:00',$firstRec['collection_datetime']) );
            $startTime = strtotime( date('Y-m-d 00:00:00',$lastRec['collection_datetime']) );
            $endTime   = $lastRec['collection_datetime'];
            while($startTime >= $firstRecStartTime){
                $dataB = (new \yii\db\Query())
                    ->select(['id', 'car_vin', 'collection_datetime', 'battery_package_soc', 'data_hex'])
                    ->from($tableName)
                    ->where(
                        'car_vin = :car_vin AND `collection_datetime` >= :collection_datetime_s AND `collection_datetime` <= :collection_datetime_e AND car_current_status = 2',
                        [
                            ':car_vin' => $carVin,
                            ':collection_datetime_s' => $startTime,
                            ':collection_datetime_e' => $endTime,
                        ]
                    )
                    ->orderBy('collection_datetime asc')
                    ->all($connection);
                if (!$dataB) {
                    continue;
                }
                //1.将所查上报记录按同一次充电记录分组组装成数组。
                //注意：因为一次充电过程会不断的上报数据，所以上报间隔固定秒数内都视为同一次充电过程。
                $recIds = [];
                $seconds = 300;  //上报间隔秒数
                foreach ($dataB as $key => $val) {
                    //先判断当前记录的前一条记录是否存在并且与当前记录上报时间间隔在范围内，若否，则当前记录为某一次充电开始记录；
                    if (isset($dataB[$key - 1]) && ($val['collection_datetime'] - $dataB[$key - 1]['collection_datetime']) < $seconds) {
                        array_push($recIds[count($recIds) - 1], $val['id']);
                    } else {
                        $recIds[] = [$val['id']];
                    }
                }
                //print_r($recIds);exit;
                //2.倒序获取最近有效的充电记录用作分析(至少有开始和结束2条上报记录)
                for ($i = count($recIds) - 1; $i >= 0; $i--) {
                    if (count($recIds[$i]) >= 2) {
                        $validChargeIds = $recIds[$i];
                        $dataC = [];
                        foreach($validChargeIds as $validId){
                            foreach($dataB as $dataBRow){
                                if($dataBRow['id'] == $validId){
                                    $dataC[] = $dataBRow;
                                    break;
                                }
                            }
                        }
                        //print_r($dataC);exit;
                        //达到T1分钟时，获取充电电流$SOC_A；再经过T2分钟后，获取充电电流$SOC_B。
                        //计算此间的充电电流积分值：C1 = I1* N1/3600 + I2* N2/3600 + … IX* NX/3600安时（Ah）（注意：每个子项都表示N秒内车辆充电的电量，电池包电流I1* N1/3600（秒换算成小时））
                        $chargeTime = $dataC[count($dataC) - 1]['collection_datetime'] - $dataC[0]['collection_datetime'];
                        $T1 = $criterion['T1'];
                        $T2 = $criterion['T2'];
                        if ($chargeTime >= ($T1+$T2) * 60) { //充电时间>=($T1+$T2)分钟
                            $SOCArr = []; //每帧的SOC值
                            $C1 = 0;
                            foreach ($dataC as $key=>$row) {
                                if(isset($dataC[$key+1])){
                                    if(($row['collection_datetime'] - $dataC[0]['collection_datetime']) >= $T1 * 60){
                                        $SOCArr[] = $row['battery_package_soc'];
                                        if(($row['collection_datetime'] - $dataC[0]['collection_datetime']) > ($T1+$T2) * 60){
                                            break; //经过T2分钟后，则退出循环
                                        }
                                        //解析数据
                                        $analysisObj = new CarRealtimeDataAnalysis($dataC[$key+1]['data_hex']);
                                        $realtimeData = $analysisObj->getRealtimeData();
                                        if ($realtimeData) {
                                            $I = $realtimeData['battery_package_current'];
                                            $N = $dataC[$key+1]['collection_datetime'] - $row['collection_datetime'];
                                            $C1 += $I * $N / 3600;
                                        }
                                    }
                                }
                            }
                            if($SOCArr){ //取到可用数据则直接返回
                                return [
                                    'status'=>true,
                                    'SOC_A'=>$SOCArr[0],
                                    'SOC_B'=>$SOCArr[count($SOCArr)-1],
                                    'C1'=>$C1
                                ];
                            }
                        }
                    }
                }
                //查往前一天的充电上报记录
                $endTime = $startTime;
                $startTime = $startTime - 3600 * 24;
            }
        }
        return false;
    }





}
