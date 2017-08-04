<?php

namespace frontend\modules\company\controllers;

use frontend\controllers\BaseController;
use common\models\ProProduceUser;
use yii;
use yii\data\Pagination;
use yii\helpers\Json;
use common\models\Department;
use frontend\modules\company\models\BusRoleUser;
use frontend\modules\company\models\BusRole;
use frontend\modules\system\models\SysOnline;


class UserController extends BaseController {
    public $response = ['success'=>false, 'message'=>''];

    public function actionIndex() {

        $datas['urls'] = array(
            'adds' => \Yii::$app->urlManager->createUrl('company/user/adds'),
            'add' => \Yii::$app->urlManager->createUrl('company/user/add'),
            'deleted' => \Yii::$app->urlManager->createUrl('company/user/delete'),
            'open' => \Yii::$app->urlManager->createUrl('company/user/open'),
            'save' => \Yii::$app->urlManager->createUrl('company/user/save'),
            'showr' => \Yii::$app->urlManager->createUrl('company/user/role-user'),
            'pri' => \Yii::$app->urlManager->createUrl('company/user/save-pri'),
            'lock' => \Yii::$app->urlManager->createUrl('company/user/lock-user'),
            'setpw' => \Yii::$app->urlManager->createUrl('company/user/save-pws'),
            'dept' => \Yii::$app->urlManager->createUrl('company/user/depart'),
        );
        $buttons = [
            'add'=>array('text'=>'添加','class'=>'icon-add', 'click'=>'User.add()'),
            'edit'=>array('text'=>'修改','class'=>'icon-edit', 'click'=>'User.edit()'),
            'del'=>array('text'=>'删除','class'=>'icon-delete', 'click'=>'User.del()'),
            'setrole'=>array('text'=>'角色设置','class'=>'icon-user-key', 'click'=>'User.setRole()'),
            'lock'=>array('text'=>'锁定/解锁','class'=>'icon-user-key', 'click'=>'User.setLock()'),
            'passw'=>array('text'=>'修改密码','class'=>'icon-key', 'click'=>'User.setpw()'),


        ];
        $datas['buttons'] = $this->validateUserButtons(__METHOD__, $buttons, $_SESSION['bUserId']);  // 权限过滤

        return $this->renderPartial('index',['datas'=>$datas]);


    }

    /**
     * 人员列表
     */
        public function actionList() {

        $search = Yii::$app->request->get();
        $session = Yii::$app->session;
        $session->open();
           $onLine = new  SysOnline();
           $line = $onLine->getOnline();
       $bossid = $session['bBossInfo']['id'];
        $page = ($search['page'] ? $search['page'] : 1);
        $rows = ($search['rows'] ? $search['rows'] : 20);
            $sort ='';
            $zd = $search['sort'];
            $px = $search['order'];
            if($zd){
                $sort = $zd." ". $px.",";
            }
        $query = ProProduceUser::find()->with('companys')->with('depart')
            ->where("deleted=0 and boss_id = $bossid");
        if(!empty($search['worker_name'])){
            $query->andFilterWhere(['worker_name'=>$search['worker_name']]);
        }
        if(!empty($search['user_name'])){
           $query->andFilterWhere(['user_name'=>$search['user_name']]);
        }
        if(!empty($search['part_id'])){
            $query->andFilterWhere(['part_id'=>$search['part_id']]);
        }
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->defaultPageSize = $rows;

        $models = $query->offset($pages->offset)->limit($pages->limit)->orderBy($sort." itemid DESC")
            ->asArray()->all();
        foreach($models as $key=>$company)
        {
            $companys = $company['companys'];
            $models[$key]['boss_id'] = $companys['company_name'];

            $departs = $company['depart'];
            $models[$key]['part_id'] = $departs['department'];
            $lines = $line[$models[$key]['itemid']] ? $line[$models[$key]['itemid']]:0;
            $models[$key]['on_line'] = $lines;
        }

            echo json_encode(['total' =>$countQuery->count(), 'rows' =>$models]);
    }
    public function actionAdds()
    {
        return $this->renderPartial('edit');
    }

