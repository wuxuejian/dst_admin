<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cs_car_type".
 *
 * @property string $id
 * @property string $car_type
 * @property string $brand_id
 * @property string $car_model
 * @property string $engine_model
 * @property string $fuel_type
 * @property string $displacement
 * @property string $peak_power
 * @property string $rated_power
 * @property string $endurance_mileage
 * @property string $manufacturer_name
 * @property string $wheel_distance_f
 * @property string $wheel_distance_b
 * @property integer $wheel_amount
 * @property string $wheel_specifications
 * @property string $shaft_distance
 * @property integer $shaft_amount
 * @property string $outside_long
 * @property string $outside_width
 * @property string $outside_height
 * @property string $total_mass
 * @property string $check_mass
 * @property integer $cab_passenger
 * @property string $use_nature
 * @property integer $is_del
 * @property string $add_time
 * @property string $add_aid
 * @property string $cubage
 * @property double $approach_angle
 * @property double $departure_angle
 * @property string $empty_mass
 * @property string $power_battery_capacity
 * @property string $power_battery_manufacturer
 * @property string $drive_motor_manufacturer
 * @property string $max_speed
 * @property double $fast_charging_time
 * @property double $slow_charging_time
 * @property string $charging_type
 * @property string $car_front_img
 * @property string $car_left_img
 * @property string $car_right_img
 * @property string $car_tail_img
 * @property string $car_control_img
 * @property string $car_full_img
 */
class CarType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_car_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'displacement', 'peak_power', 'rated_power', 'endurance_mileage', 'wheel_distance_f', 'wheel_distance_b', 'shaft_distance',  'outside_long', 'outside_width', 'outside_height', 'total_mass', 'check_mass', 'cab_passenger', 'is_del', 'add_time', 'add_aid', 'empty_mass', 'power_battery_capacity', 'max_speed','inside_long','inside_width','inside_height'], 'integer'],
            [['approach_angle', 'departure_angle', 'fast_charging_time', 'cubage', 'slow_charging_time'], 'number'],
            [['car_type', 'engine_model', 'fuel_type', 'wheel_specifications'], 'string', 'max' => 50],
            [['car_model','car_model_name', 'manufacturer_name', 'power_battery_manufacturer', 'drive_motor_manufacturer', 'charging_type'], 'string', 'max' => 100],
            [['car_front_img', 'car_left_img', 'car_right_img', 'car_tail_img', 'car_control_img', 'car_full_img'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_type' => 'Car Type',
            'brand_id' => 'Brand ID',
            'car_model' => 'Car Model',
            'engine_model' => 'Engine Model',
            'fuel_type' => 'Fuel Type',
            'displacement' => 'Displacement',
            'peak_power' => 'Peak Power',
            'rated_power' => 'Rated Power',
            'endurance_mileage' => 'Endurance Mileage',
            'manufacturer_name' => 'Manufacturer Name',
            'wheel_distance_f' => 'Wheel Distance F',
            'wheel_distance_b' => 'Wheel Distance B',
            'wheel_amount' => 'Wheel Amount',
            'wheel_specifications' => 'Wheel Specifications',
            'shaft_distance' => 'Shaft Distance',
            //'shaft_amount' => 'Shaft Amount',
            'outside_long' => 'Outside Long',
            'outside_width' => 'Outside Width',
            'outside_height' => 'Outside Height',
            'total_mass' => 'Total Mass',
            'check_mass' => 'Check Mass',
            'cab_passenger' => 'Cab Passenger',
            //'use_nature' => 'Use Nature',
            'is_del' => 'Is Del',
            'add_time' => 'Add Time',
            'add_aid' => 'Add Aid',
            'cubage' => 'Cubage',
            'approach_angle' => 'Approach Angle',
            'departure_angle' => 'Departure Angle',
            'empty_mass' => 'Empty Mass',
            'power_battery_capacity' => 'Power Battery Capacity',
            'power_battery_manufacturer' => 'Power Battery Manufacturer',
            'drive_motor_manufacturer' => 'Drive Motor Manufacturer',
            'max_speed' => 'Max Speed',
            'fast_charging_time' => 'Fast Charging Time',
            'slow_charging_time' => 'Slow Charging Time',
            'charging_type' => 'Charging Type',
            'car_front_img' => 'Car Front Img',
            'car_left_img' => 'Car Left Img',
            'car_right_img' => 'Car Right Img',
            'car_tail_img' => 'Car Tail Img',
            'car_control_img' => 'Car Control Img',
            'car_full_img' => 'Car Full Img',
            'inside_long'=>'inside_long',
            'inside_width'=>'inside_width',
            'inside_height'=>'inside_height',
            'car_model_name'=>'Car Model Name'

        ];
    }
}
