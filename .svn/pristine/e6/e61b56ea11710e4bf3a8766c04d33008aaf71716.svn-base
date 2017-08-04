<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%system_menu}}".
 *
 * @property string $id
 * @property string $pid
 * @property string $name
 * @property string $mca
 * @property string $target_url
 * @property string $icon_class
 * @property string $list_order
 * @property integer $opend
 * @property string $note
 * @property integer $is_del
 * @property integer $is_lock
 */
class SystemMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'list_order', 'opend', 'is_del', 'is_lock'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['mca', 'target_url', 'note'], 'string', 'max' => 255],
            [['icon_class'], 'string', 'max' => 100]
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
            'mca' => 'Mca',
            'target_url' => 'Target Url',
            'icon_class' => 'Icon Class',
            'list_order' => 'List Order',
            'opend' => 'Opend',
            'note' => 'Note',
            'is_del' => 'Is Del',
            'is_lock' => 'Is Lock',
        ];
    }
}
