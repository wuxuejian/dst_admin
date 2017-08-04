<?php
namespace backend\modules\drbac\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\RbacMca;
use yii\base\Object;
use backend\models\RbacActionBtn;
/**
 * module controller and action
 * @author Administrator
 *
 */
class McaController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /**
     * 获取模块列表
     */
    public function actionGetModuleList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = RbacMca::find()->andWhere(['type'=>0]);
        $query->andFilterWhere(['like','name',yii::$app->request->get('name')]);
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy('`list_order` desc,`id`')->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }
    
    /**
     * 添加模块
     */
    public function actionAddModule()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $model = new RbacMca();
            $model->setScenario('module');
            $model->load(yii::$app->request->post(),'');
            $model->type = 0;
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '模块添加成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '模块添加失败';
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
            return null;
        }
        //data submit end
        return $this->render('add-module');
    }
    
    /**
     * 修改模块数据
     */
    public function actionEditModule()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            $model = RbacMca::findOne(['id'=>$id]);
            $model->setScenario('module');
            $model->load(yii::$app->request->post(),'');
            $model->type = 0;
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '模块修改成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '模块修改失败';
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
            return null;
        }
        //data submit end
        $id = yii::$app->request->get('id') or die('param id is required');
        $moduleInfo = RbacMca::find()->where(['id'=>$id])->asArray()->one() or die('data not found');
        return $this->render('edit-module',[
            'moduleInfo'=>$moduleInfo
        ]);
    }
    
    /**
     * 模块管理
     */
    public function actionManageModule()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        return $this->render('manage-module',[
            'id'=>$id
        ]);
    }
    
    /**
     * 模块下控制器管理
     */
    public function actionManageController()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        return $this->render('manage-controller',[
            'id'=>$id//模块id
        ]);
    }
    /**
     * 模块下的获取控制器列表
     */
    public function actionGetControllerList()
    {
        $id = yii::$app->request->get('id') or die('param id is required');//模块id
        $module = RbacMca::find()
                  ->select(['module_code'])->where(['id'=>$id])->asArray()->one()
                  or die('module not found');
        //控制器列表
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = RbacMca::find()
                 ->andWhere(['module_code'=>$module['module_code']])
                 ->andWhere(['type'=>1]);
        //查询条件
        $query->andFilterWhere(['like','name',yii::$app->request->get('name')]);
        //查询条件结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy('`list_order` desc,`id`')->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }
    
    /**
     * 添加控制器
     */
    public function actionAddController()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $moduleId = yii::$app->request->post('moduleId') or die('param id is required');//模块id
            $module = RbacMca::find()
                      ->select(['module_code'])->where(['id'=>$moduleId])->asArray()->one()
                      or die('module not found');
            $model = new RbacMca();
            $model->setScenario('controller');
            $model->load(yii::$app->request->post(),'');
            $model->type = 1;
            $model->module_code = $module['module_code'];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '控制器添加成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '控制器添加失败';
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
            return null;
        }
        //data submit end
        $moduleId = yii::$app->request->get('moduleId') or die('param id is required');//模块id
        return $this->render('add-controller',[
            'moduleId'=>$moduleId
        ]);
    }
    
    /**
     * 修改控制器信息
     */
    public function actionEditController()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = intval(yii::$app->request->post('id')) or die('param id is required');
            $model = RbacMca::findOne($id);
            $model->setScenario('controller');
            $model->load(yii::$app->request->post(),'');
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '控制器修改成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '控制器修改失败';
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
            return null;
        }
        //data submit end
        $id = intval(yii::$app->request->get('id')) or die('param id is required');
        $controllerInfo = RbacMca::find()->where(['id'=>$id,'type'=>1])->asArray()->one() or die('controller info not found');
        return $this->render('edit-controller',[
            'controllerInfo'=>$controllerInfo
        ]);
    }
    
    /**
     * 方法管理
     */
    public function actionManageAction()
    {
        $id = yii::$app->request->get('id') or die('param id is required');//控制器id
        return $this->render('manage-action',[
            'id'=>$id
        ]);
    }
    
    /**
     * 获取控制器下的方法列表
     */
    public function actionGetActionList()
    {
        $id = yii::$app->request->get('id') or die('param id is required');//控制器id
        $controller = RbacMca::find()
                      ->select(['module_code','controller_code'])->where(['id'=>$id])
                      ->asArray()->one()
                      or die('controller not found');
        //控制器列表
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = RbacMca::find()
                 ->andWhere(['module_code'=>$controller['module_code']])
                 ->andWhere(['controller_code'=>$controller['controller_code']])
                 ->andWhere(['type'=>2]);
        //查询条件
        $query->andFilterWhere(['like','name',yii::$app->request->get('name')]);
        //查询条件结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy('`list_order` desc,`id`')->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }
    
    /**
     * 添加方法
     */
    public function actionAddAction()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $controllerId = yii::$app->request->post('controllerId') or die('param controllerId is required');//控制器id
            $controller = RbacMca::find()
                          ->select(['module_code','controller_code'])
                          ->where(['id'=>$controllerId])->asArray()->one()
                          or die('controller not found');
            $model = new RbacMca();
            $model->setScenario('action');
            $model->load(yii::$app->request->post(),'');
            $model->type = 2;
            $model->module_code = $controller['module_code'];
            $model->controller_code = $controller['controller_code'];
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '方法添加成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '方法添加失败';
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
            return null;
        }
        //data submit end
        $controllerId = yii::$app->request->get('controllerId') or die('param controllerId is required');//控制器id
        return $this->render('add-action',[
            'controllerId'=>$controllerId
        ]);
    }
    
    /**
     * 修改指定方法
     */
    public function actionEditAction()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');//方法id
            $model = RbacMca::findOne(['id'=>$id]);
            $model or die('action not found');
            $model->setScenario('action');
            $model->load(yii::$app->request->post(),'');
            $model->type = 2;
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '方法修改成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '方法修改失败';
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
            return null;
        }
        //data submit end
        $id = yii::$app->request->get('id') or die('param id is required');//方法id
        $actionInfo = RbacMca::find()
                      ->where(['id'=>$id,'type'=>2])->asArray()->one();
        $actionInfo or die('action not found');
        return $this->render('edit-action',[
            'actionInfo'=>$actionInfo
        ]);
    }
    
    /**
     * 管理页面按钮
     */
    public function actionManageActionBtn()
    {
        $actionId = yii::$app->request->get('actionId') or die('param actionId is required');
        return $this->render('manage-action-btn',[
            'actionId'=>$actionId
        ]);
    }
    
    /**
     * 获取方法的方法上的按钮列表
     */
    public function actionGetActionBtnList()
    {
        $actionId = yii::$app->request->get('actionId') or die('param actionid is required');
        //按钮列表
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = RbacActionBtn::find()
                 ->where(['action_id'=>$actionId,'is_del'=>0]);
        //查询条件
        $query->andFilterWhere(['like','text',yii::$app->request->get('text')]);
        //查询条件结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->orderBy('`list_order` desc,`id`')->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
        echo json_encode($returnArr);
    }
    
    /**
     * 为指定方法添加按钮
     */
    public function actionAddActionBtn()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $model = new RbacActionBtn();
            $model->setScenario('add');
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '按钮添加成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '按钮添加失败';
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
            return null;
        }
        //data submit end
        $actionId = yii::$app->request->get('actionId') or die('param actionId is required');
        return $this->render('add-action-btn',[
            'actionId'=>$actionId
        ]);
    }
    /**
     * 修改指定按钮
     */
    public function actionEditActionBtn()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            $model = RbacActionBtn::findOne(['id'=>$id]);
            $model or die('btn record not found');
            $model->setScenario('edit');
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '按钮修改成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '按钮修改失败';
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
            return null;
        }
        //data submit end
        $id = yii::$app->request->get('id') or die('param id is required');
        $btnInfo = RbacActionBtn::find()->where(['id'=>$id])->asArray()->one();
        $btnInfo or die('btn record not found');
        return $this->render('edit-action-btn',[
            'btnInfo'=>$btnInfo
        ]);
    }
    
    /**
     * 删除指定按钮
     */
    public function actionRemoveActionBtn()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $model = RbacActionBtn::findOne(['id'=>$id]);
        $model->is_del = 1;
        $returnArr = [];
        if($model->save(false)){
            $returnArr['status'] = true;
            $returnArr['info'] = '按钮删除成功';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '按钮失败';
        }
        echo json_encode($returnArr);
    }
}