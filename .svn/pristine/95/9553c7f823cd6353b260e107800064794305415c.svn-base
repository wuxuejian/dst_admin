<?php
/**
 * 基类控制器用于登录验证等必要操作
 */
namespace backend\modules\interfaces\controllers;
use backend\models\VipAppLogin;
use backend\models\ChargeFrontmachine;
use backend\classes\FrontMachineDbConnection;
use yii;
use yii\web\Controller;
class BaseController extends Controller{
    public $layout = false;
    public $enableCsrfValidation = false;
    public function init(){
        parent::init();
        //验证app用户是否已经登录
        if(!isset($_REQUEST['mobile']) || !isset($_REQUEST['_loginkey'])){
            die(json_encode(['error'=>1,'msg'=>'参数错误！']));
        }
        if(!VipAppLogin::checkLogin('mobile',$_REQUEST['mobile'],$_REQUEST['_loginkey'])){
            die(json_encode(['error'=>1,'msg'=>'身份认证已过期，请重新登录！']));
        }
        return true;
    }

    /**
     * 前置机链接
     */
    protected function connectFrontMachineDbByFmId($fmId=0,$isFmIdFromCharger=false)
    {
        $fm = ChargeFrontmachine::find()
            ->select(['db_host' => 'addr','db_port','db_username','db_password','db_name'])
            ->where(['id' => $fmId, 'is_del' => 0])
            ->asArray()->one();
        if ($fm) {
            $dbHost = $fm['db_host'];
            $dbPort = $fm['db_port'];
            $dbUsername = $fm['db_username'];
            $dbPassword = $fm['db_password'];
            $dbName = $fm['db_name'];
            if ($dbHost && $dbPort && $dbUsername && $dbPassword && $dbName) {
                $fmConnection = (new FrontMachineDbConnection())->connectFrontMachineDb($dbHost, $dbUsername, $dbPassword,$dbPort,$dbName);
                return $fmConnection;
            } else {
                return '该前置机的数据库连接信息填写不完整！';
            }
        } else {
            if ($isFmIdFromCharger) {
                return '根据该电桩查找不到对应的前置机，可能该电桩基本信息的“所属前置机”字段填写有误！';
            }
        }
    }
}