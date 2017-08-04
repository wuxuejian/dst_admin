<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car_let_contract_renew_record}}".
 *
 * @property string $id
 * @property string $contract_id
 * @property string $admin_id
 * @property double $should_money
 * @property double $true_money
 * @property string $cost_expire_time
 * @property string $action_time
 * @property string $note
 */
class CarLetContractRenewRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_let_contract_renew_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contract_id', 'admin_id', 'cost_expire_time', 'action_time'], 'integer'],
            [['should_money', 'true_money'], 'number'],
            [['note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contract_id' => 'Contract ID',
            'admin_id' => 'Admin ID',
            'should_money' => 'Should Money',
            'true_money' => 'True Money',
            'cost_expire_time' => 'Cost Expire Time',
            'action_time' => 'Action Time',
            'note' => 'Note',
        ];
    }
}
