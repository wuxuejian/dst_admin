<?php
/*
 * 运营公司 模型
 */
namespace backend\models;
class OperatingCompany extends \common\models\OperatingCompany{
    public function rules(){
        $rules = [
            [['name','addr','note'],'trim'],
            [['name','addr','note'],'filter','filter'=>'htmlspecialchars'],
            ['name','unique','message'=>'运营公司名称重复！'],
        ];
        return array_merge($rules,parent::rules());
    }

    /*
     * 获取运营公司
     */
    static function getOperatingCompany(){
        $res = OperatingCompany::find()
            ->select(['id','pid','name'])
            ->where(['is_del'=>0])
            ->indexBy('id')
            ->asArray()->all();
        return $res;
    }

}