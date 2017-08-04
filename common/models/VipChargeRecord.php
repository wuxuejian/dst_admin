<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vip_charge_record}}".
 *
 * @property string $id
 * @property string $number
 * @property string $vip_id
 * @property string $pole_id
 * @property string $measuring_point
 * @property integer $last_gun_status
 * @property string $write_datetime
 * @property string $start_status
 * @property string $start_fail_reason
 * @property string $end_datetime
 * @property string $end_status
 * @property double $c_amount
 * @property string $pay_status
 * @property string $pay_type
 * @property string $pay_datetime
 * @property string $platform_trade_no
 * @property string $last_notify_datetime
 * @property string $fm_id
 * @property string $fm_charge_no
 * @property string $fm_start_id
 * @property string $fm_end_id
 */
class VipChargeRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip_charge_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vip_id', 'pole_id', 'measuring_point', 'last_gun_status', 'fm_id', 'fm_start_id', 'fm_end_id'], 'integer'],
            [['write_datetime', 'end_datetime', 'pay_datetime', 'last_notify_datetime'], 'safe'],
            [['start_status', 'end_status', 'pay_status', 'pay_type'], 'string'],
            [['c_amount'], 'number'],
            [['number', 'platform_trade_no', 'fm_charge_no'], 'string', 'max' => 50],
            [['start_fail_reason'], 'string', 'max' => 255],
            [['number'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'vip_id' => 'Vip ID',
            'pole_id' => 'Pole ID',
            'measuring_point' => 'Measuring Point',
            'last_gun_status' => 'Last Gun Status',
            'write_datetime' => 'Write Datetime',
            'start_status' => 'Start Status',
            'start_fail_reason' => 'Start Fail Reason',
            'end_datetime' => 'End Datetime',
            'end_status' => 'End Status',
            'c_amount' => 'C Amount',
            'pay_status' => 'Pay Status',
            'pay_type' => 'Pay Type',
            'pay_datetime' => 'Pay Datetime',
            'platform_trade_no' => 'Platform Trade No',
            'last_notify_datetime' => 'Last Notify Datetime',
            'fm_id' => 'Fm ID',
            'fm_charge_no' => 'Fm Charge No',
            'fm_start_id' => 'Fm Start ID',
            'fm_end_id' => 'Fm End ID',
        ];
    }
}
