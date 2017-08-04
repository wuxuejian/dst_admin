<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cs_car_stock".
 *
 * @property integer $id
 * @property integer $car_id
 * @property integer $car_type
 * @property integer $car_status
 * @property integer $is_del
 * @property integer $department_id
 * @property integer $c_customer_id
 * @property integer $add_aid
 * @property string $add_time
 */
class CarStock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_car_stock';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['car_id', 'car_type', 'car_status', 'is_del', 'department_id', 'c_customer_id', 'add_aid','operating_company_id'], 'integer'],
            [['add_time'], 'safe']
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
            'car_type' => 'Car Type',
            'car_status' => 'Car Status',
            'is_del' => 'Is Del',
            'department_id' => 'Department ID',
            'c_customer_id' => 'C Customer ID',
            'add_aid' => 'Add Aid',
            'add_time' => 'Add Time',
            'operating_company_id' =>'operating_company_id',
        ];
    }
}
