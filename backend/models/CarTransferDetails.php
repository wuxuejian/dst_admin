<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cs_car_transfer_details".
 *
 * @property integer $id
 * @property integer $transfer_list_id
 * @property integer $car_id
 * @property integer $start_time
 * @property string $transport_company
 * @property string $transport_tel
 * @property integer $transport_money
 */
class CarTransferDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_car_transfer_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transfer_list_id', 'car_id', 'start_time', 'transport_money', 'is_del', 'is_confirm', 'end_time', 'credentials_status'], 'integer'],
            [['transport_company'], 'string', 'max' => 30],
            [['abnormal_note'], 'string', 'max' => 100],
            [['transport_tel'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transfer_list_id' => 'Transfer List ID',
            'car_id' => 'Car ID',
            'start_time' => 'Start Time',
            'transport_company' => 'Transport Company',
            'transport_tel' => 'Transport Tel',
            'transport_money' => 'Transport Money',
            'is_del' => 'is_del',
            'is_confirm' => 'is_confirm',
            'end_time' => 'end_time',
            'credentials_status' => 'credentials_status',
            'abnormal_note' => 'abnormal_note'
        ];
    }
}
