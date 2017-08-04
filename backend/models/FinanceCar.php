<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cs_finance_car".
 *
 * @property integer $id
 * @property integer $finance_id
 * @property integer $car_id
 * @property string $add_time
 * @property string $add_aid
 */
class FinanceCar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_finance_car';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['finance_id', 'car_id', 'add_time', 'add_aid'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'finance_id' => 'Finance ID',
            'car_id' => 'Car ID',
            'add_time' => 'Add Time',
            'add_aid' => 'Add Aid',
        ];
    }
}
