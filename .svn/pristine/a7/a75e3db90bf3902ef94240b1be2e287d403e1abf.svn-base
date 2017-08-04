<?php
/**
 * @Desc: 微信推广活动【邀请朋友】 控制器
 * @date:	2016-03-05
 */
namespace backend\modules\promotion\controllers;
use yii;
use yii\data\Pagination;
use backend\models\VipPromotionSign;
use backend\classes\JSSDK; //微信公众号web分享类


class InviteFriendController extends BaseController{

    /*
     * 访问“邀请朋友”视图
     */
	public function actionIndex(){
		$appid = VipPromotionSign::$_appid;
		$redirect_uri = urlencode ( 'http://yqzc.dstzc.com/index.php?r=promotion/invite-friend/invite');
		//静默
		$url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
		header("Location:".$url);
	}
	
    public function actionInvite(){
        $data = [];
        $appid = VipPromotionSign::$_appid;
        $secret = VipPromotionSign::$_secret;
        $code = Yii::$app->request->get('code');

        //分享相关
		$jssdk = new JSSDK($appid, $secret);
        $signPackage = $jssdk->GetSignPackage();
        $data['signPackage'] = $signPackage;

        if($code){
            $userInfo = VipPromotionSign::getWxUserInfo($appid, $secret, $code);
            $data['open_id'] = $userInfo['openid'];
            //查出当前微信用户绑定的邀请码
            $res = VipPromotionSign::find()
                ->select(['invite_code_mine'])
                ->where(['open_id' => $userInfo['openid']])
                ->asArray()->one();
            if(!empty($res) && $res['invite_code_mine']){
                $data['myInviteCode'] = $res['invite_code_mine'];
            }else{
                $data['myInviteCode'] = '';
            }
        }else{
            $data['open_id'] = '';
            $data['myInviteCode'] = '';
        }
		//print_r($data);exit;
		//return $this->render('index',$data);
		return $this->render('../sign/share',$data); //显示分享邀请码页面
	}





}