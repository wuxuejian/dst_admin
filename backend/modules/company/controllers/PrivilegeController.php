<?php
/**
 * Created by PhpStorm.
 * User: lingcheng
 * Date: 2015/5/18
 * Time: 16:07
 */
namespace frontend\modules\company\controllers;

use yii;
use common\models\Department;
use common\models\ProProduceUser;
use common\models\SysMenu;
use frontend\modules\company\models\BusRole;
use frontend\modules\company\models\BusRolePriv;
use frontend\modules\company\models\BusRoleUser;


class PrivilegeController extends \frontend\controllers\BaseController {
    public function actionIndex(){
        $datas['urls'] = array(
            'saverole' => \Yii::$app->urlManager->createUrl(['company/privilege/save-role']),
            'deleterole' => \Yii::$app->urlManager->createUrl(['company/privilege/delete-role']),
            'setpri' => \Yii::$app->urlManager->createUrl(['company/privilege/set-pri']),
            'savepri' => \Yii::$app->urlManager->createUrl(['company/privilege/save-pri']),
            'member' => \Yii::$app->urlManager->createUrl(['company/privilege/member']),
            'saveMember' => \Yii::$app->urlManager->createUrl(['company/privilege/save-member']),
            'delMember' => \Yii::$app->urlManager->createUrl(['company/privilege/del-member']),
        );

        $buttons = [
            'add'=>array('text'=>'添加','class'=>'icon-add', 'click'=>'Pri.addPriv()'),
            'edit'=>array('text'=>'修改','class'=>'icon-edit', 'click'=>'Pri.editPriv()'),
            'del'=>array('text'=>'删除','class'=>'icon-delete', 'click'=>'Pri.deletedPri()'),
            'setpriv'=>array('text'=>'权限设置','class'=>'icon-user-key', 'click'=>'Pri.setPriv()'),
            'member'=>array('text'=>'成员管理','class'=>'icon-user', 'click'=>'Pri.member()'),

        ];

        $datas['buttons'] = $this->validateUserButtons(__METHOD__, $buttons, $_SESSION['bUserId']);  // 权限过滤
        return $this->renderPartial('index',['datas'=>$datas]);
    }

    /**
     * 所有有效角色的列表
     */
   public function actionList(){
       $param = Yii::$app->request->post();
       $session = Yii::$app->session;
       $session->open();
       $bossId = $session['bBossInfo']['id'];

       $page = ($param['page'] ? $param['page'] : 1);
       $rows = ($param['rows'] ? $param['rows'] : 20);
       $sort='';
       if($param['sort'] && $param['order']){
            $sort=$param['sort'].' '.$param['order'];
       }else{
           $sort = "id desc";
       }
//print_r($sort);


       $query = $modules = BusRole::find()->with('roles')
           ->where("deleted=0 and boss_id=:boss_id",[':boss_id'=>$bossId])
           ->andFilterWhere(['like', 'name', $param['name']]);
       $count = $query->count();
       $roles = $query->asArray()
           ->offset(($page-1)*$rows)->limit($rows)
           ->orderBy($sort)->all();
       $user = new ProProduceUser();
       foreach($roles as $r => $v){
           foreach($v['roles'] as $key => $value){
                $useName = $user->getUserName($value['role_user']);;
                $useNames .=  $useName['user_name'].'、';
           }
           $roles[$r]['users']=trim($useNames,'、');
           $useNames='';
       }

       $datas = ['total' => $count, 'rows' => &$roles];

       echo json_encode($datas);
   }

    /**
     * 保存角色
     */
     public function actionSaveRole(){
         $response = ['success'=>false, 'message'=>''];
         $session = Yii::$app->session;
         $session->open();
         $bossId = $session['bBossInfo']['id'];

         $data = Yii::$app->request->post();
         // 判断有无重复
         $count = BusRole::find()
             ->where("id!=:id AND deleted=0 AND boss_id=:boss_id AND (code=:code OR name=:name)",
                 [':id'=>$data['id'], ':boss_id'=>$bossId, ':code'=>$data['code'], ':name'=>$data['name']])
             ->count();

         if ($count > 0){
             $response['message'] = "编码【{$data['code']}】或名称【{$data['name']}】 已经存在";
             echo json_encode($response);
             exit;
         }

         $role = new BusRole();
         if (!$data['id'] || $data['id'] < 1){
             $role = new BusRole();
             $role->setAttributes($data);
             $role->boss_id=$bossId;
             if ($role->save(false)){
                 $response['success'] = true;
                 $response['message'] = '保存成功';
             }else{
                 $response['message'] = '保存失败';
             }
         }else{
             $role = BusRole::find()
                 ->where('id=:id', [':id'=>$data['id']])
                 ->one();
                 $role->setAttributes($data);
                 $role->boss_id=$bossId;
                 if ($role->save(true)){
                     $response['success'] = true;
                     $response['message'] = '保存成功';
                 }else{
                     $response['message'] = '保存失败';
                 }

         }
         echo json_encode($response);
         exit;
    }

