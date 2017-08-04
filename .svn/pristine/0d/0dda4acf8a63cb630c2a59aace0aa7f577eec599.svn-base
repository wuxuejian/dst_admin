<?php  
/**
 * MongoDB 新操作类
 * User: pengyl
 * Date: 06/06/2017
 */

namespace db;

class MongoDBNew  
{
  const dbName = 'dstcar';

  private $_col;
  private $_manager;

  public function __construct($collection='')
  {
    $this->_col = $collection;
    $this->_manager = null;
  }

  /**
   * 获取集合完整名字
   * @return string
   */
  protected function getFullName () {
    return MongoDBNew::dbName . '.' . $this->_col;
  }

  public function setCollection($collection) {
  	$this->_col = $collection;
  }
  
  public function dropCollectionData($collection){
  	$command = new \MongoDB\Driver\Command([
  			'drop' => $collection
  			]);
  	$this->getManager()->executeCommand(self::getDb(),$command);
  }
  
  public function listCommands(){
  	$command = new \MongoDB\Driver\Command(array("listCommands" => 1));
  	return $this->getManager()->executeCommand(self::getDb(),$command)->toArray();
  }
  
  /**
   * 获取集合名
   * @return string
   */
  protected function getCollection() {
    return $this->_col;
  }

  /**
   * 获取表名
   * @return string
   */
  public function getDb() {
    return self::dbName;
  }

  /**
   * 获取连接对象
   * @return \MongoDB\Driver\Manager
   */
  protected function getManager () {
    if ($this->_manager === null) {
		$demo_seed1 = 'dds-wz9703d55c77df741.mongodb.rds.aliyuncs.com:3717';
	    $demo_seed2 = 'dds-wz9703d55c77df742.mongodb.rds.aliyuncs.com:3717';
	    $demo_replname = "mgset-3456847";
	    $demo_user = 'root';
	    $demo_password = 'Dstcar_520';
	    $demo_db = 'admin'; 
	    # 根据实例信息构造mongodb connection string
	    $demo_uri = 'mongodb://' . $demo_user . ':' . $demo_password . '@' .
			$demo_seed1 . ',' . $demo_seed2 . '/' . $demo_db . '?replicaSet=' . $demo_replname;
		$this->_manager = new \MongoDB\Driver\Manager($demo_uri);
    }
//     if ($this->_manager === null) {
//     	$passwd = rawurlencode('it@dstcar.com2017');
//     	$username = 'user_it';
//     	$uri = "mongodb://{$username}:{$passwd}@120.76.220.3:27017/dstcar";
//     	$this->_manager = new \MongoDB\Driver\Manager($uri);
//     }
// 	print_r($this->_manager);
// 	exit;
    return $this->_manager;
  }

  /**
   * 执行查询操作
   * @param $filter 筛选条件
   * @param array $options  选项
   * @return array
   */
  public function query($filter, $options = []) {
    $query = new \MongoDB\Driver\Query($filter, $options);
    $cursor = $this->getManager()->executeQuery($this->getFullName(), $query);
    return $cursor->toArray();
  }

  /**
   * 查询单条数据
   * @param array $filter
   * @param array $options
   * @return array
   */
  public function queryOne ($filter, $options = ['limit' => 1]) {
    $res = $this->query($filter, $options);
	if($res){
		return (array)$res[0];
	}
    return $res;
  }

  /**
   * 增加一条数据
   * @param array $param
   * @return int|null 如果成功返回增加的条数，否则返回null
   */
  public function insert($param) {
    $bulk = new \MongoDB\Driver\BulkWrite;
    $bulk->insert($param);
    $res = $this->getManager()->executeBulkWrite($this->getFullName(), $bulk);

    return $res->getInsertedCount();
  }

  /**
   * 修改数据
   * @param array $filter
   * @param array $data
   * @param array $option
   * @return int|null 如果成功返回受影响行数, 否则返回null
   */
  public function update ($filter, $data, $option = []) {
    $bulk = new \MongoDB\Driver\BulkWrite;
    $bulk->update($filter, $data, $option);
    $result = $this->getManager()->executeBulkWrite($this->getFullName(), $bulk);

    return $result->getModifiedCount();
  }

  /**
   * 删除数据
   * @param array $filter
   * @param array $option
   * @return int|null 如果删除成功，返回删除数据条数，否则返回0
   */
  public function delete ($filter, $option = []) {
    // 安全起见，如果没有传递筛选器，则不允许删除
    if (!count($filter)) {
      return;
    }

    $bulk = new \MongoDB\Driver\BulkWrite;
    $bulk->delete($filter, $option);
    $res = $this->getManager()->executeBulkWrite($this->getFullName(), $bulk);

    return $res->getDeletedCount();
  }

  /**
   * 获取数据长度
   * @param $filter
   */
  public function getCount($filter) {
    $command_count = new \MongoDB\Driver\Command([
      'count' => $this->getCollection(),
      'query' => $filter
    ]);

    $cursor = $this->getManager()->executeCommand(self::getDb(),$command_count);
    $info = $cursor->toArray();
    return $info[0]->n;
  }
  
  public function checkCollectionIsExist($collectionName){
  	$command = new \MongoDB\Driver\Command(['count' => $collectionName]);
  	$info = $this->getManager()->executeCommand(self::getDb(), $command)->toArray();
  	return $info[0]->n;
  }

  /**
   * 获取聚合数据
   * @param array $match
   * @param array $group
   * @return array
   */
  public function aggregate($match, $group) {
    $command = new \MongoDB\Driver\Command([
      'aggregate' => $this->getCollection(),
      'pipeline' => [
        ['$match' => $match],
        ['$group' => $group]
      ]
    ]);

    $data = $this->getManager()->executeCommand(self::getDb(), $command);

    return $data->toArray();
  }
}