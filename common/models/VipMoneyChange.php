<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vip_money_change}}".
 *
 * @property string $id
 * @property string $vip_id
 * @property double $change_money
 * @property string $reason
 * @property string $systime
 * @property string $note
 */
class VipMoneyChange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip_money_change}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vip_id', 'systime'], 'integer'],
            [['change_money'], 'number'],
            [['reason', 'note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vip_id' => 'Vip ID',
            'change_money' => 'Change Money',
            'reason' => 'Reason',
            'systime' => 'Systime',
            'note' => 'Note',
        ];
    }
}
