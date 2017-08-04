<?php
namespace backend\models;
class CarBrand extends \common\models\CarBrand{
    public function rules(){
        $rules = [
            [['name','code','note'],'filter','filter'=>'htmlspecialchars'],
            ['name','unique','message'=>'品牌名称重复！'],
        ];
        return array_merge($rules,parent::rules());
    }

    /*
     * 获取车辆品牌
     */
    static function getCarBrands(){
        $res = CarBrand::find()
            ->select(['id','pid','name'])
            ->where(['is_del'=>0])
            ->indexBy('id')
            ->asArray()->all();
        return $res;
    }

}