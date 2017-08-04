<?php
require('../../common/classes/CarRealtimeDataAnalysis.php');
$config = [
    //车辆管理系统数据库
    'db_car_system'=>[
        'dbname'=>'car_system',
        /*'host'=>'120.76.114.155',
        'user'=>'szdst',
        'pwd'=>'szdst123'*/
        'host'=>'rm-wz9k9ct8av553n1jt.mysql.rds.aliyuncs.com',
        'user'=>'user_carsystem',
        'pwd'=>'CLY7dzc8WRUQ'
    ],
    //车辆监控数据库
    'db_car_monidata'=>[
        'dbname'=>'car_monidata',
        /*'host'=>'120.76.114.155',
        'user'=>'szdst',
        'pwd'=>'szdst123'*/
        'host'=>'localhost',
        'user'=>'root',
        'pwd'=>'4Z3uChwl'
    ],
    'logFile'=>'./checkIsCorrectSocBySlowCharge.php.log',//日志文件
];

//功能函数
function writeLog($log)
{
    global $config;
    $log = date('Y-m-d H:i:s').' '.$log."\n";
    file_put_contents($config['logFile'],$log,FILE_APPEND);
    echo $log,"\n";
}


/**
 * 入口
 */
function main(){
    global $config;
    //链接数据库
    $dsn = "mysql:dbname={$config['db_car_system']['dbname']};host={$config['db_car_system']['host']}";
    try {
        $dbhSystem = new \PDO($dsn, $config['db_car_system']['user'], $config['db_car_system']['pwd'],[
            \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
            \PDO::ATTR_PERSISTENT=>true//长链接
        ]);
    } catch (PDOException $e) {
        writeLog('Connection failed: ' . $e->getMessage());
        die;
    }
    $dsn = "mysql:dbname={$config['db_car_monidata']['dbname']};host={$config['db_car_monidata']['host']}";
    try {
        $dbhMonidata = new \PDO($dsn, $config['db_car_monidata']['user'], $config['db_car_monidata']['pwd'],[
            \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
            \PDO::ATTR_PERSISTENT=>true//长链接
        ]);
    } catch (PDOException $e) {
        writeLog('Connection failed: ' . $e->getMessage());
        die;
    }
    set_time_limit(0);
    //===（1）查找车辆尚未执行修正的最新的通知================
    $sql = 'SELECT `id`,`car_vin`,`notice_time` FROM `cs_battery_correct_notice` WHERE `is_del` = 0 AND `is_corrected` = 0 ORDER BY notice_time DESC,modify_time DESC';
    $sth = $dbhSystem->prepare($sql);
    $sth->execute();
    $notices = $sth->fetchAll(PDO::FETCH_ASSOC);
    if(!$notices){
        die;
    }
    $carNotice = [];
    foreach($notices as $item){
        if(!isset($carNotice[$item['car_vin']])){
            $carNotice[$item['car_vin']] = $item['notice_time'];
        }
    }
    //print_r($carNotice);exit;
    //===（2）遍历每辆车，查该通知时间以来是否有过慢充修正SOC=======
    $correctedCars = [];
    foreach($carNotice as $carVin=>$noticeTime){
        //获取车辆电池检测标准
        $sql = "SELECT a.* FROM `cs_battery_detect_criteria` AS a LEFT JOIN `cs_battery_detection` AS b ON a.`battery_type` = b.`battery_type` WHERE a.`is_del` = 0 AND b.`car_vin` = '{$carVin}'";
        $sth = $dbhSystem->prepare($sql);
        $sth->execute();
        $criterion = $sth->fetch(PDO::FETCH_ASSOC);
        if(!$criterion){
            continue;
        }
        //要查询的数据库及数据表
        $tableName = 'cs_tcp_car_history_data_'.date('Ym').'_'.substr($carVin,-1);
        $sql = "SHOW TABLES LIKE '{$tableName}'";
        $sth = $dbhMonidata->prepare($sql);
        $sth->execute();
        $tabRes = $sth->fetch(PDO::FETCH_ASSOC);
        if(!$tabRes){
            continue; //退出并执行下次循环
        }
        //每次查往后一天内的充电记录
        $startTime = strtotime($noticeTime.' 00:00:00');
        $endTime = $startTime + 3600*24;
        $deadline = strtotime('now');
        while ($endTime <= $deadline) {
            $sql = "SELECT `id`,`car_vin`,`collection_datetime` FROM {$tableName} WHERE `car_vin` = '{$carVin}' AND car_current_status = 2 AND `collection_datetime` >= '{$startTime}' AND `collection_datetime` <= '{$endTime}' ORDER BY collection_datetime ASC;";
            $sth = $dbhMonidata->prepare($sql);
            $sth->execute();
            $dataX = $sth->fetchAll(PDO::FETCH_ASSOC);
            if (!isset($dataX) || !$dataX) {
                continue;
            }
            //1.将这段时间内的上报记录按同一次充电过程进行分组。
            //注意：因为一次充电过程会不断的上报数据，所以上报间隔固定秒数内都视为同一次充电过程。
            $recIds = [];
            $seconds = 300;  //上报间隔秒数
            foreach ($dataX as $key => $val) {
                //先判断当前记录的前一条记录是否存在并且与当前记录上报时间间隔在范围内，若否，则当前记录为某一次充电开始记录；
                if (isset($dataX[$key - 1]) && ($val['collection_datetime'] - $dataX[$key - 1]['collection_datetime']) < $seconds) {
                    array_push($recIds[count($recIds) - 1], $val['id']);
                } else {
                    $recIds[] = [$val['id']];
                }
            }
            unset($dataX);
            if (!$recIds) {
                continue;
            }
            //print_r($recIds);exit;
            //2.判断每个有效的充电过程（至少有开始和结束2条上报记录）是否属于慢充修正SOC
            for($i=count($recIds)-1; $i>=0; $i--){
                if (count($recIds[$i]) >= 2) {
                    $validChargeIds = $recIds[$i];
                    $validChargeIdStr = join("','",$validChargeIds);
                    //3.查该次充电过程的所有上报记录
                    $sql = "SELECT `id`,`car_vin`,`collection_datetime`,`data_hex` FROM {$tableName} WHERE `id` IN('{$validChargeIdStr}') ORDER BY collection_datetime ASC;";
                    $sth = $dbhMonidata->prepare($sql);
                    $sth->execute();
                    $dataY = $sth->fetchAll(PDO::FETCH_ASSOC);
                    //print_r($dataY);exit;

                    //---算法判断：-------------------------------------------
                    //单体电池平均电压 < V7 , T3分钟内的充电电流最大值 > I2 A,则判断为慢充（算法四）
                    //在慢充条件下，记录充电过程中的单体最高电池电压 ≥V8 阀值，判定用户已对车辆电池用慢充桩满充电（算法五）
                    $T3 = $criterion['T3'];
                    $I2 = $criterion['I2'];
                    $endChargeTime = $dataY[count($dataY) - 1]['collection_datetime'];
                    $chargeTime = $endChargeTime - $dataY[0]['collection_datetime'];
                    //充电时长<T3分钟
                    if($chargeTime < $T3 * 60){
                        continue; //进行下次循环
                    }
                    $IArr = [];    //每帧的充电电流
                    $avgVArr = []; //每帧的单体电池平均电压
                    $hVArr = [];   //每帧的单体电池最高电压
                    $isBreak = false;
                    //解析每帧数据
                    foreach($dataY as $row){
                        $analysisObj = new \common\classes\CarRealtimeDataAnalysis($row['data_hex']);
                        $realtimeData = $analysisObj->getRealtimeData();
                        $IArr[] = $realtimeData['battery_package_current'];
                        //达到T3分钟时，若判断充电电流最大值>I2不成立，则退出循环
                        if(($row['collection_datetime'] - $dataY[0]['collection_datetime']) > $T3 * 60){
                            if(max($IArr) <= $I2){
                                $isBreak = true;
                                break;
                            }
                        }
                        $avgVArr[] = ($realtimeData['battery_single_hv_value'] + $realtimeData['battery_single_lv_value']) / 2;
                        $hVArr[] = $realtimeData['battery_single_hv_value'];
                    }
                    if($isBreak){
                        continue; //进行下次循环
                    }
                    //print_r($hVArr);exit;
                    //若判定是执行了慢充修正SOC，则去修改对应的修正通知的已执行慢充修正字段
                    $V7 = $criterion['V7'];
                    $V8 = $criterion['V8'];
                    $avgV = array_sum($avgVArr) / count($avgVArr);
                    if($avgV<$V7 && max($hVArr)>=$V8){
                        $endYmd = date('Y-m-d',$endChargeTime);
                        $endYmdHis = date('Y-m-d H:i:s',$endChargeTime);
                        $sql = "UPDATE `cs_battery_correct_notice` SET `is_corrected` = 1,`correct_time` = '{$endYmdHis}' WHERE `notice_time` <= '{$endYmd}' AND `modify_time` <= '{$endYmdHis}';";
                        $sth = $dbhSystem->prepare($sql);
                        $sth->execute();
                        $correctedCars[] = $carVin;
                        $isEndFor = true;
                    }
                    if(isset($isEndFor) && $isEndFor){
                        $isEndWhile = true;
                        break; //退出for循环
                    }
                }
            }
            if(isset($isEndWhile) && $isEndWhile){
                break; //退出while循环
            }
            //每次查往后一天内的充电
            $startTime = $endTime;
            $endTime += 3600*24;
        }
    }
    if(!empty($correctedCars)){
        writeLog('Corrected:'.join(',',$correctedCars));
    }else{
        writeLog('No car was corrected.');
    }
}
main();