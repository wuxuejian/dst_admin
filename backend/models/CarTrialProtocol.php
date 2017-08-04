<?php
/**
 * 车辆试用协议 模型
 */
namespace backend\models;
class CarTrialProtocol extends \common\models\CarTrialProtocol
{

	/**
     * 关联【企业客户】
     */
    public function getCustomerCompany()
    {
        return $this->hasOne(CustomerCompany::className(),['id'=>'ctp_cCustomer_id']);
    }
	
	/**
     * 关联【个人客户】
     */
    public function getCustomerPersonal()
    {
        return $this->hasOne(CustomerPersonal::className(),['id'=>'ctp_pCustomer_id']);
    }

    public function getAdmin(){
        return $this->hasOne(Admin::className(),[
            'id'=>'ctp_modify_aid'
        ]);
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

    public function rules()
    {
        $rules = [
		    [['ctp_number','ctp_note'],'filter','filter'=>'htmlspecialchars'],
            ['ctp_number','required','message'=>'协议编号不能为空！'],
            ['ctp_number','unique','message'=>'协议编号不能重复！'],
			[['ctp_car_nums','ctp_cCustomer_id','ctp_pCustomer_id'],'default','value'=>'0'],
        ];
		return array_merge($rules,parent::rules());
    }


    /*
     * 检测是否要根据当前登录人员所属运营公司来显示列表数据-20160326
     * 注：企业/个人试用协议列表、选择协议combogrid等使用。
     * @$isStrictlyLimited: 是否严格按所属运营公司来限制数据的显示。
     * 当参数为false时，地上铁人员也能看其他运营公司协议数据，如协议基本信息列表；
     * 当参数为true时，包括地上铁在内各运营公司都只能看自己的数据，如选择协议combogrid。
     */
    public static function isLimitedToShowByAdminOperatingCompany($isStrictlyLimited=false){
        $arr = [];
        //显示当前登录用户对应运营公司的协议-20160325
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
     * 检查当前登录用户和要操作的协议的所属运营公司是否匹配-20160326
     * 注：修改/删除协议等协议管控操作时使用。
     */
    public static function checkOperatingCompanyIsMatch($protocolId){
        $arr = [];
        $super = $_SESSION['backend']['adminInfo']['super'];
        $username = $_SESSION['backend']['adminInfo']['username'];
        //除了开发人员和超级管理员不受管控外，其他人必须检测
        if(!$super && $username != 'administrator'){
            $adminInfo_operatingCompanyId = $_SESSION['backend']['adminInfo']['operating_company_id'];
            $protocol = carTrialProtocol::find()->select(['operating_company_id'])->where(['ctp_id'=>$protocolId,'ctp_is_del'=>0])->asArray()->one();
            //若登录用户所属运营公司不等于协议所属运营公司
            if($adminInfo_operatingCompanyId != $protocol['operating_company_id']){
                $OperatingCompany = OperatingCompany::find()
                    ->select(['name'])
                    ->where(['id'=>$adminInfo_operatingCompanyId])
                    ->asArray()->one();
                if($OperatingCompany){
                    $arr = ['status'=>false,'info'=>"对不起，您只能操作【{$OperatingCompany['name']}】运营公司的协议！"];
                }else{
                    $arr = ['status'=>false,'info'=>"对不起，您与该协议的所属运营公司不匹配，不允许操作！"];
                }
            }
        }
        return $arr;
    }



}