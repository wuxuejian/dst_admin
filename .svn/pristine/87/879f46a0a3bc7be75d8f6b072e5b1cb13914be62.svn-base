<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car_let_contract}}".
 *
 * @property string $id
 * @property string $number
 * @property string $customer_type
 * @property string $cCustomer_id
 * @property string $pCustomer_id
 * @property string $due_time
 * @property string $start_time
 * @property string $end_time
 * @property double $bail
 * @property string $cost_expire_time
 * @property string $reg_time
 * @property string $note
 * @property string $sign_date
 * @property integer $is_del
 * @property string $last_modify_datetime
 * @property string $modify_aid
 * @property string $operating_company_id
 */
class CarLetContract extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_let_contract}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cCustomer_id', 'pCustomer_id', 'due_time', 'start_time', 'end_time', 'cost_expire_time', 'reg_time', 'sign_date', 'is_del', 'last_modify_datetime', 'modify_aid', 'operating_company_id'], 'integer'],
            [['bail'], 'number'],
            [['number'], 'string', 'max' => 100],
            [['customer_type'], 'string', 'max' => 20],
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
            'number' => '合同编号',
            'customer_type' => '客户类型：企业、个人',
            'cCustomer_id' => '企业客户ID',
            'pCustomer_id' => '个人客户ID',
            'due_time' => '合同到期时间',
            'start_time' => '合同开始时间',
            'end_time' => '合同结束时间',
            'bail' => '保证金',
            'cost_expire_time' => '缴费到期时间',
            'reg_time' => '合同登记时间',
            'note' => '备注',
            'sign_date' => '合同签订日期',
            'is_del' => '删除标记',
            'last_modify_datetime' => '上次修改时间',
            'modify_aid' => '上次修改的管理员id',
            'operating_company_id' => '所属运营公司ID',
        ];
    }
}
