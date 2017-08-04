<?php
/*
 * 企业租金催缴短信通知，后台触发执行
 */
class CompanySmsNotify {
    protected static $config = [
        'db_car_system'=>[
        'dbname'=>'car_system',
        'host'=>'rm-wz9k9ct8av553n1jt.mysql.rds.aliyuncs.com',
        'user'=>'user_carsystem',
        'pwd'=>'CLY7dzc8WRUQ'//123456
        ]
    ];
    protected $logFile;         //记录日志文件
    protected $dbhSystem;   


    public function __construct(){
        //记录日志文件
        $this->logFile = dirname(__FILE__).'/log/companySmsNotify.php.log';
        try {
        	$dbConfig = self::$config['db_car_system'];
        	$dsn = "mysql:dbname=". $dbConfig['dbname'] .";host=" . $dbConfig['host'];
        	$this->dbhSystem = new \PDO($dsn, $dbConfig['user'],$dbConfig['pwd'],[
        			\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
        			\PDO::ATTR_PERSISTENT=>true, //长链接
        			\PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
        			]);
        } catch (PDOException $e) {
        	$this->writeLog('Connection failed: ' . $e->getMessage());
        	die;
        }
    }


    /*
     * 记录日志
     */
    function writeLog($log){
        $log = date('Y-m-d H:i:s') . ' ' . $log . "\n";
        file_put_contents($this->logFile, $log, FILE_APPEND);
    }

    /*
     * 入口
     */
    function main(){
//     	error_reporting(0);
    	//获取未发送，未删除的通知
		$sql = "SELECT * FROM cs_company_sms_notify WHERE is_send = 0 and is_del=0 and keeper_mobile<>''";
		$sth = $this->dbhSystem->prepare($sql);
		$sth->execute();
		$data = $sth->fetchAll(PDO::FETCH_ASSOC);
		if(count($data)==0){
    		return false;
		}
		//循环发送通知
		foreach ($data as $row){
			if(!$row['keeper_mobile']){
				continue;
			}
// 			$row['keeper_mobile'] = '18576771708';
			$params = array(
					'number'=>$row['car_num'],
					'rent'=>$row['amount'],
					'date'=>$row['delivery_time']
			);
			if(!$this->send(array('地上铁租车','SMS_11440041'),$params,$row['keeper_mobile'])){
				continue;
			}
			//修改通知状态为已发送
			$cur_time = time();
			$sql = "update cs_company_sms_notify set is_send=1,send_time={$cur_time} where id={$row['id']}";
			$this->dbhSystem->prepare($sql)->execute();
			//发送记录
			$send_time = date("Y-m-d H:i:s");
			$sql = "insert into cs_company_sms_notify_log(
				company_number,company_name,car_num,amount,delivery_time,
				keeper_name,keeper_mobile,send_time,oper_user) 
				values('{$row['company_number']}','{$row['company_name']}',{$row['car_num']},
					{$row['amount']},'{$row['delivery_time']}','{$row['keeper_name']}',
					'{$row['keeper_mobile']}','{$send_time}','{$row['oper_user']}')";
			$this->dbhSystem->prepare($sql)->execute();
// 			exit;
		}
    }
    //发送短信
    function send($type,$params,$mobile){
//     	return true;
    	$aliDaYuDir = '/data/wwwroot/DST/extension/taobao-sdk-PHP-shot-message';
    	include_once($aliDaYuDir.'/TopSdk.php');
    	$c = new TopClient();
    	// true account of dst
    	$c->appkey = '23318373';
    	$c->secretKey = 'ac1303f029af0aa1dcbf1e0209a49ec2';
    	$req = new AlibabaAliqinFcSmsNumSendRequest();
    	//$req->setExtend("123456");
    	$req->setSmsType("normal");
    	$req->setSmsFreeSignName($type[0]);
    	$req->setSmsParam(json_encode($params));
    	$req->setRecNum($mobile);
    	$req->setSmsTemplateCode($type[1]);
    	//return $resp = $c->execute($req)->result;
    	$result = $c->execute($req)->result;
    
    	if($result->err_code == 0 && $result->success == 'true')    //短信发送成功
    	{
    		return true;
    	}else{
    		return false;
    	}
    }
}

//实例化对象，并执行入口函数
$companySmsNotify = new CompanySmsNotify();
$companySmsNotify->main();

