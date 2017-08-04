<?php
namespace backend\modules\drbac\controllers;
use backend\controllers\BaseController;
use backend\models\Admin;
use backend\models\Mac;
use backend\models\Department;
use backend\models\RbacRole;
use backend\models\AdminRole;
use backend\models\OperatingCompany;
use yii;
use yii\data\Pagination;
class UserController extends BaseController
{
    public function actionIndex()
    {
        //查询部门数据
        $buttons = $this->getCurrentActionBtn();
        return $this->render('index',[
            'buttons'=>$buttons,
        ]);
    }
    
    /**
     * 获取用户列表
     */
    public function actionGetUserList()
    {
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $andWhere = [Admin::tableName().'.is_del'=>0];
        //非开发人员账号或开发人员账号模拟其他用户则不显示开发人员账号
        if(!self::$isSuperman || isset($_SESSION['backend']['simulation'])){
            $andWhere[Admin::tableName().'.`super`'] = 0;
        }
        $query = Admin::find()
            ->select([
                Admin::tableName().'.*',
                'department_name'=>Department::tableName().'.`name`',
                'operating_company'=>OperatingCompany::tableName().'.`name`'
            ])
            ->joinWith('department',false,'LEFT JOIN')
            ->joinWith('operatingCompany',false,'LEFT JOIN')
			->leftJoin('{{%mac}}', '{{%mac}}.`admin_id` = {{%admin}}.`id`')
            ->andWhere($andWhere);
        //查询条件开始
        $query->andFilterWhere([
            'like',
            Admin::tableName().'.`username`',
            yii::$app->request->get('username')
        ]);
		$query->andFilterWhere([
            'like',
            Mac::tableName().'.`mac`',
            yii::$app->request->get('mac')
        ]);
        $query->andFilterWhere([
            'like',
            Admin::tableName().'.`name`',
            yii::$app->request->get('name')
        ]);
        $query->andFilterWhere([
            'like',
            Department::tableName().'.`name`',
            yii::$app->request->get('department_name')
        ]);
        $query->andFilterWhere([
            'like',
            OperatingCompany::tableName().'.`name`',
            yii::$app->request->get('operating_company')
        ]);
        //查询条件结束
        //排序开始
        $sortColumn = yii::$app->request->get('sort');
        $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
        $orderBy = '';
        if($sortColumn){
            switch ($sortColumn) {
                case 'department_name':
                    $orderBy = Department::tableName().'.`name` ';
                    break;
                case 'operating_company':
                    $orderBy = OperatingCompany::tableName().'.`name` ';
                    break;
                default:
                    $orderBy = Admin::tableName().'.`'.$sortColumn.'` ';
                    break;
            }
        }else{
           $orderBy = Admin::tableName().'.`id` ';
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
	function input_csv($handle) {
        $out = array ();
        $n = 0;
        while ($data = fgetcsv($handle, 10000)) {
            $num = count($data);
            for ($i = 0; $i < $num; $i++) {
                $out[$n][$i] = mb_convert_encoding($data[$i], "UTF-8", "GBK");
            }
            $n++;
        }
        return $out;
    }
	
	 /**
     * 指定用户的mac地址管理
     */
    public function actionMacList()
    {
    	$id = yii::$app->request->get('id') or die('param id is required');
    	$buttons = $this->getCurrentActionBtn();
    	
    	return $this->render('mac-list',[
    			'uid'=>$id,
    			'buttons'=>$buttons,
    		
    			]);
    }
	  /**
     * 获取指定用户的mac地址列表
     */
    public function actionGetMacList()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 10;
        $query = Mac::find()
            ->select(['{{%mac}}.*','{{%admin}}.`name`'])
            ->leftjoin('{{%admin}}','{{%admin}}.id={{%mac}}.add_aid')
			->andWhere(['{{%mac}}.`is_del`'=>0])    
            ->andWhere(['{{%mac}}.`admin_id`'=>$id]);
        //查询条件
        
        //查询条件结束
        //排序开始
       // $sortColumn = yii::$app->request->get('sort');
       // $sortType = yii::$app->request->get('order') == 'asc' ? 'asc' : 'desc';
      //  $orderBy = '';
       // if($sortColumn){
            // switch ($sortColumn) {
                // case 'username':
                    // $orderBy = '{{%admin}}.`'.$sortColumn.'` ';
                    // break;
                // default:
                    // $orderBy = '{{%car_insurance_business}}.`'.$sortColumn.'` ';
                    // break;
            // }
        // }else{
            // $orderBy = '{{%car_insurance_business}}.`id` ';
        // }
       // $orderBy .= $sortType;
        //排序结束
        $total = $query->count();
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        $returnArr = [];
        $returnArr['rows'] = $data;
        $returnArr['total'] = $total;
		//var_dump("hihihih");
        echo json_encode($returnArr);
    }
	
	 /**
     * 添加Mac
     */
    public function actionMacAdd()
    {
		
		 $admin_id = yii::$app->request->get('uid');
//		 or die('param id is required');
       // var_dump($admin_id);
		//data submit start
        //if(1==2){
        if(yii::$app->request->isPost){
			
            $model = new Mac;           
            $model->load(yii::$app->request->post(),'');
	
			//var_dump($admin_id);
            $returnArr = [];
            if($model->validate()){
                $model->add_time = date('Y-m-d H:i:s');           
                $model->add_aid = $_SESSION['backend']['adminInfo']['id'];         
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = 'Mac地址添加成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = 'Mac地址添加失败';
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
        
    
        return $this->render('mac-add',[
            'admin_id'=>$admin_id
        ]);
    }
	
	
	 /**
     * 修改mac信息
     */
    public function actionMacEdit()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            $model = Mac::findOne(['id'=>$id]) or die('record not found');
           // $model->setScenario('edit');
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            if($model->validate()){
            	           	
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '修改成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '修改失败';
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
        $macInfo = Mac::find()
                     ->select([
                        'id','mac','note'
                    
                     ])
                     ->where(['id'=>$id])->asArray()->one();
        $macInfo or die('record not found');
      // var_dump($macInfo);
        return $this->render('mac-edit',[
            'macInfo'=>$macInfo         
        ]);
    }
	
	 /**
     * 删除mac
     */
    public function actionMacRemove()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $returnArr = [];
        if(Mac::updateAll(['is_del'=>1],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = '删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '删除失败！';
        }
        echo json_encode($returnArr);
    }
	
	/**
     * MARK mac导入、修改？
     */
    public function actionMacImport(){ 		
       // if (1==2){
	   if(yii::$app->request->isPost){
			
            $list = array();
            //1.解析csv
            $filename = $_FILES['append']['tmp_name'];
            if (empty ($filename)) {
                echo '文件不存在';exit;
            }
            $handle = fopen($filename, 'r');          
		    $result = $this->input_csv($handle);
			$result = array_slice($result, 1); 
			//解析csv end...
            $connection = yii::$app->db;
			$add_num = 0;
            $update_num = 0;
			//var_dump($result);exit;
			foreach ($result as $key => $one) {				
				//2.初始化配置数据
				//var_dump($one);exit;
				$name = $one[1];
				$mac = $one[8];
				$mac_pass = $one[9];
				$note = $one[10];
				$add_time = date('Y-m-d H:i:s');
				//MARK 根据账号找到对应记录,只取一条，同账号的不管啦，有一定的误差。
				//因为系统的表结构缺乏编码，比如：部门编码，岗位编码，工号等OA系统必备字段没有。
				//所以excel导入的准确性难以保证。有误差手动改咯。
								
				//用户id
				$admin_id = 0;
				$sql = "select id from cs_admin where `username`='$name'";
				$admin_info = $connection->createCommand($sql)->queryOne();	
				if ($admin_info) {
					$admin_id = $admin_info['id'];
				}		
				//TODO 更新用户的mac_pass
				
				//操作人员
				$add_aid = $_SESSION['backend']['adminInfo']['id'];
				
				$saveData = [
					'mac' => $mac,
					'admin_id' => $admin_id,
					'add_time' => $add_time,
					'add_aid' => $add_aid,
					'note' => $note					
				];
			
				//var_dump($saveData);
				//exit;
				//3.检查数据合法性
                       	
            	//$err_info = $this->checkDrivingLicenseImportData($connection,$one[0],$one[9],$one[4],$addr,$saveData,$one[8]);
            	//if ($err_info) {
            	//	array_unshift($err_info, "检查第{$key}条数据失败<br/>");
				//	$returnArr['status'] = false;
				//	$returnArr['info'] = $err_info;
				//	return json_encode($returnArr);
            	
				//} 
				//var_dump($saveData);exit;
				$result[$key]= $saveData;				
			}
			foreach ($result as $key => $one) {			
				//4.新增或修改数据
				$r = $this->addOrUpdateMac($connection,$one);
                if($r == 'update'){
                	$update_num++;
                }else if ($r == 'add'){
                	$add_num++;
                }
			}
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = "文件导入成功！新增：{$add_num}，条记录，修改：{$update_num}条记录";
            return json_encode($returnArr);
        }
        return $this->render('mac-import');
    }
	 /**
     * 新增或修改mac信息（批量导入且暂时无法支持批量修改）
     */
	function addOrUpdateMac($connection,$saveData) {
		$band = $connection->createCommand(
    			"select id from cs_mac where admin_id='$saveData[admin_id]' AND mac='$saveData[mac]'"
    	)->queryOne();
		if($band){
    		$r = $connection->createCommand()->update('cs_mac', $saveData,
    				'id=:id',
    				array(':id'=>$band['id'])
    		)->execute();
			if ($r) {
				return 'update';
			}
    	}else {
    		$query = $connection->createCommand()->insert('cs_mac',$saveData);
    		$r = $query->execute();
			if ($r) {
				return 'add';
			}
    	}
		return 'no';
		
	}
	
	
    
    /**
     * 添加用户
     */
    public function actionAddUser()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $delData = Admin::find()
                ->select(['id'])
                ->where([
                    'username'=>yii::$app->request->post('username'),
                    'is_del'=>1
                ])->asArray()->one();
            if($delData){
                Admin::updateAll([
                    'is_del'=>0
                    ],[
                    'id'=>$delData['id']
                ]);
                return json_encode(['status'=>true,'info'=>'数据被恢复！']);
            }
            $model = new Admin;
            $model->setScenario('add');
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            if($model->validate()){
            	if(yii::$app->request->post('operating_company_ids')){
            		$model->operating_company_ids = implode(',',yii::$app->request->post('operating_company_ids'));
            	}
                $model->password = md5(substr(md5($model->password),0,30));
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '用户添加成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '用户添加失败';
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
        //获取部门数据
        $department = Department::find()
            ->select(['id','name'])
            ->where(['is_del'=>0])
            ->asArray()
            ->all();
        //获取公司
        $operatingCompany = OperatingCompany::find()
        ->select(['id','name','area'])
        ->where(['is_del'=>0])
        ->asArray()
        ->all();
//         print_r($operatingCompany);
//         exit;
        return $this->render('add-user',[
            'department'=>$department,
        	'operatingCompany'=>$operatingCompany
        ]);
    }

    /**
     * 修改用户信息
     */
    public function actionEditUser()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            $model = Admin::findOne(['id'=>$id]) or die('record not found');
            $model->setScenario('edit');
            $model->load(yii::$app->request->post(),'');
            $returnArr = [];
            if($model->validate()){
            	if(yii::$app->request->post('operating_company_ids')){
            		$model->operating_company_ids = implode(',',yii::$app->request->post('operating_company_ids'));
            	}else {
            		$model->operating_company_ids = '';
            	}
            	
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '用户修改成功';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '用户修改失败';
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
        $adminInfo = Admin::find()
                     ->select([
                        'id','username','name','sex','mac_pass',
                        'department_id','operating_company_id','email','telephone','qq','operating_company_ids'
                     ])
                     ->where(['id'=>$id])->asArray()->one();
        $adminInfo or die('record not found');
        //获取部门数据
        $department = Department::find()
                      ->select(['id','name'])
                      ->where(['is_del'=>0])
                      ->asArray()
                      ->all();
        //获取公司
        $operatingCompany = OperatingCompany::find()
        ->select(['id','name','area'])
        ->where(['is_del'=>0])
        ->asArray()
        ->all();
        return $this->render('edit-user',[
            'adminInfo'=>$adminInfo,
            'department'=>$department,
        	'operatingCompany'=>$operatingCompany
        ]);
    }

    /**
     * 删除用户
     */
    public function actionRemoveUser()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $returnArr = [];
        if(Admin::updateAll(['is_del'=>1],['id'=>$id,'super'=>0])){
            $returnArr['status'] = true;
            $returnArr['info'] = '用户删除成功！';
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '用户删除失败！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 锁定或解锁账号
     */
    public function actionLockUser()
    {
        $id = yii::$app->request->get('id') or die('param id is required');
        $model = Admin::find()->select(['id','username','is_locked'])->where(['id'=>$id,'super'=>0])->one();
        $model or die('record not found');
        if($model->getOldAttribute('is_locked') == 0){
            $lock = 1;
            $action = '锁定';
        }else{
            $lock = 0;
            $action = '解锁';
        }
        $username = $model->getOldAttribute('username');
        $returnArr = [];
        if($model::updateAll(['is_locked'=>$lock],['id'=>$id])){
            $returnArr['status'] = true;
            $returnArr['info'] = "用户:{$username},被{$action}！";
        }else{
            $returnArr['status'] = false;
            $returnArr['info'] = '操作失败！';
        }
        echo json_encode($returnArr);
    }

    /**
     * 修改密码
     */
    public function actionResetPassword()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $id = yii::$app->request->post('id') or die('param id is required');
            $model = Admin::findOne(['id'=>$id]);
            $model or die('record not found');
            $model->setScenario('reset-password');
            $model->password = yii::$app->request->post('password');
            $returnArr = [];
            if($model->validate()){
                $model->password = md5(substr(md5($model->password),0,30));
                if($model->save(false)){
                    $returnArr['status'] = true;
                    $returnArr['info'] = '密码修改成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '密码修改失败！';
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
        $id = intval(yii::$app->request->get('id')) or die('param id is required');
        return $this->render('reset-password',[
            'id'=>$id
        ]);
    }

    /**
     * 角色分配
     */
    public function actionRoleDistribution()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $adminId = yii::$app->request->post('adminId') or die('param adminId is required');
            //删除该用户原有角色
            AdminRole::deleteAll(['admin_id'=>$adminId]);
            //插入新角色
            $returnArr = [];
            $returnArr['status'] = true;
            $returnArr['info'] = '';
            $roleIds = yii::$app->request->post('role_id');
            if($roleIds){
                foreach($roleIds as $key=>$val){
                    $model = new AdminRole;
                    $model->admin_id = $adminId;
                    $model->role_id = $val;
                    if($model->validate()){
                        if(!$model->save(false)){
                            //添加失败
                            $returnArr['status'] = false;
                            $returnArr['info'] = '角色ID:$val，添加失败！';
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
        }
        //data submit end
        $adminId = yii::$app->request->get('adminId') or die('param adminId is required');
        //查询当前用户所有角色
        $userRoles = AdminRole::find()->select(['role_id'])->where(['admin_id'=>$adminId])->indexBy('role_id')->asArray()->all();
        //查询所有角色
        $roles = RbacRole::find()->where(['is_del'=>0])->asArray()->all();
        return $this->render('role-distribution',[
            'adminId'=>$adminId,
            'roles'=>$roles,
            'userRoles'=>$userRoles
        ]);
    }
}
