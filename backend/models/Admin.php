<?php
namespace backend\models;
use yii\db\ActiveRecord;
class Admin extends ActiveRecord
{
    public $verifyCode;
    public static function tableName()
    {
        return "cs_admin";
    }

    /**
     * 与【部门】表关系
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::className(),[
            'id'=>'department_id',
        ]);
    }

    /**
     * 与【运营公司】表关系
     */
    public function getOperatingCompany()
    {
        return $this->hasOne(OperatingCompany::className(),[
            'id'=>'operating_company_id',
        ]);
    }

    public function rules()
    {
        return [
            [
                'verifyCode','captcha','message'=>'验证码错误！',
                'on'=>'login'//登陆
            ],
            [
                'username','required','message'=>'用户名不能为空！',
                'on'=>['add','login']//登陆-添加
            ],
            [
                'username','unique','message'=>'用户名已经存在！',
                'on'=>'add'//添加
            ],
            [
                'username','match','pattern'=>'/^\w{6,20}$/','message'=>'用户名格式错误！',
                'on'=>['add','login']//登陆-添加
            ],
            [
                'password','match','pattern'=>'/^\w{6,20}$/',
                'message'=>'密码格式错误！',
                'on'=>['add','login','reset-password']//登陆-添加
            ],
            [
                'name','filter','filter'=>'htmlspecialchars',
                'on'=>['add','edit']//添加-修改
            ],
            [
                'name','string','length'=>[0,10],
                'tooLong'=>'姓名不能超过10个字！',
                'on'=>['add','edit']//添加-修改
            ],
            ['sex','in','range'=>[0,1],'on'=>['add','edit']],
            [
                'email','email','message'=>'邮箱格式错误！',
                'on'=>['add','edit']//添加-修改
            ],
            [
                'telephone','match','pattern'=>'/^1[345678]\d{9}$/',
                'message'=>'手机号码错误！',
                'on'=>['add','edit']//添加-修改
            ],
            [
                'qq','match','pattern'=>'/^\d{6,40}$/','message'=>'QQ号码错误！',
                'on'=>['add','edit']//添加-修改
            ],
            [
                'department_id','filter','filter'=>'intval',
                'on'=>['add','edit']//添加-修改
            ],
            [
                'department_id','checkDepartment',
                'on'=>['add','edit']//添加-修改
            ],
            [
                'operating_company_id','filter','filter'=>'intval',
                'on'=>['add','edit']//添加-修改
            ],
            [
                'repair_company','required','message'=>'用户类型不能为空！',
                'on'=>['add','edit']//登陆-添加
            ],
           
        ];
    }

    public function checkDepartment()
    {
        if(!empty($this->department_id)){
            $department = Department::find()
                          ->where(['id'=>$this->department_id])
                          ->one();
            if($department){
                return true;
            }
        }
        $this->addError('department_id','部门错误！');
        return false;
    }

    public function scenarios()
    {
        return [
            'default'=>['*'],
            'login'=>['username','password','verifyCode'],
            'add'=>['mac_pass','username','password','name','sex','email','telephone','qq','department_id','operating_company_id','operating_company_ids','repair_company'],
            'edit'=>['mac_pass','name','sex','email','telephone','qq','department_id','operating_company_id','operating_company_ids','repair_company'],
            'reset-password'=>['password']
        ];
    }
    
}