<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vip_recharge_record}}".
 *
 * @property string $id
 * @property string $trade_no
 * @property string $vip_id
 * @property double $total_fee
 * @property string $request_datetime
 * @property string $pay_way
 * @property string $last_notify_datetime
 * @property string $trade_status
 * @property string $gmt_create_datetime
 * @property string $gmt_payment_datetime
 */
class VipRechargeRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip_recharge_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vip_id', 'request_datetime', 'last_notify_datetime', 'gmt_create_datetime', 'gmt_payment_datetime'], 'integer'],
            [['total_fee'], 'number'],
            [['pay_way', 'trade_status'], 'string'],
            [['trade_no'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'trade_no' => 'Trade No',
            'vip_id' => 'Vip ID',
            'total_fee' => 'Total Fee',
            'request_datetime' => 'Request Datetime',
            'pay_way' => 'Pay Way',
            'last_notify_datetime' => 'Last Notify Datetime',
            'trade_status' => 'Trade Status',
            'gmt_create_datetime' => 'Gmt Create Datetime',
            'gmt_payment_datetime' => 'Gmt Payment Datetime',
        ];
    }
}
