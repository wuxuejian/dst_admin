<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cs_car_transfer".
 *
 * @property integer $id
 * @property string $dd_number
 * @property integer $add_time
 * @property string $attachment_url
 * @property string $originator
 * @property integer $originator_operating_company_id
 */
class CarTransfer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_car_transfer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_time', 'originator_operating_company_id', 'is_del', 'status', 'car_number', 'car_ok_number'], 'integer'],
            [['dd_number', 'originator'], 'string', 'max' => 20],
            [['attachment_url'], 'string', 'max' => 100]
        ];
    }
    
    public function scenarios()
    {
    	$columns = $this->getAttributes();
    	return [
	    	'default'=>array_keys($columns),
	    	'edit'=>[
	    		'dd_number','attachment_url','originator','originator_operating_company_id'
	    	]
    	];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dd_number' => 'Dd Number',
            'add_time' => 'Add Time',
            'attachment_url' => 'Attachment Url',
            'originator' => 'Originator',
            'originator_operating_company_id' => 'Originator Operating Company ID',
            'is_del' => 'is_del',
            'status' => 'status',
            'car_number' => 'car_number',
            'car_ok_number' => 'car_ok_number',
        ];
    }
}
