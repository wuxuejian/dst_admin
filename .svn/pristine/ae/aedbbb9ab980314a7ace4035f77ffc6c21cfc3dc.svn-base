<?php
namespace backend\models;
class VipMoneyChange extends \common\models\VipMoneyChange{

    // 关联【会员表】
    public function getVip()
    {
        return $this->hasOne(Vip::className(), ['id' => 'vip_id']);
    }

	
	
}
