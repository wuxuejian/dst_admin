<?php
/**
 * 电桩异常检测该文件应以守护进程方式运行
 */
//进程信息存放文件
$processInfoFilePath = dirname(__FILE__).'/ProcessInfo/'.basename(__FILE__).'.log';
if(!isset($argv[1])){
    $argv[1] = '';
}
switch ($argv[1]) {
    case 'start':
        if(file_exists($processInfoFilePath)){
            $processInfo = file_get_contents($processInfoFilePath);
            $processInfo = json_decode($processInfo,true);
            if($processInfo && !empty($processInfo['pid'])){
                shell_exec('kill -9 '.$processInfo['pid']);
            }
        }else{
            $processInfo = [];
        }
        $processInfo['startTime'] = time();
        if(isset($argv[2]) && $argv[2] == '-d'){
            $pid = pcntl_fork();
            if($pid){
                //当前父进程
                //结束父进程让子进程成为孤儿进程
                $processInfo['pid'] = $pid;
                //var_export($processInfo);
                file_put_contents($processInfoFilePath,json_encode($processInfo));
                echo 'success,start in deamon mode ...',"\n";
                die;
            }
            //子进程
        }else{
            $processInfo['pid'] = 0;
            file_put_contents($processInfoFilePath,json_encode($processInfo));
            echo 'success,start in debug mode ...',"\n";
        }
        break;
    case 'stop':
        if(file_exists($processInfoFilePath)){
            $processInfo = file_get_contents($processInfoFilePath);
            $processInfo = json_decode($processInfo);
            if($processInfo && !empty($processInfo['pid'])){
                $processInfo['pid'] = 0;
                file_put_contents($processInfoFilePath, json_encode($processInfo));
                shell_exec('kill -9 '.$processInfo['pid']);
            }
        }
        die;
    default:
        $tips = 'php '.basename(__FILE__).' start (-d) | stop';
        die($tips."\n");
}
//载入必要文件
require('../taobao-sdk-PHP-shot-message/TopSdk.php');
do{
    try{
        new PoleAlert($processInfoFilePath);
    }catch(\Exception $e){
        $exceptionInfo = '['.date('Y-m-d H:i:s').']'.$e->getMessage();
        $exceptionInfo .= $e->getTraceAsString()."\n";
        file_put_contents('./PoleAlert.log',$exceptionInfo,FILE_APPEND);
        //echo $exceptionInfo;
    }
    sleep(5);
}while(true);
/**
 * 电桩故障检测
 */
class PoleAlert
{   
    //配置项目
    protected static $config = [
        //管理系统数据库链接
        'dbname'=>'car_system',
        /*'host'=>'120.76.114.155',
        'user'=>'szdst',
        'pwd'=>'szdst123',*/
        'host'=>'rm-wz9k9ct8av553n1jt.mysql.rds.aliyuncs.com',
        'user'=>'user_carsystem',
        'pwd'=>'CLY7dzc8WRUQ',
        'refreshTime'=>100,//前置机数据信息和报警规则刷新时间(秒)
    ];
    protected $dbhSystem = null;   //管理系统数据库链接资源
    protected $fmInfo = [];        //当前系统前置机数据
    protected $alertCondition = [];//报警条件
    protected $lastRefreshTime = 0;//上次刷新时间
    protected $fmExamTimeTag = []; //前置机检测时间
    protected $processInfoFilePath;//进程信息文件
    public function __construct($processInfoFilePath)
    {
        $this->processInfoFilePath = $processInfoFilePath;
        //链接管理系统数据库
        $dsn = 'mysql:dbname='.self::$config['dbname'].';host='.self::$config['host'];
        $this->dbhSystem = new \PDO($dsn,self::$config['user'],self::$config['pwd'],[
            \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
            \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION,
        ]);
        //循环检测
        do{
            if(empty($this->fmInfo) || empty($this->alertCondition) || time() - $this->lastRefreshTime > self::$config['refreshTime']){
                //获取前置机信息和报警条件
                $this->getFrontMachineInfoAndAlertCondition();
            }
            if(empty($this->fmInfo) || empty($this->alertCondition)){
                sleep(10);
                continue;
            }
            foreach($this->fmInfo as $val){
                //检测当前前置机
                $this->examFrontMachine($val);
                //批量发送短信
                $this->sendMessage();
                //返回进程信息
                $processInfo = json_decode(file_get_contents($this->processInfoFilePath),true);
                $processInfo['activeTime'] = time();
                $processInfo['memory'] = memory_get_usage();
                file_put_contents($this->processInfoFilePath, json_encode($processInfo));
                }
        }while(true);
    }

