<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%system_task}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $exec_command
 * @property string $exec_frequency
 * @property integer $in_use
 * @property integer $pid
 * @property string $last_exec_datetime
 */
class SystemTask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_task}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['in_use', 'pid'], 'integer'],
            [['last_exec_datetime'], 'safe'],
            [['name', 'exec_command'], 'string', 'max' => 255],
            [['exec_frequency'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '任务名称',
            'exec_command' => '任务执行命令',
            'exec_frequency' => '执行频率',
            'in_use' => '是否启用',
            'pid' => '任务进程id',
            'last_exec_datetime' => '任务上次执行时间',
        ];
    }
}
