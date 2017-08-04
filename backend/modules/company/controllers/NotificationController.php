<?php

/**
 * @name 系统通知管理
 * @author tanbenjiang
 * @date: 2015-6-23
 */
namespace frontend\modules\company\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\data\Pagination;
use common\models\SysMessage;
use common\models\SysCodeDetail;
use common\models\EcsUsers;
use common\models\BusCompany;
use common\models\SysUser;
use common\models\ProProduceUser;
use backend\classes\Common;

class NotificationController extends \yii\web\Controller 
{
	
	public $enableCsrfValidation = false;
	
	public function actionIndex() 
	{
		// 操作按钮控制
    	$data['urls'] = [
    	'look'  => url::to(['notification/look-message']),
    	];
		
		$datas['buttons'] = [
    	'look'=>array('text'=>'查看通知','class'=>'icon-tip', 'click'=>"Notification.lookMessage('{$data['urls']['look']}',2,'my-message-look','查看通知')"),
    	'detele'=>array('text'=>'删除通知','class'=>'icon-delete', 'click'=>'Notification.delMessage()')
    	];
		
		//商户列表信息
    	$company = new BusCompany();
    	$datas['companylist'] = $company->find()->asArray()->all();
    	
    	$datas['urls'] = array(
    			'save' => \Yii::$app->urlManager->createUrl('company/message/save'),
    			'look' => \Yii::$app->urlManager->createUrl('company/message/look'),
    			'deleted' => \Yii::$app->urlManager->createUrl('company/message/delete'),
    			'getUser' => \Yii::$app->urlManager->createUrl('company/message/getuser'),
    			'messagelist' => \Yii::$app->urlManager->createUrl('company/message/messagelist'),
    			'savereply' => \Yii::$app->urlManager->createUrl('company/message/savereply'),
    	);
    	
    	// 获取通知类型
    	$datas['notification_type'] = Common::getSysCodeDetail('notification_type');
    	
    	//消息类型
    	$message_type = new SysCodeDetail();
    	$message_type_query = $message_type->find()->where(['code_id' => 31])->asArray()->all();
    	$datas['message_type_query'] = $message_type_query;
    	
        return $this->renderPartial('index',$datas);
	}
	
	/**
	 * @name 远程获取通知列表
	 * @author tanbenjiang
	 *
	 */
	public function actionMessagelist()
	{
		// 获取通知类型
		$notificationType = Yii::$app->request->get('type_id');
		 
		//查询当前登录的商户信息
		$boss_id = Yii::$app->session['bBossInfo']['id'];
		 
		$param = \Yii::$app->request->post();
		$response = ['success'=>false, 'message'=>''];
		$page = ($param['page'] ? $param['page'] : 1);
		$rows = ($param['rows'] ? $param['rows'] : 10);
	
		$model = new SysMessage();
		$query = $model->find()
		->where(['typeid'=>0,'sub_typeid'=>$notificationType,'parent_id' => 0,'deleted' => 0])
		->with(['company','company1','sysUser','sysUser1','ecsUser','proProduceUser']);
		$query->andFilterWhere(['to_user' => $boss_id]); // 收件人ID
		 
		//查询类型
		switch ($param['type'])
		{
			case 1:
				//所有消息
				break;
			case 2:
				//所有接受的消息
				$query->andFilterWhere(['to_user' => $boss_id]);
				$query->andFilterWhere(['to' => 2]);
				break;
			case 3:
				//所有发送的消息    当前登录的用户，默认为admin
				$query->andFilterWhere(['from_user' => $boss_id]);
				break;
			case 4:
				//未阅读的消息
				$query->andFilterWhere(['is_read' => 0]);
				break;
			case 5:
				//已阅读的消息
				$query->andFilterWhere(['is_read' => 1]);
				break;
			default:
				//所有消息
				break;
		}
		 
		$queryClone = clone $query;
		$totalCount = $queryClone->count();
		$pages = new Pagination(['defaultPageSize'=>$rows,'totalCount'=>$totalCount,'pageSizeLimit'=>false]);		
		$query = $query->asArray()
					   ->orderBy('add_time desc')
					   ->offset(($page-1)*$rows)
					   ->limit($pages->limit)
					   ->all();
		 
		// 处理显示数据
		foreach($query as $key=>$val)
		{
			// 处理显示标题
			$query[$key]['title'] = Common::cutStr($query[$key]['title'], 30);
			
			// $ecsuser = $val['proProduceUser'];
			$ecsuser = $val['ecsUser'];
	
			/*****************************收件人数据处理  商户***************************/
			if($val['to'] == 1)
			{
				// 商户（自己）发送消息给会员
				$query[$key]['company_name'] = $ecsuser['user_name'];
			}elseif($val['to'] == 2){
				// 其他人发送消息给商户（自己）
				$company = $val['company'];
				$query[$key]['company_name'] = $company['company_name'];
			}else{
				//商户（自己）发送消息给系统（管理员）,提取管理员的用户名
				$sysUser1 = $val['sysUser1'];
				$query[$key]['company_name'] = $sysUser1['user_name'];
			}
	
			/*****************************发件人数据处理  商户***************************/
			if($val['from'] == 1){//会员发送消息给商户（自己）
				$query[$key]['user_name'] = $ecsuser['user_name'];
			}elseif($val['from'] == 0){
				// 发件人类型为系统，则提取对应管理员
				$sysUser = $val['sysUser'];
				$query[$key]['user_name'] = $sysUser['user_name'];
					
			}elseif($val['from'] == 2){//商户（自己）发送消息给会员或者系统
				$company1 = $val['company1'];
				$query[$key]['user_name'] = $company1['company_name'];
			}
		}
	
		// 返回分页参数
		$datas = ['total' => $totalCount, 'rows' => $query];
		 
		echo json_encode($datas);
	
	}
	
