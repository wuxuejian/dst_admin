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
    //前置机数据库
    'db_cloucdz'=>[
        'dbname'=>'dstcdz',
        'host'=>'120.76.41.26',
        'user'=>'root',
        'pwd'=>'Dst20161201@Cdz.com'
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
//发送短信
function send($type,$params,$mobile) {	
	//发送
// 	$aliDaYuDir = '/data/wwwroot/DST/extension/taobao-sdk-PHP-shot-message';
// 	include_once($aliDaYuDir.'/TopSdk.php');
	$c = new TopClient();
	// true account of dst
	$c->appkey = '23318373';
	$c->secretKey = 'ac1303f029af0aa1dcbf1e0209a49ec2';
	$req = new AlibabaAliqinFcSmsNumSendRequest();
	
	$req->setSmsType("normal");	
	$req->setSmsFreeSignName($type[0]);
	$req->setSmsParam(json_encode($params));
	$req->setRecNum($mobile);
	$req->setSmsTemplateCode($type[1]);
	$result = $c->execute($req)->result;

	if($result->err_code == 0 && $result->success == 'true')    //短信发送成功
	{
		return true;
	}else{
		return false;
	}
}

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
    $dsn = "mysql:dbname={$config['db_cloucdz']['dbname']};host={$config['db_cloucdz']['host']}";
    try {
        $dbCloucdz = new \PDO($dsn, $config['db_cloucdz']['user'], $config['db_cloucdz']['pwd'],[
            \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
            \PDO::ATTR_PERSISTENT=>false//长链接
        ]);
    } catch (PDOException $e) {
        writeLog('Connection failed: ' . $e->getMessage());
        die;
    }
    set_time_limit(0);
	
	//查询出所有已丢失的充电卡
	$sql = "SELECT `cs_charge_card`.cc_code
            FROM `cs_charge_card`			
            WHERE cc_status='LOSE'			
			";				
	$card_pre = $dbhSystem->prepare($sql);
	$card_pre->execute();
	$card_res = $card_pre->fetchAll(PDO::FETCH_ASSOC);
	$card_ids = ""; //卡号
	//结果集转字符串
	if(!$card_res){
        die;
    }
	foreach ($card_res as $card_id) {
		$card_ids .= "'".$card_id['cc_code']."',";			
	}	
	$card_ids = trim($card_ids);
	$card_ids = substr($card_ids, 0, strlen($card_ids)-1);
		
    //查询已丢失的充电卡是否在充电	
    $sql = "SELECT `charge_record`.START_CARD_NO,`charge_pole`.DEV_NAME,`charge_station`.CS_NAME
            FROM `charge_record`
			LEFT JOIN `charge_pole` ON `charge_record`.`DEV_ID` = `charge_pole`.`DEV_ID`			
			LEFT JOIN `charge_station` ON `charge_station`.`CS_ID` = `charge_pole`.`CS_ID`			
            WHERE TIME_TAG > '".date('Y-m-d H:i:s',strtotime('-1 day'))."' 
			  AND START_CARD_NO in($card_ids)
			  AND DEAL_TYPE = 0"			
			;   	
	$sth = $dbCloucdz->prepare($sql);
	
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    if(!$result){
        die;
    }
	//遍历结果集每个充电卡发送短信
	$mobile = '15817462257,18576771708';//正式代码
	foreach ($result as $card) {
		$params = array(
				'start_card_no'=>$card['START_CARD_NO'],
				'cs_name'=>$card['CS_NAME'],
				'dev_name'=>$card['DEV_NAME']
		);
		//$params = array();
		if(!send(array('地上铁租车','SMS_36580037'),$params,$mobile)){
			continue;
		}		
	}
    writeLog('Complete chargeCardMsg.');
}
main();