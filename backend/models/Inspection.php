<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oa_inspection".
 *
 * @property string $id
 * @property integer $car_brand_id
 * @property string $car_model
 * @property integer $put_car_num
 * @property integer $inspection_num
 * @property string $inspection_director_name
 * @property string $validate_car_time
 * @property string $inspection_result
 * @property string $car_no_img
 * @property integer $approve_status
 * @property integer $approve_result
 * @property string $approve_note
 * @property string $add_time
 * @property string $oper_user
 */
class Inspection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_inspection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['car_brand_id', 'put_car_num', 'inspection_num', 'approve_status', 'approve_result'], 'integer'],
            [['validate_car_time', 'add_time'], 'safe'],
            [['id'], 'string', 'max' => 20],
            [['car_model', 'inspection_director_name', 'oper_user'], 'string', 'max' => 50],
            [['inspection_result', 'approve_note'], 'string', 'max' => 500],
            [['car_no_img'], 'string', 'max' => 100]
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
            'put_car_num' => 'Put Car Num',
            'inspection_num' => 'Inspection Num',
            'inspection_director_name' => 'Inspection Director Name',
            'validate_car_time' => 'Validate Car Time',
            'inspection_result' => 'Inspection Result',
            'car_no_img' => 'Car No Img',
            'approve_status' => 'Approve Status',
            'approve_result' => 'Approve Result',
            'approve_note' => 'Approve Note',
            'add_time' => 'Add Time',
            'oper_user' => 'Oper User',
        ];
    }
    
    public function scenarios()
    {
    	return [
    		'default'=>['id','car_brand_id','car_model','put_car_num','inspection_num','inspection_director_name','validate_car_time','inspection_result','car_no_img','approve_status','approve_result','approve_note','add_time','oper_user'],
	    	'approve'=>['approve_status','approve_result','approve_note'],
	    	'confirm'=>['approve_status']
    	];
    }
}
