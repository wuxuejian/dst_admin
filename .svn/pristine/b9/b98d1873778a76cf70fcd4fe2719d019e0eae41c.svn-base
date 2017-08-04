<?php
/**
 * @Desc:	会员建议 模型 
 * @author: chengwk
 * @date:	2015-12-07
 */
namespace backend\models;

class VipSuggestion extends \common\models\VipSuggestion
{

    public function rules(){
		$rules = [];
		//xss跨站攻击
		$rules[] = [
			[
				'vs_title','vs_content','vs_respond_txt'
			],'filter','filter'=>'htmlspecialchars'
		];
		return array_merge($rules,parent::rules());
    }
    
	public function getVip(){
		return $this->hasOne(Vip::className(),['id'=>'vs_vip_id']);
	}

	public function getAdmin(){
		return $this->hasOne(Admin::className(),['id'=>'vs_responder_id']);
	}

	
}
