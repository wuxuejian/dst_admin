<?php
/**
 * 会员注册登录管理控制器
 */
namespace backend\modules\interfaces\controllers;
use backend\models\Vip;
use backend\models\ChargeAppointment;
use backend\models\VipNotice;
use backend\models\VipAppLogin;
use backend\models\VipUpload;
use yii\web\Controller;

class RegisterLoginController extends Controller{
	public $layout = false;
    public $enableCsrfValidation = false;
	/**
     *	注册App
	 */
	public function actionRegister(){
		$datas = [];
		$_mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : ''; 	// 手机号
		$_pwd  	 = isset($_REQUEST['pwd']) ? trim($_REQUEST['pwd']) : ''; 			// 密码
		$_vcode  = isset($_REQUEST['vcode']) ? trim($_REQUEST['vcode']) : ''; 		// 手机验证码
		$_name   = isset($_REQUEST['name']) ? trim($_REQUEST['name']) : ''; 		// 会员名称
		$_sex    = isset($_REQUEST['sex']) ? intval($_REQUEST['sex']) : 1;          // 性别
		$_email  = isset($_REQUEST['email']) ? trim($_REQUEST['email']) : ''; 		// 邮箱
		$_mark   = isset($_REQUEST['mark']) ? trim($_REQUEST['mark']) : ''; 	    // 备注
		$_ver    = isset($_REQUEST['ver']) ? trim($_REQUEST['ver']) : '';		 	// 当前所用App版本号

		if(!$_mobile || !$_pwd || !$_vcode) {
			$datas['error'] = 1;
			$datas['msg'] = '注册失败：填写不能为空！';
			$datas['errline'] = __LINE__;
			echo json_encode($datas);
			exit;
		}

		// 验证验证码是否正确?
		if(!Vip::checkShotMessageCode($_mobile,$_vcode)) {
			$datas['error'] = 1;
			$datas['msg'] = '注册失败：验证码不正确！';
			$datas['errline'] = __LINE__;
			echo json_encode($datas);
			exit;
		}

		//修改客户信息必然有记录否则短信验证不通过
		$vip = Vip::findOne(['mobile'=>$_mobile]);
		if(!empty($vip->getOldAttribute('code'))) {
			$datas['error'] = 1;
			$datas['msg'] = '注册失败：该手机号已经存在！';
			$datas['errline'] = __LINE__;
			echo json_encode($datas);
			exit;
		}
		$vip->code = 'VIP'.date('YmdHis').mt_rand(100,999);
		$vip->password = md5(substr(md5($_pwd),0,30));
		$vip->mobile = $_mobile;
		$vip->client = $_name;
		$vip->sex = $_sex;
		$vip->email = $_email;
		$vip->mark = $_mark;
		$vip->app_ver = $_ver;
		$vip->systime = time();
		if ($vip->save(false)){
			//登录操作
			$loginKeyInfo = VipAppLogin::login('id',$vip->getAttribute('id'));
			$datas['error'] = 0;
			$datas['msg'] = '注册成功！';
			$sendData = $vip->getAttributes();
			unset($sendData['code']);
			unset($sendData['password']);
			unset($sendData['is_del']);
			unset($sendData['money_acount']);
			unset($sendData['shot_message_code']);
			unset($sendData['sm_reqtime']);
			unset($sendData['sm_number']);
			$datas['data'] = array_merge($sendData,$loginKeyInfo);
		} else {
			$datas['error'] = 1;
			$datas['msg'] = '注册失败：保存会员时出错！';
			$datas['errline'] = __LINE__;
		}
		echo json_encode($datas); exit;
	}