    /**
     * 添加用户
     */
    public function actionAdd()
    {
        $data = Yii::$app->request->post();
        $boss = Yii::$app->session->get('bBossInfo');
        $bossId = $boss['id'];
        $model = new ProProduceUser();
        //同一个商户下面不允许名字重复

            $worker_name = $data['worker_name'];
            $user_name = $data['user_name'];
            $count = ProProduceUser::find()->where(['boss_id'=>$bossId,'deleted'=>'0'])->andWhere(['or','worker_name="'.$worker_name.'"','user_name="'.$user_name.'"'])->count();
            if ($count > 0) {
                $response = ['success' =>false,'message' => '用户名已经存在!'];
                echo json_encode($response);
                exit;
            } else {

                $auto_key =$model->getAuthKey();
                $model->worker_name = $worker_name;
                $model->auth_key = $auto_key;
                $model->password_hash =md5($data['password'].$auto_key);
                $model->user_name = $data['user_name'];
                $model->letter = $data['letter'];
                $model->boss_id = $bossId;
                $model->part_id = $data['form_fronted_company_user_part_id'];
                $model->mobile = $data['mobile'];
                $model->email = $data['email'];
                $model->telephone = $data['telephone'];
                $model->qq = $data['qq'];
                $model->principal = $data['principal'];
                $model->sex = $data['sex'];
                if($model->save()){
                    $response = ['success' =>true, 'message' => '商户下属用户添加成功'];
                }else{
                    $response = ['success' =>false,'message' => '商户下属用户添加失败'];
                }
                echo json_encode($response);
            }

    }

/*
 * @name 编辑用户信息
 */
    public function actionOpen()
    {
        $id = Yii::$app->request->get('id');
        $model = ProProduceUser::findOne($id);
        return $this->renderPartial('edit', ['model' => $model]);
    }
    /**
     * 保存修改
     */
    public function actionSave()
    {
        $data = Yii::$app->request->post();
        $boss = Yii::$app->session->get('bBossInfo');
        $bossId = $boss['id'];
        $worker_name = $data['worker_name'];
        $user_name = $data['user_name'];
        $count = ProProduceUser::find()
            ->where(['boss_id'=>$bossId])
            ->andWhere(['or','worker_name="'.$worker_name.'"','user_name="'.$user_name.'"'])
            ->andWhere('itemid !='.$data['itemid'])
            ->count();
        if ($count > 0) {
            $response = ['success' =>false, 'message' => '用户已经存在！'];
            echo json_encode($response);
            exit;
        }
        $model = ProProduceUser::findOne($data['itemid']);
        $model->user_name = $data['user_name'];
        $model->boss_id = $bossId;
        $model->part_id = $data['form_fronted_company_user_part_id'];
        $model->mobile = $data['mobile'];
        $model->email = $data['email'];
        $model->letter = $data['letter'];
        $model->telephone = $data['telephone'];
        $model->qq = $data['qq'];
        $model->principal = $data['principal'];
        $model->sex = $data['sex'];
        if ($model->save()) {
            $response = ['success' =>true, 'message' => '商户下属用户修改成功！'];
        } else {
            $response = ['success' =>false, 'message' => '商户下属用户修改失败！'];
        }
        echo json_encode($response);
    }
    /**
     * @name 删除用户
     * @return json
     */
    public function actionDelete(){
        $id = Yii::$app->request->post('id');
        $response = ['success'=>false, 'message'=>''];
        $model = ProProduceUser::findOne($id);
        $model->deleted= 1;
        if ($model->save()) {
            $response =['success'=>true, 'message'=>'删除成功！'];
        } else {
            $response = ['success'=>false, 'message'=>'删除失败！'];
        }
        echo json_encode($response);
    }


