<?php
/**
 * 深圳顺丰车辆数据转发给汇通天下 该文件应以守护进程方式运行
 */
//进程信息存放文件
$processInfoFilePath = dirname(__FILE__).'/ProcessInfo/'.basename(__FILE__).'.log';
if(!isset($argv[1])){
// 	$argv[1] = 'start';
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

date_default_timezone_set('PRC');

//建立socket
function createNewSocket(){
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    //发送超时
    socket_set_option($socket,SOL_SOCKET,SO_SNDTIMEO,array("sec"=>3,"usec"=>0 ));
    //接收超时
    socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,array("sec"=>60,"usec"=>0 ));
    socket_connect($socket, 'localhost',5000);
    return $socket;
}

require('/mnt/wwwroot/dstcar/vendor/yiisoft/yii2/db/MongoDBNew.php');
//转发车辆类
class TcpTransmitCar {
    protected static $config = [
        //dst车辆监控数据库
        'db_car_monidata'=>[
            'dbname'=>'car_monidata',
            'host'=>'120.25.209.72',
            'user'=>'szdst',
            'pwd'=>'571f1480ac650',
        ]
    ];
    protected $logFile;  //记录日志文件
    protected $dbh;      //pdo对象
    protected $sth;      //pdo statement对象
    protected $dbhMongo; //mongo db

    /**
     * 构造函数
     */
    public function __construct(){
        $this->logFile = dirname(__FILE__) . '/' . basename(__FILE__) . '.log';
        $this->connect();
    }

    /*
     * 记录日志
     */
    function writeLog($log){
        $log = date('Y-m-d H:i:s') . ' | ' . $log . "\n";
        file_put_contents($this->logFile, $log, FILE_APPEND);
    }

    /**
     * 链接数据库
     */
    public function connect() {
        $dbConfig = self::$config['db_car_monidata'];
        $dsn = "mysql:dbname=". $dbConfig['dbname'] .";host=" . $dbConfig['host'];
        $this->dbh = new \PDO($dsn, $dbConfig['user'], $dbConfig['pwd'],[
            \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
            //\PDO::ATTR_PERSISTENT=>true, //长链接
            //\PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
        ]);
        
        
        //链接数据库
        try {
//         	$this->dbhMongo = new MongoClient("mongodb://120.76.220.3:27017",array('username'=>'user_it','password'=>'it@dstcar.com2017','db'=>'dstcar'));
        	$this->dbhMongo = new \yii\db\MongoDBNew();
        } catch (Exception $e) {
        	$this->writeLog('Connection mongodb failed: ' . $e->getMessage());
        }
    }

    /**
     * 预处理
     */
    public function prepareSql($sql) {
        $this->sth = $this->dbh->prepare($sql);
        return $this;
    }

    /**
     * 执行sql语句
     */
    public function executeSql($parameters = []) {
        $executeRes = $this->sth->execute($parameters);
        if(!$executeRes && $this->sth->errorCode() == 'HY000'){ //数据库goAway时重连
            $this->connect();
        }
        return $executeRes;
    }