    /**
     * 删除角色
     *
     */
    public function actionDeleteRole(){
        $data = Yii::$app->request->post();
        $response = ['success'=>false, 'message'=>''];
        $role = BusRole::find()
            ->where('id=:id', [':id'=>$data['id']])
            ->one();
        $role->deleted=1;
        if ($role->save()){
            $response['success'] = true;
            $response['message'] = '保存成功';
        }else{
            $response['message'] = '保存失败';
        }
        echo json_encode($response);
    }


    /*
     * 以下为权限设置
     */

    /**
     * 列出所有的权限有则默认选上
     * @return string
     */
    public function actionSetPri(){
       /* //所有主目录
        $menuAll =SysMenu::find()
            ->select('id, parent, mark, text, buttons')
            ->indexBy('mark')
            ->asArray()
            ->where("deleted=0 AND installed=1 AND parent ='B' ")
            ->orderBy('parent, sort, mark')
            ->all();
        $menu_all =array();
        foreach($menuAll as $m){
             $key = $m['mark'];
            $menu_all[$key]['id']= $m['id'];
            $menu_all[$key]['text']= $m['text'];

        }

         //获取该角色已有权限
        $role_id = Yii::$app->request->get('role_id');
        $bRole = new BusRolePriv();
        $privs  = $bRole ->getRolePriv($role_id);
        $datas['menulist'] = $menu_all;
         $menus = SysMenu::find()
            ->select('id, parent, mark, text, buttons')
            ->indexBy('mark')
            ->asArray()
            ->where("deleted=0 AND installed=1 and parent<>''")
            ->orderBy('parent, sort, mark')
            ->all();

        foreach ($menus as $mk => $menu){
            $mark = $menu['mark'];
            $parent = $menu['parent'];
            if(is_array($privs['menus'])){
             $menu['checked'] = (in_array($mark, $privs['menus']) ? 'checked' : '');   // 是否初始化勾选
            }else{
                $menu['checked']='';
            }
            if (!empty($menu['buttons'])){
                $id = $menu['id'];
                $btns = explode(';', $menu['buttons']);
                $menu['buttons'] = '';
                $k=0;
                foreach ($btns as $str){
                    $btn = explode(':', $str);
                    if(is_array($privs['buttons'][$mark])){
                        if (in_array($btn[0], $privs['buttons'][$mark])){
                            $check = 'checked';
                        }else{
                            $check = '';
                        }
                    }
                    if($btn[1]){
                    $menu['buttons'] .= "<input type='checkbox' name='{$mark}' id='{$parent}{$mark}{$k}' value='{$btn[0]}' {$check}>{$btn[1]}";
                    }
                    $k++;
                }
            }
            $datas['menu'][$parent][] = $menu;
        }

        return $this->renderPartial('setpriv',['datas'=>$datas]);*/


        //所有主目录
        $menuAll =SysMenu::find()
            ->select('id, parent, mark, text, buttons')
            ->indexBy('mark')
            ->asArray()
            ->where("deleted=0 AND installed=1 AND parent ='B' ")
            ->orderBy('parent, sort, mark')
            ->all();
        $menu_all =array();
        foreach($menuAll as $m){
            $key = $m['mark'];
            $menu_all[$key]['id']= $m['id'];
            $menu_all[$key]['text']= $m['text'];

        }

       //获取该角色已有权限

        $role_id = Yii::$app->request->get('role_id');
        $bRole = new BusRolePriv();
        $privs  = $bRole ->getRolePriv($role_id);
        $datas['menulist'] = $menu_all;
        $model = new SysMenu();
        $menus = $model->menus('B');//查询出所有目录
        $priv = new SysMenu();
        $datas['menu'] = $priv->setCheckButtons($menus,$privs);//所有按钮生成并默认选择
        return $this->renderPartial('setpriv',['datas'=>$datas]);


   }

