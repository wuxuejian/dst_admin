<?php
namespace backend\models;
use yii\db\ActiveRecord;
class CarAttachment extends ActiveRecord
{
    public $attachment = '';
    public static function tableName()
    {
        return '{{%car_attachment}}';
    }
    
    public function rules()
    {
        return [
            ['attachment','file','extensions' => 'png,jpg,zip,jpeg,gif,avi','maxSize'=>1024*1024*100],
            ['name','filter','filter'=>'htmlspecialchars'],
            ['carId','checkCarId']
        ];
    }
    public function scenarios(){
        return [
            'default'=>['*'],
            'add'=>['attachment','name','carId','upload_time']
        ];
    }
    public function checkCarId()
    {
        if(Car::find()->select(['id'])->one()){
            return true;
        }
        $this->addError('carId','carId error');
        return false;
    }
}