	/**
	 * @name 查看消息操作
	 * @author yangping
	 * @param string $status Url中传入操作状态，做判断用(判断Url传递是否成功)
	 * @return mixed
	 * 
	 */
	public function actionLookMessage() 
	{
		//商户列表信息
		$company = new BusCompany();
		$datas['companylist'] = $company->find()
		                                ->asArray()
		                                ->all();
	
		$datas['urls'] = array(
				'save' => \Yii::$app->urlManager->createUrl('company/message/save'),
				'look' => \Yii::$app->urlManager->createUrl('company/message/look'),
				'deleted' => \Yii::$app->urlManager->createUrl('company/message/delete'),
				'getUser' => \Yii::$app->urlManager->createUrl('company/message/getuser'),
				'messagelist' => \Yii::$app->urlManager->createUrl('company/message/messagelist'),
				'savereply' => \Yii::$app->urlManager->createUrl('company/message/savereply'),
		);
		
		// 当前登录用户id
		$now_user  = Yii::$app->session['bBossInfo']['id'];
		
		// 更新消息为已读状态
		if(!empty($_GET['itemid']))
		{
			// 未读 且 发送人不是当前登录用户
			if($_GET['is_read'] == 0 && $_GET['from_user'] != $now_user)
			{
				$model = new SysMessage();
				$comm = $model::find()->where( ['itemid'=>$_GET['itemid']])
				                      ->one();
				
				$comm->setAttribute('is_read', 1);
				if($comm->save())
				{
					$response['success'] = true;
					$response['message'] = '状态更新成功！';
				}else
				{
					$response['success'] = false;
					$response['message'] = '状态更新失败！';					
				}
			}
		}
		 
		//当前查看消息
		$sys_message = new SysMessage();
		
		$sys_message_list = $sys_message->find()
		                                ->where(['itemid' => $_GET['itemid']])
		                                ->asArray()
		                                ->one();    //获取当前查看的消息  一维数组
		// 处理消息类型显示
		if(!empty($sys_message_list))
		{
			$notifcationType = Common::getSysCodeDetail('notification_type');
			foreach($notifcationType as $key=>$value)
			{
				if($sys_message_list['sub_typeid'] == $value['code_key'])
				{
					$sys_message_list['typename'] = $value['code_value'];
				}
			}
		}
		
		// 格式化当前消息的发送时间
		$sys_message_list['add_time'] = date("Y-m-d H:i:s",$sys_message_list['add_time']);	 

		return $this->renderPartial('look-message',['datas'=>$sys_message_list]);
	}
	
	
	
