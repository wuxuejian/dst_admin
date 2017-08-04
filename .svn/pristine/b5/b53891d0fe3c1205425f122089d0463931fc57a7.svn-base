<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_contract_approval".
 *
 * @property integer $id
 * @property integer $contract_type
 * @property integer $contract_format
 * @property string $contract_name
 * @property string $contract_no
 * @property string $customer_company_name
 * @property string $customer_contact_name
 * @property string $customer_contact_tel
 * @property string $contract_cruces
 * @property integer $money_type
 * @property string $money
 * @property integer $business_type
 * @property string $business_time
 * @property string $business_way
 * @property string $oper_name
 * @property integer $oper_department_id
 * @property string $oper_tel
 * @property integer $contract_num
 * @property string $approval_start_time
 * @property string $approval_end_time
 * @property string $contract_url
 * @property integer $is_del
 * @property integer $is_cancel
 */
class ContractApproval extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_contract_approval';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contract_type', 'contract_format', 'money_type', 'business_type', 'oper_department_id', 'contract_num', 'is_del', 'is_cancel'], 'integer'],
            [['business_time', 'approval_start_time', 'approval_end_time'], 'safe'],
            [['contract_name', 'customer_company_name', 'contract_url'], 'string', 'max' => 100],
            [['contract_no', 'business_way', 'oper_name'], 'string', 'max' => 50],
            [['customer_contact_name'], 'string', 'max' => 255],
            [['customer_contact_tel', 'oper_tel'], 'string', 'max' => 20],
            [['contract_cruces'], 'string', 'max' => 500],
            [['money'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contract_type' => 'Contract Type',
            'contract_format' => 'Contract Format',
            'contract_name' => 'Contract Name',
            'contract_no' => 'Contract No',
            'customer_company_name' => 'Customer Company Name',
            'customer_contact_name' => 'Customer Contact Name',
            'customer_contact_tel' => 'Customer Contact Tel',
            'contract_cruces' => 'Contract Cruces',
            'money_type' => 'Money Type',
            'money' => 'Money',
            'business_type' => 'Business Type',
            'business_time' => 'Business Time',
            'business_way' => 'Business Way',
            'oper_name' => 'Oper Name',
            'oper_department_id' => 'Oper Department ID',
            'oper_tel' => 'Oper Tel',
            'contract_num' => 'Contract Num',
            'approval_start_time' => 'Approval Start Time',
            'approval_end_time' => 'Approval End Time',
            'contract_url' => 'Contract Url',
            'is_del' => 'Is Del',
            'is_cancel' => 'Is Cancel',
        ];
    }
}
