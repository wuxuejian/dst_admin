<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car_anomaly_shotmessage_rule}}".
 *
 * @property string $id
 * @property string $wd_start_time
 * @property string $wd_end_time
 * @property string $wd_mobile
 * @property string $hd_start_time
 * @property string $hd_end_time
 * @property string $hd_mobile
 */
class CarAnomalyShotmessageRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_anomaly_shotmessage_rule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wd_mobile', 'hd_mobile'], 'string'],
            [['wd_start_time', 'wd_end_time', 'hd_start_time', 'hd_end_time'], 'string', 'max' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wd_start_time' => 'Wd Start Time',
            'wd_end_time' => 'Wd End Time',
            'wd_mobile' => 'Wd Mobile',
            'hd_start_time' => 'Hd Start Time',
            'hd_end_time' => 'Hd End Time',
            'hd_mobile' => 'Hd Mobile',
        ];
    }
}
