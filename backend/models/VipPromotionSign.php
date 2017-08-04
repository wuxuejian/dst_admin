<?php
/**
 * @Desc:	微信推广活动报名注册 模型
 * @date:	2016-03-03
 */
namespace backend\models;
use yii;
class VipPromotionSign extends \common\models\VipPromotionSign{

    public static $_appid = 'wx6f2b678b136578ba';  //微信appid
    public static $_secret = 'b4432ef2c58cbf3c5f9195a38622b051'; //微信secret
    public static $_host = 'http://yqzc.dstzc.com'; //域名

    public function rules(){
        $rules = [
			[['client','mobile','email','mark','invite_code_used','company', 'profession', 'district'],'trim'],
            [['client','mobile','email','mark','invite_code_used','company', 'profession', 'district'],'filter','filter'=>'htmlspecialchars'],
			//['client','required','message'=>'姓名必填！'],
			['mobile','required','message'=>'手机必填！'],
			['mobile','unique','message'=>'该手机号已经存在！'],
            ['mobile','match','pattern'=>'/^1[34578]\d{9}$/','message'=>'手机号格式错误！'],
			['email','unique','message'=>'该邮箱已经存在！'],
			['email','email','message'=>'邮箱格式错误！'], 
            ['sex','filter','filter'=>'intval']
        ];
		return array_merge($rules,parent::rules());
    }
    
	//会员表关联车辆表查询，一个会员可以有多辆车也可能暂时没有登记车辆。
    public function getVehicle()
    {
        return $this->hasMany(Vehicle::className(), ['vip_id' => 'id']);
    }

	//关联自身查询邀请人。（加入了Yii2 AR的别名）
    public function getInviter(){
        return $this->hasOne(VipPromotionSign::className(), ['invite_code_mine' => 'invite_code_used'])
                    ->from(VipPromotionSign::tableName().' inviter'); // from设置别名
    }



    /**
     * 验证短信验证码是否正确
     * @param   string $mobile    要验证的短信验证码
     * @param   string $inputCode 用户输入的验证码
     * @return  bool              成功返回true否则返回false
     */
    public static function checkShotMessageCode($mobile,$inputCode){
        $vipInfo = self::find()
            ->select(['id','shot_message_code','sm_reqtime'])
            ->where(['mobile'=>$mobile])->asArray()->one();
        if(!$vipInfo){
            return false;
        }
        if($vipInfo['shot_message_code'] == $inputCode){
            self::updateAll([
                'shot_message_code'=>'',
                'sm_reqtime'=>0,
                'sm_number'=>''
            ],['id'=>$vipInfo['id']]);
            return true;
        }else{
            return false;
        }
    }

    /**
     * 根据电话号码获取用户id
     * @param  string $phoneNumber 电话号码
     * @return int    $vip_id      会员id/0 0表示无对应记录
     */
    public static function getVipIdByPhoneNumber($phoneNumber){
        $vipInfo = self::find()->select(['id'])->where(['mobile'=>$phoneNumber])->asArray()->one();
        if($vipInfo){
            return $vipInfo['id'];
        }else{
            return 0;
        }
    }

    public static function getJson($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }


    /*
     *  获取微信用户openid等信息
     *  @$appid: 微信appid
     *  @$secret: 微信secret
     *  @$code: 微信访问页面跳转新url时自动传递的编码
     */
    public static function getWxUserInfo($appid, $secret, $code){
        //静默
        //第一步:取全局access_token
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
        $token = VipPromotionSign::getJson($url);

        //第二步:取得openid
        $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
        $oauth2 = VipPromotionSign::getJson($oauth2Url);

        //第三步:根据全局access_token和openid查询用户信息
        $access_token = $token["access_token"];
        $openid = $oauth2['openid'];
        $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
        $userinfo = VipPromotionSign::getJson($get_user_info_url);

        //打印用户信息
        //print_r($userinfo['openid']);
        return $userinfo;
    }


}
