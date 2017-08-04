<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%battery_correct_notice}}".
 *
 * @property string $id
 * @property string $car_vin
 * @property string $notice_time
 * @property string $notice_sender
 * @property string $contact_name
 * @property string $mark
 * @property integer $is_del
 * @property string $modify_time
 * @property integer $modify_aid
 * @property integer $is_corrected
 * @property string $correct_res
 * @property string $correct_time
 */
class BatteryCorrectNotice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%battery_correct_notice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notice_time', 'modify_time', 'correct_time'], 'safe'],
            [['is_del', 'modify_aid', 'is_corrected'], 'integer'],
            [['car_vin', 'notice_sender', 'contact_name', 'mark', 'correct_res'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键，电池修正通知表',
            'car_vin' => '车架号',
            'notice_time' => '通知日期',
            'notice_sender' => '通知人员',
            'contact_name' => '联系人',
            'mark' => '备注',
            'is_del' => '是否已删除',
            'modify_time' => '记录时间',
            'modify_aid' => '记录人员',
            'is_corrected' => '是否执行慢充修正',
            'correct_res' => '修正结果',
            'correct_time' => '修正完成时间',
        ];
    }
}
