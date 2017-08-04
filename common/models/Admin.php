<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%admin}}".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property integer $super
 * @property string $name
 * @property string $sex
 * @property string $operating_company_id
 * @property integer $department_id
 * @property string $email
 * @property string $telephone
 * @property string $qq
 * @property string $active_time
 * @property integer $is_locked
 * @property string $ltime
 * @property string $lip
 * @property integer $is_del
 */
class Admin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['super', 'sex', 'operating_company_id', 'department_id', 'active_time', 'is_locked', 'ltime', 'is_del'], 'integer'],
            [['username'], 'string', 'max' => 20],
            [['password'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 10],
            [['email', 'qq'], 'string', 'max' => 50],
            [['telephone'], 'string', 'max' => 11],
            [['lip'], 'string', 'max' => 15],
            [['username'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'super' => '程序员标记',
            'name' => 'Name',
            'sex' => 'Sex',
            'operating_company_id' => '所属运营公司ID',
            'department_id' => 'Department ID',
            'email' => 'Email',
            'telephone' => 'Telephone',
            'qq' => 'Qq',
            'active_time' => 'Active Time',
            'is_locked' => 'Is Locked',
            'ltime' => 'Ltime',
            'lip' => 'Lip',
            'is_del' => '删除标记',
        ];
    }
}