	/**
	 *	登录App
	 */
	public function actionLogin(){
		$datas = [];
		$_mobile  = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : ''; 		// 手机号
		$_pwd  	  = isset($_REQUEST['pwd']) ? trim($_REQUEST['pwd']) : ''; 				// 密码
		$_ver  	  = isset($_REQUEST['ver']) ? trim($_REQUEST['ver']) : '';		 		// App版本号

		if(!$_mobile || !$_pwd) {
			$datas['error'] = 1;
			$datas['msg'] = '填写不能为空！';
			$datas['errline'] = __LINE__;
			echo json_encode($datas); exit;
		}

		$vip = Vip::findOne(['mobile'=>$_mobile]);
		if(!$vip){
			$datas['error'] = 1;
			$datas['msg'] = '该手机号尚未注册！';
			$datas['errline'] = __LINE__;
		}else{
			if($vip->password != md5(substr(md5($_pwd),0,30))){
				$datas['error'] = 1;
				$datas['msg'] = '登录密码不正确！';
				$datas['errline'] = __LINE__;
			}else{
				//生成登录key
				$loginKeyInfo = VipAppLogin::login('id',$vip->getAttribute('id'));
				$sendData = $vip->getAttributes();
				unset($sendData['code']);
				unset($sendData['password']);
				unset($sendData['is_del']);
				unset($sendData['money_acount']);
				unset($sendData['shot_message_code']);
				unset($sendData['sm_reqtime']);
				unset($sendData['sm_number']);
				//返回当前客户驾驶证图片
				$driveImage = VipUpload::find()
					->select(['file_path'])
					->where(['vip_id'=>$vip->getAttribute('id')])
					->asArray()->one();
				if($driveImage){
					$sendData['drive_image'] = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].'/'.$driveImage['file_path'];
				}else{
					$sendData['drive_image'] = '';
				}
				$datas['error'] = 0;
				$datas['msg'] = '登录成功！';
				$datas['data'] = array_merge($sendData,$loginKeyInfo);
			}
		}
		echo json_encode($datas);
	}

	/**
	 *	忘记密码
	 */
	public function actionForgetPwd(){
		$datas = [];
		$_mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : ''; 	 // 手机号
		$_newpwd = isset($_REQUEST['newpwd']) ? trim($_REQUEST['newpwd']) : ''; 	 // 新密码
		$_vcode  = isset($_REQUEST['vcode']) ? trim($_REQUEST['vcode']) : ''; 		 // 手机验证码
		$_ver    = isset($_REQUEST['ver']) ? trim($_REQUEST['ver']) : '';		 	 // App版本号

		if(!$_mobile || !$_newpwd || !$_vcode){
			$datas['error'] = 1;
			$datas['msg'] = '修改失败：填写不能为空！';
			$datas['errline'] = __LINE__;
			echo json_encode($datas); exit;
		}

		// 验证验证码是否正确?
		if(!Vip::checkShotMessageCode($_mobile,$_vcode)) {
			$datas['error'] = 1;
			$datas['msg'] = '修改失败：验证码不正确！';
			$datas['errline'] = __LINE__;
			echo json_encode($datas); exit;
		}

		$vip = Vip::findOne(['mobile'=>$_mobile]);
		if(!$vip){
			$datas['error'] = 1;
			$datas['msg'] = '修改失败：该手机号尚未注册！';
			$datas['errline'] = __LINE__;
		}else{
			$vip->password = md5(substr(md5($_newpwd),0,30));
			if($vip->save(false)){
				$datas['error'] = 0;
				$datas['msg'] = '修改密码成功！';
			}else{
				$datas['error'] = 1;
				$datas['msg'] = '修改失败：保存新密码时出错！';
				$datas['errline'] = __LINE__;
			}
		}
		echo json_encode($datas); exit;
	}

	/**
	 *	修改密码
	 */
	public function actionEditPwd(){
		$datas = [];
		$_mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : ''; 	 // 手机号
		$_oldpwd = isset($_REQUEST['oldpwd']) ? trim($_REQUEST['oldpwd']) : ''; 	 // 旧密码
		$_newpwd = isset($_REQUEST['newpwd']) ? trim($_REQUEST['newpwd']) : ''; 	 // 新密码
		$_ver    = isset($_REQUEST['ver']) ? trim($_REQUEST['ver']) : '';		 	 // App版本号

		if(!$_mobile){
			$datas['error'] = 1;
			$datas['msg'] = '修改失败：请先登录！';
			$datas['errline'] = __LINE__;
			echo json_encode($datas); exit;
		}

		if(!$_oldpwd || !$_newpwd){
			$datas['error'] = 1;
			$datas['msg'] = '修改失败：填写不能为空！';
			$datas['errline'] = __LINE__;
			echo json_encode($datas); exit;
		}

		$vip = Vip::findOne(['mobile'=>$_mobile]);
		if(!$vip){
			$datas['error'] = 1;
			$datas['msg'] = '修改失败：该手机号尚未注册！';
			$datas['errline'] = __LINE__;
		}else{
			if($vip->password != md5(substr(md5($_oldpwd),0,30))){
				$datas['error'] = 1;
				$datas['msg'] = '修改失败：原密码不正确！';
				$datas['errline'] = __LINE__;
			}else{
				$vip->password = md5(substr(md5($_newpwd),0,30));
				if($vip->save(false)){
					$datas['error'] = 0;
					$datas['msg'] = '修改成功！';
				}else{
					$datas['error'] = 1;
					$datas['msg'] = '修改失败：保存新密码时出错！';
					$datas['errline'] = __LINE__;
				}
			}
		}
		echo json_encode($datas); exit;
	}
		
	
	
}