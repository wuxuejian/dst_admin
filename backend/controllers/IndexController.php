<?php
namespace backend\controllers;
use backend\models\RbacMca;
use backend\models\SystemMenu;
use common\classes\Category;
use backend\classes\Approval;
use backend\models\CarBack;
use Yii;
class IndexController extends BaseController
{
    public $enableCsrfValidation  = false;
    public $maintain_steps = array('maintain-reg'=>5,'fault'=>6,'maintain-archive'=>8);  //车辆维修登记步骤权限
    public $repair_steps = array('assigned'=>1,'affirm'=>2,'field-reg'=>3,'serve-archive'=>array(5,4));  //车辆维修登记步骤权限
    public function actionIndex()
    {
    	$session = yii::$app->session;
    	$session->open();
        $this->layout = false;
        
        $login_pwd = $session['backend']['login_pwd'];
        $pwd_format_ok = preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[a-zA-Z0-9]{8,20}$/',$login_pwd);
        		
        return $this->render('index',[
            'pwd_format_ok'=>$pwd_format_ok
        ]);
    }

    /**
     * 系统欢迎页
     */
    public function actionWelcome()
    {
		return $this->render('welcome');
    }
    
    public function actionGetData()
    {
    	$sys_res = (new \yii\db\Query())->from('cs_system_code_update_log')->all();
    	
    	/****** 我的待办  ******/
    	//1、提车流程待待办数
    	$approval = new Approval();
    	$result = $approval->my_approvel();
    	$tc_todo = 0;
    	if(count($result) ==0){
    		if(self::$isSuperman){
    			$tc_todo = 0;
    		}else{
    			$roles = (new \yii\db\Query())->from('cs_admin_role')->where('admin_id=:admin_id',[':admin_id'=>$_SESSION['backend']['adminInfo']['id']])->all();
    			$role_id = [];
    			$admin_role = array();
    			foreach ($roles as $role)
    			{
    				$admin_role[] = $role['role_id'];
    			}
    		
    			$template_row = (new \yii\db\Query())->select('id')->from('oa_process_template')->where('by_business=:by_business',[':by_business'=>'process/car/index'])->one();
    			$roles_res = (new \yii\db\Query())->select('assign_role_id')->from('oa_process_steps')->where('template_id=:template_id',[':template_id'=>$template_row['id']])->all();
    			$roles = array();
    			foreach ($roles_res as $row){
    				$roles[] = $row['assign_role_id'];
    			}
    			$intersect = array_intersect($admin_role,$roles);
    			$tc_todo = !empty($intersect) ? 0: 'NOACCESS';
    		}
    		
    	}else{
    		
    		$query = (new \yii\db\Query())->from('oa_extract_report')->where('oa_extract_report.is_del=1');
    		$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
    		$query->andWhere("operating_company_id in ({$ocs})");
    		$query->andWhere(['id'=>$result]);
    		$tc_todo = $query->count();
    	}
    	//2、退车流程待办数
    	$db_states = $this->db_states();
    	$back_car_todo = count($db_states) ? 0:'NOACCESS';
    	$db_states = implode(",",$db_states);
    	$connection = \Yii::$app->db;
    	$isLimitedArr = CarBack::isLimitedToShowByAdminOperatingCompany();
    	if(is_array($isLimitedArr) && isset($isLimitedArr['status']) && $isLimitedArr['status']){
    		$data = $connection->createCommand(
    				"select count(*) cnt from cs_car_back where is_del=0 and state in ({$db_states}) and operating_company_id in (0,{$isLimitedArr['adminInfo_operatingCompanyId']})"
    				)->queryOne();
    	}else {
    		$data = $connection->createCommand(
    				"select count(*) cnt from cs_car_back where is_del=0 and state in ({$db_states})"
    				)->queryOne();
    	}
    	
    	$back_car_todo = $data['cnt'];
    	//3、车辆维修登记
    	$steps_todo = array();
    	foreach ($this->maintain_steps as $key=>$val){
    		if($this->isAccess($key,'process','repair')){
    			$steps_todo[] = $val;
    		}
    	}
    	$maintain_todo = count($steps_todo)? 0: 'NOACCESS';  	
    	if(count($steps_todo) >0){
    		$steps_todo_str = implode(',', $steps_todo);
    		$query = (new \yii\db\Query())->from('oa_car_maintain')->where("status in ({$steps_todo_str}) and car_id !=''");
    		$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
    		$query->andWhere("operating_company_id in ({$ocs})");
    		$maintain_todo = $query->count();
    	}
    	//4、售后服务登记
    	$steps_todo = array();
    	foreach ($this->repair_steps as $key=>$val){
    		if($this->isAccess($key,'process','repair')){
    			if(is_array($val)){
    				foreach ($val as $v){
    					$steps_todo[] = $v;
    				}
    			}else{
    				$steps_todo[] = $val;
    			}
    	
    		}
    	}
    	$repair_todo = count($steps_todo)? 0 : 'NOACCESS';
    	if(count($steps_todo) >0){
    		$steps_todo_str = implode(',', $steps_todo);
    		$query = (new \yii\db\Query())->from('oa_repair')->where("status in ({$steps_todo_str})");
    		$ocs = !empty($_SESSION['backend']['adminInfo']['operating_company_ids']) ? $_SESSION['backend']['adminInfo']['operating_company_ids']: $_SESSION['backend']['adminInfo']['operating_company_id'];
    		$query->andWhere("operating_company_id in ({$ocs})");
    		$repair_todo = $query->count();
    	}
    	
    	/****** 系统升级日志 ******/
    	$sys_log = (new \yii\db\Query())->select('product,update_type,update_date,module,note')->from('cs_system_code_update_log')->all();
    	$sys_data = array();
    	if($sys_log){
    		foreach ($sys_log as $val){
    			$content = '';
    			$content = $val['product'] == 1 ? '地上铁APP':'地上铁系统';
    			switch ($val['update_type']){
    				case 1:
    					$content .= '优化';
    					break;
    				case 2:
    					$content .= '修复';
    					break;
    				case 3:
    					$content .= '新增';
    					break;
    				case 4:
    					$content .= '删除';
    					break;
    				default:
    					break;
    			}
    			$content .= $val['module'].'-'.$val['note'];
    			$sys_data[] = array('content'=>$content,'update_date'=>$val['update_date']);
    		}
    	}
    	
    	/****** 公司新闻 ******/
    	$news = array();
    	$news = (new \yii\db\Query())->select('id,title,add_time')->from('ow_news')->where('is_del=0')->orderBy('add_time DESC')->limit(5)->all();
    	
    	$data = array(
    			'tc_todo'		=> $tc_todo,
    			'back_car_todo' => $back_car_todo,
    			'maintain_todo' => $maintain_todo,
    			'repair_todo'   => $repair_todo,
    			'sys_log'		=> $sys_data,
    			'news'			=> $news,
    	);
    	echo json_encode($data);
    	exit();
    }
    
