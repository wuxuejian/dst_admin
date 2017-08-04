<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_inspection_car".
 *
 * @property integer $id
 * @property string $vehicle_dentification_number
 * @property string $note
 * @property string $inspection_id
 */
class InspectionCar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_inspection_car';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_dentification_number'], 'string', 'max' => 50],
            [['note'], 'string', 'max' => 500],
            [['inspection_id'], 'string', 'max' => 20]
        ];
    }
    
    public function scenarios()
    {
    	return [
    	'default'=>['vehicle_dentification_number','note','inspection_id'],
    	'edit'=>['vehicle_dentification_number','note']
    	];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vehicle_dentification_number' => 'Vehicle Dentification Number',
            'note' => 'Note',
            'inspection_id' => 'Inspection ID',
        ];
    }
}
