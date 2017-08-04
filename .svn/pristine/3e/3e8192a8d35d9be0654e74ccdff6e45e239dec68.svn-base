<?php
/**
* 连接充电桩的指定前置机数据库
* @author chengwk
* @time   2015-12-30 16:40
*/
namespace backend\classes;
use backend\models\ChargeFrontmachine;
use Yii;

class FrontMachineDB{

    /**
     * 根据前置机id获取前置机数据库链接资源
     * @param  int    $id 前置机id
     * @return array      [status,info/object]
     */
    public static function getConnByID($id){
        //获取前置机记录
        $fmRecord = ChargeFrontmachine::find()
            ->select(['db_username','db_password','db_port','db_name'])
            ->where(['id'=>$id])->asArray()->one();
        if(!$fmRecord){
            return [false,'没找到对应的前置机数据！'];
        }
    }
    
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