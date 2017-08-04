<?php
namespace backend\modules\drbac\controllers;
use backend\controllers\BaseController;
use backend\models\Department;
use yii;
use yii\data\Pagination;
use common\classes\Category;
/**
 * 部门管理控制器
 */
class DepartmentController extends BaseController
{
    public function actionIndex()
    {
    	if($_SERVER['REQUEST_METHOD'] == 'POST')
    	{
    		//运营公司
    		$oc = yii::$app->request->post('operating_company_id');
    		$oc = !empty($oc) ? $oc:0;
    		$result = (new \yii\db\Query())->from('cs_department')->where('is_del=0 AND operating_company_id=:oc',[':oc'=>$oc])->all();
    		$data = array();
    		if($result){
    			$data = Category::unlimitedForLayer($result,'pid');
    		}

    		$returnArr = [];
    		$returnArr['rows'] = $data;
    		$returnArr['total'] = count($data);
    		return json_encode($returnArr);
    	}
    	$searchFormOptions = [];
    	//车辆运营公司
    	$oc = (new \yii\db\Query())->from('cs_operating_company')->where('is_del=0')->all();
    	if($oc)
    	{
    		$searchFormOptions['operating_company_id'] = [];
    		$searchFormOptions['operating_company_id'][] = ['value'=>'','text'=>''];
    		foreach($oc as $val){
    			$searchFormOptions['operating_company_id'][] = ['value'=>$val['id'],'text'=>$val['name']];
    	
    		}
    	}
    	
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',['buttons'=>$buttons,'searchFormOptions'=>$searchFormOptions]);
    }
    

    
    
    /**
     * 获取所有分类 combotree
     */
    public function actionGetCategorys(){
    	$oc = yii::$app->request->get('oc');
    	$result = (new \yii\db\Query())->select('id,pid,name as text')->from('cs_department')->where('is_del = 0 AND operating_company_id=:oc',[':oc'=>$oc])->all();
    	$data = [];
    	if(!empty($result)){
    		$nodes = Category::unlimitedForLayer($result,'pid');
    	}
    	$mark = yii::$app->request->get('mark');
    	switch ($mark)
    	{
    		case 1:
    			$mark = '';
    			break;
    		default:
    			$mark = '作为一级部门';
    		break;
    	}
    	
    	
    	//判断是否需要显示顶级根节点
    	$isShowRoot = intval(yii::$app->request->get('isShowRoot'));
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

    /**
     * 获取部门列表
     */
/*     public function actionGetDepartmentList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = Department::find()->where(['is_del'=>0]);
        //查询条件开始
        $query->andFilterWhere(['like','name',yii::$app->request->get('name')]);
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            $orderBy = Department::tableName().'.`'.$sortColumn.'` ';
        }else{
           $orderBy = Department::tableName().'.`id` ';
        }
        $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    } */

    /**
     * 添加部门
     */
    public function actionAdd()
    {
        //data submit start
        if(yii::$app->request->isPost){
            /* $model = new Department();
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '部门添加成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '部门添加失败';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $errorStr = '';
                    foreach($error as $val){
                        $errorStr .= $val[0];
                    }
                    $returnArr['info'] = $errorStr;
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            echo json_encode($returnArr);
            return null; */
        	
        	$pid		= intval(yii::$app->request->post('pid'));
        	$oc			= yii::$app->request->post('operating_company_id');
        	$name 		= yii::$app->request->post('name');
        	$note 		= yii::$app->request->post('note');        	
        	$db = \Yii::$app->db;
        	$result = $db->createCommand()->insert('cs_department',
        			['pid'							=> $pid,
        			'operating_company_id'			=> $oc,
        			'name'							=> $name,
        			'note'							=> $note,
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
        
        $oc = (new \yii\db\Query())->from('cs_operating_company')->where('is_del=0')->all();
        
        //data submit end
        return $this->render('add',['oc'=>$oc]);
    }

    /**
     * 修改部门
     */
    public function actionEdit()
    {
        //data submit start
        if(yii::$app->request->isPost){
            /* $id = yii::$app->request->post('id') or die('param id is required');
            $model = Department::findOne(['id'=>$id]);
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '部门信息修改成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '部门信息修改失败';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $errorStr = '';
                    foreach($error as $val){
                        $errorStr .= $val[0];
                    }
                    $returnArr['info'] = $errorStr;
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            echo json_encode($returnArr);
            return null; */
        	$id			= intval(yii::$app->request->post('id'));
        	$pid		= yii::$app->request->post('pid');
        	$oc			= yii::$app->request->post('operating_company_id');
        	$name 		= yii::$app->request->post('name');
        	$note 		= yii::$app->request->post('note');
        	$db = \Yii::$app->db;
        	try {
        		$result = $db->createCommand()->update('cs_department',
        				['pid'							=> $pid,
        				'operating_company_id'			=> $oc,
        				'name'							=> $name,
        				'note'							=> $note,
        				],'id=:id',[':id'=>$id])->execute();
        		$returnArr['status'] = true;
        		$returnArr['info'] = '修改成功！';
        	} catch (Exception $e) {
        		$returnArr['status'] = false;
        		$returnArr['info'] = '修改失败！';
        	}
        	
        	return json_encode($returnArr);
        }
        //data submit end
        $id = intval(yii::$app->request->get('id')) or die('param id is required');
        $result = Department::find()->where(['id'=>$id])->asArray()->one();
        $result or die('record is not found');
        $oc = (new \yii\db\Query())->from('cs_operating_company')->where('is_del=0')->all();
        return $this->render('edit',['result'=>$result,'oc'=>$oc]);
    }

    /**
     * 删除部门
     */
    public function actionRemove()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $db = \Yii::$app->db;
        //检测其它模块是否有关联
        //1.检查是否有自用备用车记录
        $car_stock = (new \yii\db\Query())->select('id')->from('cs_car_stock')->where('is_del = 0 AND department_id=:department_id',[':department_id'=>$id])->one();
        if($car_stock){
        	$returnArr['status'] = true;
        	$returnArr['info'] = '存在自用备用车记录，不能删除！';
        	exit(json_encode($returnArr));
        }
        //检测end
        
        $result = $db->createCommand()->update('cs_department',
        		[
        		'is_del'=>1
        		],'id=:id OR pid=:pid',[':id'=>$id,':pid'=>$id])->execute();
        
        $returnArr = [];
        if($result)
        {
        	$returnArr['status'] = true;
        	$returnArr['info'] = '部门删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '部门删除失败！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 获取指定部门的成员列表
     */
/*     public function actionGetUserList()
    {

    }
 */
    /**
     * 部门添加成员
     */
/*     public function actionAddUser()
    {

    } */

    /**
     * 部门删除成员
     */
/*     public function actionRemoveUser()
    {

    } */

}