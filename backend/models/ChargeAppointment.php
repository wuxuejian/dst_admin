<?php
/**
 * @Desc:	充电预约单模型 
 * @author: chengwk
 * @date:	2015-11-07
 */
namespace backend\models;
use yii\db\ActiveRecord;
use backend\models\Vip;

class ChargeAppointment extends ActiveRecord
{
    public $mobile = ''; // 声明一个属性mobile

    public static function tableName()
    {
        return '{{%charge_appointment}}';
    }

    public function rules()
    {
        return [	
			['mobile','trim'],
			['mobile','checkIsExisted'],
            
			['chargerid','required','message'=>'你还没有选择电桩！'],			
            ['chargerid','filter','filter'=>'intval' ],			
				
			['appointed_date','match','pattern'=>'/^\d{4}-\d{2}-\d{2}$/','message'=>'预约日期非法（格式：YYYY-mm-dd）！'],
            [
				['time_start','time_end'],
				'match','pattern'=>'/^\d{2}:\d{2}$/',
				'message'=>'起始或截止时间非法（格式：HH:ii）！'
			],
			
			['mark','string']
		];
    }
	
	function checkIsExisted($attribute,$params){
		$_mobile = $this->$attribute; // 注意mobile属性是在顶部声明的，而不是预约记录表里的字段。
		$vip = Vip::findOne(['mobile'=>$_mobile]);
        if(!$vip){
            $this->addError($attribute,'当前手机号还未注册成会员,无法预约！');
            return false;
        }
        $this->vip_id = $vip->id; // 为vip_id赋值
        return true;
	}
	
	
	//关联电桩表
    public function getCharger()
    {
        return $this->hasOne(ChargeSpots::className(), ['id' => 'chargerid']);
    }

    //关联会员表
    public function getVip()
    {
        return $this->hasOne(Vip::className(), ['id' => 'vip_id']);
    }
	
	
	
	
}
