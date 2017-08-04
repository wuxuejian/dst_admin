<?php
namespace backend\models;
class CustomerCompany extends \common\models\CustomerCompany
{
    public function rules(){
        $rules = [];
        $rules[] = ['number','unique','message'=>'客户号不唯一新重新提交！'];
        //$rules[] = ['reg_number','unique','message'=>'注册号已经存在，请重新提交！'];
        $rules[] = [['reg_number','company_addr','company_brief','corporate_name','corporate_post','corporate_telephone','corporate_mobile','corporate_email','corporate_qq','director_name','director_post','director_telephone','director_mobile','director_email','director_qq','contact_name','contact_post','contact_telephone','contact_mobile','contact_email','contact_qq','keeper_name','keeper_post','keeper_telephone','keeper_mobile','keeper_email','keeper_qq','note'],'filter','filter'=>'htmlspecialchars'];
        return array_merge($rules,parent::rules());
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
     * 注：企业客户列表、选择客户combogrid等使用。
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
     * 检查当前登录用户和要操作的企业客户的所属运营公司是否匹配-20160328
     * 注：修改/删除企业客户等管控操作时使用。
     */
    public static function checkOperatingCompanyIsMatch($customerId){
        $arr = [];
        $super = $_SESSION['backend']['adminInfo']['super'];
        $username = $_SESSION['backend']['adminInfo']['username'];
        //除了开发人员和超级管理员不受管控外，其他人必须检测
        if(!$super && $username != 'administrator'){
            $adminInfo_operatingCompanyId = $_SESSION['backend']['adminInfo']['operating_company_id'];
            $customer = CustomerCompany::find()->select(['operating_company_id'])->where(['id'=>$customerId,'is_del'=>0])->asArray()->one();
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