	/**
	 * @name 获取当前查看消息的所有下级回复   递归调用
	 * @param integer $itemid 消息ID
	 * @param array $arr 存储消息信息的数组
	 * @return unknown
	 * 
	 */
	public function getAllNextReply($itemid,$arr)
	{
		// 注意一定要强制排序，否则会造成数组排序不正确
		$is_eq_level = SysMessage::find()->where(['parent_id' => $itemid])
		                                 ->asArray()
		                                 ->orderBy(['itemid'=>SORT_ASC])
		                                 ->all();
		// 计算数组的长度
		$count = count($is_eq_level);
		//echo $itemid;   
		// 判断是否有多条回复  如果有则循环添加进数组
		if($count > 0)
		{
			foreach ($is_eq_level as $is_eq_levelk => $is_eq_levelv)
			{
				// 处理时间显示
				$is_eq_level[$is_eq_levelk]['time'] = date("Y-m-d H:i:s",$is_eq_levelv['add_time']);
				array_push($arr, $is_eq_level[$is_eq_levelk]);
				                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
				// 多条回复的最后一条回复调用递归查询其下的所有回复
				if(($is_eq_levelk+1) === $count)
				{
				   $arr = $this->getAllNextReply($is_eq_levelv['itemid'],$arr); // 递归计算出每条子回复是否还有回复
				}	
			}
			
		}
		
		return $arr;
		
	}
	
	
	/**
	 * 获取当前查看消息的所有上级回复   递归调用
	 * @param unknown $parent_id
	 * @param unknown $arr
	 * @return unknown
	 */
	public function getAllBeforeReply($parent_id,$arr){
		
		if($parent_id != 0){
    		$is_eq_level = SysMessage::find()->with(['company1','sysUser1'])->where(['parent_id' => $parent_id])->asArray()->all();
    	}
		$con = count($is_eq_level);
		//判断上级是否有多条回复  如果有则循环添加进数组
		if($con > 1){
			foreach ($is_eq_level as $is_eq_levelk => $is_eq_levelv){
				$is_eq_level[$is_eq_levelk]['time'] = date("Y-m-d H:i:s",$is_eq_levelv['add_time']);
				array_push($arr, $is_eq_level[$is_eq_levelk]);
			}
		}
		 
		$result = SysMessage::find()->with(['company1','sysUser1'])->where(['itemid' => $parent_id])->asArray()->all();
		$count = count($result);
		if($count == 0){
			return $arr;
		}else{
			foreach ($result as $resultk => $resultv){
				$result[$resultk]['time'] = date("Y-m-d H:i:s",$resultv['add_time']);
				array_push($arr, $result[$resultk]);
				if(($resultk+1) == $count){
					return $this->getAllBeforeReply($resultv['parent_id'],$arr);
				}
			}
		}
		 
	
	}
	
	/**
	 * 数组重新按时间升序
	 * @param unknown $allreply
	 * @return unknown
	 */
	public function getOrderByData($allreply){
		foreach ($allreply as $key => $value) {
			$name[$key] = $value['add_time'];
		}
		array_multisort($name, $allreply);
		return $allreply;
		 
	}
	
