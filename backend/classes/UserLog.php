<?php
namespace backend\classes;
use Yii;
use backend\models\SysUserLog;

/**
 * @name 系统用户日志类
 * @author tanbenjiang
 * @date 2015-6-11
 * 
 */
class UserLog{
	
  /**
   * @name 向数据表cs_sys_user_log插入日志记录
   * @author tanbenjiang
   * @param string $action 操作描述[组合字符串]
   * @param string $logType 日志类型【系统管理:sys 会员操作:mem 商户操作:bus 平台资金操作:fund 订单管理:order 生产加工管理:pro 产品操作:etp 其他:other】
   */
	public static function log($action, $logType='other')
	{
		// 验证参数
		if(!isset($action))
		{
			return ['success'=>false,'message'=>'参数有误！'];
			exit;
		}
		
		// 初始化相关参数
		$qstring = self::getUrl(); // 网址参数
		$backendArr = Yii::$app->session->get('backend');
		$userId = $backendArr['adminInfo']['id']; 			// 用户ID
		$userName = $backendArr['adminInfo']['username'];   // 用户名
		$isSuper = $backendArr['adminInfo']['super'];   	// 是否开发人员
		$ip = self::getIp();
		$logTime = time(); // 日志记录时间
		
        // 记录日志
        $model = new SysUserLog();
        $model->log_type = $logType;
        $model->qstring = $qstring;
        $model->action = $action;
        $model->user_id = $userId;
        $model->user_name= $userName;
        $model->is_super = $isSuper;
        $model->ip = $ip;
        $model->log_time = $logTime;
        
        if($model->save())
        {
        	return ['success'=>true,'message'=>'日志记录成功！'];
        } else {
        	return ['success'=>false,'message'=>'日志记录失败！', 'error'=> $model->errors];
        }
	}
	
	
	
	/**
	 * @name 获取真实的客户端的IP地址
	 * @return $ip
	 * 
	 */
	public static function getIp() 
	{
		$unknown = 'unknown';
		
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) 
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) 
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		 
		if (false !== strpos($ip, ',')) $ip = reset(explode(',', $ip));
		
		return $ip;
	}
	
	
	/**
	 * @name 获取请求Url地址
	 * @author tanbenjiang
	 * 
	 */
	public static function getUrl()
	{
		$url = Yii::$app->request->hostInfo.Yii::$app->request->getUrl();
		return $url;
	}
	
	
}