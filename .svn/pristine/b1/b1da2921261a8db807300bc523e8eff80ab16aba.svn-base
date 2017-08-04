<?php
/**
 * 电池衰减检测标准 模型
 */
namespace backend\models;
class BatteryDetectCriteria extends \common\models\BatteryDetectCriteria
{
    /**
     * 关联【人员】表
     */
    public function getAdmin(){
        return $this->hasOne(Admin::className(),[
            'id'=>'creator_id'
        ]);
    }

    /**
     * 关联【电池检测记录】表
     */
    public function getBatteryDetection(){
        return $this->hasOne(BatteryDetection::className(),[
            'battery_type'=>'battery_type'
        ]);
    }

    
    public function rules()
    {
        $rules = [
            //[['battery_type'],'filter','filter'=>'htmlspecialchars'],
            [['I1', 'V1_S', 'V1_E', 'V2_S', 'V2_E', 'V3_S', 'V3_E', 'Y1_S', 'Y1_E', 'Y2_S', 'Y2_E', 'Y3_S', 'Y3_E', 'V4_S', 'V4_E', 'V5_S', 'V5_E', 'V6_S', 'V6_E', 'A1', 'A2', 'A3', 'T1', 'T2', 'T3', 'X', 'I2', 'V7', 'V8'], 'number'],
        ];
		return array_merge($rules,parent::rules());
    }


}