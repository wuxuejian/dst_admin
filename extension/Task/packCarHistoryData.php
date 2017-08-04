<?php
/*
 * 打包车辆历史数据，每月1号0点执行，打包上月数据
 */
class PackCarHistoryData {
	protected $logFile;         //记录日志文件
	public function __construct(){
		$this->logFile = dirname(__FILE__).'/packCarHistoryData.php.log';
	}
	function main(){
		$date = date("Ym",strtotime("-2 month"));	//上上月
		$this->writeLog('start...打包数据');
		for($i=0; $i<10; $i++){
			$shell = 'mysqldump -h127.0.0.1 -uroot -p4Z3uChwl car_monidata cs_tcp_car_history_data_'.$date.'_'.$i.' | gzip > /home/szdst/cs_tcp_car_history_data_'.$date.'_'.$i.'.sql.zip';
			$this->writeLog($shell);
			shell_exec($shell);
		}
		$this->writeLog('end...打包数据');
	}
	/*
	 * 记录日志
	*/
	function writeLog($log){
		$log = date('Y-m-d H:i:s') . ' ' . $log . "\n";
		file_put_contents($this->logFile, $log, FILE_APPEND);
		//echo $log,"\n";
	}
}

//实例化对象，并执行入口函数
$pchObj = new PackCarHistoryData();
$pchObj->main();