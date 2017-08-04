<?php
/**
 * @Desc:	会员列表模型 
 * @author: chengwk
 * @date:	2015-10-19
 */
namespace backend\models;
use yii\db\ActiveRecord;
class Vip extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%vip}}';
    }
    
    public function rules(){
        return [
			[['client','email','mark'],'trim'],
            [['client','mobile','email','mark'],'filter','filter'=>'htmlspecialchars'],
			['mobile','unique','message'=>'该手机号已经存在！'],
            ['mobile','match','pattern'=>'/^1[34578]\d{9}$/','message'=>'手机号格式错误！'],
			['email','unique','message'=>'该邮箱已经存在！'],
			['email','email','message'=>'邮箱格式错误！'], 
            ['sex','filter','filter'=>'intval']
        ];
    }
    
	//关联车辆表。注意会员可以有多辆车也可能暂时没有登记车辆。
    public function getVehicle()
    {
        return $this->hasMany(Vehicle::className(), ['vip_id' => 'id'])
                ->andOnCondition('({{%vehicle}}.`is_del` = 0 OR {{%vehicle}}.`vehicle` IS NULL)'); //增加其他on条件
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

}
