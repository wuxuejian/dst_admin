<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car_brand}}".
 *
 * @property integer $id
 * @property string $pid
 * @property string $name
 * @property string $code
 * @property string $note
 * @property integer $is_del
 */
class CarBrand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_brand}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'is_del'], 'integer'],
            [['name', 'code', 'note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'name' => 'Name',
            'code' => 'Code',
            'note' => 'Note',
            'is_del' => 'Is Del',
        ];
    }
}
