<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%customer_company}}".
 *
 * @property integer $id
 * @property string $number
 * @property string $password
 * @property string $company_name
 * @property string $reg_number
 * @property string $company_addr
 * @property string $company_lng
 * @property string $company_lat
 * @property string $company_brief
 * @property string $corporate_name
 * @property string $corporate_post
 * @property string $corporate_telephone
 * @property string $corporate_mobile
 * @property string $corporate_email
 * @property string $corporate_qq
 * @property string $director_name
 * @property string $director_post
 * @property string $director_telephone
 * @property string $director_mobile
 * @property string $director_email
 * @property string $director_qq
 * @property string $contact_name
 * @property string $contact_post
 * @property string $contact_telephone
 * @property string $contact_mobile
 * @property string $contact_email
 * @property string $contact_qq
 * @property string $keeper_name
 * @property string $keeper_post
 * @property string $keeper_telephone
 * @property string $keeper_mobile
 * @property string $keeper_email
 * @property string $keeper_qq
 * @property string $note
 * @property string $vip_id
 * @property integer $is_del
 * @property string $operating_company_id
 */
class CustomerCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_company}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vip_id', 'is_del', 'operating_company_id','type','classify1_id','classify2_id','level'], 'integer'],
            [['number', 'reg_number', 'corporate_name', 'corporate_email', 'director_name', 'director_email', 'contact_name', 'contact_email', 'keeper_name', 'keeper_email','account_name','bank_account','account_opening'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 32],
            [['company_name', 'company_addr', 'company_brief', 'note'], 'string', 'max' => 255],
            [['company_lng', 'company_lat', 'director_telephone', 'contact_telephone', 'keeper_telephone'], 'string', 'max' => 20],
            [['corporate_post', 'corporate_telephone', 'corporate_qq', 'director_post', 'director_qq', 'contact_post', 'contact_qq', 'keeper_post', 'keeper_qq'], 'string', 'max' => 30],
            [['corporate_mobile', 'director_mobile', 'contact_mobile', 'keeper_mobile'], 'string', 'max' => 11]
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
            'password' => '客户密码',
            'company_name' => '公司名称',
            'reg_number' => '营业执照注册号',
            'company_addr' => '公司地址',
            'company_lng' => '经度',
            'company_lat' => '纬度',
            'company_brief' => '公司简介',
            'corporate_name' => '法人代表姓名',
            'corporate_post' => '法人代表职务',
            'corporate_telephone' => '法人代表座机',
            'corporate_mobile' => '法人代表手机',
            'corporate_email' => '法人代表邮箱',
            'corporate_qq' => '法人代表QQ',
            'director_name' => '负责人姓名',
            'director_post' => '负责人职务',
            'director_telephone' => '负责人座机',
            'director_mobile' => '负责人手机',
            'director_email' => '负责人邮箱',
            'director_qq' => '负责人QQ',
            'contact_name' => '联系人姓名',
            'contact_post' => '联系人职务',
            'contact_telephone' => '联系人座机',
            'contact_mobile' => '联系人手机',
            'contact_email' => '联系人邮箱',
            'contact_qq' => '联系人QQ',
            'keeper_name' => '车辆管理人姓名',
            'keeper_post' => '车辆管理人职务',
            'keeper_telephone' => '车辆管理人座机',
            'keeper_mobile' => '车辆管理人手机',
            'keeper_email' => '车辆管理人邮箱',
            'keeper_qq' => '车辆管理人QQ',
            'note' => '备注',
            'vip_id' => '关联会员id',
            'is_del' => '删除标记',
            'operating_company_id' => '所属运营公司ID',
            'account_name' => '开户名',
            'bank_account' => '开户银行',
            'account_opening' => '开户帐号',
            'type' => '客户类型',
            'classify1_id' => '客户类型一级分类ID',
            'classify2_id' => '客户类型二级分类ID',
            'level' => '客户等级',
        ];
    }
}
