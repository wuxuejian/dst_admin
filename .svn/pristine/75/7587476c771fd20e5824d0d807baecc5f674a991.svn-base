<?php
/*
 * 缓存车辆运行轨迹，判断每日00~23点车辆是否存在运行轨迹
 */
class CacheCarTrack {
    protected static $config = [
/*        //车辆监控数据库（仅本月的监控数数据）
        'db_car_monidata'=>[
            'dbname'=>'car_monidata',
            'host'=>'120.25.209.72',
            'user'=>'szdst',
            'pwd'=>'571f1480ac650'
//            'host'=>'localhost',
//            'user'=>'root',
//            'pwd'=>'4Z3uChwl',
        ],*/
        //车辆监控数据库（历史备份的监控数数据）
        'db_car_monidata_wicp'=>[
            'dbname'=>'car_monidata',
            'host'=>'localhost',
            'user'=>'root',
            'pwd'=>'Szdst20160328&'
        ]
    ];
    protected $logFile;         //记录日志文件
    protected $dbhMonidata;     //本月的监控数据库连接资源
    protected $dbhMonidataWicp; //历史备份的监控数据库连接资源


    public function __construct(){
        //记录日志文件
        $this->logFile = dirname(__FILE__).'/cacheCarTrack.php.log';
        //连接本月监控数据库
/*        try {
            $dbConfig = self::$config['db_car_monidata'];
            $dsn = "mysql:dbname=". $dbConfig['dbname'] .";host=" . $dbConfig['host'];
            $this->dbhMonidata = new \PDO($dsn, $dbConfig['user'],$dbConfig['pwd'],[
                \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
                \PDO::ATTR_PERSISTENT=>true, //长链接
                \PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            $this->writeLog('Connection failed: ' . $e->getMessage());
            die;
        }*/
        //连接历史备份监控数据库
        try {
            $dbConfig = self::$config['db_car_monidata_wicp'];
            $dsn = "mysql:dbname=". $dbConfig['dbname'] .";host=" . $dbConfig['host'];
            $this->dbhMonidataWicp = new \PDO($dsn, $dbConfig['user'],$dbConfig['pwd'],[
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
        //echo $log,"\n";
    }


    /*
     * 入口
     */
    function main(){
        $year  = date('Y');
        $month = '03';
        $searchYm = $year . $month;
        $tableName = 'cs_tcp_car_history_data_201603';
        //设置要查询历史备份数据库
        $queryDB = $this->dbhMonidataWicp;
        set_time_limit(0);
        $dayArr = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
        //查询出一部分车辆
        $sql = "SELECT DISTINCT `car_vin` FROM `{$tableName}`"; // WHERE `car_vin` LIKE '%0'";
        $sth = $queryDB->prepare($sql);
        $sth->execute();
        $carRes = $sth->fetchAll(PDO::FETCH_ASSOC);
        $cars = array_column($carRes,'car_vin');
        //print_r($cars);exit;
        foreach($cars as $carVin){
            //（1）先查该车辆该月采集的第一条数据时间
            $sql = "SELECT `collection_datetime` FROM `{$tableName}` WHERE `car_vin` = '{$carVin}' AND `speed` > 0 AND `longitude_value` > 0 AND `latitude_value` > 0 ORDER BY `collection_datetime` ASC  LIMIT 1";
            $sth = $queryDB->prepare($sql);
            $sth->execute();
            $rec = $sth->fetch(PDO::FETCH_ASSOC);
            if(!$rec){
                continue;
            }
            //（2）从该月第一条数据采集时间开始往后查找每一日存在轨迹的时点
            foreach ($dayArr as $day) {
                $searchYmd = $searchYm . $day;
                if(strtotime($searchYmd) < strtotime(date('Y-m-d',$rec['collection_datetime']))){
                    continue;
                }
                //第一种思路：查出一整日并按时点进行分组
                $startTime = strtotime($searchYmd.' 000000');
                $endTime   = strtotime($searchYmd.' 235959');
                $sql = "SELECT FROM_UNIXTIME(`collection_datetime`,'%H') AS perHour FROM `{$tableName}` WHERE `car_vin` = '{$carVin}' AND `speed` > 0 AND `longitude_value` > 0 AND `latitude_value` > 0 AND `collection_datetime` >= '{$startTime}' AND `collection_datetime` <= '{$endTime}' GROUP BY perHour ORDER BY perHour";
                $sth = $queryDB->prepare($sql);
                $sth->execute();
                $data = $sth->fetchAll(PDO::FETCH_ASSOC);
                if($data){
                    $carTrackArr[$day] = join('|',array_column($data,'perHour'));
                    unset($data);
                }
                //第二种思路：将一日分24个时段查询是否存在轨迹
                /*$hour = 0;
                while($hour <= 23){
                    if($hour < 10){
                        $hour = '0' . $hour;
                    }
                    $startTime = strtotime($searchYmd.' '.$hour.'0000');
                    $endTime   = strtotime($searchYmd.' '.$hour.'5959');
                    $sql = "SELECT `id` FROM `{$tableName}` WHERE `car_vin` = '{$carVin}' AND `speed` > 0 AND `longitude_value` > 0 AND `latitude_value` > 0 AND `collection_datetime` >= '{$startTime}' AND `collection_datetime` <= '{$endTime}' LIMIT 1";
                    $sth = $queryDB->prepare($sql);
                    $sth->execute();
                    $data = $sth->fetchAll(PDO::FETCH_ASSOC);
                    if($data){
                        $carTrackArr[$day][] = $hour;
                    }
                    $hour++;
                }
                if(isset($carTrackArr[$day]) && $carTrackArr[$day]){
                    $carTrackArr[$day] = join('|',$carTrackArr[$day]);
                }*/
            }
            //print_r($carTrackArr);exit;
            //（3）查出有数据时则往数据表插入新纪录
            $this->checkTableIsExists($queryDB,'cs_cache_car_track_2016_03');
            if($carTrackArr){
                $sql = "SELECT `id` FROM `cs_cache_car_track_2016_03` WHERE `car_vin` = '{$carVin}' AND `month` = '{$month}' ";
                $sth = $this->dbhMonidataWicp->prepare($sql);
                $sth->execute();
                $record = $sth->fetch(PDO::FETCH_ASSOC);
                if($record){
                    $sql = "UPDATE `cs_cache_car_track_2016_03` SET ";
                    $tmpArr = [];
                    foreach($carTrackArr as $day=>$trackHours){
                        $tmpArr[] = "`day_{$day}` = '{$trackHours}' ";
                    }
                    $sql .= join(',',$tmpArr) . " WHERE `car_vin` = '{$carVin}' AND `month` = '{$month}' ";
                    $sth = $this->dbhMonidataWicp->prepare($sql);
                    $sth->execute();
                }else{
                    $dailyStr = '';
                    foreach ($dayArr as $day) {
                        if (isset($carTrackArr[$day]) && $carTrackArr[$day]) {
                            $dailyStr .= "'" . $carTrackArr[$day] . "',";
                        } else {
                            $dailyStr .= "'',";
                        }
                    }
                    $dailyStr = substr($dailyStr,0,-1);
                    $sql = "INSERT INTO `cs_cache_car_track_2016_03` (`id`,`car_vin`,`month`,`day_01`,`day_02`,`day_03`,`day_04`,`day_05`,`day_06`,`day_07`,`day_08`,`day_09`,`day_10`,`day_11`,`day_12`,`day_13`,`day_14`,`day_15`,`day_16`,`day_17`,`day_18`,`day_19`,`day_20`,`day_21`,`day_22`,`day_23`,`day_24`,`day_25`,`day_26`,`day_27`,`day_28`,`day_29`,`day_30`,`day_31`) VALUES (NULL,'{$carVin}','{$month}',{$dailyStr})";
                    $sth = $this->dbhMonidataWicp->prepare($sql);
                    $sth->execute();
                }
            }
            $this->writeLog("Queried car '{$carVin}';");
        }
        $this->writeLog("Complete statistics.\n");
    }


    /*
     * 检查某数据表是否存在。若存在直接返回，若不存在则创建该表
     */
    protected function checkTableIsExists($db,$cacheTableName){
        $sql = "SHOW TABLES LIKE '{$cacheTableName}'";
        $sth = $db->prepare($sql);
        $sth->execute();
        $tabRes = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($tabRes) {
            return;
        }
        $sql = "
            CREATE TABLE `{$cacheTableName}` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，车辆运行轨迹缓存表',
              `car_vin` varchar(50) NOT NULL DEFAULT '' COMMENT '车架号',
              `month` char(2) NOT NULL DEFAULT '' COMMENT '月份',
              `day_01` varchar(100) NOT NULL DEFAULT '' COMMENT '01日（00~23，每日哪些时段存在运行轨迹）',
              `day_02` varchar(100) NOT NULL DEFAULT '' COMMENT '02日',
              `day_03` varchar(100) NOT NULL DEFAULT '' COMMENT '03日',
              `day_04` varchar(100) NOT NULL DEFAULT '' COMMENT '04日',
              `day_05` varchar(100) NOT NULL DEFAULT '' COMMENT '05日',
              `day_06` varchar(100) NOT NULL DEFAULT '' COMMENT '06日',
              `day_07` varchar(100) NOT NULL DEFAULT '' COMMENT '07日',
              `day_08` varchar(100) NOT NULL DEFAULT '' COMMENT '08日',
              `day_09` varchar(100) NOT NULL DEFAULT '' COMMENT '09日',
              `day_10` varchar(100) NOT NULL DEFAULT '' COMMENT '10日',
              `day_11` varchar(100) NOT NULL DEFAULT '' COMMENT '11日',
              `day_12` varchar(100) NOT NULL DEFAULT '' COMMENT '12日',
              `day_13` varchar(100) NOT NULL DEFAULT '' COMMENT '13日',
              `day_14` varchar(100) NOT NULL DEFAULT '' COMMENT '14日',
              `day_15` varchar(100) NOT NULL DEFAULT '' COMMENT '15日',
              `day_16` varchar(100) NOT NULL DEFAULT '' COMMENT '16日',
              `day_17` varchar(100) NOT NULL DEFAULT '' COMMENT '17日',
              `day_18` varchar(100) NOT NULL DEFAULT '' COMMENT '18日',
              `day_19` varchar(100) NOT NULL DEFAULT '' COMMENT '19日',
              `day_20` varchar(100) NOT NULL DEFAULT '' COMMENT '20日',
              `day_21` varchar(100) NOT NULL DEFAULT '' COMMENT '21日',
              `day_22` varchar(100) NOT NULL DEFAULT '' COMMENT '22日',
              `day_23` varchar(100) NOT NULL DEFAULT '' COMMENT '23日',
              `day_24` varchar(100) NOT NULL DEFAULT '' COMMENT '24日',
              `day_25` varchar(100) NOT NULL DEFAULT '' COMMENT '25日',
              `day_26` varchar(100) NOT NULL DEFAULT '' COMMENT '26日',
              `day_27` varchar(100) NOT NULL DEFAULT '' COMMENT '27日',
              `day_28` varchar(100) NOT NULL DEFAULT '' COMMENT '28日',
              `day_29` varchar(100) NOT NULL DEFAULT '' COMMENT '29日',
              `day_30` varchar(100) NOT NULL DEFAULT '' COMMENT '30日',
              `day_31` varchar(100) NOT NULL DEFAULT '' COMMENT '31日',
              PRIMARY KEY (`id`),
              KEY `car_vin` (`car_vin`),
              KEY `month` (`month`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='车辆运行轨迹缓存表'
        ";
        $sth = $this->dbhMonidata->prepare($sql);
        $sth->execute();
        $this->writeLog("Create new table `{$cacheTableName}`.");
    }

}


//实例化对象，并执行入口函数
$cctObj = new CacheCarTrack();
$cctObj->main();

