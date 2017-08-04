<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%charge_card_recharge_record}}".
 *
 * @property string $ccrr_id
 * @property string $ccrr_code
 * @property integer $ccrr_card_id
 * @property double $ccrr_before_money
 * @property double $ccrr_recharge_money
 * @property double $ccrr_incentive_money
 * @property double $ccrr_after_money
 * @property string $ccrr_mark
 * @property string $ccrr_create_time
 * @property string $ccrr_creator_id
 * @property integer $ccrr_is_del
 */
class ChargeCardRechargeRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%charge_card_recharge_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ccrr_card_id', 'ccrr_creator_id', 'ccrr_is_del'], 'integer'],
            [['ccrr_before_money', 'ccrr_recharge_money', 'ccrr_incentive_money', 'ccrr_after_money'], 'number'],
            [['ccrr_create_time'], 'safe'],
            [['ccrr_code'], 'string', 'max' => 50],
            [['ccrr_mark'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ccrr_id' => '主键，充电卡充值记录表',
            'ccrr_code' => '充值单号',
            'ccrr_card_id' => '充电卡ID',
            'ccrr_before_money' => '充值前余额',
            'ccrr_recharge_money' => '本次充值金额',
            'ccrr_incentive_money' => '本次奖励金额',
            'ccrr_after_money' => '充值后余额',
            'ccrr_mark' => '充值备注',
            'ccrr_create_time' => '记录创建时间',
            'ccrr_creator_id' => '记录创建人id',
            'ccrr_is_del' => '记录是否已删除',
        ];
    }
}
