<?php
namespace backend\models;

use Yii;
use backend\models\SysUserLog;
use backend\classes\TimeSearch; // 引入时间查询器

/**
 * This is the model class for table "cs_sys_user_log".
 * @name 用户日志查询模型
 * @author tanbenjiang
 * 
 */
class SysUserLogSearch extends SysUserLog
{
	/**
	 * @name 查询操作
	 * @author tanbenjiang
	 * @param array $params
	 */
    public static function search(array $params){
     	$query = SysUserLog::find();

    	$query = $query->andFilterWhere(['like','user_name',$params['user_name']])
    				   ->andFilterWhere(['like','action',$params['action']])
    				   ->andFilterWhere(['like','ip',$params['ip']]);
    	
    	// 日志类型
    	if(isset($params['log_type']) && $params['log_type'] != ''){
    	    $query = $query->andFilterWhere(['log_type'=>strval($params['log_type'])]);
    	}

    	// 若不是开发人员，或者是开发人员模拟其他用户登录的，则只能看到普通日志
    	if( (!isset($_SESSION['backend']['adminInfo']['super']) || $_SESSION['backend']['adminInfo']['super'] != 1) || 
			(isset($_SESSION['backend']['adminInfo']['super']) && $_SESSION['backend']['adminInfo']['super'] == 1 && isset($_SESSION['backend']['simulation']))
		){
    	    $query = $query->andFilterWhere(['is_super'=>0]);
    	}
    	
    	// 引入时间查询器
    	$query = TimeSearch::timeSeach($query,$params['search_timeduan'],$params['start_time'],$params['end_time'],'log_time');
    	 
    	return $query;  //返回查询对象 
    }
}
