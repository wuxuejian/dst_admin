<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%charge_spots_alert}}".
 *
 * @property string $id
 * @property string $station_id
 * @property string $dev_addr
 * @property integer $inner_id
 * @property integer $pole_status
 * @property string $event_code
 * @property string $alert_name
 * @property integer $alert_level
 * @property integer $alert_dispose
 * @property string $alert_content
 * @property string $happen_datetime
 * @property string $event_desc
 * @property string $times
 * @property integer $has_send_shotmsg
 * @property string $status
 * @property string $deal_datetime
 */
class ChargeSpotsAlert extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%charge_spots_alert}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['station_id', 'inner_id', 'pole_status', 'alert_level', 'alert_dispose', 'times', 'has_send_shotmsg'], 'integer'],
            [['happen_datetime', 'deal_datetime'], 'safe'],
            [['status'], 'string'],
            [['dev_addr'], 'string', 'max' => 10],
            [['event_code'], 'string', 'max' => 4],
            [['alert_name'], 'string', 'max' => 100],
            [['alert_content'], 'string', 'max' => 255],
            [['event_desc'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'station_id' => 'Station ID',
            'dev_addr' => 'Dev Addr',
            'inner_id' => 'Inner ID',
            'pole_status' => 'Pole Status',
            'event_code' => 'Event Code',
            'alert_name' => 'Alert Name',
            'alert_level' => 'Alert Level',
            'alert_dispose' => 'Alert Dispose',
            'alert_content' => 'Alert Content',
            'happen_datetime' => 'Happen Datetime',
            'event_desc' => 'Event Desc',
            'times' => 'Times',
            'has_send_shotmsg' => 'Has Send Shotmsg',
            'status' => 'Status',
            'deal_datetime' => 'Deal Datetime',
        ];
    }
}
