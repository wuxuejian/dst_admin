<?php
namespace backend\modules\drbac\controllers;
use backend\controllers\BaseController;
use backend\models\RbacRole;
use backend\models\Admin;
use backend\models\AdminRole;
use backend\models\RbacMca;
use backend\models\RbacRoleMca;
use yii;
use yii\data\Pagination;
use yii\base\Object;
/**
 * 角色与权限管理类
 * @author Administrator
 *
 */
class RoleAccessController extends BaseController
{
    public function actionIndex()
    {
        if(self::$isSuperman && !isset($_SESSION['backend']['simulation'])){
            //开发人员账号
            $isTrueSuperman = true;
        }else{
            //非开发人员或开发人员模拟其他用户
            $isTrueSuperman = false;
        }
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'isTrueSuperman'=>$isTrueSuperman,
            'buttons'=>$buttons,
        ]);
    }
    
    /**
     * 获取角色列表
     */
    public function actionGetRoleList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = RbacRole::find()->where(['is_del'=>0]);
        //查询条件开始
        $query->andFilterWhere(['like','name',yii::$app->request->get('name')]);
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            $orderBy = RbacRole::tableName().'.`'.$sortColumn.'` ';
        }else{
           $orderBy = RbacRole::tableName().'.`id` ';
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
    }
    
    /**
     * 添加角色
     */
    public function actionAddRole()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $model = new RbacRole();
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '角色添加成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '角色添加失败';
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
        return $this->render('add-role');
    }
    
    /**
     * 修改角色信息
     */
    public function actionEditRole()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            //不允许修改超级管理员
            if($id == 1){
                echo json_encode(['status'=>false,'info'=>'无法修改系统角色"超级管理员"！']);
                die;
            }
            $model = RbacRole::findOne(['id'=>$id]);
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '角色修改成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '角色修改失败';
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
        $roleInfo = RbacRole::find()->where(['id'=>$id])->asArray()->one() or die('role record is not found');
        return $this->render('edit-role',[
            'roleInfo'=>$roleInfo
        ]);
    }

    /**
     * 删除角色
     */
    public function actionRemoveRole()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $returnArr = [];
        if($id == 1){
            $returnArr['status'] = false;
            $returnArr['info'] = '不能删除系统角色"超级管理员"！';
            echo json_encode($returnArr);
            die;
        }
        if(RbacRole::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '角色删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '角色删除失败！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 角色成员管理
     */
    public function actionMemberManage()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $roleId = yii::$app->request->post('roleId') or die('param roleId is required');
            //删除原有成员信息
            AdminRole::deleteAll(['role_id'=>$roleId]);
            //插入新成员
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            $adminIds = yii::$app->request->post('admin_id');
            if($adminIds){
                foreach($adminIds as $key=>$val){
                    $model = new AdminRole;
                    $model->admin_id = $val;
                    $model->role_id = $roleId;
                    if($model->validate()){
                        if(!$model->save(false)){
                            //添加失败
                            $returnArr['status'] = false;
                            $returnArr['info'] = '用户ID:$val，添加失败！';
                        }
                    }else{
                        $returnArr['status'] = false;
                        $error = $model->getErrors();
                        if($error){
                            $errorStr = '';
                            foreach($error as $val){
                                $errorStr .= $val[0];
                            }
                            $returnArr['info'] .= $errorStr;
                        }else{
                            $returnArr['info'] .= '未知错误！';
                        } 
                    }
                }
            }
            $returnArr['info'] .= '操作完成！';
            echo json_encode($returnArr);
            return null;
            return null;
        }
        //data submit end
        $roleId = yii::$app->request->get('roleId') or die('param roleId is required');
        //查询所有用户
        $admin = Admin::find()
                 ->select(['id','username','name','sex'])
                 ->where(['is_del'=>0,'super'=>0])
                 ->asArray()
                 ->all();
        //查询当前角色所有的用户
        $roleAdmin = AdminRole::find()
                     ->select(['admin_id'])
                     ->where(['role_id'=>$roleId])
                     ->indexBy('admin_id')->asArray()->all();
        return $this->render('member-manage',[
            'admin'=>$admin,
            'roleId'=>$roleId,
            'roleAdmin'=>$roleAdmin
        ]);
    }

    /**
     * 角色权限管理
     */
    public function actionAccessManage()
    {
        if(yii::$app->request->isPost){
            $roleId = yii::$app->request->post('roleId') or die('param roleId is required');
            //不允许非开发人员账号或开发人员账号模拟其它用户修改超级管理员权限
            if($roleId == 1 && (!self::$isSuperman || isset($_SESSION['backend']['simulation']))){
                echo json_encode(['status'=>false,'info'=>'无权修改系统角色"超级管理员"权限！']);
                die;
            }
            //删除该角色所有可访问的mca
            RbacRoleMca::deleteAll(['role_id'=>$roleId]);
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            if(yii::$app->request->post('actionIds')){
                foreach(yii::$app->request->post('actionIds') as $val){
                    $model = new RbacRoleMca;
                    $model->role_id = $roleId;
                    $model->mca_id = $val;
                    if($model->validate()){
                       $model->save(false);
                    }else{
                        $returnArr['status'] = false;
                        $error = $model->getErrors();
                        if($error){
                            $errorStr = '';
                            foreach($error as $val){
                                $errorStr .= $val[0];
                            }
                            $returnArr['info'] .= $errorStr;
                        }else{
                            $returnArr['info'] .= '未知错误！';
                        } 
                    }
                }
            }
            $returnArr['info'] .= '操作完成！';
            echo json_encode($returnArr);
            return null;
        }
        $roleId = yii::$app->request->get('roleId') or die('param roleId is required');
        if($roleId == 1 && !self::$isSuperman){
            die('无权修改系统角色"超级管理员"权限！');
        }
        //查询所有模块
        $_modules = RbacMca::find()
                   ->where(['type'=>0])
                   ->orderBy('`list_order` desc,`id`')
                   ->asArray()->all();
        //查询所有方法过滤程序员独立访问方法
        $_actions = RbacMca::find()
                   ->where(['type'=>2,'programmer'=>0])
                   ->orderBy('`module_code`,`controller_code`,`list_order` desc')
                   ->asArray()->all();
        $actions = [];
        foreach($_actions as $val){
            $key = $val['module_code'];
            if(!isset($val[$key])){
                $val[$key] = [];
            }
            $actions[$key][] = $val;
        }
        unset($_actions);
        $modules = [];
        foreach($_modules as $val){
            $key = $val['module_code'];
            if(isset($actions[$key])){
                $val['children'] = $actions[$key];
            }else{
                $val['children'] = [];
            }
            $modules[] = $val;
        }
        unset($_modules);
        //获取当前角色已经有的权限
        $accessActionIds = RbacRoleMca::find()->select(['mca_id'])
                           ->where(['role_id'=>$roleId])
                           ->asArray()->all();
        if($accessActionIds){
            $accessActionIds = array_column($accessActionIds,'mca_id');
        }
        return $this->render('access-manage',[
            'roleId'=>$roleId,
            'modules'=>$modules,
            'accessActionIds'=>$accessActionIds,
        ]);
    }

}