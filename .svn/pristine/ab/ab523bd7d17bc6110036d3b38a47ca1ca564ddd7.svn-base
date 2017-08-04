<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cs_maintain_record".
 *
 * @property integer $id
 * @property integer $car_id
 * @property string $add_time
 * @property double $driving_mileage
 * @property string $maintenance_shop
 * @property string $amount
 * @property integer $type
 */
class MaintainRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_maintain_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['car_id'], 'integer'],
            [['add_time'], 'safe'],
            [['out_time'], 'safe'],
            [['driving_mileage'], 'number'],
            [['maintenance_shop'], 'string', 'max' => 100],
			[['type'], 'integer'],
            [['in_car_img'], 'string', 'max' => 100],
            [['out_car_img'], 'string', 'max' => 100],
            [['amount'], 'string', 'max' => 10],
            [['maintain_img'], 'string', 'max' => 100],
             [['add_id'], 'integer'],
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
            'add_time' => 'Add Time',
            'out_time' => 'Out Time',
            'driving_mileage' => 'Driving Mileage',
            'maintenance_shop' => 'Maintenance Shop',
            'amount' => 'Amount',
            'in_car_img' => 'in_car_img',
            'out_car_img' => 'out_car_img',
            'maintain_img' => 'maintain_img',
            'type' => 'Type',
             'add_id' => 'Add ID',
        ];
    }
}
