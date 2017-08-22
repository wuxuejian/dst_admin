<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cs_car_transfer_list".
 *
 * @property integer $id
 * @property integer $transfer_id
 * @property integer $car_type_id
 * @property integer $number
 * @property integer $pre_operating_company_id
 * @property integer $after_operating_company_id
 * @property integer $is_owner_change
 * @property integer $after_owner_id
 * @property string $note
 */
class CarTransferList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_car_transfer_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transfer_id','car_brand_id', 'car_type_id', 'number', 'pre_operating_company_id', 'after_operating_company_id', 'is_owner_change', 'after_owner_id'], 'integer'],
            [['note'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transfer_id' => 'Transfer ID',
            'car_brand_id' => 'Car Brand ID',
            'car_type_id' => 'Car Type ID',
            'number' => 'Number',
            'pre_operating_company_id' => 'Pre Operating Company ID',
            'after_operating_company_id' => 'After Operating Company ID',
            'is_owner_change' => 'Is Owner Change',
            'after_owner_id' => 'After Owner ID',
            'note' => 'Note',
        ];
    }
}
