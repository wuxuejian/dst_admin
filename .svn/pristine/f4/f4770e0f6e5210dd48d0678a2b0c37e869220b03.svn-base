<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%charge_spots_alert_item}}".
 *
 * @property string $id
 * @property string $event_code
 * @property string $name
 * @property integer $alert_level
 * @property integer $alert_dispose
 * @property string $alert_content
 * @property integer $in_use
 */
class ChargeSpotsAlertItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%charge_spots_alert_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alert_level', 'alert_dispose', 'in_use'], 'integer'],
            [['event_code'], 'string', 'max' => 4],
            [['name', 'alert_content'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_code' => 'Event Code',
            'name' => 'Name',
            'alert_level' => 'Alert Level',
            'alert_dispose' => 'Alert Dispose',
            'alert_content' => 'Alert Content',
            'in_use' => 'In Use',
        ];
    }
}
