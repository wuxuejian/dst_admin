<?php
namespace backend\models;
class ChargeCardSwap extends \common\models\ChargeCardSwap
{
    public function rules()
    {
        $rules = [
            ['note','filter','filter'=>'htmlspecialchars']
        ];
        return array_merge($rules,parent::rules());
    }

    //关联管理员
    public function getAdmin(){
        return $this->hasOne(Admin::className(),[
            'id'=>'aaid'
        ]);
    }

    //关联充电卡
    public function getChargeCard(){
        return $this->hasOne(ChargeCard::className(),[
            'cc_id'=>'cc_id'
        ]);
    }
}
