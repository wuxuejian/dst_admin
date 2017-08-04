<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car}}".
 *
 * @property string $id
 * @property string $plate_number
 * @property string $car_status
 * @property string $buy_batch_number
 * @property string $owner_id
 * @property string $operating_company_id
 * @property string $identity_name
 * @property string $identity_number
 * @property string $reg_organ
 * @property string $reg_date
 * @property string $reg_number
 * @property string $car_type
 * @property string $brand_id
 * @property string $car_model
 * @property string $car_color
 * @property string $vehicle_dentification_number
 * @property string $import_domestic
 * @property string $engine_number
 * @property string $engine_model
 * @property string $fuel_type
 * @property string $displacement
 * @property string $power
 * @property string $endurance_mileage
 * @property string $manufacturer_name
 * @property string $turn_type
 * @property string $wheel_distance_f
 * @property string $wheel_distance_b
 * @property integer $wheel_amount
 * @property string $wheel_specifications
 * @property integer $plate_amount
 * @property string $shaft_distance
 * @property integer $shaft_amount
 * @property string $outside_long
 * @property string $outside_width
 * @property string $outside_height
 * @property string $inside_long
 * @property string $inside_width
 * @property string $inside_height
 * @property string $total_mass
 * @property string $check_mass
 * @property integer $check_passenger
 * @property string $check_tow_mass
 * @property integer $cab_passenger
 * @property string $use_nature
 * @property string $gain_way
 * @property string $leave_factory_date
 * @property string $issuing_organ
 * @property string $issuing_date
 * @property string $battery_model
 * @property string $motor_model
 * @property string $motor_monitor_model
 * @property string $note
 * @property integer $is_del
 * @property string $add_time
 * @property string $add_aid
 */
class Car extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_id', 'operating_company_id', 'reg_date', 'brand_id', 'displacement', 'power', 'endurance_mileage', 'wheel_distance_f', 'wheel_distance_b', 'wheel_amount', 'plate_amount', 'shaft_distance', 'shaft_amount', 'outside_long', 'outside_width', 'outside_height', 'inside_long', 'inside_width', 'inside_height', 'total_mass', 'check_mass', 'check_passenger', 'check_tow_mass', 'cab_passenger', 'leave_factory_date', 'issuing_date', 'is_del', 'add_time', 'add_aid', 'gain_year'], 'integer'],
            [['plate_number'], 'string', 'max' => 20],
            [['car_status', 'reg_number', 'car_type', 'car_color', 'import_domestic', 'engine_model', 'fuel_type', 'turn_type', 'wheel_specifications', 'use_nature', 'gain_way'], 'string', 'max' => 50],
            [['buy_batch_number', 'identity_name', 'identity_number', 'reg_organ', 'car_model', 'vehicle_dentification_number', 'engine_number', 'manufacturer_name', 'issuing_organ'], 'string', 'max' => 100],
            [['battery_model', 'motor_model', 'motor_monitor_model'], 'string', 'max' => 30],
            [['note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plate_number' => 'Plate Number',
            'car_status' => 'Car Status',
            'buy_batch_number' => 'Buy Batch Number',
            'owner_id' => 'Owner ID',
            'operating_company_id' => 'Operating Company ID',
            'identity_name' => 'Identity Name',
            'identity_number' => 'Identity Number',
            'reg_organ' => 'Reg Organ',
            'reg_date' => 'Reg Date',
            'reg_number' => 'Reg Number',
            'car_type' => 'Car Type',
            'brand_id' => 'Brand ID',
            'car_model' => 'Car Model',
            'car_color' => 'Car Color',
            'vehicle_dentification_number' => 'Vehicle Dentification Number',
            'import_domestic' => 'Import Domestic',
            'engine_number' => 'Engine Number',
            'engine_model' => 'Engine Model',
            'fuel_type' => 'Fuel Type',
            'displacement' => 'Displacement',
            'power' => 'Power',
            'endurance_mileage' => 'Endurance Mileage',
            'manufacturer_name' => 'Manufacturer Name',
            'turn_type' => 'Turn Type',
            'wheel_distance_f' => 'Wheel Distance F',
            'wheel_distance_b' => 'Wheel Distance B',
            'wheel_amount' => 'Wheel Amount',
            'wheel_specifications' => 'Wheel Specifications',
            'plate_amount' => 'Plate Amount',
            'shaft_distance' => 'Shaft Distance',
            'shaft_amount' => 'Shaft Amount',
            'outside_long' => 'Outside Long',
            'outside_width' => 'Outside Width',
            'outside_height' => 'Outside Height',
            'inside_long' => 'Inside Long',
            'inside_width' => 'Inside Width',
            'inside_height' => 'Inside Height',
            'total_mass' => 'Total Mass',
            'check_mass' => 'Check Mass',
            'check_passenger' => 'Check Passenger',
            'check_tow_mass' => 'Check Tow Mass',
            'cab_passenger' => 'Cab Passenger',
            'use_nature' => 'Use Nature',
            'gain_way' => 'Gain Way',
            'leave_factory_date' => 'Leave Factory Date',
            'issuing_organ' => 'Issuing Organ',
            'issuing_date' => 'Issuing Date',
            'battery_model' => 'Battery Model',
            'motor_model' => 'Motor Model',
            'motor_monitor_model' => 'Motor Monitor Model',
            'note' => 'Note',
            'is_del' => 'Is Del',
            'add_time' => 'Add Time',
            'add_aid' => 'Add Aid',
            'gain_year' => 'Gain Year',
        ];
    }
}
