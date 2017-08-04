<?php
date_default_timezone_set('PRC');
class TaskMain {
    protected static $config = [
        /*'db_dsn' => 'mysql:host=120.76.114.155;dbname=car_system', 
        'db_username' => 'szdst',
        'db_password' => 'szdst123',*/
        'db_dsn' => 'mysql:host=rm-wz9k9ct8av553n1jt.mysql.rds.aliyuncs.com;dbname=car_system', 
        'db_username' => 'user_carsystem',
        'db_password' => 'CLY7dzc8WRUQ',
    ];
    protected $taskDatetime;
    protected $dbhSystem;//管理系统数据库链接资源
    public function __construct() {
        //本次任务执行时间
        $this->taskDatetime = time();
    }
    public function main() {
        $tryTimes = 5;
        do{
            try{
                $this->dbhSystem = new \PDO(self::$config['db_dsn'],self::$config['db_username'],self::$config['db_password'],[
                    \PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8',
                    \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION,
                ]);
                
                break;
            }catch(\Exception $e){
                $tryTimes --;
            }
        }while($tryTimes > 0);
        if(empty($this->dbhSystem)){
            echo 'can not connect to mysql!';
            return;
        }
        $tasks = $this->getTask();
        if(!$tasks){
            //没有要执行的任务
            return true;
        }
        foreach($tasks as $val){
            $this->getProcessDoTask($val);
        }
    }

    /**
     * 获取当前时刻需要执行的计划任务
     */
    protected function getTask(){
        $tasks = [];
        $sql = 'select * from `cs_system_task` where in_use = 1';
        $sth = $this->dbhSystem->prepare($sql);
        $sth->execute();
        $activeTasks = $sth->fetchAll(\PDO::FETCH_ASSOC);
        
        if(!$activeTasks){
            return $tasks;
        }
        //检测任务是否需要立即执行
        $month = date('m',$this->taskDatetime);
        $day  = date('d',$this->taskDatetime);
        $hour = date('H',$this->taskDatetime);
        $minute = date('i',$this->taskDatetime);
        $week = '0'.date('w',$this->taskDatetime);
        foreach($activeTasks as $val){
            $pattern = "/^(?:\*|(?:.*?){$minute}(?:.*?))\|(?:\*|(?:.*?){$hour}(?:.*?))\|(?:\*|(?:.*?){$day}(?:.*?))\|(?:\*|(?:.*?){$month}(?:.*?))\|(?:\*|(?:.*?){$week}(?:.*?))$/";
            if(preg_match($pattern, $val['exec_frequency'])){
                $tasks[]  = $val;
            }
        }
        return $tasks;
    }

    /**
     * 打开新进程执行指定任务
     */
    protected function getProcessDoTask($task) {
        $pid = pcntl_fork();
        if($pid){
            //父进程
            $sql = 'update cs_system_task set pid = '.$pid.',`last_exec_datetime` = "'.date('Y-m-d H:i:s').'" where id = '.$task['id'];
            $sth = $this->dbhSystem->prepare($sql);
            $sth->execute();
        }else{
            //子进程
            ob_start();
            $log = date('Y-m-d H:i:s').': '.$task['exec_command']."\r\n";
            file_put_contents(dirname(__FILE__).'/task.log',$log,FILE_APPEND);
            shell_exec($task['exec_command']);
            ob_end_clean();
            sleep(2);//等待init进程接管
            die;//防止子进程执行父进程foreach内容
        }
    }

    public function __destruct() {
        $this->dbhSystem = null;
    }
}
(new TaskMain())->main();