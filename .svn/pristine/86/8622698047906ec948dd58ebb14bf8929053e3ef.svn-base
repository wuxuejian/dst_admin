<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cs_company_sms_notify_log".
 *
 * @property integer $id
 * @property string $company_number
 * @property string $company_name
 * @property integer $car_num
 * @property double $amount
 * @property string $delivery_time
 * @property string $keeper_name
 * @property string $keeper_mobile
 * @property string $send_time
 * @property string $oper_user
 */
class CompanySmsNotifyLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_company_sms_notify_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['car_num'], 'integer'],
            [['amount'], 'number'],
            [['delivery_time', 'send_time'], 'safe'],
            [['company_number', 'keeper_name', 'oper_user'], 'string', 'max' => 50],
            [['company_name'], 'string', 'max' => 100],
            [['keeper_mobile'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_number' => 'Company Number',
            'company_name' => 'Company Name',
            'car_num' => 'Car Num',
            'amount' => 'Amount',
            'delivery_time' => 'Delivery Time',
            'keeper_name' => 'Keeper Name',
            'keeper_mobile' => 'Keeper Mobile',
            'send_time' => 'Send Time',
            'oper_user' => 'Oper User',
        ];
    }
}
