<?php
/**
 * 车辆行驶证模型
 * @author wangmin
 */
namespace backend\models;
use yii\db\ActiveRecord;
class CarDrivingLicense extends \common\models\CarDrivingLicense
{

    public function rules(){
        $rules = parent::rules();
        $rules[] = [['addr'],'checkConfig','skipOnEmpty'=>false];
        return $rules;
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

    public function checkCarId($attribute)
    {
        $attributeLabels = $this->attributeLabels();
        if(!Car::find()->select(['id'])->where(['id'=>$this->car_id])->one()){
            $this->addError($attribute,$attributeLabels[$attribute].'非法！');
            return false;
        }
        return true;
    }

    public function checkConfig($attribute){
        $attributeLabels = $this->attributeLabels();
        if(empty($this->$attribute)){
            $this->addError($attribute,$attributeLabels[$attribute].'不能为空！');
            return false;
        }
        //与配置表中的对应关系
        $configRelation = [
            'addr'=>'DL_REG_ADDR',
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