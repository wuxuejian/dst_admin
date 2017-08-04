<?php
/**
 * 数据库链接类非单例模式
 */
class DB{
    protected $config;//配置
    protected $dbh;//pdo对象
    protected $sth;//pdo statement对象
    public function __construct(array $config){
        $config['port'] = empty($config['port']) ? 3306 : $config['port'];
        $config['charset'] = empty($config['charset']) ? 'utf8' : $config['charset'];
        $this->config = $config;
        $this->connect();
    }

    /**
     * 链接数据库
     */
    public function connect() {
        $dsn = 'mysql:dbname='.$this->config['dbname'].';host='.$this->config['host'].';port='.$this->config['port'].';charset='.$this->config['charset'];
        $dbh = new PDO($dsn, $this->config['username'], $this->config['password']);
        $this->dbh = $dbh;
    }

    /**
     * 预处理
     */
    public function prepare($sql) {
        $this->sth = $this->dbh->prepare($sql);
        return $this;
    }

    /**
     * 解析一条内容
     */
    public function fetchOne($parameters = []) {
        $res = $this->fetchAll($parameters);
        if($res && isset($res[0])){
            return $res[0];
        }else{
            return $res;
        }
    }

    /**
     * 解析所有内容
     */
    public function fetchAll($parameters = []) {
        //$this->execute($parameters);
        return $this->sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 执行sql语句
     */
    public function execute($parameters = []) {
        $executeRes = $this->sth->execute($parameters);
        if(!$executeRes && $this->sth->errorCode() == 'HY000'){ //数据库goAway时重连
            $this->connect();
        }
        return $executeRes;
    }

    /**
     * 获取statement对象
     */
    public function getStatement() {
        return $this->sth;
    }
}


//---测试---
/*$config = [
    'dbname'=>'car_monidata',
    'host'=>'120.76.114.155',
    'username'=>'szdst',
    'password'=>'szdst123',
];
$obj = new DB($config);
$sql = "select id,car_vin from cs_tcp_transmit_car_sz_sf";
$obj->prepare($sql);
if(!$obj->execute()){
    echo "failed \n";
}else{
    echo "successful \n";
}
//$obj->prepare($sql)->execute();
var_dump($obj->fetchAll());*/

