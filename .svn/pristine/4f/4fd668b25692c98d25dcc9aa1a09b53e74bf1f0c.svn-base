<?php
/**
 *	车辆试用协议明细表 模型
 */
namespace backend\models;
class CarTrialProtocolDetails extends \common\models\CarTrialProtocolDetails
{
    public $plate_number = '';

    public function getCarTrialProtocol()
    {
        return $this->hasOne(CarTrialProtocol::className(),[
            'ctp_id'=>'ctpd_protocol_id'
        ]);
    }

	/**
     * 关联【企业客户】
     */
    public function getCustomerCompany()
    {
        return $this->hasOne(CustomerCompany::className(),['id'=>'ctpd_cCustomer_id']);
    }
	
	/**
     * 关联【个人客户】
     */
    public function getCustomerPersonal()
    {
        return $this->hasOne(CustomerPersonal::className(),['id'=>'ctpd_pCustomer_id']);
    }

	public function getCar()
    {
        return $this->hasOne(Car::className(),[
            'id'=>'ctpd_car_id'
        ]);
    }

    public function rules()
    {
        $rules =  [
			[['ctpd_cCustomer_id','ctpd_pCustomer_id'],'default','value'=>'0'],
        ];
		return array_merge($rules,parent::rules());
    }
}