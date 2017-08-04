<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car_fault}}".
 *
 * @property string $id
 * @property string $car_id
 * @property string $contract_id
 * @property string $protocol_id
 * @property string $cCustomer_id
 * @property string $pCustomer_id
 * @property string $number
 * @property string $fault_status
 * @property string $f_datetime
 * @property string $f_place
 * @property string $fb_name
 * @property string $fb_mobile
 * @property string $fb_date
 * @property string $ap_name
 * @property string $report_date
 * @property string $expect_end_date
 * @property string $fzr_name
 * @property string $fzr_mobile
 * @property string $repair_order_no
 * @property string $f_desc
 * @property string $f_reason
 * @property string $f_dispose
 * @property string $register_aid
 * @property string $reg_datetime
 * @property string $thumb_plate_number
 * @property string $thumb_meter
 * @property string $thumb_scene
 * @property string $thumb_place
 * @property string $thumb_fb
 * @property string $thumb_repair_order
 * @property integer $is_del
 */
class CarFault extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_fault}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['car_id', 'contract_id', 'protocol_id', 'cCustomer_id', 'pCustomer_id', 'register_aid', 'is_del'], 'integer'],
            [['f_datetime', 'fb_date', 'report_date', 'expect_end_date', 'reg_datetime'], 'safe'],
            [['number', 'fault_status', 'fb_name', 'fb_mobile', 'ap_name', 'fzr_name', 'fzr_mobile', 'repair_order_no'], 'string', 'max' => 50],
            [['f_place', 'thumb_plate_number', 'thumb_meter', 'thumb_scene', 'thumb_place', 'thumb_fb', 'thumb_repair_order'], 'string', 'max' => 255],
            [['f_desc', 'f_reason', 'f_dispose'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_id' => '车辆id',
            'contract_id' => '出租合同id',
            'protocol_id' => '试用协议id',
            'cCustomer_id' => '企业客户id',
            'pCustomer_id' => '个人客户id',
            'number' => '故障编号',
            'fault_status' => '故障状态',
            'f_datetime' => '故障发生时间',
            'f_place' => '故障发生地点',
            'fb_name' => '故障反馈人',
            'fb_mobile' => '反馈人联系电话',
            'fb_date' => '故障反馈时间',
            'ap_name' => '本方初次受理人',
            'report_date' => '故障上报时间',
            'expect_end_date' => '预计完结时间',
            'fzr_name' => '故障负责人',
            'fzr_mobile' => '负责人联系电话',
            'repair_order_no' => '进厂维修单号',
            'f_desc' => '故障现象描述',
            'f_reason' => '故障引发原因',
            'f_dispose' => '故障处理方法',
            'register_aid' => '登记人员id',
            'reg_datetime' => '登记时间',
            'thumb_plate_number' => '车牌照片',
            'thumb_meter' => '仪表照片',
            'thumb_scene' => '故障现场照片',
            'thumb_place' => '故障位置照片',
            'thumb_fb' => '反馈人签名',
            'thumb_repair_order' => '进场维修单号照片',
            'is_del' => '删除标记',
        ];
    }
}
