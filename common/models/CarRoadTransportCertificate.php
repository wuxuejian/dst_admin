<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%car_road_transport_certificate}}".
 *
 * @property string $id
 * @property string $car_id
 * @property string $ton_or_seat
 * @property string $issuing_organ
 * @property string $rtc_province
 * @property string $rtc_city
 * @property string $rtc_number
 * @property string $issuing_date
 * @property string $last_annual_verification_date
 * @property string $next_annual_verification_date
 */
class CarRoadTransportCertificate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%car_road_transport_certificate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['car_id', 'issuing_date', 'last_annual_verification_date', 'next_annual_verification_date'], 'integer'],
            [['ton_or_seat'], 'string', 'max' => 10],
            [['issuing_organ'], 'string', 'max' => 100],
            [['rtc_province', 'rtc_city'], 'string', 'max' => 5],
            [['rtc_number'], 'string', 'max' => 50],
            [['car_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_id' => 'Car ID',
            'ton_or_seat' => 'Ton Or Seat',
            'issuing_organ' => 'Issuing Organ',
            'rtc_province' => 'Rtc Province',
            'rtc_city' => 'Rtc City',
            'rtc_number' => 'Rtc Number',
            'issuing_date' => 'Issuing Date',
            'last_annual_verification_date' => 'Last Annual Verification Date',
            'next_annual_verification_date' => 'Next Annual Verification Date',
        ];
    }
}
