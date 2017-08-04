<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car_moni_exception_condition}}".
 *
 * @property integer $id
 * @property string $battery_type
 * @property string $add_uid
 * @property string $add_datetime
 */
class CarMoniExceptionCondition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_moni_exception_condition}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_uid'], 'integer'],
            [['add_datetime'], 'safe'],
            [['battery_type'], 'string', 'max' => 30],
            [['battery_type'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'battery_type' => 'Battery Type',
            'add_uid' => 'Add Uid',
            'add_datetime' => 'Add Datetime',
        ];
    }
}
