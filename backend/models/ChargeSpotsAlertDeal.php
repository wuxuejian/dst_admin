<?php

namespace backend\models;
class ChargeSpotsAlertDeal extends \common\models\ChargeSpotsAlertDeal
{
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(),['id'=>'reg_aid']);
    } 
}
