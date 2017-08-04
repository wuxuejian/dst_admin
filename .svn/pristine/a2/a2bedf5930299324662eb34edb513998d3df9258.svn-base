<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%config_item}}".
 *
 * @property string $id
 * @property string $belongs_id
 * @property string $value
 * @property string $text
 * @property string $note
 * @property string $list_order
 * @property integer $is_del
 */
class ConfigItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['belongs_id', 'list_order', 'is_del'], 'integer'],
            [['value', 'note'], 'string', 'max' => 255],
            [['text'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'belongs_id' => 'Belongs ID',
            'value' => 'Value',
            'text' => 'Text',
            'note' => 'Note',
            'list_order' => 'List Order',
            'is_del' => 'Is Del',
        ];
    }
}
