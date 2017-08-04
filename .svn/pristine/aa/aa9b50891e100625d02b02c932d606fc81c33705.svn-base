<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vip_app_login}}".
 *
 * @property integer $id
 * @property string $vip_id
 * @property string $key
 * @property string $key_ctime
 * @property string $key_etime
 */
class VipAppLogin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip_app_login}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vip_id', 'key_ctime', 'key_etime'], 'integer'],
            [['key'], 'string', 'max' => 32],
            [['vip_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vip_id' => 'Vip ID',
            'key' => 'Key',
            'key_ctime' => 'Key Ctime',
            'key_etime' => 'Key Etime',
        ];
    }
}
