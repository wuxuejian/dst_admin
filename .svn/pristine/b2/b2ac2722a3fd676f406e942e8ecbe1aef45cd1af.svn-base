<?php
/**
 * 流程流转类
 */
namespace backend\classes;
use yii;
class Approval {
	
	//业务流程步骤
	private  $_steps = array();
	//js对象
	private  $_js_object ='';
	//js方法
	private  $_js_function ='';
	//模版id
	private  $_template_id ='';
	/**
	 * 查询流程要执行的步骤
	 * @param unknown_type $by_business     对应业务action
	 * @param unknown_type $by_business_id  当前业务id
	 */
	public function steps($by_business,$by_business_id)
	{
		//查询出对应的流程模版id
		$template_row = (new \yii\db\Query())->select('id,js_object,js_function')->from('oa_process_template')->where('by_business=:by_business',[':by_business'=>$by_business])->one();
		$this->_js_object = $template_row['js_object'];
		$this->_js_function = $template_row['js_function'];
		$this->_template_id =  $template_row['id'];
		if($template_row['id'])
		{
			//查询出业务 流程执行步骤
			$query = (new \yii\db\Query())->from('oa_approval_result')->where('template_id=:template_id',[':template_id'=>$template_row['id']]);
			$steps = $query->andWhere('by_business_id=:by_business_id',['by_business_id'=>$by_business_id])->orderBy('sort ASC')->all();
			return $steps;
		}else{
			return 0; //找不到到对应的审核流程!
		}
	}
	
	/**
	 * 当前审批状态
	 * @param unknown_type $by_business     对应业务action
	 * @param unknown_type $by_business_id  当前业务id
	 * @param unknown_type $is_cancel  		申请状态
	 */
	public function approval_status($by_business,$by_business_id,$is_cancel,$table)
	{
		$this->_steps = $this->steps($by_business, $by_business_id);
/* 		$result = $this->count_down($by_business,$table);
		if($result == '超时')
		{
			$is_cancel = -1;
			$this->_steps = 0;
		} */
		
		if($this->_steps){
	
			foreach ($this->_steps as $step)
			{
				//查询出当前步骤指定的角色名
				$rbac_role_row = (new \yii\db\Query())->select('name')->from('cs_rbac_role')->where('id=:id',[':id'=>$step['assign_role_id']])->one();
				
				//当前步骤不需要审批通过，执行事件
				if($step['approval_status'] == 0 && $step['event_status'] == 0)
				{
					$event_name = $this->event($step['event']);
					$text = '等待'.$rbac_role_row['name'].$event_name;
					return "<span><font color='black'>{$text}</font></span>";
				}
				//当前步骤需要审批通过
				if($step['approval_status'] == 3)
				{
					if($step['sort'] ==60){
						$text = '等待'.$rbac_role_row['name'].'确认收款方式';
						return "<span><font color='black'>{$text}</font></span>";
					}elseif($step['sort'] ==90){
						$text = '等待'.$rbac_role_row['name'].'确认租金信息';
						return "<span><font color='black'>{$text}</font></span>";
					}else{
						$text = '等待'.$rbac_role_row['name'].'通过';
						return "<span><font color='black'>{$text}</font></span>";
					}
					
					
				}
				//申请被驳回
				if($step['approval_status'] == 2)
				{
					$text = '申请被驳回';
					return "<span><font color='red'>{$text}</font></span>";
				}
				
			}
			return "<span><font color='green'>流程结束</font></span>";
		}else{
			if($is_cancel ==0)
			{
				return "<span><font color='red'>申请已取消</font></span>";
			}elseif($is_cancel ==-1){
				
				$track_row = (new \yii\db\Query())->from('oa_approval_track')->where('template_id =:template_id and by_business_id=:by_business_id',[':template_id'=>$this->_template_id,':by_business_id'=>$by_business_id])->orderBy('id DESC')->limit(1)->one();
				$remark = $track_row['remark'] ? $track_row['remark'] : '无';
				return "<span><font color='red'>申请被驳回,原因：{$remark}</font></span>";
			}else{
				return "<span><font color='red'>申请未提交</font></span>";
			}
			
		}
	}
	
