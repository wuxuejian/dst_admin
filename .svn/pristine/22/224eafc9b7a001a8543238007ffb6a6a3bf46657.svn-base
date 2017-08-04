<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%tcp_car_exception}}".
 *
 * @property string $car_vin
 * @property string $data_source
 * @property string $alert_type
 * @property string $ecu_module
 * @property string $content
 * @property integer $level
 * @property string $collection_datetime
 * @property string $update_datetime
 */
class TcpCarException extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tcp_car_exception}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db1');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data_source'], 'string'],
            [['level', 'collection_datetime', 'update_datetime'], 'integer'],
            [['car_vin'], 'string', 'max' => 17],
            [['alert_type'], 'string', 'max' => 100],
            [['ecu_module', 'content'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'car_vin' => 'Car Vin',
            'data_source' => 'Data Source',
            'alert_type' => 'Alert Type',
            'ecu_module' => 'Ecu Module',
            'content' => 'Content',
            'level' => 'Level',
            'collection_datetime' => 'Collection Datetime',
            'update_datetime' => 'Update Datetime',
        ];
    }
}