    /**
     * 查询当前需要检测的前置机信息和报警条件
     */
    protected function getFrontMachineInfoAndAlertCondition()
    {
        //查询前置机数据
        $sql = 'select addr,db_username,db_password,db_port,db_name from cs_charge_frontmachine where is_del = 0';
        $sth = $this->dbhSystem->prepare($sql);
        $sth->execute();
        $fmInfo = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $this->fmInfo = $fmInfo;
        //获取系统电桩报警规则
        $sql = 'select * from cs_charge_spots_alert_item where in_use = 1';
        $sth = $this->dbhSystem->prepare($sql);
        $sth->execute();
        $alertCondition = $sth->fetchAll(\PDO::FETCH_ASSOC);
        if($alertCondition){
            foreach($alertCondition as $key=>$val){
                $alertCondition[$val['event_code']] = $val;
                unset($alertCondition[$key]);
            }
        }else{
            $alertCondition = [];
        }
        $this->alertCondition = $alertCondition;
        //更新刷新时间
        $this->lastRefreshTime = time();
    }

    /**
     * 检测方法
     */
    protected function examFrontMachine($fmInfo)
    {
        $dbh = null;
        //链接数据库
        $dsn = 'mysql:dbname='.$fmInfo['db_name'].';host='.$fmInfo['addr'].';port='.$fmInfo['db_port'];
        $dbh = new \PDO($dsn,$fmInfo['db_username'],$fmInfo['db_password'],[
            \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
            \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION,
        ]);
        //查询前置机数据
        if(isset($this->fmExamTimeTag[$fmInfo['addr']])){
            $timeTag = date('Y-m-d H:i:s',$this->fmExamTimeTag[$fmInfo['addr']]);
        }else{
            $timeTag = date('Y-m-d H:i:s');
        }
        $eventCode = array_keys($this->alertCondition);
        $sql = 'select de.*,cp.DEV_ADDR from dev_event as de left join charge_pole as cp on cp.DEV_ID = de.DEV_ID where de.`TIME_TAG` > "'.$timeTag.'" and de.`EVENT_CODE` in("'.join('","',$eventCode).'") order by de.`time_tag` desc';
        $sth = $dbh->prepare($sql);
        //$sth->execute();
        //$alertInfo = $sth->fetchAll(\PDO::FETCH_ASSOC);
        if(empty($alertInfo)){
            $this->fmExamTimeTag[$fmInfo['addr']] = time();
            $dbh = null;
            return true;
        }
        $this->fmExamTimeTag[$fmInfo['addr']] = strtotime($alertInfo[0]['TIME_TAG']);
        $logicAddr = join('","',array_unique(array_column($alertInfo,'DEV_ADDR')));
        $sql = 'select `station_id`,`logic_addr` from cs_charge_spots where logic_addr in("'.$logicAddr.'") and is_del = 0';
        $sth = $this->dbhSystem->prepare($sql);
        $sth->execute();
        $poleInfo = $sth->fetchAll(\PDO::FETCH_ASSOC);
        if($poleInfo){
            foreach($poleInfo as $key=>$val){
                $poleInfo[$val['logic_addr']] = $val;
                unset($poleInfo[$key]);
            }
        }
        foreach($alertInfo as $val){
            //var_dump($val);
            //查询前置机上当前电桩状态
            $sql = 'select STATUS from charge_status where DEV_ID = '.$val['DEV_ID'].' and INNER_ID = '.$val['INNER_ID'].' limit 1';
            $sth = $dbh->prepare($sql);
            $poleStatus = $sth->fetchAll(\PDO::FETCH_ASSOC);
            if($poleStatus){
                $poleStatus = $poleStatus[0]['STATUS'];
            }else{
                $poleStatus = 4;
            }
            $alertCondition = $this->alertCondition[$val['EVENT_CODE']];
            $sql = 'select `id` from cs_charge_spots_alert where `dev_addr` = :dev_addr and `inner_id` = :inner_id and `event_code` = :event_code and alert_name = :alert_name and alert_level = :alert_level and alert_dispose = :alert_dispose and alert_content = :alert_content and `status` != "end" limit 1';
            $sth = $this->dbhSystem->prepare($sql);
            $sth->execute([
                ':dev_addr'=>$val['DEV_ADDR'],
                ':inner_id'=>$val['INNER_ID'],
                ':event_code'=>$val['EVENT_CODE'],
                ':alert_name'=>$alertCondition['name'],
                ':alert_level'=>$alertCondition['alert_level'],
                ':alert_dispose'=>$alertCondition['alert_dispose'],
                ':alert_content'=>$alertCondition['alert_content'],
            ]);
            $hasData = $sth->fetchAll(\PDO::FETCH_ASSOC);
            if($hasData){
                $sql = 'update `cs_charge_spots_alert` set `pole_status` = :pole_status,`times` = `times` + 1,`happen_datetime` = :happen_datetime where `id` = '.$hasData[0]['id'];
                $sth = $this->dbhSystem->prepare($sql);
                $sth->execute([
                    ':pole_status'=>$poleStatus,
                    ':happen_datetime'=>$val['HAPPEN_TIME']
                ]);
            }else{
                $sql = 'insert into cs_charge_spots_alert (`station_id`,`dev_addr`,`inner_id`,`pole_status`,`event_code`,`alert_name`,`alert_level`,`alert_dispose`,`alert_content`,`happen_datetime`,`event_desc`,`status`) values (:station_id,:dev_addr,:inner_id,:pole_status,:event_code,:alert_name,:alert_level,:alert_dispose,:alert_content,:happen_datetime,:event_desc,:status)';
                $sth = $this->dbhSystem->prepare($sql);
                $stationId = 0;//电站id
                if($poleInfo && isset($poleInfo[$val['DEV_ADDR']])){
                    $stationId = $poleInfo[$val['DEV_ADDR']]['station_id'];
                }
                $sth->execute([
                    ':station_id'=>$stationId,
                    ':dev_addr'=>$val['DEV_ADDR'],
                    ':inner_id'=>$val['INNER_ID'],
                    'pole_status'=>$poleStatus,
                    ':event_code'=>$val['EVENT_CODE'],
                    ':alert_name'=>$alertCondition['name'],
                    ':alert_level'=>$alertCondition['alert_level'],
                    ':alert_dispose'=>$alertCondition['alert_dispose'],
                    ':alert_content'=>$alertCondition['alert_content'],
                    ':happen_datetime'=>$val['HAPPEN_TIME'],
                    ':event_desc'=>$val['EVENT_DESC'],
                    ':status'=>$alertCondition['alert_dispose'] > 0 ? 'new' : 'no_need',
                ]);
            }
        }
        $dbh = null;
        return true;
    }

