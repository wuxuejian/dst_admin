<?php

/**
 * @name 前台个人消息管理
 * @author luoyaping
 * @Date: 2015-6-10
 * @beizhu tanbenjiang edit
 * 
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

class MessageController extends \yii\web\Controller {
	
	public $enableCsrfValidation = false;
	
	public function actionIndex() 
	{
		// 定义消息分类（0:收到的消息 1:发出的消息）
		$datas['infoType'] = ['收到的消息','发出的消息'];
		
		// 操作按钮控制
    	$data['urls'] = [
    	'add'  => url::to(['message/add-message']),
    	'look'  => url::to(['message/look-message']),
    	'reply'  => url::to(['message/reply-message']),
    	];
		
		$datas['buttons'] = [
    	'add'=>array('text'=>'发送消息','class'=>'icon-add', 'click'=>"Message.addMessage('{$data['urls']['add']}',1,'my-message-add','发送消息')"),
    	'look'=>array('text'=>'查看消息','class'=>'icon-tip', 'click'=>"Message.lookMessage('{$data['urls']['look']}',1,'my-message-look','查看消息')"),
    	'reply'=>array('text'=>'回复消息','class'=>'icon-add', 'click'=>"Message.replyMessage('{$data['urls']['reply']}',1,'my-message-reply','回复消息')"),
    	'detele'=>array('text'=>'删除消息','class'=>'icon-delete', 'click'=>'Message.delMessage()')
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
    	
    	
    	//消息类型
    	$message_type = new SysCodeDetail();
    	$message_type_query = $message_type->find()->where(['code_id' => 31])->asArray()->all();
    	$datas['message_type_query'] = $message_type_query;
    	
        return $this->renderPartial('index',$datas);
	}
	
	/**
	 * @name 远程获取消息列表
	 * @author luoyaping
	 *
	 */
	public function actionMessagelist()
	{
	
		//查询当前登录的商户信息
		$boss_id = Yii::$app->session['bBossInfo']['id'];
		$infoType = Yii::$app->request->get('infoType'); // 获取消息类型（0:收到的消息 1:发出的消息） 
		$param = \Yii::$app->request->post();
		$response = ['success'=>false, 'message'=>''];
		$page = ($param['page'] ? $param['page'] : 1);
		$rows = ($param['rows'] ? $param['rows'] : 20);
	
		$model = new SysMessage();
		
		// 个人消息中只显示交互消息
		$query = $model->find()
		               ->where(['typeid'=>1,'parent_id' => 0,'deleted' => 0])
		               ->with(['company','company1','sysUser','sysUser1','ecsUser','proProduceUser']);
		
		if($infoType == 0)
		{
			// 收到的消息
			$query = $query->andFilterWhere(['to_user' => $boss_id,'to' => 2]);  // 收件人为当前商户用户
		}else if($infoType == 1)
		{
			// 发出的消息
			$query = $query->andFilterWhere(['from_user' => $boss_id,'from' => 2]); // 发件人为当前商户用户
		}		
		
		
		$query->andFilterWhere(['like','title',trim($param['key_word'])]);
		$query->orFilterWhere(['like','content',trim($param['key_word'])]);
		 
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
		 
		// 分页处理（按照发送实际排序）
		$queryClone = clone $query;
		$totalCount = $queryClone->count();
		$pages = new Pagination(['defaultPageSize'=>$rows,'totalCount'=>$totalCount,'pageSizeLimit'=>false]);
		$query = $query->asArray()
		               ->orderBy('add_time desc')
		               ->offset(($page-1)*$rows)
		               ->limit($pages->limit)
		               ->all();
		 
		 
		foreach($query as $key=>$val)
		{
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
	
			//消息类型
			$message_type = new SysCodeDetail();
			$message_type_query = $message_type->find()
			                                   ->asArray()
			                                   ->all();
	
			foreach ($message_type_query as $message_type_queryk => $message_type_queryv)
			{
				if($val['typeid'] == $message_type_queryv['id'])
				{
					$query[$key]['type_name'] = $message_type_queryv['code_value'];
				}
			}
	
			// 处理显示状态
			if($val['is_read']==0 && $val['is_reply']==0)
			{
				$query[$key]['status'] = 0; // 未读，未回复
			}elseif($val['is_read']==1 && $val['is_reply']==0){
				$query[$key]['status'] = 1; // 已读，未回复
			}elseif($val['is_read']==1 && $val['is_reply']==1){
				$query[$key]['status'] = 2; // 已读，已回复
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
		                                ->with(['company1','sysUser1'])
		                                ->where(['itemid' => $_GET['itemid']])
		                                ->asArray()
		                                ->one();  //获取当前查看的消息  一维数组
		
		// 格式化当前消息的发送时间
		$sys_message_list['time'] = date("Y-m-d H:i:s",$sys_message_list['add_time']);
		 
		// 获取与该消息相关的下级全部回复
		$allreply = $this->getAllNextReply($sys_message_list['itemid'],array());
		array_push($allreply, $sys_message_list);
		 
		// 获取与该消息相关的上级全部回复
		if($sys_message_list['parent_id'] != 0)
		{
			//调用递归函数
			$allbeforereply = $this->getAllBeforeReply($sys_message_list['parent_id'],array());
			foreach ($allbeforereply as $allbeforereplyk => $allbeforereplyv)
			{
				array_push($allreply, $allbeforereply[$allbeforereplyk]);
			}
		}
		
		// 商户下的用户
		$proProduceUser = new ProProduceUser();
		$proProduceUserlist = $proProduceUser->find()
		                                     ->where(['deleted' => 0])
		                                     ->asArray()
		                                     ->all();
		
		// 商户本身
		$busCompany = new BusCompany();
		$busCompanylist = $busCompany->find()
		                             ->where(['deleted' => 0])
		                             ->asArray()
		                             ->all();
		
		// 后台管理员
		$sysUser = new SysUser();
		$sysUserlist = $sysUser->find()
		                       ->where(['deleted' => 0])
		                       ->asArray()
		                       ->all();
		
		// 商城会员
		$ecsUsers = new EcsUsers();
		$ecsUserslist = $ecsUsers->find()
		                         ->asArray()
		                         ->all();
		
		//数组按时间查询排序  升序
		$newallreply = $this->getOrderByData($allreply);
		
		//循环数组  
		foreach ($newallreply as $newk => $newv)
		{
			//如果是当前登录用户则显示在左边   否则右边（并且发件人类型必须为商户类型）
			if($newv['from_user'] == $now_user && $newv['from'] == 2)
			{
				$newallreply[$newk]['direction'] = 'left';
			}else{
				$newallreply[$newk]['direction'] = 'right';
			}
			
			//消息来自系统  则发件人来自表sys_user
			if($newv['from'] == 0)
			{
				foreach ($sysUserlist as $sysUserlistk => $sysUserlistv)
				{
					if($sysUserlistv['id'] == $newv['from_user'])
					{
						$newallreply[$newk]['from_name'] = $sysUserlistv['user_name']; // 用户名
						$newallreply[$newk]['from_name_avatar'] = $sysUserlistv['small_avatar']; // 用户名的头像
					}
				}
				
			//消息来自商城会员  则发件人来自表ecs_user
			}elseif($newv['from'] == 1){
				foreach ($ecsUserslist as $ecsUserslistk => $ecsUserslistv)
				{
					if($ecsUserslistv['id'] == $newv['from_user'])
					{
						$newallreply[$newk]['from_name'] = $ecsUserslistv['user_name'];
					}
				}
				
			//消息来自商户  则发件人来自表bus_company
			}elseif($newv['from'] == 2){
				foreach ($busCompanylist as $busCompanylistk => $busCompanylistv)
				{
					if($busCompanylistv['id'] == $newv['from_user'])
					{
						$newallreply[$newk]['from_name'] = $busCompanylistv['company_name'];
					}
				}
			}
		}
		
		
		$datas['allreplylist'] = $newallreply;
		return $this->renderPartial('look-message',$datas);
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
	public function actionAddMessage()
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
     * @name 添加消息后的保存操作
     * @author luoyangping
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
			
			// 消息类型（暂时都为交互消息）
			$model->typeid = 1; // 交互消息类型
			
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
    
    
    
    /**
     * @name 前台商户刷新右上角消息通知操作
     * @author tanbenjiang
     * @date 2015-6-23
     * 
     */
    public function actionRefreshInformation()
    {
    	// 获取商户ID
    	$bossId = Yii::$app->session['bBossInfo']['id'];
    	
    	// 获取系统公告
    	$sysNotification = SysMessage::find()->select('itemid')
    	                  					 ->where(['typeid'=>0,'sub_typeid'=>0,'is_read'=>0,'parent_id'=>0,'deleted'=>0])
    	                  					 ->count();
    	
    	// 获取订单通知
    	$orderNotification = SysMessage::find()->select('itemid')
    	                                       ->where(['typeid'=>0,'sub_typeid'=>1,'to'=>2,'to_user'=>$bossId,'is_read'=>0,'parent_id'=>0,'deleted'=>0])
    	                                       ->count();   
    	// 获取催单通知
    	$reminderNotification = SysMessage::find()->select('itemid')
    	                                       	  ->where(['typeid'=>0,'sub_typeid'=>2,'to'=>2,'to_user'=>$bossId,'is_read'=>0,'parent_id'=>0,'deleted'=>0])
    	                                          ->count();   

    	// 获取通知总数
    	$totalNotification = $sysNotification + $orderNotification + $reminderNotification;
    	
    	// 获取消息总数（收件人必须为前台商户的用户,目前to_user只能定位到门店，无法定位到门店下的用户）
    	$message = SysMessage::find()->where(['typeid'=>1,'is_read'=>0,'parent_id'=>0,'deleted'=>0,'to'=>2,'to_user'=>$bossId])
    	                             ->with(['sysUser'])
    	                             ->orderBy(['add_time'=>SORT_DESC])
    	                             ->asArray()
    	                             ->all();
    	
    	// 获取消息总数
    	$totalMessage = count($message);
   	
    	// 获取最新的3条消息
    	$newMessage = array_slice($message, 0, 3);
    	
    	if(!empty($newMessage))
    	{
    		// 处理数据显示
    		foreach ($newMessage as $key=>$value)
    		{
    			// 发件人类型为【系统】,则与sys_user表的关联，获取其用户名和头像
    			if($value['from'] == 0)
    			{
    				$sysUser = $value['sysUser'];
    				$newMessage[$key]['user_name'] = $sysUser['user_name'];
    				$newMessage[$key]['avatar'] = Yii::$app->session->get('back_url_path').$sysUser['small_avatar'];
    			}
    			
    			// 发件人类型为【会员】，则与ecs_users表关联，获取其用户名和头像
    			if($value['from'] == 1)
    			{
    				
    			}
    			
    			// 发件人类型为【本商户下的其他用户】，则与ecs_users表关联，获取其用户名和头像
    			if($value['from'] == 2 && $value['from_user'] == Yii::$app->session['bBossInfo']['id'])
    			{
    				
    			}
    			
    			// 发件人类型为【其他商户】，则与bus_company表关联，获取其用户名和头像
    			if($value['from'] == 2 && $value['from_user'] != Yii::$app->session['bBossInfo']['id'])
    			{
    				
    			}
    			
    			// 统一处理时间
    			$day = (int)((time()-$value['add_time'])/(60*60*24));
    			$hour = (int)((time()-$value['add_time'])%(60*60*24)/(60*60));
    			$minute = (int)((time()-$value['add_time'])%(60*60*24*60*60)/60);
    			$second = (int)((time()-$value['add_time']));
    			
    			if($day > 0)
    			{
    		        $newMessage[$key]['add_time'] = $day.'天前';
    			}else if($hour > 0){
    				$newMessage[$key]['add_time'] = $hour.'小时前';
    			}else if($minute > 0){
    				$newMessage[$key]['add_time'] = $minute.'分钟前';
    			}else if($second > 0){
    				$newMessage[$key]['add_time'] = $second.'秒前';
    			}
    		}
    	}
    	
    	$response['sysNotification'] = intval($sysNotification); // 系统公告
    	$response['orderNotification'] = intval($orderNotification); // 订单通知
    	$response['reminderNotification'] = intval($reminderNotification); // 催单通知
    	$response['totalNotification'] = $totalNotification; // 系统通知 = 系统公告  + 订单通知
    	$response['totalMessage'] = $totalMessage; // 交互消息
    	$response['newMessage'] = $newMessage; // 最近的3条消息
    	
    	echo json_encode($response);
    }
    
    
    
}