<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%china_city}}".
 *
 * @property integer $id
 * @property integer $cityid
 * @property string $city
 * @property integer $fatherid
 */
class ChinaCity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%china_city}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cityid', 'city', 'fatherid'], 'required'],
            [['cityid', 'fatherid'], 'integer'],
            [['city'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cityid' => 'Cityid',
            'city' => 'City',
            'fatherid' => 'Fatherid',
        ];
    }
}
