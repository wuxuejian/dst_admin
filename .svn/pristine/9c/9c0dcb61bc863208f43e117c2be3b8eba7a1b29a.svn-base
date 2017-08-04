<?php
/**
* 连接充电桩的指定前置机数据库
* @author chengwk
* @time   2015-12-30 16:40
*/
namespace backend\classes;
use Yii;

class FrontMachineDbConnection{
	
  /**
   * 连接充电桩前置机数据库
   */
	public function connectFrontMachineDb($host, $username, $password, $port, $dbName, $tablePrefix='', $charset='utf8')
	{
		try {
			$fmConnection = new \yii\db\Connection([
				'dsn' => "mysql:host={$host};port={$port};dbname={$dbName}",
				'username' => $username,
				'password' => $password,
				'charset' => $charset,
				'tablePrefix' => $tablePrefix
			]);
			return $fmConnection;
		} catch (Exception $e) {
            return '前置机数据库连接异常！';
		}	
	}
	
	
}