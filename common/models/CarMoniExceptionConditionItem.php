<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car_moni_exception_condition_item}}".
 *
 * @property integer $id
 * @property string $battery_type
 * @property string $alert_type
 * @property string $max_min
 * @property double $set_value
 * @property integer $alert_level
 * @property integer $alert_dispose
 * @property string $alert_content
 * @property integer $interval_time
 * @property integer $in_use
 */
class CarMoniExceptionConditionItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_moni_exception_condition_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['max_min'], 'string'],
            [['set_value'], 'number'],
            [['alert_level', 'alert_dispose', 'interval_time', 'in_use'], 'integer'],
            [['battery_type'], 'string', 'max' => 30],
            [['alert_type'], 'string', 'max' => 100],
            [['alert_content'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'battery_type' => 'Battery Type',
            'alert_type' => 'Alert Type',
            'max_min' => 'Max Min',
            'set_value' => 'Set Value',
            'alert_level' => 'Alert Level',
            'alert_dispose' => 'Alert Dispose',
            'alert_content' => 'Alert Content',
            'interval_time' => 'Interval Time',
            'in_use' => 'In Use',
        ];
    }
}
