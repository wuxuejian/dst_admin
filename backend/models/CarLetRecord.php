<?php
namespace backend\models;
use yii\db\ActiveRecord;
class CarLetRecord extends ActiveRecord
{
    public $plate_number = '';
    public static function tableName()
    {
        return '{{%car_let_record}}';
    }

    public function getCar()
    {
        return $this->hasOne(Car::className(),[
            'id'=>'car_id'
        ]);
    }

    public function getLetContract()
    {
        return $this->hasOne(CarLetContract::className(),[
            'id'=>'contract_id'
        ]);
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

    public function rules()
    {
        return [
            ['contract_id','checkContractId','skipOnEmpty'=>false],
            ['plate_number','checkPlateNumber','skipOnEmpty'=>false],
            ['month_rent','default','value'=>'0.00'],
            ['month_rent','match','pattern'=>'/^\d{0,7}(\.\d{1,2})?$/','message'=>'月租金格式有错！'],
            ['let_time','filter','filter'=>'strtotime'],
            ['note','filter','filter'=>'htmlspecialchars'],
            ['note','string','length'=>[0,255],'tooLong'=>'备注长度操出！'],
			[['cCustomer_id','pCustomer_id'],'default','value'=>'0'],
        ];
    }

    public function scenarios()
    {
        return [
            'default'=>['contract_id','plate_number','month_rent','let_time','note'],
            'edit'=>['let_time','month_rent','note']
        ];
    }

    public function checkContractId()
    {
        if(empty($this->contract_id)){
            $this->addError('contract_id','合同错误！');
            return false;
        }
        $contract = CarLetContract::find()->select(['id'])->where(['id'=>$this->contract_id])->one();
        if(!$contract){
            $this->addError('contract_id','合同错误！');
            return false;
        }
        return true;
    }

    public function checkPlateNumber()
    {
        //添加车辆到合同是要验证该车辆是否可用
        if(empty($this->plate_number)){
            $this->addError('plate_number','车牌号不能为空！');
            return false;
        }
        $car = Car::find()->select(['id'])
               ->where(['plate_number'=>$this->plate_number])
              // ->andWhere(['car_status'=>'STOCK'])
               ->asArray()
               ->one();
        if(!$car){
            $this->addError('plate_number','车辆错误！');
            return false;
        }
        //检测车辆是否有未归还记录
        $noBackRecord = CarLetRecord::find()
            ->where(['car_id'=>$car['id'],'back_time'=>0])->one();
        if($noBackRecord){
            $this->addError('plate_number','车辆：'.$this->plate_number.'，有未归还记录请先“归还”车辆！');
            return false;
        }
        $this->car_id = $car['id'];
        return true;
    }

}