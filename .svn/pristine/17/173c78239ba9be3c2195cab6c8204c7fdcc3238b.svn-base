<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car_trial_protocol}}".
 *
 * @property string $ctp_id
 * @property string $ctp_number
 * @property string $ctp_customer_type
 * @property string $ctp_cCustomer_id
 * @property string $ctp_pCustomer_id
 * @property integer $ctp_car_nums
 * @property string $ctp_sign_date
 * @property string $ctp_start_date
 * @property string $ctp_end_date
 * @property string $ctp_note
 * @property string $ctp_systime
 * @property integer $ctp_sysuserid
 * @property string $ctp_sysuser
 * @property string $ctp_is_del
 * @property string $ctp_last_modify_datetime
 * @property string $ctp_modify_aid
 * @property string $operating_company_id
 */
class CarTrialProtocol extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_trial_protocol}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ctp_cCustomer_id', 'ctp_pCustomer_id', 'ctp_car_nums', 'ctp_systime', 'ctp_sysuserid', 'ctp_last_modify_datetime', 'ctp_modify_aid', 'operating_company_id'], 'integer'],
            [['ctp_sign_date', 'ctp_start_date', 'ctp_end_date'], 'safe'],
            [['ctp_number'], 'string', 'max' => 50],
            [['ctp_customer_type'], 'string', 'max' => 20],
            [['ctp_note'], 'string', 'max' => 255],
            [['ctp_sysuser'], 'string', 'max' => 30],
            [['ctp_is_del'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ctp_id' => '主键，车辆试用协议表',
            'ctp_number' => '试用协议编号',
            'ctp_customer_type' => '客户类型：企业、个人',
            'ctp_cCustomer_id' => '企业客户ID',
            'ctp_pCustomer_id' => '个人客户ID',
            'ctp_car_nums' => '试用车数量',
            'ctp_sign_date' => '协议签订日期',
            'ctp_start_date' => '开始试用日期',
            'ctp_end_date' => '结束试用日期',
            'ctp_note' => '协议备注',
            'ctp_systime' => '系统时间',
            'ctp_sysuserid' => '系统人员ID',
            'ctp_sysuser' => '系统人员',
            'ctp_is_del' => '是否已删除',
            'ctp_last_modify_datetime' => '上次修改时间',
            'ctp_modify_aid' => '上次修改的管理员id',
            'operating_company_id' => '所属运营公司ID',
        ];
    }
}
