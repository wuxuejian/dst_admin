<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vip_promotion_apply_cash}}".
 *
 * @property string $id
 * @property string $apply_id
 * @property string $apply_date
 * @property string $pay_type
 * @property string $bank_name
 * @property string $bank_card
 * @property string $alipay_account
 * @property string $total_rent_num
 * @property double $total_reward
 * @property double $settled_reward
 * @property double $unsettled_reward
 * @property string $apply_letIds
 * @property string $settle_status
 * @property double $real_settle_money
 * @property string $real_settle_letIds
 * @property string $mark
 * @property string $create_time
 */
class VipPromotionApplyCash extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip_promotion_apply_cash}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apply_id', 'total_rent_num'], 'integer'],
            [['apply_date', 'create_time'], 'safe'],
            [['total_reward', 'settled_reward', 'unsettled_reward', 'real_settle_money'], 'number'],
            [['pay_type'], 'string', 'max' => 30],
            [['bank_name', 'bank_card', 'alipay_account'], 'string', 'max' => 50],
            [['apply_letIds', 'real_settle_letIds', 'mark'], 'string', 'max' => 200],
            [['settle_status'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID主键',
            'apply_id' => '申请人ID',
            'apply_date' => '申请日期',
            'pay_type' => '申请支付方式（银行转账、支付宝转账等）',
            'bank_name' => '银行名称',
            'bank_card' => '银行卡号',
            'alipay_account' => '支付宝账号',
            'total_rent_num' => '朋友租车总数',
            'total_reward' => '奖金总额',
            'settled_reward' => '已结算金额',
            'unsettled_reward' => '本次申请结算金额',
            'apply_letIds' => '本次申请结算的租车记录id',
            'settle_status' => '结算状态',
            'real_settle_money' => '本次实际结算金额',
            'real_settle_letIds' => '本次实际结算的租车记录id',
            'mark' => '备注',
            'create_time' => '系统记录时间',
        ];
    }
}
