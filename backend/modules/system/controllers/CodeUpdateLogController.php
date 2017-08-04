<?php
/**
 * 代码更新日志控制器
 */
namespace backend\modules\system\controllers;
use backend\controllers\BaseController;
use backend\models\SystemCodeUpdateLog;
use yii;
use yii\base\Object;
use yii\data\Pagination;
class CodeUpdateLogController extends BaseController
{
	public function actionIndex()
	{
		$buttons = $this->getCurrentActionBtn();
		return $this->render('index',[
				'buttons'=>$buttons
				]);
	}
	
	/**
	 * 获取日志列表
	 */
	public function actionGetList()
	{
		$product = yii::$app->request->get('product');
		$update_type = yii::$app->request->get('update_type');
		$start_update_date = yii::$app->request->get('start_update_date');
		$end_update_date = yii::$app->request->get('end_update_date');
		
		$query = SystemCodeUpdateLog::find()
		->select(['*']);
		//查询条件
		if($product){
			$query->andFilterWhere(['=','`product`',$product]);
		}
		if($update_type){
			$query->andFilterWhere(['=','`update_type`',$update_type]);
		}
		if($start_update_date){
			$query->andFilterWhere(['>=','`update_date`',$start_update_date]);
		}
		if($end_update_date){
			$query->andFilterWhere(['<=','`update_date`',$end_update_date]);
		}
		//排序
		$sortColumn = yii::$app->request->get('sort');
		$sortType = yii::$app->request->get('order') == 'desc' ? 'desc' : 'asc';
		$orderBy = '';
		if($sortColumn){
			$orderBy = '`'.$sortColumn.'` ';
		}else{
			$orderBy = '`id` ';
		}
		$orderBy .= $sortType;
		$total = $query->count();
		$pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
		$pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
		$query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy);
		$rows = $query->asArray()->all();
		$returnArr = [];
		$returnArr['rows'] = $rows;
		$returnArr['total'] = $total;
		return json_encode($returnArr);
	}
	
	/**
	 * 添加日志
	 */
	public function actionAdd()
	{
		if(yii::$app->request->isPost){
			$formData = yii::$app->request->post();
			$model = new SystemCodeUpdateLog();
			$model->load($formData,'');
			$model->oper_user = $_SESSION['backend']['adminInfo']['username'];
			if($model->save(true)){
				$returnArr['status'] = true;
				$returnArr['info'] = '日志添加成功！';
			}else{
				$returnArr['status'] = false;
				$error = $model->getErrors();
				if($error){
					$errorStr = join('',array_column($error,0));
				}else{
					$errorStr = '未知错误！';
				}
				$returnArr['info'] = $errorStr;
			}
			return json_encode($returnArr);
		}else{
			return $this->render('add');
		}
	}
	
	/**
	 * 修改日志
	 */
	public function actionEdit()
	{
		//data submit
		if(yii::$app->request->isPost){
			$formData = yii::$app->request->post();
			$model = SystemCodeUpdateLog::findOne(['id'=>$formData['id']]);
			if(!$model){
				die('未找到对应日志！');
			}
			$model->load($formData,'');
			if($model->validate()){
				if($model->save(false)){
					$returnArr['status'] = true;
					$returnArr['info'] = '日志修改成功';
				}else{
					$returnArr['status'] = false;
					$returnArr['info'] = '日志修改失败';
				}
			}else{
				$returnArr['status'] = false;
				$error = $model->getErrors();
				if($error){
					$errorStr = join('',array_column($error,0));
				}else{
					$errorStr = '未知错误';
				}
				$returnArr['info'] = $errorStr;
			}
			return json_encode($returnArr);
		}else{
			$id = intval(yii::$app->request->get('id'));
			$log = SystemCodeUpdateLog::find()->where(['id'=>$id])->asArray()->one();
			if(!$log){
				die('未找到对应日志！');
			}
			return $this->render('edit',['log'=>$log]);
		}
	}
	
	/**
	 * 删除任务
	 */
	public function actionDel()
	{
		$id = intval(yii::$app->request->get('id')) or die('param(id) is required');
		$returnArr = [];
		if(SystemCodeUpdateLog::deleteAll(['id'=>$id])){
			$returnArr['status'] = true;
			$returnArr['info'] = '日志删除成功！';
		}else{
			$returnArr['status'] = false;
			$returnArr['info'] = '日志删除失败！';
		}
		echo json_encode($returnArr);
	}
	
	/**
	 * 查看详情
	 */
	public function actionDetail(){
		$id = intval(yii::$app->request->get('id'));
		$log = SystemCodeUpdateLog::find()->where(['id'=>$id])->asArray()->one();
		if(!$log){
			die('未找到对应日志！');
		}
		return $this->render('detail',['log'=>$log]);
	}
}