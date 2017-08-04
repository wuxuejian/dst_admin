<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car_trial_protocol_details}}".
 *
 * @property string $ctpd_id
 * @property string $ctpd_protocol_id
 * @property string $ctpd_cCustomer_id
 * @property string $ctpd_pCustomer_id
 * @property integer $ctpd_car_id
 * @property string $ctpd_deliver_date
 * @property string $ctpd_back_date
 * @property string $ctpd_note
 * @property string $ctpd_is_del
 * @property string $operating_company_id
 */
class CarTrialProtocolDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_trial_protocol_details}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ctpd_protocol_id', 'ctpd_cCustomer_id', 'ctpd_pCustomer_id', 'ctpd_car_id', 'operating_company_id'], 'integer'],
            [['ctpd_deliver_date', 'ctpd_back_date'], 'safe'],
            [['ctpd_note'], 'string', 'max' => 255],
            [['ctpd_is_del'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ctpd_id' => '主键，车辆试用协议明细表',
            'ctpd_protocol_id' => '试用协议ID',
            'ctpd_cCustomer_id' => '企业客户ID',
            'ctpd_pCustomer_id' => '个人客户ID',
            'ctpd_car_id' => '试用车辆ID',
            'ctpd_deliver_date' => '交车日期',
            'ctpd_back_date' => '还车日期',
            'ctpd_note' => '备注',
            'ctpd_is_del' => '是否已删除',
            'operating_company_id' => '所属运营公司ID',
        ];
    }
}
