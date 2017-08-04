<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%vip_promotion_sign}}".
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
 * @property string $open_id
 * @property string $invite_code_mine
 * @property string $invite_code_used
 * @property string $company
 * @property string $profession
 * @property string $district
 * @property integer $is_lock
 */
class VipPromotionSign extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vip_promotion_sign}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sex', 'systime', 'is_del', 'sm_reqtime', 'is_lock'], 'integer'],
            [['money_acount'], 'number'],
            [['code', 'client', 'app_ver', 'district'], 'string', 'max' => 30],
            [['mobile'], 'string', 'max' => 11],
            [['password'], 'string', 'max' => 32],
            [['email', 'sysuser', 'open_id', 'company', 'profession'], 'string', 'max' => 50],
            [['mark'], 'string', 'max' => 200],
            [['shot_message_code'], 'string', 'max' => 6],
            [['sm_number'], 'string', 'max' => 3],
            [['invite_code_mine', 'invite_code_used'], 'string', 'max' => 8],
            [['mobile'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '会员ID',
            'code' => '会员编号',
            'client' => '会员名称',
            'mobile' => '手机号',
            'password' => '登录密码',
            'sex' => '性别',
            'email' => '邮箱',
            'mark' => '备注',
            'app_ver' => '当前所用App版本号',
            'systime' => '系统时间',
            'sysuser' => '操作人员',
            'is_del' => '是否已删除',
            'money_acount' => '账号总金额',
            'shot_message_code' => '短信验证码',
            'sm_reqtime' => '短消息请求时间',
            'sm_number' => '短消息序号',
            'open_id' => '微信openid',
            'invite_code_mine' => '生成的我的邀请码（8位字母+数字）',
            'invite_code_used' => '使用的他人的邀请码',
            'company' => '就职公司',
            'profession' => '职业',
            'district' => '所在区域',
            'is_lock' => '是否锁定',
        ];
    }
}
