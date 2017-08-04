<?php
/**
 * 其它险模型
 * time    2014/10/16 12:04
 * @author pengyl
 */
namespace backend\models;
class CarInsuranceOther extends \common\models\CarInsuranceOther
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
        $rules = [];
        $rules[] = ['car_id','checkCarId','skipOnEmpty'=>false];
        $rules[] = [['insurer_company'],'checkConfig','skipOnEmpty'=>false];
        $rules[] = [['start_date','end_date'],'filter','filter'=>'strtotime'];
        return array_merge($rules,parent::rules());
    }

    public function checkCarId($attribute)
    {
        $attributeLabels = $this->attributeLabels();
        if(empty($this->$attribute)){
            $this->addError($attribute,$attributeLabels[$attribute].'不能为空！');
            return false;
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
            'insurer_company'=>'INSURANCE_COMPANY',
        ];
        $key = $configRelation[$attribute];
        $configCategory = ConfigCategory::find()->select(['id'])->where(['key'=>$key])->asArray()->one();
        $configItem = ConfigItem::find()
                      ->select(['id'])
                      ->where(['belongs_id'=>$configCategory['id'],'value'=>$this->$attribute])
                      ->one();
        if(!$configItem){
            $this->addError($attribute,$attributeLabels[$attribute].'不是有效的配置项！');
            return false;
        }
        return true;
    }
}