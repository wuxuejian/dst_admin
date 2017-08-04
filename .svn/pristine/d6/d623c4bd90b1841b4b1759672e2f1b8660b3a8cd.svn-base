<?php
namespace backend\controllers;
use backend\models\Admin;
use backend\models\Mac;
use Yii;
use backend\classes\UserLog;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller
{
	public $enableCsrfValidation = false;
	
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'backend\classes\CaptchaAction',
                'minLength' => 4,
                'maxLength' => 4,
                //'fixedVerifyCode' => '123'.mt_rand(1,9),
            ]
        ];
    }

    /*public function actionIndex()
    {
        return $this->render('index');
    }*/

    public function actionLogin()
    {
        //data submit start
        if(yii::$app->request->isPost){
            $model = new Admin;
            $model->setScenario('login');
            $model->load(yii::$app->request->post(),'');
			$mac_post = yii::$app->request->post('mac');
			
            $returnArr = [];
            if($model->validate()){
                //查询用户数据
                $password = md5(substr(md5($model->password),0,30));
                $adminInfo = $model->find()
                             ->where([
                                 'username'=>$model->username,
                                 'password'=>$password,
                             ])
                             ->asArray()->one();
                if(!$adminInfo){
                    //用户名或密码错误
                    $returnArr['status'] = false;
                    $returnArr['info'] = '用户名或密码错误！';
                }else{
                    //--用户名和密码正确--//
                    if($adminInfo['is_del'] == 1){
                        //--账号已经被删除--//
                        $returnArr['status'] = false;
                        $returnArr['info'] = '用户名或密码错误！';
                    }elseif($adminInfo['is_locked'] == 1){
                        //--账号被锁定--//
                        $returnArr['status'] = false;
                        $returnArr['info'] = '该账号被锁定！';
                    }else{
						$mac_pass = false;
											
						//TODO begin特权账号判断
						if(!isset($adminInfo['mac_pass']) || $adminInfo['mac_pass'] != '1') {
							if (isset($mac_post) && $mac_post != "") {
								$mac = new Mac;//查询mac地址库
								$macInfo = $mac->find()
									 ->where([
										 'mac'=>$mac_post,                              
										 'is_del'=>'0'                              
									 ])
									 ->asArray()->all();                
								//该mac地址不在已登记的库中
								if (!$macInfo){
									$mac_pass = false; 
								} else {
									//遍历mac记录,比对当前登录的mac是否和登录的mac一致
									//foreach ($macInfo as $c) {
									//	if (isset($mac_post) && ($c['mac'] == $mac_post)) {
											$mac_pass = true;
									//		break;
									//	}
									//}						
								}
							}							
						} else {
							$mac_pass = true;
						}
						//end 特权判断
						//mac验证通过
						if ($mac_pass){							
							//--合法的登陆账号--//
							//提示信息
							$returnArr['status'] = true;
							$returnArr['info'] = '登陆成功！';
							//保存登陆状态
							unset($adminInfo['password']);
							$sessionArr = [];
							$sessionArr['isLogin'] = 1;
							$sessionArr['adminInfo'] = $adminInfo;
                                                        $sessionArr['login_pwd'] = $model->password;
							$session = yii::$app->session;
							$session->open();
							$_SESSION['backend'] = $sessionArr;
							//更新登陆信息
							$saveArr = [];
							$saveArr['ltime'] = time();
							$saveArr['lip'] = $_SERVER['REMOTE_ADDR'];
							$saveArr['active_time'] = time();
							$model->updateAll($saveArr,['id'=>$adminInfo['id']]);
							
							UserLog::log("登陆系统！",'sys'); 
						} else {
							$returnArr['status'] = false;
							$returnArr['info'] = '该账号未授权在此设备上登录！';
						}
                    }                
                }
            }else{
                $errors = $model->getErrors();
                if($errors){
                    $errorStr = '';
                    foreach($errors as $val){
                        $errorStr .= $val[0];
                    }
                }else{
                    $errorStr = '未知错误！';
                }
                $returnArr['status'] = false;
                $returnArr['info'] = $errorStr;
            }
            echo json_encode($returnArr);
            return null;
        }
        //data submit end
        $model = new Admin;
        /* return $this->render('login',[
            'model'=>$model
        ]); */
        return $this->render('login1',[
        		'model'=>$model
        		]);
    }

    public function actionLogout()
    {
        $session = yii::$app->session;
        $session->open();
        $_SESSION['backend'] = null;
        $this->redirect(['site/login']);
    }
	
	
	/*
	 * 修改资料
	 */
    public function actionEditProfile(){
		if(yii::$app->request->isPost){
			$session = yii::$app->session;
			$session->open();
			
			$formData = yii::$app->request->post();
			$model = Admin::findOne($formData['id']);
			if(trim($formData['oldPassword'])){ // 若原密码不为空则表示要修改密码，需要先验证原密码。
				if($model->password != md5(substr(md5(trim($formData['oldPassword'])),0,30))){
					return json_encode(['status'=>false,'info'=>'原密码验证错误！']);
				}
				if(trim($formData['newPassword'])){
					$newPassword = trim($formData['newPassword']);
					//8-15位包含大、小写字母，数字检测
					if(!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[a-zA-Z0-9]{8,20}$/',$newPassword)){
						return json_encode(['status'=>false,'info'=>'密码必须同时包含英文大写、小写字母与数字，长度不少于8位。']);
					}
					$model->password = md5(substr(md5($newPassword),0,30));
				}
			}else {
				//8-15位包含大、小写字母，数字检测
				if(!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[a-zA-Z0-9]{8,20}$/',$session['backend']['login_pwd'])){
					return json_encode(['status'=>false,'info'=>'密码必须同时包含英文大写、小写字母与数字，长度不少于8位。']);
				}
			}
			$model->sex = $formData['sex'];
			$model->telephone = trim($formData['telephone']);
			$model->email = trim($formData['email']);
			$model->qq = trim($formData['qq']);
            $returnArr = [];
            if($model->validate()){
                if($model->save(false)){
                	if(@$newPassword){
	                	$sessionArr = $session['backend'];
	                	$sessionArr['login_pwd'] = $newPassword;
	                	$session['backend'] = $sessionArr;
                	}
                    $returnArr['status'] = true;
                    $returnArr['info'] = '修改资料成功！';
                }else{
                    $returnArr['status'] = false;
                    $returnArr['info'] = '修改资料失败！';
                }
            }else{
                $returnArr['status'] = false;
                $error = $model->getErrors();
                if($error){
                    $arr = array_column($error,0);
                    $errorStr = join('',$arr);
                    $returnArr['info'] = $errorStr;
                }else{
                    $returnArr['info'] = '未知错误';
                }
            }
            return json_encode($returnArr);
		}else{
			$session = yii::$app->session;
			$session->open();
			$id = $_SESSION['backend']['adminInfo']['id'];
			$adminInfo = Admin::find()->where(['id'=>$id])->asArray()->one();
			return $this->render('edit-profile',['adminInfo'=>$adminInfo]);
		}
    }
	

	
}
