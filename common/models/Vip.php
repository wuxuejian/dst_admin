<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vip}}".
 *
 * @property string $id
 * @property string $code
 * @property string $client
 * @property string $mobile
 * @property string $password
 * @property integer $sex
 * @property string $email
 * @property string $mark
 * @property string $app_ver
 * @property integer $systime
 * @property string $sysuser
 * @property integer $is_del
 * @property double $money_acount
 * @property string $shot_message_code
 * @property string $sm_reqtime
 * @property string $sm_number
 */
class Vip extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sex', 'systime', 'is_del', 'sm_reqtime'], 'integer'],
            [['money_acount'], 'number'],
            [['code', 'client', 'app_ver'], 'string', 'max' => 30],
            [['mobile'], 'string', 'max' => 11],
            [['password'], 'string', 'max' => 32],
            [['email', 'sysuser'], 'string', 'max' => 50],
            [['mark'], 'string', 'max' => 200],
            [['shot_message_code'], 'string', 'max' => 6],
            [['sm_number'], 'string', 'max' => 3],
            [['mobile'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'client' => 'Client',
            'mobile' => 'Mobile',
            'password' => 'Password',
            'sex' => 'Sex',
            'email' => 'Email',
            'mark' => 'Mark',
            'app_ver' => 'App Ver',
            'systime' => 'Systime',
            'sysuser' => 'Sysuser',
            'is_del' => 'Is Del',
            'money_acount' => 'Money Acount',
            'shot_message_code' => 'Shot Message Code',
            'sm_reqtime' => 'Sm Reqtime',
            'sm_number' => 'Sm Number',
        ];
    }
}
