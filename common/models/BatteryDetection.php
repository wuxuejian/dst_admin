<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%battery_detection}}".
 *
 * @property string $id
 * @property string $car_vin
 * @property string $battery_type
 * @property string $soc_deviation_status
 * @property string $soc_deviation_val
 * @property string $soc_deviation_res
 * @property string $capacitance_attenuation_status
 * @property string $capacitance_attenuation_res
 * @property string $voltage_deviation_val
 * @property string $capacitance_deviation_status
 * @property string $capacitance_deviation_res
 * @property string $detect_time
 * @property string $used_history_data
 * @property integer $latest_notice_id
 */
class BatteryDetection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%battery_detection}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['detect_time'], 'safe'],
            [['latest_notice_id'], 'integer'],
            [['car_vin', 'battery_type', 'soc_deviation_status', 'soc_deviation_val', 'soc_deviation_res', 'capacitance_attenuation_status', 'capacitance_attenuation_res', 'voltage_deviation_val', 'capacitance_deviation_status', 'capacitance_deviation_res'], 'string', 'max' => 30],
            [['used_history_data'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键，车辆电池衰减检测表',
            'car_vin' => '车架号',
            'battery_type' => '电池类型',
            'soc_deviation_status' => 'SOC偏移状态',
            'soc_deviation_val' => 'SOC偏移量',
            'soc_deviation_res' => 'SOC偏移判定结果',
            'capacitance_attenuation_status' => '电池容量衰减状态',
            'capacitance_attenuation_res' => '电池容量衰减判定结果',
            'voltage_deviation_val' => '压差偏移量',
            'capacitance_deviation_status' => '电池容量偏差状态',
            'capacitance_deviation_res' => '电池容量偏差判定结果',
            'detect_time' => '检测时间',
            'used_history_data' => '本次检测所依赖的历史数据（格式：数据库,数据表,记录id）',
            'latest_notice_id' => '最新修正通知id（按通知时间和更新时间降序）',
        ];
    }
}
