<?php
namespace backend\controllers;
use backend\models\Admin;
use backend\models\AdminRole;
use backend\models\RbacRoleMca;
use backend\models\RbacMca;
use backend\models\RbacActionBtn;
use yii;
use yii\web\Controller;
use backend\classes\FrontMachineDbConnection;
use backend\models\ChargeFrontmachine;
// 中国省市区县
use backend\models\ChinaProvince; 
use backend\models\ChinaCity;
use backend\models\ChinaArea;

class BaseController extends Controller
{
    //superman标志属性
    public static $isSuperman = false;
    //初始化方法
    public function init()
    {
        parent::init();
        $this->layout = false;
        $this->enableCsrfValidation = false;
        //登陆认证
        $session = yii::$app->session;
        $session->open();
        if(!isset($_SESSION['backend']) || !isset($_SESSION['backend']['isLogin']) || $_SESSION['backend']['isLogin'] != 1){
            $jsRedirectUrl = yii::$app->urlManager->createUrl(['/site/login']);
            echo '<script>window.location.href="'.$jsRedirectUrl.'"</script>';
            die;
        }
        //登陆认证结束
        //检测当前登陆账号是否是superman
        if(isset($_SESSION['backend']['adminInfo']['super']) && $_SESSION['backend']['adminInfo']['super'] == 1){
            self::$isSuperman = true;
        }
        //更新账号动作时间
        $adminId = $_SESSION['backend']['adminInfo']['id'];
        Admin::updateAll(['active_time'=>time()],['id'=>$adminId]);

        //获取当前用户有权访问的action存入session(已过滤superman)
        self::flashUserAction();
        //权限控制开始
        if(!self::rbacAccess()){
            echo '抱歉，您未获得本信息的查看权限！';
            die;
        }
        //权限控制结束
        return true;
    }

    /**
     * 为当前用户有权进入的action作session缓存
     * @return boolean
     */
    protected static function flashUserAction()
    {
        
        if(self::$isSuperman && !isset($_SESSION['backend']['simulation'])){
            //超级用户并且没有虚拟其它用户登陆
            return true;
        }else{
            //非超级用户或超级用户虚拟其它用户登陆
            if(isset($_SESSION['backend']['accessActionIds']) && isset($_SESSION['backend']['accessActionRouter'])){
                //已经缓存过
                return true;
            }
            if(self::$isSuperman){
                //虚拟用户
                $adminId = $_SESSION['backend']['simulation'];
            }else{
                //真实登陆用户
                $adminId = $_SESSION['backend']['adminInfo']['id']; 
            }
            //查询用户角色
            $roleIds = AdminRole::find()
                       ->select('role_id')
                       ->where(['admin_id'=>$adminId])->asArray()->all();
            if(!$roleIds){
                //该账号无有效角色
                return false;   
            }
            $roleIds = array_column($roleIds,'role_id');
            //查询角色有权进入的方法
            $accessMcaIds = RbacRoleMca::find()
                     ->select(['distinct `mca_id`'])
                     ->where(['in','role_id',$roleIds])
                     ->asArray()->all();
            if(!$accessMcaIds){
                //该账号无可访问的mca
                return false;
            }
            //查询当前登陆账号所有可以访问的方法信息
            $actionInfo = RbacMca::find()
                          ->select(['id','module_code','controller_code','action_code'])
                          ->where(['type'=>2])
                          ->andWhere(['in','id',array_column($accessMcaIds,'mca_id')])
                          ->asArray()->all();
            if(!$actionInfo){
                //该账号无可访问的方法
                return false;
            }
            $accessActionRouter = [];
            foreach($actionInfo as $val){
                $accessActionRouter[] = $val['module_code'].'/'.$val['controller_code'].'/'.$val['action_code'];
            }
            $_SESSION['backend']['accessActionIds'] = array_column($actionInfo,'id');
            $_SESSION['backend']['accessActionRouter'] = $accessActionRouter;
            return true;
        }
        
    }

    /**
     * rbac访问控制
     */
    public static function rbacAccess()
    {
        //超级用户并且没有模拟其他用户登陆
        if(self::$isSuperman && !isset($_SESSION['backend']['simulation'])){
            return true;
        }
        //超级用户模拟其他用户登陆或非超级用户
        if(self::$isSuperman){
            //模拟其它用户时允需超级用户访问的route
            $allowAccessRoute = [
                'system/simulation/logout'
            ];
            if(in_array(yii::$app->requestedRoute,$allowAccessRoute)){
                return true;
            }
        }
        $router = explode('/',Yii::$app->requestedRoute);
        if(count($router) > 2){
            $mcaId = RbacMca::find()
                     ->select(['id'])
                     ->where([
                        'module_code'=>$router[0],
                        'controller_code'=>$router[1],
                        'action_code'=>$router[2]
                     ])
                     ->asArray()
                     ->one();
            if(!$mcaId){
                return true;
            }
            if(!in_array($mcaId['id'],$_SESSION['backend']['accessActionIds'])){
                return false;
            }
        }
        return true;
    }

