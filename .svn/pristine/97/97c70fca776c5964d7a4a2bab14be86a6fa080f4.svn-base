<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%motor}}".
 *
 * @property string $id
 * @property string $motor_model
 * @property string $encoder
 * @property string $cooling_type
 * @property string $motor_maker
 * @property double $rated_power
 * @property double $rated_speed
 * @property double $rated_frequency
 * @property double $rated_current
 * @property double $rated_torque
 * @property double $rated_voltage
 * @property double $peak_power
 * @property double $peak_speed
 * @property double $peak_frequency
 * @property double $peak_current
 * @property double $peak_torque
 * @property double $polar_logarithm
 * @property string $create_time
 * @property string $creator_id
 * @property integer $is_del
 */
class Motor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%motor}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rated_power', 'rated_speed', 'rated_frequency', 'rated_current', 'rated_torque', 'rated_voltage', 'peak_power', 'peak_speed', 'peak_frequency', 'peak_current', 'peak_torque', 'polar_logarithm'], 'number'],
            [['create_time'], 'safe'],
            [['creator_id', 'is_del'], 'integer'],
            [['motor_model', 'encoder', 'cooling_type', 'motor_maker'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键，电机表',
            'motor_model' => '电机型号',
            'encoder' => '编码器：旋变',
            'cooling_type' => '冷却方式：水冷',
            'motor_maker' => '电机生产厂家',
            'rated_power' => '额定功率',
            'rated_speed' => '额定转速',
            'rated_frequency' => '额定频率',
            'rated_current' => '额定电流',
            'rated_torque' => '额定转矩',
            'rated_voltage' => '额定电压',
            'peak_power' => '峰值功率',
            'peak_speed' => '峰值转速',
            'peak_frequency' => '峰值频率',
            'peak_current' => '峰值电流',
            'peak_torque' => '峰值转矩',
            'polar_logarithm' => '极对数',
            'create_time' => '创建时间',
            'creator_id' => '创建人员id',
            'is_del' => '是否已删除',
        ];
    }
}
