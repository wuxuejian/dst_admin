<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vip_guide}}".
 *
 * @property string $id
 * @property resource $content
 * @property string $last_modify_datetime
 * @property integer $last_modify_aid
 */
class VipGuide extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip_guide}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['last_modify_datetime'], 'safe'],
            [['last_modify_aid'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键，APP操作指南',
            'content' => '内容',
            'last_modify_datetime' => '最后修改时间',
            'last_modify_aid' => '最后修改人员ID',
        ];
    }
}
