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
class PurchaseOrderMain extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_purchase_order_main';
    }

     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [           
            [['contract_number'], 'string', 'max' => 100],
            [['order_number'], 'string', 'max' => 100]
        

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contract_number' => 'contract_number',
            'order_number' => 'order_number'
         
        ];
    }
}
