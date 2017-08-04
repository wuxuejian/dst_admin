<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%charge_card}}".
 *
 * @property string $cc_id
 * @property string $cc_code
 * @property string $cc_type
 * @property string $cc_status
 * @property double $cc_current_money
 * @property integer $cc_holder_id
 * @property string $cc_start_date
 * @property string $cc_end_date
 * @property string $cc_mark
 * @property string $cc_create_time
 * @property string $cc_creator_id
 * @property string $recharge_times
 * @property integer $cc_is_del
 */
class ChargeCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%charge_card}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cc_current_money'], 'number'],
            [['cc_holder_id', 'cc_creator_id', 'recharge_times', 'cc_is_del'], 'integer'],
            [['cc_start_date', 'cc_end_date', 'cc_create_time'], 'safe'],
            [['cc_code'], 'string', 'max' => 50],
            [['cc_type', 'cc_status'], 'string', 'max' => 30],
            [['cc_mark'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cc_id' => 'Cc ID',
            'cc_code' => 'Cc Code',
            'cc_type' => 'Cc Type',
            'cc_status' => 'Cc Status',
            'cc_current_money' => 'Cc Current Money',
            'cc_holder_id' => 'Cc Holder ID',
            'cc_start_date' => 'Cc Start Date',
            'cc_end_date' => 'Cc End Date',
            'cc_mark' => 'Cc Mark',
            'cc_create_time' => 'Cc Create Time',
            'cc_creator_id' => 'Cc Creator ID',
            'recharge_times' => 'Recharge Times',
            'cc_is_del' => 'Cc Is Del',
        ];
    }
}
