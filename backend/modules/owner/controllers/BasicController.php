<?php
/**
 * @Desc:	机动车所有人基本管理 控制器
 * @author: chengwk
 * @date:	2016-02-25
 */
namespace backend\modules\owner\controllers;
use backend\controllers\BaseController;
use backend\models\Owner;
use yii;
use yii\data\Pagination;
use common\classes\Category;

class BasicController extends BaseController{
	/**
	 * 机动车所有人管理入口
	 */
	public function actionIndex(){
		$buttons = $this->getCurrentActionBtn();
		return $this->render('index',[
			'buttons'=>$buttons,
		]);
	}

	/**
	 * 获取机动车所有人列表
	 */
	public function actionGetList(){
        $query = Owner::find()
            ->select(['id','pid','name','code','addr','note'])
            ->andWhere(['`is_del`'=>0]);
        //查询条件开始
        $query->andFilterWhere(['like','`name`',yii::$app->request->get('name')]);
        $query->andFilterWhere(['like','`code`',yii::$app->request->get('code')]);
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
	 * 添加所有人
	 */
	public function actionAdd(){
        if(yii::$app->request->isPost){
            $returnArr = [
                'status'=>false,
                'info'=>'',
            ];
            $model = new Owner;
            $model->load(yii::$app->request->post(),'');
            $model->pid = intval($model->pid);
            if($model->save(true)){
                $returnArr['status'] = true;
                $returnArr['info'] = '所有人添加成功！';
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
     * 修改机动车所有人
     */
    public function actionEdit(){
        if(yii::$app->request->isPost){
            $returnArr = [
                'status'=>false,
                'info'=>'',
            ];
            $id = yii::$app->request->post('id');
            $model = Owner::findOne(['id'=>$id]);
            if(!$model){
                $returnArr['info'] = '记录未找到！';
                return json_encode($returnArr);
            }
            $model->load(yii::$app->request->post(),'');
            $model->pid = intval($model->pid);
            if($model->save(true)){
                $returnArr['status'] = true;
                $returnArr['info'] = '所有人修改成功！';
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
            $model = Owner::findOne(['id'=>$id]);
            if(!$model){
                return 'record not found!';
            }
            return $this->render('edit',[
                'recordInfo'=>$model->getOldAttributes(),
            ]);
        }
    }

    /**
     * 删除机动车所有人
     */
    public function actionRemove(){
        $returnArr = [
            'status'=>false,
            'info'=>'',
        ];
        $id = yii::$app->request->get('id');
        $num = Owner::updateAll(['is_del' => 1], ['id'=>$id]);
        if($num){
            $returnArr['status'] = true;
            $returnArr['info'] = '所有人删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '所有人删除失败！';
        }
        echo json_encode($returnArr);exit;
    }


}