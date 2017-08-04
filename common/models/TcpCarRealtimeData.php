<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%tcp_car_realtime_data}}".
 *
 * @property string $car_vin
 * @property string $data_source
 * @property string $collection_datetime
 * @property string $update_datetime
 * @property string $ignition_datetime
 * @property string $flameout_datetime
 * @property double $total_driving_mileage
 * @property integer $position_effective
 * @property integer $latitude_type
 * @property integer $longitude_type
 * @property double $longitude_value
 * @property double $latitude_value
 * @property double $speed
 * @property integer $direction
 * @property string $gear
 * @property integer $accelerator_pedal
 * @property integer $brake_pedal_distance
 * @property integer $moter_controller_temperature
 * @property integer $moter_speed
 * @property integer $moter_temperature
 * @property double $moter_voltage
 * @property double $moter_current
 * @property double $moter_generatrix_current
 * @property integer $air_condition_temperature
 * @property integer $brake_pedal_status
 * @property integer $power_system_ready
 * @property integer $emergency_electric_request
 * @property integer $car_current_status
 * @property string $battery_package_voltage
 * @property double $battery_package_total_voltage
 * @property string $battery_package_temperature
 * @property double $battery_package_current
 * @property double $battery_package_soc
 * @property double $battery_package_power
 * @property integer $battery_package_hv_serial_num
 * @property integer $battery_single_hv_serial_num
 * @property double $battery_single_hv_value
 * @property integer $battery_package_lv_serial_num
 * @property integer $battery_single_lv_serial_num
 * @property double $battery_single_lv_value
 * @property integer $battery_package_ht_serial_num
 * @property integer $battery_single_ht_serial_num
 * @property integer $battery_single_ht_value
 * @property integer $battery_package_lt_serial_num
 * @property integer $battery_single_lt_serial_num
 * @property integer $battery_single_lt_value
 * @property integer $battery_package_resistance_value
 * @property integer $battery_package_equilibria_active
 * @property integer $battery_package_fuel_consumption
 * @property string $data_hex
 */
class TcpCarRealtimeData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tcp_car_realtime_data}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db1');
    }

//¹ØÁª tcp_car
public function getCar()
    {
        return $this->hasOne(Car::className(),[
            'id'=>'car_vin'
        ]);
    }

    /**
     * @inheritdoc
    
	 */
    public function rules()
    {
        return [
            [['car_vin'], 'required'],
            [['data_source', 'battery_package_voltage', 'battery_package_temperature', 'data_hex'], 'string'],
            [['collection_datetime', 'update_datetime', 'ignition_datetime', 'flameout_datetime', 'position_effective', 'latitude_type', 'longitude_type', 'direction', 'accelerator_pedal', 'brake_pedal_distance', 'moter_controller_temperature', 'moter_speed', 'moter_temperature', 'air_condition_temperature', 'brake_pedal_status', 'power_system_ready', 'emergency_electric_request', 'car_current_status', 'battery_package_hv_serial_num', 'battery_single_hv_serial_num', 'battery_package_lv_serial_num', 'battery_single_lv_serial_num', 'battery_package_ht_serial_num', 'battery_single_ht_serial_num', 'battery_single_ht_value', 'battery_package_lt_serial_num', 'battery_single_lt_serial_num', 'battery_single_lt_value', 'battery_package_resistance_value', 'battery_package_equilibria_active', 'battery_package_fuel_consumption'], 'integer'],
            [['total_driving_mileage', 'longitude_value', 'latitude_value', 'speed', 'moter_voltage', 'moter_current', 'moter_generatrix_current', 'battery_package_total_voltage', 'battery_package_current', 'battery_package_soc', 'battery_package_power', 'battery_single_hv_value', 'battery_single_lv_value'], 'number'],
            [['car_vin'], 'string', 'max' => 17],
            [['gear'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'car_vin' => 'Car Vin',
            'data_source' => 'Data Source',
            'collection_datetime' => 'Collection Datetime',
            'update_datetime' => 'Update Datetime',
            'ignition_datetime' => 'Ignition Datetime',
            'flameout_datetime' => 'Flameout Datetime',
            'total_driving_mileage' => 'Total Driving Mileage',
            'position_effective' => 'Position Effective',
            'latitude_type' => 'Latitude Type',
            'longitude_type' => 'Longitude Type',
            'longitude_value' => 'Longitude Value',
            'latitude_value' => 'Latitude Value',
            'speed' => 'Speed',
            'direction' => 'Direction',
            'gear' => 'Gear',
            'accelerator_pedal' => 'Accelerator Pedal',
            'brake_pedal_distance' => 'Brake Pedal Distance',
            'moter_controller_temperature' => 'Moter Controller Temperature',
            'moter_speed' => 'Moter Speed',
            'moter_temperature' => 'Moter Temperature',
            'moter_voltage' => 'Moter Voltage',
            'moter_current' => 'Moter Current',
            'moter_generatrix_current' => 'Moter Generatrix Current',
            'air_condition_temperature' => 'Air Condition Temperature',
            'brake_pedal_status' => 'Brake Pedal Status',
            'power_system_ready' => 'Power System Ready',
            'emergency_electric_request' => 'Emergency Electric Request',
            'car_current_status' => 'Car Current Status',
            'battery_package_voltage' => 'Battery Package Voltage',
            'battery_package_total_voltage' => 'Battery Package Total Voltage',
            'battery_package_temperature' => 'Battery Package Temperature',
            'battery_package_current' => 'Battery Package Current',
            'battery_package_soc' => 'Battery Package Soc',
            'battery_package_power' => 'Battery Package Power',
            'battery_package_hv_serial_num' => 'Battery Package Hv Serial Num',
            'battery_single_hv_serial_num' => 'Battery Single Hv Serial Num',
            'battery_single_hv_value' => 'Battery Single Hv Value',
            'battery_package_lv_serial_num' => 'Battery Package Lv Serial Num',
            'battery_single_lv_serial_num' => 'Battery Single Lv Serial Num',
            'battery_single_lv_value' => 'Battery Single Lv Value',
            'battery_package_ht_serial_num' => 'Battery Package Ht Serial Num',
            'battery_single_ht_serial_num' => 'Battery Single Ht Serial Num',
            'battery_single_ht_value' => 'Battery Single Ht Value',
            'battery_package_lt_serial_num' => 'Battery Package Lt Serial Num',
            'battery_single_lt_serial_num' => 'Battery Single Lt Serial Num',
            'battery_single_lt_value' => 'Battery Single Lt Value',
            'battery_package_resistance_value' => 'Battery Package Resistance Value',
            'battery_package_equilibria_active' => 'Battery Package Equilibria Active',
            'battery_package_fuel_consumption' => 'Battery Package Fuel Consumption',
            'data_hex' => 'Data Hex',
        ];
    }
}
