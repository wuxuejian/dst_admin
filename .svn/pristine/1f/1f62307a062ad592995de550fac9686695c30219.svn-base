<?php
/*
 * 缓存车辆运行轨迹，判断每日00~23点车辆是否存在运行轨迹
 */
class CacheCarTrack {
    protected static $config = [
        //车辆监控数据库（仅本月的监控数数据）
        'db_car_monidata'=>[
            'dbname'=>'car_monidata',
            'host'=>'localhost',
            'user'=>'root',
            'pwd'=>'4Z3uChwl'
        ],
        ////内部测试数据库
/*         'db_test'=>[ 
			'dbname'=>'car_monidata',
            'host'=>'120.76.114.155', 
            'user'=>'szdst',
            'pwd'=>'szdst123',
        ] */
    ];
    protected $logFile;         //记录日志文件
    protected $dbhMonidata;     //本月的监控数据库连接资源
    protected $dbTest;          //内部测试数据库连接资源


    public function __construct(){
        //记录日志文件
        $this->logFile = dirname(__FILE__).'/cacheCarTrack.php.log';
        //链接数据库
        try {
            $this->connectDb();
        } catch (PDOException $e) {
            $this->writeLog('Connection failed: ' . $e->getMessage());
            $this->connectDb();
        }
    }

	
	/**
	 * 链接数据库
	 */
	function connectDb(){
            $dbConfig = self::$config['db_car_monidata'];
            $dsn = "mysql:dbname=". $dbConfig['dbname'] .";host=" . $dbConfig['host'];
            $this->dbhMonidata = new \PDO($dsn, $dbConfig['user'],$dbConfig['pwd'],[
                \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
                \PDO::ATTR_PERSISTENT=>true, //长链接
                \PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
            ]);
/* 			//内部测试数据库
            $dbConfig = self::$config['db_test'];
            $dsn = "mysql:dbname=". $dbConfig['dbname'] .";host=" . $dbConfig['host'];
            $this->dbTest = new \PDO($dsn, $dbConfig['user'],$dbConfig['pwd'],[
                \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
                \PDO::ATTR_PERSISTENT=>true, //长链接
                \PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
            ]); */
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
        $curYear  = date('Y');
        $cacheTableName = "cs_cache_car_track_{$curYear}";
        //因为“车辆运行轨迹缓存表”是按年建立的，所以每年的1月1日检查该年份的表是否存在，若不在则创建该表
        if(date('m-d') == '01-01'){
            $this->checkTableIsExists($cacheTableName);
        }
        $curMonth = date('m');
        $searchYm = $curYear . $curMonth;
        $tabName = 'cs_tcp_car_history_data_' . $searchYm. '__'; //注意，最后一个下划线将匹配0-9任意字符
        //要查询的监控数据库
        $queryDB = $this->dbhMonidata;
        //要更新保存的控数据库
        //$saveUpdateDB = $this->dbTest;
        $saveUpdateDB = $this->dbhMonidata;
        set_time_limit(0);
        //检测该月份的监控数据表是否存在，若不存在则退出
        $sql = "SHOW TABLES LIKE '{$tabName}'";
        $sth = $queryDB->prepare($sql);
        $sth->execute();
        $tabRes = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (!$tabRes) {
			$tip = "Not find table like {$tabName}.";
			//echo $tip . "<br>";
            $this->writeLog($tip);
            return;
        }

        //查出上一次缓存轨迹时的扫描的最后记录ID
        $sql = "SELECT * FROM `cs_cache_car_track_last_scan_id` WHERE `id` = 1";
        $sth = $queryDB->prepare($sql);
        $sth->execute();
        $lastScanIdArr = $sth->fetch(PDO::FETCH_ASSOC);
        if (!$lastScanIdArr) {
			$tip = "Table `cs_cache_car_track_last_scan_id` cannot be empty.";
            //echo $tip . "<br>";
            $this->writeLog($tip);
            return;
        }
		//千万注意：这里要判断下上次扫描时间是否是“上个月的最后一天的23点”？若是，则本次是本月1日第一次扫描，要将各表的id设为从0开始扫描！
		if(substr($lastScanIdArr['modify_time'],0,13) == date('Y-m-t 23',strtotime('-1 month'))){ //t表示月的天数
			$sql = "UPDATE `cs_cache_car_track_last_scan_id` SET `_0`=0,`_1`=0,`_2`=0,`_3`=0,`_4`=0,`_5`=0,`_6`=0,`_7`=0,`_8`=0,`_9`=0 WHERE `id` = 1";
			$sth = $queryDB->prepare($sql);
			$sth->execute();
			$keys = range(0,9);
			foreach($keys as $k){
				$lastScanIdArr['_'.$k] = 0;
			}
		}

        //每月份最多会查出10张数据表（表名的尾数为0-9，是按车架号尾数分组的）：
        //遍历该月份的每张监控数据表，查找出哪些车辆该月的哪几日00~23时的哪些时段存在运行轨迹
        $tables = array_column($tabRes, "Tables_in_car_monidata ({$tabName})");
        foreach ($tables as $tableName) {
            //（1）每次只需查出大于该表上次缓存时扫描的最后记录id的数据再分析即可。建议设置此脚本每天多次执行，以减小数据量。
            $whichTab = substr($tableName,-2);
            $lastScanId = $lastScanIdArr[$whichTab];
            $sql = "SELECT `id`,`car_vin`,`collection_datetime` FROM `{$tableName}` WHERE `speed` > 0 AND `longitude_value` > 0 AND `latitude_value` > 0 AND `id` > {$lastScanId} ORDER BY `id` ASC";
            $sth = $queryDB->prepare($sql);
            $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            if (!$data) {
                continue;
            }
            $nowScanId = $data[count($data)-1]['id']; //本次扫描的最后记录id
            //--按车、（年）月日、时分析数据-------------------------------------
            $perTableDataArr = [];
            foreach ($data as $item) {
                $car  = $item['car_vin'];
                //$Y  = date('Y',$item['collection_datetime']);
                $m  = date('m',$item['collection_datetime']);
                $d  = date('d',$item['collection_datetime']);
                $H  = date('H',$item['collection_datetime']);
                if(!isset($perTableDataArr[$car])){
                    $perTableDataArr[$car][$m][$d][] = $H;
                }else{
                    if(!in_array($H,$perTableDataArr[$car][$m][$d])){
                        $perTableDataArr[$car][$m][$d][] = $H;
                    }
                }
            }
            unset($data);
            //print_r($perTableDataArr);exit;
            //（2）再往“车辆运行轨迹缓存表”中插入或更新该月份的每辆车每日的运行轨迹数据
            if ($perTableDataArr) {
				foreach ($perTableDataArr as &$monthsDaysHours) {
					foreach ($monthsDaysHours as &$daysHours) {
						foreach ($daysHours as $day=>$hours) {
							sort($hours);
							$daysHours[$day] = join('|',$hours);
						}	
					}
				}
				//print_r($perTableDataArr);exit;
                $dayArr = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
                foreach ($perTableDataArr as $carVin=>$monthsDaysTrack) {
					foreach ($monthsDaysTrack as $month=>$daysTrack) {
						$sql = "SELECT * FROM `{$cacheTableName}` WHERE `car_vin` = '{$carVin}' AND `month` = '{$month}'";
						$sth = $saveUpdateDB->prepare($sql);
						if(!$sth->execute()){
							$this->writeLog(' | Failed:'.$sql);
							continue;
						}
						$hasRecord = $sth->fetch(PDO::FETCH_ASSOC);
						if($hasRecord){
							$sql = "UPDATE `{$cacheTableName}` SET ";
							$tmpArr = [];
							foreach($daysTrack as $day=>$trackHours){
								//因为脚本可能被设置成一天执行多次等，所以某日的时点会分多次统计，这里将当前统计时点与之前时点合并再去重
								$oldHours = $hasRecord['day_'.$day];
								if($oldHours){
									$newHours = array_merge(explode('|',$oldHours), explode('|',$trackHours));
									$uNewHours = array_unique($newHours);
									sort($uNewHours);
									$newTrackHours = join('|',$uNewHours);
								}else{
									$newTrackHours = $trackHours;
								}
								$tmpArr[] = "`day_{$day}` = '{$newTrackHours}' ";
							}
							$sql .= join(',',$tmpArr) . " WHERE `car_vin` = '{$carVin}' AND `month` = '{$month}' ";
							$sth = $saveUpdateDB->prepare($sql);
							if(!$sth->execute()){
								$this->writeLog(' | Update failed:'.$sql);
							}
						}else{
							$dailyStr = '';
							foreach ($dayArr as $day) {
								if (isset($daysTrack[$day]) && $daysTrack[$day]) {
									$dailyStr .= "'" . $daysTrack[$day] . "',";
								} else {
									$dailyStr .= "'',";
								}
							}
							$dailyStr = substr($dailyStr,0,-1);
							$sql = "INSERT INTO `{$cacheTableName}` (`id`,`car_vin`,`month`,`day_01`,`day_02`,`day_03`,`day_04`,`day_05`,`day_06`,`day_07`,`day_08`,`day_09`,`day_10`,`day_11`,`day_12`,`day_13`,`day_14`,`day_15`,`day_16`,`day_17`,`day_18`,`day_19`,`day_20`,`day_21`,`day_22`,`day_23`,`day_24`,`day_25`,`day_26`,`day_27`,`day_28`,`day_29`,`day_30`,`day_31`) VALUES (NULL,'{$carVin}','{$month}',{$dailyStr})";
							$sth = $saveUpdateDB->prepare($sql);
							if(!$sth->execute()){
								$this->writeLog(' | Insert failed:'.$sql);
							}
						}
					}	
                }
                unset($perTableDataArr);
            }
            //（3）保存该表的本次扫描的最后记录id
            $sql = "UPDATE `cs_cache_car_track_last_scan_id` SET `{$whichTab}` = {$nowScanId},`modify_time`='".date('Y-m-d H:i:s')."'  WHERE `id` = 1";
            $sth = $saveUpdateDB->prepare($sql);
			if(!$sth->execute()){
				$this->writeLog(' | Insert failed:'.$sql);
			}else{
				$tip = "Queried part-table `{$tableName}`;";
				//echo $tip . "<br>";
				$this->writeLog($tip);
			}
        }
		$this->writeLog("Complete statistics.\n");
    }


    /*
     * 检查某数据表是否存在。若存在直接返回，若不存在则创建该表
     */
    protected function checkTableIsExists($cacheTableName){
        $sql = "SHOW TABLES LIKE '{$cacheTableName}'";
        $sth = $this->dbhMonidata->prepare($sql);
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

