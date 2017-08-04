<?php
/*
 * 计划任务 控制器
 */
namespace backend\modules\system\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\ConfigCategory;
use backend\models\SystemTask;

class TaskController extends BaseController
{
    public function actionIndex()
    {
        $configItems = ['exec_frequency'];
        $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons,
            'config'=>$config
        ]);
    }
    
    /**
     * 获取任务列表
     */
    public function actionGetListData()
    {
        $query = SystemTask::find()
            ->select(['*']);
        //查询条件
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
        $query->offset($pages->offset)->limit($pages->limit)->orderBy($orderBy);
        $rows = $query->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $rows;
        $returnArr['total'] = $total;
        return json_encode($returnArr);
    }
    
    /**
     * 添加任务
     */
    public function actionAdd()
    {
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            $model = new SystemTask();
            $model->load($formData,'');
            if($model->save(true)){
                $returnArr['status'] = true;
                $returnArr['info'] = '计划任务添加成功！';
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
            $configItems = ['exec_frequency'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            return $this->render('add',['config'=>$config]);
        }
    }
    
    /**
     * 修改任务
     */
    public function actionEdit()
    {
        //data submit
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            $model = SystemTask::findOne(['id'=>$formData['id']]);
            if(!$model){
                die('未找到对应计划任务！');
            }
            $model->load($formData,'');
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '计划任务修改成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '计划任务修改失败';
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
            $task = SystemTask::find()->where(['id'=>$id])->asArray()->one();
            if(!$task){
                die('未找到对应计划任务！');
            }
            $configItems = ['exec_frequency'];
            $config = (new ConfigCategory())->getCategoryConfig($configItems,'value');
            //若当前执行频率是自定义的则修改时添加入下拉列表中显示
            $keys = array_keys($config['exec_frequency']);
            if(!in_array($task['exec_frequency'], $keys)){
                $config['exec_frequency'][$task['exec_frequency']] = ['value'=>$task['exec_frequency'],'text'=>$task['exec_frequency']];
            }
            return $this->render('edit',['task'=>$task,'config'=>$config]);
        }
    }
    
    /**
     * 删除任务
     */
    public function actionRemove()
    {
        $id = intval(yii::$app->request->get('id')) or die('param(id) is required');
        $returnArr = [];
        if(SystemTask::deleteAll(['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '计划任务删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '计划任务删除失败！';
        }
        echo json_encode($returnArr);
    }



}