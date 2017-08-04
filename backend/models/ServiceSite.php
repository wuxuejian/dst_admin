<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cs_maintain_record".
 *
 * @property integer $id
 * @property integer $car_id
 * @property string $add_time
 * @property double $driving_mileage
 * @property string $maintenance_shop
 * @property string $amount
 * @property integer $type
 */
class ServiceSite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_service_site';
    }

     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [           
           
            [['site_name'], 'string', 'max' => 100]
        

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'site_name' => 'Site Name'          
         
        ];
    }
}
