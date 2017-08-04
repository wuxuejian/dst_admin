<?php
/**
 * 充电卡充值记录 模型
 */
namespace backend\models;
class ChargeCardRechargeRecord extends \common\models\ChargeCardRechargeRecord
{

    // 关联【人员表】
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(), ['id' => 'ccrr_creator_id']);
    }
	
    // 关联【电卡表】
    public function getChargeCard()
    {
        return $this->hasOne(ChargeCard::className(), ['cc_id' => 'ccrr_card_id']);
    }
	


	public function rules()
    {
        $rules = [
		    ['ccrr_mark','trim'],
			['ccrr_mark','filter','filter'=>'htmlspecialchars'],
			[['ccrr_recharge_money','ccrr_incentive_money'],'default','value'=>0.00]
        ];
		return array_merge($rules,parent::rules());
    }


}