<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cs_tcp_car".
 *
 * @property string $id
 * @property string $data_source
 * @property string $car_vin
 * @property string $reg_time
 * @property integer $car_type
 * @property string $car_model
 * @property integer $storage_type
 * @property integer $motor_type
 * @property integer $motor_power
 * @property string $motor_speed
 * @property string $motor_torque
 * @property integer $motor_num
 * @property integer $motor_position
 * @property integer $motor_cooling_type
 * @property string $car_mileage
 * @property integer $car_maximum_speed
 * @property string $reg_number
 * @property string $plate_number
 * @property string $terminal_manufactor
 * @property string $terminal_number
 * @property string $terminal_serial_number
 */
class TcpCar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_tcp_car';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db1');
    }

//¹ØÁª TcpCarRealtimeData
	public function getTcpCarRealtimeData(){
        return $this->hasOne(TcpCarRealtimeData::className(),[
            'car_vin'=>'id'
        ]);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data_source'], 'string'],
            [['reg_time', 'car_type', 'storage_type', 'motor_type', 'motor_power', 'motor_speed', 'motor_torque', 'motor_num', 'motor_position', 'motor_cooling_type', 'car_mileage', 'car_maximum_speed', 'reg_number', 'terminal_serial_number'], 'integer'],
            [['car_vin'], 'string', 'max' => 17],
            [['car_model', 'plate_number'], 'string', 'max' => 20],
            [['terminal_manufactor'], 'string', 'max' => 4],
            [['terminal_number'], 'string', 'max' => 6],
            [['car_vin'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data_source' => 'Data Source',
            'car_vin' => 'Car Vin',
            'reg_time' => 'Reg Time',
            'car_type' => 'Car Type',
            'car_model' => 'Car Model',
            'storage_type' => 'Storage Type',
            'motor_type' => 'Motor Type',
            'motor_power' => 'Motor Power',
            'motor_speed' => 'Motor Speed',
            'motor_torque' => 'Motor Torque',
            'motor_num' => 'Motor Num',
            'motor_position' => 'Motor Position',
            'motor_cooling_type' => 'Motor Cooling Type',
            'car_mileage' => 'Car Mileage',
            'car_maximum_speed' => 'Car Maximum Speed',
            'reg_number' => 'Reg Number',
            'plate_number' => 'Plate Number',
            'terminal_manufactor' => 'Terminal Manufactor',
            'terminal_number' => 'Terminal Number',
            'terminal_serial_number' => 'Terminal Serial Number',
        ];
    }
}
