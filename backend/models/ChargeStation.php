<?php
/**
 * 充电站 模型
 */
namespace backend\models;
class ChargeStation extends \common\models\ChargeStation
{

    // 关联【前置机表】
    public function getChargeFrontmachine()
    {
        return $this->hasOne(ChargeFrontmachine::className(), ['id' => 'cs_fm_id']);
    }

    // 关联【充电桩表】，一个充电站下可有多个充电桩
    public function getCharger()
    {
        return $this->hasMany(ChargeSpots::className(), ['station_id' => 'cs_id']);
    }

    // 关联【人员表】
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(), ['id' => 'cs_creator_id']);
    }
	
	
	public function rules()
    {
        $rules = [	           
		    [['cs_code','cs_name','cs_address','cs_building_user','cs_manager_name','cs_manager_mobile','cs_service_telephone','app_tips','cs_mark'],
				'trim'
			],
            [['cs_code','cs_name','cs_address','cs_building_user','cs_manager_name','cs_manager_mobile','cs_service_telephone','app_tips','cs_mark'],
				'filter','filter'=>'htmlspecialchars' 
			],

/*          //因为数据表中将删除的记录标记为cs_is_del=1而非真实删除掉,所以这里不能简单以unique判断！
            ['cs_code','required','message'=>'电站编号不能为空！'],
            ['cs_code','unique','message'=>'该电站编号已经存在！'],
            ['cs_name','required','message'=>'电站名称不能为空！'],
            ['cs_name','unique','message'=>'该电站名称已经存在！']
*/
            ['cs_code','checkValueValidity','skipOnEmpty'=>false],
            ['cs_name','checkValueValidity','skipOnEmpty'=>false],

            [['cs_servicefee'], 'default', 'value'=>0.00],

        ];
		return array_merge($rules,parent::rules());
    }

    /**
     * 自定义检查某些字段值的合法性
     * 注意：因为数据表中将删除的记录标记为cs_is_del=1而非真实删除掉,所以这里不能简单以unique判断值唯一性！
     */
    public function checkValueValidity($attribute){
        // 自定义要检查哪几个字段
        $checkFieldsInfo = [
            'cs_code'=>'电站编号',
            'cs_name'=>'电站名称'
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
            $res = ChargeStation::find()
                ->select(['cs_id'])
                ->where([
                    $attribute=>$attrValue,
                    'cs_is_del'=>0
                ])
                ->asArray()->one();
            if (!empty($res)) {
                if($res['cs_id'] != $this->cs_id){
                    $this->addError($attribute, '该'.($checkFieldsInfo[$attribute]).'已经存在！');
                    return false;
                }
            }
            return true;
        }
        return false;
    }

}