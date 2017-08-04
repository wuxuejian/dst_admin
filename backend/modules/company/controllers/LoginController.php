<?php
/**
 * Created by PhpStorm.
 * User: zenglc
 * Date: 15-5-8
 * Time: 下午3:23
 */
namespace frontend\modules\company\controllers;

use Yii;
use common\models\ProProduceUser;
use common\models\BusCompany;
use common\models\Department;
use frontend\modules\company\models\BusRole;
use frontend\modules\company\models\BusRoleUser;
use frontend\modules\company\models\BusRolePriv;
use frontend\modules\company\models\BusUserPriv;
use frontend\modules\system\models\SysOnline;


class LoginController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex(){
       $datas['urls'] = array(
            'login' => \Yii::$app->urlManager->createUrl('company/login/login'),
            'default' => \Yii::$app->urlManager->createUrl('company/default/'),
        );
        $session = Yii::$app->session;
        $session->open();
        $uid = $session['bUserId'];
        $bid = $session['bBossInfo']['id'];
        //$menus = $session['bPrivMenus'];
        //$buttons = $session['sPrivButtons'];
        $default =$datas['urls']['default'];
        if(isset($uid) && isset($bid)){
                return $this->redirect($default);
                exit;
        }

        return $this->renderPartial('index',['datas'=>$datas]);
    }
    public function actionLogin(){
        $data = Yii::$app->request->post();
        $response = ['success'=>false, 'message'=>''];
        $session = Yii::$app->session;
        $session->open();

        //验证所属商户是否正常
        $company = new BusCompany();
        $comm = $company->verifyCommunity($data);
        if(!$comm['id']){
            $response['success'] = false;
            $response['message'] = '商户被锁定或不存在！';
            echo json_encode($response);
            exit;
        }else{
            $session['bBossInfo'] = [
                'id' => $comm['id'],
                'type' => $comm['bus_type'],
                'sn' => $comm['bus_sn'] ,
                'name' => $comm['company_name'],
                'level' => $comm['bus_rank'],
            ];
        }
        $bossId = $session['bBossInfo']['id'];
        if(!$bossId){
            $response['success'] = false;
            $response['message'] = '商户被锁定或不存在！';
            echo json_encode($response);
            exit;
        }

       //获取混淆key
        $prouser = new ProProduceUser();
        $vKey = $prouser->verifyKey($data['user'],$bossId);
        if(!$vKey){
            $response['success'] = false;
            $response['message'] = '用户名或密码不正确！';
            echo json_encode($response);
            exit;
        }

        //密码和混淆码
        $pw = $data['pwd'].$vKey;
        //验证用户和密码
        $userInfo =$prouser->verifyUser($data,md5($pw),$bossId);

        if(empty($userInfo['worker_name'])){
            $response['success'] = false;
            $response['message'] = '用户或密码错误！';
            echo json_encode($response);
            exit;
        }


        if($userInfo['worker_name']){
            $query = new ProProduceUser();
            //更新最近一次登陆ip
            $query->setIp($userInfo['itemid']);
            //处理登陆成功后的信息
            $session->set('bUserId',$userInfo['itemid']);//账户id
            $session->set('bUserName',$userInfo['user_name']);//用户名称
            $session->set('bWorkName',$userInfo['worker_name']);//用户登陆名
            $session->set('bDeptId',$userInfo['part_id']);//用户所属部门id
            $session->set('bDeptId',$userInfo['part_id']);//用户所属部门id
            $session->set('bAdmin',$userInfo['admin']);//商户下的管理员

            $dept = new Department();
            $deptName =$dept->getUserDepartment($userInfo['part_id']);
            $session->set('bDeptName',$deptName);//用户所属部门名称；
            $session->set('bUserAvatar',$userInfo['small_avatar']); //小头像

            //记住用户名称和密码
           // if($data['remember']){
                $session->set('bHfbname',$data['bossname']);//商户名称
              //  $session->set('bHfuname',$data['username']);//用户名称
               // $session->set('bUserId',$userInfo['itemid']);//账户id
           // }
/****************************************** 权限处理开始 *******************************************/
            //获取用户角色
            $privi = new BusRoleUser();
            $usRole = $privi->getUserRole($userInfo);
            //通过角色获取目录权限
            $query = new BusRolePriv;
            $pris = $query->getRolePriv($usRole);
            //获取个人目录权限
            $upriv = new BusUserPriv;
            $usePpris = $upriv->getUserPriv($userInfo['itemid']);
            //合并角色和个人权限 目录
            if(is_array($pris['menus']) && !is_array($usePpris['menus'])){
                $men = $pris['menus'];
            }elseif(is_array($usePpris['menus']) && !is_array($pris['menus'])){
                $men = $usePpris['menus'];
            }elseif(is_array($pris['menus']) && is_array($usePpris['menus'])){
                $men = array_merge($pris['menus'],$usePpris['menus']);
            }
            if(!empty($men)){
                $menus = array_unique($men);
            }else{
                $menus=array();
            }

            //合并角色和个人权限 按钮
            if(is_array($pris['buttons']) && !is_array($usePpris['buttons'])){
                $butt = $pris['buttons'];
            }elseif(is_array($usePpris['buttons']) && !is_array($pris['buttons'])){
                $butt = $usePpris['buttons'];
            }elseif(is_array($pris['buttons']) && is_array($usePpris['buttons'])){
                $butt = array_merge_recursive($pris['buttons'],$usePpris['buttons']);
            }

            if(!empty($butt)){
                $button = array_merge_recursive($butt);
            }else{
                $button = array();
            }

          if(!empty($button)){
              foreach($button as $key => $arr){
                  $buttons[$key] =array_unique($arr);
              }
          }else{
              $buttons = array();
          }

/****************************************** 权限处理结束 *******************************************/

            $session->set('bPrivMenus',$menus);
            $session->set('sPrivButtons',$buttons);//按钮权限前后台使用同一个公共文件则使用同一个session名称
            $session['bRoleId'] =$usRole;//用户角色id数组
            $busRole = new BusRole();
            $roleName = $busRole->getRoleName($usRole);
            $session['bRoleName'] =$roleName;//用户角色名称['角色id'=>角色名称]
            $response['success'] = true;
            echo json_encode($response);
        }else{
            $response['success'] = false;
            $response['message'] = '用户名或密码错误！';
            echo json_encode($response);
        }
    }

    /**
     * 退出返回登陆页面
     */
    public function actionLogout(){
        $session = Yii::$app->session;
        $session->open();
        $onLine = new SysOnline();
        $onLine->deline($session['bUserId']);
        //$session->destroy();
        $session->remove('bUserId');
        $session->remove('bPrivMenus');
        $session->remove('sPrivButtons');
        $urls = \Yii::$app->urlManager->createUrl('company/login/index');
        return $this->redirect($urls);
    }
}