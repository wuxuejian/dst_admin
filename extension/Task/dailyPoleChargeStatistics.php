<?php
$config = [
    //车辆管理系统数据库
    'db_car_system'=>[
        'dbname'=>'car_system',
        'host'=>'rm-wz9k9ct8av553n1jt.mysql.rds.aliyuncs.com',
        'user'=>'user_carsystem',
        'pwd'=>'CLY7dzc8WRUQ'
    ],
    //前置机数据库
    'db_cloucdz'=>[
	    'dbname'=>'dstcdz',
	    'host'=>'120.76.41.26',
	    'user'=>'root',
	    'pwd'=>'Dst20161201@Cdz.com'
    ],
    'logFile'=>dirname(__FILE__).'/dailyPoleChargeStatistics.php.log',//日志文件
];

//功能函数
function writeLog($log)
{
    global $config;
    $log = date('Y-m-d H:i:s').' '.$log."\n";
    file_put_contents($config['logFile'],$log,FILE_APPEND);
    echo $log,"\n";
}


/*
 * 按日统计每个充电桩的“APP”或“电卡”的充电量/充电金额/充电次数，并保存到数据库中。
 * @$dbhSystem：数据库连接资源
 * @$dbCloucdz：前置机数据库连接资源
 * @$logicAddr：充电桩逻辑地址数组
 * @$type: 区分“APP”或“电卡”
 * 注意：电卡编号共16位数，而其中“999000...”+“会员ID”形式的电卡即为app充电记录，其他为IC电卡充电记录
 */
