<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cs_car_insurance_claim".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $date
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $area_id
 * @property string $addr
 * @property string $informant_name
 * @property string $informant_tel
 * @property string $result
 * @property string $append1_urls
 * @property string $insurance1_text
 * @property string $insurance2_text
 * @property string $insurance3_text
 * @property string $servicing_note
 * @property double $damage_amount
 * @property double $claim_amount
 * @property string $finance_text
 * @property double $transfer_amount
 */
class CarInsuranceClaim extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cs_car_insurance_claim';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'date', 'province_id', 'city_id', 'area_id'], 'integer'],
            [['damage_amount', 'claim_amount', 'transfer_amount'], 'number'],
            [['addr'], 'string', 'max' => 100],
            [['informant_name'], 'string', 'max' => 50],
            [['informant_tel'], 'string', 'max' => 20],
            [['result', 'servicing_note'], 'string', 'max' => 200],
            [['append1_urls', 'insurance1_text', 'insurance2_text', 'insurance3_text', 'finance_text'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'date' => 'Date',
            'province_id' => 'Province ID',
            'city_id' => 'City ID',
            'area_id' => 'Area ID',
            'addr' => 'Addr',
            'informant_name' => 'Informant Name',
            'informant_tel' => 'Informant Tel',
            'result' => 'Result',
            'append1_urls' => 'Append1 Urls',
            'insurance1_text' => 'Insurance1 Text',
            'insurance2_text' => 'Insurance2 Text',
            'insurance3_text' => 'Insurance3 Text',
            'servicing_note' => 'Servicing Note',
            'damage_amount' => 'Damage Amount',
            'claim_amount' => 'Claim Amount',
            'finance_text' => 'Finance Text',
            'transfer_amount' => 'Transfer Amount',
        ];
    }
}
