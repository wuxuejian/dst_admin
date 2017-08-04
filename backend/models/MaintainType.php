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
class MaintainType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_maintain_type';
    }

     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [           
            [['maintain_type'], 'string', 'max' => 100],
            [['maintain_des'], 'string', 'max' => 100],
            [['car_model_name'], 'string', 'max' => 100]

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'maintain_type' => 'Maintain Type',
            'maintain_des' => 'Maintain descent',
            'car_model_name' => 'Car Model Name'
         
        ];
    }
}
