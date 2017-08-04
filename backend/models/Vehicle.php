<?php
/**
 * @Desc:	充电预约单模型 
 * @author: chengwk
 * @date:	2015-10-20
 */
namespace backend\models;

class Vehicle extends \common\models\Vehicle
{
    
    public function rules(){
        $rules = [];
		$rules[] = [['vehicle','vhc_model'],'trim'];
		$rules[] = [['vehicle','vhc_model'],'filter','filter'=>'htmlspecialchars'];
		$rules[] = ['vehicle','match','pattern'=>'/^[\x{4e00}-\x{9fa5}][A-Z][A-Z\d]{5}$/u','message'=>'车牌号格式错误！'];//PHP里匹配汉字[\u4e00-\u9fa5]不行，要/^[\x{4e00}-\x{9fa5}]+$/u，记得最后也要加上‘u’
		$rules[] = ['vehicle','unique','message'=>'该车牌号已经存在！'];
		return array_merge($rules,parent::rules());
	}

	
}
