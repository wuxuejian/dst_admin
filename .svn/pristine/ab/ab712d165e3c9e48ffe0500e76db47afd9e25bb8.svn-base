<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car_let_record}}".
 *
 * @property string $id
 * @property string $contract_id
 * @property string $cCustomer_id
 * @property string $pCustomer_id
 * @property string $car_id
 * @property double $month_rent
 * @property string $let_time
 * @property string $back_time
 * @property string $note
 * @property integer $is_del
 * @property string $operating_company_id
 */
class CarLetRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_let_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contract_id', 'cCustomer_id', 'pCustomer_id', 'car_id', 'let_time', 'back_time', 'is_del', 'operating_company_id'], 'integer'],
            [['month_rent'], 'number'],
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
            'contract_id' => '本条记录所属的合同id',
            'cCustomer_id' => '企业客户ID',
            'pCustomer_id' => '个人客户ID',
            'car_id' => '汽车id',
            'month_rent' => '月租金',
            'let_time' => '车辆出租时间',
            'back_time' => '还车时间',
            'note' => '备注',
            'is_del' => '删除标记',
            'operating_company_id' => '所属运营公司ID',
        ];
    }
}
