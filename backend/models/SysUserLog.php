<?php
namespace backend\models;

use Yii;

/**
 * This is the model class for table "sys_user_log".
 *
 * @property integer $log_id
 * @property string $qstring
 * @property string $action
 * @property integer $user_id
 * @property string $user_name
 * @property string $ip
 * @property integer $log_time
 * @property enum $log_type
 */
class SysUserLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sys_user_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_time', 'user_id','is_super'], 'integer'],
            [['qstring', 'action'], 'string'],
            [['user_name'], 'string', 'max' => 100],
            [['ip'], 'string', 'max' => 50],
            [['log_type'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => 'Log Id',
            'qstring' => 'Qstring',
            'action' => 'Action',
            'user_id' => 'User Id',
            'user_name' => 'User Name',
            'ip' => 'Ip',
            'log_time' => 'Log Time',
            'log_type' => 'Log Type'
        ];
    }
}
