<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_inspection_all_car".
 *
 * @property integer $id
 * @property integer $car_id
 * @property integer $inspection_result
 * @property integer $is_put
 * @property string $car_note
 * @property string $inspection_id
 */
class InspectionAllCar extends \yii\db\ActiveRecord
{
	public $vehicle_dentification_number = '';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_inspection_all_car';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        	['vehicle_dentification_number','checkVehicleDentificationNumber','skipOnEmpty'=>false],
            [['car_id', 'inspection_result', 'is_put'], 'integer'],
            [['car_note'], 'string', 'max' => 500],
            [['inspection_id'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_id' => 'Car ID',
            'inspection_result' => 'Inspection Result',
            'is_put' => 'Is Put',
            'car_note' => 'Car Note',
            'inspection_id' => 'Inspection ID',
        ];
    }
    
    public function scenarios()
    {
    	return [
    	'default'=>['vehicle_dentification_number','inspection_result','is_put','car_note','inspection_id'],
    	'edit'=>['vehicle_dentification_number','inspection_result','is_put','car_note']
    	];
    }
    
    public function checkVehicleDentificationNumber()
    {
    	//添加车辆到合同是要验证该车辆是否可用
    	if(empty($this->vehicle_dentification_number)){
    		$this->addError('vehicle_dentification_number','车架号不能为空！');
    		return false;
    	}
    	$car = Car::find()->select(['id'])
    	->where(['vehicle_dentification_number'=>$this->vehicle_dentification_number])
    	->andWhere(['car_status'=>'STOCK'])
    	->asArray()
    	->one();
    	if(!$car){
    		$this->addError('vehicle_dentification_number','车辆错误！');
    		return false;
    	}
    	$this->car_id = $car['id'];
    	return true;
    }
}
