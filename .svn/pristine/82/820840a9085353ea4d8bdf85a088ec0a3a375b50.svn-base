<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vip_suggestion}}".
 *
 * @property string $vs_id
 * @property string $vs_code
 * @property string $vs_title
 * @property string $vs_content
 * @property string $vs_time
 * @property integer $vs_vip_id
 * @property integer $vs_responder_id
 * @property string $vs_respond_txt
 * @property string $vs_respond_time
 * @property string $vs_mark
 * @property integer $vs_systime
 * @property integer $vs_is_del
 */
class VipSuggestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip_suggestion}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vs_time', 'vs_respond_time'], 'safe'],
            [['vs_vip_id', 'vs_responder_id', 'vs_systime', 'vs_is_del'], 'integer'],
            [['vs_code'], 'string', 'max' => 30],
            [['vs_title'], 'string', 'max' => 100],
            [['vs_content', 'vs_respond_txt'], 'string', 'max' => 300],
            [['vs_mark'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vs_id' => 'Vs ID',
            'vs_code' => 'Vs Code',
            'vs_title' => 'Vs Title',
            'vs_content' => 'Vs Content',
            'vs_time' => 'Vs Time',
            'vs_vip_id' => 'Vs Vip ID',
            'vs_responder_id' => 'Vs Responder ID',
            'vs_respond_txt' => 'Vs Respond Txt',
            'vs_respond_time' => 'Vs Respond Time',
            'vs_mark' => 'Vs Mark',
            'vs_systime' => 'Vs Systime',
            'vs_is_del' => 'Vs Is Del',
        ];
    }
}