function dailyPoleChargeStatistics($dbhSystem,$dbCloucdz,$logicAddr,$type){
    switch($type){
        case 'app':
            $cardNoCon = " `START_CARD_NO` LIKE '999000%' ";
            $tableName = 'cs_report_pole_charge_statistics_app';
            break;
        case 'card':
            $cardNoCon = " `START_CARD_NO` NOT LIKE '999000%' ";
            $tableName = 'cs_report_pole_charge_statistics_card';
            break;
    }
    if(!isset($cardNoCon)){
        die;
    }
    $logicAddrStr = "'" . join("','",$logicAddr) . "'";
    $startTime_YmdHis = date('Y-m-d 00:00:00',strtotime('-1 day')); //从前一天开始统计即可
    $timeTagCon = " `TIME_TAG` >= '{$startTime_YmdHis}' ";  // 下面查询语句中若不加上此时间条件则是查询更新所有历史记录 AND {$timeTagCon}
    //注意：因为上报的数据本身有一些异常记录，所以下面统计时只将充电量和充电消费金额皆为非负值的记录才算为一次有效充电记录。
    $sql = "SELECT
                SUM(CASE WHEN ((`END_DEAL_DL` - `START_DEAL_DL`) >= 0 AND (`REMAIN_BEFORE_DEAL` - `REMAIN_AFTER_DEAL`) >= 0) THEN  (`END_DEAL_DL` - `START_DEAL_DL`) ELSE 0 END) AS chargeKwh,
                SUM(CASE WHEN ((`END_DEAL_DL` - `START_DEAL_DL`) >= 0 AND (`REMAIN_BEFORE_DEAL` - `REMAIN_AFTER_DEAL`) >= 0) THEN  (`REMAIN_BEFORE_DEAL` - `REMAIN_AFTER_DEAL`)ELSE 0 END) AS chargeMoney,
                SUM(CASE WHEN ((`END_DEAL_DL` - `START_DEAL_DL`) >= 0 AND (`REMAIN_BEFORE_DEAL` - `REMAIN_AFTER_DEAL`) >= 0) THEN 1 ELSE 0 END) AS chargeTimes,
                LEFT(`TIME_TAG`,10) AS chargeTime,
                `DEV_ADDR`
            FROM `charge_record`
            LEFT JOIN `charge_pole` ON `charge_record`.`DEV_ID` = `charge_pole`.`DEV_ID`
            WHERE `DEV_ADDR` IN ({$logicAddrStr}) AND `DEAL_TYPE` IN(1,2) AND {$cardNoCon} AND {$timeTagCon}
            GROUP BY `DEV_ADDR`, `chargeTime`
            ORDER BY `DEV_ADDR` ASC, `chargeTime` ASC ";
    $sth = $dbCloucdz->prepare($sql); //从前置机数据库查充电统计
    $sth->execute();
    $records = $sth->fetchAll(PDO::FETCH_ASSOC);
    $dailyArr = [];
    if($records){
        foreach($records as $record){
            $yearMonth = substr($record['chargeTime'],0,7);
            $day = substr($record['chargeTime'],-2);
            $dailyArr[$record['DEV_ADDR']][$yearMonth][$day] = $record['chargeKwh'] . '|' . $record['chargeMoney'] . '|' . $record['chargeTimes'];
        }
    }
    //print_r($dailyArr);exit;
    if($dailyArr){
        foreach($dailyArr as $DEV_ADDR=>$poleItem){  //遍历每一个充电桩
            foreach($poleItem as $yearMonth=>$item){ //遍历每一个年月的充电情况，向数据表中插入或更新纪录，并更新该月合计金额
                $sql = "SELECT `id` FROM `{$tableName}` WHERE `logic_addr` = '{$DEV_ADDR}' AND `year_month` = '{$yearMonth}' ";
                $sth = $dbhSystem->prepare($sql);
                $sth->execute();
                $hasRecord = $sth->fetchAll(PDO::FETCH_ASSOC);
                if($hasRecord){
                    $sql = "UPDATE `{$tableName}` SET ";
                    $tmpArr = [];
                    foreach($item as $day=>$kwhMoneyTimes){
                        $tmpArr[] = "`day_{$day}` = '{$kwhMoneyTimes}' ";
                    }
                    $sql .= join(',',$tmpArr) . " WHERE `logic_addr` = '{$DEV_ADDR}' AND `year_month` = '{$yearMonth}' ";
                    $sth = $dbhSystem->prepare($sql);
                    $sth->execute();
                    writeLog($sql);
                }else{
                    $days = [
                        '01','02','03','04','05','06','07','08','09','10','11','12','13','14','15',
                        '16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'
                    ];
                    $tmpArr = [];
                    foreach($days as $day){
                        if(isset($item[$day])){
                            $tmpArr[] = $item[$day];
                        }else{
                            $tmpArr[] = '';
                        }
                    }
                    $sql = "INSERT INTO `{$tableName}`(
                              `id`,`logic_addr`,`year_month`,
                              `day_01`,`day_02`,`day_03`,`day_04`,`day_05`,`day_06`,`day_07`,`day_08`,`day_09`,`day_10`,
                              `day_11`,`day_12`,`day_13`,`day_14`,`day_15`,`day_16`,`day_17`,`day_18`,`day_19`,`day_20`,
                              `day_21`,`day_22`,`day_23`,`day_24`,`day_25`,`day_26`,`day_27`,`day_28`,`day_29`,`day_30`,`day_31`,`month_total`)
                            VALUES (NULL,'{$DEV_ADDR}','{$yearMonth}','" . join("','",$tmpArr) . "','') ";
                    $sth = $dbhSystem->prepare($sql);
                    $sth->execute();
                    writeLog($sql);
                }
                //更新该月合计金额
                $sql = "SELECT * FROM `{$tableName}` WHERE `logic_addr` = '{$DEV_ADDR}' AND `year_month` = '{$yearMonth}'";
                $sth = $dbhSystem->prepare($sql);
                $sth->execute();
                $result = $sth->fetch(PDO::FETCH_ASSOC);
                if($result){
                    unset($result['id']);
                    unset($result['logic_addr']);
                    unset($result['year_month']);
                    unset($result['month_total']);
                    $totalKwh = 0;
                    $totalMoney = 0;
                    $totalTimes = 0;
                    foreach($result as $val){
                        if($val){
                            list($k,$m,$t) = explode('|',$val);
                            $totalKwh   += $k;
                            $totalMoney += $m;
                            $totalTimes += $t;
                        }
                    }
                    if($totalKwh || $totalMoney || $totalTimes){
                        $monthTotal = $totalKwh . '|' . $totalMoney . '|' . $totalTimes;
                        $sql = "UPDATE `{$tableName}` SET `month_total` = '{$monthTotal}' WHERE `logic_addr` = '{$DEV_ADDR}' AND `year_month` = '{$yearMonth}'";
                        $sth = $dbhSystem->prepare($sql);
                        $sth->execute();
                        writeLog($sql);
                    }
                }
            }
        }
    }
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
    $dsn = "mysql:dbname={$config['db_cloucdz']['dbname']};host={$config['db_cloucdz']['host']}";
    try {
        $dbCloucdz = new \PDO($dsn, $config['db_cloucdz']['user'], $config['db_cloucdz']['pwd'],[
            \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
            \PDO::ATTR_PERSISTENT=>true//长链接
        ]);
    } catch (PDOException $e) {
        writeLog('Connection failed: ' . $e->getMessage());
        die;
    }
    set_time_limit(0);
    //---查询出所有充电站的所有有效充电桩的逻辑地址，以便去前置机数据库查对应充电记录--------
    $sql = "SELECT `logic_addr`
            FROM `cs_charge_spots`
            LEFT JOIN `cs_charge_station` ON `cs_id` = `station_id`
            WHERE `is_del` = 0 AND `fm_id` = 1 AND `logic_addr` != '' AND `cs_is_del` = 0 AND `cs_status` != 'STOPPED'";
    $sth = $dbhSystem->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    $logicAddr = array_column($result, 'logic_addr');
    if(!$logicAddr){
        die;
    }
    //---每日统计每一个充电桩的“APP”和“电卡”的充电量/充电金额/充电次数-------
    dailyPoleChargeStatistics($dbhSystem,$dbCloucdz,$logicAddr,'app');
    dailyPoleChargeStatistics($dbhSystem,$dbCloucdz,$logicAddr,'card');
    writeLog('Complete statistics.');
}
main();