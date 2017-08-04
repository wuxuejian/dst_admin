<?php
namespace backend\models;
use yii\db\ActiveRecord;
class CarLetContract extends ActiveRecord
{
	
    public static function tableName()
    {
        return '{{%car_let_contract}}';
    }
    
    /**
     * 关联【出租记录】
     */
    public function getCarLetRecord()
    {
    	return $this->hasMany(CarLetRecord::className(), ['contract_id' => 'id'])->onCondition(['{{%car_let_record}}.is_del' => 0]);
    }

	/**
     * 关联【企业客户】
     */
    public function getCustomerCompany()
    {
        return $this->hasOne(CustomerCompany::className(),['id'=>'cCustomer_id']);
    }
	
	/**
     * 关联【个人客户】
     */
    public function getCustomerPersonal()
    {
        return $this->hasOne(CustomerPersonal::className(),['id'=>'pCustomer_id']);
    }

    public function getAdmin(){
        return $this->hasOne(Admin::className(),[
            'id'=>'modify_aid'
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
        return [
            ['number','required','message'=>'合同编号不能为空！'],
            ['number','filter','filter'=>'htmlspecialchars'],
            ['number','string','length'=>[0,100],'tooLong'=>'合同编号长度超出！'],
            ['salesperson','string','length'=>[0,50],'tooLong'=>'归属销售员长度超出！'],
            ['number','unique','message'=>'合同编号不能重复！'],
            ['start_time','required','message'=>'开始时间不能为空！'],
            ['start_time','match','pattern'=>'/^20\d{2}(-\d{2}){2}$/','message'=>'开始时间格式错误！'],
            ['end_time','required','message'=>'结束时间不能为空！'],
            ['end_time','match','pattern'=>'/^20\d{2}(-\d{2}){2}$/','message'=>'结束时间格式错误！'],
            ['due_time','required','message'=>'合同期限不能为空！'],
            ['due_time','match','pattern'=>'/^20\d{2}(-\d{2}){2}$/','message'=>'合同期限格式错误！'],
            ['bail','default','value'=>'0.00'],
            ['bail','match','pattern'=>'/^\d{1,7}(\.\d{1,2})?$/','message'=>'保证金格式错误！'],
            ['note','filter','filter'=>'htmlspecialchars'],
            ['note','string','length'=>[0,255],'tooLong'=>'备注长度超出！'],
            ['sign_date','required','message'=>'合同签订时间不能为空！'],
            ['sign_date','match','pattern'=>'/^20\d{2}(-\d{2}){2}$/','message'=>'合同签订时间格式错误！'],
            [['start_time','end_time','due_time','sign_date'],'filter','filter'=>'strtotime'],
			[['cCustomer_id','pCustomer_id'],'default','value'=>'0'],
        ];
    }

    public function scenarios()
    {
        return [
            'default'=>[
                'number','cCustomer_id','pCustomer_id','start_time','end_time','due_time','bail','note','sign_date'
            ],
            'edit'=>[
                'number','cCustomer_id','pCustomer_id','start_time','end_time','due_time','bail','note','sign_date','salesperson'
            ],
        ];
    }


    /*
     * 检测是否要根据当前登录人员所属运营公司来显示列表数据-20160326
     * 注：企业/个人客户合同列表、选择合同combogrid等使用。
     * @$isStrictlyLimited: 是否严格按所属运营公司来限制数据的显示。
     * 当参数为false时，地上铁人员也能看其他运营公司合同数据，如合同基本信息列表；
     * 当参数为true时，包括地上铁在内各运营公司都只能看自己的数据，如选择合同combogrid。
     */
    public static function isLimitedToShowByAdminOperatingCompany($isStrictlyLimited=false){
        $arr = [];
        //显示当前登录用户对应运营公司的合同-20160325
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
     * 检查当前登录用户和要操作的合同的所属运营公司是否匹配-20160326
     * 注：修改/删除合同等合同管控操作时使用。
     */
    public static function checkOperatingCompanyIsMatch($contractId){
        $arr = [];
        $super = $_SESSION['backend']['adminInfo']['super'];
        $username = $_SESSION['backend']['adminInfo']['username'];
        //除了开发人员和超级管理员不受管控外，其他人必须检测
        if(!$super && $username != 'administrator'){
            $adminInfo_operatingCompanyId = $_SESSION['backend']['adminInfo']['operating_company_id'];
            $contract = CarLetContract::find()->select(['operating_company_id'])->where(['id'=>$contractId,'is_del'=>0])->asArray()->one();
            //若登录用户所属运营公司不等于合同所属运营公司
            if($adminInfo_operatingCompanyId != $contract['operating_company_id']){
                $OperatingCompany = OperatingCompany::find()
                    ->select(['name'])
                    ->where(['id'=>$adminInfo_operatingCompanyId])
                    ->asArray()->one();
                if($OperatingCompany){
                    $arr = ['status'=>false,'info'=>"对不起，您只能操作【{$OperatingCompany['name']}】运营公司的合同！"];
                }else{
                    $arr = ['status'=>false,'info'=>"对不起，您与该合同的所属运营公司不匹配，不允许操作！"];
                }
            }
        }
        return $arr;
    }



}