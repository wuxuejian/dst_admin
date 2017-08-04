<?php
/**
 * 车辆出租合同续费记录模型
 * time:2015/10/13 17:20
 * @author wangmin
 */
namespace backend\models;
use yii\db\ActiveRecord;
class CarLetContractRenewRecord extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%car_let_contract_renew_record}}';
    }

    // 关联出租合同
    public function getCarLetContract()
    {
        return $this->hasOne(CarLetContract::className(),['id'=>'contract_id']);
    }


    public function getAdmin()
    {
        return $this->hasOne(Admin::className(),['id'=>'admin_id']);
    }

    public function rules()
    {
        return [
            ['contract_id','checkContractId','skipOnEmpty'=>false],
            ['true_money','required','message'=>'实收金额不能为空！'],
            ['true_money','match','pattern'=>'/^\d{1,7}(\.\d{1,2})?$/','message'=>'实收金额格式错误！'],
            ['cost_expire_time','checkCostExprieTime','skipOnEmpty'=>false],
            ['note','filter','filter'=>'htmlspecialchars'],
            ['note','string','length'=>[0,255],'tooLong'=>'备注长度超出！']
        ];
    }

    public function checkContractId()
    {
        if(empty($this->contract_id)){
            $this->addError('contract_id','合同参数丢失！');
            return false;
        }
        $contract = CarLetContract::find()->select(['id'])->where(['id'=>$this->contract_id])->one();
        if(!$contract){
            $this->addError('contract_id','合同错误！');
            return false;
        }
        return true;
    }

    public function checkCostExprieTime()
    {
        if(empty($this->cost_expire_time)){
            $this->addError('cost_expire_time','续费到期时间不能为空！');
            return false;
        }
        if(!preg_match('/^20\d{2}(-\d{2}){2}$/',$this->cost_expire_time)){
            $this->addError('cost_expire_time','续费到期时间格式错误！');
            return false;
        }
        $this->cost_expire_time = strtotime($this->cost_expire_time);
        if($this->cost_expire_time <= time()){
            $this->addError('cost_expire_time','无法将续费到期时间设置在一个过去的时间！');
            return false;
        }
        return true;
    }
}