    /**
     * @name 返回商户下的部门
     * @return json
     */
    public function actionDepart(){
        $dept = array();
        $session = Yii::$app->session;
        $session->open();
        $bossid=$session['bBossInfo']['id'];
        $model = new Department;
          $query =  $model->find()
              ->select('id,department')
              ->asArray()
              ->where('deleted=0 and boss_id=:boss_id',[':boss_id'=>$bossid])
              ->orderby('sort asc')
              ->all();
      echo json_encode($query);
    }
    /**
     * @name 获取所有角色和该用户所属角色然后显示
     * @return json
     */
    public function actionRoleUser(){
        $uid = Yii::$app->request->get('uid');
       //查询出本商户下所有角色
        $session = Yii::$app->session;
        $session->open();
        $bossid=$session['bBossInfo']['id'];

        $allRole = BusRole::find()
            ->where('deleted=0 and boss_id = :boss_id',[':boss_id'=>$bossid])
            ->asArray()
            ->all();

        //查询此客户下的所有角色
        $query = BusRoleUser::find()
            ->where('role_user=:role_user',[':role_user'=>$uid])
            ->asArray()
            ->all();

        foreach($query as $rs){
            $urle[]= $rs['role_id'];
            foreach($urle as $r){
                   $usRole[] =$r;
            }
        }
        if(is_array($usRole)){
            $usRoles = array_unique($usRole);
        }
        foreach($allRole as $ck){

                if(in_array($ck['id'],$usRoles)){
                   $check="checked";
                   }else{
                    $check=" ";
                  }
            $ck['check']="<input type='checkbox' name='roleId[]' id='".$ck['id']."' ".$check." value='".$ck['id']."'>";
            $useRole[] = $ck;
        }

        echo json_encode($useRole);
    }

    /**
     * @name 保存用户的角色
     * @return json
     */
    public function actionSavePri(){
        $response = ['success'=>false, 'message'=>''];
        $data = Yii::$app->request->post();
        $uid = $data['uid'];
        $customer = BusRoleUser::deleteAll('role_user='.$uid);
        $sucess = 1;
        if(count($data['roleId'])>0){
          foreach($data['roleId'] as $role){
                  $priv = new BusRoleUser();
                  $priv->role_user=$uid;
                  $priv->role_id=$role;
                  $sucess =  $priv->save();
                  $priv='';
          }
        }
        if ($sucess){
            $response = ['success'=>true, 'message'=>'保存成功！'];
        }else{
            $response = ['success'=>false, 'message'=>'保存失败！'];
        }
        echo json_encode($response);
        exit;
    }
    /**
     * @name 锁定/解锁用户
     * @return json
     */
    public function actionLockUser(){
        $response = ['success'=>false, 'message'=>''];
       $itemid = Yii::$app->request->get('itemid');
       $module = ProProduceUser::find()
           ->where(['itemid'=>$itemid])
           ->asArray()
           ->one();
        if($module['status_lock']==0){
            $newStatus=1;
        }else{
            $newStatus=0;
        }
        $user = ProProduceUser::findOne($itemid);
        $user->status_lock = $newStatus;
            if($newStatus==1){
                $message = '锁定！';
            }else{
                $message = '解锁！';
            }
              if ($user->save()){
                  $response = ['success'=>true, 'message'=>$message];
              }else{
                  $response = ['success'=>false, 'message'=>'操作失败！'];
              }
        echo json_encode($response);
    }

    /**
     * @name 管理员修改员工密码保存
     * @return json
     */
    public function actionSavePws(){
        $response = ['success'=>false, 'message'=>''];
        $data=Yii::$app->request->post();
        $user = new ProProduceUser();
        $autoKey=$user->getAuthKey();
        if($data['p1'] == $data['p2']){
            $query = ProProduceUser::findOne($data['itemid']);
            $mima = $data['p1'].$autoKey;
            $newPass = md5($mima);
            $query->password_hash = $newPass;
            $query->auth_key = $autoKey;
            if($query->save(true)){
                $response = ['success'=>true, 'message'=>'保存成功！'];
            }else{
                $response = ['success'=>false, 'message'=>'保存失败！'];
            }
        }else{
            $response = ['success'=>false, 'message'=>'两次输入的密码不一样！'];
        }
        echo json_encode($response);
    }
}
