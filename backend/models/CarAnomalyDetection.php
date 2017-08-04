<?php
namespace backend\models;
class CarAnomalyDetection extends \common\models\CarAnomalyDetection
{
    /**
     * 车辆报警类型
     */
    public static $alertType = [
        'total_vol'=>'总电压报警',
        'single_vol'=>'电池单体电压报警',
        'single_vol_diff'=>'电池压差报警',
        'discharge_current'=>'放电电流报警',
        'charge_current'=>'充电电流报警',
        'insulation'=>'绝缘故障报警',
        'package_tem'=>'电池包温度报警',
        'package_tem_change'=>'电池温升报警',
        'bms_auto_exam'=>'BMS自检报警',
        'pole_communication'=>'电桩通讯报警'
    ];
    public function getCar()
    {
        return $this->hasOne(Car::className(),['vehicle_dentification_number'=>'car_vin']);
    }
}
