<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vip_charge_record_count}}".
 *
 * @property integer $id
 * @property string $vip_id
 * @property string $vcr_id
 * @property string $fm_id
 * @property string $fm_start_id
 * @property string $fm_end_id
 * @property string $fm_deal_no
 * @property double $money
 * @property string $count_datetime
 */
class VipChargeRecordCount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip_charge_record_count}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vip_id', 'vcr_id', 'fm_id', 'fm_start_id', 'fm_end_id'], 'integer'],
            [['money'], 'number'],
            [['count_datetime'], 'safe'],
            [['fm_deal_no'], 'string', 'max' => 50],
            [['fm_id', 'fm_start_id'], 'unique', 'targetAttribute' => ['fm_id', 'fm_start_id'], 'message' => 'The combination of Fm ID and Fm Start ID has already been taken.']
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
            'vcr_id' => 'Vcr ID',
            'fm_id' => 'Fm ID',
            'fm_start_id' => 'Fm Start ID',
            'fm_end_id' => 'Fm End ID',
            'fm_deal_no' => 'Fm Deal No',
            'money' => 'Money',
            'count_datetime' => 'Count Datetime',
        ];
    }
}
