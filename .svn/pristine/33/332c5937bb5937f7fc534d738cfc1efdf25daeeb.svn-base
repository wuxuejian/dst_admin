<?php
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//发送超时
socket_set_option($socket,SOL_SOCKET,SO_SNDTIMEO,array("sec"=>3,"usec"=>0 ));
//接收超时
socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,array("sec"=>60,"usec"=>0 ));

socket_connect($socket, 'localhost',5000);

date_default_timezone_set('PRC');

class TcpTransmitCar {
    protected static $config = [
        'db_car_monidata'=>[
            'dbname'   => 'car_monidata',
            'host'     => '120.25.209.72',
            'user'     => 'szdst',
            'pwd'      => '571f1480ac650',
//            'host'=>'localhost',
//            'user'=>'root',
//            'pwd'=>'4Z3uChwl',
        ]
    ];
    protected $dbhMonidata; //数据库连接资源

    public function __construct(){
        //连接数据库
        try {
            $dbConfig = self::$config['db_car_monidata'];
            $dsn = "mysql:dbname=". $dbConfig['dbname'] .";host=" . $dbConfig['host'];
            $this->dbhMonidata = new \PDO($dsn, $dbConfig['user'],$dbConfig['pwd'],[
                \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
                \PDO::ATTR_PERSISTENT=>true, //长链接
                \PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }


    /*
     * 获取要转发车辆的配置信息
     */
    function getTransmitCars(){
        $sql = "SELECT `car_vin`,`mobile`,`province_id`,`city_id`,`manufacturer_id`,`terminer_model`,`terminer_id`,`color`,`identification`,`auth_code` FROM `cs_tcp_transmit_car_sz_sf`";
        $sth = $this->dbhMonidata->prepare($sql);
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }


    /*
     * 获取某车辆的位置数据
     */
    function getCarPositionData($carVin){
        //$tabName = 'cs_tcp_car_history_data_' . date('Ym') . '_' . substr($carVin,-1);
        $tabName = 'cs_tcp_car_history_data_201605_' . substr($carVin,-1);
        $sql = "SELECT `car_vin`,`collection_datetime`,`latitude_value`,`longitude_value`,`speed`,`direction` FROM `{$tabName}` WHERE `car_vin` = '{$carVin}' LIMIT 1";
        $sth = $this->dbhMonidata->prepare($sql);
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }


}

//实例化对象
$ttcObj = new TcpTransmitCar();
$cars = $ttcObj->getTransmitCars();
$carMessage = $ttcObj->getCarPositionData($cars[0]['car_vin']);
$carMessage[0]['mobile'] = substr($cars[0]['mobile'],-11);
var_dump($carMessage[0]['mobile']);
$data = [
    'type'=>'getPositionData',
    'params'=>$carMessage[0],
];
var_dump(json_encode($data));
for($i=0;$i<10;$i++){
   socket_write($socket, json_encode($data)."\n");
   sleep(1);
}