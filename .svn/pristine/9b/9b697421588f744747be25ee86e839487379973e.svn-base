<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car_insurance_other}}".
 *
 * @property string $id
 * @property string $car_id
 * @property string $insurer_company
 * @property double $money_amount
 * @property string $start_date
 * @property string $end_date
 * @property string $is_del
 * @property string $add_datetime
 * @property string $add_aid
 */
class CarInsuranceOther extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_insurance_other}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['car_id', 'start_date', 'end_date', 'is_del', 'add_datetime', 'add_aid'], 'integer'],
            [['money_amount'], 'number'],
            [['insurer_company'], 'string', 'max' => 100],
            [['number'], 'string', 'max' => 30],
            [['note'], 'string', 'max' => 300],
            [['append_urls', 'insurance_text'], 'string', 'max' => 500]
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
            'insurer_company' => 'Insurer Company',
            'money_amount' => 'Money Amount',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'is_del' => 'Is Del',
            'add_datetime' => 'Add Datetime',
            'add_aid' => 'Add Aid',
        ];
    }
}
