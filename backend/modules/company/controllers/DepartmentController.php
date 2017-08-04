<?php
namespace backend\modules\company\controllers;
use backend\controllers\BaseController;
use yii;
use yii\data\Pagination;
use backend\classes\SelectMade;
use backend\models\BusCompany;
use backend\models\Department;
use backend\models\EcsOrderInfo;
use backend\models\ProProduceUser;

class DepartmentController extends BaseController
{
    public function actionIndex(){
        $datas['urls'] = array(
            'save' => \Yii::$app->urlManager->createUrl('company/department/save'),
            'deleted' => \Yii::$app->urlManager->createUrl('company/department/cancel'),
        );


        $buttons = [
            'add'=>array('text'=>'添加','class'=>'icon-add', 'click'=>'Department.addDepartment()'),
            'edit'=>array('text'=>'编辑','class'=>'icon-edit','click'=>'Department.editDepartment()'),
            'del'=>array('text'=>'删除','class'=>"icon-delete", 'click'=>'Department.deleteDepartment()')
        ];

        $datas['buttons'] = $this->validateUserButtons(__METHOD__, $buttons, $_SESSION['bUserId']);  // 权限过滤

        return $this->renderPartial('index', $datas);
    }

    /**
     * @name 部门列表
     * @return json
     */
    public function actionList()
    {
        $session = Yii::$app->session;
        $session->open();
        $param = Yii::$app->request->post();

        $page = ($param['page'] ? $param['page'] : 1);
        $rows = ($param['rows'] ? $param['rows'] : 20);

        $sort ='';
        $zd = $param['sort'];
        $px = $param['order'];
        if($zd){
            $sort = $zd." ". $px.",";
        }

       $query = Department::find()
           ->with('companys')
           ->where(['boss_id' => $session['bBossInfo']['id'],'deleted'=>0])
           ->orderBy('sort desc')
                ->asArray();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->pageSizeLimit=false;
        $pages->defaultPageSize = $rows;
        $models = $query->offset(($page-1)*$rows)
            ->limit($pages->limit)
            ->OrderBy($sort." sort asc")
            ->all();
        foreach($models as $key=>$company)
        {
            $companys = $company['companys'];
            $models[$key]['boss_id'] = $companys['company_name'];
        }

        echo json_encode( ['total' =>$pages->totalCount, 'rows' =>$models]);

   }

    /**
     * @name 保存部门信息
     * @return json
     */
    public function actionSave(){
        $session = Yii::$app->session;
        $session->open();
        $data = Yii::$app->request->post();
        $response = ['success'=>false, 'message'=>''];
        if (!$data['id'] || $data['id'] < 1){
            $depart = new Department();
            $depart->setAttributes($data);
            $depart->boss_id=$session['bBossInfo']['id'];
            if ($depart->save()){
                    $response['success'] = true;
                    $response['message'] = '保存成功';
            }else{
                $response['message'] = '保存失败';
            }
        }else{
            $depart = Department::find()->where('id=:id', [':id'=>$data['id']])->one();
            $depart->setAttributes($data);
            if ($depart->save()){
                $response['success'] = true;
                $response['message'] = '保存成功';
            }else{
                $response['message'] = '保存失败';
            }

        }

        echo json_encode($response);
    }
    /**
     * @name 删除部门
     * @return json
     */
    public function actionCancel(){
        $data = Yii::$app->request->post();
        //先验证部门下是否有员工
        $user = new ProProduceUser;
        $users = $user->find()->where(['part_id'=>$data['id']])->count();
        if($users>0){
            $response['success'] = false;
            $response['message'] = '部门下有员工，不可删除该部门！';
            echo json_encode($response);
            exit;
        }
        $depart = Department::findOne($data['id']);
        $depart->deleted=1;
        if($depart->save()){
            $response['success'] = true;
            $response['message'] = '删除成功！';
        }else{
            $response['success'] = false;
            $response['message'] = '删除失败！';
        }
        echo json_encode($response);
    }
  
}

