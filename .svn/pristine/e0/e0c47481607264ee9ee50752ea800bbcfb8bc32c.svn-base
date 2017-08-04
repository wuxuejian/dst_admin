<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%config_category}}".
 *
 * @property string $id
 * @property string $parent_id
 * @property string $title
 * @property string $key
 * @property string $list_order
 * @property integer $is_del
 * @property integer $is_lock
 */
class ConfigCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'list_order', 'is_del', 'is_lock'], 'integer'],
            [['title', 'key'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'title' => 'Title',
            'key' => 'Key',
            'list_order' => 'List Order',
            'is_del' => 'Is Del',
            'is_lock' => 'Is Lock',
        ];
    }
}
