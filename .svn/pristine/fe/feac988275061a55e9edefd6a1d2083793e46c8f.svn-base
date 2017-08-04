<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%china_province}}".
 *
 * @property integer $id
 * @property integer $provinceid
 * @property string $province
 */
class ChinaProvince extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%china_province}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['provinceid', 'province'], 'required'],
            [['provinceid'], 'integer'],
            [['province'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'provinceid' => 'Provinceid',
            'province' => 'Province',
        ];
    }
}
