<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%operating_company}}".
 *
 * @property integer $id
 * @property string $pid
 * @property string $name
 * @property string $addr
 * @property string $note
 * @property integer $is_del
 */
class OperatingCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operating_company}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'is_del'], 'integer'],
            [['area', 'name', 'addr'], 'string', 'max' => 30],
            [['note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键ID，车辆运营公司表',
            'pid' => '父id',
            'area' => '所属大区',
            'name' => '运营公司名称',
            'addr' => '运营公司地址',
            'note' => '备注',
            'is_del' => '删除标记',
        ];
    }
}