    /**
     * 保存设置的角色的权限
     */
    public function actionSavePri(){
        $response = ['success'=>false, 'message'=>''];
        $data = Yii::$app->request->post();
        $model = new BusRolePriv();
        $succ = $model->savePriv($data);
        if ($succ){
            //删除按钮缓存文件 zenglc 2015-08-28
            $runtime =  Yii::getAlias('@app/runtime');
            $buttonFiel = $runtime.'/sysmenu_route_mark.php';
            if(is_file($buttonFiel)){
                unlink($buttonFiel);
            }
            $response['success'] = true;
            $response['message'] = '保存成功';
        }else{
            $response['success'] = false;
            $response['message'] = '保存失败';
        }
        echo json_encode($response);
        exit;

    }

    /**
     * 查看角色成员
     * @return string
     */
    public function actionMember(){
        $roleId = Yii::$app->request->get('role_id');

        return $this->renderPartial('member',['roleId'=>$roleId]);
    }

    /**
     * 获取到该角色下的人员列表
     */
    public function actionRoleMember(){
        $roleId = Yii::$app->request->get('role_id');
        $param = Yii::$app->request->post();
        $page = ($param['page'] ? $param['page'] : 1);
        $rows = ($param['rows'] ? $param['rows'] : 20);

        $allRoleUser = BusRoleUser::find()
            ->select('role_user')
            ->where('role_id=:role_id',[':role_id'=>$roleId])
            ->asArray()
            ->all();
        if(!empty($allRoleUser)){
            foreach($allRoleUser as $uid){
                $ustr[]=$uid['role_user'];
            }
            if(is_array($ustr)){
                $user =  ProProduceUser::find()
                    ->where(['in', 'itemid', $ustr])
                    ->andWhere('status_lock=:status_lock and deleted=:deleted',[':status_lock'=>0,':deleted'=>0]);
                $count = $user->count();
                $roles = $user->asArray()
                    ->orderBy('itemid')
                    ->offset(($page-1)*$rows)->limit($rows)
                    ->all();

                $datas = ['total' => $count, 'rows' => &$roles];
            }
        }else{
            $datas='';
        }


        echo json_encode($datas);
    }
    /*
     * 保存该角色下的人员
     */
    public function actionSaveMember()
    {
        $response = ['success' => false, 'message' => ''];
        $param = Yii::$app->request->post();
        $roleId = $param['roleId'];
        $query = new BusRoleUser();
        if (count($param['userId'])>0) {
            foreach ($param['userId'] as $uid) {
                $users = $query->find()
                    ->where('role_user=:role_user and role_id=:role_id', [':role_user' => $uid, ':role_id' => $roleId])
                    ->asArray()
                    ->one();
                if (!$users) {
                    $ruser = new BusRoleUser();
                    $ruser->role_id = $roleId;
                    $ruser->role_user = $uid;
                    $ruser->save();
                }
            }
     }
        $response['success'] = true;
        $response['message'] = '保存成功';
        echo json_encode( $response);
    }

    /**
     * 删除角色下的成员
     */
     public function actionDelMember(){
         $param = Yii::$app->request->post();
         $response = ['success' => false, 'message' => ''];
         $query = BusRoleUser::deleteAll("role_user=".$param['id']. " and role_id = ".$param['roleId']);
         if ($query){
             $response['success'] = true;
             $response['message'] = '操作成功！';
         }else{
             $response['message'] = '操作失败';
         }
         echo json_encode($response);
     }
    public function actionTest()
    {


        //所有主目录
        $menuAll =SysMenu::find()
            ->select('id, parent, mark, text, buttons')
            ->indexBy('mark')
            ->asArray()
            ->where("deleted=0 AND installed=1 AND parent ='B' ")
            ->orderBy('parent, sort, mark')
            ->all();
        $menu_all =array();
        foreach($menuAll as $m){
            $key = $m['mark'];
            $menu_all[$key]['id']= $m['id'];
            $menu_all[$key]['text']= $m['text'];

        }
        /*
         * 获取该角色已有权限
         */
        $role_id = Yii::$app->request->get('role_id');
        $bRole = new BusRolePriv();
        $privs  = $bRole ->getRolePriv($role_id);
        $datas['menulist'] = $menu_all;
        $model = new SysMenu();
        $menus = $model->menus('B');//查询出所有目录
        $priv = new SysMenu();
        $datas['menu'] = $priv->setCheckButtons($menus,$privs);//所有按钮生成并默认选择
        return $this->renderPartial('show',['datas'=>$datas]);
    }
}