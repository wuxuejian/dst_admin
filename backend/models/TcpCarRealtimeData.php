<?php

namespace backend\models;
class TcpCarRealtimeData extends \common\models\TcpCarRealtimeData
{
    public function attributeLabels()
    {
        return [
            'car_vin' => '车辆vin',
            'data_source'=> '数据来源',
            'collection_datetime' => '数据采集时间',
            'update_datetime' => '系统更新时间',
            'ignition_datetime' => '点火时间',
            'flameout_datetime' => '熄火时间',
            'total_driving_mileage' => '累计行驶里程',
            'position_effective' => '定位有效',
            'latitude_type' => '纬度类型',
            'longitude_type' => '经度类型',
            'latitude_value' => '纬度值',
            'longitude_value' => '经度值',
            'speed' => '车辆速度',
            'direction' => '方向',
            'gear' => '档位',
            'accelerator_pedal' => '加速踏板行程',
            'brake_pedal_distance' => '制动踏板行程',
            'moter_controller_temperature' => '电机控制器温度',
            'moter_speed' => '电机转速',
            'moter_temperature' => '电机温度',
            'moter_voltage' => '电机电压',
            'moter_current' => '电机电流',
            'moter_generatrix_current' => '电机主线电流',
            'air_condition_temperature' => '空调设定温度',
            'brake_pedal_status' => '制动踏板状态',
            'power_system_ready' => '动力系统就绪',
            'emergency_electric_request' => '紧急下电请求',
            'car_current_status' => '车辆状态',
            'battery_package_voltage' => '电池包电压',
            'battery_package_total_voltage' => '电池总电压',
            'battery_package_temperature' => '电池温度数据',
            'battery_package_current' => '电池包电流',
            'battery_package_soc' => 'soc',
            'battery_package_power' => '电池剩余能量',
            'battery_package_hv_serial_num' => '高压电池包号',
            'battery_single_hv_serial_num' => '高压电池号',
            'battery_single_hv_value' => '电压最高值',
            'battery_package_lv_serial_num' => '低压电池包号',
            'battery_single_lv_serial_num' => '低压电池号',
            'battery_single_lv_value' => '电压最低值',
            'battery_package_ht_serial_num' => '高温电池包号',
            'battery_single_ht_serial_num' => '高温电池号',
            'battery_single_ht_value' => '最高温度值',
            'battery_package_lt_serial_num' => '低温电池包号',
            'battery_single_lt_serial_num' => '低温电池号',
            'battery_single_lt_value' => '最低温度值',
            'battery_package_resistance_value' => '绝缘电阻值',
            'battery_package_equilibria_active' => '均衡激活',
            'battery_package_fuel_consumption' => '液体燃料消耗',
        ];
    }
}
