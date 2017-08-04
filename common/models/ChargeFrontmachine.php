<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%charge_frontmachine}}".
 *
 * @property string $id
 * @property string $addr
 * @property string $port
 * @property string $access_level
 * @property string $password
 * @property string $register_number
 * @property string $note
 * @property integer $is_del
 */
class ChargeFrontmachine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%charge_frontmachine}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['port', 'register_number', 'is_del'], 'integer'],
            [['addr'], 'string', 'max' => 20],
            [['access_level'], 'string', 'max' => 100],
            [['password', 'db_username', 'db_password', 'db_name'], 'string', 'max' => 50],
            [['note'], 'string', 'max' => 255],
            [['db_port'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'addr' => '地址',
            'port' => '端口',
            'access_level' => '权限等级',
            'password' => '密码',
            'register_number' => '寄存器编号',
            'note' => '备注',
            'db_username' => '连接前置机数据库的用户名',
            'db_password' => '连接前置机数据库的密码',
            'db_name' => '连接前置机使用的数据库名称',
            'is_del' => '删除标记',
        ];
    }
}
