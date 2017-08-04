<?php
/**
 * 车辆测试数据管理模型
 * @author wangmin
 */
namespace backend\models;
use yii\db\ActiveRecord;
class CarTest extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%car_test}}';
    }

    public function getCar()
    {
        return $this->hasOne(Car::className(),[
            'id'=>'car_id'
        ]);
    }

    public function attributeLabels()
    {
        return [
            'car_id'=>'所属车辆',
            'mileage'=>'测试里程',
            'use_hour'=>'测试用时小时数',
            'use_minute'=>'测试用时分钟数',
            'slow_recharge_status'=>'慢充充电状态',
            'fast_recharge_status'=>'快充充电状态'
        ];
    }

    public function rules()
    {
        $als = $this->attributeLabels();
        return [
            ['car_id','required','message'=>$als['car_id'].'不能为空！'],
            ['car_id','checkCarId'],
            ['mileage','required','message'=>$als['mileage'].'不能为空！'],
            ['mileage','match','pattern'=>'/^\d+(\.\d{1})?$/','message'=>$als['mileage'].'格式（格式：88.8）错误！'],
            ['use_hour','required','message'=>$als['use_hour'].'不能为空！'],
            ['use_hour','match','pattern'=>'/^[0-9](\d+)?$/','message'=>$als['use_hour'].'只能是整型值可以为0！'],
            ['use_minute','required','message'=>$als['use_minute'].'不能为空！'],
            ['use_minute','match','pattern'=>'/^[012345]\d?$/','message'=>$als['use_minute'].'只能是小于60的整型值可以为0！'],
            [['slow_recharge_status','fast_recharge_status'],'filter','filter'=>'htmlspecialchars'],
            ['slow_recharge_status','string','length'=>[0,255],'message'=>$als['slow_recharge_status'].'不能超过255个字！'],
            ['fast_recharge_status','string','length'=>[0,255],'message'=>$als['fast_recharge_status'].'不能超过255个字！']
        ];
    }

    public function checkCarId($attribute)
    {
        //状态为库存并且删标记为0
        $attributeLabels = $this->attributeLabels();
        $car = Car::find()->select(['id'])
                ->where(['id'=>$this->car_id,'is_del'=>0])
                ->one();
        if(!$car){
            $this->addError($attribute,$attributeLabels[$attribute].'非法！');
            return false;
        }
        return true;
    }
}