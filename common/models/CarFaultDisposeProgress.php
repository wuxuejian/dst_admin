<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car_fault_dispose_progress}}".
 *
 * @property string $id
 * @property integer $fault_id
 * @property string $disposer
 * @property string $dispose_date
 * @property string $fault_status
 * @property string $progress_desc
 * @property string $create_time
 * @property string $creator_id
 * @property integer $is_del
 */
class CarFaultDisposeProgress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_fault_dispose_progress}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fault_id', 'creator_id', 'is_del'], 'integer'],
            [['dispose_date', 'create_time'], 'safe'],
            [['disposer'], 'string', 'max' => 30],
            [['fault_status'], 'string', 'max' => 50],
            [['progress_desc'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键，车辆故障处理进度表',
            'fault_id' => '所属故障记录ID',
            'disposer' => '故障受理人',
            'dispose_date' => '受理日期',
            'fault_status' => '故障状态',
            'progress_desc' => '进度描述',
            'create_time' => '记录创建时间',
            'creator_id' => '记录创建人id',
            'is_del' => '删除标记',
        ];
    }


    
}