	/**
	 * 审批倒计时
	 */
	public function count_down($action,$table)
	{
		if($this->_steps)
		{
			foreach ($this->_steps as $key=>$step)
			{
				
				$row = (new \yii\db\Query())->from('oa_approval_track')->where(
						'template_id=:template_id AND by_business_id=:by_business_id',
						[':template_id'=>$step['template_id'],':by_business_id'=>$step['by_business_id']]
				)->orderBy('id DESC')->limit(1)->one();
				
				//当前步骤不需要审批通过，执行事件
				if(($step['approval_status'] == 0 && $step['event_status'] == 0))
				{
					/* if(time()-$row['time'] >$step['count_down']*86400)
					{
						//$result = $this->cancel($action,$step['by_business_id'],$table,$this->_template_id,-1);
						//if($result)
						//{
						//	$event_name = $this->event($step['event']);
						//	$this->track($this->_template_id,$step['by_business_id'],$event_name,'驳回','超时');
						//} 
						
						return '超时';
					}else{

						$date1 = date_create(date('Y-m-d H:i',$step['count_down']*86400+$row['time']));
						$date2 = date_create(date('Y-m-d H:i',time()));
						$diff = date_diff($date1,$date2);
						
						return $diff->format("%d天%h时%i分");
					} */
					
					$t = $this->time_operation($row['time'], $step['count_down']*86400*9/24);
					if(time() > $t){
						return '超时';
					}else{
						$date1 = date_create(date('Y-m-d H:i',$t));
						$date2 = date_create(date('Y-m-d H:i',time()));
						$diff = date_diff($date1,$date2);
						
						return $diff->format("%d天%h时%i分");
					}
					
				}
				if($step['approval_status'] == 3)
				{
					/* if(time()-$row['time'] >$step['count_down']*86400)
					{
						//$result = $this->cancel($action,$step['by_business_id'],$table,$this->_template_id,-1);
						//if($result)
						//{
						//	$event_name = $this->event($step['event']);
						//	$this->track($this->_template_id,$step['by_business_id'],'审核','驳回','超时');
						//}
						return '超时';
					}else{
						//$time = ($step['count_down']*86400-(time()-$row['time']));
						//$day = floor($time/86400);
						//$hour = ceil(($time%86400)/3600);
						//return $day.'天'.$hour.'小时'; 
						$date1 = date_create(date('Y-m-d H:i',$step['count_down']*86400+$row['time']));
						$date2 = date_create(date('Y-m-d H:i',time()));
						$diff = date_diff($date1,$date2);
						return $diff->format("%d天%h时%i分");
					} */
					$t = $this->time_operation($row['time'], $step['count_down']*86400*9/24);
					if(time() > $t){
						return '超时';
					}else{
						$date1 = date_create(date('Y-m-d H:i',$t));
						$date2 = date_create(date('Y-m-d H:i',time()));
						$diff = date_diff($date1,$date2);
					
						return $diff->format("%d天%h时%i分");
					}
				}
				if($step['approval_status'] == 2){
					return '';  //驳回
				}
				
				
			}
		}
	}
	/**
	 * 当前操作
	 * @param unknown_type $is_cancel  		申请状态
	 * @param unknown_type $id  			业务id
	 */
	public function approval_operation($is_cancel,$id,$table)
	{
		$session = yii::$app->session;
		$session->open();
		//当前用户id
		$user_id = $_SESSION['backend']['adminInfo']['id'];
		//查询出当前用户角色id
		$roles = (new \yii\db\Query())->from('cs_admin_role')->where('admin_id=:admin_id',[':admin_id'=>$user_id])->all();
		foreach ($roles as $role)
		{
			$role_id[] = $role['role_id'];
		}
		
		
		//当前操作 菜单选项 全局显示
		//$menu_str = "<span><b>详情</b></span>&nbsp;"; 
		$menu_str = '';
		$menu_str .="<a  href='javascript:{$this->_js_object}.trace({$this->_template_id})' class='easyui-linkbutton  l-btn l-btn-small' style='width:76px;height:24px;text-align:center;line-height:24px'>流程追踪</a>&nbsp;";
		
		if($this->_steps)
		{
			
			
			//$menu_str .="<span><b>流程追踪</b></span>&nbsp;";
			//avg  模版id
			foreach ($this->_steps as $key=>$step)
			{
				//流程第一步
				if($key == 0)
				{
					//被驳回,可以重新申请
					if($step['approval_status'] == 2)
					{
						//$menu_str .="<span><b>重新申请</b></span>&nbsp;";
						$menu_str .="<span><a href='javascript:{$this->_js_object}.again()' class='easyui-linkbutton  l-btn l-btn-small' style='width:76px;height:24px;text-align:center;line-height:24px'>重新申请</a></span>&nbsp;";
						return $menu_str;
					}
					
				}
				//申请人id
				$report_row = (new \yii\db\Query())->select('user_id')->from('oa_extract_report')->where('id=:id',[':id'=>$step['by_business_id']])->one();
				$report_user_id = $report_row['user_id'];
				
				//当前步骤不需要审批通过，只 执行事件
				if($step['approval_status'] == 0 && $step['event_status'] == 0)
				{
					//指定审批角色
					if(in_array($step['assign_role_id'], $role_id))
					{
						$event_name = $this->event($step['event']);
						//$menu_str .="<span><b>{$event_name}</b></span>&nbsp;";
						$menu_str .="<span><a href='javascript:{$this->_js_object}.{$step['js_function']}({$step['id']},{$this->_template_id})' class='easyui-linkbutton  l-btn l-btn-small' style='width:100px;height:24px;text-align:center;line-height:24px'>{$event_name}</a></span>&nbsp;";
					}
					//当前步骤,当前用户是否是申请人，  申请人可以取消申请,终止流程
					if($user_id == $report_user_id && $step['is_cancel'] == 1)
					{
						//$menu_str .="<span><b>取消申请</b></span>&nbsp;";
						$menu_str .="<span><a href='javascript:{$this->_js_object}.cancel({$this->_template_id})' class='easyui-linkbutton  l-btn l-btn-small' style='width:76px;height:24px;text-align:center;line-height:24px'>取消申请</a></span>&nbsp;";
					}
					return $menu_str;
				
				}
				//当前步骤需要审批通过
				if($step['approval_status'] == 3)
				{
					if(in_array($step['assign_role_id'], $role_id))
					{
						//$menu_str .="<span><b>通过</b></span>&nbsp;&nbsp;<span><b>驳回</b></span>&nbsp;";
						
						//arg  流程步骤id
						$menu_str .="<span><a href='javascript:{$this->_js_object}.pass({$step['id']},{$this->_template_id})' class='easyui-linkbutton  l-btn l-btn-small' style='width:76px;height:24px;text-align:center;line-height:24px'>通过</a></span>&nbsp;<span><a href='javascript:{$this->_js_object}.no_pass({$step['id']},{$this->_template_id})' class='easyui-linkbutton  l-btn l-btn-small' style='width:76px;height:24px;text-align:center;line-height:24px'>驳回</a></span>&nbsp;";
						
					}
					//当前步骤,当前用户是否是申请人，  申请人可以取消申请,终止流程
					if($user_id == $report_user_id && $step['is_cancel'] == 1)
					{
						$menu_str .="<span><a href='javascript:{$this->_js_object}.cancel({$this->_template_id})' class='easyui-linkbutton  l-btn l-btn-small' style='width:76px;height:24px;text-align:center;line-height:24px'>取消申请</a></span>&nbsp;";
					}
					return $menu_str;
				}
				
			}
			
			
		}else{
			//申请人id
			$report_row = (new \yii\db\Query())->select('user_id,is_cancel')->from("{$table}")->where('id=:id',[':id'=>$id])->one();
			$report_user_id = $report_row['user_id'];
			
			
			//当前用户 == 申请用户
			if($user_id == $report_user_id)
			{
				//申请已取消
				if($is_cancel == 0 || $is_cancel == -1)
				{
					$menu_str .="<span><a href='javascript:{$this->_js_object}.again()' class='easyui-linkbutton  l-btn l-btn-small' style='width:76px;height:24px;text-align:center;line-height:24px'>重新申请</a></span>&nbsp;";
				}else{
					//$menu_str .="<span><a href='javascript:{$this->_js_object}.confirm()' class='easyui-linkbutton  l-btn l-btn-small' style='width:76px;height:24px;text-align:center;line-height:24px'>提交申请</a></span>&nbsp;";
					$menu_str .="<span><a href='javascript:{$this->_js_object}.edit()' class='easyui-linkbutton  l-btn l-btn-small' style='width:76px;height:24px;text-align:center;line-height:24px'>编辑</a></span>&nbsp;";
				}
				$menu_str .="<span><a href='javascript:{$this->_js_object}.remove()' class='easyui-linkbutton  l-btn l-btn-small' style='width:76px;height:24px;text-align:center;line-height:24px'>删除</a></span>&nbsp;";
				return $menu_str;
			}
			
		}
		return $menu_str;
	}
	
