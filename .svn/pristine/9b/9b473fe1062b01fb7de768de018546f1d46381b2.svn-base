<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_inspection_all".
 *
 * @property string $id
 * @property integer $car_brand_id
 * @property string $car_model
 * @property integer $car_num
 * @property integer $real_car_num
 * @property string $inspection_director_name
 * @property string $validate_car_time
 * @property integer $approve_status
 * @property string $note
 * @property string $add_time
 * @property string $oper_user
 * @property integer $is_del
 */
class InspectionAll extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_inspection_all';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['car_brand_id', 'car_num', 'real_car_num', 'approve_status', 'is_del'], 'integer'],
            [['validate_car_time', 'add_time'], 'safe'],
            [['id'], 'string', 'max' => 20],
            [['car_model', 'inspection_director_name', 'oper_user'], 'string', 'max' => 50],
            [['note'], 'string', 'max' => 500]
        ];
    }
    
    public function scenarios()
    {
    	return [
    	'default'=>['id','car_brand_id','car_model','car_num','real_car_num','inspection_director_name','validate_car_time','approve_status','note','add_time','oper_user','is_del'],
    	'approve'=>['approve_status'],
    	'del'=>['is_del'],
    	'confirm'=>['approve_status']
    	];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_brand_id' => 'Car Brand ID',
            'car_model' => 'Car Model',
            'car_num' => 'Car Num',
            'real_car_num' => 'Real Car Num',
            'inspection_director_name' => 'Inspection Director Name',
            'validate_car_time' => 'Validate Car Time',
            'approve_status' => 'Approve Status',
            'note' => 'Note',
            'add_time' => 'Add Time',
            'oper_user' => 'Oper User',
            'is_del' => 'Is Del',
        ];
    }
}
