<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%tcp_applications}}".
 *
 * @property string $id
 * @property string $app_name
 * @property string $app_path
 * @property string $app_addr
 * @property string $app_port
 * @property integer $is_del
 */
class TcpApplications extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tcp_applications}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_port', 'is_del'], 'integer'],
            [['app_name', 'app_addr'], 'string', 'max' => 100],
            [['app_path'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_name' => '应用名称',
            'app_path' => '应用目录',
            'app_addr' => '应用地址',
            'app_port' => '应用端口号',
            'is_del' => '删除标记',
        ];
    }
}