	/**
	 * 查询出事件 名称
	 * @param unknown_type $action  事件action
	 */
	public function event($action)
	{
		$event = (new \yii\db\Query())->from('oa_event')->where('action=:action',[':action'=>$action])->one();
		if($event)
		{
			return $event['name'];
		}else{
			return 3;//流程要执行的事件不存在
		}
	}
	
	/**
	 * 提交申请
	 * @param unknown_type $action  对应业务action，查询出流程模版
	 * @param unknown_type $id      id
	 */
	public function confirm($action,$id)
	{
		$template_row = (new \yii\db\Query())->from('oa_process_template')->where('by_business=:by_business',[':by_business'=>$action])->one();
		if(!$template_row)
		{
			return '审批流程模版不存在!';
		}
		$template_id = $template_row['id'];
		
		$steps = (new \yii\db\Query())->from('oa_process_steps')->where('template_id=:template_id and sort >0',[':template_id'=>$template_id])->orderBy('sort ASC')->all();
		if(!$steps)
		{
			return '审批流程模版步骤为空!';
		}
		
		$db = \Yii::$app->db;
		//开启事物
		$transaction = $db->beginTransaction();
		try {
			foreach ($steps as $step)
			{
				$db->createCommand()->insert('oa_approval_result',
						[
						'by_business_id' => $id,
						'template_id'    => $template_id,
						'assign_role_id' => $step['assign_role_id'],
						'sort'           => $step['sort'],
						'approval_status'=> !empty($step['is_approval_action']) ? 3:0,
						'event'          => $step['is_event_action'],
						'js_function'    => $step['js_function'],
						'event_status'   => !empty($step['is_event_action']) ?0:'',
						'is_cancel'      => $step['is_cancel'],
						'is_send_email'  => $step['is_send_email'],
						'count_down'     => $step['count_down'],
						'create_time'    => time(),
						]
						
				)->execute();
			}
			
			$transaction->commit(); //提交
			$this->track($template_id,$id,'提交审核','开始');
			$this->send_mail($template_id,$id);
			return true;
		} catch (Exception $e) {
			$transaction->rollBack(); //回滚
			return false;
		}
		
	}
	