	/**
	 * 回复消息页面
	 * @return string
	 */
	public function actionReplyMessage()
	{
		 
		//商户列表信息
		$company = new BusCompany();
		$datas['companylist'] = $company->find()->asArray()->all();
		 
		$datas['urls'] = array(
				'save' => \Yii::$app->urlManager->createUrl('company/message/save'),
				'look' => \Yii::$app->urlManager->createUrl('company/message/look'),
				'deleted' => \Yii::$app->urlManager->createUrl('company/message/delete'),
				'getUser' => \Yii::$app->urlManager->createUrl('company/message/getuser'),
				'messagelist' => \Yii::$app->urlManager->createUrl('company/message/messagelist'),
				'savereply' => \Yii::$app->urlManager->createUrl('company/message/savereply'),
		);
		
		//当前查看消息
		$sys_message = new SysMessage();
		$sys_message_list = $sys_message->find()->with(['company1','sysUser1'])->where(['itemid' => $_GET['itemid']])->asArray()->one();//获取当前查看的消息  一维数组
		//时间格式化
		$sys_message_list['time'] = date("Y-m-d H:i:s",$sys_message_list['add_time']);
	
		//获取下级回复
		$allreply = $this->getAllNextReply($sys_message_list['itemid'],array());
		array_push($allreply, $sys_message_list);
	
		//获取上级回复
		if($sys_message_list['parent_id'] != 0){
			//调用递归函数
			$allbeforereply = $this->getAllBeforeReply($sys_message_list['parent_id'],array());
			foreach ($allbeforereply as $allbeforereplyk => $allbeforereplyv){
				array_push($allreply, $allbeforereply[$allbeforereplyk]);
			}
		}
		 
		//商户会员
		$proProduceUser = new ProProduceUser();
		$proProduceUserlist = $proProduceUser->find()->where(['deleted' => 0])->asArray()->all();
		//商户
		$busCompany = new BusCompany();
		$busCompanylist = $busCompany->find()->where(['deleted' => 0])->asArray()->all();
		//管理员
		$sysUser = new SysUser();
		$sysUserlist = $sysUser->find()->where(['deleted' => 0])->asArray()->all();
		//商城会员
		$ecsUsers = new EcsUsers();
		$ecsUserslist = $ecsUsers->find()->asArray()->all();
		//$userid = Yii::$app->session->get('sUserId');
		//查询当前登录的商户信息
		$boss_id = Yii::$app->session['bBossInfo']['id'];
		$newallreply = $this->getOrderByData($allreply);//数组按时间查询排序  升序
		$replyinfo = array();
		$itemids = '';
		foreach ($newallreply as $newk => $newv){
			//如果是当前登录用户则显示在左边   否则右边
			if($newv['from_user'] == $boss_id && $newv['from'] == 2)
			{
				$newallreply[$newk]['direction'] = 'left';
			}else{
				$replyinfo = $newallreply[$newk];
				$newallreply[$newk]['direction'] = 'right';
				$itemids .= $newv['itemid'];
				$itemids .= ',';
			}
			
			//消息来自系统  则发件人来自表sys_user
			if($newv['from'] == 0)
			{
				foreach ($sysUserlist as $sysUserlistk => $sysUserlistv){
					if($sysUserlistv['id'] == $newv['from_user']){
						$newallreply[$newk]['from_name'] = $sysUserlistv['user_name'];
						$newallreply[$newk]['from_name_avatar'] = $sysUserlistv['small_avatar'];
					}
				}
				//消息来自系统  则发件人来自表pro_produceUser
			}elseif($newv['from'] == 1){
				foreach ($proProduceUser as $proProduceUserk => $proProduceUserv){
					if($proProduceUserv['itemid'] == $newv['from_user']){
						$newallreply[$newk]['from_name'] = $proProduceUserv['user_name'];
					}
				}
				//消息来自系统  则发件人来自表bus_company
			}elseif($newv['from'] == 2){
				foreach ($busCompanylist as $busCompanylistk => $busCompanylistv){
					if($busCompanylistv['id'] == $newv['from_user']){
						$newallreply[$newk]['from_name'] = $busCompanylistv['company_name'];
					}
				}
			}
		}
		$datas['allreplylist'] = $newallreply;
		$datas['replyinfo'] = $replyinfo; //返回最后一条对方回复的消息
		$datas['itemids'] = $itemids; //返回对方所有回复的消息id
		return $this->renderPartial('reply-message',$datas);
	}
	
	/**
	 * @name 发送消息
	 * @param string $status Url中传入操作状态，做判断用(判断Url传递是否成功)
	 * @return mixed
	 */
	public function actionAddMessage() {
		//商户列表信息
		$company = new BusCompany();
		$datas['companylist'] = $company->find()->asArray()->all();
		 
		$datas['urls'] = array(
				'save' => \Yii::$app->urlManager->createUrl('company/message/save'),
				'look' => \Yii::$app->urlManager->createUrl('company/message/look'),
				'deleted' => \Yii::$app->urlManager->createUrl('company/message/delete'),
				'getUser' => \Yii::$app->urlManager->createUrl('company/message/getuser'),
				'messagelist' => \Yii::$app->urlManager->createUrl('company/message/messagelist'),
				'savereply' => \Yii::$app->urlManager->createUrl('company/message/savereply'),
		);
		 
		//返回消息类型
		$message_type = new SysCodeDetail();
		$message_type_query = $message_type->find()->where(['code_id' => 31])->asArray()->all();
		$datas['message_type_query'] = $message_type_query;
		 
		return $this->renderPartial('add-message',$datas);
	}

	
	/**
	 * 获取用户信息
	 */
	public function actionGetuser(){
		$level_id = $_GET['level_id'];
		 
		//1  管理员
		if($level_id == 1){
			$sys_user = new SysUser();
			$sys_user_query = $sys_user->find()->where(['deleted' => 0])->asArray()->all();
			foreach ($sys_user_query as $sys_user_queryk => $sys_user_queryv){
				$sys_user_query[$sys_user_queryk]['user_id'] = $sys_user_queryv['id'];
				$sys_user_query[$sys_user_queryk]['user_name'] = $sys_user_queryv['user_name'];
			}
			echo json_encode($sys_user_query);
			
		//2 会员
		}elseif($level_id ==2){
			$pro_produce_user = new ProProduceUser();
			$pro_produce_user_query = $pro_produce_user->find()->where(['deleted' => 0])->asArray()->all();
			foreach ($pro_produce_user_query as $pro_produce_user_queryk => $pro_produce_user_queryv){
				$pro_produce_user_query[$pro_produce_user_queryk]['user_id'] = $pro_produce_user_queryv['itemid'];
				$pro_produce_user_query[$pro_produce_user_queryk]['user_name'] = $pro_produce_user_queryv['user_name'];
			}
			echo json_encode($pro_produce_user_query);
		}
	}
	
