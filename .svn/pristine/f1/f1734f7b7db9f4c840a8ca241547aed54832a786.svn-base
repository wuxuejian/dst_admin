<?php
/**
 * @Desc: 微信推广活动->微信公众号->我要注册 控制器
 * @date:	2016-03-03
 */
namespace backend\modules\promotion\controllers;
use yii;
use yii\data\Pagination;
use backend\models\VipPromotionSign;
use backend\classes\JSSDK; //微信公众号web分享类

class SignController extends BaseController{

    /*
     * 访问“我要注册”视图
     */
    public function actionIndex(){
        //判断若是点击别人的分享链接进入注册页面，则可从url取出别人的邀请码
        if(Yii::$app->request->get('invite_code')){
            $invite_code = Yii::$app->request->get('invite_code');
        }else{
            $invite_code = '';
        }
        $appid = VipPromotionSign::$_appid;
        $redirect_uri = urlencode ( "http://yqzc.dstzc.com/index.php?r=promotion/sign/sign&invite_code=$invite_code");
        //静默
        $url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
        header("Location:".$url);
    }

    public function actionSign(){
        $data = [];
        $appid = VipPromotionSign::$_appid;
        $secret = VipPromotionSign::$_secret;
        $code = Yii::$app->request->get('code');
        if($code){
            $userInfo = VipPromotionSign::getWxUserInfo($appid, $secret, $code);
            $data['open_id'] = $userInfo['openid'];

            //检查是否已经注册过，查出当前微信用户绑定的邀请码，若是已经注册过的则转为显示分享邀请码页面
            $res = VipPromotionSign::find()
                ->select(['invite_code_mine'])
                ->where(['open_id' => $userInfo['openid']])
                ->asArray()->one();
            if(!empty($res) && $res['invite_code_mine']){
                $data['myInviteCode'] = $res['invite_code_mine'];
                $data['isVisitSignMenu'] = 1;
                //分享相关
                $jssdk = new JSSDK($appid, $secret);
                $signPackage = $jssdk->GetSignPackage();
                $data['signPackage'] = $signPackage;
                return $this->render('share',$data);
            }

        }else{
            $data['open_id'] = '';
        }
        //判断若是点击别人的分享链接进入注册页面，则可从url取出别人的邀请码
        if(Yii::$app->request->get('invite_code')){
            $data['invite_code'] = Yii::$app->request->get('invite_code');
        }else{
            $data['invite_code'] = '';
        }
        return $this->render('index',$data);
	}


	/*
	 * 我要注册-首页提交
	 */
	public function actionIndexFormSubmit(){
		if(yii::$app->request->isPost) {
            $_openId = isset($_REQUEST['open_id']) ? trim($_REQUEST['open_id']) : ''; // 微信openid
            $_name = isset($_REQUEST['name']) ? trim($_REQUEST['name']) : '';        // 名称
            $_sex = isset($_REQUEST['sex']) ? intval($_REQUEST['sex']) : 1;          // 性别
            $_mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';    // 手机号
            $_vcode = isset($_REQUEST['vcode']) ? trim($_REQUEST['vcode']) : '';        // 短信验证码
            $_inviteCode = isset($_REQUEST['invite_code']) ? trim($_REQUEST['invite_code']) : ''; // 使用的邀请码

            //验证微信openid
            if(!$_openId){
                return json_encode(['status' => false, 'info' => '目前仅支持微信客户端打开链接注册！']);
            }
            $res = VipPromotionSign::find()
                ->select(['invite_code_mine'])
                ->where(['open_id' => $_openId])
                ->asArray()->one();
            if(!empty($res) && $res['invite_code_mine']){
                return json_encode(['status' => false, 'info' => '您已经注册过了！']);
            }

            //验证字段非空
            if (!$_name || !$_mobile || !$_vcode) {
                return json_encode(['status' => false, 'info' => '姓名、手机、验证码为必填项！']);
            }

            //验证所填邀请码是否存在
            if ($_inviteCode) {
                $record = VipPromotionSign::find()
                    ->select(['id'])
                    ->where(['invite_code_mine' => $_inviteCode])
                    ->asArray()->one();
                if (empty($record)) {
                    return json_encode(['status' => false, 'info' => '所填邀请码不存在！']);
                }
            }

            //修改客户信息必然有记录否则短信验证不通过
            $VipPromotionSign = VipPromotionSign::findOne(['mobile' => $_mobile]);
            if ($VipPromotionSign->code != '') {
                return json_encode(['status' => false, 'info' => '该手机号已经注册过了！']);
            }

            // 验证短信验证码
            if (!VipPromotionSign::checkShotMessageCode($_mobile, $_vcode)) {
                return json_encode(['status' => false, 'info' => '验证码不正确！']);
            }

            $VipPromotionSign->open_id = $_openId;
            $VipPromotionSign->client = $_name;
            $VipPromotionSign->sex = $_sex;
            $VipPromotionSign->mobile = $_mobile;
            $VipPromotionSign->invite_code_used = strtoupper($_inviteCode);
            if ($VipPromotionSign->save(true)) {
                $data['status'] = true;
                $data['info'] = '请您继续填写注册信息！';
                $data['data'] = $VipPromotionSign->getAttributes();
                return json_encode($data);
            } else {
                return json_encode(['status' => false, 'info' => '保存时出错！']);
            }
        }
	}