    /**
     * 批量发送短信
     */
    protected function sendMessage()
    {
        $sql = 'select csa.id,csa.dev_addr,csa.pole_status,csa.alert_name,csa.alert_content,csa.happen_datetime,cs.cs_name from cs_charge_spots_alert as csa left join cs_charge_station cs on cs.cs_id = csa.station_id where csa.has_send_shotmsg = 0 and csa.alert_dispose = 2';
        $sth = $this->dbhSystem->prepare($sql);
        $sth->execute();
        $sendMsgItem = $sth->fetchAll(\PDO::FETCH_ASSOC);
        if(!$sendMsgItem){
            return true;
        }
        $sql = 'select * from cs_charge_spots_alert_shotmessage_rule where id = 1 limit 1';
        $sth = $this->dbhSystem->prepare($sql);
        $sth->execute();
        $shotmessageRule = $sth->fetchAll(PDO::FETCH_ASSOC);
        if($shotmessageRule){
            $shotmessageRule = $shotmessageRule[0];
            //查询本次报警的电桩的状态
            foreach($sendMsgItem as $val){
                $alertDatetime = strtotime($val['happen_datetime']);
                switch (intval(date('N',$alertDatetime))){
                    case 6:
                    case 7:
                        //节假日
                        if(date('Hi',$alertDatetime) < str_replace(':','',$shotmessageRule['hd_start_time']) || date('Hi',$alertDatetime) > str_replace(':','',$shotmessageRule['hd_end_time']) || empty($shotmessageRule['hd_mobile'])){
                            continue;
                        }
                        $resiveMobiles = $shotmessageRule['hd_mobile'];
                        break;
                    default:
                        //工作日
                        if(date('Hi',$alertDatetime) < str_replace(':','',$shotmessageRule['wd_start_time']) || date('Hi',$alertDatetime) > str_replace(':','',$shotmessageRule['wd_end_time']) || empty($shotmessageRule['wd_mobile'])){
                            continue;
                        }
                        $resiveMobiles = $shotmessageRule['wd_mobile'];
                        break;
                }
                switch ($val['pole_status']) {
                    case 0:
                        $val['pole_status'] = '充电';
                        break;
                    case 1:
                        $val['pole_status'] = '待机';
                        break;
                    case 2:
                        $val['pole_status'] = '故障';
                        break;
                    case 3:
                        $val['pole_status'] = '禁用';
                        break;
                    default:
                        $val['pole_status'] = '离线';
                        break;
                }
                $params = [
                    'station'=>$val['cs_name'],
                    'pole'=>$val['dev_addr'],
                    'status'=>$val['pole_status'],
                    'project'=>$val['alert_name'],
                    'content'=>$val['alert_content']
                ];
                $c = new \TopClient;
                // true account of dst
                $c->appkey = '23318373';
                $c->secretKey = 'ac1303f029af0aa1dcbf1e0209a49ec2';
                $req = new \AlibabaAliqinFcSmsNumSendRequest;
                //$req->setExtend("123456");
                $req->setSmsType("normal");
                $req->setSmsFreeSignName('充电桩报警');
                $req->setSmsParam(json_encode($params));
                $req->setRecNum($resiveMobiles);
                $req->setSmsTemplateCode('SMS_8665020');
                $c->execute($req)->result;
            }
        }
        $sql = 'update cs_charge_spots_alert set has_send_shotmsg = 1 where id  in('.join(',',array_column($sendMsgItem,'id')).')';
        $sth = $this->dbhSystem->prepare($sql);
        $sth->execute();
    }

    /**
     * 
     */
    public function __destruct()
    {
        $this->dbhSystem = null;
    }

}