   	function db_states(){
    	//对应待办状态(退车状态,1已登记,2沟通已确认,3领导已审批,4售后已验车,5车辆入库已确认,6押金已结算,7 黄总已审批,8合同已终止,9车辆已入库,20客户取消退车,21领导（退车申请被驳回）,22黄总驳回)
    	$db_states = array();	//待办状态
    	array_push($db_states, 0);
    	if($this->isAccess('add2','car','car-back')){
    		array_push($db_states, 1);
    		array_push($db_states, 21);
    	}
    	if($this->isAccess('add3','car','car-back')){
    		array_push($db_states, 2);
    	}
    	if($this->isAccess('add4','car','car-back')){
    		array_push($db_states, 3);
    	}
    	if($this->isAccess('add5','car','car-back')){
    		array_push($db_states, 4);
    	}
    	if($this->isAccess('add6','car','car-back')){
    		array_push($db_states, 22);
    		array_push($db_states, 5);
    	}
    	if($this->isAccess('add7','car','car-back')){
    		array_push($db_states, 6);
    	}
    	if($this->isAccess('add8','car','car-back')){
    		array_push($db_states, 7);
    	}
    	if($this->isAccess('add9','car','car-back')){
    		array_push($db_states, 8);
    	}
    	return $db_states;
    }
    
    
    function isAccess($index,$module_code,$controller_code){
    	if(self::$isSuperman){
    		return true;
    	}
    	$mcaId = RbacMca::find()
    	->select(['id'])
    	->where([
    			'module_code'=>$module_code,
    			'controller_code'=>$controller_code,
    			'action_code'=>$index
    			])
    			->asArray()
    			->one();
    	return in_array($mcaId['id'],$_SESSION['backend']['accessActionIds']);
    }
    /**
     * desc:获取系统主菜单
     */
    /*public function actionMenu()
    {
        //查询当前所有显示的菜单
        $menuItem = self::getMenuItem();
        $menu = [];
        foreach($menuItem['modules'] as $mVal){
            $_menu = [
                'text'=>$mVal['name'],
                'state'=>'closed',
                'children'=>[]
            ];
            foreach($menuItem['actions'] as $aVal){
                if($aVal['module_code'] == $mVal['module_code']){
                    //当前方法属于当前主循环的控制器
                    $controllerMCA = $aVal['module_code'].'_'.$aVal['controller_code'];
                    $url = $aVal['module_code'].'/'.$aVal['controller_code'].'/'.$aVal['action_code'];
                    if(isset($menuItem['controllers'][$controllerMCA])){
                        //该方法有对应的控制器
                        if(!isset($_menu['children'][$controllerMCA])){
                            //没有在菜单中创建了该控制器作为二级菜单先创建二级菜单
                            $_menu['children'][$controllerMCA] = [
                                'text'=>$menuItem['controllers'][$controllerMCA]['name'],
                                'state'=>'closed',
                                'children'=>[]
                            ];
                        }
                        $_menu['children'][$controllerMCA]['children'][] = [
                            'text'=>$aVal['name'],
                            'url'=>"?r={$url}",
                            'appoint_url'=>$aVal['appoint_url'],
                        ];
                    }else{
                        //该方法没有对应的控制器
                        $_menu['children'][] = [
                            'text'=>$aVal['name'],
                            'url'=>"?r={$url}",
                            'appoint_url'=>$aVal['appoint_url'],
                        ];
                    }
                }
            }
            //过滤无子菜单的一级菜单
            if(count($_menu['children']) > 0){
                $menu[] = $_menu;
            }
        }
        $_menu = [];
        //去掉二级菜单的非数字下标
        foreach($menu as $val){
            $m = $val;
            $m['children'] = [];
            foreach($val['children'] as $v){
                $m['children'][] = $v;
            }
            $_menu[] = $m;
        }
        echo json_encode($_menu);
    }*/

