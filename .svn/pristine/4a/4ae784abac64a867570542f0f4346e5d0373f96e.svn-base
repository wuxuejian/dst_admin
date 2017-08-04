<?php
/**
 * @Desc:	微信推广活动-奖金结算记录表 模型
 * @date:	2016-03-16
 */
namespace backend\models;
use yii;
class VipPromotionSettle extends \common\models\VipPromotionSettle{

/*     public function rules(){
        $rules = [
			[['', '','','mark'],'trim'],
            [['', '','','mark'],'filter','filter'=>'htmlspecialchars'],
        ];
		return array_merge($rules,parent::rules());
    } */


	//关联注册信息表
    public function getVipPromotionSign(){
        return $this->hasOne(VipPromotionSign::className(), ['id' => 'inviter_id']);
    }

    // 关联【人员表】
    public function getAdmin() {
        return $this->hasOne(Admin::className(), ['id' => 'creator_id']);
    }




}
