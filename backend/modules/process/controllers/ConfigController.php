<?php
/**
 * 流程配置类
 * @author Administrator
 *
 */
namespace backend\modules\process\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\RbacRole;
class ConfigController extends BaseController
{
	//一些流程需要执行的事件
	public $events = [];
	// 对应的业务模块
	public $business = [];
	
	public function init()
	{
		parent::init();
		$db = new \yii\db\Query();
		$result = $db->select('*')->from('oa_event')->all();
		if($result)
		{
			foreach ($result as $val)
			{
				if($val['type'] ==1)
				{
					$this->events[$val['action']] = $val['name'];
				}else{
					$this->business[$val['action']] = $val['name'];
				}
				
			}
		}
	}
	
	
	/**
	 * 配置列表
	 */
	public function actionIndex()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			$query = $db->select('*')->from('oa_process_template');
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			//按名称模糊搜索
			$name = yii::$app->request->post('name');
			if($name)
			{
				$query->where(['like','name',$name]);
			}
			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			if($sort)
			{
				$query->orderBy("{$sort} {$order}");
			}
			$total = $query->count();
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1]);
			$result = $query->offset($pages->offset)->limit($pages->limit)->all();
			$result = json_decode(json_encode($result));
			if($result){
				foreach ($result as $val)
				{
					$val->by_business      = $this->business[$val->by_business];
					$val->last_update_time = date('Y-m-d H:i',$val->last_update_time);
					$val->create_time      = date('Y-m-d H:i',$val->create_time);
				}
			}				
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
				
		$buttons = $this->getCurrentActionBtn();
		return $this->render('index',['buttons'=>$buttons]);
	}
	
	/**
	 * 新增
	 */
	public function actionAdd()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = \Yii::$app->db;
			$name = yii::$app->request->post('name');
			//对应业务
			$by_business = yii::$app->request->post('by_business');
			$event_row = (new \yii\db\Query())->from('oa_event')->where('action=:action',['action'=>$by_business])->one();
			$js_object = $event_row['js_object'];
			$js_function = $event_row['js_function'];
			$time = time();
			$result = $db->createCommand()->insert('oa_process_template',
					['name'=>$name,
					'by_business'=>$by_business,
					'js_object'  => $js_object,
					'js_function' => $js_function,
					'last_update_time'=>$time,
					'create_time'=>$time]
					)->execute();
			if($result)
			{
				
				
				$db->createCommand()->insert('oa_process_steps',
						['template_id'    => $db->getLastInsertID(),
						'assign_role_id'  => 1,
						'is_event_action' => $by_business,
						'sort'=>0,
						'last_update_time'=>time(),
						'create_time' =>time(),
						]
				)->execute();
				$returnArr['status'] = true;
				$returnArr['info'] = '流程添加成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '数据保存失败！';
			}
			
			return json_encode($returnArr);
		}
		return $this->render('add',['business'=>$this->business]);
	}
	
	/**
	 * 修改
	 */
	public function actionEdit()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = \Yii::$app->db;
			$id = yii::$app->request->post('id');
			$name = yii::$app->request->post('name');
			//对应业务
			$by_business = yii::$app->request->post('by_business');
			$event_row = (new \yii\db\Query())->from('oa_event')->where('action=:action',['action'=>$by_business])->one();
			$js_object = $event_row['js_object'];
			$js_function = $event_row['js_function'];
			$time = time();
			$result = $db->createCommand()->update('oa_process_template',
					['name'=>$name,
					'by_business'=>$by_business,
					'js_object'  => $js_object,
					'js_function' => $js_function,
					'last_update_time'=>$time],
					'id=:id',[':id'=>$id]
					)->execute();
			//echo '<pre>';
			//var_dump($db->createCommand()->update('oa_process_template',['name'=>$name,'last_update_time'=>$time],'id=:id',[':id'=>$id])->getRawSql());exit();
			
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '流程编辑成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '数据保存失败！';
			}
			
			return json_encode($returnArr);
		}
		$id = yii::$app->request->get('id');
		$db = new \yii\db\Query();
		$result = $db->select('*')->from('oa_process_template')->where('id=:id',['id'=>$id])->one();
		return  $this->render('edit',['result'=>$result,'business'=>$this->business]);
	}
	
	/**
	 * 删除流程
	 */
	public function actionDelete()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$db = \Yii::$app->db;
			//开启事物
			$transaction = $db->beginTransaction();
			try {
				$db->createCommand()->delete('oa_process_template','id=:id',[':id'=>$id])->execute();
				$db->createCommand()->delete('oa_process_steps','template_id=:template_id',[':template_id'=>$id])->execute();
				$transaction->commit(); //提交
				
				$returnArr['status'] = true;
				$returnArr['info'] = '删除成功！';
			} catch (Exception $e) {
				$transaction->rollBack(); //回滚
				
				$returnArr['status'] = false;
				$returnArr['info'] = '删除失败！';
			}
					
			return json_encode($returnArr);
		}
	}
	/**
	 * 流程步骤
	 * @return string
	 */
	public function actionSteps()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			$query = $db->select('oa_process_steps.*,cs_rbac_role.name as role_name')->from('oa_process_steps');
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			
			$query->join('LEFT JOIN','cs_rbac_role','cs_rbac_role.id = oa_process_steps.assign_role_id');
			$query->where('template_id=:template_id',[':template_id'=>yii::$app->request->get('template_id')]);
			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			if($sort)
			{
				$query->orderBy("{$sort} {$order}");
			}else{
				$query->orderBy('sort asc');
			}
			$total = $query->count();
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1]);
			$result = $query->offset($pages->offset)->limit($pages->limit)->all();
			$result = json_decode(json_encode($result));  //数组转对象
			if($result){
				foreach ($result as $val)
				{
					if(array_key_exists($val->is_event_action, $this->events))
					{
						$val->is_approval_action = $this->events[$val->is_event_action];
					}else if(array_key_exists($val->is_event_action, $this->business)){
						$val->is_approval_action = $this->business[$val->is_event_action];
					}else{
						$val->is_approval_action = '审批';
					}
					//$val->is_approval_action = $val->is_approval_action ? '审批':$this->events[$val->is_event_action];				
					$val->is_cancel = $val->is_cancel ? '可以':'不可以';
					
					$val->last_update_time = date('Y-m-d H:i',$val->last_update_time);
					$val->create_time = date('Y-m-d H:i',$val->create_time);
				}
			}
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
		$template_id = yii::$app->request->get('id');
		$buttons = $this->getCurrentActionBtn();
		return  $this->render('steps',['buttons'=>$buttons,'template_id'=>$template_id]);
	}
	
	/**
	 *  增加流程步骤
	 */
	public function actionAddStep()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = \Yii::$app->db;
			//流程模版id
			$template_id = yii::$app->request->post('template_id');
			//角色id
			$assign_role_id = yii::$app->request->post('assign_role_id');
			//是否执行审批OR事件 => 1OR2
			$is_approval_action = yii::$app->request->post('is_approval_action')==1 ? 1:0; 
			//要执行的事件
			$is_event_action = yii::$app->request->post('is_approval_action')!=1? yii::$app->request->post('is_event_action'):'';
			$js_function = '';
			if($is_event_action)
			{
				$event_row = (new \yii\db\Query())->from('oa_event')->where('action=:action',['action'=>$is_event_action])->one();
				$js_function = $event_row['js_function'];
			}
			//当前步骤内申请人是否可以终止申请
			$is_cancel = yii::$app->request->post('is_cancel');
			//审核倒计时 （过了倒计时自动取消申请）
			$count_down = yii::$app->request->post('count_down');
			//审核的步骤顺序（asc）
			$sort = yii::$app->request->post('sort');
			$time = time();
			$result = $db->createCommand()->insert(
						'oa_process_steps',
						['template_id'=>$template_id,
						'assign_role_id'=>$assign_role_id,
						'is_approval_action'=>$is_approval_action,
						'is_event_action'=>$is_event_action,
						'js_function' => $js_function,
						'is_cancel'=>$is_cancel,
						'count_down'=>$count_down,
						'sort'=>$sort,
						'last_update_time'=>$time,
						'create_time'=>$time]
						)->execute();

			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '步骤添加成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '数据保存失败！';
			}
			
			return json_encode($returnArr);
			
		}
		$template_id = yii::$app->request->get('template_id');
		$roles = RbacRole::find()->all();
		return $this->render('add-step',['roles'=>$roles,'events'=>$this->events,'template_id'=>$template_id]);
	}
	
	/**
	 * 修改流程步骤
	 */
	public function actionEditStep()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = \Yii::$app->db;
			//步骤id
			$id = yii::$app->request->post('id');
			//流程模版id
			$template_id = yii::$app->request->post('template_id');
			//角色id
			$assign_role_id = yii::$app->request->post('assign_role_id');
			//是否执行审批OR事件 => 1OR2
			$is_approval_action = yii::$app->request->post('is_approval_action')==1 ? 1:0;
			//要执行的事件
			$is_event_action = yii::$app->request->post('is_approval_action')!=1? yii::$app->request->post('is_event_action'):'';
			$js_function = '';
			if($is_event_action)
			{
				$event_row = (new \yii\db\Query())->from('oa_event')->where('action=:action',['action'=>$is_event_action])->one();
				$js_function = $event_row['js_function'];
			}
			//当前步骤内申请人是否可以终止申请
			$is_cancel = yii::$app->request->post('is_cancel');
			//审核倒计时 （过了倒计时自动取消申请）
			$count_down = yii::$app->request->post('count_down');
			//审核的步骤顺序（asc）
			$sort = yii::$app->request->post('sort');
			$time = time();
			$result = $db->createCommand()->update(
					'oa_process_steps',
					['template_id'=>$template_id,
					'assign_role_id'=>$assign_role_id,
					'is_approval_action'=>$is_approval_action,
					'is_event_action'=>$is_event_action,
					'js_function' => $js_function,
					'is_cancel'=>$is_cancel,
					'count_down'=>$count_down,
					'sort'=>$sort,
					'last_update_time'=>$time],
					'id=:id',[':id'=>$id]
			)->execute();
			
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '步骤编辑成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '数据保存失败！';
			}
			
			return json_encode($returnArr);
		}
		//步骤id
		$id = yii::$app->request->get('id');
		$db = new \yii\db\Query();
		$result = $db->select('*')->from('oa_process_steps')->where('id=:id',[':id'=>$id])->one();
		$result['is_approval_action'] = $result['is_approval_action'] ? '1':'2';

		$roles = RbacRole::find()->all();
		return $this->render('edit-step',['result'=>$result,'roles'=>$roles,'events'=>$this->events]);
	}
	/**
	 * 删除步骤
	 */
	public function actionDeleteStep()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$db = \Yii::$app->db;
			$result = $db->createCommand()->delete('oa_process_steps','id=:id',[':id'=>$id])->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '删除成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '删除失败！';
			}
			
			return json_encode($returnArr);
		}
	}
	
	/**
	 *  事件管理
	 */
	
	public function actionEvents()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			$query = $db->select('*')->from('oa_event');
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
			//按名称模糊搜索
			$name = yii::$app->request->post('name');
			if($name)
			{
				$query->where(['like','name',$name]);
			}
			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			if($sort)
			{
				$query->orderBy("{$sort} {$order}");
			}
			$total = $query->count();
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1]);
			$result = $query->offset($pages->offset)->limit($pages->limit)->all();
			$result = json_decode(json_encode($result));
			$returnArr = [];
			$returnArr['rows'] = $result;
			$returnArr['total'] = $total;
			return json_encode($returnArr);
		}
		$buttons = $this->getCurrentActionBtn();
		return $this->render('events',['buttons'=>$buttons]);
	}
	
	
	public function actionAddEvent()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = \Yii::$app->db;
			//名称
			$name = yii::$app->request->post('name');
			//URL路由
			$action = yii::$app->request->post('action');
			//JS对象
			$js_object = yii::$app->request->post('js_object');
			//JS方法
			$js_function = yii::$app->request->post('js_function');
			//类型 1，指定事件  2，对应业务
			$type = yii::$app->request->post('type');
			$result = $db->createCommand()->insert('oa_event',
					['name'=>$name,
					'action'=>$action,
					'js_object'=>$js_object,
					'js_function'=>$js_function,
					'type'=>$type]
					)->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '事件添加成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '数据保存失败！';
			}
		
			return json_encode($returnArr);
		}
		return $this->render('add-event');
	}
	
	/**
	 * 
	 */
	
	public function actionEditEvent()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = \Yii::$app->db;
			$id = yii::$app->request->post('id');
			//名称
			$name = yii::$app->request->post('name');
			//URL路由
			$action = yii::$app->request->post('action');
			//JS对象
			$js_object = yii::$app->request->post('js_object');
			//JS方法
			$js_function = yii::$app->request->post('js_function');
			//类型 1，指定事件  2，对应业务
			$type = yii::$app->request->post('type');
			$result = $db->createCommand()->update('oa_event',
					['name'=>$name,
					'action'=>$action,
					'js_object'=>$js_object,
					'js_function'=>$js_function,
					'type'=>$type],
					'id=:id',[':id'=>$id]
			)->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '事件添加成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '数据保存失败！';
			}
		
			return json_encode($returnArr);
		}
		$id = yii::$app->request->get('id');
		$result = (new \yii\db\Query())->from('oa_event')->where('id=:id',[':id'=>$id])->one();
		return $this->render('edit-event',['result'=>$result]);
	}
	
	
	public function actionDeleteEvent()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$db = \Yii::$app->db;
			$result = $db->createCommand()->delete('oa_event','id=:id',[':id'=>$id])->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '删除成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '删除失败！';
			}
		
			return json_encode($returnArr);
		}
	}
}