<?php
$config = [
    //车辆管理系统数据库
   /* 'db_car_system'=>[
        'dbname'=>'car_system',
        'host'=>'127.0.0.1',
        'user'=>'root',
        'pwd'=>''*/
        'host'=>'rm-wz9k9ct8av553n1jt.mysql.rds.aliyuncs.com',
        'user'=>'user_carsystem',
        'pwd'=>'CLY7dzc8WRUQ'
    ],

    //前置机数据库
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
//MARK
require('../../common/classes/CarRealtimeDataAnalysis.php');
require('../taobao-sdk-PHP-shot-message/TopSdk.php');
require('../../backend/classes/Mail.php');
require('../../backend/modules/car/controllers/CarBackController.php');

//发送短信
function sendMail($body){
    $mail = new Mail();
    $carback = new CarBack();
    $subject = '退车入库提醒';
    $user_emails = $carback->getUserMailByMca('car','car-back','add5');
    $mail->send($user_emails,$subject, $body);
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
    
    set_time_limit(0);
	
	//查询出所有已丢失的充电卡
	$sql = "SELECT id,oper_time3,contract_text,car_storage_text
            FROM `cs_car_back`
            where is_send_mail <> 1						
			";


	$card_pre = $dbhSystem->prepare($sql);
	$card_pre->execute();
	$card_res = $card_pre->fetchAll(PDO::FETCH_ASSOC);
    //var_dump($card_res);exit;
	//结果集转字符串
	if(!$card_res){
        die;
    }
	//遍历结果集符合条件时发送信息
	$now = strtotime(date('Y-m-d',time()));
	foreach ($card_res as $value) {
        //转化时间格式
        $startDateStr = strtotime($value['oper_time3']);
        $id = $value['id'];
        //取出合同json数据
        //json数据解码
        $car_storage = json_decode($value['car_storage_text']);
        //创建carid字符串序列
        $car_storage_text = 0;
        //var_dump($car_storage);
        if($car_storage) {
            foreach ($car_storage as $row){
                if($row->car_id){
                    $car_storage_text += count(explode(',',$row->car_id));
                }
            }
        }
        //查出车牌号
       $sql = "SELECT plate_number
               FROM   `cs_car`
               where  id in ($car_storage_text)                
               ";
        //$connection->createCommand($sql)->execute();
        $card_pla = $dbhSystem->prepare($sql);
        $card_pla->execute();
        $card_num = $card_pla->fetchAll(PDO::FETCH_ASSOC);
        $plate_number = '';
        foreach ($card_num as $val) {
           $plate_number .= $val['plate_number'].'、';
            // $plate_number =  $plate_number . $val['plate_number'].',';
             //'1,2' 
        }

        //构建邮件内容
        $body ="你有一个待处理的事项：【退车流程，车辆入库】。客户退车 $plate_number售后和车管需在【7日】之内对所退车辆整备完毕并确认入库。请及时登录系统进行【车辆入库】,以免影响工作进度。点击这里：<a href='http://xt.dstzc.com'>登录地上铁系统</a>,或者在浏览器中输入以下地址登录 ：xt.dstzc.com 。如果对此有疑问和建议，请向系统开发部反馈。"; 
    
        //$now = strtotime(date('Y-m-d',time()));
        $remain = $now-$startDateStr;
		if(7-intval($remain/86400))==3){
            sendMail($body);
            $sql = "update cs_car_back set is_send_mail='1' where id={$id}";
            $card_sen = $dbhSystem->prepare($sql);
            $card_sen->execute();
           
        }
    }

    writeLog('sendMil ok.');
}
main();