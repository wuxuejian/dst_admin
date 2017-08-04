<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car_anomaly_detection}}".
 *
 * @property integer $id
 * @property string $car_vin
 * @property string $battery_type
 * @property string $alert_type
 * @property string $max_min
 * @property integer $alert_level
 * @property integer $alert_dispose
 * @property string $alert_content
 * @property string $alert_datetime
 * @property string $alert_value
 * @property string $times
 * @property string $status
 * @property string $deal_date
 */
class CarAnomalyDetection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_anomaly_detection}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['max_min', 'status'], 'string'],
            [['alert_level', 'alert_dispose', 'times'], 'integer'],
            [['alert_datetime', 'deal_date'], 'safe'],
            [['car_vin', 'alert_value'], 'string', 'max' => 100],
            [['battery_type', 'alert_content'], 'string', 'max' => 255],
            [['alert_type'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_vin' => 'Car Vin',
            'battery_type' => 'Battery Type',
            'alert_type' => 'Alert Type',
            'max_min' => 'Max Min',
            'alert_level' => 'Alert Level',
            'alert_dispose' => 'Alert Dispose',
            'alert_content' => 'Alert Content',
            'alert_datetime' => 'Alert Datetime',
            'alert_value' => 'Alert Value',
            'times' => 'Times',
            'status' => 'Status',
            'deal_date' => 'Deal Date',
        ];
    }
}
