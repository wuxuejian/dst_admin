<?php

namespace backend\models;
class CarAnomalyDetectionDeal extends \common\models\CarAnomalyDetectionDeal
{
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(),['id'=>'reg_aid']);
    } 
}