    /**
     * 解析所有内容
     */
    public function fetchAllData() {
        return $this->sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * 获取要转发数据的车辆
     */
    function getTransmitCars(){
        $sql = "SELECT `car_vin`,`mobile`,`province_id`,`city_id`,`manufacturer_id`,`terminer_model`,`terminer_id`,`color`,`identification`,`auth_code`,`last_time`,`protocol_type` FROM `cs_tcp_transmit_car_sz_sf`";
        $this->prepareSql($sql)->executeSql();
        return $this->fetchAllData();
    }

    /*
     * 获取某车辆的位置数据
     * @$carVin   车架号
     * @$lastTime 上次转发数据的最后时间
     */
    function getCarPositionData($carVin,$lastTime){
        $tabName = 'cs_tcp_car_history_data_' . date('Ym') . '_' . substr($carVin,-1);
        //检测该月份的监控数据表是否存在，若不存在则退出
        $sql = "SHOW TABLES LIKE '{$tabName}'";
        $this->prepareSql($sql)->executeSql();
        if (!$this->fetchAllData()) {
            return [];
        }
        if(!$lastTime){
            $lastTime = strtotime(date('Y-m-d'));
        }
        $sql = "SELECT `car_vin`,`collection_datetime`,`latitude_value`,`longitude_value`,`speed`,`direction` FROM `{$tabName}` WHERE `car_vin` = '{$carVin}' AND `collection_datetime` > {$lastTime} ORDER BY `collection_datetime` ASC";
        $this->prepareSql($sql)->executeSql();
        return $this->fetchAllData();
    }
    
    /*
     * 获取某车辆的位置数据
    * @$lastTime 上次转发数据的最后时间
    */
    function getCarPositionGbData($car_vin, $lastTime){
    	$tabName = 'car_history_data_' . date('Ym') . '_'. substr($car_vin,-1);
    	$this->dbhMongo->setCollection($tabName);	//选择当前集合
    	
    	$filter = [
	    	'carVin' => $car_vin,
	    	'collectionDatetime' => ['$gt' => (int)$lastTime]
    	];
    	$options = [
	    	'projection' => [
	    		'_id'=>0,'carVin'=>1,'collectionDatetime'=>1,'latitudeValue'=>1,'longitudeValue'=>1,'speed'=>1
	    	],
	    	'sort' => ['collectionDatetime' => 1],	//collectionDatetime升序
	    	'limit' => 8000
    	];
    	$data = $this->dbhMongo->query($filter,$options);
    	if(!$data){
    		return false;
    	}
    	//数据格式化
    	$retdata = [];
    	foreach ($data as $index=>$obj){
    		$row['car_vin'] = $car_vin;
    		$row['collection_datetime'] = $obj->collectionDatetime;
    		$row['longitude_value'] = $obj->longitudeValue;
    		$row['latitude_value'] = $obj->latitudeValue;
    		$row['direction'] = 0;
    		
    		$retdata[$index] = $row;
    	}
    	unset($data);
    	return $retdata;
    }

    /*
     * 保存某车辆上次转发数据的最后时间
     * @$carVin   车架号
     * @$lastTime 上传转发数据的最后时间
     */
    function updateLastTime($carVin,$lastTime){
        $sql = "UPDATE `cs_tcp_transmit_car_sz_sf` SET `last_time` = {$lastTime} WHERE `car_vin` = '{$carVin}'";
        $this->prepareSql($sql)->executeSql();
    }
}

//建立socket
$socket = createNewSocket();
//实例化对象
$ttcObj = new TcpTransmitCar();
do{
    //获取要转发数据的车辆
    $cars = $ttcObj->getTransmitCars();
    
    //遍历每台车辆，获取位置数据
    foreach($cars as $car){
//     	if($car['protocol_type'] != 1){
//     		continue;
//     	}
    	if($car['protocol_type'] == 1){
    		//国标数据
    		$positionData = $ttcObj->getCarPositionGbData($car['car_vin'],$car['last_time']);
    	}else {
    		$positionData = $ttcObj->getCarPositionData($car['car_vin'],$car['last_time']);
    	}
        
        if($positionData){
            //逐条发送数据
            foreach($positionData as $row){
                $data = [
                    'type'=>'getPositionData',
                    'params'=>array_merge($row,['mobile'=>$car['mobile']]),
                ];
                if(!@socket_write($socket, json_encode($data)."\n")){
                    //$errorCode = socket_last_error($socket);
                    //$errorMsg = socket_strerror($errorCode);
                    //$ttcObj->writeLog("Function socket_write() failed to send data: {$errorMsg}");
                    //若数据发送失败，重新建立socket
                    socket_close($socket);
                    $socket = createNewSocket();
                }
            }
            //全部转发完毕后，将最后时间保存下来以便下次查询新的位置数据
            $ttcObj->updateLastTime($car['car_vin'],$positionData[count($positionData)-1]['collection_datetime']);
        }
        unset($positionData);
    }
    //返回进程信息
    $processInfo = json_decode(file_get_contents($processInfoFilePath),true);
    $processInfo['activeTime'] = time();
    $processInfo['memory'] = memory_get_usage();
    file_put_contents($processInfoFilePath, json_encode($processInfo));
}while(true);
//socket_close($socket);