    /*
     * 访问“我要注册-下一步”视图
     */
    function actionNext(){
        $id = yii::$app->request->get('id');
        return $this->render('next',['id'=>$id]);
    }

    /*
     * 我要注册-下一步的提交
     */
    function actionNextFormSubmit(){
        if(yii::$app->request->isPost) {
            $_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;                            // ID
            $_company = isset($_REQUEST['company']) ? trim($_REQUEST['company']) : '';              // 公司
            $_profession = isset($_REQUEST['profession']) ? trim($_REQUEST['profession']) : '';     // 职业
            $_district = isset($_REQUEST['district']) ? trim($_REQUEST['district']) : '';           // 区域
            $_otherDistrict = isset($_REQUEST['otherDistrict']) ? trim($_REQUEST['otherDistrict']) : ''; // 其他区域

            $VipPromotionSign = VipPromotionSign::findOne($_id);
            if (!$VipPromotionSign) {
                return json_encode(['status' => false, 'info' => '找不到对应记录！']);
            }
            $VipPromotionSign->code = 'VIP' . date('YmdHis') . mt_rand(100, 999);
            $VipPromotionSign->company = $_company;
            $VipPromotionSign->profession = $_profession;
            $VipPromotionSign->district = $_otherDistrict != '' ? $_otherDistrict : $_district;
            $VipPromotionSign->systime = time();
            //生成唯一的8位长度的邀请码
            do{
                $newInviteCode = '';
                $str1 = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; //去掉I和O以免和1与0混淆
                $str2 = '0123456789';
                while(strlen($newInviteCode)<2) {
                    $newInviteCode .= $str1[rand(0, strlen($str1)-1)]; //随机一个字母
                }
                while(strlen($newInviteCode)<8) {
                    $newInviteCode .= $str2[rand(0, strlen($str2)-1)]; //随机一个数字
                }
                $record = VipPromotionSign::find()
                    ->select(['id'])
                    ->where(['invite_code_mine'=>$newInviteCode])
                    ->asArray()->one();
            }while(!empty($record));
            $VipPromotionSign->invite_code_mine = $newInviteCode;
            if ($VipPromotionSign->save(true)) {
                $data['status'] = true;
                $data['info'] = '注册成功！';
                $data['data'] = $VipPromotionSign->getAttributes();
                return json_encode($data);
            } else {
                return json_encode(['status' => false, 'info' => '保存时出错！']);
            }
        }
    }

    /*
     * 注册成功后访问的分享视图
     */
	function actionShare(){
        $data = [];
        $data['myInviteCode'] = $_GET['myInviteCode'];
        //分享相关
        $appid = VipPromotionSign::$_appid;
        $secret = VipPromotionSign::$_secret;
        $jssdk = new JSSDK($appid, $secret);
        $signPackage = $jssdk->GetSignPackage();
        $data['signPackage'] = $signPackage;
        return $this->render('share',$data);
	}


    /*
     * 所有邀请码使用次数统计
     */
    function actionInviteCodeStatistics(){
        $query = VipPromotionSign::find()
            ->select(['invite_code_used','usedNum'=>'COUNT(invite_code_used)'])
            ->where("invite_code_used != '' AND is_del = 0")
            ->groupBy('invite_code_used')
            ->orderBy('usedNum DESC');
        $total = $query->count();
        //分页
        $pageSize = isset($_GET['rows']) && $_GET['rows'] <= 50 ? intval($_GET['rows']) : 1;
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $pageSize]);
        $data = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return json_encode($data);
    }




}