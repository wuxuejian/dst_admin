<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%battery_detect_criteria}}".
 *
 * @property string $id
 * @property string $battery_type
 * @property double $I1
 * @property double $V1_S
 * @property double $V1_E
 * @property double $V2_S
 * @property double $V2_E
 * @property double $V3_S
 * @property double $V3_E
 * @property double $Y1_S
 * @property double $Y1_E
 * @property double $Y2_S
 * @property double $Y2_E
 * @property double $Y3_S
 * @property double $Y3_E
 * @property double $V4_S
 * @property double $V4_E
 * @property double $V5_S
 * @property double $V5_E
 * @property double $V6_S
 * @property double $V6_E
 * @property double $A1
 * @property double $A2
 * @property double $A3
 * @property double $T1
 * @property double $T2
 * @property double $T3
 * @property double $X
 * @property double $I2
 * @property double $V7
 * @property double $V8
 * @property string $create_time
 * @property string $creator_id
 * @property integer $is_del
 */
class BatteryDetectCriteria extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%battery_detect_criteria}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['I1', 'V1_S', 'V1_E', 'V2_S', 'V2_E', 'V3_S', 'V3_E', 'Y1_S', 'Y1_E', 'Y2_S', 'Y2_E', 'Y3_S', 'Y3_E', 'V4_S', 'V4_E', 'V5_S', 'V5_E', 'V6_S', 'V6_E', 'A1', 'A2', 'A3', 'T1', 'T2', 'T3', 'X', 'I2', 'V7', 'V8'], 'number'],
            [['create_time'], 'safe'],
            [['creator_id', 'is_del'], 'integer'],
            [['battery_type'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键，电池衰减检测标准表',
            'battery_type' => '电池类型',
            'I1' => '充电电流阀值I1',
            'V1_S' => '单体电池电压平均值范围V1—开始',
            'V1_E' => '单体电池电压平均值范围V1-结束',
            'V2_S' => '单体电池电压平均值范围V2—开始',
            'V2_E' => '单体电池电压平均值范围V2-结束',
            'V3_S' => '单体电池电压平均值范围V3—开始',
            'V3_E' => '单体电池电压平均值范围V3-结束',
            'Y1_S' => 'SOC区间范围Y1—开始',
            'Y1_E' => 'SOC区间范围Y1-结束',
            'Y2_S' => 'SOC区间范围Y2—开始',
            'Y2_E' => 'SOC区间范围Y2-结束',
            'Y3_S' => 'SOC区间范围Y3—开始',
            'Y3_E' => 'SOC区间范围Y3-结束',
            'V4_S' => '单体电池电压平均值范围V4—开始',
            'V4_E' => '单体电池电压平均值范围V4-结束',
            'V5_S' => '单体电池电压平均值范围V5—开始',
            'V5_E' => '单体电池电压平均值范围V5-结束',
            'V6_S' => '单体电池电压平均值范围V6—开始',
            'V6_E' => '单体电池电压平均值范围V6-结束',
            'A1' => '单体最高最低压差值A1',
            'A2' => '单体最高最低压差值A2',
            'A3' => '单体最高最低压差值A3',
            'T1' => '判定开始充电时间值T1',
            'T2' => '判定开始充电时间值T2',
            'T3' => '判定开始充电时间值T3',
            'X' => 'SOC容量偏差百分比X',
            'I2' => '充电电流阀值I2',
            'V7' => '单体电池电压平均值V7',
            'V8' => '单体电池电压平均值V8',
            'create_time' => '创建时间',
            'creator_id' => '创建人员id',
            'is_del' => '是否已删除',
        ];
    }
}