    //根据不同情况返回菜单项
    /*protected static function getMenuItem()
    {
        //查询作为菜单的模块、控制器、方法
        $allMenu = RbacMca::find()
                   ->select(['id','name','module_code','controller_code','action_code','type','programmer','appoint_url'])
                   ->where(['is_menu'=>1])
                   ->orderBy('`list_order` desc,`id`')
                   ->asArray()
                   ->all();
        $modules = [];
        $controllers = [];
        $actions = [];
        foreach($allMenu as $val){
            switch ($val['type']) {
                case '0':
                    $mca = $val['module_code'];
                    $modules[$mca] = $val;
                    break;
                case '1':
                    $mca = $val['module_code'].'_'.$val['controller_code'];
                    $controllers[$mca] = $val;
                    break;
                case '2':
                    $mca = $val['module_code'].'_'.$val['controller_code'].'_'.$val['action_code'];
                    $actions[$mca] = $val;
                    break;
            }
        }
        if(self::$isSuperman && !isset($_SESSION['backend']['simulation'])){
            //超级用户返回所有模块、控制器、方法--//
            return [
                'modules'=>$modules,
                'controllers'=>$controllers,
                'actions'=>$actions
            ];
        }else{
            //非超级用户或超级用户模拟其他用户登陆
            //--当前登陆账号能访问的控制器id
            $accessActionIds = $_SESSION['backend']['accessActionIds'];
            //--过滤当前登陆账号不能访问的菜单
            if(!$accessActionIds){
                //无可访问方法
                $_actions = [];
            }else{
                $_actions = [];
                foreach($actions as $val){
                    if(in_array($val['id'],$accessActionIds) && $val['programmer'] != 1){
                        $_actions[] = $val;
                    }
                }
            }
        }
        return [
            'modules'=>$modules,
            'controllers'=>$controllers,
            'actions'=>$_actions
        ];
    }*/
    /**
     * 获取系统主菜单
     */
    public function actionMenu() {
        $allMenu = SystemMenu::find()
           ->select(['id','pid','text'=>'name','mca','target_url','iconCls'=>'icon_class','state'=>'opend'])
           ->where(['is_del'=>0])
           ->orderBy('`list_order` desc,`id`')
           ->asArray()
           ->all();
        if(empty($allMenu)){
            return '[]';
        }
        foreach($allMenu as &$val){
            if( !empty($val['mca']) ){
                $val['mca'] = 'index.php?r='.$val['mca'];
            }
        }
        unset($val);
        $_allMenu = [];
        if(self::$isSuperman && !isset($_SESSION['backend']['simulation'])){
            //超级用户返回所有模块、控制器、方法--//
            $_allMenu = $allMenu;
        }else{
            //非超级用户或超级用户模拟其他用户登陆
            //--当前登陆账号能访问的控制器id
            $accessActionRouter = $_SESSION['backend']['accessActionRouter'];
            //--过滤当前登陆账号不能访问的菜单
            if(!empty($accessActionRouter)){
                foreach($allMenu as $val){
                    if( empty($val['mca']) || in_array(substr($val['mca'],12),$accessActionRouter) ){
						if($val['id'] != 18){ //其他人屏蔽掉“代码更新”菜单
						    $_allMenu[] = $val;	
						}
                    }
                }
            }
        }
        unset($allMenu);
        if(empty($_allMenu)){
            return '[]';
        }else{
            $allMenu = Category::unlimitedForLayer($_allMenu);
            $allMenu = self::fileterEmptyMenu($allMenu);
            return json_encode($allMenu);
        }
    }

    /**
     * 过滤因权限不足导致的空菜单
     */
    protected static function fileterEmptyMenu($menu) {
        $_menu = [];
        foreach($menu as $val){
            if( !empty($val['children']) ){
                $val['children'] = self::fileterEmptyMenu($val['children']);
            }
            if( !empty($val['mca']) || !empty($val['target_url']) || !empty($val['children']) ){
                if(!empty($val['children']) && !empty($val['state']) ){
                    $val['state'] = 'open';
                }else if(!empty($val['children'])){
                    $val['state'] = 'closed';
                }else{
                    unset($val['state']);
                }
                $_menu[] = $val;
            }
        }
        return $_menu;
    }
}