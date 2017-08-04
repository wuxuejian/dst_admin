<?php

namespace backend\models;
class ChargeSpotsAlertShotmessageRule extends \common\models\ChargeSpotsAlertShotmessageRule
{
    public function rules()
    {
        $rules = [
            [['wd_start_time','wd_end_time','wd_mobile','hd_start_time','hd_end_time','hd_mobile'],'filter','filter'=>'htmlspecialchars'],
        ];
        return array_merge($rules,parent::rules());
    }
}
