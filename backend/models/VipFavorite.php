<?php
/**
 * @Desc:	我的收藏记录 模型 
 * @author: chengwk
 * @date:	2015-11-24
 */
namespace backend\models;
use yii\db\ActiveRecord;
use backend\models\Vip;

class VipFavorite extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%vip_favorite}}';
    }

    public function rules()
    {
        return [

		];
    }
	
	function checkIsExisted($attribute,$params){
		$_mobile = $this->$attribute;
		$vip = Vip::findOne(['mobile'=>$_mobile]);
        if(!$vip){
            $this->addError($attribute,'当前手机号还未注册,无法收藏！');
            return false;
        }
        return true;
	}
	
	
	//关联电桩表
    public function getChargeStation()
    {
        return $this->hasOne(ChargeStation::className(), ['cs_id' => 'chargerid']);
    }
	
	//关联会员表
    public function getVip()
    {
        return $this->hasOne(Vip::className(), ['id' => 'vip_id']);
    }
	
	
	
	
}
