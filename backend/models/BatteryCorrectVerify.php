<?php
/**
 * 电池验证修正结果表 模型
 */
namespace backend\models;
use yii;

class BatteryCorrectVerify extends \common\models\BatteryCorrectVerify
{

    public function rules()
    {
        $rules = [
            [['process_way'],'trim'],
            [['process_way'],'filter','filter'=>'htmlspecialchars'],
        ];
        return array_merge($rules,parent::rules());
    }


    /**
     * 关联【车辆】表
     */
    public function getCar()
    {
        return $this->hasOne(Car::className(), [
            'vehicle_dentification_number' => 'car_vin'
        ]);
    }

    /**
     * 关联【人员】表
     */
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(), [
            'id' => 'verifier_id'
        ]);
    }




}
