<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cs_car_office_register".
 *
 * @property integer $id
 * @property integer $car_id
 * @property integer $department_id
 * @property integer $username_id
 * @property string $start_time
 * @property string $end_time
 * @property string $reason
 * @property string $address
 * @property double $total_distance
 * @property double $remain_distance
 * @property string $note
 */
class CarOfficeRegister extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_car_office_register';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['car_id', 'department_id', 'username_id'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['reason'], 'required'],
            [['total_distance', 'remain_distance'], 'number'],
            [['reason', 'address', 'note'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_id' => 'Car ID',
            'department_id' => 'Department ID',
            'username_id' => 'Username ID',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'reason' => 'Reason',
            'address' => 'Address',
            'total_distance' => 'Total Distance',
            'remain_distance' => 'Remain Distance',
            'note' => 'Note',
        ];
    }
}
