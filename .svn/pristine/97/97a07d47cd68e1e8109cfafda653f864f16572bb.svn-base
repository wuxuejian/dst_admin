<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%charge_card_swap}}".
 *
 * @property string $id
 * @property string $cc_id
 * @property string $type
 * @property double $before_money
 * @property double $money
 * @property double $after_money
 * @property string $write_status
 * @property string $note
 * @property string $atime
 * @property string $aaid
 */
class ChargeCardSwap extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%charge_card_swap}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cc_id', 'aaid'], 'integer'],
            [['type', 'write_status'], 'string'],
            [['before_money', 'money', 'after_money'], 'number'],
            [['atime'], 'safe'],
            [['note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cc_id' => 'Cc ID',
            'type' => 'Type',
            'before_money' => 'Before Money',
            'money' => 'Money',
            'after_money' => 'After Money',
            'write_status' => 'Write Status',
            'note' => 'Note',
            'atime' => 'Atime',
            'aaid' => 'Aaid',
        ];
    }
}