    /**
     * 获取当前控制器的按钮
     */
    public static function getCurrentActionBtn()
    {
        $router = explode('/',Yii::$app->requestedRoute);
        $actionInfo = RbacMca::find()
                     ->select(['id'])
                     ->where([
                        'module_code'=>$router[0],
                        'controller_code'=>$router[1],
                        'action_code'=>$router[2]
                     ])
                     ->asArray()
                     ->one();
        if(!$actionInfo){
            return [];
        }
        //查询当前控制器的所有按钮
        $allBtn = RbacActionBtn::find()
                  ->select(['text','icon','on_click','target_mca_code','note'])
                  ->where(['action_id'=>$actionInfo['id'],'is_del'=>0])
                  ->orderBy('`list_order` desc,`id`')
                  ->asArray()
                  ->all();
        if(!$allBtn){
            //没查询到按钮
            return [];
        }
        //根据情况过滤按钮
        if(self::$isSuperman && !isset($_SESSION['backend']['simulation'])){
            //超级用户并且没有模拟其它用户登陆
            return $allBtn;
        }else{
            //普通用户或超级用户模拟其他用户登陆（过滤用户无权访问的btn）
            foreach ($allBtn as $key=>$value) {
                if(!in_array($value['target_mca_code'],$_SESSION['backend']['accessActionRouter'])){
                    unset($allBtn[$key]);
                }
            }
            return $allBtn;
        }
    }


    /**
     * 连接前置机数据库，若成功返回连接句柄，失败返回提示信息。（在‘电桩监控’模块大量使用到它）
     * @$fmId: 前置机id。
     * @$isFmIdFromCharger: （此参数不再使用）
     */
    public function connectFrontMachineDbByFmId($fmId=0,$isFmIdFromCharger=false)
    {
        $query = ChargeFrontmachine::find()->select(['db_host' => 'addr', 'db_username', 'db_password', 'db_port', 'db_name']);
        // 若指定有前置机ID则连接该前置机；若没有指定则默认连接第一个有效前置机。
        if (isset($fmId) && $fmId) {
            $fm = $query->where(['id' => $fmId, 'is_del' => 0])->asArray()->one();
        } else {
            $fm = $query->where(['is_del' => 0])->orderBy('id ASC')->asArray()->one();
        }
        if ($fm) {
            $dbHost = $fm['db_host'];
            $dbUsername = $fm['db_username'];
            $dbPassword = $fm['db_password'];
            $dbPort = $fm['db_port'];
            $dbName = $fm['db_name'];
            if ($dbHost && $dbUsername && $dbPassword && $dbPort && $dbName) {
                $fmConnection = (new FrontMachineDbConnection())->connectFrontMachineDb($dbHost, $dbUsername, $dbPassword, $dbPort, $dbName);
				return $fmConnection;
            } else {
                return '该前置机的数据库连接信息填写不完整！';
            }
        } else {
            return '找不到对应的前置机！';
        }
    }

	
	/**
     * 获取中国的省份
     */
    public function actionGetChinaProvince()
    {
		$data = ChinaProvince::find()
			->select(['id','provinceid','province'])
			->orderBy('provinceid ASC')
			->asArray()
			->all();
		echo json_encode($data);
	}
	
	/**
     * 获取中国的地级市
     */
    public function actionGetChinaCity()
    {
		$provinceId = intval(yii::$app->request->get('provinceId'));
		$data = ChinaCity::find()
			->select(['id','cityid','city'])
			->where(['fatherid'=>$provinceId])
			->orderBy('cityid ASC')
			->asArray()
			->all();
		echo json_encode($data);
	}
	
	/**
     * 获取中国的区县
     */
    public function actionGetChinaArea()
    {
		$cityId = intval(yii::$app->request->get('cityId'));
		$data = ChinaArea::find()
			->select(['id','areaid','area'])
			->where(['fatherid'=>$cityId])
			->orderBy('areaid ASC')
			->asArray()
			->all();
		echo json_encode($data);
	}
	
	

}