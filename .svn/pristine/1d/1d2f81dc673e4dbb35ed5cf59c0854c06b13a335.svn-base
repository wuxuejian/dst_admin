<?php
/**
 * 车辆道路运输证模型
 * @author wangmin
 */
namespace backend\models;
use yii\db\ActiveRecord;
class CarRoadTransportCertificate extends \common\models\CarRoadTransportCertificate
{

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


    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            [
                'ton_or_seat','issuing_organ','rtc_province',
                'rtc_city','rtc_number'
            ],'filter','filter'=>'htmlspecialchars'
        ];
        $rules[] = ['car_id','checkCarId','skipOnEmpty'=>false];
        $rules[] = [['issuing_organ'],'checkConfig','skipOnEmpty'=>false];
        return $rules;
    }

    public function checkCarId($attribute)
    {
        $attributeLabels = $this->attributeLabels();
        if(empty($this->$attribute)){
            $this->addError($attribute,$attributeLabels[$attribute].'不能为空！');
        }
        if(!Car::find()->select(['id'])->where(['id'=>$this->car_id])->one()){
            $this->addError($attribute,$attributeLabels[$attribute].'非法！');
            return false;
        }
        return true;
    }

    /**
     * 验证配置项是否正确
     */
    public function checkConfig($attribute){
        $attributeLabels = $this->attributeLabels();
        if(empty($this->$attribute)){
            $this->addError($attribute,$attributeLabels[$attribute].'不能为空！');
            return false;
        }
        //与配置表中的对应关系
        $configRelation = [
            'issuing_organ'=>'TC_ISSUED_BY',
        ];
        $key = $configRelation[$attribute];
        $configCategory = ConfigCategory::find()->select(['id'])->where(['key'=>$key])->asArray()->one();
        $configItem = ConfigItem::find()
                      ->select(['id'])
                      ->where(['belongs_id'=>$configCategory['id'],'value'=>$this->$attribute])
                      ->one();
        if(!$configItem){
            $this->addError($attribute,$attributeLabels[$attribute].'不是有效的值！');
            return false;
        }
        return true;
    }
}