<?php
/**
 * @Desc:	微信推广活动-申请提现表 模型
 * @date:	2016-03-16
 */
namespace backend\models;
use yii;
class VipPromotionApplyCash extends \common\models\VipPromotionApplyCash{

    public function rules(){
        $rules = [
			[['bank_name', 'bank_card','alipay_account','mark'],'trim'],
            [['bank_name', 'bank_card','alipay_account','mark'],'filter','filter'=>'htmlspecialchars'],
        ];
		return array_merge($rules,parent::rules());
    }


	//关联注册信息表，查申请人信息。（加入了Yii2 AR的别名）
    public function getApplicantInfo(){
        return $this->hasOne(VipPromotionSign::className(), ['id' => 'apply_id'])
                    ->from(VipPromotionSign::tableName().' applicant'); // from设置别名
    }

    // 关联【人员表】
    public function getAdmin() {
        return $this->hasOne(Admin::className(), ['id' => 'creator_id']);
    }




}
