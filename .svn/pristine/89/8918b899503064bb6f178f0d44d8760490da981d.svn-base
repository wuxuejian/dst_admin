<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%tcp_author}}".
 *
 * @property string $id
 * @property string $count
 * @property string $password
 * @property string $company_name
 * @property string $client_id
 * @property string $client_ip
 * @property string $connect_datetime
 * @property string $connect_times
 * @property integer $is_online
 * @property string $note
 * @property integer $is_del
 */
class TcpAuthor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tcp_author}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'connect_datetime', 'connect_times', 'is_online', 'is_del'], 'integer'],
            [['count', 'password'], 'string', 'max' => 32],
            [['company_name', 'note'], 'string', 'max' => 255],
            [['client_ip'], 'string', 'max' => 19],
            [['count'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'count' => '账号',
            'password' => '密码',
            'company_name' => '公司名称',
            'client_id' => '连接号',
            'client_ip' => '连接ip',
            'connect_datetime' => '连接时间',
            'connect_times' => '连接总次数',
            'is_online' => '是否在线',
            'note' => '备注',
            'is_del' => '删除标记',
        ];
    }
}
