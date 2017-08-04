<?php
/**
 * 充电卡 模型
 */
namespace backend\models;
class ChargeCard extends \common\models\ChargeCard
{

    // 关联【人员表】
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(), ['id' => 'cc_creator_id']);
    }
	
	// 关联【会员表】
    public function getVip()
    {
        return $this->hasOne(Vip::className(),[
            'id'=>'cc_holder_id'
        ]);
    }

    // 关联【充值记录表】
    public function getChargeCardRechargeRecord()
    {
        return $this->hasMany(ChargeCardRechargeRecord::className(), ['ccrr_card_id' => 'cc_id']);
    }

	public function rules()
    {
         $rules = [
		    [['cc_code','cc_mark'],
				'trim'
			],
			[['cc_code','cc_mark'],
				'filter','filter'=>'htmlspecialchars'
			],
            ['cc_code','checkValueValidity','skipOnEmpty'=>false]
        ];
		return array_merge($rules,parent::rules());
    }

    /**
     * 自定义检查某些字段值的合法性
     * 注意：因为数据表中将删除的记录标记为cc_is_del=1而非真实删除掉,所以这里不能简单以unique判断值唯一性！
     */
    public function checkValueValidity($attribute){
        // 自定义要检查哪几个字段
        $checkFieldsInfo = [
            'cc_code'=>'电卡编号'
        ];
        $checkFields = array_keys($checkFieldsInfo);
        if (in_array($attribute,$checkFields)) {
            $attrValue = trim($this->$attribute);
            // 检查字段值不能为空
            if($attrValue == ''){
                $this->addError($attribute, $checkFieldsInfo[$attribute].'不能为空！');
                return false;
            }
            // 检查字段值不能重复（要排除掉已标记为删除的记录和当前记录自身！）
            $res = ChargeCard::find()
                ->select(['cc_id'])
                ->where([
                    $attribute=>$attrValue,
                    'cc_is_del'=>0
                ])
                ->asArray()->one();
            if (!empty($res)) {
                if($res['cc_id'] != $this->cc_id){
                    $this->addError($attribute, '该'.($checkFieldsInfo[$attribute]).'已经存在！');
                    return false;
                }
            }
            return true;
        }
        return false;
    }


}