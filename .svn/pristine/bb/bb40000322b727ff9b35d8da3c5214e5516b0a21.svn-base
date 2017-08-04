<?php
/**
 * 车辆故障处理进度模型
 */
namespace backend\models;
class CarFaultDisposeProgress extends \common\models\CarFaultDisposeProgress
{


    public function getAdmin(){
        return $this->hasOne(Admin::className(),[
            'id'=>'creator_id',
        ]);
    }
    
    public function rules()
    {
        $rules = [];
        // $rules[] = ['car_id','checkCarId','skipOnEmpty'=>false];
        // $rules[] = [['status'],'checkConfig','skipOnEmpty'=>false];
        return array_merge($rules,parent::rules());
    }
	

	
}	
