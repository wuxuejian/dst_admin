<?php
/**
 * @Desc:	车辆运营公司管理 控制器
 * @author: chengwk
 * @date:	2016-03-23
 */
namespace backend\modules\operating\controllers;
use backend\controllers\BaseController;
use backend\models\OperatingCompany;
use yii;
use yii\data\Pagination;
use backend\classes\UserLog;
use common\classes\Category;

class BasicController extends BaseController{
	/**
	 * 车辆运营公司管理入口
	 */
	public function actionIndex(){
		$buttons = $this->getCurrentActionBtn();
		return $this->render('index',[
			'buttons'=>$buttons,
		]);
	}

	/**
	 * 获取车辆运营公司列表
	 */
	public function actionGetList(){
        $query = OperatingCompany::find()
            ->select(['id','pid','name','addr','note','area'])
            ->andWhere(['`is_del`'=>0]);
        //查询条件开始
        $query->andFilterWhere(['like','`name`',yii::$app->request->get('name')]);
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
        $rows = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->indexBy('id')->asArray()->all();
        $data = [];
        if($rows){
            $data = Category::unlimitedForLayer($rows,'pid');
        }
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
	}

	/**
	 * 添加运营公司
	 */
	public function actionAdd(){
        if(yii::$app->request->isPost){
            $returnArr = [
                'status'=>false,
                'info'=>'',
            ];
            $model = new OperatingCompany;
            $model->load(yii::$app->request->post(),'');
            $model->pid = intval($model->pid);
            if($model->save(true)){
                $returnArr['status'] = true;
                $returnArr['info'] = '运营公司添加成功！';
                UserLog::log('添加车辆运营公司【'.$model->name.'】','sys');
            }else{
                $errors = $model->getErrors();
                if($errors){
                    $returnArr['info'] = join('',array_column($errors,0));
                }else{
                    $returnArr['info'] = '未知错误！';
                }
            }
            echo json_encode($returnArr);
            return;
        }else{
            return $this->render('add');
        }
	}

    /**
     * 修改车辆运营公司
     */
    public function actionEdit(){
        if(yii::$app->request->isPost){
            $returnArr = [
                'status'=>false,
                'info'=>'',
            ];
            $id = yii::$app->request->post('id');
            $model = OperatingCompany::findOne(['id'=>$id]);
            if(!$model){
                $returnArr['info'] = '记录未找到！';
                return json_encode($returnArr);
            }
            $model->load(yii::$app->request->post(),'');
            $model->pid = intval($model->pid);
            if($model->save(true)){
                $returnArr['status'] = true;
                $returnArr['info'] = '运营公司修改成功！';
                UserLog::log('修改车辆运营公司【'.$model->name.'】','sys');
            }else{
                $errors = $model->getErrors();
                if($errors){
                    $returnArr['info'] = join('',array_column($errors,0));
                }else{
                    $returnArr['info'] = '未知错误！';
                }
            }
            return json_encode($returnArr);
        }else{
            $id = yii::$app->request->get('id');
            if(!$id){
                return 'param id is required!';
            }
            $model = OperatingCompany::findOne(['id'=>$id]);
            if(!$model){
                return 'record not found!';
            }
            return $this->render('edit',[
                'recordInfo'=>$model->getOldAttributes(),
            ]);
        }
    }

    /**
     * 删除车辆运营公司
     */
    public function actionRemove(){
        $id = yii::$app->request->get('id');
        $num = OperatingCompany::updateAll(['is_del' => 1], ['id'=>$id]);
        if($num){
            $returnArr['status'] = true;
            $returnArr['info'] = '运营公司删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '运营公司删除失败！';
        }
        echo json_encode($returnArr);exit;
    }


}