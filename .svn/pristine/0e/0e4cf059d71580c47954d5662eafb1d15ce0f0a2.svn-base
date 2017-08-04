<?php
/**
 * 流程配置类
 * @author Administrator
 *
 */
namespace backend\modules\station\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\RbacRole;
use common\classes\Category;
class FaultController extends BaseController
{
	
	public function actionIndex()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$db = new \yii\db\Query();
			$query = $db->select('*')->from('oa_fault_category')->where('is_category=0');
			$pageSize = isset($_POST['rows']) && $_POST['rows'] <= 50 ? intval($_POST['rows']) : 10;
 			//按故障名称模糊搜索
			$category = yii::$app->request->post('category');
			if($category)
			{
				$query->andWhere(['like','category',$category]);
			}
			
			$pid = yii::$app->request->post('pid');
			if($pid)
			{
				$query->andWhere(['like','tier_pid',','.$pid.',']);
			}
			
			
			//排序字段
			$sort = yii::$app->request->post('sort');
			$order = yii::$app->request->post('order');  //asc|desc
			if($sort)
			{
				$query->orderBy("{$sort} {$order}");
			}else{
				$query->orderBy("time DESC");
			}
			$total = $query->count();
			$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize,'page'=>$_POST['page']-1 ]);
			$result = $query->offset($pages->offset)->limit($pages->limit)->all();
			$result = json_decode(json_encode($result));
 			if($result){
				foreach ($result as $val)
				{
/* 					$tier_pid = substr($val->tier_pid, 0,-1);  //去掉最后的 “,”分号
					$tier_pid = substr($tier_pid, -1,0);  //去掉前面的 “,”分号 */
					
					$tier_pid = trim($val->tier_pid,',');
					$categorys = (new \yii\db\Query())->from('oa_fault_category')->where("id in ({$tier_pid})")->all();
					if($categorys)
					{
						
						$val->category1 = @$categorys[0]['category'];
						$val->code1		= @$categorys[0]['code'];
						$val->category2 = @$categorys[1]['category'];
						$val->code2		= @$categorys[1]['code'];
						$val->category3 = @$categorys[2]['category'];
						$val->code3		= @$categorys[2]['code'];
					}
					$val->time      = date('Y-m-d H:i',$val->time);
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
	
	
	public function actionAdd()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$category 	= yii::$app->request->post('category');
			$code		= yii::$app->request->post('code');
			$pid		= yii::$app->request->post('pid');
			$dfm_code	= yii::$app->request->post('dfm_code');
			$tier_pid	= $this->_gettierpid($pid);
			$total_code	= $this->_gettotal_code($tier_pid);
			$total_code .= $code;
			$operator 	= $this->_operator();
			
			$db = \Yii::$app->db;
			$result = $db->createCommand()->insert('oa_fault_category',
					 ['category'	=> $category,
					 'code'			=> $code,
					 'pid'			=> $pid,
					 'tier_pid'		=> $tier_pid,
					 'time'         => time(),
					 'dfm_code'		=> $dfm_code,
					 'total_code'	=> $total_code,
					 'operator'		=> $operator,
					'is_category'	=> 0,
					 ])->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '新增成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '新增失败！';
			}
			
			
			return json_encode($returnArr);
		}
		return $this->render('add');
	}
	
	
	public function actionEdit()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$category 	= yii::$app->request->post('category');
			$code		= yii::$app->request->post('code');
			$pid		= yii::$app->request->post('pid');
			$dfm_code	= yii::$app->request->post('dfm_code');
			$tier_pid	= $this->_gettierpid($pid);
			$total_code	= $this->_gettotal_code($tier_pid);
			$total_code .= $code;
			$operator 	= $this->_operator();
			$id 		= intval(yii::$app->request->post('id'));
			
			$db = \Yii::$app->db;
			$result = $db->createCommand()->update('oa_fault_category',
					['category'	=> $category,
					'code'		=> $code,
					'pid'		=> $pid,
					'tier_pid'	=> $tier_pid,
					'time'      => time(),
					'dfm_code'	=> $dfm_code,
					'total_code'=> $total_code,
					'operator'	=> $operator,
					'is_category'=> 0,
					],'id=:id',[':id'=>$id]
					)->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '修改成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '修改失败！';
			}
		
		
			return json_encode($returnArr);
		}
		$id = intval(yii::$app->request->get('id'));
		$result = (new \yii\db\Query())->from('oa_fault_category')->where('id=:id',[':id'=>$id])->one();
		return $this->render('edit',['result'=>$result]);
	}
	
	
	public function actionDel()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$id = yii::$app->request->post('id');
			$db = \Yii::$app->db;
			$result = $db->createCommand()->delete('oa_fault_category','id=:id',[':id'=>$id])->execute();
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
	
	public function actionCategory()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$result = (new \yii\db\Query())->from('oa_fault_category')->where('is_category=1')->orderBy('id ASC')->all();
			$data = [];
			if($result){
				$data = Category::unlimitedForLayer($result,'pid');
			}
			$returnArr = [];
			$returnArr['rows'] = $data;
			$returnArr['total'] = count($data);
			return json_encode($returnArr);
		}
		
		
		$buttons = $this->getCurrentActionBtn();
		return $this->render('category',['buttons'=>$buttons]);
	}
	
	/**
	 * 增加分类
	 */
	public function actionAddCategory()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$pid		= intval(yii::$app->request->post('pid'));
			$category 	= yii::$app->request->post('category');
			$code 		= yii::$app->request->post('code');
			$tier_pid	= $this->_gettierpid($pid);
			$operator = $this->_operator();
			
			$db = \Yii::$app->db;
			$result = $db->createCommand()->insert('oa_fault_category',
					['category'		=> $category,
					'code'			=> $code,
					'pid'			=> $pid,
					'tier_pid'		=> $tier_pid,
					'time'          => time(),
					'operator'		=> $operator,
					'is_category'	=> 1,
					])->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '新增成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '新增失败！';
			}
			
			
			return json_encode($returnArr);
		}
		$id = intval(yii::$app->request->get('id'));
		return $this->render('add-category',['curMenuId'=>$id]);
	}
	
	/**
	 * 修改分类
	 */
	public function actionEditCategory()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$pid		= intval(yii::$app->request->post('pid'));
			$category 	= yii::$app->request->post('category');
			$code 		= yii::$app->request->post('code');
			$tier_pid	= $this->_gettierpid($pid);
			$operator 	= $this->_operator();
			$id 		= intval(yii::$app->request->post('id'));
			
			$db = \Yii::$app->db;
			$result = $db->createCommand()->update('oa_fault_category',
					['category'		=> $category,
					'code'			=> $code,
					'pid'			=> $pid,
					'tier_pid'		=> $tier_pid,
					'time'          => time(),
					'operator'		=> $operator,
					'is_category'	=> 1,
					],'id=:id',[':id'=>$id]
					)->execute();
			if($result)
			{
				$returnArr['status'] = true;
				$returnArr['info'] = '修改成功！';
			}else{
				$returnArr['status'] = false;
				$returnArr['info'] = '修改失败！';
			}
	
	
			return json_encode($returnArr);
		}
		$id = intval(yii::$app->request->get('id'));
		$result = (new \yii\db\Query())->from('oa_fault_category')->where('id=:id',[':id'=>$id])->one();
		return $this->render('edit-category',['result'=>$result]);
	}
	
	
	
	
	public function actionExport()
	{
	
		$db = new \yii\db\Query();
		$query = $db->select('*')->from('oa_fault_category')->where('is_category=0');
		//按故障名称模糊搜索
		$category = yii::$app->request->get('category');
		if($category)
		{
			$query->andWhere(['like','category',$category]);
		}
		
		$pid = yii::$app->request->get('pid');
		if($pid)
		{
			$query->andWhere(['like','tier_pid',','.$pid.',']);
		}
		$result = $query->all();
		
		$filename = '故障列表.csv'; //设置文件名
		$str = "故障大类,编码,故障级别,编码,故障原因大类,编码,故障名称,编码,总故障编码,东风原始故障码,登记时间,登记人员\n";
		if($result){
			foreach ($result as $val)
			{
/* 				$tier_pid = substr($val['tier_pid'], 0,-1);  //去掉最后的 “,”分号 */
				$tier_pid = trim($val['tier_pid'],',');
				$categorys = (new \yii\db\Query())->from('oa_fault_category')->where("id in ({$tier_pid})")->all();
				if($categorys)
				{
					
					$category1 = @$categorys[0]['category'];
					$code1		= @$categorys[0]['code'];
					$category2 = @$categorys[1]['category'];
					$code2		= @$categorys[1]['code'];
					$category3 = @$categorys[2]['category'];
					$code3		= @$categorys[2]['code'];
				}
				$category	= str_replace('，', ',', $val['category']);
				$code		= $val['code'];
				$total_code	= $val['total_code'];
				$dfm_code	= $val['dfm_code'];
				$time      	= date('Y-m-d H:i',$val['time']);
				$operator	= $val['operator'];
								
				$str .="{$category1},{$code1},{$category2},{$code2},{$category3},{$code3},{$category},{$code},{$total_code},{$dfm_code},{$time},{$operator}"."\n";
			}
		} 
		
	
		$str = mb_convert_encoding($str, "GBK", "UTF-8");
		$this->export_csv($filename,$str); //导出
	}
	
	function export_csv($filename,$data)
	{
		$filename = mb_convert_encoding($filename, "GBK","UTF-8");
		//		header("Content-type: text/html; charset=utf-8");
		header("Content-type:text/csv;charset=GBK");
		header("Content-Disposition:attachment;filename=".$filename);
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		//	header("Content-Length:".strlen($data));
		echo $data;
	}
		
	
	/**
	 * 获取层级父id
	 * @param unknown_type $id	
	 */
	public function _gettierpid($id)
	{
		if(empty($id))
		{
			return 0;
		}
		
		$result = (new \yii\db\Query())->from('oa_fault_category')->where('id=:id',[':id'=>$id])->one();
		if(!empty($result))
		{
			if($result['tier_pid'] === 0)   //顶级父id
			{
				return ','.$id.',';
			}else{
				return $result['tier_pid'].$id.',';
			}
			
		}else{
			return 0;
		}
	}
	
	/**
	 * 获取故障的总故障编码
	 * @param unknown_type $tierpid
	 */
	public function _gettotal_code($tier_pid)
	{
/* 		$tier_pid = substr($tier_pid, 0,-1);  //去掉最后的 “,”分号 */
		$tier_pid = trim($tier_pid,',');
		$result = (new \yii\db\Query())->from('oa_fault_category')->where("id in ({$tier_pid})")->all();
		if($result)
		{
			$str ='';
			foreach ($result as $k=>$row)
			{
				if($k == 0)
				{
					$str .= $row['code'];
				}else{
					$str .= '-'.$row['code'];
				}
				
			}
			return $str;
		}else{
			return null;
		}
	}
	
	/**
	 * 获取当前用户 name
	 */
	public function _operator()
	{
		$session = yii::$app->session;
		$session->open();
		
		return  $_SESSION['backend']['adminInfo']['name'];
	}
	
	/**
	 * 获取所有分类 combotree
	 */
	public function actionGetCategorys(){	
		$result = (new \yii\db\Query())->select('id,pid,category as text')->from('oa_fault_category')->where('is_category = 1')->orderBy('id ASC')->all();
		$data = [];
		if(!empty($result)){
			$nodes = Category::unlimitedForLayer($result,'pid');
		}
	
		//判断是否需要显示顶级根节点
		$isShowRoot = intval(yii::$app->request->get('isShowRoot'));
		$mark = yii::$app->request->get('mark');
		switch ($mark)
		{
			case 1:
				$mark = '不限';
				break;
			default:
				$mark = '作为一级分类';
				break;
		}
		
		
		if($isShowRoot){
			if(!empty($nodes)){
				$data = [['id'=>0,'text'=>$mark,'iconCls'=>'icon-filter','children'=>$nodes]];
			}else{
				$data = [['id'=>0,'text'=>$mark,'iconCls'=>'icon-filter','children'=>[]]];
			}
		}
		
		
/* 		echo '<pre>';
		var_dump($data);exit(); */
		return json_encode($data);
	}	
}	