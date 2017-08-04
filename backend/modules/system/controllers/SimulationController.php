<?php
/**
 * 超级用户虚拟其它用户登陆
 * time    2015/10/24 09:54
 * @author wangmin
 */
namespace backend\modules\system\controllers;
use backend\controllers\BaseController;
use backend\models\Admin;
use yii;
class SimulationController extends BaseController
{
    /*
     * 设置需要模拟的用户
     * system/simulation/set-user
     */
    public function actionSetUser()
    {
        if(empty($_GET['username'])){
            die('username can not empty!');
        }
        $admin = Admin::findOne(['username'=>yii::$app->request->get('username')]);
        if(!$admin){
            die('user not exists!');
        }
        if($admin->getOldAttribute('super') == 1){
            die('the count you want to set is a developers!');
        }
        $_SESSION['backend']['simulation'] = $admin->getOldAttribute('id');//模拟账号id
        $_SESSION['backend']['simulationInfo'] = $admin->getOldAttributes();//模拟账号基本信息
        $this->redirect(['/index/index']);
    }

    /**
     * 取消模拟登陆
     */
    public function actionLogout()
    {
        unset($_SESSION['backend']['simulation']);
        unset($_SESSION['backend']['simulationInfo']);
        unset($_SESSION['backend']['accessActionIds']);
        unset($_SESSION['backend']['accessActionRouter']);
        $this->redirect(['/index/index']);
    }
}