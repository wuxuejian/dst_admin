<?php
$config = [
    //车辆管理系统数据库
    'db_car_system'=>[
        'dbname'=>'car_system',
        // 'host'=>'127.0.0.1',
        // 'user'=>'root',
        // 'pwd'=>''
        'host'=>'rm-wz9k9ct8av553n1jt.mysql.rds.aliyuncs.com',
        'user'=>'user_carsystem',
        'pwd'=>'CLY7dzc8WRUQ'
    ],
  
    'logFile'=>dirname(__FILE__).'/chargeCardMsgTrack.php.log',//日志文件
];

//功能函数
function writeLog($log)
{
    global $config;
    $log = date('Y-m-d H:i:s').' '.$log."\n";
    file_put_contents($config['logFile'],$log,FILE_APPEND);
    echo $log,"\n";
}

require('../../common/classes/CarRealtimeDataAnalysis.php');
require('../taobao-sdk-PHP-shot-message/TopSdk.php');

/**
 * 入口
 */
function main(){
    global $config;
    //链接两端的数据库
    $dsn = "mysql:dbname={$config['db_car_system']['dbname']};host={$config['db_car_system']['host']}";
    try {
        $dbhSystem = new \PDO($dsn, $config['db_car_system']['user'], $config['db_car_system']['pwd'],[
            \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
            \PDO::ATTR_PERSISTENT=>false//长链接
        ]);
    } catch (PDOException $e) {
        writeLog('Connection failed: ' . $e->getMessage());
        die;
    }
   
    set_time_limit(0);
	
	$sql = "SELECT `cs_car_insurance_claim`.id,
					`cs_car_insurance_claim`.pay_text,
					`cs_car_insurance_claim`.damaged_text
            FROM `cs_car_insurance_claim`			
           	
			";				
	$sth = $dbhSystem->prepare($sql);
	$sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
	
    if(!$result){
        die;
    }
	
	foreach ($result as $one) {
		$id = $one['id'];
		$rent_amount = 0;
		$damaged_money2 = 0;
		if ($one['pay_text'] != null && $one['pay_text'] != '') {
			$the_arr = json_decode($one['pay_text']);
			if ($the_arr && isset($the_arr[0]->rent_amount) && $the_arr[0]->rent_amount != '' && $the_arr[0]->rent_amount != 0) {				
				$rent_amount = $the_arr[0]->rent_amount;					
			}
		}
		if ($one['damaged_text'] != null && $one['damaged_text'] != '') {
			$the_arr2 = json_decode($one['damaged_text']);
			if ($the_arr2 && isset($the_arr2[0]->damaged_money2) && $the_arr2[0]->damaged_money2 != '' && $the_arr2[0]->damaged_money2 != 0) {				
				$damaged_money2 = $the_arr2[0]->damaged_money2;					
			}
		}
		$sql = "UPDATE `cs_car_insurance_claim`
						SET rent_amount=$rent_amount,
							damaged_money2=$damaged_money2
						WHERE id=$id		
           	
				";				
		$sth = $dbhSystem->prepare($sql);
		$sth->execute();		
	}
    writeLog('Complete chargeCardMsg.');
}
main();