<?php

namespace backend\models;
class ChargeSpotsAlert extends \common\models\ChargeSpotsAlert
{
    public function getChargeStation()
    {
        return $this->hasOne(ChargeStation::className(),[
            'cs_id'=>'station_id',
        ]);
    }
}
