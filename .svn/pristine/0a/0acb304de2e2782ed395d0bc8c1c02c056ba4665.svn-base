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
class Mac extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_mac';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [          
            [['admin_id'], 'integer'],          
            [['add_aid'], 'integer'],          
            [['note'], 'string', 'max' => 100],
			[['add_time'], 'safe'],
            [['mac'], 'string', 'max' => 100]
		
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mac' => 'mac',
            'admin_id' => 'admin_id',
           
        ];
    }
}
