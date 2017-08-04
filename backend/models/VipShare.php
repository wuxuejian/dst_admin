<?php
/**
 * @Desc:	我的分享记录 模型 
 * @author: chengwk
 * @date:	2015-11-24
 */
namespace backend\models;
use yii\db\ActiveRecord;
use backend\models\Vip;

class VipShare extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%vip_share}}';
    }

    public function rules()
    {
        return [	
			['mobile','trim'],
			['mobile','checkIsExisted'],
            
			[['chargerid'],'required','message'=>'你还没有选择电桩！'],			
            [['chargerid'],'filter','filter'=>'intval' ],			
			
			['mark','string']
		];
    }
	
	function checkIsExisted($attribute,$params){
		$_mobile = $this->$attribute;
		$vip = Vip::findOne(['mobile'=>$_mobile]);
        if(!$vip){
            $this->addError($attribute,'当前手机号还未注册,无法分享！');
            return false;
        }
        return true;
	}
	
	
	//关联电桩表
    public function getCharger()
    {
        return $this->hasOne(ChargeSpots::className(), ['id' => 'approve_chargerid']);
    }
	
	//关联会员表
    public function getVip()
    {
        return $this->hasOne(Vip::className(), ['id' => 'vip_id']);
    }
	
	
	
	
}
