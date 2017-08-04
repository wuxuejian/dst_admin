<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%china_area}}".
 *
 * @property integer $id
 * @property integer $areaid
 * @property string $area
 * @property integer $fatherid
 */
class ChinaArea extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%china_area}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['areaid', 'area', 'fatherid'], 'required'],
            [['areaid', 'fatherid'], 'integer'],
            [['area'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'areaid' => 'Areaid',
            'area' => 'Area',
            'fatherid' => 'Fatherid',
        ];
    }
}
