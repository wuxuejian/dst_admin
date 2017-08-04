<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%system_daemon}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $script_path
 * @property string $description
 */
class SystemDaemon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_daemon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'script_path', 'description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '进程名称',
            'script_path' => '脚本位置',
            'description' => '任务描述',
        ];
    }
}
