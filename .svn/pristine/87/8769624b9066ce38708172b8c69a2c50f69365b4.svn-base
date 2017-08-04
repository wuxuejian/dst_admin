<?php
namespace backend\modules\system\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\models\SystemMenu;
use backend\models\RbacMca;
use common\classes\Category;
use backend\classes\UserLog;

class MenuController extends BaseController
{
    public function actionIndex()
    {
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons,
        ]);
    }
    
    /**
     * 获取菜单列表
     */
    public function actionGetListData()
    {
        $query = SystemMenu::find()
            ->andWhere(['`is_del`'=>0]);
        //查询条件
        $query->andFilterWhere(['like','`name`',yii::$app->request->get('name')]);
        //排序
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'desc' ? 'desc' : 'asc';
        $orderBy = '';
        if($sortColumn){
            $orderBy = '`'.$sortColumn.'` ';
            $orderBy .= $sortType;
        }else{
            $orderBy = '`list_order` DESC,`id` ASC';
        }
        $rows = $query->orderBy($orderBy)->asArray()->all();
        $data = [];
        if($rows){
            $data = Category::unlimitedForLayer($rows,'pid');
        }
        //print_r($data);exit;
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = count($data);
        return json_encode($returnArr);
    }
    
    /**
     * 添加菜单
     * system/menu/add
     */
    public function actionAdd()
    {
        //data submit
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            //若有填写mca则检查它在数据表cs_rbac_mca中是否已存在。
            $mca = trim($formData['mca']);
            if($mca){
                $mcaArr = explode('/',$mca);
                if(!$mcaArr || count($mcaArr) != 3){
                    return json_encode(['status'=>false, 'info'=>'请注意“菜单MCA”要遵循以下格式填写：模块/控制器/方法！']);
                }else{
                    $row = RbacMca::find()->where(['module_code'=>$mcaArr[0],'controller_code'=>$mcaArr[1],'action_code'=>$mcaArr[2]])->asArray()->one();
                    if(!$row){
                        return json_encode(['status'=>false, 'info'=>'您填写的“菜单MCA”在数据表cs_rbac_mca中不存在！']);
                    }
                }
            }
            $model = new SystemMenu();
            $model->load($formData,'');
            if($model->save(true)){
                $returnArr['status'] = true;
                $returnArr['info'] = '菜单添加成功！';
                UserLog::log('菜单管理-添加菜单（id：' . $model->id . '）', 'sys');
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
            $id = intval(yii::$app->request->get('id'));
            return $this->render('add',['curMenuId'=>$id]);
        }
    }
    
    /**
     * 修改菜单
     */
    public function actionEdit()
    {
        //data submit
        if(yii::$app->request->isPost){
            $formData = yii::$app->request->post();
            //若有填写mca则检查它在数据表cs_rbac_mca中是否已存在。
            $mca = trim($formData['mca']);
            if($mca){
                $mcaArr = explode('/',$mca);
                if(!$mcaArr || count($mcaArr) != 3){
                    return json_encode(['status'=>false, 'info'=>'请注意“菜单MCA”要遵循以下格式填写：模块/控制器/方法！']);
                }else{
                    $row = RbacMca::find()->where(['module_code'=>$mcaArr[0],'controller_code'=>$mcaArr[1],'action_code'=>$mcaArr[2]])->asArray()->one();
                    if(!$row){
                        return json_encode(['status'=>false, 'info'=>'您填写的“菜单MCA”在数据表cs_rbac_mca中不存在！']);
                    }
                }
            }
            $model = SystemMenu::findOne(['id'=>$formData['id']]);
            if(!$model){
                die('未找到对应菜单！');
            }
            $model->load($formData,'');
            if($model->validate()){
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '菜单修改成功';
                    UserLog::log('菜单管理-修改菜单（id：' . $model->id . '）', 'sys');
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '菜单修改失败';
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
            $menu = SystemMenu::find()->where(['id'=>$id])->asArray()->one();
            if(!$menu){
                die('未找到对应菜单！');
            }
            return $this->render('edit',['menu'=>$menu]);
        }
    }
    
    /**
     * 删除菜单
     */
    public function actionRemove()
    {
        $id = intval(yii::$app->request->get('id')) or die('param(id) is required');
        $returnArr = [];
        if(SystemMenu::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '菜单删除成功！';
            UserLog::log("菜单管理-删除菜单（id：{$id}）", 'sys');
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '菜单删除失败！';
        }
        echo json_encode($returnArr);
    }



}