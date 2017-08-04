<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%battery_correct_verify}}".
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
 * @property string $verify_res
 * @property string $verify_time
 * @property string $verify_aid
 * @property string $process_time
 * @property string $process_status
 * @property string $process_way
 * @property string $recheck_res
 * @property string $recheck_time
 * @property string $recheck_aid
 */
class BatteryCorrectVerify extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%battery_correct_verify}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['verify_time', 'process_time', 'recheck_time'], 'safe'],
            [['verify_aid', 'recheck_aid'], 'integer'],
            [['car_vin', 'battery_type', 'soc_deviation_status', 'soc_deviation_val', 'soc_deviation_res', 'capacitance_attenuation_status', 'capacitance_attenuation_res', 'voltage_deviation_val', 'capacitance_deviation_status', 'capacitance_deviation_res', 'verify_res', 'process_status', 'recheck_res'], 'string', 'max' => 30],
            [['process_way'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键，电池验证修正结果表',
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
            'verify_res' => '验证修正结果（仅当3项检测都正常才显示正常）',
            'verify_time' => '验证修正时间',
            'verify_aid' => '验证人员id',
            'process_time' => '处理时间',
            'process_status' => '处理状态（已处理、未处理、待跟进）',
            'process_way' => '处理方法',
            'recheck_res' => '复检结果（仅当3项检测都正常才显示正常）',
            'recheck_time' => '复检时间',
            'recheck_aid' => '复检人员id',
        ];
    }
}
