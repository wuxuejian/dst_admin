<?php
/**
 * 车辆二级维护记录模型
 * time    2015/10/16 10:29
 * @author wangmin
 */
namespace backend\models;
use yii\db\ActiveRecord;
class CarSecondMaintenance extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%car_second_maintenance}}';
    }

    public function getCar()
    {
        return $this->hasOne(Car::className(),[
            'id'=>'car_id'
        ]);
    }

    public function getAdmin(){
        return $this->hasOne(Admin::className(),[
            'id'=>'add_aid'
        ]);
    }

    public function attributeLabels()
    {
        return [
            'car_id'=>'所属于车辆',
            'number'=>'维护卡编号',
            'current_date'=>'本次维护时间',
            'next_date'=>'下次维护时间'
        ];
    }

    public function rules()
    {
        $attributeLabels = $this->attributeLabels();
        return [
            ['car_id','required','message'=>$attributeLabels['car_id'].'不能为空！'],
            ['car_id','checkCarId'],
            ['number','required','message'=>$attributeLabels['number'].'不能为空！'],
            ['number','match','pattern'=>'/^\d{0,100}$/','message'=>$attributeLabels['number'].'只能是数值类型长度不能超过100位！'],
            ['current_date','required','message'=>$attributeLabels['current_date'].'不能为空！'],
            ['current_date','match','pattern'=>'/^\d{4}(-\d{2}){2}$/','message'=>$attributeLabels['current_date'].'非法的日期格式！'],
            ['next_date','required','message'=>$attributeLabels['next_date'].'不能为空！'],
            ['next_date','match','pattern'=>'/^\d{4}(-\d{2}){2}$/','message'=>$attributeLabels['next_date'].'非法的日期格式！'],
            [['current_date','next_date'],'filter','filter'=>'strtotime']
        ];
    }

    public function checkCarId($attribute)
    {
        $attributeLabels = $this->attributeLabels();
        if(!Car::find()->select(['id'])->where(['id'=>$this->car_id])->one()){
            $this->addError($attribute,$attributeLabels[$attribute].'非法！');
            return false;
        }
        return true;
    }
}