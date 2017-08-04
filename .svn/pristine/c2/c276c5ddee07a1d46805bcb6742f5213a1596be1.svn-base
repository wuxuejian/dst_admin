<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vip_promotion_let}}".
 *
 * @property string $id
 * @property string $renter_id
 * @property string $amount
 * @property string $contract_no
 * @property string $sign_date
 * @property string $operator
 * @property string $mark
 * @property string $create_time
 * @property integer $creator_id
 * @property string $inviter_invite_code
 * @property string $is_settle
 */
class VipPromotionLet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip_promotion_let}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['renter_id', 'amount', 'creator_id'], 'integer'],
            [['sign_date', 'create_time'], 'safe'],
            [['contract_no'], 'string', 'max' => 50],
            [['operator'], 'string', 'max' => 30],
            [['mark'], 'string', 'max' => 200],
            [['inviter_invite_code'], 'string', 'max' => 8],
            [['is_settle'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID主键',
            'renter_id' => '租车人ID',
            'amount' => '租车数量',
            'contract_no' => '合同编号',
            'sign_date' => '合同签订日期',
            'operator' => '合同受理人',
            'mark' => '备注',
            'create_time' => '系统记录时间',
            'creator_id' => '系统登记人员',
            'inviter_invite_code' => '邀请人的邀请码（用以关联查邀请人信息）',
            'is_settle' => '奖金是否已结算给邀请人',
        ];
    }
}
