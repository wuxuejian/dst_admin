<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vip_promotion_settle}}".
 *
 * @property string $id
 * @property string $inviter_id
 * @property double $settled_money
 * @property string $settled_letId
 * @property string $create_time
 * @property string $creator_id
 * @property string $applyCash_id
 */
class VipPromotionSettle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip_promotion_settle}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inviter_id', 'creator_id', 'applyCash_id'], 'integer'],
            [['settled_money'], 'number'],
            [['create_time'], 'safe'],
            [['settled_letId'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID主键',
            'inviter_id' => '邀请人ID',
            'settled_money' => '结算金额',
            'settled_letId' => '结算的租车记录id',
            'create_time' => '操作时间',
            'creator_id' => '操作人员',
            'applyCash_id' => '大于0时表示提现申请记录Id',
        ];
    }
}
