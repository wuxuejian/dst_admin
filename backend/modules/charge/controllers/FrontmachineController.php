<?php
/**
 * @Desc:   充电桩前置机控制器 
 * @author: wangmin
 * @date:   2015-12-19 09:21
 */
namespace backend\modules\charge\controllers;
use backend\controllers\BaseController;
use backend\models\ChargeFrontmachine;
use yii;
use yii\data\Pagination;
use common\models\Excel;
class FrontmachineController extends BaseController{
    
    /**
     * 前置机管理
     */
    public function actionIndex(){
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons,
        ]);
    }

    /**
     * 获取前置机列表
     */
    public function actionGetList(){
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = ChargeFrontmachine::find()->andWhere(['`is_del`'=>0]);
        //查询条件
        $query->andFilterWhere(['like','`addr`',yii::$app->request->get('addr')]);
        //查询条件结束
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            //switch ($sortColumn) {
                //case 'username':
                    //$orderBy = '{{%admin}}.`'.$sortColumn.'` ';
                    //break;
                //default:
                    $orderBy = '`'.$sortColumn.'` ';
                    //break;
            //}
        }else{
            $orderBy = '`id` ';
        }
        $orderBy .= $sortType;
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy)->asArray()->all();
        unset($data[0]['db_password']);
        $returnArr = [];
        $returnArr['rows'] = $data; 
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }

    /**
     * 添加前置机
     */
    public function actionAdd(){
        if(yii::$app->request->isPost){
            //post请求
            $model = new ChargeFrontmachine();
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            $returnArr['status'] = false;
            $returnArr['info'] = '';
            if($model->validate() && $model->save(false)){
                $returnArr['status'] = true;
                $returnArr['info'] = '前置机添加成功！';
            }else{
                $returnArr['status'] = false;
                $errors = $model->getErrors();
                if($errors){
                    $returnArr['info'] = join('',array_column($errors,0));
                }else{
                    $returnArr['info'] = '数据保存失败！';
                }
            }
            echo json_encode($returnArr);
            return;
        }else{
            //get请求
            return $this->render('add');
        }
    }

    /**
     * 修改前置机
     */
    public function actionEdit(){
        if(yii::$app->request->isPost){
            //post请求
            $returnArr = [];
            $returnArr['status'] = false;
            $returnArr['info'] = '';
            $model = ChargeFrontmachine::findOne(['id'=>yii::$app->request->post('id')]);
            if(!$model){
                $returnArr['status'] = false;
                $returnArr['info'] = '记录未找到！';
                echo json_encode($returnArr);
                return;
            }
            $model->load(yii::$app->request->post(),'');
            if($model->validate() && $model->save(false)){
                $returnArr['status'] = true;
                $returnArr['info'] = '前置机修改成功！';
            }else{
                $returnArr['status'] = false;
                $errors = $model->getErrors();
                if($errors){
                    $returnArr['info'] = join('',array_column($errors,0));
                }else{
                    $returnArr['info'] = '数据保存失败！';
                }
            }
            echo json_encode($returnArr);
            return;
        }else{
            //get请求
            $model = ChargeFrontmachine::findOne(['id'=>yii::$app->request->get('id')]);
            if(!$model){
                echo 'record not found!';
                return;
            }
            $oldData = $model->getOldAttributes();
            unset($oldData['db_password']);
            
            return $this->render('edit',[
                'oldData'=>$oldData,
            ]);
        }
    }

    /**
     * 删除前置机
     */
    public function actionRemove(){
        $id = intval(yii::$app->request->get('id'));
        $returnArr = [];
        $returnArr['status'] = false;
        $returnArr['info'] = '';
        if($id && ChargeFrontmachine::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '前置机删除成功！';
        }else{
            $returnArr['info'] = '前置机删除失败！';
        }
        echo json_encode($returnArr);
    }
}