<?php
/**
 * 车辆异常检测该文件应以守护进程方式运行
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
        }
        $processInfo['startTime'] = time();
        if(isset($argv[2]) && $argv[2] == '-d'){
            $pid = pcntl_fork();
            if($pid){
                //当前父进程
                //结束父进程让子进程成为孤儿进程
                $processInfo['pid'] = $pid;
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
require('../../common/classes/CarRealtimeDataAnalysis.php');
require('../taobao-sdk-PHP-shot-message/TopSdk.php');
class CarAnomalyDetection {
    protected static $config = [
        //车辆管理系统数据库
        'db_car_system'=>[
            'dbname'=>'car_system',
            /*'host'=>'120.76.114.155',
            'user'=>'szdst',
            'pwd'=>'szdst123',*/
            'host'=>'rm-wz9k9ct8av553n1jt.mysql.rds.aliyuncs.com',
            'user'=>'user_carsystem',
            'pwd'=>'CLY7dzc8WRUQ',
        ],
        //车辆监控数据库
        'db_car_monidata'=>[
            'dbname'=>'car_monidata',
            /*'host'=>'120.25.209.72',
            'user'=>'szdst',
            'pwd'=>'571f1480ac650',*/
            'host'=>'localhost',
            'user'=>'root',
            'pwd'=>'4Z3uChwl',
        ],
        'alert_type'=>[
            'total_vol'=>'总电压报警',
            'single_vol'=>'电池单体电压报警',
            'single_vol_diff'=>'电池压差报警',
            'discharge_current'=>'放电电流报警',
            'charge_current'=>'充电电流报警',
            'insulation'=>'绝缘故障报警',
            'package_tem'=>'电池包温度报警',
            'package_tem_change'=>'电池温升报警',
            'bms_auto_exam'=>'BMS自检报警',
            'pole_communication'=>'电桩通讯报警'
        ]
    ];

    protected $dbhSystem;//管理系统数据库连接资源
    protected $dbhMonidata;//监控数据库连接资源
    protected $processInfoFilePath;//进程信息文件
    public function __construct(){
        //链接数据库
        $dbname = self::$config['db_car_system']['dbname'];
        $host = self::$config['db_car_system']['host'];
        $dsn = "mysql:dbname={$dbname};host={$host}";
        $this->dbhSystem = new \PDO($dsn, self::$config['db_car_system']['user'],self::$config['db_car_system']['pwd'],[
            \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
            \PDO::ATTR_PERSISTENT=>true,//长链接
            \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION
        ]);
        $dbname = self::$config['db_car_monidata']['dbname'];
        $host = self::$config['db_car_monidata']['host'];
        $dsn = "mysql:dbname={$dbname};host={$host}";
        $this->dbhMonidata = new \PDO($dsn, self::$config['db_car_monidata']['user'],self::$config['db_car_monidata']['pwd'],[
            \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
            \PDO::ATTR_PERSISTENT=>true,//长链接
            \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION
        ]);
        $this->processInfoFilePath = dirname(__FILE__).'/ProcessInfo/'.basename(__FILE__).'.log';
        //启动检测
        $this->main();
    }

    /**
     * 检测车辆是否离线
     */
    protected function outofContactAnalysis($carVin,$batteryType)
    {
        //return [];
        //查询该类电池的系统设置的最短数据上报时间
        $sql = 'select `alert_level`,`alert_dispose`,`alert_content`,`interval_time` from cs_car_moni_exception_condition_item where battery_type = "'.addslashes($batteryType).'" and alert_type = "bms_auto_exam" and in_use = 1 limit 1';
        $sth = $this->dbhSystem->prepare($sql);
        $sth->execute();
        $condition = $sth->fetchAll(PDO::FETCH_ASSOC);
        if(!$condition){
            //没有找到bms系统自检可用的条件
            return [];
        }
        //查询当前车辆是否离线
        $sql = 'select `collection_datetime` from cs_tcp_car_realtime_data where car_vin = "'.$carVin.'" order by `collection_datetime` desc limit 1';
        $sth = $this->dbhSystem->prepare($sql);
        $sth->execute();
        $lastMonidata = $sth->fetchAll(PDO::FETCH_ASSOC);
        if($lastMonidata){
            $alertValue = time() - $lastMonidata[0]['collection_datetime'];
            if($alertValue < $condition[0]['interval_time']){
                //车辆上报数据时间在正常范围内
                return [];
            }
        }else{
            $alertValue = 0;
        }
        $returnArr = [
            'car_vin'=>$carVin,
            'alert_type'=>'bms_auto_exam',//bms自检报警
            'max_min'=>'max',
            'alert_level'=>$condition[0]['alert_level'],
            'alert_dispose'=>$condition[0]['alert_dispose'],
            'alert_content'=>$condition[0]['alert_content'],
            'alert_datetime'=>date('Y-m-d H:i:s'),
            'alert_value'=>$alertValue,
        ];
        return $returnArr;
    }
    /**
     * 电压与温度报警分析
     * @param string   $carVin           车辆车架号
     * @param int      $totalBattery     车辆电池数量
     * @param string   $batteryType      车辆电池类型
     * @param array    $monidataAnalysis 被解析后的车辆监控数据 
     */
    protected function volTemAlert($carVin,$totalBattery,$batteryType,$monidataAnalysis)
    {
        $alertData = [];
        //查询该电池所有启用的电池总电压报警条件
        $sql = 'select `battery_type`,`alert_type`,`max_min`,`set_value`,`alert_level`,`alert_dispose`,`alert_content` from cs_car_moni_exception_condition_item where battery_type = "'.addslashes($batteryType).'" and alert_type in ("total_vol","single_vol","single_vol_diff","package_tem","package_tem_change") and in_use = 1';
        $sth = $this->dbhSystem->prepare($sql);
        $sth->execute();
        $condition = $sth->fetchAll(PDO::FETCH_ASSOC);
        if(!$condition){
            //没有可用条件
            return $alertData;
        }
        $totalVol = array_column($monidataAnalysis,'battery_package_total_voltage');
        $totalVolMax = max($totalVol);//最大总电电压
        $totalVolMin = min($totalVol);//最小总电压
        $singleVolMax = max(array_column($monidataAnalysis,'battery_single_hv_value'));//单个电池最大电压
        $singleVolMin = min(array_column($monidataAnalysis,'battery_single_lv_value'));//单个电池最小电压
        $singleDiffValue = max(array_column($monidataAnalysis,'battery_single_vol_diff'));//单个电池压差最大值
        $temMax = max(array_column($monidataAnalysis,'battery_single_ht_value'));//温度最大值
        $temMin = min(array_column($monidataAnalysis,'battery_single_lt_value'));//温度最小值
        $temChangeMax = max(array_column($monidataAnalysis,'battery_tem_change'));//温升最大值
        foreach($condition as $val){
            switch ($val['alert_type'].'_'.$val['max_min']) {
                case 'total_vol_max':
                    if($totalVolMax >= $totalBattery * $val['set_value']){
                        $alertData[] = [
                            'car_vin'=>$carVin,
                            'alert_type'=>'total_vol',
                            'max_min'=>'max',
                            'alert_level'=>$val['alert_level'],
                            'alert_dispose'=>$val['alert_dispose'],
                            'alert_content'=>$val['alert_content'],
                            'alert_datetime'=>date('Y-m-d H:i:s'),
                            'alert_value'=>$totalVolMax,
                        ];
                    }
                    break;
                case 'total_vol_min':
                    if($totalVolMin <= $totalBattery * $val['set_value']){
                        $alertData[] = [
                            'car_vin'=>$carVin,
                            'alert_type'=>'total_vol',
                            'max_min'=>'min',
                            'alert_level'=>$val['alert_level'],
                            'alert_dispose'=>$val['alert_dispose'],
                            'alert_content'=>$val['alert_content'],
                            'alert_datetime'=>date('Y-m-d H:i:s'),
                            'alert_value'=>$totalVolMin,
                        ];
                    }
                    break;
                case 'single_vol_max':
                    if($singleVolMax >= $val['set_value']){
                        $alertData[] = [
                            'car_vin'=>$carVin,
                            'alert_type'=>'single_vol',
                            'max_min'=>'max',
                            'alert_level'=>$val['alert_level'],
                            'alert_dispose'=>$val['alert_dispose'],
                            'alert_content'=>$val['alert_content'],
                            'alert_datetime'=>date('Y-m-d H:i:s'),
                            'alert_value'=>$singleVolMax,
                        ];
                    }
                    break;
                case 'single_vol_min':
                    if($singleVolMin <= $val['set_value']){
                        $alertData[] = [
                            'car_vin'=>$carVin,
                            'alert_type'=>'single_vol',
                            'max_min'=>'min',
                            'alert_level'=>$val['alert_level'],
                            'alert_dispose'=>$val['alert_dispose'],
                            'alert_content'=>$val['alert_content'],
                            'alert_datetime'=>date('Y-m-d H:i:s'),
                            'alert_value'=>$singleVolMin,
                        ];
                    }
                    break;
                case 'single_vol_diff_max':
                    if($singleDiffValue >= $val['set_value']){
                        $alertData[] = [
                            'car_vin'=>$carVin,
                            'alert_type'=>'single_vol_diff',
                            'max_min'=>'max',
                            'alert_level'=>$val['alert_level'],
                            'alert_dispose'=>$val['alert_dispose'],
                            'alert_content'=>$val['alert_content'],
                            'alert_datetime'=>date('Y-m-d H:i:s'),
                            'alert_value'=>$singleDiffValue,
                        ];
                    }
                    break;
                case 'package_tem_max':
                    if($temMax >= $val['set_value']){
                        $alertData[] = [
                            'car_vin'=>$carVin,
                            'alert_type'=>'package_tem',
                            'max_min'=>'max',
                            'alert_level'=>$val['alert_level'],
                            'alert_dispose'=>$val['alert_dispose'],
                            'alert_content'=>$val['alert_content'],
                            'alert_datetime'=>date('Y-m-d H:i:s'),
                            'alert_value'=>$temMax,
                        ];
                    }
                    break;
                case 'package_tem_min':
                    if($temMin <= $val['set_value']){
                        $alertData[] = [
                            'car_vin'=>$carVin,
                            'alert_type'=>'package_tem',
                            'max_min'=>'min',
                            'alert_level'=>$val['alert_level'],
                            'alert_dispose'=>$val['alert_dispose'],
                            'alert_content'=>$val['alert_content'],
                            'alert_datetime'=>date('Y-m-d H:i:s'),
                            'alert_value'=>$temMin,
                        ];
                    }
                    break;
                case 'package_tem_change_max':
                    if($temChangeMax >= $val['set_value']){
                        $alertData[] = [
                            'car_vin'=>$carVin,
                            'alert_type'=>'package_tem_change',
                            'max_min'=>'max',
                            'alert_level'=>$val['alert_level'],
                            'alert_dispose'=>$val['alert_dispose'],
                            'alert_content'=>$val['alert_content'],
                            'alert_datetime'=>date('Y-m-d H:i:s'),
                            'alert_value'=>$temChangeMax,
                        ];
                    }
                    break;
            }
        }
        return $alertData;
    }
    /**
     * 电流报警分析
     * @param string   $carVin           车架号
     * @param string   $batteryType      车辆电池类型
     * @param array    $monidataAnalysis 被解析后的车辆监控数据 
     */
    protected function currentAlert($carVin,$batteryType,$monidataAnalysis)
    {
        $alertData = [];
        //查询该电池所有启用的电池总电压报警条件
        $sql = 'select `battery_type`,`alert_type`,`max_min`,`set_value`,`alert_level`,`alert_dispose`,`alert_content` from cs_car_moni_exception_condition_item where battery_type = "'.addslashes($batteryType).'" and alert_type in ("charge_current","discharge_current") and in_use = 1';
        $sth = $this->dbhSystem->prepare($sql);
        $sth->execute();
        $condition = $sth->fetchAll(PDO::FETCH_ASSOC);
        if(!$condition){
            //没有可用条件
            return $alertData;
        }
        $maxChargeCurrent = 0;//最大充电电流
        $maxDischargeCurrent = 0;//最大放电电流
        foreach($monidataAnalysis as $val){
            if($val['battery_package_current'] < 0){
                //充电电流
                $maxChargeCurrent = abs($val['battery_package_current']) > $maxChargeCurrent ? abs($val['battery_package_current']) : $maxChargeCurrent;
            }else{
                //放电电流
                $maxDischargeCurrent = $val['battery_package_current'] > $maxDischargeCurrent ? $val['battery_package_current'] : $maxDischargeCurrent;
            }
        }
        foreach($condition as $val){
            switch ($val['alert_type'].'_'.$val['max_min']) {
                case 'charge_current_max':
                    if($maxChargeCurrent >= $val['set_value']){
                        $alertData[] = [
                            'car_vin'=>$carVin,
                            'alert_type'=>'total_vol',
                            'max_min'=>'max',
                            'alert_level'=>$val['alert_level'],
                            'alert_dispose'=>$val['alert_dispose'],
                            'alert_content'=>$val['alert_content'],
                            'alert_datetime'=>date('Y-m-d H:i:s'),
                            'alert_value'=>$maxChargeCurrent,
                        ];
                    }
                    break;
                case 'discharge_current_max':
                    if($maxDischargeCurrent >= $val['set_value']){
                        $alertData[] = [
                            'car_vin'=>$carVin,
                            'alert_type'=>'total_vol',
                            'max_min'=>'max',
                            'alert_level'=>$val['alert_level'],
                            'alert_dispose'=>$val['alert_dispose'],
                            'alert_content'=>$val['alert_content'],
                            'alert_datetime'=>date('Y-m-d H:i:s'),
                            'alert_value'=>$maxDischargeCurrent,
                        ];
                    }
                    break;
            }
        }
        return $alertData;
    }
    /**
     * 短信发送
     */
    protected function sendMessage(){
        $sql = 'select cad.`id`,cad.`car_vin`,cad.`alert_type`,cad.`alert_content`,cad.`alert_datetime`,cad.`alert_value`,c.`plate_number` from cs_car_anomaly_detection as cad left join cs_car as c on c.`vehicle_dentification_number` = cad.`car_vin` where c.`is_del` = 0 and cad.`alert_dispose` = 2 and cad.`has_send_shotmsg` = 0';
        $sth = $this->dbhSystem->prepare($sql);
        $sth->execute();
        $shouldSendRecord = $sth->fetchAll(PDO::FETCH_ASSOC);
        if(!$shouldSendRecord){
            return true;
        }
        $sql = 'select * from cs_car_anomaly_shotmessage_rule where id = 1';
        $sth = $this->dbhSystem->prepare($sql);
        $sth->execute();
        $shotmessageRule = $sth->fetchAll(PDO::FETCH_ASSOC);
        if($shotmessageRule){
            $shotmessageRule = $shotmessageRule[0];
            $shotmessageRule['wd_mobile'] = str_replace('|',',',$shotmessageRule['wd_mobile']);
            $shotmessageRule['hd_mobile'] = str_replace('|',',',$shotmessageRule['hd_mobile']);
            foreach($shouldSendRecord as $val){
                $alertDatetime = strtotime($val['alert_datetime']);
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
                $params = [
                    'car'=>$val['plate_number'],
                    'alert_datetime'=>$val['alert_datetime'],
                    'project'=>self::$config['alert_type'][$val['alert_type']],
                    'value'=>$val['alert_value'],
                    'tip'=>$val['alert_content']
                ];
                $c = new \TopClient;
                // true account of dst
                $c->appkey = '23318373';
                $c->secretKey = 'ac1303f029af0aa1dcbf1e0209a49ec2';
                $req = new \AlibabaAliqinFcSmsNumSendRequest;
                //$req->setExtend("123456");
                $req->setSmsType("normal");
                $req->setSmsFreeSignName('地上铁车辆报警');
                $req->setSmsParam(json_encode($params));
                $req->setRecNum($resiveMobiles);
                $req->setSmsTemplateCode('SMS_35425010');
                $c->execute($req);
            }
        }
        //更新发送状态
        $sql = 'update cs_car_anomaly_detection set has_send_shotmsg = 1 where id in('.join(',',array_column($shouldSendRecord,'id')).')';
        $sth = $this->dbhSystem->prepare($sql);
        $sth->execute();
        return true;
    }

    /**
     * 入口
     */
    protected function main() {
        $csCarStartId = 0;
        do{
            //获取短信推送规则
            $sql = 'select c.`id`,c.`vehicle_dentification_number` as car_vin,b.`battery_type` from `cs_car` as c left join `cs_battery` as b on b.`battery_model` = c.`battery_model` where c.`is_del` = 0 and c.`id` > '.$csCarStartId.' and b.`battery_type` IS NOT NULL order by c.`id` asc limit 50';
            $sth = $this->dbhSystem->prepare($sql);
            $sth->execute();
            $allCar = $sth->fetchAll(PDO::FETCH_ASSOC);
            if(!$allCar){
                $csCarStartId = 0;//进行下一轮检测
                continue;
            }
            $csCarStartId = end($allCar)['id'];
            foreach($allCar as $val){
                //检测是否有短信未发送
                $this->sendMessage();
                //开始检测每台车数据
                $val['car_vin'] = addslashes($val['car_vin']);
                $saveData = [];
                //检测车辆是否离线
                $data = $this->outofContactAnalysis($val['car_vin'],$val['battery_type']);
                if($data){
                    $saveData[] = array_merge($saveData,$data);
                }
                //获取当前车辆未被分析的监控数据
                $sql = 'select `id`,`collection_datetime` from cs_car_anomaly_detection_datetime where car_vin = "'.$val['car_vin'].'" limit 1';
                $sth = $this->dbhSystem->prepare($sql);
                $sth->execute();
                //获取车辆上次已经分析的监控数据的采集时间
                $lastCollectionDatetimeRecord = $sth->fetchAll(PDO::FETCH_ASSOC);
                $lastCollectionDatetime = time();
                if($lastCollectionDatetimeRecord){
                    $lastCollectionDatetime = $lastCollectionDatetimeRecord[0]['collection_datetime'];
                }
                //查询监控数据
                $currentMonidataTable = 'cs_tcp_car_history_data_'.date('Ym').'_'.substr($val['car_vin'],-1);
                $sql = 'select `collection_datetime`,`data_hex` from `'.$currentMonidataTable.'` where `car_vin`="'.$val['car_vin'].'" and `collection_datetime` > '.$lastCollectionDatetime.' order by `collection_datetime` desc limit 1000';
                //$sql = 'select `collection_datetime`,`data_hex` from `'.$currentMonidataTable.'` where `car_vin`="'.$val['car_vin'].'" order by `collection_datetime` desc limit 100';
                $sth = $this->dbhMonidata->prepare($sql);
                $sth->execute();
                $monidata = $sth->fetchAll(PDO::FETCH_ASSOC);
                //车辆本次分析的监控数据采集时间
                $currentCollectionDateTime = time();
                if($monidata){
                    $currentCollectionDateTime = $monidata[0]['collection_datetime'];
                    //当前车辆电池总数
                    $totalBattery = 0;
                    $monidataAnalysis = [];//存放解析后的数据
                    foreach($monidata as $k=>$v){
                        //出现解析错误放弃改数据包
                        $crdaObj = new \common\classes\CarRealtimeDataAnalysis($v['data_hex']);
                        $unpackData = $crdaObj->getRealtimeData();
                        if(!$unpackData){
                            //无法解析本包
                            continue;
                        }
                        if(empty($unpackData['collection_datetime']) || empty($unpackData['battery_package_total_voltage']) || empty($unpackData['battery_single_hv_value']) || empty($unpackData['battery_single_lv_value']) || empty($unpackData['battery_package_current']) || empty($unpackData['battery_single_ht_value']) || empty($unpackData['battery_single_lt_value']) || empty($unpackData['battery_package_voltage'])){
                            //本包数据不全舍弃该包
                            continue;
                        }
                        if($unpackData['battery_single_hv_value'] >= 65.535 || $unpackData['battery_single_lv_value'] >= 65.535){
                            //电压数据非法
                            continue;
                        }
                        $bpvObj = json_decode($unpackData['battery_package_voltage']);
                        if($bpvObj && isset($bpvObj->totalSingleBattery)){
                            $totalBattery = $bpvObj->totalSingleBattery > $totalBattery ? $bpvObj->totalSingleBattery : $totalBattery;
                        }
                        $monidataItem = [
                            //数据采集时间
                            'collection_datetime'=>$unpackData['collection_datetime'],
                            //电池包总电压
                            'battery_package_total_voltage'=>$unpackData['battery_package_total_voltage'],
                            //单电池电压最大值
                            'battery_single_hv_value'=>$unpackData['battery_single_hv_value'],
                            //单电池电压最小值
                            'battery_single_lv_value'=>$unpackData['battery_single_lv_value'],
                            //电池压差
                            'battery_single_vol_diff'=>abs(sprintf('%.3f',$unpackData['battery_single_hv_value'] - $unpackData['battery_single_lv_value'])),
                            //电池包电流
                            'battery_package_current'=>$unpackData['battery_package_current'],
                            //电池包最高温度
                            'battery_single_ht_value'=>$unpackData['battery_single_ht_value'],
                            //电池包最低温度
                            'battery_single_lt_value'=>$unpackData['battery_single_lt_value'],
                            //电池温差
                            'battery_tem_change'=>0,
                        ];
                        //计算两个包的温差
                        $lastMonidata = end($monidataAnalysis);
                        if($lastMonidata && isset($lastMonidata['battery_single_ht_value']) && $lastMonidata['battery_single_ht_value'] < $monidataItem['battery_single_ht_value']){
                            $monidataItem['battery_tem_change'] = sprintf('%.2f',$monidataItem['battery_single_ht_value'] - $lastMonidata['battery_single_ht_value']);
                        }
                        $monidataAnalysis[] = $monidataItem;
                        unset($monidata[$k]);
                    }
                    unset($monidata);
                    if($monidataAnalysis){
                        //解析后数据不为空再进行数据分析
                        //电压温度报警分析
                        $data = $this->volTemAlert($val['car_vin'],$totalBattery,$val['battery_type'],$monidataAnalysis);
                        if($data){
                            $saveData = array_merge($saveData,$data);
                        }
                        //电流报警分析
                        $data = $this->currentAlert($val['car_vin'],$val['battery_type'],$monidataAnalysis);
                        if($data){
                            $saveData = array_merge($saveData,$data);
                        }
                    }
                    
                }
                //保存车辆报警
                if($saveData){
                    foreach($saveData as $v){
                        //检查车辆是否有未处理的同类型异常如果有则只更新报警时间
                        $sql = "select `id` from `cs_car_anomaly_detection` where car_vin = '{$val['car_vin']}' and alert_type = '{$v['alert_type']}' and max_min = '{$v['max_min']}' and alert_level = {$v['alert_level']} and alert_dispose = {$v['alert_dispose']} and  status != 'end' limit 1";
                        $sth = $this->dbhSystem->prepare($sql);
                        $sth->execute();
                        $hasRecord = $sth->fetchAll(PDO::FETCH_ASSOC);
                        if($hasRecord){
                            $sql = 'update `cs_car_anomaly_detection` set alert_datetime = :alert_datetime,`alert_value` = :alert_value,times = times + 1 where id = :id';
                            $sth = $this->dbhSystem->prepare($sql);
                            $sth->execute([
                                ':alert_datetime'=>$v['alert_datetime'],
                                ':alert_value'=>$v['alert_value'],
                                ':id'=>$hasRecord[0]['id']
                            ]);
                        }else{
                            $sql = 'insert into `cs_car_anomaly_detection`(`car_vin`,`battery_type`,`alert_type`,`max_min`,`alert_level`,`alert_dispose`,`alert_content`,`alert_datetime`,`alert_value`,`status`) values (:car_vin,:battery_type,:alert_type,:max_min,:alert_level,:alert_dispose,:alert_content,:alert_datetime,:alert_value,:status)';
                            $sth = $this->dbhSystem->prepare($sql);
                            $sth->execute([
                                ':car_vin'=>$val['car_vin'],
                                ':battery_type'=>$val['battery_type'],
                                ':alert_type'=>$v['alert_type'],
                                ':max_min'=>$v['max_min'],
                                ':alert_level'=>$v['alert_level'],
                                ':alert_dispose'=>$v['alert_dispose'],
                                ':alert_content'=>$v['alert_content'],
                                ':alert_datetime'=>$v['alert_datetime'],
                                ':alert_value'=>$v['alert_value'],
                                ':status'=>$v['alert_dispose'] == 0 ? 'no_need' : 'new'
                            ]);
                        }
                    }
                }
                //更新本台车的数据分析截止时间
                if($lastCollectionDatetimeRecord){
                    $sql = 'update `cs_car_anomaly_detection_datetime` set `collection_datetime`='.$currentCollectionDateTime.',`sys_datetime`="'.date('Y-m-d H:i:s').'" where `id`='.$lastCollectionDatetimeRecord[0]['id'];
                }else{
                    $sql = 'insert into `cs_car_anomaly_detection_datetime` (`car_vin`,`collection_datetime`,`sys_datetime`) values ("'.$val['car_vin'].'",'.$currentCollectionDateTime.',"'.date('Y-m-d H:i:s').'")';
                }
                //var_dump($sql);
                $sth = $this->dbhSystem->prepare($sql);
                $sth->execute();
            }
            //返回进程信息
            $processInfo = json_decode(file_get_contents($this->processInfoFilePath),true);
            $processInfo['activeTime'] = time();
            $processInfo['memory'] = memory_get_usage();
            file_put_contents($this->processInfoFilePath, json_encode($processInfo));
        }while(true);
    }

    public function __destruct(){
        $this->dbhSystem = null;
        $this->dbhMonidata = null;
    }
}
do{
    try{
        new  CarAnomalyDetection();
    }catch(\Exception $e){
        $exceptionInfo = '['.date('Y-m-d H:i:s').']'.$e->getMessage();
        $exceptionInfo .= $e->getTraceAsString()."\n";
        file_put_contents('./CarAnomalyDetection.log',$exceptionInfo,FILE_APPEND);
        //echo $exceptionInfo;
    }
    sleep(5);
}while(true);