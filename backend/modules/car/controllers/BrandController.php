<?php
/**
 * 车辆品牌管理控制器
 * time    2016/02/20
 * @author wangmin
 */
namespace backend\modules\car\controllers;
use backend\controllers\BaseController;
use backend\models\CarBrand;
use yii;
use yii\data\Pagination;
class BrandController extends BaseController{
	/**
	 * 车辆品牌管理入口
	 */
	public function actionIndex(){
		$buttons = $this->getCurrentActionBtn();
		return $this->render('index',[
			'buttons'=>$buttons,
		]);
	}

	/**
	 * 获取车辆品牌列表
	 */
	public function actionGetList(){
/*        // 【不能查子品牌】
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = CarBrand::find()
            ->select(['id','name','code','note'])
            ->andWhere(['`is_del`'=>0,'pid'=>0]);
        ////查询条件开始
        $query->andFilterWhere(['like','`name`',yii::$app->request->get('name')]);
        $query->andFilterWhere(['like','`code`',yii::$app->request->get('code')]);
        ////查询条件结束
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            $orderBy = '`'.$sortColumn.'` ';
        }else{
            $orderBy = '`id` ';
        }
        $orderBy .= $sortType;
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        if($data){
            $pids = join(',',array_column($data,'id'));
            $children = CarBrand::find()->where(['pid'=>$pids,'is_del'=>0])
                ->asArray()->all();
            foreach($data as &$_cBrandItem){
                $_cBrandItem['children'] = [];
                foreach($children as $val){
                    if($val['pid'] == $_cBrandItem['id']){
                        unset($val['pid']);
                        unset($val['is_del']);
                        $_cBrandItem['children'][] = $val;
                    }
                }
            }
        }
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);*/

        $query = CarBrand::find()
            ->select(['id','pid','name','code','note'])
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
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->indexBy('id')->asArray()->all();
        //print_r($data);exit;

        if($data){
            //获取所有父ID，再将父ID降序排列，这样循环可以从最低级往上级层层查找。
            $pIds = array_unique(array_column($data,'pid'));
            rsort($pIds);
            foreach($pIds as $pId) {
                foreach($data as $k=>$item){
                    if($item['pid'] == $pId){
                        $data[$pId]['children'][] = $item;
                        unset($data[$k]);
                    }
                }
            }
            $data = $data[0]['children'];
        }
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
	}

	/**
	 * 添加品牌
	 */
	public function actionAdd(){
        if(yii::$app->request->isPost){
            $returnArr = [
                'status'=>false,
                'info'=>'',
            ];
            $model = new CarBrand;
            $model->load(yii::$app->request->post(),'');
            $model->pid = intval($model->pid);
            if($model->save(true)){
                $returnArr['status'] = true;
                $returnArr['info'] = '品牌添加成功！';
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
     * 修改车辆品牌
     */
    public function actionEdit(){
        if(yii::$app->request->isPost){
            $returnArr = [
                'status'=>false,
                'info'=>'',
            ];
            $id = yii::$app->request->post('id');
            $model = CarBrand::findOne(['id'=>$id]);
            if(!$model){
                $returnArr['info'] = '记录未找到！';
                echo json_encode($returnArr);
                return;
            }
            $model->load(yii::$app->request->post(),'');
            $model->pid = intval($model->pid);
            if($model->save(true)){
                $returnArr['status'] = true;
                $returnArr['info'] = '品牌修改成功！';
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
            $id = yii::$app->request->get('id');
            if(!$id){
                echo 'param id is required!';
                return;
            }
            $model = CarBrand::findOne(['id'=>$id]);
            if(!$model){
                echo 'record not found!';
                return;
            }
            return $this->render('edit',[
                'brandInfo'=>$model->getOldAttributes(),
            ]);
        }
    }

    /**
     * 删除车辆品牌
     */
    public function actionRemove(){
        $returnArr = [
            'status'=>false,
            'info'=>'',
        ];
        $id = yii::$app->request->get('id');
        $num = CarBrand::updateAll(['is_del' => 1], ['id'=>$id]);
        if($num){
            $returnArr['status'] = true;
            $returnArr['info'] = '品牌删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '品牌删除失败！';
        }
        echo json_encode($returnArr);exit;
    }
}