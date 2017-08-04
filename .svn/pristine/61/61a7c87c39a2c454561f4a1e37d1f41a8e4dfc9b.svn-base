<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%owner}}".
 *
 * @property integer $id
 * @property string $pid
 * @property string $name
 * @property string $code
 * @property string $addr
 * @property string $note
 * @property integer $is_del
 */
class Owner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%owner}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'is_del'], 'integer'],
            [['name', 'code', 'addr'], 'string', 'max' => 30],
            [['note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键ID，机动车所有人表',
            'pid' => '父id',
            'name' => '所有人名称',
            'code' => '所有人编号',
            'addr' => '所有人地址',
            'note' => '备注',
            'is_del' => '删除标记',
        ];
    }
}
