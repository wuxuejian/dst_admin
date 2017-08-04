<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car_driving_license}}".
 *
 * @property string $id
 * @property string $car_id
 * @property string $addr
 * @property string $register_date
 * @property string $issue_date
 * @property string $archives_number
 * @property string $total_mass
 * @property string $force_scrap_date
 * @property string $valid_to_date
 * @property string $next_valid_date
 */
class CarDrivingLicense extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_driving_license}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['car_id', 'register_date', 'issue_date', 'total_mass', 'force_scrap_date', 'valid_to_date', 'next_valid_date'], 'integer'],
            [['addr','image'], 'string', 'max' => 255],
            [['archives_number'], 'string', 'max' => 50]
			//,
            //[['car_id'], 'unique']
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
            'addr' => '登记地址',
            'register_date' => 'Register Date',
            'issue_date' => 'Issue Date',
            'archives_number' => 'Archives Number',
            'total_mass' => 'Total Mass',
            'force_scrap_date' => 'Force Scrap Date',
            'valid_to_date' => 'Valid To Date',
            'next_valid_date' => 'Next Valid Date',
			
        ];
    }
}
