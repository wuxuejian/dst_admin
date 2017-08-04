<?php
$config = [
    //车辆管理系统数据库
    'db_car_system'=>[
	    'dbname'=>'car_system',
	    'host'=>'rm-wz9k9ct8av553n1jt.mysql.rds.aliyuncs.com',
	    'user'=>'user_carsystem',
	    'pwd'=>'CLY7dzc8WRUQ'
    ],
    'logFile'=>dirname(__FILE__).'/dailyRechargeStatistics.php.log',//日志文件
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
 * 按日统计“APP”或“电卡”的充值金额，并保存到数据库中。
 * @$dbhSystem：数据库连接资源
 * @$type: 区分“APP”或“电卡”
 */
function dailyRechargeStatistics($dbhSystem,$type){
    $startTime_YmdHis = date('Y-m-d 00:00:00',strtotime('-1 day')); //从前一天开始统计即可
    $startTime_seconds = strtotime($startTime_YmdHis);
    switch($type){
        case 'app':
            $sql = "SELECT SUM(`total_fee`) AS rechargeMoney,COUNT(`total_fee`) AS rechargeTimes,LEFT(FROM_UNIXTIME(`gmt_payment_datetime`),10) AS rechargeTime FROM `cs_vip_recharge_record` WHERE `trade_status` = 'success' AND `gmt_payment_datetime` > {$startTime_seconds} GROUP BY rechargeTime ORDER BY rechargeTime ASC";
            $tableName = 'cs_report_recharge_statistics_app';
            break;
        case 'card':
            $sql = "SELECT SUM(`ccrr_recharge_money`) AS rechargeMoney,COUNT(`ccrr_recharge_money`) AS rechargeTimes,LEFT(`ccrr_create_time`,10) AS rechargeTime FROM `cs_charge_card_recharge_record` WHERE `ccrr_is_del` = 0 AND `write_status` = 'success' AND `ccrr_create_time` > '{$startTime_YmdHis}' GROUP BY rechargeTime ORDER BY rechargeTime ASC";
            $tableName = 'cs_report_recharge_statistics_card';
            break;
    }
    $sth = $dbhSystem->prepare($sql);
    $sth->execute();
    $records = $sth->fetchAll(PDO::FETCH_ASSOC);
    $dailyArr = [];
    if($records){
        foreach($records as $record){
            $yearMonth = substr($record['rechargeTime'],0,7);
            $day = substr($record['rechargeTime'],-2);
            $dailyArr[$yearMonth][$day] = $record['rechargeMoney'] . '|' . $record['rechargeTimes'];
        }
    }
    //print_r($dailyArr);exit;
    if($dailyArr){
        //遍历每一个年月的充值情况，向数据表中插入或更新纪录，并更新该月合计金额
        foreach($dailyArr as $yearMonth=>$item){
            $sql = "SELECT `id` FROM `{$tableName}` WHERE `year_month` = '{$yearMonth}' ";
            $sth = $dbhSystem->prepare($sql);
            $sth->execute();
            $hasRecord = $sth->fetchAll(PDO::FETCH_ASSOC);
            if($hasRecord){
                $sql = "UPDATE `{$tableName}` SET ";
                $tmpArr = [];
                foreach($item as $day=>$moneyTimes){
                    $tmpArr[] = "`day_{$day}` = '{$moneyTimes}' ";
                }
                $sql .= join(',',$tmpArr) . " WHERE `year_month` = '{$yearMonth}' ";
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
                  `id`,`year_month`,
                  `day_01`,`day_02`,`day_03`,`day_04`,`day_05`,`day_06`,`day_07`,`day_08`,`day_09`,`day_10`,
                  `day_11`,`day_12`,`day_13`,`day_14`,`day_15`,`day_16`,`day_17`,`day_18`,`day_19`,`day_20`,
                  `day_21`,`day_22`,`day_23`,`day_24`,`day_25`,`day_26`,`day_27`,`day_28`,`day_29`,`day_30`,`day_31`,`month_total`)
                VALUES (NULL,'{$yearMonth}','" . join("','",$tmpArr) . "','') ";
                $sth = $dbhSystem->prepare($sql);
                $sth->execute();
                writeLog($sql);
            }
            //更新该月合计金额
            $sql = "SELECT * FROM `{$tableName}` WHERE  `year_month` = '{$yearMonth}'";
            $sth = $dbhSystem->prepare($sql);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            if($result){
                unset($result['id']);
                unset($result['year_month']);
                unset($result['month_total']);
                $totalMoney = 0;
                $totalTimes = 0;
                foreach($result as $val){
                    if($val){
                        list($m,$t) = explode('|',$val);
                        $totalMoney += $m;
                        $totalTimes += $t;
                    }
                }
                if($totalMoney || $totalTimes){
                    $monthTotal = $totalMoney . '|' . $totalTimes;
                    $sql = "UPDATE `{$tableName}` SET `month_total` = '{$monthTotal}' WHERE  `year_month` = '{$yearMonth}'";
                    $sth = $dbhSystem->prepare($sql);
                    $sth->execute();
                    writeLog($sql);
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
    set_time_limit(0);
    //---每日统计“APP”和“电卡”充值金额/充值次数-------
    dailyRechargeStatistics($dbhSystem,'app');
    dailyRechargeStatistics($dbhSystem,'card');
    writeLog('Complete statistics.');
}
main();