    /**
     * @name 添加留言后的保存操作
     * @author yangping
     * @beizhu tanbenjiang edit 2015-6-18
     * 
     */
	public function actionSave()
	{
		$datas = Yii::$app->request->post('formData');
		$response = ['success'=>false, 'message'=>''];
		 
		$model = new SysMessage();
		if($model->load(Yii::$app->request->post('formData'),''))
		{
			
			$model->add_time = time();
			$model->from = 2; //商户
			
			//发送人id
			$model->from_user = $boss_id = Yii::$app->session['bBossInfo']['id'];;
			$model->is_read = 0;
			$model->typeid = 121;
			
			if( $datas['search_level'] == 1 )
			{ //管理员
				$model->to = 0;
			}elseif( $datas['search_level'] == 2 ){  //会员
				$model->to = 1;
			}
			
			//保存到数据库中
			if($model->save())
			{
				$response['success'] = true;
				$response['message'] = '保存成功';
			}else{
				$response['message'] = '保存失败';
			}
		}
		
		echo json_encode($response);
	}
	
	/**
	 * @name 更改留言状态操作
	 * @author yaping
	 * 
	 */
	public function actionLook(){
		$response = ['success'=>false, 'message'=>''];
		if(!empty($_POST['itemid'])){
			//当前登录用户id
			$now_user = Yii::$app->session['bBossInfo']['id'];
			
			if($_POST['is_read'] == 0 && $_POST['from_user'] != $now_user)
			{
				$model = new SysMessage();
				$comm = $model::find()->where( ['itemid'=>$_POST['itemid']])
				                      ->one();
				$comm->setAttribute('is_read', 1);
				
				if($comm->save())
				{
					$response['success'] = true;
					$response['message'] = '已读';
				}else{
					$response['message'] = '未读';
				}
			}
		}
		echo json_encode($response);
	}
	
	/**
	 * @name 删除留言操作
	 * @author yaping
	 * 
	 */
	public function actionDelete()
	{
		//批量删除留言信息
		if(!empty($_POST['id']))
		{
			$del_id = implode(',', $_POST['id']);
			 
			//批量更新删除字段为1
			$model = new SysMessage();
			$result = $model->updateAll(['deleted' => 1],'itemid in('.$del_id.')');
	
			if($result)
			{
				echo Json::encode(['success'=>true,'message'=>'删除成功！']);
			}else{
				echo Json::encode(['success'=>false,'message'=>'删除失败！']);
			}
			 
		}else{
			echo Json::encode(['success'=>false,'message'=>'删除失败！']);
		}
	}
	
	/**
	 * @name 保存回复信息操作
	 * @author yaping
	 * 
	 */
 	public function actionSavereply()
 	{
    	$param = \Yii::$app->request->post();
    	
    	$response = ['success'=>false, 'message'=>''];
    	$model = new SysMessage();
    	
    	//消息来自商户
 		if($_POST['from_id'] == 2)
 		{
    		$model->updateAll(['is_reply' => 0,'is_read' => 0],'itemid in('.$_POST['itemids'].')');
    	}else{
    		$model->updateAll(['is_reply' => 1,'is_read' => 1],'itemid in('.$_POST['itemids'].')');
    	}
    	
    	if($model->load(Yii::$app->request->post(),''))
    	{
    		$model->add_time = time();
    		//保存到数据库中
    		if($model->save())
    		{
    			$response['success'] = true;
    			$response['message'] = '保存成功';
    		}else{
    			$response['message'] = '保存失败';
    		}
    	}
    	
    	echo json_encode($response);
    }
    
    
    
}