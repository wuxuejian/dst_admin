<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car_anomaly_detection_deal}}".
 *
 * @property integer $id
 * @property string $cad_id
 * @property string $status
 * @property string $deal_way
 * @property string $deal_date
 * @property string $reg_aid
 * @property integer $is_del
 */
class CarAnomalyDetectionDeal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_anomaly_detection_deal}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cad_id', 'reg_aid', 'is_del'], 'integer'],
            [['status'], 'string'],
            [['deal_date'], 'safe'],
            [['deal_way'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cad_id' => 'Cad ID',
            'status' => 'Status',
            'deal_way' => 'Deal Way',
            'deal_date' => 'Deal Date',
            'reg_aid' => 'Reg Aid',
            'is_del' => 'Is Del',
        ];
    }
}
