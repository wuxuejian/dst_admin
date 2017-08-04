<?php

namespace backend\models;
class CarMoniExceptionCondition extends \common\models\CarMoniExceptionCondition
{
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(),[
            'id'=>'add_uid',
        ]);
    }
}
