<?php
namespace backend\models;
class CustomerPersonal extends \common\models\CustomerPersonal
{
    public function rules()
    {
        $rules = [
            ['number','unique','message'=>'客户号不唯一新重新提交！'],
            [['id_name','id_address','driving_number','driving_addr','driving_issue_authority']
                ,'filter','filter'=>'htmlspecialchars'
            ],
            ['telephone','match','pattern'=>'/^(\d{1,6}-)?\d{2,20}$/','message'=>'固定电话格式错误！'],
            ['mobile','match','pattern'=>'/^1[3578]\d{9}$/','message'=>'移动电话格式错误！'],
            ['qq','match','pattern'=>'/^\d{0,30}$/','message'=>'QQ号码错误！'],
            ['email','email','message'=>'邮箱格式错误！'],
            ['id_number','match','pattern'=>'/^(\d{14}|\d{17})[Xx\d]$/','message'=>'身份证号码错误！'],
            ['id_sex','in','range'=>[0,1],'message'=>'性别错误！'],
            ['driving_class','checkDrivingClass','skipOnEmpty'=>false],
            [['driving_issue_date','driving_valid_from'],'filter','filter'=>'strtotime','skipOnEmpty'=>true],
            [['driving_issue_date','driving_valid_from'],'default','value'=>0],
            [['driving_valid_for'],'default','value'=>0],
			[['personal_lng','personal_lat'],'double'],
			[['personal_lng','personal_lat'],'default','value'=>'']
        ];
        return array_merge($rules,parent::rules());
    }

    public function checkDrivingClass()
    {
        if(empty($this->driving_class)){
            $this->addError('driving_class','请选择准驾车型！');
            return false;
        }
        $category = ConfigCategory::find()->select(['id'])->where(['key'=>'driv_class'])->asArray()->one();
        if(!ConfigItem::find()->select(['id'])->where(['belongs_id'=>$category['id'],'value'=>$this->driving_class])->one()){
            $this->addError('driving_class','准驾车型错误！');
            return false;
        }
        return true;
    }


    /**
     * 关联【运营公司】
     */
    public function getOperatingCompany()
    {
        return $this->hasOne(OperatingCompany::className(),[
            'id'=>'operating_company_id',
        ]);
    }


    /*
     * 检测是否要根据当前登录人员所属运营公司来显示列表数据-20160328
     * 注：个人客户列表、选择客户combogrid等使用。
     * @$isStrictlyLimited: 是否严格按所属运营公司来限制数据的显示。
     * 当参数为false时，地上铁人员也能看其他运营公司客户数据，如协议基本信息列表；
     * 当参数为true时，包括地上铁在内各运营公司都只能看自己的数据，如选择客户combogrid。
     */
    public static function isLimitedToShowByAdminOperatingCompany($isStrictlyLimited=false){
        $arr = [];
        $super = $_SESSION['backend']['adminInfo']['super'];
        $username = $_SESSION['backend']['adminInfo']['username'];
        //除了开发人员和超级管理员不受管控外，其他人必须检测
        if(!$super && $username != 'administrator'){
            $adminInfo_operatingCompanyIds = $_SESSION['backend']['adminInfo']['operating_company_ids'];
            if($isStrictlyLimited){
                $arr = ['status'=>true,'adminInfo_operatingCompanyId'=>$adminInfo_operatingCompanyIds];
            }else{
                //除了地上铁人员外，只能查看匹配运营公司的数据
                if(!$adminInfo_operatingCompanyIds){
            		$adminInfo_operatingCompanyIds = 10000;
            	}
            	$arr = ['status'=>true,'adminInfo_operatingCompanyId'=>$adminInfo_operatingCompanyIds];
            }
        }
        return $arr;
    }


    /*
     * 检查当前登录用户和要操作的个人客户的所属运营公司是否匹配-20160328
     * 注：修改/删除个人客户等管控操作时使用。
     */
    public static function checkOperatingCompanyIsMatch($customerId){
        $arr = [];
        $super = $_SESSION['backend']['adminInfo']['super'];
        $username = $_SESSION['backend']['adminInfo']['username'];
        //除了开发人员和超级管理员不受管控外，其他人必须检测
        if(!$super && $username != 'administrator'){
            $adminInfo_operatingCompanyId = $_SESSION['backend']['adminInfo']['operating_company_id'];
            $customer = CustomerPersonal::find()->select(['operating_company_id'])->where(['id'=>$customerId,'is_del'=>0])->asArray()->one();
            //若登录用户所属运营公司不等于客户所属运营公司
            if($adminInfo_operatingCompanyId != $customer['operating_company_id']){
                $OperatingCompany = OperatingCompany::find()
                    ->select(['name'])
                    ->where(['id'=>$adminInfo_operatingCompanyId])
                    ->asArray()->one();
                if($OperatingCompany){
                    $arr = ['status'=>false,'info'=>"对不起，您只能操作【{$OperatingCompany['name']}】运营公司的客户！"];
                }else{
                    $arr = ['status'=>false,'info'=>"对不起，您与该客户的所属运营公司不匹配，不允许操作！"];
                }
            }
        }
        return $arr;
    }




}