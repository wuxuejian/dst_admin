<?php
namespace backend\models;
/**
 * 汽车故障模型
 * @author wangmin
 *
 */
class CarFault extends \common\models\CarFault
{

    public function getCar()
    {
        return $this->hasOne(Car::className(),['id'=>'car_id']);
    }

    public function getAdmin(){
        return $this->hasOne(Admin::className(),['id'=>'register_aid']);
    }

    //关联【出租合同】
    public function getCarLetContract()
    {
        return $this->hasOne(CarLetContract::className(),['id'=>'contract_id']);
    }
    //关联【试用协议】
    public function getCarTrialProtocol()
    {
        return $this->hasOne(CarTrialProtocol::className(),['ctp_id'=>'protocol_id']);
    }
    //关联【企业客户】
    public function getCustomerCompany()
    {
        return $this->hasOne(CustomerCompany::className(),['id'=>'cCustomer_id']);
    }
    //关联【个人客户】
    public function getCustomerPersonal()
    {
        return $this->hasOne(CustomerPersonal::className(),['id'=>'pCustomer_id']);
    }

    public function rules()
    {
        $rules = [];
        $rules[] = ['car_id','checkCarId','skipOnEmpty'=>false];
        $rules[] = [['fault_status'],'checkConfig','skipOnEmpty'=>false];
        $rules[] = [['f_place','fb_name','fb_mobile','f_desc','f_reason','ap_name','fzr_name','fzr_mobile','repair_order_no','f_dispose'],'trim'];
        $rules[] = [['f_place','fb_name','fb_mobile','f_desc','f_reason','ap_name','fzr_name','fzr_mobile','repair_order_no','f_dispose'],'filter','filter'=>'htmlspecialchars'];
        return array_merge($rules,parent::rules());
    }

    public function checkCarId()
    {
        if(empty($this->car_id)){
            $this->addError('car_id','登记车辆不能为空！');
            return false;
        }
        if(!Car::find()->select(['id'])->where(['id'=>$this->car_id])->one()){
            $this->addError('car_id','登记车辆错误！');
            return false; 
        }
        return true;
    }

    /**
     * 验证配置项是否正确
     */
    public function checkConfig($attribute){
        $attributeLabels = $this->attributeLabels();
        if(empty($this->$attribute)){
            $this->addError($attribute,$attributeLabels[$attribute].'不能为空！');
            return false;
        }
        //与配置表中的对应关系
        $configRelation = [
            'fault_status'=>'fault_status',
        ];
        $key = $configRelation[$attribute];
        $configCategory = ConfigCategory::find()->select(['id'])->where(['key'=>$key])->asArray()->one();
        $configItem = ConfigItem::find()
                      ->select(['id'])
                      ->where(['belongs_id'=>$configCategory['id'],'value'=>$this->$attribute])
                      ->one();
        if(!$configItem){
            $this->addError($attribute,$attributeLabels[$attribute].'不是有效的值！');
            return false;
        }
        return true;
    }
}