	/**
	 * 取消、驳回申请
	 * @param unknown_type $action       对应业务action，查询出流程模版
	 * @param unknown_type $id           id
	 * @param unknown_type $table        表名
	 * @param unknown_type $template_id  模版id
	 * @param unknown_type $is_cancel    取消、驳回   0|-1
	 */ 
	public function cancel($action,$id,$table,$template_id,$is_cancel)
	{
/*  		$template_row = (new \yii\db\Query())->from('oa_process_template')->where('by_business=:by_business',[':by_business'=>$action])->one();
		if(!$template_row)
		{
			return '审批流程模版不存在!';
		}
		$template_id = $template_row['id']; */
		
	 	$db = \yii::$app->db;
	 	//开启事物
	 	$transaction = $db->beginTransaction();
	 	try {
	 		$db->createCommand()->update($table, ['is_cancel'=>$is_cancel],'id=:id',[':id'=>$id])->execute();
	 		$db->createCommand()->delete('oa_approval_result','by_business_id=:by_business_id and template_id=:template_id',[':by_business_id'=>$id,':template_id'=>$template_id])->execute();
	 		
	 		$transaction->commit(); //提交
	 		if($is_cancel == 0)
	 		{
	 			$this->track($template_id,$id,'终止审批','取消申请');
	 		}
	 		$this->send_mail($template_id,$id);
	 		return true;
	 	} catch (Exception $e) {
	 		$transaction->rollBack(); //回滚
	 		return false;
	 	}
	 	
	}
	
	
	/**
	 * 通过
	 * @param unknown_type $id       业务id
	 * @param unknown_type $step_id  步骤id
	 * @param unknown_type $template_id  模版id
	 */
	public function pass($id,$step_id,$template_id,$remark)
	{
		$session = yii::$app->session;
		$session->open();
		//当前用户id
		$user_id = $_SESSION['backend']['adminInfo']['id'];
		
		$db = \yii::$app->db;
		$result = $db->createCommand()->update('oa_approval_result', 
				['approval_status' => 1,            //通过
				'operator'         => $user_id,     //操作人id
				'time'             => time(),       //审核时间
				'remark'		   => $remark,
				],
				'id=:step_id and by_business_id=:id',[':step_id'=>$step_id,':id'=>$id]
				)->execute();

		if($result)
		{
			$this->track($template_id,$id,'审核','通过');
			$this->send_mail($template_id,$id);
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 驳回
	 * @param unknown_type $id          id
	 * @param unknown_type $step_id	            步骤id
	 * @param unknown_type $action			
	 * @param unknown_type $table
	 * @param unknown_type $template_id  模版id
	 * @param unknown_type $remark       备注
	 * @return string|boolean
	 */
	public function no_pass($id,$step_id,$action,$table,$template_id,$remark)
	{		
		$steps = $this->steps($action, $id);
		if(!$steps)
		{
			return '流程模版不存在或没有提交申请！';
		}
		
		$session = yii::$app->session;
		$session->open();
		//当前用户id
		$user_id = $_SESSION['backend']['adminInfo']['id'];
		$db = \yii::$app->db;
		foreach ($steps as $key=>$step)
		{
			//流程第一步
			if($key == 0 && $step['id'] == $step_id)
			{
				//等待审批状态被驳回,系统自动取消申请
				if($step['approval_status'] == 3)
				{
					//取消申请
					$r = $this->cancel($action, $id, $table,$template_id,-1);
					if($r)
					{
						$this->track($template_id,$id,'审核','驳回',$remark);
						return true;
					}
				}else{
					return '当前状态不能驳回！';
				}
			
			}
			
			if($step['id'] == $step_id && $key >0)
			{
				$prev_arr = $steps[$key-1];    //当前数组指针前一位
/* 				echo 'step_id='.$step_id;
				echo '<pre>';
				var_dump($prev_arr);exit(); */
				
				//上一步 是审批动作
				if($prev_arr['approval_status'] == 1)
				{
					$result = $db->createCommand()->update('oa_approval_result', ['approval_status'=>3,'operator'=> '','time'=> time()],'id=:id',[':id'=>$prev_arr['id']])->execute();
					if($result)
					{
						$this->track($template_id,$id,'审核','驳回',$remark);
						$this->send_mail($template_id,$id);
						return true;
					}else{
						return false;
					}
				}
				
				//上一步是指定事件
				if($prev_arr['event_status'] == 1)
				{
					$result = $db->createCommand()->update('oa_approval_result', ['event_status'=>0,'operator'=> '','time'=> time()],'id=:id',[':id'=>$prev_arr['id']])->execute();
					if($result)
					{
						$this->track($template_id,$id,'审核','驳回',$remark);
						$this->send_mail($template_id,$id);
						return true;
					}else{
						return false;
					}
				}
				
				
			}
			
		}
		
	}
	
	/**
	 * 完成事件
	 * @param unknown_type $template_id   模版id
	 * @param unknown_type $id            业务id
	 * @param unknown_type $step_id       步骤id
	 * @param unknown_type $action        
	 * @return boolean
	 */
	public function complete_event($template_id,$id,$step_id,$action)
	{
		$session = yii::$app->session;
		$session->open();
		//当前用户id
		$user_id = $_SESSION['backend']['adminInfo']['id'];
		
		$db = \yii::$app->db;
		$result = $db->createCommand()->update('oa_approval_result',
				['event_status'    => 1,            //完成
				'operator'         => $user_id,     //操作人id
				'time'             => time()        //审核时间
				],
				'id=:step_id and by_business_id=:id',[':step_id'=>$step_id,':id'=>$id]
		)->execute();
		
		if($result)
		{
			$event_name = $this->event($action);
			$this->track($template_id,$id,$event_name,'完成');
			$this->send_mail($template_id,$id);
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 流程追踪
	 * @param unknown_type $template_id
	 * @param unknown_type $id
	 * @param unknown_type $current_operator
	 * @param unknown_type $result
	 * @param unknown_type $remark          备注
	 */
	public function track($template_id,$id,$current_operator,$result,$remark='')
	{
		$session = yii::$app->session;
		$session->open();
		//当前用户id
		$user_id = $_SESSION['backend']['adminInfo']['id'];
		$user_name = $_SESSION['backend']['adminInfo']['name'];
		//查询出当前用户角色id
		$role_row = (new \yii\db\Query())->from('cs_admin_role')->where('admin_id=:admin_id',[':admin_id'=>$user_id])->one();

		$rbac_role = (new \yii\db\Query())->from('cs_rbac_role')->where('id=:id',[':id'=>$role_row['role_id']])->one();
		$role_name = $rbac_role['name'];
		
		
		if($remark == '超时')
		{
			$user_name = '--';
			$role_name = '--';
		}
		
		$db = \yii::$app->db;
		$result = $db->createCommand()->insert('oa_approval_track',
				['template_id'     => $template_id,         
				'by_business_id'   => $id,     
				'operator_name'    => $user_name,
				'role_name'        => $role_name,
				'current_operator' => $current_operator,
				'result'           => $result,
				'remark'           => $remark,
				'time'             => time(),
				])->execute();
		if($result)
		{
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 
	 * @param unknown_type $template_id  模版id
	 * @param unknown_type $id			 业务id
	 */
	public function send_mail($template_id,$id)
	{
		
		
		$template = (new \yii\db\Query())->select('name,by_business')->from('oa_process_template')->where('id=:id',[':id'=>$template_id])->one();
		
		$steps = (new \yii\db\Query())->from('oa_approval_result')->where('by_business_id=:by_business_id AND template_id=:template_id',[':by_business_id'=>$id,':template_id'=>$template_id])->all();
		
		$oc = 0;
		if($template && $template['by_business']=='process/car/index'){
			$extract_row = (new \yii\db\Query())->select('operating_company_id')->from('oa_extract_report')->where('id=:id',[':id'=>$id])->one();
			$oc = !empty($extract_row['operating_company_id']) ? $extract_row['operating_company_id'] :0;
		}
		
		if($steps)
		{
			foreach ($steps as $key=>$step)
			{
			
				//当前步骤不需要审批通过，只 执行事件
				if(($step['approval_status'] == 0 && $step['event_status'] == 0) || $step['approval_status'] == 3)
				{
					//查询出当前角色下用户
					$emails = (new \yii\db\Query())->select('email,operating_company_id,operating_company_ids')->from('cs_admin_role')
					->where('role_id=:role_id',[':role_id'=>$step['assign_role_id']])
					->join('LEFT JOIN','cs_admin','cs_admin.id=cs_admin_role.admin_id')
					->all();
			
			
					$sendto_email =[];
					if($emails)
					{
						foreach ($emails as $email)
						{
							$admin_oc  = $email['operating_company_id'];
							$admin_ocs = !empty($email['operating_company_ids']) ? explode(',', $email['operating_company_ids']) : array();	
							if(!empty($email['email']) &&($oc == $admin_oc || in_array($oc, $admin_ocs)))
							{
								$sendto_email[] = $email['email'];
							}
							
						}
					}
					
					if($template['by_business'] == 'process/car/index' && $key!=0)
					{
						//每一个步骤审批新进度推送给申请人
						$user_id = (new \yii\db\Query())->select('user_id')->from('oa_extract_report')->where('id=:id',[':id'=>$id])->one();
						$row = (new \yii\db\Query())->select('email')->from('cs_admin')->where('id=:id',[':id'=>$user_id['user_id']])->one();
						if(!empty($row['email']))
						{
							$mail = new Mail();
							$subject = '进度提醒';
							$body ="你提交的审批有了新的进度：【提车申请】，请登录地上铁系统查看。点击这里：<a href='http://xt.dstzc.com'>登录地上铁系统</a>，或者在浏览器中输入以下地址登录 ：xt.dstzc.com 。<br>如果对此有疑问和建议，请向系统开发部反馈。";
							$mail->send(array($row['email']),$subject, $body);
						}
					}
					
					
					$sendto_email = array_filter($sendto_email);
					if($sendto_email)
					{					
						$mail = new Mail();
						$subject = '流程审批';
						$body = "你有一个待处理的事项：【{$template['name']}】。请及时登录地上铁系统查看并处理该事项，以免影响工作进度。点击这里：<a href='http://xt.dstzc.com'>登录地上铁系统</a>，或者在浏览器中输入以下地址登录 ：xt.dstzc.com 。<br>如果对此有疑问和建议，请向系统开发部反馈。";

						$mail->send($sendto_email,$subject, $body);
					}
					
					
					return true;
				}
			
			}
			
			//审批已完结
		}
		
	}
 
	/**
	 * 
	 * @param unknown_type $id              提车申请ID
	 * @param unknown_type $step_id         当前步骤ID
	 * @param unknown_type $template_id     模版ID
	 */
	public function next_step($id, $step_id,$template_id)
	{
		$current_step = (new \yii\db\Query())->from('oa_approval_result')->where('id=:step_id and by_business_id=:id',[':step_id'=>$step_id,':id'=>$id])->one();
		$next_step = (new \yii\db\Query())->from('oa_approval_result')->where('id=:step_id and by_business_id=:id and approval_status=0',[':step_id'=>$step_id+1,':id'=>$id])->one();
		if(!empty($next_step) && $current_step['assign_role_id'] == $next_step['assign_role_id'])
		{
			$js_row = (new \yii\db\Query())->from('oa_event')->where('action=:action',[':action'=>$next_step['event']])->one();
			return "{$js_row['js_object']}.{$js_row['js_function']}({$next_step['id']},{$next_step['template_id']})";
			//return "{$js_row['js_function']}({$next_step['id']},{$next_step['template_id']})";
		}else{
			return '';
		}
	}
	
	
	public function my_approvel()
	{
		
		//当前用户id
		$user_id = $_SESSION['backend']['adminInfo']['id'];
		//查询出当前用户角色id
		$roles = (new \yii\db\Query())->from('cs_admin_role')->where('admin_id=:admin_id',[':admin_id'=>$user_id])->all();
		$role_id = [];
		foreach ($roles as $role)
		{
			$role_id[] = $role['role_id'];
		}
		
		//查询出对应的流程模版id
		$template_row = (new \yii\db\Query())->select('id')->from('oa_process_template')->where('by_business=:by_business',[':by_business'=>'process/car/index'])->one();
		$my_approvel = [];
		if($template_row['id'])
		{
			//查询出当前角色需要审批、执行事件的申请ID
			$query = (new \yii\db\Query())->select('id,by_business_id,approval_status,event_status')->from('oa_approval_result')->where('template_id=:template_id',[':template_id'=>$template_row['id']]);
			$approvals = $query->andWhere(['assign_role_id'=>$role_id])->all();	

			foreach ($approvals as $key=>$approval)
			{
				//当前申请该角色还未审批、执行事件
				if($approval['approval_status'] == 3 || $approval['event_status']==='0')
				{
					//判断当前角色的前一个审批人是否完成了审批,查询不到上一步骤，则当前角色的审批操作为第一步
					$last_step_id = $approval['id']-1;
					$row = (new \yii\db\Query())->select('id,approval_status,event_status')->from('oa_approval_result')->where('id=:id AND by_business_id=:by_business_id',[':id'=>$last_step_id,':by_business_id'=>$approval['by_business_id']])->one();
					if($row)
					{
						
					/* 	echo '<pre>';
						var_dump($row); */
						//上一步骤没有审批
						if($row['approval_status'] ==3 || $row['event_status']==='0')
						{
							unset($approvals[$key]);
						}
						
					}
					
				}else{
					unset($approvals[$key]);
				}
				
			}
/* 			echo '----------';
			echo '<pre>';
			var_dump($approvals);
			exit(); */
			foreach ($approvals as $v)
			{
				$my_approvel[] = $v['by_business_id'];
			}
		}else{
			return 0; //找不到到对应的审核流程!
		}
		
		//当前用户未提交审批的申请ID
		$ret = [];
		$result = (new \yii\db\Query())->select('id')->from('oa_extract_report')->where('user_id=:user_id',[':user_id'=>$user_id])->all();
		if($result)
		{
			foreach ($result as $key=>$val){
				$r = (new \yii\db\Query())->select('id')->from('oa_approval_result')->where('by_business_id=:by_business_id AND template_id=:template_id',[':by_business_id'=>$val['id'],':template_id'=>$template_row['id']])->one();
				if($r)
				{
					unset($result[$key]);
				}
			}
			foreach ($result as $val)
			{
				$ret[] = $val['id'];
			}
		}
		return array_merge($my_approvel,$ret);
		
	}
	
	/**
	 * 计算当前时间搓加上时间戳（周一到周五  9-18点的时间 为有效时间，不是有效时间变更为有效时间在进行计算）
	 * @param unknown_type $time
	 * @param unknown_type $count_down
	 * @return number|unknown
	 */
	public function time_operation($time,$count_down){
		$start_time  = $time;
		$week = date('w',$start_time);
		if($week == 0 || $week == 6){
			//下个星期一 9点
			$start_time = strtotime(date('Y-m-d 9:0',strtotime ("next Monday", strtotime(date('Y-m-d',$start_time)))));
		}else{
			//2、是否在指定9:00-18:00工作时间内
			$hour_minute = date('Gi',$start_time);
			if($hour_minute < 900){
				$start_time = strtotime(date('Y-m-d 9:0',$start_time));
			}else if($hour_minute >1800 && $hour_minute < 2359){
				$start_time = strtotime(date('Y-m-d 9:0',strtotime ("+1 day", strtotime(date('Y-m-d',$start_time)))));
			}
		}
		$end_time    = strtotime(date('Y-m-d 18:0',$start_time));
		if($start_time + $count_down < $end_time){
			return   $start_time + $count_down;
		}else{
			$t = $end_time - $start_time;
			$count_down = $count_down - $t;
			$time = strtotime ("+1 day", strtotime(date('Y-m-d',$start_time)));
			$t = $this->time_operation($time,$count_down);
			return $t;
			exit();
		}
	}
}