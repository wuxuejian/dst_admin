<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%customer_personal}}".
 *
 * @property integer $id
 * @property string $number
 * @property string $telephone
 * @property string $mobile
 * @property string $qq
 * @property string $email
 * @property string $id_name
 * @property string $id_number
 * @property string $id_address
 * @property string $personal_lng
 * @property string $personal_lat
 * @property integer $id_sex
 * @property string $driving_number
 * @property string $driving_addr
 * @property string $driving_issue_date
 * @property string $driving_class
 * @property string $driving_valid_from
 * @property string $driving_valid_for
 * @property string $driving_issue_authority
 * @property integer $is_del
 * @property string $operating_company_id
 */
class CustomerPersonal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_personal}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_sex', 'driving_issue_date', 'driving_valid_from', 'driving_valid_for', 'is_del', 'operating_company_id'], 'integer'],
            [['number', 'qq'], 'string', 'max' => 30],
            [['telephone', 'personal_lng', 'personal_lat'], 'string', 'max' => 20],
            [['mobile'], 'string', 'max' => 11],
            [['email', 'id_name', 'driving_number', 'driving_class','account_name','bank_account','account_opening'], 'string', 'max' => 50],
            [['id_number'], 'string', 'max' => 18],
            [['id_address', 'driving_addr', 'driving_issue_authority'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => '客户号',
            'telephone' => '固定电话',
            'mobile' => '移动电话',
            'qq' => 'Qq',
            'email' => '邮箱',
            'id_name' => '姓名',
            'id_number' => '身份证号',
            'id_address' => '住址',
            'personal_lng' => '经度',
            'personal_lat' => '纬度',
            'id_sex' => '性别',
            'driving_number' => '驾驶证号',
            'driving_addr' => '驾驶证住址',
            'driving_issue_date' => '驾驶证初次领证日期',
            'driving_class' => '驾驶证准驾车型',
            'driving_valid_from' => '驾驶证有效起始日期',
            'driving_valid_for' => '驾驶证有效期限',
            'driving_issue_authority' => '驾驶证发证机关',
            'is_del' => '客户删除标记',
            'operating_company_id' => '所属运营公司ID',
            'account_name' => '开户名',
            'bank_account' => '开户银行',
            'account_opening' => '开户帐号',
        ];
    }
}
